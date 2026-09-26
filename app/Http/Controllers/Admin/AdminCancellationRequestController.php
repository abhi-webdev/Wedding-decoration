<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CancellationRequest;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminCancellationRequestController extends Controller
{
    /**
     * Display a listing of cancellation requests.
     */
    public function index(Request $request)
    {
        $query = CancellationRequest::with(['booking.decoration', 'user'])->latest();

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->paginate(20)->withQueryString();
        $cancellationRequests = $requests;

        $counts = [
            'all' => CancellationRequest::count(),
            'pending' => CancellationRequest::where('status', 'pending')->count(),
            'approved' => CancellationRequest::where('status', 'approved')->count(),
            'rejected' => CancellationRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.cancellation-requests.index', compact('requests', 'cancellationRequests', 'counts'));
    }

    /**
     * Approve cancellation request and set booking to cancelled.
     */
    public function approve(Request $request, $id)
    {
        $cancelReq = CancellationRequest::with('booking')->findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $note = $validated['admin_note'] ?? $validated['admin_notes'] ?? ("Cancellation request approved by " . Auth::user()->name);

        // 1. Update request status
        $cancelReq->update([
            'status' => 'approved',
            'admin_note' => $note,
        ]);

        // 2. Update booking status to cancelled
        $booking = $cancelReq->booking;
        if ($booking) {
            $booking->update([
                'status' => 'cancelled',
            ]);

            // 3. Record status history
            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'status' => 'cancelled',
                'note' => "Cancellation request approved: " . $cancelReq->reason . " (" . $note . ")",
                'changed_by_user_id' => Auth::id(),
            ]);

            AdminActivityLog::log(
                'Approved Cancellation',
                'CancellationRequest',
                $cancelReq->id,
                "Approved cancellation for booking #{$booking->booking_reference}"
            );
        }

        return back()->with('success', "Cancellation request has been approved and marked as Cancelled.");
    }

    /**
     * Reject cancellation request and keep booking in original status.
     */
    public function reject(Request $request, $id)
    {
        $cancelReq = CancellationRequest::with('booking')->findOrFail($id);

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $note = $validated['admin_note'] ?? $validated['admin_notes'] ?? 'Request declined by planner.';

        $cancelReq->update([
            'status' => 'rejected',
            'admin_note' => $note,
        ]);

        AdminActivityLog::log(
            'Rejected Cancellation',
            'CancellationRequest',
            $cancelReq->id,
            "Rejected cancellation for request #{$cancelReq->id}. Note: {$note}"
        );

        return back()->with('info', "Cancellation request has been rejected. Booking remains active.");
    }
}
