<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add profile fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('whatsapp', 30)->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('whatsapp');
            $table->string('state', 100)->default('Bihar')->after('city');
            $table->text('address')->nullable()->after('state');
        });

        // 2. Booking Cancellation Requests Table
        Schema::create('booking_cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reason');
            $table->text('details')->nullable();
            $table->string('status', 30)->default('pending'); // pending, approved, rejected
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        // 3. Booking Reschedule Requests Table
        Schema::create('booking_reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('requested_date');
            $table->time('requested_start_time');
            $table->time('requested_end_time');
            $table->text('reason')->nullable();
            $table->string('status', 30)->default('pending'); // pending, approved, rejected
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_reschedule_requests');
        Schema::dropIfExists('booking_cancellation_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'whatsapp', 'city', 'state', 'address']);
        });
    }
};
