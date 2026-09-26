<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'user_id',
        'decoration_id',
        'event_type',
        'event_date',
        'start_time',
        'end_time',
        'guest_count',
        'address_line',
        'locality',
        'city',
        'district',
        'state',
        'pincode',
        'customer_name',
        'customer_phone',
        'customer_email',
        'whatsapp_number',
        'special_requirements',
        'base_amount',
        'addon_amount',
        'estimated_total',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
        'base_amount' => 'decimal:2',
        'addon_amount' => 'decimal:2',
        'estimated_total' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function addons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(BookingStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function cancellationRequests()
    {
        return $this->hasMany(BookingCancellationRequest::class)->orderBy('created_at', 'desc');
    }

    public function rescheduleRequests()
    {
        return $this->hasMany(BookingRescheduleRequest::class)->orderBy('created_at', 'desc');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class)->orderBy('created_at', 'desc');
    }

    public function latestQuotation()
    {
        return $this->hasOne(Quotation::class)->latestOfMany();
    }

    public function activeQuotation()
    {
        return $this->hasOne(Quotation::class)->whereNotIn('status', ['rejected', 'cancelled'])->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->orderBy('issued_at', 'desc');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->whereIn('status', ['paid', 'successful'])->sum('amount');
    }

    public function getAdvancePaidAttribute(): float
    {
        return (float) $this->payments()->whereIn('status', ['paid', 'successful'])->where('payment_type', 'advance')->sum('amount');
    }

    public function getEffectiveTotalAttribute(): float
    {
        $quote = $this->activeQuotation;
        if ($quote && $quote->grand_total > 0) {
            return (float) $quote->grand_total;
        }
        return (float) $this->estimated_total;
    }

    public function getBalanceDueAttribute(): float
    {
        $total = $this->effective_total;
        $paid = $this->total_paid;
        return max(0, $total - $paid);
    }

    public function getHasPendingCancellationAttribute(): bool
    {
        return $this->cancellationRequests()->where('status', 'pending')->exists();
    }

    public function getHasPendingRescheduleAttribute(): bool
    {
        return $this->rescheduleRequests()->where('status', 'pending')->exists();
    }

    /**
     * Accessors & Helpers
     */
    public function getFormattedBaseAmountAttribute(): string
    {
        return '₹' . number_format($this->base_amount, 0);
    }

    public function getFormattedAddonAmountAttribute(): string
    {
        return '₹' . number_format($this->addon_amount, 0);
    }

    public function getFormattedEstimatedTotalAttribute(): string
    {
        return '₹' . number_format($this->estimated_total, 0);
    }

    public function getFormattedEventDateAttribute(): string
    {
        return $this->event_date ? Carbon::parse($this->event_date)->format('d M Y (D)') : '';
    }

    public function getFormattedTimeRangeAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return '';
        }
        $start = Carbon::createFromFormat('H:i:s', strlen($this->start_time) === 5 ? $this->start_time . ':00' : $this->start_time)->format('g:i A');
        $end = Carbon::createFromFormat('H:i:s', strlen($this->end_time) === 5 ? $this->end_time . ':00' : $this->end_time)->format('g:i A');
        return "{$start} – {$end}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'quoted' => 'Quotation Sent',
            'confirmed' => 'Booking Confirmed',
            'advance_paid' => 'Advance Received',
            'scheduled' => 'Setup Scheduled',
            'completed' => 'Event Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Unavailable / Rejected',
            'rescheduled' => 'Rescheduled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
            'quoted' => 'bg-blue-100 text-blue-900 border-blue-300',
            'confirmed', 'advance_paid', 'scheduled' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            'completed' => 'bg-purple-100 text-purple-900 border-purple-300',
            'cancelled', 'rejected' => 'bg-rose-100 text-rose-900 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
