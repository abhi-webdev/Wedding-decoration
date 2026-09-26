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

    public function getImagePathAttribute()
    {
        return $this->image ?: ($this->image_url ?: 'images/decorations/jaimala-stage-01.jpg');
    }

    public function getDisplayImageAttribute()
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        if ($this->image_url) {
            return asset($this->image_url);
        }
        return asset('images/decorations/jaimala-stage-01.jpg');
    }
}
