<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\GalleryCategory;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = GalleryItem::with('galleryCategory');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('location', 'like', "%{$s}%")
                  ->orWhere('event_type', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('gallery_category_id', $request->category_id);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $galleryItems = $query->orderBy('is_active', 'desc')
                              ->orderBy('sort_order', 'asc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(16)
                              ->withQueryString();

        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.gallery.index', compact('galleryItems', 'categories'));
    }

    public function create()
    {
        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.gallery.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'gallery_category_id' => 'nullable|exists:gallery_categories,id',
            'caption' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $catId = $validated['gallery_category_id'] ?? $validated['category_id'] ?? 1;
        $galCat = GalleryCategory::find($catId);
        $categoryName = $galCat ? $galCat->name : ($validated['event_type'] ?? 'Wedding');
        
        $destPath = public_path('uploads/gallery');
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('image')) {
            $files = [$request->file('image')];
        }

        if (!empty($files)) {
            $count = 0;
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    $fileName = 'gal_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $file->move($destPath, $fileName);
                    $imagePath = 'uploads/gallery/' . $fileName;

                    $itemTitle = count($files) > 1 ? ($validated['title'] . ' ' . ($index + 1)) : $validated['title'];

                    $item = GalleryItem::create([
                        'gallery_category_id' => $catId,
                        'category' => $categoryName,
                        'title' => $itemTitle,
                        'slug' => Str::slug($itemTitle) . '-' . time() . '-' . Str::random(4),
                        'image' => $imagePath,
                        'image_url' => $imagePath,
                        'caption' => $validated['caption'] ?? null,
                        'description' => $validated['description'] ?? null,
                        'location' => $validated['location'] ?? 'Patna, Bihar',
                        'event_type' => $validated['event_type'] ?? 'Wedding',
                        'is_featured' => $request->boolean('is_featured', false),
                        'is_active' => $request->boolean('is_active', true),
                        'display_order' => ($validated['sort_order'] ?? 0) + $index,
                        'sort_order' => ($validated['sort_order'] ?? 0) + $index,
                    ]);
                    $count++;
                }
            }

            AdminActivityLog::log(
                'Added Gallery Photos',
                'GalleryItem',
                null,
                "Added {$count} gallery photograph(s)"
            );

            return redirect()->route('admin.gallery.index')->with('success', "{$count} gallery photo(s) added successfully.");
        }

        $item = GalleryItem::create([
            'gallery_category_id' => $catId,
            'category' => $categoryName,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'image' => null,
            'image_url' => null,
            'caption' => $validated['caption'] ?? null,
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? 'Patna, Bihar',
            'event_type' => $validated['event_type'] ?? 'Wedding',
            'is_featured' => $request->boolean('is_featured', false),
            'is_active' => $request->boolean('is_active', true),
            'display_order' => $validated['sort_order'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        AdminActivityLog::log(
            'Added Gallery Photo',
            'GalleryItem',
            $item->id,
            "Added gallery photograph '{$item->title}'"
        );

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item '{$item->title}' added successfully.");
    }

    public function show($id)
    {
        return redirect()->route('admin.gallery.edit', $id);
    }

    public function edit($id)
    {
        $item = GalleryItem::findOrFail($id);
        $categories = GalleryCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.gallery.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:gallery_categories,id',
            'gallery_category_id' => 'nullable|exists:gallery_categories,id',
            'caption' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'nullable|string|max:100',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $catId = $validated['gallery_category_id'] ?? $validated['category_id'] ?? $item->gallery_category_id;
        $galCat = GalleryCategory::find($catId);
        $categoryName = $galCat ? $galCat->name : ($validated['event_type'] ?? $item->category ?? 'Wedding');
        $imagePath = $item->image ?: $item->image_url;

        // Handle explicit image removal
        if ($request->boolean('remove_image')) {
            if ($item->image && str_starts_with($item->image, 'uploads/gallery/') && file_exists(public_path($item->image))) {
                @unlink(public_path($item->image));
            }
            $imagePath = null;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'gal_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/gallery');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imagePath = 'uploads/gallery/' . $fileName;

            // Delete old upload if local
            if ($item->image && str_starts_with($item->image, 'uploads/gallery/') && file_exists(public_path($item->image))) {
                @unlink(public_path($item->image));
            }
        }

        $item->update([
            'gallery_category_id' => $catId,
            'category' => $categoryName,
            'title' => $validated['title'],
            'image' => $imagePath,
            'image_url' => $imagePath,
            'caption' => $validated['caption'] ?? $item->caption,
            'description' => $validated['description'] ?? $item->description,
            'location' => $validated['location'] ?? $item->location,
            'event_type' => $validated['event_type'] ?? $item->event_type,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : $item->is_featured,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $item->is_active,
            'sort_order' => $validated['sort_order'] ?? $item->sort_order,
        ]);

        AdminActivityLog::log(
            'Updated Gallery Photo',
            'GalleryItem',
            $item->id,
            "Updated gallery photo '{$item->title}'"
        );

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item '{$item->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);
        $title = $item->title;

        if ($item->image && str_starts_with($item->image, 'uploads/gallery/') && file_exists(public_path($item->image))) {
            @unlink(public_path($item->image));
        }

        $item->delete();

        AdminActivityLog::log(
            'Deleted Gallery Photo',
            'GalleryItem',
            $id,
            "Deleted gallery photo '{$title}'"
        );

        return redirect()->route('admin.gallery.index')->with('success', "Gallery item '{$title}' deleted successfully.");
    }
}
