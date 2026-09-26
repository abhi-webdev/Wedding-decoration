<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'tagline',
        'short_description',
        'description',
        'primary_image',
        'location',
        'starting_price',
        'base_price',
        'discount_price',
        'price_unit',
        'style',
        'primary_color',
        'color_theme',
        'guest_capacity',
        'setup_time',
        'rating',
        'reviews_count',
        'is_featured',
        'is_trending',
        'is_available',
        'is_active',
        'features',
        'display_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'float',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'starting_price' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(DecorationImage::class)->orderBy('display_order');
    }

    public function items()
    {
        return $this->hasMany(DecorationItem::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'decoration_addons')->where('is_active', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getActualBookingPriceAttribute(): float
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < ($this->base_price ?: $this->starting_price)) {
            return (float)$this->discount_price;
        }
        return (float)($this->base_price ?: $this->starting_price);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->base_price ?: (float)$this->starting_price;
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->effective_price;
        return '₹' . number_format($price, 0, '.', ',');
    }

    public function getFormattedDiscountPriceAttribute()
    {
        if ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->effective_price) {
            return '₹' . number_format($this->discount_price, 0, '.', ',');
        }
        return null;
    }

    public function getHasDiscountAttribute()
    {
        return ($this->discount_price && $this->discount_price > 0 && $this->discount_price < $this->effective_price);
    }

    public function getSafePrimaryImageAttribute(): ?string
    {
        $img = $this->primary_image;
        if (empty($img)) {
            return null;
        }
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return null;
        }
        if (str_starts_with($img, 'uploads/')) {
            return asset('storage/' . $img);
        }
        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }
        if (file_exists(public_path($img))) {
            return asset($img);
        }
        return asset('storage/' . $img);
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return $this->safe_primary_image;
    }

    public function getDisplayDescriptionAttribute()
    {
        return $this->short_description ?: ($this->tagline ?: $this->description);
    }
}
