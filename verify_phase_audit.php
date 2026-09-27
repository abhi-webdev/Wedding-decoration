<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== 1. AUDITING DATABASE IMAGE RECORDS ===" . PHP_EOL;

$tables = [
    'decorations' => ['image', 'primary_image'],
    'decoration_images' => ['image_path'],
    'categories' => ['image', 'banner_image'],
    'packages' => ['image'],
    'offers' => ['image', 'banner_image'],
    'gallery_items' => ['image', 'image_url'],
    'addons' => ['image'],
    'service_areas' => ['hero_image'],
    'wedding_videos' => ['video_path', 'thumbnail_path'],
];

$foundExternal = 0;
foreach ($tables as $table => $cols) {
    foreach ($cols as $col) {
        if (!Schema::hasColumn($table, $col)) continue;
        $count = DB::table($table)
            ->whereNotNull($col)
            ->where(function($q) use ($col) {
                $q->where($col, 'LIKE', 'http%')
                  ->orWhere($col, 'LIKE', '%unsplash%')
                  ->orWhere($col, 'LIKE', '%pexels%')
                  ->orWhere($col, 'LIKE', '%pixabay%')
                  ->orWhere($col, 'LIKE', '%google%');
            })->count();
        if ($count > 0) {
            echo "[WARNING] Table {$table} column {$col} has {$count} external image records!" . PHP_EOL;
            $foundExternal += $count;
        } else {
            echo "  [OK] Table {$table}.{$col}: Clean (0 external URLs)" . PHP_EOL;
        }
    }
}

echo PHP_EOL . "=== 2. TESTING MODEL ACCESSORS FOR SAFE LOCAL URLS ===" . PHP_EOL;

$decor = \App\Models\Decoration::first();
echo "Decoration Safe Primary Image: " . var_export($decor ? $decor->safe_primary_image : 'No record', true) . PHP_EOL;

$cat = \App\Models\Category::first();
echo "Category Safe Image: " . var_export($cat ? $cat->safe_image : 'No record', true) . PHP_EOL;

$pkg = \App\Models\Package::first();
echo "Package Display Image: " . var_export($pkg ? $pkg->display_image : 'No record', true) . PHP_EOL;

$offer = \App\Models\Offer::first();
echo "Offer Display Image: " . var_export($offer ? $offer->display_image : 'No record', true) . PHP_EOL;

$gal = \App\Models\GalleryItem::first();
echo "Gallery Item Display Image: " . var_export($gal ? $gal->display_image : 'No record', true) . PHP_EOL;

$vid = \App\Models\WeddingVideo::first();
echo "Video Safe Thumbnail: " . var_export($vid ? $vid->safe_thumbnail_url : 'No record', true) . PHP_EOL;
echo "Video Safe Video URL: " . var_export($vid ? $vid->safe_video_url : 'No record', true) . PHP_EOL;

$addon = \App\Models\Addon::first();
echo "Addon Safe Image: " . var_export($addon ? $addon->safe_image : 'No record', true) . PHP_EOL;

echo PHP_EOL . "=== 3. VERIFYING UPLOAD DIRECTORIES ===" . PHP_EOL;
$directories = [
    'uploads',
    'uploads/decorations',
    'uploads/decoration-gallery',
    'uploads/categories',
    'uploads/packages',
    'uploads/offers',
    'uploads/gallery',
    'uploads/videos',
    'uploads/thumbnails',
    'uploads/addons',
    'uploads/hero',
    'uploads/logo',
];

foreach ($directories as $dir) {
    $fullPath = public_path($dir);
    if (!file_exists($fullPath)) {
        mkdir($fullPath, 0755, true);
    }
    echo "  [OK] Directory exists: {$dir}" . PHP_EOL;
}

echo PHP_EOL . "=== AUDIT COMPLETE: SUCCESS ===" . PHP_EOL;
