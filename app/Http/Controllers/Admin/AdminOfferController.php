<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminOfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('coupon_code', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $offers = $query->orderBy('is_active', 'desc')->orderBy('valid_until', 'desc')->paginate(15)->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:offers,slug',
            'coupon_code' => 'nullable|string|max:50|unique:offers,coupon_code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'badge_text' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['coupon_code'] = !empty($validated['coupon_code']) ? strtoupper(trim($validated['coupon_code'])) : null;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'offer_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/offers');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $validated['image'] = 'uploads/offers/' . $fileName;
            $validated['image_url'] = 'uploads/offers/' . $fileName;
        }

        $offer = Offer::create($validated);

        AdminActivityLog::log(
            auth()->id(),
            'create',
            'offer',
            $offer->id,
            "Created promotional offer '{$offer->title}' ({$offer->coupon_code})"
        );

        return redirect()->route('admin.offers.index')->with('success', "Offer '{$offer->title}' created successfully.");
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:offers,slug,' . $offer->id,
            'coupon_code' => 'nullable|string|max:50|unique:offers,coupon_code,' . $offer->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_booking_amount' => 'nullable|numeric|min:0',
            'badge_text' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['coupon_code'] = !empty($validated['coupon_code']) ? strtoupper(trim($validated['coupon_code'])) : null;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle explicit image removal
        if ($request->boolean('remove_image')) {
            if ($offer->image && str_starts_with($offer->image, 'uploads/offers/') && file_exists(public_path($offer->image))) {
                @unlink(public_path($offer->image));
            }
            $validated['image'] = null;
            $validated['image_url'] = null;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $fileName = 'offer_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/offers');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
            }
            $file->move($destPath, $fileName);
            $validated['image'] = 'uploads/offers/' . $fileName;
            $validated['image_url'] = 'uploads/offers/' . $fileName;

            // Delete old upload if local
            if ($offer->image && str_starts_with($offer->image, 'uploads/offers/') && file_exists(public_path($offer->image))) {
                @unlink(public_path($offer->image));
            }
        }

        $offer->update($validated);

        AdminActivityLog::log(
            auth()->id(),
            'update',
            'offer',
            $offer->id,
            "Updated promotional offer '{$offer->title}'"
        );

        return redirect()->route('admin.offers.index')->with('success', "Offer '{$offer->title}' updated successfully.");
    }

    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $title = $offer->title;

        if ($offer->image && str_starts_with($offer->image, 'uploads/offers/') && file_exists(public_path($offer->image))) {
            @unlink(public_path($offer->image));
        }

        $offer->delete();

        AdminActivityLog::log(
            auth()->id(),
            'delete',
            'offer',
            $id,
            "Deleted offer '{$title}'"
        );

        return redirect()->route('admin.offers.index')->with('success', "Offer '{$title}' deleted successfully.");
    }
}
