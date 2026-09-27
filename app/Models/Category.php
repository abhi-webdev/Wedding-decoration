<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'image_url',
        'icon_name',
        'display_order',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function decorations()
    {
        return $this->hasMany(Decoration::class);
    }

    public function videos()
    {
        return $this->hasMany(WeddingVideo::class);
    }

    public function getIsActiveAttribute()
    {
        return $this->is_featured ?? true;
    }

    public function getSortOrderAttribute()
    {
        return $this->display_order ?? 0;
    }

    public function activeDecorations()
    {
        return $this->hasMany(Decoration::class)->where('is_active', true)->where('is_available', true);
    }

    public function getSafeImageAttribute(): ?string
    {
        $img = $this->image_url;
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
