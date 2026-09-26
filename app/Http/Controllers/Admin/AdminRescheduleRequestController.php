<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RescheduleRequest;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminRescheduleRequestController extends Controller
{
    /**
     * Display a listing of reschedule requests.
     */
    public function index(Request $request)
    {
        $query = RescheduleRequest::with(['booking.decoration', 'user'])->latest();

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->paginate(20)->withQueryString();
        $rescheduleRequests = $requests;

        $counts = [
            'all' => RescheduleRequest::count(),
            'pending' => RescheduleRequest::where('status', 'pending')->count(),
            'approved' => RescheduleRequest::where('status', 'approved')->count(),
            'rejected' => RescheduleRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.reschedule-requests.index', compact('requests', 'rescheduleRequests', 'counts'));
    }

    /**
     * Approve reschedule request after verifying date availability.
     */
    public function approve(Request $request, $id)
    {
        $rescheduleReq = RescheduleRequest::with('booking')->findOrFail($id);
        $booking = $rescheduleReq->booking;

        if (!$booking) {
            return back()->with('error', "Associated booking not found.");
        }

        // 1. Verify conflicting confirmed/scheduled bookings on the new date
        $conflicting = Booking::where('decoration_id', $booking->decoration_id)
            ->where('id', '!=', $booking->id)
            ->where('event_date', $rescheduleReq->requested_date)
            ->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])
            ->exists();

        if ($conflicting) {
            return back()->with('error', "Cannot approve reschedule: The decoration is already booked for another client on {$rescheduleReq->formatted_requested_date}. Please contact the customer for an alternative date.");
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $note = $validated['admin_note'] ?? $validated['admin_notes'] ?? ("Reschedule request approved for new date " . $rescheduleReq->formatted_requested_date . " by " . Auth::user()->name);

        // 2. Update booking date and time
        $oldDate = $booking->formatted_event_date ?? $booking->event_date;
        $booking->update([
            'event_date' => $rescheduleReq->requested_date,
            'start_time' => $rescheduleReq->requested_start_time ?? $booking->start_time,
            'end_time' => $rescheduleReq->requested_end_time ?? $booking->end_time,
            'status' => 'rescheduled',
        ]);

        // 3. Update reschedule request
        $rescheduleReq->update([
            'status' => 'approved',
            'admin_note' => $note,
        ]);

        // 4. Log status history
        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'status' => 'rescheduled',
            'note' => "Date rescheduled from {$oldDate} to {$rescheduleReq->formatted_requested_date}. Note: {$note}",
            'changed_by_user_id' => Auth::id(),
        ]);

        AdminActivityLog::log(
            'Approved Reschedule',
            'RescheduleRequest',
            $rescheduleReq->id,
            "Rescheduled booking #{$booking->booking_reference} to {$rescheduleReq->formatted_requested_date}"
        );

        return back()->with('success', "Reschedule request approved! Booking #{$booking->booking_reference} event date has been updated to " . $rescheduleReq->formatted_requested_date . ".");
    }

    /**
     * Reject reschedule request and preserve current event date.
     */
    public function reject(Request $request, $id)
    {
        $rescheduleReq = RescheduleRequest::with('booking')->findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $note = $validated['admin_note'] ?? $validated['admin_notes'] ?? 'Reschedule request declined by planner.';

        $rescheduleReq->update([
            'status' => 'rejected',
            'admin_note' => $note,
        ]);

        AdminActivityLog::log(
            'Rejected Reschedule',
            'RescheduleRequest',
            $rescheduleReq->id,
            "Rejected reschedule for booking #{$rescheduleReq->booking_id}. Note: {$note}"
        );

        return back()->with('info', "Reschedule request has been rejected.");
    }
}
