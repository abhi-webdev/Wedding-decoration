<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'state',
        'district',
        'category',
        'status_note',
        'description',
        'image',
        'is_primary',
        'is_active',
        'display_order',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getCityAttribute()
    {
        return $this->district ?? $this->name;
    }

    public function getTierAttribute()
    {
        return $this->is_primary ? 'Primary Hub' : 'Standard Area';
    }

    public function getTravelSurchargeAttribute()
    {
        return 0;
    }

    public function getMinBookingAmountAttribute()
    {
        return 25000;
    }
}
