<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Category;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminAddonController extends Controller
{
    public function index(Request $request)
    {
        $query = Addon::query();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $addons = $query->orderBy('is_active', 'desc')->orderBy('name', 'asc')->paginate(15)->withQueryString();

        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        $categories = Category::orderBy('display_order')->get();
        return view('admin.addons.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $addon = Addon::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $validated['image'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AdminActivityLog::log(
            'Created Add-on',
            'Addon',
            $addon->id,
            "Created add-on '{$addon->name}' with price ₹{$addon->price}"
        );

        return redirect()->route('admin.addons.index')->with('success', "Add-on '{$addon->name}' created successfully.");
    }

    public function show($id)
    {
        return redirect()->route('admin.addons.edit', $id);
    }

    public function edit($id)
    {
        $addon = Addon::findOrFail($id);
        $categories = Category::orderBy('display_order')->get();
        return view('admin.addons.edit', compact('addon', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $addon = Addon::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $addon->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'image' => $validated['image'] ?? $addon->image,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $addon->is_active,
        ]);

        AdminActivityLog::log(
            'Updated Add-on',
            'Addon',
            $addon->id,
            "Updated add-on '{$addon->name}'"
        );

        return redirect()->route('admin.addons.index')->with('success', "Add-on '{$addon->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $addon = Addon::findOrFail($id);
        $name = $addon->name;
        $addon->delete();

        AdminActivityLog::log(
            'Deleted Add-on',
            'Addon',
            $id,
            "Deleted add-on '{$name}'"
        );

        return redirect()->route('admin.addons.index')->with('success', "Add-on '{$name}' deleted successfully.");
    }
}
