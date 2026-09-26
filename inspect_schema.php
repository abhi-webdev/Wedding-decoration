<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = [
    'users',
    'categories',
    'addons',
    'decorations',
    'decoration_images',
    'decoration_items',
    'decoration_addons',
    'packages',
    'package_decorations',
    'offers',
    'gallery_items',
    'gallery_categories',
    'service_areas',
    'faqs',
    'quote_requests',
    'contact_messages',
    'booking_cancellation_requests',
    'booking_reschedule_requests',
    'bookings',
    'booking_addons',
    'booking_status_histories',
    'site_settings',
    'admin_activity_logs'
];

foreach ($tables as $t) {
    echo "=== $t ===\n";
    if (Schema::hasTable($t)) {
        echo implode(', ', Schema::getColumnListing($t)) . "\n\n";
    } else {
        echo "TABLE DOES NOT EXIST!\n\n";
    }
}
