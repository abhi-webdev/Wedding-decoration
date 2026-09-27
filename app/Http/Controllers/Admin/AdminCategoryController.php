<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\AdminActivityLog;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('decorations');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%")
                  ->orWhere('tagline', 'like', "%{$s}%");
        }

        $categories = $query->orderBy('display_order', 'asc')->paginate(15)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon_name' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $baseSlug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $displayOrder = $validated['display_order'] ?? $validated['sort_order'] ?? (Category::count() + 1);
        $isFeatured = $request->boolean('is_featured', true) || $request->boolean('is_active', true);

        $imageUrl = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'cat_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/categories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imageUrl = 'uploads/categories/' . $fileName;
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_url' => $imageUrl,
            'icon_name' => $validated['icon_name'] ?? 'fa-flower',
            'display_order' => $displayOrder,
            'is_featured' => $isFeatured,
        ]);

        AdminActivityLog::log('Created Category', 'Category', $category->id, "Created category {$category->name}");

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' created successfully!");
    }

    /**
     * Display category details or redirect to edit.
     */
    public function show($id)
    {
        return redirect()->route('admin.categories.edit', $id);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update existing category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon_name' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : $category->slug;
        $displayOrder = $validated['display_order'] ?? $validated['sort_order'] ?? $category->display_order;
        $isFeatured = $request->has('is_featured') ? $request->boolean('is_featured') : ($request->has('is_active') ? $request->boolean('is_active') : $category->is_featured);

        $imageUrl = $category->image_url;

        // Handle explicit image removal
        if ($request->boolean('remove_image')) {
            if ($category->image_url && str_starts_with($category->image_url, 'uploads/categories/') && file_exists(public_path($category->image_url))) {
                @unlink(public_path($category->image_url));
            }
            $imageUrl = null;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'cat_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/categories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imageUrl = 'uploads/categories/' . $fileName;

            // Delete old uploaded image if local
            if ($category->image_url && str_starts_with($category->image_url, 'uploads/categories/') && file_exists(public_path($category->image_url))) {
                @unlink(public_path($category->image_url));
            }
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'tagline' => $validated['tagline'] ?? $category->tagline,
            'description' => $validated['description'] ?? $category->description,
            'image_url' => $imageUrl,
            'icon_name' => $validated['icon_name'] ?? $category->icon_name,
            'display_order' => $displayOrder,
            'is_featured' => $isFeatured,
        ]);

        AdminActivityLog::log('Updated Category', 'Category', $category->id, "Updated category {$category->name}");

        return redirect()->route('admin.categories.index')->with('success', "Category '{$category->name}' updated successfully!");
    }

    /**
     * Remove the specified category safely.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $count = $category->decorations()->count();

        if ($count > 0) {
            return redirect()->route('admin.categories.index')->with('error', "Cannot delete category '{$category->name}' because {$count} decorations depend on it. Please reassign or delete them first.");
        }

        if ($category->image_url && str_starts_with($category->image_url, 'uploads/categories/') && file_exists(public_path($category->image_url))) {
            @unlink(public_path($category->image_url));
        }

        $name = $category->name;
        $category->delete();

        AdminActivityLog::log('Deleted Category', 'Category', $id, "Deleted category {$name}");

        return redirect()->route('admin.categories.index')->with('success', "Category '{$name}' deleted successfully.");
    }

    /**
     * Toggle featured status of category.
     */
    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->is_featured = !$category->is_featured;
        $category->save();

        return back()->with('success', "Category '{$category->name}' featured state toggled.");
    }
}
