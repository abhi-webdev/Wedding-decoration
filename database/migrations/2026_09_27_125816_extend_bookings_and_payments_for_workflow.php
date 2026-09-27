<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Extend bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'booking_type')) {
                $table->string('booking_type', 30)->default('decoration')->after('user_id');
            }
            if (!Schema::hasColumn('bookings', 'package_id')) {
                $table->foreignId('package_id')->nullable()->after('decoration_id')->constrained('packages')->nullOnDelete();
            }
        });

        // Ensure decoration_id is nullable on bookings table so package bookings work
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE bookings MODIFY decoration_id BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Ignore if already nullable or driver differences
        }

        // 2. Extend payments table
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'receipt_number')) {
                $table->string('receipt_number', 60)->nullable()->unique()->after('payment_reference');
            }
            if (!Schema::hasColumn('payments', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('payments', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('recorded_by')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payments', 'verified_at')) {
                $table->dateTime('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('payments', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('payments', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('payments', 'receipt_number')) {
                $table->dropColumn('receipt_number');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'package_id')) {
                $table->dropForeign(['package_id']);
                $table->dropColumn('package_id');
            }
            if (Schema::hasColumn('bookings', 'booking_type')) {
                $table->dropColumn('booking_type');
            }
        });
    }
};
