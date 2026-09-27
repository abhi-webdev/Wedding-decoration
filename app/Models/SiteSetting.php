<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function getSafeImage($key, $default = null): ?string
    {
        $val = static::get($key);
        if (empty($val)) {
            return $default;
        }
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
            return $default;
        }
        if (file_exists(public_path($val))) {
            return asset($val);
        }
        if (file_exists(public_path('storage/' . $val))) {
            return asset('storage/' . $val);
        }
        if (str_starts_with($val, 'uploads/')) {
            return asset($val);
        }
        if (str_starts_with($val, 'storage/')) {
            return asset($val);
        }
        return $default;
    }
}
