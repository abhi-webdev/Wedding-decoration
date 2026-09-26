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
        // 1. Bookings Table
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('decoration_id')->constrained('decorations')->cascadeOnDelete();
            
            // Event Details
            $table->string('event_type');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('guest_count')->default(1);
            
            // Location
            $table->string('address_line');
            $table->string('locality')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('state')->default('Bihar');
            $table->string('pincode', 20)->nullable();
            
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_phone', 30);
            $table->string('customer_email')->nullable();
            $table->string('whatsapp_number', 30)->nullable();
            $table->text('special_requirements')->nullable();
            
            // Financial Calculation
            $table->decimal('base_amount', 12, 2);
            $table->decimal('addon_amount', 12, 2)->default(0.00);
            $table->decimal('estimated_total', 12, 2);
            
            // Status Lifecycle
            $table->string('status', 40)->default('pending'); // pending, quoted, confirmed, advance_paid, scheduled, completed, cancelled, rejected, rescheduled
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();

            // Indexes for fast availability checking and querying
            $table->index('event_date');
            $table->index('status');
            $table->index(['decoration_id', 'event_date']);
        });

        // 2. Booking Add-ons Table
        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        // 3. Booking Status Histories Table
        Schema::create('booking_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('status', 40);
            $table->text('note')->nullable();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_histories');
        Schema::dropIfExists('booking_addons');
        Schema::dropIfExists('bookings');
    }
};
