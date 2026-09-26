<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\QuoteRequest;
use App\Models\Category;
use App\Models\ServiceArea;

class QuoteController extends Controller
{
    /**
     * Show the custom quote request form.
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('display_order')->get();
        
        $biharLocations = ServiceArea::where('category', 'Bihar Core')
            ->orWhere('category', 'Bihar Extended')
            ->orderBy('sort_order')
            ->get();

        $upLocations = ServiceArea::where('category', 'Nearby Uttar Pradesh')
            ->orderBy('sort_order')
            ->get();

        $authUser = Auth::user();

        // Optional pre-selected package or decoration from query string
        $preselectedPackage = $request->input('package');
        $preselectedDecoration = $request->input('decoration');

        return view('quote.create', compact(
            'categories',
            'biharLocations',
            'upLocations',
            'authUser',
            'preselectedPackage',
            'preselectedDecoration'
        ));
    }

    /**
     * Store a new custom quote request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date|after_or_equal:today',
            'guest_count' => 'nullable|integer|min:1|max:10000',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'locality' => 'nullable|string|max:255',
            'venue_name' => 'nullable|string|max:255',
            'decoration_preference' => 'nullable|array',
            'decoration_preference.*' => 'string|max:100',
            'budget_range' => 'nullable|string|max:100',
            'special_requirements' => 'nullable|string|max:3000',
            'reference_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        // Secure file upload handling
        $imagePath = null;
        if ($request->hasFile('reference_image') && $request->file('reference_image')->isValid()) {
            $file = $request->file('reference_image');
            $safeFileName = 'ref_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('quotes', $safeFileName, 'public');
        }

        // Generate unique quote reference: AUQ-YYYYMMDD-XXXXX
        $datePrefix = Carbon::now()->format('Ymd');
        do {
            $randSuffix = str_pad((string)random_int(100, 99999), 5, '0', STR_PAD_LEFT);
            $reference = "AUQ-{$datePrefix}-{$randSuffix}";
        } while (QuoteRequest::where('quote_reference', $reference)->exists());

        $quoteRequest = QuoteRequest::create([
            'quote_reference' => $reference,
            'user_id' => Auth::id(),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'event_type' => $validated['event_type'],
            'event_date' => Carbon::parse($validated['event_date'])->format('Y-m-d'),
            'guest_count' => $validated['guest_count'] ?? null,
            'state' => $validated['state'],
            'city' => $validated['city'],
            'locality' => $validated['locality'] ?? null,
            'venue_name' => $validated['venue_name'] ?? null,
            'decoration_preference' => $validated['decoration_preference'] ?? [],
            'budget_range' => $validated['budget_range'] ?? 'Flexible / Need Guidance',
            'special_requirements' => $validated['special_requirements'] ?? null,
            'reference_image' => $imagePath,
            'status' => 'new',
        ]);

        return view('quote.success', compact('quoteRequest'));
    }
}
