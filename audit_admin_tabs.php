<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Decoration;
use App\Models\Category;
use App\Models\Addon;
use App\Models\Package;
use App\Models\Offer;
use App\Models\GalleryItem;
use App\Models\Faq;
use App\Models\ServiceArea;
use App\Models\QuoteRequest;
use App\Models\ContactMessage;
use App\Models\CancellationRequest;
use App\Models\RescheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$admin = User::where('role', 'super_admin')->first();
if (!$admin) {
    echo "ERROR: Super admin not found!\n";
    exit(1);
}

echo "=======================================================\n";
echo "   ADITYA UTSAV — ADMIN PANEL COMPLETE TAB AUDIT\n";
echo "=======================================================\n\n";

$tabsToTest = [
    'Dashboard' => '/admin/dashboard',
    'Bookings (Index)' => '/admin/bookings',
    'Customers (Index)' => '/admin/customers',
    'Decorations (Index)' => '/admin/decorations',
    'Decorations (Create)' => '/admin/decorations/create',
    'Categories (Index)' => '/admin/categories',
    'Categories (Create)' => '/admin/categories/create',
    'Addons (Index)' => '/admin/addons',
    'Addons (Create)' => '/admin/addons/create',
    'Packages (Index)' => '/admin/packages',
    'Packages (Create)' => '/admin/packages/create',
    'Offers (Index)' => '/admin/offers',
    'Offers (Create)' => '/admin/offers/create',
    'Gallery (Index)' => '/admin/gallery',
    'Gallery (Create)' => '/admin/gallery/create',
    'FAQs (Index)' => '/admin/faqs',
    'FAQs (Create)' => '/admin/faqs/create',
    'Service Areas (Index)' => '/admin/service-areas',
    'Service Areas (Create)' => '/admin/service-areas/create',
    'Quote Requests (Index)' => '/admin/quotes',
    'Contact Messages (Index)' => '/admin/messages',
    'Cancellation Requests' => '/admin/cancellation-requests',
    'Reschedule Requests' => '/admin/reschedule-requests',
    'Site Settings' => '/admin/settings',
    'Admin Staff / Users' => '/admin/users',
    'Activity Logs' => '/admin/activity-logs',
];

$firstBooking = Booking::first();
if ($firstBooking) {
    $tabsToTest['Bookings (Show)'] = "/admin/bookings/{$firstBooking->id}";
}

$firstCustomer = User::where('role', 'customer')->first();
if ($firstCustomer) {
    $tabsToTest['Customers (Show)'] = "/admin/customers/{$firstCustomer->id}";
}

$firstDeco = Decoration::first();
if ($firstDeco) {
    $tabsToTest['Decorations (Edit)'] = "/admin/decorations/{$firstDeco->id}/edit";
}

$firstCategory = Category::first();
if ($firstCategory) {
    $tabsToTest['Categories (Edit)'] = "/admin/categories/{$firstCategory->id}/edit";
}

$firstAddon = Addon::first();
if ($firstAddon) {
    $tabsToTest['Addons (Edit)'] = "/admin/addons/{$firstAddon->id}/edit";
}

$firstPackage = Package::first();
if ($firstPackage) {
    $tabsToTest['Packages (Edit)'] = "/admin/packages/{$firstPackage->id}/edit";
}

$firstOffer = Offer::first();
if ($firstOffer) {
    $tabsToTest['Offers (Edit)'] = "/admin/offers/{$firstOffer->id}/edit";
}

$firstGallery = GalleryItem::first();
if ($firstGallery) {
    $tabsToTest['Gallery (Edit)'] = "/admin/gallery/{$firstGallery->id}/edit";
}

$firstFaq = Faq::first();
if ($firstFaq) {
    $tabsToTest['FAQs (Edit)'] = "/admin/faqs/{$firstFaq->id}/edit";
}

$firstArea = ServiceArea::first();
if ($firstArea) {
    $tabsToTest['Service Areas (Edit)'] = "/admin/service-areas/{$firstArea->id}/edit";
}

$firstQuote = QuoteRequest::first();
if ($firstQuote) {
    $tabsToTest['Quote Requests (Show)'] = "/admin/quotes/{$firstQuote->id}";
}

$firstMsg = ContactMessage::first();
if ($firstMsg) {
    $tabsToTest['Contact Messages (Show)'] = "/admin/messages/{$firstMsg->id}";
}

$passed = 0;
$failed = 0;
$errors = [];

foreach ($tabsToTest as $label => $uri) {
    try {
        $req = Request::create($uri, 'GET');
        $session = $app['session']->driver();
        $session->start();
        $req->setLaravelSession($session);
        $app->instance('request', $req);
        Auth::guard('web')->setUser($admin);
        
        $response = $kernel->handle($req);
        $status = $response->getStatusCode();
        
        if ($status === 200) {
            echo " [PASS] $label ($uri) -> HTTP $status OK\n";
            $passed++;
        } elseif ($status === 302) {
            $redir = $response->headers->get('Location');
            echo " [REDIRECT 302] $label ($uri) -> $redir\n";
            $failed++;
            $errors[$label] = "Redirected to $redir (Possible auth / middleware issue)";
        } else {
            echo " [FAIL] $label ($uri) -> HTTP $status\n";
            $failed++;
            $errors[$label] = "HTTP status $status: " . substr(strip_tags($response->getContent()), 0, 400);
        }
    } catch (\Throwable $e) {
        echo " [FAIL] $label ($uri) -> EXCEPTION: {$e->getMessage()}\n";
        $failed++;
        $errors[$label] = "Exception in {$e->getFile()}:{$e->getLine()} — {$e->getMessage()}";
    }
}

echo "\n-------------------------------------------------------\n";
echo "Total Admin Routes Tested: " . count($tabsToTest) . "\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";
echo "-------------------------------------------------------\n";

if ($failed > 0) {
    echo "\nDetailed Failures:\n";
    foreach ($errors as $lbl => $err) {
        echo "[$lbl]: $err\n\n";
    }
}
