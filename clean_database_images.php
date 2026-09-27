<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Cleaning external, hardcoded, and missing image paths from existing database records...\n";

function isValidLocalUpload(?string $path): bool {
    if (empty($path)) return false;
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return false;
    if (file_exists(public_path($path))) return true;
    if (file_exists(public_path('storage/' . $path))) return true;
    return false;
}

// 1. Decorations
$decs = DB::table('decorations')->get();
foreach ($decs as $d) {
    if (!empty($d->primary_image) && !isValidLocalUpload($d->primary_image)) {
        DB::table('decorations')->where('id', $d->id)->update(['primary_image' => null]);
        echo "Cleared decoration #{$d->id} ({$d->name}) primary_image\n";
    }
}

// 2. Decoration Images
$decImgs = DB::table('decoration_images')->get();
foreach ($decImgs as $di) {
    if (!empty($di->image_url) && !isValidLocalUpload($di->image_url)) {
        DB::table('decoration_images')->where('id', $di->id)->update(['image_url' => null]);
        echo "Cleared decoration_image #{$di->id} image_url\n";
    }
}

// 3. Categories
$cats = DB::table('categories')->get();
foreach ($cats as $c) {
    if (!empty($c->image_url) && !isValidLocalUpload($c->image_url)) {
        DB::table('categories')->where('id', $c->id)->update(['image_url' => null]);
        echo "Cleared category #{$c->id} ({$c->name}) image_url\n";
    }
}

// 4. Packages
$pkgs = DB::table('packages')->get();
foreach ($pkgs as $p) {
    $updates = [];
    if (!empty($p->image) && !isValidLocalUpload($p->image)) {
        $updates['image'] = null;
    }
    if (!empty($p->image_url) && !isValidLocalUpload($p->image_url)) {
        $updates['image_url'] = null;
    }
    if (!empty($updates)) {
        DB::table('packages')->where('id', $p->id)->update($updates);
        echo "Cleared package #{$p->id} ({$p->name}) images\n";
    }
}

// 5. Offers
$offers = DB::table('offers')->get();
foreach ($offers as $o) {
    $updates = [];
    if (!empty($o->image) && !isValidLocalUpload($o->image)) {
        $updates['image'] = null;
    }
    if (!empty($o->image_url) && !isValidLocalUpload($o->image_url)) {
        $updates['image_url'] = null;
    }
    if (!empty($updates)) {
        DB::table('offers')->where('id', $o->id)->update($updates);
        echo "Cleared offer #{$o->id} ({$o->title}) images\n";
    }
}

// 6. Gallery Items
$gallery = DB::table('gallery_items')->get();
foreach ($gallery as $g) {
    $updates = [];
    if (!empty($g->image) && !isValidLocalUpload($g->image)) {
        $updates['image'] = null;
    }
    if (!empty($g->image_url) && !isValidLocalUpload($g->image_url)) {
        $updates['image_url'] = null;
    }
    if (!empty($updates)) {
        DB::table('gallery_items')->where('id', $g->id)->update($updates);
        echo "Cleared gallery_item #{$g->id} ({$g->title}) images\n";
    }
}

// 7. Addons
$addons = DB::table('addons')->get();
foreach ($addons as $a) {
    if (!empty($a->image) && !isValidLocalUpload($a->image)) {
        DB::table('addons')->where('id', $a->id)->update(['image' => null]);
        echo "Cleared addon #{$a->id} ({$a->name}) image\n";
    }
}

// 8. Service Areas
if (Schema::hasTable('service_areas')) {
    $areas = DB::table('service_areas')->get();
    foreach ($areas as $sa) {
        if (isset($sa->image) && !empty($sa->image) && !isValidLocalUpload($sa->image)) {
            DB::table('service_areas')->where('id', $sa->id)->update(['image' => null]);
            echo "Cleared service_area #{$sa->id} image\n";
        }
    }
}

// 9. Wedding Videos
if (Schema::hasTable('wedding_videos')) {
    $videos = DB::table('wedding_videos')->get();
    foreach ($videos as $v) {
        $updates = [];
        if (!empty($v->video_path) && !isValidLocalUpload($v->video_path)) {
            $updates['video_path'] = null;
        }
        if (!empty($v->thumbnail_path) && !isValidLocalUpload($v->thumbnail_path)) {
            $updates['thumbnail_path'] = null;
        }
        if (!empty($updates)) {
            DB::table('wedding_videos')->where('id', $v->id)->update($updates);
            echo "Cleared wedding_video #{$v->id} ({$v->title}) video/thumbnail\n";
        }
    }
}

// 10. Site Settings
$settings = DB::table('site_settings')->get();
foreach ($settings as $s) {
    if (in_array($s->key, ['site_logo', 'site_favicon', 'hero_banner', 'site_watermark']) && !empty($s->value)) {
        if (!isValidLocalUpload($s->value)) {
            DB::table('site_settings')->where('id', $s->id)->update(['value' => null]);
            echo "Cleared site_setting {$s->key}\n";
        }
    }
}

echo "Database cleanup completed successfully.\n";
