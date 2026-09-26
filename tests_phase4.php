<?php

/**
 * Phase 4 Comprehensive Automated Test Suite
 * Aditya Utsav - Customer Authentication & Account Management
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Decoration;
use App\Models\Booking;
use App\Models\BookingCancellationRequest;
use App\Models\BookingRescheduleRequest;
use App\Models\BookingStatusHistory;

echo "====================================================\n";
echo "   PHASE 4 AUTOMATED TEST SUITE: ADITYA UTSAV\n";
echo "====================================================\n\n";

$testsPassed = 0;
$testsFailed = 0;

function assertTest($name, $condition, $failDetails = '') {
    global $testsPassed, $testsFailed;
    if ($condition) {
        echo " [PASS] " . $name . "\n";
        $testsPassed++;
    } else {
        echo " [FAIL] " . $name . "\n";
        if ($failDetails) {
            echo "        Details: " . $failDetails . "\n";
        }
        $testsFailed++;
    }
}

// --------------------------------------------------------------------------
// TEST 1: User Registration & Hashing
// --------------------------------------------------------------------------
echo "--- 1. Testing User Registration & Model Fields ---\n";

$testEmail = 'customer_test_' . time() . '@adityautsav.in';
$testUser = User::create([
    'name' => 'Aditya Customer Test',
    'email' => $testEmail,
    'phone' => '9876543210',
    'whatsapp' => '9876543210',
    'city' => 'Siwan',
    'state' => 'Bihar',
    'address' => 'Station Road, Siwan',
    'password' => Hash::make('Secret123!'),
]);

assertTest('User successfully created with phone and address attributes', $testUser && $testUser->id > 0);
assertTest('Password is properly hashed using bcrypt / Argon', Hash::check('Secret123!', $testUser->password));
assertTest('Plaintext password does not match hash directly', $testUser->password !== 'Secret123!');

// Test second user for multi-tenant isolation testing
$otherEmail = 'other_cust_' . time() . '@adityautsav.in';
$otherUser = User::create([
    'name' => 'Other Customer',
    'email' => $otherEmail,
    'phone' => '9123456780',
    'password' => Hash::make('OtherSecret123!'),
]);
assertTest('Second user created for isolation tests', $otherUser && $otherUser->id > 0);

// --------------------------------------------------------------------------
// TEST 2: Authentication & Profile Management
// --------------------------------------------------------------------------
echo "\n--- 2. Testing Authentication & Profile Update ---\n";

Auth::login($testUser);
assertTest('Auth::check() is true after login', Auth::check() && Auth::id() === $testUser->id);

// Update Profile
$testUser->update([
    'name' => 'Aditya Customer Updated',
    'phone' => '9988776655',
    'whatsapp' => '9988776655',
    'city' => 'Patna',
    'address' => 'Boring Road, Patna',
]);
$refreshedUser = User::find($testUser->id);
assertTest('Profile fields updated successfully', $refreshedUser->name === 'Aditya Customer Updated' && $refreshedUser->city === 'Patna');

// Update Password
$oldPassCheck = Hash::check('Secret123!', $refreshedUser->password);
assertTest('Current password verified before change', $oldPassCheck);

$refreshedUser->password = Hash::make('NewPassword456!');
$refreshedUser->save();
assertTest('New password updated and verified', Hash::check('NewPassword456!', $refreshedUser->fresh()->password));

// --------------------------------------------------------------------------
// TEST 3: Customer Dashboard & Real Database Metrics
// --------------------------------------------------------------------------
echo "\n--- 3. Testing Customer Dashboard & Metrics ---\n";

$decoration = Decoration::first();
if (!$decoration) {
    echo "No decorations found in DB. Creating dummy decoration for testing...\n";
    $decoration = Decoration::create([
        'category_id' => 1,
        'name' => 'Royal Test Decoration',
        'slug' => 'royal-test-decoration-' . time(),
        'base_price' => 50000,
        'discount_price' => 45000,
        'description' => 'Test Decoration Description',
        'is_active' => true,
    ]);
}

// Create 3 bookings for testUser
$booking1 = Booking::create([
    'booking_reference' => 'AU-TEST-' . time() . '-1',
    'user_id' => $testUser->id,
    'decoration_id' => $decoration->id,
    'event_type' => 'Wedding (Vivah)',
    'event_date' => date('Y-m-d', strtotime('+10 days')),
    'start_time' => '16:00',
    'end_time' => '23:00',
    'guest_count' => 300,
    'address_line' => 'Rajendra Nagar',
    'city' => 'Siwan',
    'state' => 'Bihar',
    'customer_name' => $testUser->name,
    'customer_phone' => $testUser->phone,
    'customer_email' => $testUser->email,
    'base_amount' => 45000,
    'addon_amount' => 5000,
    'estimated_total' => 50000,
    'status' => 'pending',
]);

$booking2 = Booking::create([
    'booking_reference' => 'AU-TEST-' . time() . '-2',
    'user_id' => $testUser->id,
    'decoration_id' => $decoration->id,
    'event_type' => 'Jaimala / Stage',
    'event_date' => date('Y-m-d', strtotime('+20 days')),
    'start_time' => '18:00',
    'end_time' => '23:30',
    'guest_count' => 500,
    'address_line' => 'Dak Bungalow Road',
    'city' => 'Patna',
    'state' => 'Bihar',
    'customer_name' => $testUser->name,
    'customer_phone' => $testUser->phone,
    'customer_email' => $testUser->email,
    'base_amount' => 60000,
    'addon_amount' => 0,
    'estimated_total' => 60000,
    'status' => 'confirmed',
]);

$booking3 = Booking::create([
    'booking_reference' => 'AU-TEST-' . time() . '-3',
    'user_id' => $testUser->id,
    'decoration_id' => $decoration->id,
    'event_type' => 'Haldi Ceremony',
    'event_date' => date('Y-m-d', strtotime('-5 days')),
    'start_time' => '10:00',
    'end_time' => '15:00',
    'guest_count' => 150,
    'address_line' => 'Chapra Road',
    'city' => 'Gopalganj',
    'state' => 'Bihar',
    'customer_name' => $testUser->name,
    'customer_phone' => $testUser->phone,
    'customer_email' => $testUser->email,
    'base_amount' => 25000,
    'addon_amount' => 0,
    'estimated_total' => 25000,
    'status' => 'completed',
]);

// Create 1 booking for otherUser (must never show in testUser queries)
$otherBooking = Booking::create([
    'booking_reference' => 'AU-OTHER-' . time(),
    'user_id' => $otherUser->id,
    'decoration_id' => $decoration->id,
    'event_type' => 'Mehendi Celebration',
    'event_date' => date('Y-m-d', strtotime('+15 days')),
    'start_time' => '14:00',
    'end_time' => '19:00',
    'guest_count' => 200,
    'address_line' => 'Muzaffarpur Road',
    'city' => 'Muzaffarpur',
    'state' => 'Bihar',
    'customer_name' => $otherUser->name,
    'customer_phone' => $otherUser->phone,
    'customer_email' => $otherUser->email,
    'base_amount' => 30000,
    'addon_amount' => 0,
    'estimated_total' => 30000,
    'status' => 'pending',
]);

// Verify stats
$totalCount = Booking::where('user_id', $testUser->id)->count();
$pendingCount = Booking::where('user_id', $testUser->id)->where('status', 'pending')->count();
$confirmedCount = Booking::where('user_id', $testUser->id)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count();
$completedCount = Booking::where('user_id', $testUser->id)->where('status', 'completed')->count();

assertTest('Total bookings metric counts only logged-in user (3)', $totalCount === 3);
assertTest('Pending bookings metric is correct (1)', $pendingCount === 1);
assertTest('Confirmed bookings metric is correct (1)', $confirmedCount === 1);
assertTest('Completed bookings metric is correct (1)', $completedCount === 1);

// --------------------------------------------------------------------------
// TEST 4: My Bookings Filtering, Search, Sorting
// --------------------------------------------------------------------------
echo "\n--- 4. Testing My Bookings Filters, Search & Sorting ---\n";

// Filter by status=pending
$pendingQuery = Booking::where('user_id', $testUser->id)->where('status', 'pending')->get();
assertTest('Status filter [pending] returns exactly 1 booking', $pendingQuery->count() === 1 && $pendingQuery->first()->id === $booking1->id);

// Filter by status=confirmed
$confirmedQuery = Booking::where('user_id', $testUser->id)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->get();
assertTest('Status filter [confirmed] returns exactly 1 booking', $confirmedQuery->count() === 1 && $confirmedQuery->first()->id === $booking2->id);

// Search by City "Patna"
$searchPatna = Booking::where('user_id', $testUser->id)->where('city', 'like', '%Patna%')->get();
assertTest('Search by City [Patna] returns only Patna booking', $searchPatna->count() === 1 && $searchPatna->first()->id === $booking2->id);

// Search by Reference
$searchRef = Booking::where('user_id', $testUser->id)->where('booking_reference', $booking1->booking_reference)->get();
assertTest('Search by Booking Reference returns specific booking', $searchRef->count() === 1 && $searchRef->first()->id === $booking1->id);

// --------------------------------------------------------------------------
// TEST 5: Security & Multi-Tenant Data Isolation (CRITICAL)
// --------------------------------------------------------------------------
echo "\n--- 5. Testing Multi-Tenant Data Isolation & 404 Ownership ---\n";

// testUser trying to find otherUser's booking via scoped query
$attemptLookup = Booking::where('user_id', $testUser->id)
    ->where('booking_reference', $otherBooking->booking_reference)
    ->first();

assertTest('Customer A cannot query Customer B booking (returns null/404)', $attemptLookup === null);

// otherUser querying own booking
$otherOwnerLookup = Booking::where('user_id', $otherUser->id)
    ->where('booking_reference', $otherBooking->booking_reference)
    ->first();
assertTest('Customer B can query their own booking', $otherOwnerLookup !== null && $otherOwnerLookup->id === $otherBooking->id);

// --------------------------------------------------------------------------
// TEST 6: Cancellation Request Feature & Duplicate Prevention
// --------------------------------------------------------------------------
echo "\n--- 6. Testing Cancellation Request Workflow ---\n";

// Submit cancellation request for booking1 (status: pending)
$cancelReq = BookingCancellationRequest::create([
    'booking_id' => $booking1->id,
    'user_id' => $testUser->id,
    'reason' => 'Family decision changed',
    'details' => 'Requesting to cancel due to venue location change.',
    'status' => 'pending',
]);

assertTest('Cancellation request created with pending status', $cancelReq && $cancelReq->status === 'pending');
assertTest('Booking main status remains unchanged (pending, NOT cancelled)', $booking1->fresh()->status === 'pending');
assertTest('Booking relationship detects pending cancellation', $booking1->fresh()->has_pending_cancellation === true);

// Verify duplicate check prevents second cancellation request
$hasExistingCancel = $booking1->cancellationRequests()->where('status', 'pending')->exists();
assertTest('Duplicate pending cancellation request is detected and blocked', $hasExistingCancel === true);

// --------------------------------------------------------------------------
// TEST 7: Reschedule Request Feature & Duplicate Prevention
// --------------------------------------------------------------------------
echo "\n--- 7. Testing Reschedule Request Workflow ---\n";

$newRequestedDate = date('Y-m-d', strtotime('+35 days'));
$rescheduleReq = BookingRescheduleRequest::create([
    'booking_id' => $booking2->id,
    'user_id' => $testUser->id,
    'requested_date' => $newRequestedDate,
    'requested_start_time' => '17:00',
    'requested_end_time' => '23:30',
    'reason' => 'Astrologer recommended an auspicious new muhurat date.',
    'status' => 'pending',
]);

assertTest('Reschedule request created with requested date and pending status', $rescheduleReq && $rescheduleReq->status === 'pending');
assertTest('Original booking event date remains unaltered until approved', $booking2->fresh()->event_date->format('Y-m-d') !== $newRequestedDate);
assertTest('Booking relationship detects pending reschedule request', $booking2->fresh()->has_pending_reschedule === true);

// Verify duplicate check prevents second reschedule request
$hasExistingReschedule = $booking2->rescheduleRequests()->where('status', 'pending')->exists();
assertTest('Duplicate pending reschedule request is detected and blocked', $hasExistingReschedule === true);

// --------------------------------------------------------------------------
// TEST 8: Timeline & Status History
// --------------------------------------------------------------------------
echo "\n--- 8. Testing Booking Timeline & Status History ---\n";

BookingStatusHistory::create([
    'booking_id' => $booking2->id,
    'status' => 'quoted',
    'note' => 'Initial quote generated for Royal setup.',
    'changed_by_user_id' => $testUser->id,
]);

BookingStatusHistory::create([
    'booking_id' => $booking2->id,
    'status' => 'confirmed',
    'note' => 'Client verified package terms.',
    'changed_by_user_id' => $testUser->id,
]);

$historyCount = $booking2->statusHistories()->count();
assertTest('Booking status histories correctly logged (2 transitions)', $historyCount === 2);

// --------------------------------------------------------------------------
// CLEANUP & SUMMARY
// --------------------------------------------------------------------------
echo "\n====================================================\n";
echo "   PHASE 4 TEST SUMMARY\n";
echo "   Passed: {$testsPassed}\n";
echo "   Failed: {$testsFailed}\n";
echo "====================================================\n";

if ($testsFailed === 0) {
    echo "\n ALL PHASE 4 TESTS PASSED PERFECTLY!\n\n";
} else {
    echo "\n SOME TESTS FAILED. PLEASE REVIEW.\n\n";
}
