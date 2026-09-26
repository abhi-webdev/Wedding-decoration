<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingStatusHistory;
use App\Models\Decoration;
use App\Models\Addon;
use App\Models\ServiceArea;
use App\Models\SiteSetting;

class BookingController extends Controller
{
    /**
     * Show the multi-step booking request form for a selected decoration.
     */
    public function create($decoration)
    {
        // Support either slug or ID for flexible routing
        $decorationModel = Decoration::with(['category', 'images', 'addons', 'items'])
            ->where(function ($q) use ($decoration) {
                if (is_numeric($decoration)) {
                    $q->where('id', $decoration);
                } else {
                    $q->where('slug', $decoration);
                }
            })
            ->where('is_active', true)
            ->firstOrFail();

        // Get available add-ons for this decoration or active global add-ons
        $addons = $decorationModel->addons;
        if ($addons->isEmpty()) {
            $addons = Addon::where('is_active', true)->orderBy('price', 'asc')->get();
        }

        // Service locations
        $biharLocations = ServiceArea::where('category', 'Bihar Core')
            ->orWhere('category', 'Bihar Extended')
            ->orderBy('display_order')
            ->get();

        $upLocations = ServiceArea::where('category', 'Nearby Uttar Pradesh')
            ->orderBy('display_order')
            ->get();

        $authUser = Auth::user();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('bookings.create', compact(
            'decorationModel',
            'addons',
            'biharLocations',
            'upLocations',
            'authUser',
            'settings'
        ));
    }

    /**
     * AJAX endpoint to check date availability for a given decoration.
     * Uses Vanilla JS fetch(), returns JSON.
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'decoration_id' => 'required|exists:decorations,id',
            'event_date' => 'required|date|after_or_equal:today',
        ]);

        $decorationId = $validated['decoration_id'];
        $eventDate = Carbon::parse($validated['event_date'])->format('Y-m-d');

        // Check conflicting bookings
        $blockingStatuses = ['confirmed', 'advance_paid', 'scheduled', 'quoted'];
        $isBlocked = Booking::where('decoration_id', $decorationId)
            ->where('event_date', $eventDate)
            ->whereIn('status', $blockingStatuses)
            ->exists();

        if ($isBlocked) {
            return response()->json([
                'available' => false,
                'status' => 'booked',
                'badge' => 'Already Booked',
                'badge_class' => 'bg-rose-100 text-rose-800 border-rose-300',
                'message' => 'This decoration is already booked or scheduled for the selected date. Please choose another date or contact our team for custom alternative setups.'
            ]);
        }

        $hasPending = Booking::where('decoration_id', $decorationId)
            ->where('event_date', $eventDate)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return response()->json([
                'available' => true,
                'status' => 'pending_request',
                'badge' => 'Pending Review On Date',
                'badge_class' => 'bg-amber-100 text-amber-900 border-amber-300',
                'message' => 'This date currently has a pending request from another client. You may still submit your request, and Aditya Utsav managers will confirm slot priority within 2 hours.'
            ]);
        }

        return response()->json([
            'available' => true,
            'status' => 'available',
            'badge' => 'Available For Request',
            'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'message' => 'Great news! This decoration is fully available to be requested for your selected date in Bihar & UP service areas.'
        ]);
    }

    /**
     * Store a new booking request in a database transaction with server-side price recalculation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'decoration_id' => 'required|exists:decorations,id',
            'event_type' => 'required|string|max:100',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string|max:20',
            'end_time' => 'required|string|max:20',
            'guest_count' => 'required|integer|min:1|max:10000',
            'address_line' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:30',
            'special_requirements' => 'nullable|string|max:2000',
            'selected_addons' => 'nullable|array',
            'selected_addons.*' => 'integer|exists:addons,id',
        ]);

        // Validate time ordering
        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];
        if (strtotime($endTime) <= strtotime($startTime)) {
            return back()->withInput()->withErrors([
                'end_time' => 'Event end time must be later than the event start time.'
            ]);
        }

        // Run database transaction to ensure atomic creation
        $booking = DB::transaction(function () use ($validated, $request) {
            // 1. Re-fetch decoration from DB
            $decoration = Decoration::lockForUpdate()->findOrFail($validated['decoration_id']);

            // 2. Re-verify availability
            $eventDate = Carbon::parse($validated['event_date'])->format('Y-m-d');
            $isBlocked = Booking::where('decoration_id', $decoration->id)
                ->where('event_date', $eventDate)
                ->whereIn('status', ['confirmed', 'advance_paid', 'scheduled', 'quoted'])
                ->exists();

            if ($isBlocked) {
                throw new \Exception('This decoration was just confirmed for another client on this date. Please choose another date.');
            }

            // 3. Recalculate price on the server (NEVER trust client amounts)
            $baseAmount = $decoration->actual_booking_price;
            $addonAmount = 0.00;
            $selectedAddonModels = collect();

            if (!empty($validated['selected_addons'])) {
                $selectedAddonModels = Addon::whereIn('id', $validated['selected_addons'])
                    ->where('is_active', true)
                    ->get();

                foreach ($selectedAddonModels as $addon) {
                    $addonAmount += (float)$addon->price;
                }
            }

            $estimatedTotal = $baseAmount + $addonAmount;

            // 4. Generate unique human-friendly booking reference: AU-YYYYMMDD-XXXXX
            $datePrefix = Carbon::now()->format('Ymd');
            do {
                $randSuffix = str_pad((string)random_int(100, 99999), 5, '0', STR_PAD_LEFT);
                $reference = "AU-{$datePrefix}-{$randSuffix}";
            } while (Booking::where('booking_reference', $reference)->exists());

            // 5. Create Booking record
            $booking = Booking::create([
                'booking_reference' => $reference,
                'user_id' => Auth::id(),
                'decoration_id' => $decoration->id,
                'event_type' => $validated['event_type'],
                'event_date' => $eventDate,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'guest_count' => $validated['guest_count'],
                'address_line' => $validated['address_line'],
                'locality' => $validated['locality'] ?? null,
                'city' => $validated['city'],
                'district' => $validated['district'] ?? null,
                'state' => $validated['state'],
                'pincode' => $validated['pincode'] ?? null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                'special_requirements' => $validated['special_requirements'] ?? null,
                'base_amount' => $baseAmount,
                'addon_amount' => $addonAmount,
                'estimated_total' => $estimatedTotal,
                'status' => 'pending',
                'admin_notes' => null,
            ]);

            // 6. Create Booking Addons records
            foreach ($selectedAddonModels as $addon) {
                BookingAddon::create([
                    'booking_id' => $booking->id,
                    'addon_id' => $addon->id,
                    'quantity' => 1,
                    'unit_price' => $addon->price,
                    'total_price' => $addon->price,
                ]);
            }

            // 7. Create initial Status History record
            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'status' => 'pending',
                'note' => 'Booking request submitted by client via online portal.',
                'changed_by_user_id' => Auth::id(),
            ]);

            return $booking;
        });

        // Redirect using PRG (POST-Redirect-GET) pattern to confirmation page
        return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Your decoration booking request has been successfully submitted!');
    }

    /**
     * Show booking request confirmation page.
     */
    public function confirmation($reference)
    {
        $booking = Booking::with(['decoration.category', 'decoration.images', 'addons.addon', 'statusHistories'])
            ->where('booking_reference', $reference)
            ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('bookings.confirmation', compact('booking', 'settings'));
    }
}
