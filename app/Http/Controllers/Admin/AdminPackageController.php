<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Decoration;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::withCount('decorations');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('badge', 'like', "%{$s}%")
                  ->orWhere('tagline', 'like', "%{$s}%")
                  ->orWhere('short_description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('tier')) {
            $query->where('badge', 'like', "%{$request->tier}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $packages = $query->orderBy('is_active', 'desc')
                          ->orderBy('display_order', 'asc')
                          ->orderBy('starting_price', 'asc')
                          ->paginate(15)
                          ->withQueryString();

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $decorations = Decoration::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.create', compact('decorations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:packages,slug',
            'badge' => 'nullable|string|max:100',
            'tier' => 'nullable|string|max:100',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'starting_price' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'guest_capacity' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'decorations' => 'nullable|array',
            'decorations.*' => 'exists:decorations,id',
        ]);

        $baseSlug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Package::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $basePrice = $validated['base_price'] ?? $validated['original_price'] ?? $validated['price'] ?? 0;
        $startingPrice = $validated['starting_price'] ?? $basePrice;
        $discountPrice = $validated['discount_price'] ?? ($validated['price'] ?? null);

        $imagePath = 'images/packages/royal-bihar.jpg';
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'pkg_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/packages');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imagePath = 'uploads/packages/' . $fileName;
        }

        $package = Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'badge' => $validated['badge'] ?? $validated['tier'] ?? 'Royal',
            'tagline' => $validated['tagline'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'starting_price' => (int) $startingPrice,
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'guest_capacity' => $validated['guest_capacity'] ?? '500+ Guests',
            'duration' => $validated['duration'] ?? '1 Day',
            'image_url' => $imagePath,
            'image' => $imagePath,
            'is_featured' => $request->boolean('is_featured', true),
            'is_active' => $request->boolean('is_active', true),
            'display_order' => $validated['display_order'] ?? (Package::count() + 1),
            'sort_order' => $validated['display_order'] ?? (Package::count() + 1),
        ]);

        if (!empty($validated['decorations'])) {
            $package->decorations()->sync($validated['decorations']);
        }

        AdminActivityLog::log(
            'Created Wedding Package',
            'Package',
            $package->id,
            "Created package '{$package->name}'"
        );

        return redirect()->route('admin.packages.index')->with('success', "Wedding package '{$package->name}' created successfully.");
    }

    public function show($id)
    {
        return redirect()->route('admin.packages.edit', $id);
    }

    public function edit($id)
    {
        $package = Package::with('decorations')->findOrFail($id);
        $decorations = Decoration::where('is_active', true)->orderBy('name')->get();
        return view('admin.packages.edit', compact('package', 'decorations'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:packages,slug,' . $package->id,
            'badge' => 'nullable|string|max:100',
            'tier' => 'nullable|string|max:100',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'starting_price' => 'nullable|numeric|min:0',
            'base_price' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'guest_capacity' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
            'decorations' => 'nullable|array',
            'decorations.*' => 'exists:decorations,id',
        ]);

        $basePrice = $validated['base_price'] ?? $validated['original_price'] ?? ($validated['price'] ?? $package->base_price);
        $startingPrice = $validated['starting_price'] ?? $basePrice;
        $discountPrice = $validated['discount_price'] ?? ($validated['price'] ?? $package->discount_price);

        $imagePath = $package->image ?: $package->image_url;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'pkg_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/packages');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $imagePath = 'uploads/packages/' . $fileName;

            // Delete old upload if local
            if ($package->image && str_starts_with($package->image, 'uploads/packages/') && file_exists(public_path($package->image))) {
                @unlink(public_path($package->image));
            }
        }

        $package->update([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : $package->slug,
            'badge' => $validated['badge'] ?? $validated['tier'] ?? $package->badge,
            'tagline' => $validated['tagline'] ?? $package->tagline,
            'short_description' => $validated['short_description'] ?? $package->short_description,
            'description' => $validated['description'] ?? $package->description,
            'starting_price' => (int) $startingPrice,
            'base_price' => $basePrice,
            'discount_price' => $discountPrice,
            'guest_capacity' => $validated['guest_capacity'] ?? $package->guest_capacity,
            'duration' => $validated['duration'] ?? $package->duration,
            'image_url' => $imagePath,
            'image' => $imagePath,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : $package->is_featured,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $package->is_active,
            'display_order' => $validated['display_order'] ?? $package->display_order,
        ]);

        if ($request->has('decorations')) {
            $package->decorations()->sync($validated['decorations'] ?? []);
        }

        AdminActivityLog::log(
            'Updated Wedding Package',
            'Package',
            $package->id,
            "Updated package '{$package->name}'"
        );

        return redirect()->route('admin.packages.index')->with('success', "Wedding package '{$package->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);
        $name = $package->name;
        $package->decorations()->detach();
        $package->delete();

        AdminActivityLog::log(
            'Deleted Wedding Package',
            'Package',
            $id,
            "Deleted package '{$name}'"
        );

        return redirect()->route('admin.packages.index')->with('success', "Wedding package '{$name}' deleted successfully.");
    }
}
