<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$decorations = App\Models\Decoration::all();
echo "Total decorations: " . $decorations->count() . "\n";
foreach ($decorations as $d) {
    echo "ID: {$d->id} | Slug: '{$d->slug}' | Name: {$d->name} | Cat: {$d->category_id} | Active: " . ($d->is_active ? 'YES' : 'NO') . "\n";
}
