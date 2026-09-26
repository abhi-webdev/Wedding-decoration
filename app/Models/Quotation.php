<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'booking_id',
        'user_id',
        'decoration_id',
        'subtotal',
        'addon_total',
        'discount_amount',
        'tax_amount',
        'additional_charges',
        'grand_total',
        'advance_percentage',
        'advance_amount',
        'balance_amount',
        'valid_until',
        'notes',
        'terms',
        'status',
        'created_by',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'addon_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'advance_percentage' => 'decimal:2',
        'advance_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public static function generateQuotationNumber(): string
    {
        return 'AUQ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        if (in_array($this->status, ['accepted', 'cancelled', 'rejected'])) {
            return false;
        }
        return $this->valid_until && $this->valid_until->isPast();
    }

    public function getIsValidAttribute(): bool
    {
        return !$this->isExpired();
    }

    public function canBeAccepted(): bool
    {
        return in_array($this->status, ['sent', 'viewed']) && !$this->isExpired();
    }

    public function getFormattedGrandTotalAttribute(): string
    {
        return '₹' . number_format($this->grand_total, 2);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getFormattedDiscountAttribute(): string
    {
        return '₹' . number_format($this->discount_amount, 2);
    }

    public function getFormattedTaxAttribute(): string
    {
        return '₹' . number_format($this->tax_amount, 2);
    }

    public function getFormattedAdvanceAmountAttribute(): string
    {
        return '₹' . number_format($this->advance_amount, 2);
    }

    public function getFormattedAdvanceAttribute(): string
    {
        return '₹' . number_format($this->advance_amount, 2);
    }

    public function getFormattedBalanceAmountAttribute(): string
    {
        return '₹' . number_format($this->balance_amount, 2);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return '₹' . number_format($this->balance_amount, 2);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₹' . number_format($this->grand_total, 2);
    }

    public function getAcceptedAtAttribute()
    {
        return $this->approved_at;
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
            'sent' => 'bg-blue-100 text-blue-800 border-blue-300',
            'viewed' => 'bg-purple-100 text-purple-800 border-purple-300',
            'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-300',
            'expired' => 'bg-amber-100 text-amber-800 border-amber-300',
            'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }
}
