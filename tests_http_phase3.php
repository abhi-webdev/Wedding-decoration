<?php

$urls = [
    // 1. Homepage & Static Pages (Phase 1)
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/packages',
    'http://127.0.0.1:8000/gallery',
    'http://127.0.0.1:8000/offers',
    'http://127.0.0.1:8000/quote',
    'http://127.0.0.1:8000/about',
    'http://127.0.0.1:8000/contact',

    // 2. Catalog & Categories (Phase 2)
    'http://127.0.0.1:8000/decorations',
    'http://127.0.0.1:8000/decorations/jaimala',
    'http://127.0.0.1:8000/decorations/wedding-mandap',
    'http://127.0.0.1:8000/decorations/haldi',
    'http://127.0.0.1:8000/decorations/mehendi',
    'http://127.0.0.1:8000/decorations/sangeet',
    'http://127.0.0.1:8000/decorations/reception',
    'http://127.0.0.1:8000/decoration/traditional-bihar-jaimala-stage',
    'http://127.0.0.1:8000/decoration/classic-red-gold-wedding-mandap',

    // 3. Phase 3: Booking Pages
    'http://127.0.0.1:8000/booking/traditional-bihar-jaimala-stage',
    'http://127.0.0.1:8000/booking/classic-red-gold-wedding-mandap',
    'http://127.0.0.1:8000/booking/marigold-haldi-celebration-setup',
    'http://127.0.0.1:8000/booking/traditional-mehendi-courtyard-setup',
    'http://127.0.0.1:8000/booking/check-availability?decoration_id=1&event_date=2026-11-15',
];

echo "========================================================\n";
echo "      ADITYA UTSAV HTTP INTEGRATION TEST SUITE          \n";
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
    
    if (strpos($status, '200') !== false) {
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
    echo "SUCCESS: ALL ENDPOINTS PASSED WITH 100% SUCCESS!\n";
} else {
    echo "SOME ENDPOINTS FAILED. Please inspect.\n";
}
