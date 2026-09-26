<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\Package;
use App\Models\Offer;
use App\Models\WeddingVideo;
use App\Models\GalleryItem;
use App\Models\Booking;
use App\Models\Addon;
use App\Models\BookingCancellationRequest;
use App\Models\BookingRescheduleRequest;
use App\Models\Quotation;
use Illuminate\Support\Facades\Hash;

class SystemAuditTest extends TestCase
{
    /**
     * Test all public GET routes to verify 200 OK responses and no runtime exceptions.
     */
    public function test_all_public_routes_return_successful_response()
    {
        $publicRoutes = [
            '/',
            '/decorations',
            '/packages',
            '/offers',
            '/gallery',
            '/reels',
            '/videos',
            '/quote',
            '/faq',
            '/about',
            '/contact',
            '/service-areas',
            '/terms',
            '/cancellation-policy',
            '/booking-guide',
            '/login',
            '/register',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed asserting that route {$route} returns status 200.");
        }
    }

    /**
     * Test public single item pages with existing slugs.
     */
    public function test_public_detail_pages_with_slugs()
    {
        // 1. Category route
        $category = Category::first();
        if ($category) {
            $response = $this->get("/decorations/{$category->slug}");
            $response->assertStatus(200);
        }

        // 2. Decoration detail
        $decoration = Decoration::first();
        if ($decoration) {
            $response = $this->get("/decoration/{$decoration->slug}");
            $response->assertStatus(200);
        }

        // 3. Package detail
        $package = Package::first();
        if ($package) {
            $response = $this->get("/package/{$package->slug}");
            $response->assertStatus(200);
        }

        // 4. Offer detail
        $offer = Offer::first();
        if ($offer) {
            $response = $this->get("/offer/{$offer->slug}");
            $response->assertStatus(200);
        }

        // 5. Video detail
        $video = WeddingVideo::first();
        if ($video) {
            $response = $this->get("/video/{$video->slug}");
            $response->assertStatus(200);
        }
    }

    /**
     * Test booking availability AJAX endpoint.
     */
    public function test_booking_check_availability_endpoint()
    {
        $decoration = Decoration::first();
        if ($decoration) {
            $response = $this->getJson("/booking/check-availability?decoration_id={$decoration->id}&event_date=2027-12-15");
            $response->assertStatus(200)
                     ->assertJsonStructure(['available', 'status', 'badge', 'message']);
        }
    }

    /**
     * Test customer authentication and account dashboard isolation.
     */
    public function test_customer_dashboard_and_booking_isolation()
    {
        // Unauthenticated customer redirected
        $response = $this->get('/account');
        $response->assertRedirect('/login');

        // Create Customer A
        $customerA = User::firstOrCreate(
            ['email' => 'customer_a@adityautsav.test'],
            [
                'name' => 'Customer A',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        // Create Customer B
        $customerB = User::firstOrCreate(
            ['email' => 'customer_b@adityautsav.test'],
            [
                'name' => 'Customer B',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        // Authenticated Customer A can view their account
        $response = $this->actingAs($customerA)->get('/account');
        $response->assertStatus(200);

        $response = $this->actingAs($customerA)->get('/account/bookings');
        $response->assertStatus(200);

        // Create a booking owned by Customer B
        $decoration = Decoration::first();
        if ($decoration) {
            $bookingB = Booking::create([
                'booking_reference' => 'AU-TEST-B-' . rand(1000, 9999),
                'user_id' => $customerB->id,
                'decoration_id' => $decoration->id,
                'event_type' => 'Jaimala',
                'event_date' => '2027-05-10',
                'start_time' => '18:00',
                'end_time' => '23:00',
                'guest_count' => 300,
                'address_line' => 'Siwan Central',
                'city' => 'Siwan',
                'state' => 'Bihar',
                'customer_name' => 'Customer B',
                'customer_phone' => '9876543210',
                'base_amount' => 50000,
                'addon_amount' => 0,
                'estimated_total' => 50000,
                'status' => 'pending',
            ]);

            // Customer A attempting to access Customer B's booking MUST return 404
            $isolatedResponse = $this->actingAs($customerA)->get("/account/bookings/{$bookingB->id}");
            $isolatedResponse->assertStatus(404);

            // Customer B accessing their own booking MUST return 200
            $ownerResponse = $this->actingAs($customerB)->get("/account/bookings/{$bookingB->id}");
            $ownerResponse->assertStatus(200);
        }
    }

    /**
     * Test admin authentication and role authorization enforcement.
     */
    public function test_admin_role_security_and_permissions()
    {
        // Unauthenticated access to admin dashboard redirects to /admin/login
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');

        // Create staff roles
        $superAdmin = User::firstOrCreate(
            ['email' => 'super_test@adityautsav.test'],
            [
                'name' => 'Super Admin Test',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $contentManager = User::firstOrCreate(
            ['email' => 'content_test@adityautsav.test'],
            [
                'name' => 'Content Manager Test',
                'password' => Hash::make('password123'),
                'role' => 'content_manager',
                'is_active' => true,
            ]
        );

        $bookingManager = User::firstOrCreate(
            ['email' => 'booking_test@adityautsav.test'],
            [
                'name' => 'Booking Manager Test',
                'password' => Hash::make('password123'),
                'role' => 'booking_manager',
                'is_active' => true,
            ]
        );

        // 1. Super Admin has full access
        $this->actingAs($superAdmin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/bookings')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/decorations')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/users')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/activity-logs')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/cancellation-requests')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/reschedule-requests')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/calendar')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/admin/settings')->assertStatus(200);

        // 2. Content Manager: Allowed content, forbidden staff management and bookings
        $this->actingAs($contentManager)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($contentManager)->get('/admin/decorations')->assertStatus(200);
        $this->actingAs($contentManager)->get('/admin/gallery')->assertStatus(200);
        $this->actingAs($contentManager)->get('/admin/videos')->assertStatus(200);
        $this->actingAs($contentManager)->get('/admin/users')->assertStatus(403);
        $this->actingAs($contentManager)->get('/admin/bookings')->assertStatus(403);
        $this->actingAs($contentManager)->get('/admin/cancellation-requests')->assertStatus(403);

        // 3. Booking Manager: Allowed bookings, forbidden content and staff management
        $this->actingAs($bookingManager)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($bookingManager)->get('/admin/bookings')->assertStatus(200);
        $this->actingAs($bookingManager)->get('/admin/customers')->assertStatus(200);
        $this->actingAs($bookingManager)->get('/admin/cancellation-requests')->assertStatus(200);
        $this->actingAs($bookingManager)->get('/admin/reschedule-requests')->assertStatus(200);
        $this->actingAs($bookingManager)->get('/admin/decorations')->assertStatus(403);
        $this->actingAs($bookingManager)->get('/admin/users')->assertStatus(403);
    }

    /**
     * Test server-side price recalculation during booking submission.
     */
    public function test_booking_submission_recalculates_price_server_side()
    {
        $decoration = Decoration::first();
        if ($decoration) {
            $addon = Addon::first();

            $response = $this->post('/booking', [
                'decoration_id' => $decoration->id,
                'event_type' => 'Royal Jaimala',
                'event_date' => '2027-11-20',
                'start_time' => '17:00',
                'end_time' => '23:00',
                'guest_count' => 450,
                'address_line' => 'Court Road, Siwan',
                'city' => 'Siwan',
                'state' => 'Bihar',
                'customer_name' => 'Aditya Client',
                'customer_phone' => '9876543211',
                'customer_email' => 'client@adityautsav.test',
                'selected_addons' => $addon ? [$addon->id] : [],
                // Malicious tampered amounts in request (must be ignored by server)
                'base_amount' => 10,
                'addon_amount' => 5,
                'estimated_total' => 15,
            ]);

            $response->assertRedirect();

            // Verify the created booking in database has the true server-calculated total
            $expectedBase = $decoration->actual_booking_price;
            $expectedAddon = $addon ? (float)$addon->price : 0;
            $expectedTotal = $expectedBase + $expectedAddon;

            $this->assertDatabaseHas('bookings', [
                'customer_name' => 'Aditya Client',
                'customer_phone' => '9876543211',
                'base_amount' => $expectedBase,
                'addon_amount' => $expectedAddon,
                'estimated_total' => $expectedTotal,
            ]);
        }
    }

    /**
     * Test cancellation request and reschedule request submission workflow.
     */
    public function test_customer_cancellation_and_reschedule_workflow()
    {
        $customer = User::firstOrCreate(
            ['email' => 'customer_request_test@adityautsav.test'],
            [
                'name' => 'Request Test Customer',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $decoration = Decoration::first();
        if ($decoration) {
            $booking = Booking::create([
                'booking_reference' => 'AU-REQ-TEST-' . rand(1000, 9999),
                'user_id' => $customer->id,
                'decoration_id' => $decoration->id,
                'event_type' => 'Reception',
                'event_date' => '2027-08-15',
                'start_time' => '19:00',
                'end_time' => '23:30',
                'guest_count' => 200,
                'address_line' => 'Siwan Bypass',
                'city' => 'Siwan',
                'state' => 'Bihar',
                'customer_name' => 'Request Test Customer',
                'customer_phone' => '9876543212',
                'base_amount' => 45000,
                'addon_amount' => 0,
                'estimated_total' => 45000,
                'status' => 'confirmed',
            ]);

            // 1. Submit Cancellation Request
            $response = $this->actingAs($customer)->post("/account/bookings/{$booking->id}/cancel-request", [
                'reason' => 'Family date change',
                'details' => 'We need to cancel due to unforeseen family schedule change.',
            ]);
            $response->assertRedirect();
            $this->assertDatabaseHas('booking_cancellation_requests', [
                'booking_id' => $booking->id,
                'user_id' => $customer->id,
                'status' => 'pending',
            ]);

            // 2. Submit Reschedule Request
            $response2 = $this->actingAs($customer)->post("/account/bookings/{$booking->id}/reschedule-request", [
                'requested_date' => '2027-09-20',
                'requested_start_time' => '18:00',
                'requested_end_time' => '23:00',
                'reason' => 'Auspicious muhurat postponed',
            ]);
            $response2->assertRedirect();
            $this->assertDatabaseHas('booking_reschedule_requests', [
                'booking_id' => $booking->id,
                'user_id' => $customer->id,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Test custom error views render properly.
     */
    public function test_custom_error_views_exist_and_render()
    {
        $this->assertTrue(view()->exists('errors.404'));
        $this->assertTrue(view()->exists('errors.403'));
        $this->assertTrue(view()->exists('errors.419'));
        $this->assertTrue(view()->exists('errors.429'));
        $this->assertTrue(view()->exists('errors.500'));

        $rendered404 = view('errors.404')->render();
        $this->assertStringContainsString('404', $rendered404);
        $this->assertStringContainsString('Aditya Utsav', $rendered404);
    }
}
