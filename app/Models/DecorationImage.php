<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorationImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'decoration_id',
        'image_url',
        'alt_text',
        'caption',
        'is_primary',
        'display_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function getSafeUrlAttribute(): ?string
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
