<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WeddingVideo extends Model
{
    use HasFactory;

    protected $table = 'wedding_videos';

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'video_path',
        'thumbnail_path',
        'video_type',
        'event_type',
        'category_id',
        'location',
        'duration',
        'is_featured',
        'is_homepage',
        'is_active',
        'sort_order',
        'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_homepage' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'views_count' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->slug)) {
                $baseSlug = Str::slug($video->title);
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $video->slug = $slug;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedDecorations()
    {
        if ($this->category_id) {
            return Decoration::where('category_id', $this->category_id)
                ->where('is_active', true)
                ->take(3)
                ->get();
        }
        return Decoration::where('is_active', true)->take(3)->get();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_active', true)->where('is_homepage', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_active', true)->where('is_featured', true);
    }

    public function getSafeVideoUrlAttribute(): string
    {
        if (empty($this->video_path)) {
            // Default elegant HTML5 demo video
            return 'https://assets.mixkit.co/videos/preview/mixkit-traditional-wedding-ceremony-under-a-canopy-48866-large.mp4';
        }
        if (str_starts_with($this->video_path, 'http://') || str_starts_with($this->video_path, 'https://')) {
            return $this->video_path;
        }
        return asset($this->video_path);
    }

    public function getSafeThumbnailUrlAttribute(): string
    {
        if (empty($this->thumbnail_path)) {
            // Fallback authentic Indian wedding thumbnails
            return match ($this->event_type) {
                'jaimala' => 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=600&q=80',
                'mandap' => 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80',
                'haldi' => 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
                'mehendi' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=600&q=80',
                'sangeet' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=600&q=80',
                'reception' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80',
                default => 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=600&q=80',
            };
        }
        if (str_starts_with($this->thumbnail_path, 'http://') || str_starts_with($this->thumbnail_path, 'https://')) {
            return $this->thumbnail_path;
        }
        return asset($this->thumbnail_path);
    }

    public function getEventTypeLabelAttribute(): string
    {
        return match ($this->event_type) {
            'jaimala' => 'Jaimala Stage',
            'mandap' => 'Vedic Mandap',
            'haldi' => 'Haldi Ceremony',
            'mehendi' => 'Mehendi Setup',
            'sangeet' => 'Sangeet Night',
            'reception' => 'Grand Reception',
            'general' => 'Wedding Highlight',
            default => ucfirst(str_replace('_', ' ', $this->event_type)),
        };
    }

    public function getVideoTypeLabelAttribute(): string
    {
        return match ($this->video_type) {
            'reel' => 'Reel',
            'short' => 'Short Video',
            'event_highlight' => 'Highlight',
            'portfolio' => 'Portfolio',
            'behind_the_scenes' => 'Behind The Scenes',
            default => ucfirst(str_replace('_', ' ', $this->video_type)),
        };
    }

    public function getFormattedDurationAttribute(): string
    {
        return $this->duration ?: '00:20';
    }
}
