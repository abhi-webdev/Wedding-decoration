<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Decoration;
use App\Models\Addon;
use App\Models\ServiceArea;
use App\Models\SiteSetting;

class DecorationController extends Controller
{
    /**
     * Display a listing of decorations with filtering, search, sorting and pagination.
     */
    public function index(Request $request)
    {
        $categories = Category::withCount(['decorations' => function ($q) {
            $q->where('is_active', true)->where('is_available', true);
        }])->orderBy('display_order')->get();

        $query = Decoration::with(['category', 'images', 'items', 'addons'])
            ->where('is_active', true)
            ->where('is_available', true);

        // 1. Category Filter (slug or ID)
        $selectedCategory = null;
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $catParam = $request->input('category');
            $selectedCategory = Category::where('slug', $catParam)->orWhere('id', $catParam)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // 2. Keyword Search (Server-side)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('style', 'like', "%{$search}%")
                  ->orWhere('primary_color', 'like', "%{$search}%")
                  ->orWhere('color_theme', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQ) use ($search) {
                      $catQ->where('name', 'like', "%{$search}%")
                           ->orWhere('slug', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Price Filter (Preset or Min/Max)
        if ($request->filled('price_range')) {
            switch ($request->input('price_range')) {
                case 'under_50k':
                    $query->where(function ($q) {
                        $q->where('base_price', '<', 50000)
                          ->orWhere('starting_price', '<', 50000);
                    });
                    break;
                case '50k_80k':
                    $query->where(function ($q) {
                        $q->whereBetween('base_price', [50000, 80000])
                          ->orWhereBetween('starting_price', [50000, 80000]);
                    });
                    break;
                case '80k_120k':
                    $query->where(function ($q) {
                        $q->whereBetween('base_price', [80000, 120000])
                          ->orWhereBetween('starting_price', [80000, 120000]);
                    });
                    break;
                case 'above_120k':
                    $query->where(function ($q) {
                        $q->where('base_price', '>=', 120000)
                          ->orWhere('starting_price', '>=', 120000);
                    });
                    break;
            }
        }

        if ($request->filled('min_price')) {
            $min = (float)$request->input('min_price');
            $query->where(function ($q) use ($min) {
                $q->where('base_price', '>=', $min)
                  ->orWhere('starting_price', '>=', $min);
            });
        }

        if ($request->filled('max_price')) {
            $max = (float)$request->input('max_price');
            $query->where(function ($q) use ($max) {
                $q->where('base_price', '<=', $max)
                  ->orWhere('starting_price', '<=', $max);
            });
        }

        // 4. Location Filter
        if ($request->filled('location') && $request->input('location') !== 'all') {
            $loc = $request->input('location');
            if ($loc === 'bihar') {
                $query->where(function ($q) {
                    $q->where('location', 'like', '%Bihar%')
                      ->orWhereIn('location', ['Siwan', 'Mairwa', 'Gopalganj', 'Chapra / Saran', 'Chapra', 'Saran', 'Barharia', 'Maharajganj']);
                });
            } elseif ($loc === 'up') {
                $query->where(function ($q) {
                    $q->where('location', 'like', '%Uttar Pradesh%')
                      ->orWhere('location', 'like', '%UP%')
                      ->orWhereIn('location', ['Gorakhpur', 'Deoria', 'Bhatpar Rani', 'Salempur']);
                });
            } else {
                $query->where('location', 'like', "%{$loc}%");
            }
        }

        // 5. Style Filter
        if ($request->filled('style') && $request->input('style') !== 'all') {
            $query->where('style', $request->input('style'));
        }

        // 6. Color Filter
        if ($request->filled('color') && $request->input('color') !== 'all') {
            $query->where('primary_color', 'like', "%" . $request->input('color') . "%");
        }

        // 7. Guest Capacity Filter
        if ($request->filled('guest_capacity') && $request->input('guest_capacity') !== 'all') {
            $query->where('guest_capacity', $request->input('guest_capacity'));
        }

        // 8. Sorting
        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(base_price, starting_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(base_price, starting_price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('is_trending', 'desc')
                      ->orderBy('display_order', 'asc');
                break;
        }

        // 9. Pagination (12 items per page with preserved query parameters)
        $decorations = $query->paginate(12)->withQueryString();

        // 10. Featured and Trending collections for highlighted sections if needed
        $featuredDecorations = Decoration::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->take(4)
            ->get();

        $trendingDecorations = Decoration::with('category')
            ->where('is_trending', true)
            ->where('is_active', true)
            ->orderBy('display_order')
            ->take(4)
            ->get();

        // Distinct Styles & Colors for filter UI
        $styles = ['Traditional', 'Royal', 'Floral', 'Modern', 'Minimal', 'Luxury', 'Colorful', 'Classic'];
        $colors = ['Red & Gold', 'Yellow', 'Pink & White', 'Cream & Gold', 'Teal & Magenta', 'Burgundy', 'Amber & Gold'];
        $guestCapacities = ['50-200 Guests', '200-500 Guests', '500-1000 Guests', '1000+ Guests'];

        $biharLocations = ServiceArea::where('category', 'Bihar Core')->orWhere('category', 'Bihar Extended')->orderBy('display_order')->get();
        $upLocations = ServiceArea::where('category', 'Nearby Uttar Pradesh')->orderBy('display_order')->get();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('decorations.index', compact(
            'decorations',
            'categories',
            'selectedCategory',
            'featuredDecorations',
            'trendingDecorations',
            'styles',
            'colors',
            'guestCapacities',
            'biharLocations',
            'upLocations',
            'settings',
            'sort'
        ));
    }

    /**
     * Display a specific category filtered page dynamically.
     */
    public function category(Request $request, $category)
    {
        $categoryModel = Category::where('slug', $category)->firstOrFail();
        $request->merge(['category' => $categoryModel->slug]);
        return $this->index($request);
    }

    /**
     * Display a single decoration detail page.
     */
    public function show($slug)
    {
        $decoration = Decoration::with(['category', 'images', 'items', 'addons'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch 4 related decorations (prefer same category, exclude current)
        $relatedDecorations = Decoration::with('category')
            ->where('category_id', $decoration->category_id)
            ->where('id', '!=', $decoration->id)
            ->where('is_active', true)
            ->where('is_available', true)
            ->take(4)
            ->get();

        // If fewer than 4 related items, supplement with other popular items
        if ($relatedDecorations->count() < 4) {
            $excludeIds = $relatedDecorations->pluck('id')->push($decoration->id)->toArray();
            $supplement = Decoration::with('category')
                ->whereNotIn('id', $excludeIds)
                ->where('is_active', true)
                ->where('is_available', true)
                ->take(4 - $relatedDecorations->count())
                ->get();
            $relatedDecorations = $relatedDecorations->concat($supplement);
        }

        // Available Add-ons for this decoration or global add-ons
        $addons = $decoration->addons;
        if ($addons->isEmpty()) {
            $addons = Addon::where('is_active', true)->take(4)->get();
        }

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('decorations.show', compact('decoration', 'relatedDecorations', 'addons', 'settings'));
    }
}
