<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationRequest extends Model
{
    use HasFactory;

    protected $table = 'booking_cancellation_requests';

    protected $fillable = [
        'booking_id',
        'user_id',
        'reason',
        'details',
        'status',
        'admin_note',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Under Review',
            'approved' => 'Cancellation Approved',
            'rejected' => 'Request Declined',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
            'approved' => 'bg-rose-100 text-rose-900 border-rose-300',
            'rejected' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
