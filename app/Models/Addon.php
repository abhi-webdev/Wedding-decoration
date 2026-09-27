<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function decorations()
    {
        return $this->belongsToMany(Decoration::class, 'decoration_addons');
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 0, '.', ',');
    }

    public function getPricingTypeAttribute()
    {
        return $this->attributes['pricing_type'] ?? 'fixed';
    }

    public function getUnitLabelAttribute()
    {
        return $this->attributes['unit_label'] ?? 'per event';
    }

    public function getSafeImageAttribute(): ?string
    {
        $img = $this->image;
        if (empty($img)) {
            return null;
        }
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return null;
        }
        if (file_exists(public_path($img))) {
            return asset($img);
        }
        if (file_exists(public_path('storage/' . $img))) {
            return asset('storage/' . $img);
        }
        if (str_starts_with($img, 'uploads/')) {
            return asset($img);
        }
        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }
        return null;
    }
}
