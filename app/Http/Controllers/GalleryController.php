<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\WeddingVideo;

class GalleryController extends Controller
{
    /**
     * Display the photo & video gallery with media filters and categories.
     */
    public function index(Request $request)
    {
        $mediaType = $request->input('type', 'all'); // 'all', 'photos', 'videos'
        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $selectedCategorySlug = $request->input('category', 'all');
        $selectedCategory = null;

        $galleryItems = collect();
        $videos = collect();

        // 1. Photos
        if ($mediaType === 'all' || $mediaType === 'photos') {
            $photoQuery = GalleryItem::with('galleryCategory')->where('is_active', true);

            if ($selectedCategorySlug && $selectedCategorySlug !== 'all') {
                $selectedCategory = GalleryCategory::where('slug', $selectedCategorySlug)->first();
                if ($selectedCategory) {
                    $photoQuery->where(function ($q) use ($selectedCategory, $selectedCategorySlug) {
                        $q->where('gallery_category_id', $selectedCategory->id)
                          ->orWhere('category', $selectedCategorySlug);
                    });
                } else {
                    $photoQuery->where('category', $selectedCategorySlug);
                }
            }

            $galleryItems = $photoQuery->orderBy('sort_order', 'asc')->paginate(18)->withQueryString();
        }

        // 2. Videos / Reels
        if ($mediaType === 'all' || $mediaType === 'videos' || $mediaType === 'reels') {
            $videoQuery = WeddingVideo::with('category')->active();
            if ($selectedCategorySlug && $selectedCategorySlug !== 'all') {
                $videoQuery->where(function ($q) use ($selectedCategorySlug) {
                    $q->where('event_type', $selectedCategorySlug)
                      ->orWhereHas('category', function ($catQ) use ($selectedCategorySlug) {
                          $catQ->where('slug', $selectedCategorySlug);
                      });
                });
            }
            $videos = $videoQuery->orderBy('sort_order', 'asc')->take(12)->get();
        }

        return view('gallery.index', compact('categories', 'galleryItems', 'videos', 'selectedCategorySlug', 'selectedCategory', 'mediaType'));
    }
}
