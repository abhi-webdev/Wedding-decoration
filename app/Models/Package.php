<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'badge',
        'tagline',
        'short_description',
        'description',
        'included_ceremonies',
        'highlights',
        'guest_capacity',
        'duration',
        'starting_price',
        'base_price',
        'discount_price',
        'image_url',
        'image',
        'is_featured',
        'is_active',
        'display_order',
        'sort_order',
    ];

    protected $casts = [
        'included_ceremonies' => 'array',
        'highlights' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'starting_price' => 'integer',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    /**
     * Decorations included inside this wedding package.
     */
    public function decorations()
    {
        return $this->belongsToMany(Decoration::class, 'package_decorations')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Display price formatted in INR.
     */
    public function getPriceAttribute()
    {
        return $this->discount_price ?: ($this->base_price ?: $this->starting_price);
    }

    public function getTierAttribute()
    {
        return $this->badge ?: 'Royal';
    }

    public function getBadgeTextAttribute()
    {
        return $this->badge ?? '';
    }

    public function getOriginalPriceAttribute()
    {
        return $this->base_price ?: $this->starting_price;
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->discount_price ?: ($this->base_price ?: $this->starting_price);
        return '₹' . number_format($price, 0, '.', ',');
    }

    public function getFormattedBasePriceAttribute()
    {
        $price = $this->base_price ?: $this->starting_price;
        return '₹' . number_format($price, 0, '.', ',');
    }

    public function getFormattedDiscountPriceAttribute()
    {
        if (!$this->discount_price) return null;
        return '₹' . number_format($this->discount_price, 0, '.', ',');
    }

    /**
     * Image resolution helper.
     */
    public function getDisplayImageAttribute(): ?string
    {
        $img = $this->image ?: $this->image_url;
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
