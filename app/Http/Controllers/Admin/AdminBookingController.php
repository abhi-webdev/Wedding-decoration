<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of bookings with search, status filters, and pagination.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'decoration.category', 'package']);

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
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('decoration', fn($decQ) => $decQ->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('package', fn($pkgQ) => $pkgQ->where('name', 'like', "%{$search}%"));
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
            'accepted' => Booking::where('status', 'accepted')->count(),
            'quoted' => Booking::where('status', 'quoted')->count(),
            'confirmed' => Booking::whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::whereIn('status', ['cancelled', 'rejected'])->count(),
        ];

        // Cities for filter
        $cities = Booking::distinct()->whereNotNull('city')->where('city', '!=', '')->pluck('city')->sort()->values();

        return view('admin.bookings.index', compact('bookings', 'counts', 'cities'));
    }

    /**
     * Display full booking details with status history timeline, customer contact, and payment history.
     */
    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'decoration.category',
            'decoration.images',
            'package',
            'addons.addon',
            'statusHistories.changedByUser',
            'cancellationRequests',
            'rescheduleRequests',
            'quotations.items',
            'payments.recordedByUser',
            'payments.verifiedByUser',
            'invoices'
        ])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Accept a customer booking request.
     */
    public function acceptBooking(Request $request, $id)
    {
        $booking = Booking::with(['user', 'decoration', 'package'])->findOrFail($id);
        $oldStatus = $booking->status;

        $note = $request->input('admin_note') ?: "Booking request verified and ACCEPTED by " . Auth::user()->name . ". Client may now proceed with token advance payment.";

        $booking->update([
            'status' => 'accepted',
        ]);

        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => 'accepted',
            'note' => $note,
            'changed_by_user_id' => Auth::id(),
        ]);

        AdminActivityLog::log(
            'Accepted Booking',
            'Booking',
            $booking->id,
            "Accepted booking #{$booking->booking_reference} for {$booking->booked_item_name} on {$booking->formatted_event_date}. Note: {$note}"
        );

        // Send email notification to customer
        NotificationService::notifyBookingAccepted($booking);

        return back()->with('success', "Booking #{$booking->booking_reference} has been ACCEPTED. Customer has been notified via email.");
    }

    /**
     * Reject a customer booking request with a mandatory reason.
     */
    public function rejectBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $booking = Booking::with(['user', 'decoration', 'package'])->findOrFail($id);
        $reason = $validated['rejection_reason'];

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $reason,
        ]);

        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => 'rejected',
            'note' => "Booking rejected by " . Auth::user()->name . ". Reason: {$reason}",
            'changed_by_user_id' => Auth::id(),
        ]);

        AdminActivityLog::log(
            'Rejected Booking',
            'Booking',
            $booking->id,
            "Rejected booking #{$booking->booking_reference}. Reason: {$reason}"
        );

        // Send email notification to customer
        NotificationService::notifyBookingRejected($booking, $reason);

        return back()->with('warning', "Booking #{$booking->booking_reference} has been REJECTED. Customer has been notified.");
    }

    /**
     * Update booking status and record status history with admin note.
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::with(['user', 'decoration', 'package'])->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,quoted,confirmed,advance_paid,scheduled,completed,cancelled,rejected,rescheduled',
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

        // Notify if newly accepted or rejected
        if ($newStatus === 'accepted' && $oldStatus !== 'accepted') {
            NotificationService::notifyBookingAccepted($booking);
        } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
            NotificationService::notifyBookingRejected($booking, $note);
        }

        return back()->with('success', "Booking #{$booking->booking_reference} status successfully updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
    }
}
