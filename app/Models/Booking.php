<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    public static function generateBookingReference(): string
    {
        do {
            $ref = 'AU-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (static::where('booking_reference', $ref)->exists());

        return $ref;
    }

    protected $fillable = [
        'booking_reference',
        'user_id',
        'booking_type',
        'decoration_id',
        'package_id',
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

    public function package()
    {
        return $this->belongsTo(Package::class);
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

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class)->whereIn('status', ['paid', 'accepted', 'successful'])->orderBy('payment_date', 'desc');
    }

    public function pendingPayments()
    {
        return $this->hasMany(Payment::class)->where('status', 'pending')->orderBy('created_at', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->orderBy('issued_at', 'desc');
    }

    /**
     * Booked Item Properties
     */
    public function getIsPackageAttribute(): bool
    {
        return $this->booking_type === 'package' || (!empty($this->package_id) && empty($this->decoration_id));
    }

    public function getBookedItemNameAttribute(): string
    {
        if ($this->is_package) {
            return $this->package?->name ?? 'Royal Wedding Package';
        }
        return $this->decoration?->name ?? 'Wedding Decoration';
    }

    public function getBookedItemTypeLabelAttribute(): string
    {
        return $this->is_package ? 'Wedding Package' : 'Wedding Decoration';
    }

    public function getBookedItemImageAttribute(): ?string
    {
        if ($this->is_package) {
            return $this->package?->display_image;
        }
        return $this->decoration?->safe_primary_image;
    }

    /**
     * Financial Calculations
     */
    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');
    }

    public function getPendingPaymentAmountAttribute(): float
    {
        return (float) $this->payments()->where('status', 'pending')->sum('amount');
    }

    public function getHasPendingPaymentAttribute(): bool
    {
        return $this->payments()->where('status', 'pending')->exists();
    }

    public function getAdvancePaidAttribute(): float
    {
        return (float) $this->payments()->whereIn('status', ['paid', 'accepted', 'successful'])->where('payment_type', 'advance')->sum('amount');
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

    public function getRemainingAmountAttribute(): float
    {
        return $this->balance_due;
    }

    public function getPaymentStatusAttribute(): string
    {
        $total = $this->effective_total;
        $paid = $this->total_paid;

        if ($total > 0 && $paid >= $total) {
            return 'paid';
        }
        if ($paid > 0) {
            return 'partially_paid';
        }
        if ($this->has_pending_payment) {
            return 'pending_verification';
        }
        return 'unpaid';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Fully Paid',
            'partially_paid' => 'Partially Paid',
            'pending_verification' => 'Payment Verification Pending',
            'unpaid' => 'Payment Pending',
            default => ucfirst(str_replace('_', ' ', $this->payment_status)),
        };
    }

    public function getPaymentStatusBadgeClassesAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'partially_paid' => 'bg-blue-100 text-blue-800 border-blue-300',
            'pending_verification' => 'bg-amber-100 text-amber-900 border-amber-300',
            'unpaid' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
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

    public function getFormattedTotalPaidAttribute(): string
    {
        return '₹' . number_format($this->total_paid, 0);
    }

    public function getFormattedBalanceDueAttribute(): string
    {
        return '₹' . number_format($this->balance_due, 0);
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
            'accepted' => 'Booking Accepted',
            'quoted' => 'Quotation Sent',
            'confirmed' => 'Booking Confirmed',
            'advance_paid' => 'Advance Received',
            'scheduled' => 'Setup Scheduled',
            'completed' => 'Event Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Unavailable / Rejected',
            'reschedule_requested' => 'Reschedule Requested',
            'rescheduled' => 'Rescheduled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
            'accepted' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            'quoted' => 'bg-blue-100 text-blue-900 border-blue-300',
            'confirmed', 'advance_paid', 'scheduled' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            'completed' => 'bg-purple-100 text-purple-900 border-purple-300',
            'cancelled', 'rejected' => 'bg-rose-100 text-rose-900 border-rose-300',
            'reschedule_requested' => 'bg-orange-100 text-orange-900 border-orange-300',
            'rescheduled' => 'bg-indigo-100 text-indigo-900 border-indigo-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
