<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\WeddingVideo;
use App\Models\Package;
use App\Models\ServiceArea;
use App\Models\Review;
use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Faq;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    /**
     * Streamlined Homepage for Aditya Utsav (Phase 8 Landing Page Strategy)
     */
    public function index()
    {
        // 1. Ceremony Categories (Key 6)
        $categories = Category::orderBy('display_order')->take(6)->get();

        // 2. Featured Wedding Decorations (Top 6)
        $featuredDecorations = Decoration::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->take(6)
            ->get();

        if ($featuredDecorations->isEmpty()) {
            $featuredDecorations = Decoration::with('category')
                ->where('is_active', true)
                ->orderBy('display_order')
                ->take(6)
                ->get();
        }

        // 3. Short Videos & Reels for Homepage
        $homepageReels = WeddingVideo::with('category')
            ->homepage()
            ->orderBy('sort_order', 'asc')
            ->take(6)
            ->get();

        // 4. Curated Packages (Top 3)
        $packages = Package::where('is_active', true)
            ->orderBy('display_order')
            ->take(3)
            ->get();

        // 5. Active Seasonal Offer
        $offer = Offer::where('is_active', true)->first();

        // 6. Compact Gallery Preview (6 Strong Photos)
        $galleryItems = GalleryItem::where('is_active', true)
            ->orderBy('display_order')
            ->take(6)
            ->get();

        // 7. Testimonials Preview (Top 3)
        $reviews = Review::where('is_featured', true)
            ->orderBy('display_order')
            ->take(3)
            ->get();

        // 8. Key Core Service Areas
        $coreServiceAreas = ServiceArea::where('is_active', true)
            ->orderBy('display_order')
            ->take(6)
            ->get();

        // 9. Concise FAQs Preview (Top 3)
        $faqs = Faq::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->take(3)
            ->get();

        if ($faqs->isEmpty()) {
            $faqs = Faq::where('is_active', true)->take(3)->get();
        }

        // 10. Site Settings
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.home', compact(
            'categories',
            'featuredDecorations',
            'homepageReels',
            'packages',
            'offer',
            'galleryItems',
            'reviews',
            'coreServiceAreas',
            'faqs',
            'settings'
        ));
    }
}
