<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Decoration;
use App\Models\Addon;
use App\Models\ServiceArea;

class PackageController extends Controller
{
    /**
     * Display a listing of wedding decoration packages.
     */
    public function index(Request $request)
    {
        $query = Package::with(['decorations'])->where('is_active', true);

        // Optional filter / sort
        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, base_price, starting_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, base_price, starting_price) DESC');
                break;
            case 'featured':
            default:
                $query->orderBy('sort_order', 'asc')->orderBy('is_featured', 'desc');
                break;
        }

        $packages = $query->get();

        return view('packages.index', compact('packages', 'sort'));
    }

    /**
     * Display a single package's full details.
     */
    public function show($slug)
    {
        $package = Package::with(['decorations.category', 'decorations.images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $otherPackages = Package::where('id', '!=', $package->id)
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        $availableAddons = Addon::where('is_active', true)->orderBy('price', 'asc')->take(6)->get();

        $serviceAreas = ServiceArea::where('is_active', true)->orderBy('sort_order')->get();

        return view('packages.show', compact('package', 'otherPackages', 'availableAddons', 'serviceAreas'));
    }
}
