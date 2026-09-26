<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminFaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('question', 'like', "%{$s}%")
                  ->orWhere('answer', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $faqs = $query->orderBy('category', 'asc')->orderBy('sort_order', 'asc')->paginate(20)->withQueryString();

        $categories = Faq::distinct()->whereNotNull('category')->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = ['general', 'booking', 'pricing', 'customization', 'logistics', 'cancellation', 'service_areas'];
        }

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq = Faq::create($validated);

        AdminActivityLog::log(
            auth()->id(),
            'create',
            'faq',
            $faq->id,
            "Added FAQ: '{$faq->question}'"
        );

        return redirect()->route('admin.faqs.index')->with('success', "FAQ created successfully.");
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq->update($validated);

        AdminActivityLog::log(
            auth()->id(),
            'update',
            'faq',
            $faq->id,
            "Updated FAQ: '{$faq->question}'"
        );

        return redirect()->route('admin.faqs.index')->with('success', "FAQ updated successfully.");
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $q = $faq->question;
        $faq->delete();

        AdminActivityLog::log(
            auth()->id(),
            'delete',
            'faq',
            $id,
            "Deleted FAQ: '{$q}'"
        );

        return redirect()->route('admin.faqs.index')->with('success', "FAQ deleted successfully.");
    }
}
