<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_category_id',
        'title',
        'slug',
        'category',
        'event_type',
        'image_url',
        'image',
        'location',
        'caption',
        'description',
        'is_featured',
        'is_active',
        'display_order',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function galleryCategory()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'gallery_category_id');
    }

    public function getImagePathAttribute(): ?string
    {
        return $this->image ?: $this->image_url;
    }

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

    public function getSafeImageUrlAttribute(): ?string
    {
        return $this->display_image;
    }
}
