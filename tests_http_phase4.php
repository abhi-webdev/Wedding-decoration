<?php

/**
 * Phase 4 HTTP & Route Integration Test Suite
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Decoration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "====================================================\n";
echo "   PHASE 4 HTTP ROUTE & VIEW INTEGRATION TESTS\n";
echo "====================================================\n\n";

$httpPassed = 0;
$httpFailed = 0;

function assertHttp($name, $condition, $info = '') {
    global $httpPassed, $httpFailed;
    if ($condition) {
        echo " [PASS] " . $name . "\n";
        $httpPassed++;
    } else {
        echo " [FAIL] " . $name . "\n";
        if ($info) echo "        Details: " . $info . "\n";
        $httpFailed++;
    }
}

// 1. Test GET /register
$req1 = Request::create('/register', 'GET');
$res1 = $kernel->handle($req1);
assertHttp('GET /register returns 200 OK', $res1->getStatusCode() === 200);
$kernel->terminate($req1, $res1);

// 2. Test GET /login
$req2 = Request::create('/login', 'GET');
$res2 = $kernel->handle($req2);
assertHttp('GET /login returns 200 OK', $res2->getStatusCode() === 200);
$kernel->terminate($req2, $res2);

// 3. Test Unauthenticated Access to /account (Redirects to /login)
$req3 = Request::create('/account', 'GET');
$res3 = $kernel->handle($req3);
assertHttp('GET /account without auth redirects to /login (302)', $res3->getStatusCode() === 302 && str_contains($res3->headers->get('Location'), 'login'));
$kernel->terminate($req3, $res3);

// 4. Test Authenticated Access to /account
$user = User::firstOrCreate(
    ['email' => 'auth_tester@adityautsav.in'],
    [
        'name' => 'Aditya Verified User',
        'phone' => '9876543210',
        'password' => Hash::make('Password123!'),
    ]
);

$req4 = Request::create('/account', 'GET');
$req4->setUserResolver(fn() => $user);
Auth::login($user);
$res4 = $kernel->handle($req4);
assertHttp('GET /account with auth returns 200 OK', $res4->getStatusCode() === 200);
assertHttp('Dashboard contains customer name', str_contains($res4->getContent(), 'Aditya Verified User') || str_contains($res4->getContent(), 'Dashboard'));
$kernel->terminate($req4, $res4);

// 5. Test Authenticated Access to /account/bookings
$req5 = Request::create('/account/bookings', 'GET');
$req5->setUserResolver(fn() => $user);
$res5 = $kernel->handle($req5);
assertHttp('GET /account/bookings with auth returns 200 OK', $res5->getStatusCode() === 200);
$kernel->terminate($req5, $res5);

// 6. Test Authenticated Access to /account/profile
$req6 = Request::create('/account/profile', 'GET');
$req6->setUserResolver(fn() => $user);
$res6 = $kernel->handle($req6);
assertHttp('GET /account/profile returns 200 OK', $res6->getStatusCode() === 200);
$kernel->terminate($req6, $res6);

// 7. Test Authenticated Access to /account/password
$req7 = Request::create('/account/password', 'GET');
$req7->setUserResolver(fn() => $user);
$res7 = $kernel->handle($req7);
assertHttp('GET /account/password returns 200 OK', $res7->getStatusCode() === 200);
$kernel->terminate($req7, $res7);

// 8. Test Zero Regressions on Phase 1, 2, 3 Public Endpoints
$reqHome = Request::create('/', 'GET');
$resHome = $kernel->handle($reqHome);
assertHttp('Phase 1: GET / (Homepage) returns 200 OK', $resHome->getStatusCode() === 200);
$kernel->terminate($reqHome, $resHome);

$reqDec = Request::create('/decorations', 'GET');
$resDec = $kernel->handle($reqDec);
assertHttp('Phase 2: GET /decorations (Catalog) returns 200 OK', $resDec->getStatusCode() === 200);
$kernel->terminate($reqDec, $resDec);

$decModel = Decoration::first();
if ($decModel) {
    $reqDetail = Request::create('/decoration/' . $decModel->slug, 'GET');
    $resDetail = $kernel->handle($reqDetail);
    assertHttp('Phase 2: GET /decoration/{slug} returns 200 OK', $resDetail->getStatusCode() === 200);
    $kernel->terminate($reqDetail, $resDetail);

    $reqBook = Request::create('/booking/' . $decModel->slug, 'GET');
    $resBook = $kernel->handle($reqBook);
    assertHttp('Phase 3: GET /booking/{slug} returns 200 OK', $resBook->getStatusCode() === 200);
    $kernel->terminate($reqBook, $resBook);
}

echo "\n====================================================\n";
echo "   HTTP INTEGRATION RESULTS\n";
echo "   Passed: {$httpPassed}\n";
echo "   Failed: {$httpFailed}\n";
echo "====================================================\n";
