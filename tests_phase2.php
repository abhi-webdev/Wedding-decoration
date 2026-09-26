<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$allDecorations = App\Models\Decoration::all();

$urls = [
    // 1. Homepage & Static Pages
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/packages',
    'http://127.0.0.1:8000/gallery',
    'http://127.0.0.1:8000/offers',
    'http://127.0.0.1:8000/quote',
    'http://127.0.0.1:8000/about',
    'http://127.0.0.1:8000/contact',

    // 2. Catalog Index & Category Endpoints
    'http://127.0.0.1:8000/decorations',
    'http://127.0.0.1:8000/decorations/jaimala',
    'http://127.0.0.1:8000/decorations/wedding-mandap',
    'http://127.0.0.1:8000/decorations/haldi',
    'http://127.0.0.1:8000/decorations/mehendi',
    'http://127.0.0.1:8000/decorations/sangeet',
    'http://127.0.0.1:8000/decorations/reception',
    'http://127.0.0.1:8000/decorations/tilak',
    'http://127.0.0.1:8000/decorations/baraat-entry',

    // 3. Search & Keywords
    'http://127.0.0.1:8000/decorations?search=mandap',
    'http://127.0.0.1:8000/decorations?search=floral',
    'http://127.0.0.1:8000/decorations?search=jaimala',
    'http://127.0.0.1:8000/decorations?search=Siwan',

    // 4. Filters
    'http://127.0.0.1:8000/decorations?category=jaimala&style=Royal',
    'http://127.0.0.1:8000/decorations?price_range=under_50k',
    'http://127.0.0.1:8000/decorations?price_range=50k_80k',
    'http://127.0.0.1:8000/decorations?price_range=80k_120k',
    'http://127.0.0.1:8000/decorations?price_range=above_120k',
    'http://127.0.0.1:8000/decorations?location=bihar',
    'http://127.0.0.1:8000/decorations?location=up',
    'http://127.0.0.1:8000/decorations?style=Traditional',
    'http://127.0.0.1:8000/decorations?color=Yellow',
    'http://127.0.0.1:8000/decorations?guest_capacity=200-500%20Guests',

    // 5. Sorting
    'http://127.0.0.1:8000/decorations?sort=price_low',
    'http://127.0.0.1:8000/decorations?sort=price_high',
    'http://127.0.0.1:8000/decorations?sort=newest',
    'http://127.0.0.1:8000/decorations?sort=name_asc',
    'http://127.0.0.1:8000/decorations?sort=name_desc',

    // 6. Pagination with filters preserved
    'http://127.0.0.1:8000/decorations?page=2',
    'http://127.0.0.1:8000/decorations?category=jaimala&style=Royal&page=1',
];

// Add ALL seeded decoration slugs dynamically!
foreach ($allDecorations as $dec) {
    $urls[] = "http://127.0.0.1:8000/decoration/{$dec->slug}";
}

// 404 test
$urls[] = 'http://127.0.0.1:8000/decoration/non-existent-test-slug';

echo "========================================================\n";
echo "       ADITYA UTSAV PHASE 2 VERIFICATION SUITE         \n";
echo "========================================================\n\n";

$passCount = 0;
$failCount = 0;

foreach ($urls as $u) {
    $ctx = stream_context_create([
        'http' => [
            'ignore_errors' => true,
            'timeout' => 8
        ]
    ]);
    $content = @file_get_contents($u, false, $ctx);
    $status = isset($http_response_header[0]) ? $http_response_header[0] : 'ERROR';
    
    $is404Test = strpos($u, 'non-existent-test-slug') !== false;
    $passed = false;
    
    if ($is404Test) {
        if (strpos($status, '404') !== false) {
            $passed = true;
        }
    } else {
        if (strpos($status, '200') !== false) {
            $passed = true;
        }
    }
    
    if ($passed) {
        $passCount++;
        echo "[PASS] $status -> $u\n";
    } else {
        $failCount++;
        echo "[FAIL] $status -> $u\n";
    }
}

echo "\n--------------------------------------------------------\n";
echo "SUMMARY: Passed: $passCount | Failed: $failCount | Total: " . count($urls) . "\n";
echo "--------------------------------------------------------\n";

if ($failCount === 0) {
    echo "SUCCESS: ALL " . count($urls) . " PHASE 2 ROUTES & TESTS PASSED (100% PERFECT)!\n";
} else {
    echo "SOME TESTS FAILED! Please inspect.\n";
}
