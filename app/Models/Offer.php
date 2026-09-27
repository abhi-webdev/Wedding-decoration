<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'short_description',
        'description',
        'highlight_badge',
        'discount_text',
        'discount_type',
        'discount_value',
        'coupon_code',
        'valid_till',
        'valid_from',
        'valid_until',
        'terms',
        'cta_text',
        'cta_link',
        'image_url',
        'image',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($offer) {
            if (empty($offer->slug) && !empty($offer->title)) {
                $offer->slug = Str::slug($offer->title);
            }
        });
    }

    public function getSlugAttribute($value)
    {
        return $value ?: Str::slug($this->title ?: 'wedding-offer-' . $this->id);
    }

    /**
     * Scope for currently valid offers.
     */
    public function scopeCurrentlyValid($query)
    {
        $today = Carbon::today()->format('Y-m-d');
        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', $today);
            });
    }

    /**
     * Check if this offer is currently valid.
     */
    public function getIsCurrentlyValidAttribute()
    {
        if (!$this->is_active) return false;

        $today = Carbon::today();
        if ($this->valid_from && $today->lt($this->valid_from)) return false;
        if ($this->valid_until && $today->gt($this->valid_until)) return false;

        return true;
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
