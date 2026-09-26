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

    public function getSafeImageAttribute()
    {
        if (!empty($this->image) && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        if (!empty($this->image) && (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://'))) {
            return $this->image;
        }
        return asset('images/placeholders/decoration-placeholder.svg');
    }
}
