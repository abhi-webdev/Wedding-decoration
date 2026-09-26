<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of bookings with search, status filters, and pagination.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'decoration.category']);

        // 1. Status Filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            if ($status === 'confirmed') {
                $query->whereIn('status', ['confirmed', 'advance_paid', 'scheduled']);
            } else {
                $query->where('status', $status);
            }
        }

        // 2. City Filter
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        // 3. Search Query
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('decoration', fn($decQ) => $decQ->where('name', 'like', "%{$search}%"));
            });
        }

        // 4. Date Filter
        if ($request->filled('event_date')) {
            $query->where('event_date', $request->input('event_date'));
        }

        // Order & Paginate
        $bookings = $query->latest()->paginate(20)->withQueryString();

        // Status counts
        $counts = [
            'all' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'quoted' => Booking::where('status', 'quoted')->count(),
            'confirmed' => Booking::whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Cities for filter
        $cities = Booking::distinct()->whereNotNull('city')->where('city', '!=', '')->pluck('city')->sort()->values();

        return view('admin.bookings.index', compact('bookings', 'counts', 'cities'));
    }

    /**
     * Display full booking details with status history timeline.
     */
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'decoration.category',
            'decoration.images',
            'addons.addon',
            'statusHistories.changedByUser',
            'cancellationRequests',
            'rescheduleRequests',
            'quotations.items',
            'payments',
            'invoices'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status and record status history with admin note.
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,quoted,confirmed,advance_paid,scheduled,completed,cancelled,rejected,rescheduled',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];
        $note = $validated['admin_note'] ?: "Status updated from {$oldStatus} to {$newStatus} by " . Auth::user()->name;

        // 1. Update booking
        $booking->update([
            'status' => $newStatus,
        ]);

        // 2. Log in status history table
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => $newStatus,
            'note' => $note,
            'changed_by_user_id' => Auth::id(),
        ]);

        // 3. Log administrative audit log
        AdminActivityLog::log(
            'Updated Booking Status',
            'Booking',
            $booking->id,
            "Changed status of #{$booking->booking_reference} to '{$newStatus}'. Note: {$note}"
        );

        return back()->with('success', "Booking #{$booking->booking_reference} status successfully updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
    }
}
