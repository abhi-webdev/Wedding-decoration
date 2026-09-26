<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'quotation_id',
        'user_id',
        'payment_reference',
        'amount',
        'payment_type',
        'payment_method',
        'status',
        'transaction_reference',
        'payment_date',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public static function generatePaymentReference(): string
    {
        return 'AUP-' . date('Ymd') . '-' . strtoupper(Str::random(5));
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

    public function recordedByUser()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'booking_id', 'booking_id');
    }

    public function getTransactionIdAttribute(): ?string
    {
        return $this->transaction_reference;
    }

    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'upi' => 'UPI (GooglePay / PhonePe / Paytm)',
            'bank_transfer' => 'NEFT / RTGS / IMPS Bank Transfer',
            'cash' => 'Cash in Hand (Office / Venue)',
            'card' => 'Debit / Credit Card',
            'online' => 'Online Payment',
            default => ucfirst(str_replace('_', ' ', $this->payment_method)),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'paid', 'successful' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'failed' => 'bg-red-100 text-red-800 border-red-300',
            'refunded' => 'bg-purple-100 text-purple-800 border-purple-300',
            'cancelled' => 'bg-slate-100 text-slate-700 border-slate-300',
            default => 'bg-slate-100 text-slate-700 border-slate-300',
        };
    }
}
