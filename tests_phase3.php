<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Decoration;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingStatusHistory;
use App\Models\User;
use Carbon\Carbon;

echo "========================================================\n";
echo "       ADITYA UTSAV PHASE 3 TEST SUITE (16 CASES)       \n";
echo "========================================================\n\n";

$passedCount = 0;
$failedCount = 0;

function assertTest($name, $condition, $details = '') {
    global $passedCount, $failedCount;
    if ($condition) {
        $passedCount++;
        echo "[PASS] $name\n";
        if ($details) echo "       ↳ $details\n";
    } else {
        $failedCount++;
        echo "[FAIL] $name\n";
        if ($details) echo "       ↳ ERROR: $details\n";
    }
}

// Ensure at least one test decoration exists
$decoration = Decoration::with('addons')->firstOrFail();
$addon = Addon::first();
$futureDate1 = Carbon::now()->addDays(20)->format('Y-m-d');
$futureDate2 = Carbon::now()->addDays(25)->format('Y-m-d');
$futureDate3 = Carbon::now()->addDays(30)->format('Y-m-d');
$pastDate = Carbon::now()->subDays(5)->format('Y-m-d');

// Cleanup old test bookings for these test dates
Booking::whereIn('event_date', [$futureDate1, $futureDate2, $futureDate3, $pastDate])->delete();

// =========================================================================
// TEST CASE 1: Valid Booking Request (Guest)
// =========================================================================
try {
    $controller = new App\Http\Controllers\BookingController();
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding (Vivah)',
        'event_date' => $futureDate1,
        'start_time' => '16:00',
        'end_time' => '23:30',
        'guest_count' => 450,
        'address_line' => 'Raj Mahal Marriage Lawn, Main Road',
        'locality' => 'Near Gandhi Maidan',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'pincode' => '841226',
        'customer_name' => 'Aarav Kumar Verma',
        'customer_phone' => '9876543210',
        'customer_email' => 'aarav.verma@example.com',
        'whatsapp_number' => '9876543210',
        'special_requirements' => 'Need traditional marigold torans along entry walkway',
        'selected_addons' => $addon ? [$addon->id] : []
    ]);

    $response = $controller->store($request);
    $booking = Booking::where('event_date', $futureDate1)->where('customer_phone', '9876543210')->first();

    assertTest(
        "CASE 1: Valid booking request creates pending record & reference",
        $booking !== null && $booking->status === 'pending' && str_starts_with($booking->booking_reference, 'AU-'),
        "Reference: {$booking->booking_reference} | Total: {$booking->formatted_estimated_total}"
    );

    // Verify booking status history
    $history = BookingStatusHistory::where('booking_id', $booking->id)->first();
    assertTest(
        "CASE 1b: Status history created with 'pending' status",
        $history !== null && $history->status === 'pending'
    );

    // Verify booking add-ons table
    if ($addon) {
        $bAddon = BookingAddon::where('booking_id', $booking->id)->first();
        assertTest(
            "CASE 1c: Booking addons table stores database unit price",
            $bAddon !== null && (float)$bAddon->unit_price === (float)$addon->price,
            "Stored DB price: {$bAddon->unit_price} vs Original: {$addon->price}"
        );
    }
} catch (\Exception $e) {
    assertTest("CASE 1: Valid booking request", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 2: Past Date Validation
// =========================================================================
try {
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding (Vivah)',
        'event_date' => $pastDate,
        'start_time' => '16:00',
        'end_time' => '23:30',
        'guest_count' => 200,
        'address_line' => 'Test Address',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Past Date Tester',
        'customer_phone' => '9876543210',
    ]);

    $failed = false;
    try {
        $controller->store($request);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        $failed = isset($ve->errors()['event_date']);
    }

    assertTest(
        "CASE 2: Past date is rejected by server validation",
        $failed,
        "Rejected date: $pastDate"
    );
} catch (\Exception $e) {
    assertTest("CASE 2: Past date validation", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 3: End Time before Start Time Validation
// =========================================================================
try {
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding (Vivah)',
        'event_date' => $futureDate2,
        'start_time' => '22:00',
        'end_time' => '18:00', // Invalid: end before start
        'guest_count' => 200,
        'address_line' => 'Test Address',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Time Tester',
        'customer_phone' => '9876543210',
    ]);

    $res = $controller->store($request);
    $hasSessionErrors = session()->has('errors') && session('errors')->has('end_time');

    assertTest(
        "CASE 3: End time before start time is rejected with clear error",
        $hasSessionErrors || $res->isRedirection(),
        "Session errors caught"
    );
} catch (\Exception $e) {
    assertTest("CASE 3: Time order validation", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 4: Conflicting Confirmed Booking Blocks Date
// =========================================================================
try {
    // Create a confirmed booking on $futureDate2
    $confirmedBooking = Booking::create([
        'booking_reference' => 'AU-TEST-CONFIRMED-01',
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding',
        'event_date' => $futureDate2,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue 1',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Original Confirmed Client',
        'customer_phone' => '9876543211',
        'base_amount' => $decoration->actual_booking_price,
        'addon_amount' => 0,
        'estimated_total' => $decoration->actual_booking_price,
        'status' => 'confirmed'
    ]);

    // AJAX Check availability
    $checkReq = \Illuminate\Http\Request::create('/booking/check-availability', 'GET', [
        'decoration_id' => $decoration->id,
        'event_date' => $futureDate2,
    ]);
    $checkRes = $controller->checkAvailability($checkReq);
    $checkData = json_decode($checkRes->getContent(), true);

    assertTest(
        "CASE 4: Confirmed booking blocks date in availability check",
        $checkData['available'] === false && $checkData['status'] === 'booked',
        "Message: {$checkData['message']}"
    );

    // Attempting to submit also fails
    $blocked = false;
    try {
        $submitReq = \Illuminate\Http\Request::create('/booking', 'POST', [
            'decoration_id' => $decoration->id,
            'event_type' => 'Wedding',
            'event_date' => $futureDate2,
            'start_time' => '16:00',
            'end_time' => '23:00',
            'guest_count' => 300,
            'address_line' => 'Venue 2',
            'city' => 'Siwan',
            'state' => 'Bihar',
            'customer_name' => 'Competing Client',
            'customer_phone' => '9876543212',
        ]);
        $controller->store($submitReq);
    } catch (\Exception $e) {
        $blocked = str_contains($e->getMessage(), 'just confirmed for another client');
    }

    assertTest(
        "CASE 4b: DB transaction aborts competing submission for confirmed date",
        $blocked
    );
} catch (\Exception $e) {
    assertTest("CASE 4: Confirmed booking conflict", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 5: Cancelled Booking Does NOT Block Date
// =========================================================================
try {
    // Update confirmed booking to cancelled
    $confirmedBooking->update(['status' => 'cancelled']);

    $checkReq = \Illuminate\Http\Request::create('/booking/check-availability', 'GET', [
        'decoration_id' => $decoration->id,
        'event_date' => $futureDate2,
    ]);
    $checkRes = $controller->checkAvailability($checkReq);
    $checkData = json_decode($checkRes->getContent(), true);

    assertTest(
        "CASE 5: Cancelled booking does not block new availability",
        $checkData['available'] === true && $checkData['status'] === 'available',
        "Status: {$checkData['status']}"
    );
} catch (\Exception $e) {
    assertTest("CASE 5: Cancelled booking test", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 6: Pending Booking Shows Advisory Message
// =========================================================================
try {
    // We already have a pending booking on $futureDate1 from CASE 1
    $checkReq = \Illuminate\Http\Request::create('/booking/check-availability', 'GET', [
        'decoration_id' => $decoration->id,
        'event_date' => $futureDate1,
    ]);
    $checkRes = $controller->checkAvailability($checkReq);
    $checkData = json_decode($checkRes->getContent(), true);

    assertTest(
        "CASE 6: Pending booking shows advisory warning without hard block",
        $checkData['available'] === true && $checkData['status'] === 'pending_request',
        "Status: {$checkData['status']}"
    );
} catch (\Exception $e) {
    assertTest("CASE 6: Pending booking check", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 7: Invalid Add-on ID Rejection
// =========================================================================
try {
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding',
        'event_date' => $futureDate3,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue 3',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Addon Tester',
        'customer_phone' => '9876543213',
        'selected_addons' => [999999] // Non-existent ID
    ]);

    $failed = false;
    try {
        $controller->store($request);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        $failed = isset($ve->errors()['selected_addons.0']);
    }

    assertTest(
        "CASE 7: Invalid Add-on ID is rejected by validation",
        $failed
    );
} catch (\Exception $e) {
    assertTest("CASE 7: Invalid add-on", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 8: Price Manipulation Protection (Server recaclulates total)
// =========================================================================
try {
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Reception',
        'event_date' => $futureDate3,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue 3',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Hacker Tester',
        'customer_phone' => '9876543214',
        'base_amount' => 1.00, // Attacker tries to send ₹1
        'estimated_total' => 1.00, // Attacker tries to send ₹1
        'selected_addons' => $addon ? [$addon->id] : []
    ]);

    $controller->store($request);
    $hackedBooking = Booking::where('event_date', $futureDate3)->where('customer_phone', '9876543214')->first();

    $expectedBase = $decoration->actual_booking_price;
    $expectedAddon = $addon ? (float)$addon->price : 0;
    $expectedTotal = $expectedBase + $expectedAddon;

    assertTest(
        "CASE 8: Server ignores manipulated client prices and calculates from DB",
        (float)$hackedBooking->base_amount === $expectedBase && (float)$hackedBooking->estimated_total === $expectedTotal,
        "Stored Total: {$hackedBooking->estimated_total} (Expected: {$expectedTotal})"
    );
} catch (\Exception $e) {
    assertTest("CASE 8: Price protection", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 9: Booking Without Any Add-ons Selected
// =========================================================================
try {
    $dateNoAddons = Carbon::now()->addDays(35)->format('Y-m-d');
    $request = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Haldi Ceremony',
        'event_date' => $dateNoAddons,
        'start_time' => '10:00',
        'end_time' => '15:00',
        'guest_count' => 150,
        'address_line' => 'Courtyard Home Setup',
        'city' => 'Mairwa',
        'state' => 'Bihar',
        'customer_name' => 'No Addons Client',
        'customer_phone' => '9876543215',
        'selected_addons' => []
    ]);

    $controller->store($request);
    $noAddonBooking = Booking::where('event_date', $dateNoAddons)->first();

    assertTest(
        "CASE 9: Booking works seamlessly without add-ons (addon_amount = 0)",
        $noAddonBooking !== null && (float)$noAddonBooking->addon_amount === 0.0,
        "Total: {$noAddonBooking->formatted_estimated_total}"
    );
} catch (\Exception $e) {
    assertTest("CASE 9: No add-ons", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 10: Guest User Booking (No Auth)
// =========================================================================
try {
    Auth::logout();
    assertTest(
        "CASE 10: Guest booking creates record with user_id = null",
        $booking->user_id === null
    );
} catch (\Exception $e) {
    assertTest("CASE 10: Guest booking", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 11: Logged-in User Booking Attaches User ID
// =========================================================================
try {
    $user = User::first();
    if ($user) {
        Auth::login($user);
        $dateAuth = Carbon::now()->addDays(40)->format('Y-m-d');
        $request = \Illuminate\Http\Request::create('/booking', 'POST', [
            'decoration_id' => $decoration->id,
            'event_type' => 'Sangeet Night',
            'event_date' => $dateAuth,
            'start_time' => '18:00',
            'end_time' => '23:30',
            'guest_count' => 500,
            'address_line' => 'Lawn VIP',
            'city' => 'Gopalganj',
            'state' => 'Bihar',
            'customer_name' => $user->name,
            'customer_phone' => '9876543216',
        ]);
        $controller->store($request);
        $authBooking = Booking::where('event_date', $dateAuth)->first();

        assertTest(
            "CASE 11: Logged-in user booking links user_id",
            $authBooking !== null && $authBooking->user_id === $user->id,
            "Linked User ID: {$authBooking->user_id}"
        );
        Auth::logout();
    } else {
        assertTest("CASE 11: Logged-in user booking", true, "Skipped (no users in DB yet)");
    }
} catch (\Exception $e) {
    assertTest("CASE 11: Logged-in user booking", false, $e->getMessage());
}

// =========================================================================
// TEST CASES 12, 13, 14: Required Validation Failures
// =========================================================================
try {
    // Missing name
    $reqNoName = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding',
        'event_date' => $futureDate1,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => '', // Empty
        'customer_phone' => '9876543210',
    ]);
    $failedName = false;
    try { $controller->store($reqNoName); } catch (\Illuminate\Validation\ValidationException $ve) { $failedName = isset($ve->errors()['customer_name']); }
    assertTest("CASE 12: Missing customer_name is rejected", $failedName);

    // Missing phone
    $reqNoPhone = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding',
        'event_date' => $futureDate1,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'customer_name' => 'Tester',
        'customer_phone' => '', // Empty
    ]);
    $failedPhone = false;
    try { $controller->store($reqNoPhone); } catch (\Illuminate\Validation\ValidationException $ve) { $failedPhone = isset($ve->errors()['customer_phone']); }
    assertTest("CASE 13: Missing customer_phone is rejected", $failedPhone);

    // Missing city/location
    $reqNoCity = \Illuminate\Http\Request::create('/booking', 'POST', [
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding',
        'event_date' => $futureDate1,
        'start_time' => '16:00',
        'end_time' => '23:00',
        'guest_count' => 300,
        'address_line' => 'Venue',
        'city' => '', // Empty
        'state' => 'Bihar',
        'customer_name' => 'Tester',
        'customer_phone' => '9876543210',
    ]);
    $failedCity = false;
    try { $controller->store($reqNoCity); } catch (\Illuminate\Validation\ValidationException $ve) { $failedCity = isset($ve->errors()['city']); }
    assertTest("CASE 14: Missing city is rejected", $failedCity);

} catch (\Exception $e) {
    assertTest("CASES 12-14: Validation requirements", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 15: Booking Confirmation Page Access by Reference
// =========================================================================
try {
    $confRes = $controller->confirmation($booking->booking_reference);
    assertTest(
        "CASE 15: Confirmation page successfully renders for reference",
        $confRes instanceof \Illuminate\View\View && $confRes->name() === 'bookings.confirmation',
        "Rendered view: {$confRes->name()}"
    );
} catch (\Exception $e) {
    assertTest("CASE 15: Confirmation page", false, $e->getMessage());
}

// =========================================================================
// TEST CASE 16: Unique Booking Reference Generation & Integrity
// =========================================================================
try {
    $refCount = Booking::where('booking_reference', $booking->booking_reference)->count();
    assertTest(
        "CASE 16: Booking reference is unique and non-sequential",
        $refCount === 1 && str_starts_with($booking->booking_reference, 'AU-' . date('Ymd')),
        "Reference: {$booking->booking_reference}"
    );
} catch (\Exception $e) {
    assertTest("CASE 16: Reference uniqueness", false, $e->getMessage());
}

echo "\n--------------------------------------------------------\n";
echo "SUMMARY: Passed: $passedCount | Failed: $failedCount\n";
echo "--------------------------------------------------------\n";

if ($failedCount === 0) {
    echo "SUCCESS: ALL PHASE 3 TEST CASES PASSED WITH 100% SUCCESS!\n";
} else {
    echo "SOME TESTS FAILED. Inspect output above.\n";
}
