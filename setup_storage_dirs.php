<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

$dirs = [
    'decorations',
    'decoration-gallery',
    'categories',
    'packages',
    'offers',
    'gallery',
    'videos',
    'thumbnails',
    'video-thumbnails',
    'hero',
    'logo',
    'addons',
    'site',
];

foreach ($dirs as $d) {
    Storage::disk('public')->makeDirectory('uploads/' . $d);
    echo "Created/Verified: storage/app/public/uploads/{$d}\n";
}
