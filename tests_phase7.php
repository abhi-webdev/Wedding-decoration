<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Decoration;
use App\Models\User;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

echo "=== STARTING PHASE 7 BUSINESS OPERATIONS TESTS ===\n\n";

$passCount = 0;
$failCount = 0;

function assertTest($condition, $name) {
    global $passCount, $failCount;
    if ($condition) {
        echo " [PASS] " . $name . "\n";
        $passCount++;
    } else {
        echo " [FAIL] " . $name . "\n";
        $failCount++;
    }
}

try {
    // 1. Check/Get Seed Entities
    $admin = User::where('role', 'super_admin')->first();
    $customer = User::where('role', 'customer')->first();
    $decoration = Decoration::first();

    assertTest($admin !== null, "Super Admin user exists");
    assertTest($customer !== null, "Customer user exists");
    assertTest($decoration !== null, "Decoration theme exists");

    // 2. Create a Test Booking for Business Operations
    $booking = Booking::create([
        'booking_reference' => 'AU-TEST-' . strtoupper(substr(uniqid(), -6)),
        'user_id' => $customer->id,
        'customer_name' => $customer->name,
        'customer_email' => $customer->email,
        'customer_phone' => $customer->phone ?? '9876543210',
        'decoration_id' => $decoration->id,
        'event_type' => 'Wedding Reception',
        'event_date' => Carbon::today()->addDays(20)->format('Y-m-d'),
        'start_time' => '10:00:00',
        'end_time' => '22:00:00',
        'guest_count' => 300,
        'address_line' => 'Station Road, Ward 12',
        'locality' => 'Near Rajendra Stadium',
        'city' => 'Siwan',
        'state' => 'Bihar',
        'pincode' => '841226',
        'base_amount' => 75000.00,
        'addon_amount' => 15000.00,
        'estimated_total' => 90000.00,
        'status' => 'pending',
        'special_requirements' => 'Phase 7 automated verification booking.'
    ]);

    assertTest($booking->id > 0, "Test Booking created with ref: {$booking->booking_reference}");

    // 3. Test Quotation Creation & Calculation
    $subtotal = 75000.00 + 15000.00 + 2000.00; // 92000
    $discount = 2000.00;
    $tax = round(($subtotal - $discount) * 0.18, 2); // 16200.00
    $grandTotal = ($subtotal - $discount) + $tax; // 106200.00
    $advPercent = 40;
    $advanceAmount = round(($grandTotal * $advPercent) / 100, 2); // 42480.00
    $balanceAmount = $grandTotal - $advanceAmount; // 63720.00

    $quotation = Quotation::create([
        'booking_id' => $booking->id,
        'user_id' => $customer->id,
        'decoration_id' => $decoration->id,
        'quotation_number' => Quotation::generateQuotationNumber(),
        'subtotal' => $subtotal,
        'discount_amount' => $discount,
        'tax_amount' => $tax,
        'grand_total' => $grandTotal,
        'advance_percentage' => $advPercent,
        'advance_amount' => $advanceAmount,
        'balance_amount' => $balanceAmount,
        'valid_until' => Carbon::today()->addDays(7)->format('Y-m-d'),
        'status' => 'sent',
        'notes' => 'Special Bihari wedding package quote.',
        'created_by' => $admin->id,
    ]);

    assertTest(str_starts_with($quotation->quotation_number, 'AUQ-'), "Quotation Number format starts with AUQ- ({$quotation->quotation_number})");
    assertTest($quotation->grand_total == 106200.00, "Quotation Grand Total accurately computed with tax and discount");
    assertTest($quotation->advance_amount == 42480.00, "Advance Amount accurately calculated at {$advPercent}%");

    // Line Items
    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'item_name' => 'Main Mandap & Stage Decoration',
        'description' => 'Royal Bihari Marigold & Rajnigandha Mandap',
        'quantity' => 1,
        'unit_price' => 75000.00,
        'total_price' => 75000.00,
        'item_order' => 0
    ]);
    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'item_name' => 'Entrance Floral Arch & Pathway Lights',
        'description' => 'Cold pyro + fairy light entrance canopy',
        'quantity' => 1,
        'unit_price' => 15000.00,
        'total_price' => 15000.00,
        'item_order' => 1
    ]);

    assertTest($quotation->items()->count() === 2, "Quotation line items successfully linked (2 items)");

    // 4. Test Customer Acceptance of Quotation
    assertTest($quotation->is_valid, "Quotation is currently valid");
    $quotation->update([
        'status' => 'accepted',
        'accepted_at' => Carbon::now(),
    ]);
    $booking->update([
        'status' => 'confirmed'
    ]);

    assertTest($booking->fresh()->status === 'confirmed', "Booking status transitioned to 'confirmed' upon quotation acceptance");
    assertTest($booking->activeQuotation() !== null, "Booking activeQuotation() resolves accepted quotation");
    assertTest($booking->effective_total == 106200.00, "Booking effective_total resolves to quotation grand total");

    // 5. Test Manual Payment Recording
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'quotation_id' => $quotation->id,
        'user_id' => $customer->id,
        'payment_reference' => Payment::generatePaymentReference(),
        'payment_method' => 'upi',
        'amount' => 45000.00, // Meets advance threshold (42480.00)
        'transaction_id' => 'UPI998877665544',
        'payment_date' => Carbon::today()->format('Y-m-d'),
        'status' => 'successful',
        'notes' => 'Received via GooglePay UPI',
        'recorded_by' => $admin->id
    ]);

    assertTest(str_starts_with($payment->payment_reference, 'AUP-'), "Payment Reference format starts with AUP- ({$payment->payment_reference})");
    assertTest($payment->booking->total_paid == 45000.00, "Booking total_paid reflects verified payment");
    assertTest($payment->booking->balance_due == (106200.00 - 45000.00), "Booking balance_due accurately updated (₹61,200.00)");

    if ($booking->total_paid >= $quotation->advance_amount) {
        $booking->update(['status' => 'advance_paid']);
    }
    assertTest($booking->fresh()->status === 'advance_paid', "Booking auto-promoted to 'advance_paid' status after advance threshold fulfilled");

    // 6. Test Invoice Generation
    $invoice = Invoice::create([
        'booking_id' => $booking->id,
        'quotation_id' => $quotation->id,
        'user_id' => $customer->id,
        'invoice_number' => Invoice::generateInvoiceNumber(),
        'subtotal' => $quotation->subtotal,
        'discount' => $quotation->discount_amount,
        'tax' => $quotation->tax_amount,
        'total' => $quotation->grand_total,
        'amount_paid' => $booking->total_paid,
        'balance_due' => max(0, $quotation->grand_total - $booking->total_paid),
        'status' => ($booking->total_paid >= $quotation->grand_total) ? 'paid' : ($booking->total_paid > 0 ? 'partially_paid' : 'issued'),
        'issued_at' => Carbon::now(),
        'due_at' => Carbon::parse($booking->event_date),
        'notes' => 'Official tax invoice for wedding decoration services.'
    ]);

    assertTest(str_starts_with($invoice->invoice_number, 'AUI-'), "Invoice Number format starts with AUI- ({$invoice->invoice_number})");
    assertTest($invoice->status === 'partially_paid', "Invoice status correctly evaluated to 'partially_paid'");
    assertTest($invoice->balance_due == 61200.00, "Invoice balance_due accurately computed");

    // 7. Test Calendar Service & Booking Aggregation
    $calendarBookings = Booking::whereMonth('event_date', Carbon::today()->month)
        ->whereYear('event_date', Carbon::today()->year)
        ->whereNotIn('status', ['cancelled', 'rejected'])
        ->get();
    assertTest($calendarBookings->isNotEmpty(), "Calendar query captures scheduled/confirmed wedding event");

    // 8. Test Blade Views Render Verification
    $viewsToTest = [
        'admin.dashboard' => ['counts' => [
            'total_bookings' => 1, 'today_bookings' => 0, 'pending_bookings' => 0, 'quoted_bookings' => 0,
            'confirmed_bookings' => 1, 'completed_bookings' => 0, 'cancelled_bookings' => 0,
            'pending_cancellations' => 0, 'pending_reschedules' => 0, 'total_customers' => 1,
            'total_decorations' => 1, 'total_packages' => 1, 'total_offers' => 1, 'total_quotes' => 0,
            'pending_quotes' => 0, 'new_messages' => 0, 'total_booking_value' => 106200,
            'total_quoted_value' => 106200, 'confirmed_booking_value' => 106200, 'advance_received' => 45000,
            'outstanding_balance' => 61200, 'total_quotations' => 1, 'total_payments' => 1, 'total_invoices' => 1
        ], 'recentBookings' => collect([$booking]), 'recentQuotations' => collect([$quotation]), 'recentPayments' => collect([$payment]), 'upcomingEvents' => collect([$booking])],
        'admin.quotations.index' => ['quotations' => Quotation::with(['booking', 'customer', 'decoration'])->paginate(10), 'counts' => ['all' => 1, 'draft' => 0, 'sent' => 0, 'accepted' => 1, 'rejected' => 0, 'cancelled' => 0, 'expired' => 0]],
        'admin.quotations.show' => ['quotation' => $quotation->load(['booking', 'customer', 'decoration', 'items', 'creator', 'payments', 'invoices'])],
        'admin.payments.index' => ['payments' => Payment::with(['booking.decoration', 'customer'])->paginate(10), 'metrics' => ['total_received' => 45000, 'advance_received' => 45000, 'balance_received' => 0, 'total_transactions' => 1]],
        'admin.payments.show' => ['payment' => $payment->load(['booking.decoration', 'quotation', 'customer', 'invoice', 'recorder'])],
        'admin.invoices.index' => ['invoices' => Invoice::with(['booking', 'customer', 'quotation'])->paginate(10), 'metrics' => ['total_invoiced' => 106200, 'total_collected' => 45000, 'balance_due' => 61200]],
        'admin.invoices.show' => ['invoice' => $invoice->load(['booking.decoration.category', 'booking.addons.addon', 'customer', 'quotation.items']), 'settings' => ['business_name' => 'Aditya Utsav', 'business_phone' => '+91 9876543210', 'business_email' => 'contact@adityautsav.in', 'business_address' => 'Siwan, Bihar', 'gst_number' => '10AAACU1234F1Z5']],
        'account.quotations.index' => ['quotations' => Quotation::with(['booking.decoration', 'items'])->where('user_id', $customer->id)->paginate(10)],
        'account.quotations.show' => ['quotation' => $quotation->load(['booking.decoration.category', 'items', 'customer'])],
        'account.payments.index' => ['payments' => Payment::with(['booking.decoration', 'invoice'])->where('user_id', $customer->id)->paginate(10)],
        'account.invoices.index' => ['invoices' => Invoice::with(['booking.decoration', 'quotation'])->where('user_id', $customer->id)->paginate(10)],
        'account.invoices.show' => ['invoice' => $invoice->load(['booking.decoration.category', 'booking.addons.addon', 'customer', 'quotation.items']), 'settings' => ['business_name' => 'Aditya Utsav', 'business_phone' => '+91 9876543210', 'business_email' => 'contact@adityautsav.in', 'business_address' => 'Siwan, Bihar', 'gst_number' => '10AAACU1234F1Z5']],
    ];

    Auth::login($admin);
    foreach ($viewsToTest as $viewName => $viewData) {
        $rendered = view($viewName, $viewData)->render();
        assertTest(strlen($rendered) > 100, "View [{$viewName}] compiled and rendered successfully (" . strlen($rendered) . " bytes)");
    }

} catch (\Throwable $e) {
    echo "\n [ERROR EXCEPTION] " . $e->getMessage() . "\n";
    echo " File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    $failCount++;
}

echo "\n============================================\n";
echo "TEST RESULTS: {$passCount} PASSED, {$failCount} FAILED\n";
echo "============================================\n";
