<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$cols = [
    'decorations' => ['primary_image'],
    'decoration_images' => ['image_url'],
    'categories' => ['image_url'],
    'packages' => ['image', 'image_url'],
    'offers' => ['image', 'image_url'],
    'gallery_items' => ['image', 'image_url'],
    'addons' => ['image'],
];

if (Illuminate\Support\Facades\Schema::hasTable('wedding_videos')) {
    $cols['wedding_videos'] = ['video_path', 'thumbnail_path'];
}

foreach ($cols as $table => $columns) {
    foreach ($columns as $col) {
        $info = DB::select("SHOW COLUMNS FROM `{$table}` LIKE '{$col}'");
        if (!empty($info)) {
            echo "{$table}.{$col} -> Nullable: {$info[0]->Null}, Type: {$info[0]->Type}\n";
        }
    }
}
