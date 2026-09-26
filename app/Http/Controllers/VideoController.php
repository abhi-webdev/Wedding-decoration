<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeddingVideo;
use App\Models\Category;

class VideoController extends Controller
{
    /**
     * Display public wedding reels and videos catalogue.
     */
    public function index(Request $request)
    {
        $query = WeddingVideo::with('category')->active();

        // Event type filter (jaimala, mandap, haldi, mehendi, sangeet, reception)
        if ($request->filled('event_type') && $request->event_type !== 'all') {
            $query->where('event_type', $request->event_type);
        }

        // Video type filter (reel, short, event_highlight, portfolio)
        if ($request->filled('video_type') && $request->video_type !== 'all') {
            $query->where('video_type', $request->video_type);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $videos = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $activeEventType = $request->input('event_type', 'all');

        return view('pages.videos.index', compact('videos', 'categories', 'activeEventType'));
    }

    /**
     * Display single wedding video/reel detail page.
     */
    public function show($slug)
    {
        $video = WeddingVideo::with('category')
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views counter
        $video->increment('views_count');

        // Related videos
        $relatedVideos = WeddingVideo::with('category')
            ->active()
            ->where('id', '!=', $video->id)
            ->where(function ($q) use ($video) {
                $q->where('event_type', $video->event_type)
                  ->orWhere('category_id', $video->category_id);
            })
            ->take(4)
            ->get();

        if ($relatedVideos->isEmpty()) {
            $relatedVideos = WeddingVideo::with('category')
                ->active()
                ->where('id', '!=', $video->id)
                ->take(4)
                ->get();
        }

        // Related decorations
        $relatedDecorations = $video->relatedDecorations();

        return view('pages.videos.show', compact('video', 'relatedVideos', 'relatedDecorations'));
    }
}
