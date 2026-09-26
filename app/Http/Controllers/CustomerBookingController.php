<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\BookingCancellationRequest;
use App\Models\BookingRescheduleRequest;

class CustomerBookingController extends Controller
{
    /**
     * Display a listing of bookings for the authenticated customer only.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Enforce customer ownership query FIRST
        $query = Booking::with(['decoration.category', 'addons.addon'])
            ->where('user_id', $userId);

        // 1. Status Filter
        $statusFilter = $request->input('status', 'all');
        if ($statusFilter && $statusFilter !== 'all') {
            if ($statusFilter === 'confirmed') {
                $query->whereIn('status', ['confirmed', 'advance_paid', 'scheduled']);
            } else {
                $query->where('status', $statusFilter);
            }
        }

        // 2. Search Query (Reference, Decoration Name, Event Type, City)
        $searchQuery = trim($request->input('search', ''));
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('booking_reference', 'like', "%{$searchQuery}%")
                  ->orWhere('event_type', 'like', "%{$searchQuery}%")
                  ->orWhere('city', 'like', "%{$searchQuery}%")
                  ->orWhereHas('decoration', function ($decQ) use ($searchQuery) {
                      $decQ->where('name', 'like', "%{$searchQuery}%");
                  });
            });
        }

        // 3. Sorting
        $sortOption = $request->input('sort', 'newest');
        switch ($sortOption) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_soonest':
                $query->orderBy('event_date', 'asc');
                break;
            case 'date_latest':
                $query->orderBy('event_date', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 4. Pagination (10 per page, preserve query parameters)
        $bookings = $query->paginate(10)->withQueryString();

        // Status counts for customer tabs
        $counts = [
            'all' => Booking::where('user_id', $userId)->count(),
            'pending' => Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('user_id', $userId)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count(),
            'completed' => Booking::where('user_id', $userId)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];

        return view('account.bookings.index', compact(
            'bookings',
            'statusFilter',
            'searchQuery',
            'sortOption',
            'counts'
        ));
    }

    /**
     * Display a single customer booking with strict ownership check (returns 404 if not owned).
     */
    public function show($bookingIdentifier)
    {
        $userId = Auth::id();

        $booking = Booking::with([
            'decoration.category',
            'decoration.images',
            'addons.addon',
            'statusHistories',
            'cancellationRequests',
            'rescheduleRequests'
        ])
        ->where('user_id', $userId)
        ->where(function ($q) use ($bookingIdentifier) {
            if (is_numeric($bookingIdentifier)) {
                $q->where('id', $bookingIdentifier);
            } else {
                $q->where('booking_reference', $bookingIdentifier);
            }
        })
        ->firstOrFail(); // Returns 404 to never reveal whether another user's booking exists

        return view('account.bookings.show', compact('booking'));
    }

    /**
     * Submit a cancellation request for a customer's booking.
     */
    public function cancelRequest(Request $request, $bookingIdentifier)
    {
        $userId = Auth::id();

        $booking = Booking::where('user_id', $userId)
            ->where(function ($q) use ($bookingIdentifier) {
                if (is_numeric($bookingIdentifier)) {
                    $q->where('id', $bookingIdentifier);
                } else {
                    $q->where('booking_reference', $bookingIdentifier);
                }
            })
            ->firstOrFail();

        // Verify status allows cancellation request
        $allowedStatuses = ['pending', 'quoted', 'confirmed', 'advance_paid', 'scheduled'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return back()->with('error', 'Cancellation requests cannot be submitted for bookings that are already ' . $booking->status . '.');
        }

        // Prevent duplicate pending requests
        if ($booking->cancellationRequests()->where('status', 'pending')->exists()) {
            return back()->with('info', 'A cancellation request for this booking is already under review by our management team.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:2000',
        ]);

        BookingCancellationRequest::create([
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => 'pending',
            'admin_note' => null,
        ]);

        return back()->with('success', 'Your cancellation request has been submitted. Our manager will review and contact you shortly.');
    }

    /**
     * Submit a reschedule request for a customer's booking.
     */
    public function rescheduleRequest(Request $request, $bookingIdentifier)
    {
        $userId = Auth::id();

        $booking = Booking::where('user_id', $userId)
            ->where(function ($q) use ($bookingIdentifier) {
                if (is_numeric($bookingIdentifier)) {
                    $q->where('id', $bookingIdentifier);
                } else {
                    $q->where('booking_reference', $bookingIdentifier);
                }
            })
            ->firstOrFail();

        // Verify status allows reschedule request
        $allowedStatuses = ['pending', 'quoted', 'confirmed', 'advance_paid'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return back()->with('error', 'Reschedule requests cannot be submitted for bookings with status: ' . $booking->status . '.');
        }

        // Prevent duplicate pending requests
        if ($booking->rescheduleRequests()->where('status', 'pending')->exists()) {
            return back()->with('info', 'A reschedule request for this booking is already under review by our management team.');
        }

        $validated = $request->validate([
            'requested_date' => 'required|date|after_or_equal:today',
            'requested_start_time' => 'required|string|max:20',
            'requested_end_time' => 'required|string|max:20',
            'reason' => 'nullable|string|max:2000',
        ]);

        if (strtotime($validated['requested_end_time']) <= strtotime($validated['requested_start_time'])) {
            return back()->withErrors(['requested_end_time' => 'Requested end time must be later than start time.']);
        }

        BookingRescheduleRequest::create([
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'requested_date' => Carbon::parse($validated['requested_date'])->format('Y-m-d'),
            'requested_start_time' => $validated['requested_start_time'],
            'requested_end_time' => $validated['requested_end_time'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'admin_note' => null,
        ]);

        return back()->with('success', 'Your reschedule request for the new date has been submitted. Our team will verify date availability and update you.');
    }
}
