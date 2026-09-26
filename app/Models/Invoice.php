<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'quotation_id',
        'user_id',
        'invoice_type',
        'subtotal',
        'discount',
        'tax',
        'total',
        'amount_paid',
        'balance_due',
        'status',
        'issued_at',
        'due_at',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public static function generateInvoiceNumber(): string
    {
        return 'AUI-' . date('Ymd') . '-' . strtoupper(Str::random(5));
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getGrandTotalAttribute(): float
    {
        return (float) $this->total;
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->amount_paid;
    }

    public function getDiscountAmountAttribute(): float
    {
        return (float) $this->discount;
    }

    public function getTaxAmountAttribute(): float
    {
        return (float) $this->tax;
    }

    public function getDueDateAttribute()
    {
        return $this->due_at;
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₹' . number_format($this->total, 2);
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return '₹' . number_format($this->total, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return '₹' . number_format($this->discount, 2);
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return '₹' . number_format($this->tax, 2);
    }

    public function getFormattedPaidAmountAttribute(): string
    {
        return '₹' . number_format($this->amount_paid, 2);
    }

    public function getFormattedBalanceDueAttribute(): string
    {
        return '₹' . number_format($this->balance_due, 2);
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'issued' => 'bg-blue-100 text-blue-800 border-blue-300',
            'partial', 'partially_paid' => 'bg-amber-100 text-amber-800 border-amber-300',
            'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }
}
