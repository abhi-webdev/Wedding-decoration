<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingAddon;
use App\Models\BookingStatusHistory;
use App\Models\Decoration;
use App\Models\Package;
use App\Models\Addon;
use App\Models\User;
use App\Models\ServiceArea;
use App\Models\SiteSetting;
use App\Services\NotificationService;

class BookingController extends Controller
{
    /**
     * Show available decorations and packages after checking date availability.
     */
    public function availabilityResults(Request $request)
    {
        $eventType = $request->input('event_type', 'Wedding & Vivah');
        $city = $request->input('city', 'Siwan');
        $eventDate = $request->input('event_date', Carbon::today()->addDays(14)->format('Y-m-d'));
        $phone = $request->input('phone', '');
        $name = $request->input('name', '');

        // Check date validity
        try {
            $parsedDate = Carbon::parse($eventDate);
            if ($parsedDate->isPast() && !$parsedDate->isToday()) {
                $eventDate = Carbon::today()->format('Y-m-d');
                $parsedDate = Carbon::today();
            }
        } catch (\Throwable $e) {
            $parsedDate = Carbon::today()->addDays(14);
            $eventDate = $parsedDate->format('Y-m-d');
        }

        // Check conflicting confirmed bookings on this date
        $blockingStatuses = ['confirmed', 'advance_paid', 'scheduled'];
        $conflictCount = Booking::where('event_date', $eventDate)
            ->whereIn('status', $blockingStatuses)
            ->count();

        $isDateAvailable = ($conflictCount < 5); // Aditya Utsav handles up to 5 concurrent setups across Bihar/UP

        // Retrieve active decorations from database
        $decorationsQuery = Decoration::with(['category', 'images'])
            ->where('is_active', true);

        // Prioritize matching category if relevant
        $decorations = $decorationsQuery->orderBy('display_order', 'asc')->get();

        // Retrieve active packages from database
        $packages = Package::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('bookings.availability_results', compact(
            'eventType',
            'city',
            'eventDate',
            'parsedDate',
            'phone',
            'name',
            'isDateAvailable',
            'conflictCount',
            'decorations',
            'packages',
            'settings'
        ));
    }

    /**
     * Endpoint to check date availability. Supports both JSON AJAX and form redirection.
     */
    public function checkAvailability(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $decorationId = $request->input('decoration_id');
            $eventDate = $request->input('event_date', date('Y-m-d'));

            if ($decorationId) {
                $blockingStatuses = ['confirmed', 'advance_paid', 'scheduled'];
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
                        'message' => 'This decoration is booked for the selected date. Please choose another date or explore our alternative setups.'
                    ]);
                }
            }

            return response()->json([
                'available' => true,
                'status' => 'available',
                'badge' => 'Available For Booking',
                'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'message' => 'Your wedding date is available! Redirecting to available decoration setups...',
                'redirect_url' => route('booking.availability', $request->all())
            ]);
        }

        // Direct form submission: redirect to availability results page
        return redirect()->route('booking.availability', $request->all());
    }

    /**
     * Show booking request form for a selected decoration.
     */
    public function create($decoration, Request $request)
    {
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

        $addons = $decorationModel->addons;
        if ($addons->isEmpty()) {
            $addons = Addon::where('is_active', true)->orderBy('price', 'asc')->get();
        }

        $biharLocations = ServiceArea::where('category', 'Bihar Core')
            ->orWhere('category', 'Bihar Extended')
            ->orderBy('display_order')
            ->get();

        $upLocations = ServiceArea::where('category', 'Nearby Uttar Pradesh')
            ->orderBy('display_order')
            ->get();

        $authUser = Auth::user();
        $settings = SiteSetting::all()->pluck('value', 'key');
        $prefill = $request->query();
        $bookingType = 'decoration';
        $packageModel = null;

        return view('bookings.create', compact(
            'decorationModel',
            'packageModel',
            'bookingType',
            'addons',
            'biharLocations',
            'upLocations',
            'authUser',
            'settings',
            'prefill'
        ));
    }

    /**
     * Show booking request form for a selected package.
     */
    public function createPackage($package, Request $request)
    {
        $packageModel = Package::with(['decorations.category', 'decorations.images'])
            ->where(function ($q) use ($package) {
                if (is_numeric($package)) {
                    $q->where('id', $package);
                } else {
                    $q->where('slug', $package);
                }
            })
            ->where('is_active', true)
            ->firstOrFail();

        $addons = Addon::where('is_active', true)->orderBy('price', 'asc')->get();

        $biharLocations = ServiceArea::where('category', 'Bihar Core')
            ->orWhere('category', 'Bihar Extended')
            ->orderBy('display_order')
            ->get();

        $upLocations = ServiceArea::where('category', 'Nearby Uttar Pradesh')
            ->orderBy('display_order')
            ->get();

        $authUser = Auth::user();
        $settings = SiteSetting::all()->pluck('value', 'key');
        $prefill = $request->query();
        $bookingType = 'package';
        $decorationModel = null;

        return view('bookings.create', compact(
            'packageModel',
            'decorationModel',
            'bookingType',
            'addons',
            'biharLocations',
            'upLocations',
            'authUser',
            'settings',
            'prefill'
        ));
    }

    /**
     * Store a new booking request in a database transaction with server-side price recalculation
     * and automatic customer account creation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_type' => 'required|in:decoration,package',
            'decoration_id' => 'nullable|required_if:booking_type,decoration|exists:decorations,id',
            'package_id' => 'nullable|required_if:booking_type,package|exists:packages,id',
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
            'customer_email' => 'required|email|max:255',
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

        $tempPassword = null;
        $createdNewAccount = false;
        $targetUser = null;

        // Run database transaction to ensure atomic creation
        $booking = DB::transaction(function () use ($validated, $request, &$tempPassword, &$createdNewAccount, &$targetUser) {
            $bookingType = $validated['booking_type'];
            $baseAmount = 0.00;
            $decorationId = null;
            $packageId = null;

            // 1. Server-side price calculation (NEVER trust frontend price)
            if ($bookingType === 'package') {
                $package = Package::lockForUpdate()->findOrFail($validated['package_id']);
                $packageId = $package->id;
                $baseAmount = (float)$package->price;
            } else {
                $decoration = Decoration::lockForUpdate()->findOrFail($validated['decoration_id']);
                $decorationId = $decoration->id;
                $baseAmount = (float)$decoration->actual_booking_price;
            }

            // 2. Server-side add-on calculation
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

            // 3. User Account Resolution / Auto Creation
            $customerEmail = trim(strtolower($validated['customer_email']));
            $userId = Auth::id();

            if (!$userId) {
                $existingUser = User::where('email', $customerEmail)->first();
                if ($existingUser) {
                    $userId = $existingUser->id;
                    $targetUser = $existingUser;
                } else {
                    // Automatically create customer account with secure random password
                    $tempPassword = Str::random(10);
                    $newUser = User::create([
                        'name' => $validated['customer_name'],
                        'email' => $customerEmail,
                        'phone' => $validated['customer_phone'],
                        'whatsapp' => $validated['whatsapp_number'] ?? $validated['customer_phone'],
                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'address' => $validated['address_line'],
                        'role' => 'customer',
                        'is_active' => true,
                        'password' => Hash::make($tempPassword),
                    ]);
                    $userId = $newUser->id;
                    $targetUser = $newUser;
                    $createdNewAccount = true;
                }
            } else {
                $targetUser = Auth::user();
            }

            // 4. Generate unique human-friendly booking reference: AU-YYYYMMDD-XXXXX
            $datePrefix = Carbon::now()->format('Ymd');
            do {
                $randSuffix = str_pad((string)random_int(100, 99999), 5, '0', STR_PAD_LEFT);
                $reference = "AU-{$datePrefix}-{$randSuffix}";
            } while (Booking::where('booking_reference', $reference)->exists());

            $eventDate = Carbon::parse($validated['event_date'])->format('Y-m-d');

            // 5. Create Booking record
            $booking = Booking::create([
                'booking_reference' => $reference,
                'user_id' => $userId,
                'booking_type' => $bookingType,
                'decoration_id' => $decorationId,
                'package_id' => $packageId,
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
                'customer_email' => $customerEmail,
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
                'note' => 'Booking request submitted by client via Aditya Utsav portal.',
                'changed_by_user_id' => $userId,
            ]);

            return $booking;
        });

        // If newly created customer account, dispatch email with temporary password & login
        if ($createdNewAccount && $targetUser && $tempPassword) {
            NotificationService::notifyCustomerAccountCreated($targetUser, $tempPassword, $booking);
            // Log in newly registered customer
            Auth::login($targetUser);
        } elseif ($targetUser && !Auth::check()) {
            Auth::login($targetUser);
        }

        // Send booking submitted notification
        NotificationService::notifyBookingSubmitted($booking);

        return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Your booking request has been successfully submitted! Our team will verify and contact you.');
    }

    /**
     * Show booking request confirmation page.
     */
    public function confirmation($reference)
    {
        $booking = Booking::with([
            'decoration.category',
            'decoration.images',
            'package',
            'addons.addon',
            'statusHistories'
        ])
        ->where('booking_reference', $reference)
        ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('bookings.confirmation', compact('booking', 'settings'));
    }
}
