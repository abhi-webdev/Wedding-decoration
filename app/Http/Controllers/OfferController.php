<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Package;

class OfferController extends Controller
{
    /**
     * Display a listing of active offers.
     */
    public function index()
    {
        $offers = Offer::currentlyValid()->orderBy('sort_order', 'asc')->get();

        return view('offers.index', compact('offers'));
    }

    /**
     * Display a single offer detail.
     */
    public function show($slug)
    {
        $offer = Offer::currentlyValid()->where('slug', $slug)->firstOrFail();

        $featuredPackages = Package::where('is_active', true)->orderBy('sort_order', 'asc')->take(3)->get();

        return view('offers.show', compact('offer', 'featuredPackages'));
    }
}
