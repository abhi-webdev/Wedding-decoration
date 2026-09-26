<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Phase 7 Business Operations.
     */
    public function up(): void
    {
        // 1. Quotations Table
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->string('quotation_number', 50)->unique();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('decoration_id')->nullable()->constrained('decorations')->nullOnDelete();
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('addon_total', 10, 2)->default(0);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('additional_charges', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2)->default(0);
                $table->decimal('advance_percentage', 5, 2)->default(40.00);
                $table->decimal('advance_amount', 10, 2)->default(0);
                $table->decimal('balance_amount', 10, 2)->default(0);
                $table->date('valid_until');
                $table->text('notes')->nullable();
                $table->text('terms')->nullable();
                $table->string('status', 30)->default('draft'); // draft, sent, viewed, accepted, rejected, expired, cancelled
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('valid_until');
            });
        }

        // 2. Quotation Items Table
        if (!Schema::hasTable('quotation_items')) {
            Schema::create('quotation_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
                $table->string('item_type', 40)->default('custom'); // decoration, addon, package, custom, additional_charge
                $table->unsignedBigInteger('item_id')->nullable();
                $table->string('description', 255);
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        // 3. Payments Table
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('payment_reference', 50)->unique();
                $table->decimal('amount', 10, 2);
                $table->string('payment_type', 30)->default('advance'); // advance, balance, full, refund, other
                $table->string('payment_method', 30)->default('upi'); // cash, bank_transfer, upi, card, online, other
                $table->string('status', 30)->default('paid'); // pending, paid, failed, refunded, cancelled
                $table->string('transaction_reference', 100)->nullable();
                $table->dateTime('payment_date');
                $table->text('notes')->nullable();
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index('status');
                $table->index('payment_type');
            });
        }

        // 4. Invoices Table
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number', 50)->unique();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('invoice_type', 30)->default('advance'); // advance, final, receipt
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->decimal('amount_paid', 10, 2)->default(0);
                $table->decimal('balance_due', 10, 2)->default(0);
                $table->string('status', 30)->default('issued'); // draft, issued, paid, partial, cancelled
                $table->dateTime('issued_at');
                $table->dateTime('due_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('invoice_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
