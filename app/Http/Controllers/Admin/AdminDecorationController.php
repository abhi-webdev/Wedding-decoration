<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decoration;
use App\Models\Category;
use App\Models\DecorationImage;
use App\Models\DecorationItem;
use App\Models\AdminActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminDecorationController extends Controller
{
    /**
     * Display a listing of decorations with category filters and search.
     */
    public function index(Request $request)
    {
        $query = Decoration::with(['category', 'images']);

        if ($request->filled('category_id') && $request->input('category_id') !== 'all') {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') $query->where('is_active', true);
            if ($status === 'inactive') $query->where('is_active', false);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('style', 'like', "%{$search}%");
            });
        }

        $decorations = $query->orderBy('display_order', 'asc')->latest()->paginate(20)->withQueryString();
        $categories = Category::orderBy('display_order')->get();

        return view('admin.decorations.index', compact('decorations', 'categories'));
    }

    /**
     * Show form for creating a new decoration.
     */
    public function create()
    {
        $categories = Category::orderBy('display_order')->get();
        return view('admin.decorations.create', compact('categories'));
    }

    /**
     * Store a newly created decoration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'setup_type' => 'nullable|string|max:50',
            'style' => 'nullable|string|max:100',
            'primary_color' => 'nullable|string|max:100',
            'guest_capacity' => 'nullable|string|max:100',
            'setup_time' => 'nullable|string|max:100',
            'setup_time_hours' => 'nullable|integer|min:1',
            'dimensions' => 'nullable|string|max:100',
            'included_items' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Base price resolution
        $basePrice = $validated['base_price'] ?? $validated['price'] ?? 0;
        $discountPrice = $validated['discount_price'] ?? null;

        // Generate unique slug
        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Decoration::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        // Handle primary image upload
        $imagePath = 'images/decorations/jaimala-stage-01.jpg'; // fallback
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = Str::slug($validated['name']) . '-' . time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/decorations');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imagePath = 'uploads/decorations/' . $fileName;
        }

        $decoration = Decoration::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'primary_image' => $imagePath,
            'location' => $validated['location'] ?? 'Patna, Bihar',
            'starting_price' => (int)$basePrice,
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'style' => $validated['style'] ?? 'Traditional',
            'primary_color' => $validated['primary_color'] ?? null,
            'guest_capacity' => $validated['guest_capacity'] ?? '200–500 Guests',
            'setup_time' => $validated['setup_time'] ?? (!empty($validated['setup_time_hours']) ? "{$validated['setup_time_hours']} Hours" : '4–6 Hours'),
            'dimensions' => $validated['dimensions'] ?? '24x12 ft',
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending') || $request->boolean('is_popular'),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'is_available' => true,
        ]);

        // Save image record in decoration_images
        DecorationImage::create([
            'decoration_id' => $decoration->id,
            'image_url' => $imagePath,
            'caption' => $decoration->name,
            'is_primary' => true,
            'display_order' => 1,
        ]);

        AdminActivityLog::log('Created Decoration', 'Decoration', $decoration->id, "Created new decoration: {$decoration->name}");

        return redirect()->route('admin.decorations.edit', $decoration->id)
            ->with('success', "Decoration '{$decoration->name}' created successfully! You can now upload gallery images or manage items.");
    }

    /**
     * Show form for editing an existing decoration.
     */
    public function edit($id)
    {
        $decoration = Decoration::with(['category', 'images', 'items', 'addons'])->findOrFail($id);
        $categories = Category::orderBy('display_order')->get();

        return view('admin.decorations.edit', compact('decoration', 'categories'));
    }

    /**
     * Update decoration details.
     */
    public function update(Request $request, $id)
    {
        $decoration = Decoration::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:100',
            'setup_type' => 'nullable|string|max:50',
            'style' => 'nullable|string|max:100',
            'primary_color' => 'nullable|string|max:100',
            'guest_capacity' => 'nullable|string|max:100',
            'setup_time' => 'nullable|string|max:100',
            'setup_time_hours' => 'nullable|integer|min:1',
            'dimensions' => 'nullable|string|max:100',
            'included_items' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $basePrice = $validated['base_price'] ?? $validated['price'] ?? $decoration->base_price;
        $discountPrice = $validated['discount_price'] ?? null;

        // If new image uploaded
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = Str::slug($validated['name']) . '-' . time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/decorations');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $newImagePath = 'uploads/decorations/' . $fileName;

            // Optional cleanup of old upload if local
            if ($decoration->primary_image && str_starts_with($decoration->primary_image, 'uploads/decorations/') && file_exists(public_path($decoration->primary_image))) {
                @unlink(public_path($decoration->primary_image));
            }

            $decoration->primary_image = $newImagePath;

            DecorationImage::create([
                'decoration_id' => $decoration->id,
                'image_url' => $newImagePath,
                'caption' => $decoration->name,
                'is_primary' => true,
                'display_order' => 1,
            ]);
        }

        $decoration->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : $decoration->slug,
            'tagline' => $validated['tagline'] ?? $decoration->tagline,
            'short_description' => $validated['short_description'] ?? $decoration->short_description,
            'description' => $validated['description'],
            'location' => $validated['location'] ?? $decoration->location,
            'starting_price' => (int)$basePrice,
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'style' => $validated['style'] ?? $decoration->style,
            'primary_color' => $validated['primary_color'] ?? $decoration->primary_color,
            'guest_capacity' => $validated['guest_capacity'] ?? $decoration->guest_capacity,
            'setup_time' => $validated['setup_time'] ?? ($validated['setup_time_hours'] ? "{$validated['setup_time_hours']} Hours" : $decoration->setup_time),
            'dimensions' => $validated['dimensions'] ?? $decoration->dimensions,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : $decoration->is_featured,
            'is_trending' => $request->has('is_trending') ? $request->boolean('is_trending') : ($request->has('is_popular') ? $request->boolean('is_popular') : $decoration->is_trending),
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $decoration->is_active,
        ]);

        AdminActivityLog::log('Updated Decoration', 'Decoration', $decoration->id, "Updated details and pricing for {$decoration->name}");

        return back()->with('success', "Decoration '{$decoration->name}' updated successfully! Changes are immediately live on the public website.");
    }

    /**
     * Toggle active state or safely deactivate.
     */
    public function toggleActive($id)
    {
        $decoration = Decoration::findOrFail($id);
        $decoration->is_active = !$decoration->is_active;
        $decoration->save();

        $state = $decoration->is_active ? 'activated' : 'deactivated';
        AdminActivityLog::log('Toggled Decoration Active Status', 'Decoration', $decoration->id, "{$decoration->name} {$state}");

        return back()->with('success', "Decoration '{$decoration->name}' has been {$state}.");
    }

    /**
     * Upload an extra gallery image for the decoration.
     */
    public function uploadImage(Request $request, $id)
    {
        $decoration = Decoration::findOrFail($id);

        $request->validate([
            'gallery_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $file = $request->file('gallery_image');
        $fileName = 'dec_extra_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $destPath = public_path('uploads/decoration-gallery');
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
        }
        $file->move($destPath, $fileName);
        $imagePath = 'uploads/decoration-gallery/' . $fileName;

        DecorationImage::create([
            'decoration_id' => $decoration->id,
            'image_url' => $imagePath,
            'caption' => $request->input('caption') ?: $decoration->name,
            'is_primary' => false,
            'display_order' => $decoration->images()->count() + 1,
        ]);

        return back()->with('success', 'New gallery image uploaded successfully.');
    }

    /**
     * Delete an extra decoration image.
     */
    public function deleteImage($id)
    {
        $image = DecorationImage::findOrFail($id);
        if ($image->image_url && str_starts_with($image->image_url, 'uploads/') && file_exists(public_path($image->image_url))) {
            @unlink(public_path($image->image_url));
        }
        $image->delete();

        return back()->with('success', 'Image removed from decoration gallery.');
    }

    /**
     * Add included item to decoration.
     */
    public function addItem(Request $request, $id)
    {
        $decoration = Decoration::findOrFail($id);

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        DecorationItem::create([
            'decoration_id' => $decoration->id,
            'name' => $request->input('item_name'),
            'item_name' => $request->input('item_name'),
            'quantity' => $request->input('quantity', 1),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Included item added to decoration.');
    }

    /**
     * Remove included item from decoration.
     */
    public function deleteItem($id)
    {
        $item = DecorationItem::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Included item removed.');
    }
}
