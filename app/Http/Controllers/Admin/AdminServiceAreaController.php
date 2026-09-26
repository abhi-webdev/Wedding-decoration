<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminServiceAreaController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceArea::query();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('district', 'like', "%{$s}%")
                  ->orWhere('state', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $serviceAreas = $query->orderBy('state', 'asc')
                              ->orderBy('is_primary', 'desc')
                              ->orderBy('name', 'asc')
                              ->paginate(20)
                              ->withQueryString();

        return view('admin.service-areas.index', compact('serviceAreas'));
    }

    public function create()
    {
        return view('admin.service-areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_areas,slug',
            'city' => 'nullable|string|max:255',
            'state' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $name = $validated['name'];
        $district = $validated['district'] ?? ($validated['city'] ?? $name);

        $area = ServiceArea::create([
            'name' => $name,
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($name),
            'state' => $validated['state'],
            'district' => $district,
            'description' => $validated['description'] ?? "Full wedding decoration & event management services in {$name}, {$district}.",
            'status_note' => 'Full Team Available',
            'is_primary' => $request->boolean('is_primary', false),
            'is_active' => $request->boolean('is_active', true),
            'display_order' => $validated['sort_order'] ?? 0,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        AdminActivityLog::log(
            'Created Service Area',
            'ServiceArea',
            $area->id,
            "Added service area '{$area->name}' ({$area->state})"
        );

        return redirect()->route('admin.service-areas.index')->with('success', "Service area '{$area->name}' added successfully.");
    }

    public function show($id)
    {
        return redirect()->route('admin.service-areas.edit', $id);
    }

    public function edit($id)
    {
        $area = ServiceArea::findOrFail($id);
        return view('admin.service-areas.edit', compact('area'));
    }

    public function update(Request $request, $id)
    {
        $area = ServiceArea::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_areas,slug,' . $area->id,
            'city' => 'nullable|string|max:255',
            'state' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $name = $validated['name'];
        $district = $validated['district'] ?? ($validated['city'] ?? $area->district);

        $area->update([
            'name' => $name,
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : $area->slug,
            'state' => $validated['state'],
            'district' => $district,
            'description' => $validated['description'] ?? $area->description,
            'is_primary' => $request->has('is_primary') ? $request->boolean('is_primary') : $area->is_primary,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $area->is_active,
            'sort_order' => $validated['sort_order'] ?? $area->sort_order,
        ]);

        AdminActivityLog::log(
            'Updated Service Area',
            'ServiceArea',
            $area->id,
            "Updated service area '{$area->name}'"
        );

        return redirect()->route('admin.service-areas.index')->with('success', "Service area '{$area->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $area = ServiceArea::findOrFail($id);
        $name = $area->name;
        $area->delete();

        AdminActivityLog::log(
            'Deleted Service Area',
            'ServiceArea',
            $id,
            "Deleted service area '{$name}'"
        );

        return redirect()->route('admin.service-areas.index')->with('success', "Service area '{$name}' deleted successfully.");
    }
}
