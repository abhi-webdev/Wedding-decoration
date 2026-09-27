<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WeddingVideo;
use App\Models\Category;
use App\Models\AdminActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdminVideoController extends Controller
{
    public function index(Request $request)
    {
        $query = WeddingVideo::with('category');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('short_description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('event_type') && $request->event_type !== 'all') {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('video_type') && $request->video_type !== 'all') {
            $query->where('video_type', $request->video_type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $videos = $query->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => WeddingVideo::count(),
            'active' => WeddingVideo::where('is_active', true)->count(),
            'homepage' => WeddingVideo::where('is_homepage', true)->count(),
            'featured' => WeddingVideo::where('is_featured', true)->count(),
        ];

        return view('admin.videos.index', compact('videos', 'counts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:wedding_videos,slug',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg|max:102400', // 100MB max
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video_type' => 'required|in:reel,short,event_highlight,portfolio,behind_the_scenes',
            'event_type' => 'required|in:jaimala,mandap,haldi,mehendi,sangeet,reception,general',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'required|string|max:150',
            'duration' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'is_homepage' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $videoPath = null;
        $thumbnailPath = null;

        // Handle Video File Upload
        if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $videoFile = $request->file('video_file');
            $videoName = 'video_' . time() . '_' . Str::random(8) . '.' . $videoFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/videos');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $videoFile->move($destinationPath, $videoName);
            $videoPath = 'uploads/videos/' . $videoName;
        }

        // Handle Thumbnail Upload
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            $thumbFile = $request->file('thumbnail_file');
            $thumbName = 'thumb_' . time() . '_' . Str::random(8) . '.' . $thumbFile->getClientOriginalExtension();
            $thumbDest = public_path('uploads/video-thumbnails');
            if (!File::isDirectory($thumbDest)) {
                File::makeDirectory($thumbDest, 0755, true, true);
            }
            $thumbFile->move($thumbDest, $thumbName);
            $thumbnailPath = 'uploads/video-thumbnails/' . $thumbName;
        }

        $slug = !empty($validated['slug']) ? $validated['slug'] : Str::slug($validated['title']);
        $baseSlug = $slug;
        $count = 1;
        while (WeddingVideo::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $video = WeddingVideo::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'video_type' => $validated['video_type'],
            'event_type' => $validated['event_type'],
            'category_id' => $validated['category_id'] ?? null,
            'location' => $validated['location'],
            'duration' => $validated['duration'] ?? '00:20',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'is_homepage' => $request->has('is_homepage') ? $request->boolean('is_homepage') : true,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        AdminActivityLog::log(
            'Uploaded Video/Reel',
            'WeddingVideo',
            $video->id,
            "Added new wedding video: {$video->title} ({$video->event_type})"
        );

        return redirect()->route('admin.videos.index')->with('success', "Wedding video '{$video->title}' uploaded successfully.");
    }

    public function edit($id)
    {
        $video = WeddingVideo::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $video = WeddingVideo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:wedding_videos,slug,' . $video->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg|max:102400',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_video' => 'nullable|boolean',
            'remove_thumbnail' => 'nullable|boolean',
            'video_type' => 'required|in:reel,short,event_highlight,portfolio,behind_the_scenes',
            'event_type' => 'required|in:jaimala,mandap,haldi,mehendi,sangeet,reception,general',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'required|string|max:150',
            'duration' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'is_homepage' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $videoPath = $video->video_path;
        $thumbnailPath = $video->thumbnail_path;

        if ($request->boolean('remove_video')) {
            if ($video->video_path && str_starts_with($video->video_path, 'uploads/videos/') && file_exists(public_path($video->video_path))) {
                @unlink(public_path($video->video_path));
            }
            $videoPath = null;
        }

        if ($request->boolean('remove_thumbnail')) {
            if ($video->thumbnail_path && str_starts_with($video->thumbnail_path, 'uploads/video-thumbnails/') && file_exists(public_path($video->thumbnail_path))) {
                @unlink(public_path($video->thumbnail_path));
            }
            $thumbnailPath = null;
        }

        if ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $videoFile = $request->file('video_file');
            $videoName = 'video_' . time() . '_' . Str::random(8) . '.' . $videoFile->getClientOriginalExtension();
            $destinationPath = public_path('uploads/videos');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            $videoFile->move($destinationPath, $videoName);
            $videoPath = 'uploads/videos/' . $videoName;

            // Delete old upload
            if ($video->video_path && str_starts_with($video->video_path, 'uploads/videos/') && file_exists(public_path($video->video_path))) {
                @unlink(public_path($video->video_path));
            }
        }

        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            $thumbFile = $request->file('thumbnail_file');
            $thumbName = 'thumb_' . time() . '_' . Str::random(8) . '.' . $thumbFile->getClientOriginalExtension();
            $thumbDest = public_path('uploads/video-thumbnails');
            if (!File::isDirectory($thumbDest)) {
                File::makeDirectory($thumbDest, 0755, true, true);
            }
            $thumbFile->move($thumbDest, $thumbName);
            $thumbnailPath = 'uploads/video-thumbnails/' . $thumbName;

            // Delete old thumbnail
            if ($video->thumbnail_path && str_starts_with($video->thumbnail_path, 'uploads/video-thumbnails/') && file_exists(public_path($video->thumbnail_path))) {
                @unlink(public_path($video->thumbnail_path));
            }
        }

        $video->update([
            'title' => $validated['title'],
            'slug' => !empty($validated['slug']) ? $validated['slug'] : $video->slug,
            'short_description' => $validated['short_description'] ?? $video->short_description,
            'description' => $validated['description'] ?? $video->description,
            'video_path' => $videoPath,
            'thumbnail_path' => $thumbnailPath,
            'video_type' => $validated['video_type'],
            'event_type' => $validated['event_type'],
            'category_id' => $validated['category_id'] ?? $video->category_id,
            'location' => $validated['location'],
            'duration' => $validated['duration'] ?? $video->duration ?? '00:20',
            'sort_order' => $validated['sort_order'] ?? $video->sort_order ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'is_homepage' => $request->boolean('is_homepage'),
            'is_active' => $request->boolean('is_active'),
        ]);

        AdminActivityLog::log(
            'Updated Video/Reel',
            'WeddingVideo',
            $video->id,
            "Updated wedding video: {$video->title}"
        );

        return redirect()->route('admin.videos.index')->with('success', "Wedding video '{$video->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $video = WeddingVideo::findOrFail($id);
        $title = $video->title;

        // Clean up local files if present
        if ($video->video_path && file_exists(public_path($video->video_path))) {
            @unlink(public_path($video->video_path));
        }
        if ($video->thumbnail_path && file_exists(public_path($video->thumbnail_path))) {
            @unlink(public_path($video->thumbnail_path));
        }

        $video->delete();

        AdminActivityLog::log(
            'Deleted Video/Reel',
            'WeddingVideo',
            $id,
            "Deleted wedding video: {$title}"
        );

        return redirect()->route('admin.videos.index')->with('success', "Wedding video '{$title}' deleted successfully.");
    }
}
