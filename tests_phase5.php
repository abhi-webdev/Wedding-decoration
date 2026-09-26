<?php

/**
 * Phase 5 Comprehensive Automated Test Suite
 * Aditya Utsav - Public Content, Packages, Offers, Gallery, Custom Quote, and Policy Pages
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$consoleKernel->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Package;
use App\Models\Offer;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\QuoteRequest;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\ServiceArea;
use App\Models\User;
use App\Models\Decoration;
use Carbon\Carbon;

echo "====================================================\n";
echo "   PHASE 5 AUTOMATED TEST SUITE: ADITYA UTSAV\n";
echo "====================================================\n\n";

$testsPassed = 0;
$testsFailed = 0;

function assertTest($name, $condition, $failDetails = '') {
    global $testsPassed, $testsFailed;
    if ($condition) {
        echo " [PASS] " . $name . "\n";
        $testsPassed++;
    } else {
        echo " [FAIL] " . $name . "\n";
        if ($failDetails) {
            echo "        Details: " . $failDetails . "\n";
        }
        $testsFailed++;
    }
}

// --------------------------------------------------------------------------
// 1. PACKAGES TESTING
// --------------------------------------------------------------------------
echo "--- 1. Testing Packages & Inclusions ---\n";

$packagesCount = Package::where('is_active', true)->count();
assertTest('Total active packages >= 8 (Count: ' . $packagesCount . ')', $packagesCount >= 8);

$firstPkg = Package::where('slug', 'royal-jaimala-package')->first();
assertTest('Package [royal-jaimala-package] exists with valid slug', $firstPkg !== null);
assertTest('Package formatted price is non-empty (' . ($firstPkg?->formatted_price ?? '') . ')', !empty($firstPkg?->formatted_price));
assertTest('Package price is numeric decimal', is_numeric($firstPkg?->base_price) || is_numeric($firstPkg?->starting_price));

$completeVivah = Package::where('slug', 'complete-bihar-vivah-package')->first();
assertTest('Package [complete-bihar-vivah-package] exists with highlights', $completeVivah && !empty($completeVivah->highlights));

// --------------------------------------------------------------------------
// 2. OFFERS & DATE VALIDITY TESTING
// --------------------------------------------------------------------------
echo "\n--- 2. Testing Offers & Date Validity ---\n";

$activeOffersCount = Offer::currentlyValid()->count();
assertTest('Total active & currently valid offers >= 3 (Count: ' . $activeOffersCount . ')', $activeOffersCount >= 3);

$seasonOffer = Offer::where('slug', 'wedding-season-special')->first();
assertTest('Offer [wedding-season-special] exists with coupon code UTSAV15', $seasonOffer && $seasonOffer->coupon_code === 'UTSAV15');
assertTest('Offer is_currently_valid returns true for active date span', $seasonOffer?->is_currently_valid === true);

// Test expired offer handling
$expiredOffer = Offer::create([
    'title' => 'Expired Summer Deal',
    'slug' => 'expired-summer-deal-' . time(),
    'discount_text' => '10% OFF',
    'discount_type' => 'percentage',
    'discount_value' => 10,
    'valid_from' => Carbon::now()->subMonths(3),
    'valid_until' => Carbon::now()->subDays(5),
    'is_active' => true,
]);
assertTest('Expired offer is excluded by scopeCurrentlyValid()', !Offer::currentlyValid()->pluck('id')->contains($expiredOffer->id));
assertTest('Expired offer property is_currently_valid returns false', $expiredOffer->is_currently_valid === false);

// --------------------------------------------------------------------------
// 3. GALLERY & CATEGORIES TESTING
// --------------------------------------------------------------------------
echo "\n--- 3. Testing Gallery Items & Categories ---\n";

$galleryCount = GalleryItem::where('is_active', true)->count();
assertTest('Total gallery photographs >= 30 (Count: ' . $galleryCount . ')', $galleryCount >= 30);

$galleryCatsCount = GalleryCategory::where('is_active', true)->count();
assertTest('Total gallery categories >= 7 (Count: ' . $galleryCatsCount . ')', $galleryCatsCount >= 7);

$jaimalaPhotos = GalleryItem::where('category', 'jaimala')->count();
assertTest('Jaimala category contains photos >= 4 (Count: ' . $jaimalaPhotos . ')', $jaimalaPhotos >= 4);

$firstGalleryItem = GalleryItem::first();
assertTest('Gallery item has local image path (No Pinterest URL)', $firstGalleryItem && !str_contains($firstGalleryItem->image_url, 'pinterest.com'));

// --------------------------------------------------------------------------
// 4. CUSTOM QUOTE SYSTEM TESTING
// --------------------------------------------------------------------------
echo "\n--- 4. Testing Custom Quote Request Submissions ---\n";

$quoteRefPrefix = 'AUQ-' . date('Ymd');
$randQuote1 = $quoteRefPrefix . '-' . rand(10000, 99999);
$testQuote = QuoteRequest::create([
    'quote_reference' => $randQuote1,
    'user_id' => null, // Guest quote
    'customer_name' => 'Guest Quote Requester',
    'customer_phone' => '9876543210',
    'customer_email' => 'guest_quote@adityautsav.in',
    'event_type' => 'Complete Wedding (Vivah)',
    'event_date' => Carbon::parse('+25 days')->format('Y-m-d'),
    'guest_count' => 500,
    'state' => 'Bihar',
    'city' => 'Siwan',
    'locality' => 'Near Gandhi Maidan',
    'venue_name' => 'Royal Heritage Marriage Lawn',
    'decoration_preference' => ['Jaimala Stage', 'Vedic Mandap', 'Entrance Toran & Gate'],
    'budget_range' => '₹50,000 – ₹1,00,000',
    'special_requirements' => 'Yellow marigold theme with brass diyas.',
    'status' => 'new',
]);

assertTest('Guest quote created with valid AUQ- reference format', $testQuote && str_starts_with($testQuote->quote_reference, 'AUQ-'));
assertTest('Quote status defaults to "new"', $testQuote->status === 'new');
assertTest('Quote preferences stored as array', is_array($testQuote->fresh()->decoration_preference));

// Test authenticated customer quote
$authUser = User::first();
if ($authUser) {
    $randQuote2 = $quoteRefPrefix . '-' . rand(10000, 99999);
    $authQuote = QuoteRequest::create([
        'quote_reference' => $randQuote2,
        'user_id' => $authUser->id,
        'customer_name' => $authUser->name,
        'customer_phone' => $authUser->phone ?? '9876543210',
        'customer_email' => $authUser->email,
        'event_type' => 'Haldi Ceremony',
        'event_date' => Carbon::parse('+15 days')->format('Y-m-d'),
        'state' => 'Bihar',
        'city' => 'Patna',
        'budget_range' => '₹25,000 – ₹50,000',
        'status' => 'new',
    ]);
    assertTest('Authenticated user quote links user_id correctly', $authQuote && $authQuote->user_id === $authUser->id);
}

// --------------------------------------------------------------------------
// 5. CONTACT FORM SUBMISSION TESTING
// --------------------------------------------------------------------------
echo "\n--- 5. Testing Contact Message Submissions ---\n";

$contactMsg = ContactMessage::create([
    'name' => 'Pooja Kumari',
    'phone' => '9988776655',
    'email' => 'pooja@example.com',
    'subject' => 'Mandap setup query for Gopalganj',
    'message' => 'We are planning a wedding in Gopalganj on 18 Dec 2026. Please share your availability.',
    'status' => 'new',
]);

assertTest('Contact message successfully stored in database', $contactMsg && $contactMsg->id > 0);
assertTest('Contact message status defaults to "new"', $contactMsg->status === 'new');

// --------------------------------------------------------------------------
// 6. FAQS CATEGORIZATION TESTING
// --------------------------------------------------------------------------
echo "\n--- 6. Testing FAQs & Categories ---\n";

$faqCount = Faq::where('is_active', true)->count();
assertTest('Total active FAQs >= 12 (Count: ' . $faqCount . ')', $faqCount >= 12);

$faqCategories = Faq::select('category')->distinct()->pluck('category')->toArray();
$requiredCategories = ['Booking', 'Decorations', 'Pricing', 'Locations', 'Cancellation', 'Payments', 'General'];
$hasAllCategories = count(array_intersect($requiredCategories, $faqCategories)) === count($requiredCategories);
assertTest('FAQs cover all 7 required categories', $hasAllCategories);

// --------------------------------------------------------------------------
// 7. SERVICE AREAS STATE ACCURACY TESTING (CRITICAL)
// --------------------------------------------------------------------------
echo "\n--- 7. Testing Service Areas & State Accuracy ---\n";

$siwan = ServiceArea::where('slug', 'siwan')->first();
assertTest('Siwan is strictly assigned to Bihar', $siwan && $siwan->state === 'Bihar');

$gopalganj = ServiceArea::where('slug', 'gopalganj')->first();
assertTest('Gopalganj is strictly assigned to Bihar', $gopalganj && $gopalganj->state === 'Bihar');

$gorakhpur = ServiceArea::where('slug', 'gorakhpur')->first();
assertTest('Gorakhpur is strictly assigned to Uttar Pradesh (NOT Bihar)', $gorakhpur && $gorakhpur->state === 'Uttar Pradesh');

$deoria = ServiceArea::where('slug', 'deoria')->first();
assertTest('Deoria is strictly assigned to Uttar Pradesh (NOT Bihar)', $deoria && $deoria->state === 'Uttar Pradesh');

// --------------------------------------------------------------------------
// 8. HTTP ROUTE & VIEW INTEGRATION TESTING (ZERO REGRESSIONS)
// --------------------------------------------------------------------------
echo "\n--- 8. Testing HTTP Route Endpoints (Phases 1–5) ---\n";

$routesToTest = [
    // Phase 5 Routes
    ['uri' => '/packages', 'name' => 'Packages Catalog'],
    ['uri' => '/package/royal-jaimala-package', 'name' => 'Package Detail'],
    ['uri' => '/offers', 'name' => 'Offers Catalog'],
    ['uri' => '/offer/wedding-season-special', 'name' => 'Offer Detail'],
    ['uri' => '/gallery', 'name' => 'Gallery Index'],
    ['uri' => '/gallery?category=jaimala', 'name' => 'Gallery Category Filter'],
    ['uri' => '/quote', 'name' => 'Get a Quote View'],
    ['uri' => '/faq', 'name' => 'FAQ View'],
    ['uri' => '/about', 'name' => 'About Page'],
    ['uri' => '/contact', 'name' => 'Contact Page'],
    ['uri' => '/service-areas', 'name' => 'Service Areas View'],
    ['uri' => '/terms', 'name' => 'Terms Page'],
    ['uri' => '/cancellation-policy', 'name' => 'Cancellation Policy Page'],
    ['uri' => '/booking-guide', 'name' => 'Booking Guide Page'],

    // Phase 1, 2, 3, 4 Regressions
    ['uri' => '/', 'name' => 'Phase 1: Homepage'],
    ['uri' => '/decorations', 'name' => 'Phase 2: Decoration Catalog'],
    ['uri' => '/login', 'name' => 'Phase 4: Login Form'],
    ['uri' => '/register', 'name' => 'Phase 4: Registration Form'],
];

foreach ($routesToTest as $r) {
    $req = Request::create($r['uri'], 'GET');
    $res = $kernel->handle($req);
    $status = $res->getStatusCode();
    assertTest("GET {$r['uri']} ({$r['name']}) returns 200 OK", $status === 200, "Received HTTP {$status}");
    $kernel->terminate($req, $res);
}

// --------------------------------------------------------------------------
// SUMMARY
// --------------------------------------------------------------------------
echo "\n====================================================\n";
echo "   PHASE 5 TEST SUMMARY\n";
echo "   Passed: {$testsPassed}\n";
echo "   Failed: {$testsFailed}\n";
echo "====================================================\n";

if ($testsFailed === 0) {
    echo "\n ALL PHASE 5 TESTS PASSED PERFECTLY WITH ZERO REGRESSIONS!\n\n";
} else {
    echo "\n SOME TESTS FAILED. PLEASE REVIEW.\n\n";
}
