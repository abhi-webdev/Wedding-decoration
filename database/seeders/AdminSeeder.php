<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeders for Phase 6 Admin Users.
     */
    public function run()
    {
        // 1. Super Admin (Full platform access)
        User::updateOrCreate(
            ['email' => 'admin@adityautsav.in'],
            [
                'name' => 'Aditya Utsav Super Admin',
                'phone' => '9931200000',
                'city' => 'Siwan',
                'state' => 'Bihar',
                'address' => 'Station Road, Siwan, Bihar',
                'password' => Hash::make('AdityaAdmin2026!'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // 2. Booking Manager (Bookings, Cancellations, Reschedules, Quotes)
        User::updateOrCreate(
            ['email' => 'bookings@adityautsav.in'],
            [
                'name' => 'Siwan Operations Manager',
                'phone' => '9931200001',
                'city' => 'Siwan',
                'state' => 'Bihar',
                'address' => 'Operations Desk, Siwan',
                'password' => Hash::make('AdityaBookings2026!'),
                'role' => 'booking_manager',
                'is_active' => true,
            ]
        );

        // 3. Content Manager (Decorations, Packages, Offers, Gallery, FAQs)
        User::updateOrCreate(
            ['email' => 'content@adityautsav.in'],
            [
                'name' => 'Design & Catalog Coordinator',
                'phone' => '9931200002',
                'city' => 'Patna',
                'state' => 'Bihar',
                'address' => 'Boring Road, Patna',
                'password' => Hash::make('AdityaContent2026!'),
                'role' => 'content_manager',
                'is_active' => true,
            ]
        );
    }
}
