<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Phase10BusinessOperationsTest extends TestCase
{
    /**
     * Test full quotation creation, calculation, sending, viewing, and customer acceptance.
     */
    public function test_quotation_lifecycle_and_customer_acceptance()
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin_p10_test@adityautsav.test'],
            [
                'name' => 'Admin P10',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'client_p10_test@adityautsav.test'],
            [
                'name' => 'Client P10',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $decoration = Decoration::first();
        $booking = Booking::create([
            'booking_reference' => 'AU-P10-' . rand(1000, 9999),
            'user_id' => $customer->id,
            'decoration_id' => $decoration ? $decoration->id : 1,
            'event_type' => 'Jaimala Ceremony',
            'event_date' => Carbon::today()->addDays(30)->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '23:30',
            'guest_count' => 350,
            'address_line' => 'Siwan Club Road',
            'city' => 'Siwan',
            'state' => 'Bihar',
            'customer_name' => 'Client P10',
            'customer_phone' => '9876543215',
            'customer_email' => 'client_p10_test@adityautsav.test',
            'base_amount' => 60000,
            'addon_amount' => 0,
            'estimated_total' => 60000,
            'status' => 'pending',
        ]);

        // 1. Admin creates quotation with 40% advance requirement
        $response = $this->actingAs($superAdmin)->post('/admin/quotations', [
            'booking_id' => $booking->id,
            'valid_until' => Carbon::today()->addDays(14)->format('Y-m-d'),
            'advance_percentage' => 40,
            'discount_amount' => 5000,
            'additional_charges' => 2000,
            'tax_amount' => 0,
            'items' => [
                [
                    'item_type' => 'decoration',
                    'item_id' => $decoration ? $decoration->id : 1,
                    'description' => 'Royal Jaimala Setup',
                    'quantity' => 1,
                    'unit_price' => 60000,
                    'discount' => 0,
                ],
                [
                    'item_type' => 'custom',
                    'description' => 'Cold Pyro Entry Effect',
                    'quantity' => 2,
                    'unit_price' => 2500,
                    'discount' => 0,
                ]
            ],
            'send_now' => true,
        ]);

        $response->assertRedirect();

        // Verify quotation in database with recalculated totals:
        // Subtotal = 60000 + 5000 = 65000
        // Grand Total = 65000 - 5000 (discount) + 2000 (additional) = 62000
        // Advance Amount = 62000 * 0.40 = 24800
        // Balance Amount = 62000 - 24800 = 37200
        $quotation = Quotation::where('booking_id', $booking->id)->latest()->first();
        $this->assertNotNull($quotation);
        $this->assertEquals(65000, (float) $quotation->subtotal);
        $this->assertEquals(62000, (float) $quotation->grand_total);
        $this->assertEquals(24800, (float) $quotation->advance_amount);
        $this->assertEquals(37200, (float) $quotation->balance_amount);
        $this->assertEquals('sent', $quotation->status);

        // 2. Customer views quotation -> status transitions to 'viewed'
        $viewResponse = $this->actingAs($customer)->get("/account/quotations/{$quotation->id}");
        $viewResponse->assertStatus(200);
        $quotation->refresh();
        $this->assertEquals('viewed', $quotation->status);

        // 3. Customer accepts quotation -> Booking confirmed, approved_at recorded
        $acceptResponse = $this->actingAs($customer)->post("/account/quotations/{$quotation->id}/accept");
        $acceptResponse->assertRedirect();

        $quotation->refresh();
        $booking->refresh();

        $this->assertEquals('accepted', $quotation->status);
        $this->assertNotNull($quotation->approved_at);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals(62000, (float) $booking->estimated_total);

        // 4. Verify Status History was created
        $this->assertDatabaseHas('booking_status_histories', [
            'booking_id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Test payment recording, advance threshold fulfillment, and invoice generation.
     */
    public function test_payment_recording_and_advance_threshold()
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin_p10_pay@adityautsav.test'],
            [
                'name' => 'Admin P10 Pay',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'client_p10_pay@adityautsav.test'],
            [
                'name' => 'Client P10 Pay',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $decoration = Decoration::first();
        $booking = Booking::create([
            'booking_reference' => 'AU-PAY-' . rand(1000, 9999),
            'user_id' => $customer->id,
            'decoration_id' => $decoration ? $decoration->id : 1,
            'event_type' => 'Mandap Wedding',
            'event_date' => Carbon::today()->addDays(45)->format('Y-m-d'),
            'start_time' => '17:00',
            'end_time' => '23:00',
            'guest_count' => 500,
            'address_line' => 'Chapra Road',
            'city' => 'Siwan',
            'state' => 'Bihar',
            'customer_name' => 'Client P10 Pay',
            'customer_phone' => '9876543216',
            'base_amount' => 50000,
            'addon_amount' => 0,
            'estimated_total' => 50000,
            'status' => 'confirmed',
        ]);

        // Advance required: 40% of 50000 = 20000
        // Record 25000 Advance Payment
        $response = $this->actingAs($superAdmin)->post('/admin/payments', [
            'booking_id' => $booking->id,
            'amount' => 25000,
            'payment_type' => 'advance',
            'payment_method' => 'upi',
            'transaction_reference' => 'UPI-TXN-' . rand(100000, 999999),
            'payment_date' => Carbon::today()->format('Y-m-d H:i:s'),
            'notes' => 'Received via PhonePe verified by manager.',
            'create_invoice' => true,
        ]);

        $response->assertRedirect();

        $booking->refresh();

        // Booking status must have transitioned to advance_paid
        $this->assertEquals('advance_paid', $booking->status);
        $this->assertEquals(25000, (float) $booking->total_paid);
        $this->assertEquals(25000, (float) $booking->balance_due);

        // Verify Invoice / Receipt generated
        $this->assertDatabaseHas('invoices', [
            'booking_id' => $booking->id,
            'amount_paid' => 25000,
            'balance_due' => 25000,
            'status' => 'partial',
        ]);

        // Customer can view their invoice
        $invoice = Invoice::where('booking_id', $booking->id)->latest()->first();
        $invResponse = $this->actingAs($customer)->get("/account/invoices/{$invoice->id}");
        $invResponse->assertStatus(200);
    }

    /**
     * Test Business Reports Dashboard and native streaming CSV exports.
     */
    public function test_business_reports_and_csv_exports()
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin_p10_report@adityautsav.test'],
            [
                'name' => 'Admin P10 Report',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // 1. Reports Dashboard
        $response = $this->actingAs($superAdmin)->get('/admin/reports');
        $response->assertStatus(200);

        // 2. Export Bookings CSV
        $exportBookings = $this->actingAs($superAdmin)->get('/admin/reports/export/bookings');
        $exportBookings->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportBookings->headers->get('Content-Type'));

        // 3. Export Payments CSV
        $exportPayments = $this->actingAs($superAdmin)->get('/admin/reports/export/payments');
        $exportPayments->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportPayments->headers->get('Content-Type'));

        // 4. Export Quotations CSV
        $exportQuotations = $this->actingAs($superAdmin)->get('/admin/reports/export/quotations');
        $exportQuotations->assertStatus(200);
        $this->assertStringContainsString('text/csv', $exportQuotations->headers->get('Content-Type'));
    }

    /**
     * Test NotificationService WhatsApp URL generator and graceful email execution.
     */
    public function test_notification_service_and_whatsapp_link()
    {
        $whatsappUrl = NotificationService::getWhatsAppUrl("Test inquiry message");
        $this->assertStringContainsString('https://wa.me/', $whatsappUrl);
        $this->assertStringContainsString('Test+inquiry+message', $whatsappUrl);

        // Test sendEmail executes without throwing fatal exceptions even if SMTP is offline
        $emailResult = NotificationService::sendEmail(
            'client_test@adityautsav.test',
            'Test Client',
            'Test Notification',
            'emails.booking_notification',
            ['message' => 'Hello from Aditya Utsav!']
        );

        // Function returns boolean gracefully
        $this->assertIsBool($emailResult);
    }
}
