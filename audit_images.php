<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$checks = [
    'decorations' => ['primary_image'],
    'decoration_images' => ['image_url'],
    'categories' => ['image_url'],
    'packages' => ['image', 'image_url'],
    'offers' => ['image', 'image_url'],
    'gallery_items' => ['image', 'image_url'],
    'addons' => ['image'],
    'service_areas' => ['image'],
    'site_settings' => ['value'],
];

if (Illuminate\Support\Facades\Schema::hasTable('wedding_videos')) {
    $checks['wedding_videos'] = ['video_path', 'thumbnail_path'];
}

$out = "";
foreach ($checks as $table => $columns) {
    $out .= "=== Table: $table ===\n";
    $rows = DB::table($table)->get();
    $out .= "Count: " . count($rows) . "\n";
    foreach ($rows as $row) {
        $info = "ID {$row->id}: ";
        foreach ($columns as $col) {
            $val = $row->$col ?? 'NULL';
            $info .= "[$col => $val] ";
        }
        $out .= $info . "\n";
    }
    $out .= "\n";
}
file_put_contents('audit_report.txt', $out);
echo "Written to audit_report.txt\n";
