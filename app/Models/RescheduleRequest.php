<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RescheduleRequest extends Model
{
    use HasFactory;

    protected $table = 'booking_reschedule_requests';

    protected $fillable = [
        'booking_id',
        'user_id',
        'requested_date',
        'requested_start_time',
        'requested_end_time',
        'reason',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'requested_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAdminNotesAttribute(): string
    {
        return $this->admin_note ?? '';
    }

    public function getFormattedRequestedDateAttribute(): string
    {
        return $this->requested_date ? Carbon::parse($this->requested_date)->format('d M Y (D)') : '';
    }

    public function getFormattedTimeRangeAttribute(): string
    {
        if (!$this->requested_start_time || !$this->requested_end_time) {
            return '';
        }
        $start = Carbon::createFromFormat('H:i:s', strlen($this->requested_start_time) === 5 ? $this->requested_start_time . ':00' : $this->requested_start_time)->format('g:i A');
        $end = Carbon::createFromFormat('H:i:s', strlen($this->requested_end_time) === 5 ? $this->requested_end_time . ':00' : $this->requested_end_time)->format('g:i A');
        return "{$start} – {$end}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Under Review',
            'approved' => 'Reschedule Approved',
            'rejected' => 'Request Declined',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
            'approved' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            'rejected' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
