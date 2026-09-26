<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuoteRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_reference',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'event_type',
        'event_date',
        'guest_count',
        'state',
        'city',
        'locality',
        'venue_name',
        'decoration_preference',
        'budget_range',
        'special_requirements',
        'reference_image',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'decoration_preference' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->customer_name;
    }

    public function getPhoneAttribute()
    {
        return $this->customer_phone;
    }

    public function getEmailAttribute()
    {
        return $this->customer_email;
    }

    public function getRequirementsAttribute()
    {
        return $this->special_requirements;
    }

    public function getMessageAttribute()
    {
        return $this->special_requirements;
    }

    public function getFormattedEventDateAttribute()
    {
        return $this->event_date ? $this->event_date->format('d F Y') : '';
    }

    public function getStatusBadgeClassesAttribute()
    {
        return match ($this->status) {
            'new' => 'bg-amber-100 text-amber-900 border-amber-300',
            'contacted' => 'bg-blue-100 text-blue-900 border-blue-300',
            'quoted' => 'bg-purple-100 text-purple-900 border-purple-300',
            'converted' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
            'closed' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
