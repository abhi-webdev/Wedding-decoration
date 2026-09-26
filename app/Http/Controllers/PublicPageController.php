<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\Package;
use App\Models\ServiceArea;
use App\Models\Review;
use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Faq;
use App\Models\SiteSetting;

class PublicPageController extends Controller
{
    public function decorations(Request $request, $category = null)
    {
        $categories = Category::orderBy('display_order')->get();
        $selectedCategory = null;

        $query = Decoration::with('category')->where('is_available', true);

        if ($category) {
            $selectedCategory = Category::where('slug', $category)->firstOrFail();
            $query->where('category_id', $selectedCategory->id);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        $decorations = $query->orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.decorations.index', compact('decorations', 'categories', 'selectedCategory', 'settings'));
    }

    public function decorationDetail($slug)
    {
        $decoration = Decoration::with(['category', 'images'])->where('slug', $slug)->firstOrFail();
        $relatedDecorations = Decoration::with('category')
            ->where('category_id', $decoration->category_id)
            ->where('id', '!=', $decoration->id)
            ->take(3)
            ->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.decorations.show', compact('decoration', 'relatedDecorations', 'settings'));
    }

    public function packages()
    {
        $packages = Package::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.packages.index', compact('packages', 'settings'));
    }

    public function packageDetail($slug)
    {
        $package = Package::where('slug', $slug)->firstOrFail();
        $otherPackages = Package::where('id', '!=', $package->id)->take(2)->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.packages.show', compact('package', 'otherPackages', 'settings'));
    }

    public function gallery(Request $request)
    {
        $category = $request->input('category', 'all');
        $query = GalleryItem::orderBy('display_order');
        
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $galleryItems = $query->get();
        $categories = Category::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.gallery.index', compact('galleryItems', 'categories', 'category', 'settings'));
    }

    public function offers()
    {
        $offers = Offer::where('is_active', true)->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.offers.index', compact('offers', 'settings'));
    }

    public function quote()
    {
        $categories = Category::orderBy('display_order')->get();
        $serviceAreas = ServiceArea::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.quote', compact('categories', 'serviceAreas', 'settings'));
    }

    public function about()
    {
        $biharCoreAreas = ServiceArea::where('category', 'Bihar Core')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.about', compact('biharCoreAreas', 'settings'));
    }

    public function contact()
    {
        $serviceAreas = ServiceArea::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.contact', compact('serviceAreas', 'settings'));
    }

    public function faq()
    {
        $faqs = Faq::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.faq', compact('faqs', 'settings'));
    }

    public function reviews()
    {
        $reviews = Review::orderBy('display_order')->get();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('pages.reviews', compact('reviews', 'settings'));
    }

    public function login()
    {
        return view('pages.auth.login');
    }

    public function register()
    {
        return view('pages.auth.register');
    }
}
