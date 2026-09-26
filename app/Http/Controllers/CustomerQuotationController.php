<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerQuotationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $quotations = Quotation::with(['booking.decoration', 'items'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account.quotations.index', compact('quotations'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $quotation = Quotation::with(['booking.decoration.category', 'booking.addons.addon', 'items', 'creator'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if ($quotation->status === 'sent') {
            $quotation->update(['status' => 'viewed']);
        }

        return view('account.quotations.show', compact('quotation'));
    }

    public function accept(Request $request, $id)
    {
        $user = Auth::user();
        $quotation = Quotation::with('booking')->where('user_id', $user->id)->findOrFail($id);

        if ($quotation->isExpired()) {
            return back()->with('error', "This quotation has expired on {$quotation->valid_until->format('d M Y')}. Please contact Aditya Utsav to request an updated quotation.");
        }

        if ($quotation->status === 'accepted') {
            return back()->with('info', 'This quotation has already been accepted.');
        }

        DB::beginTransaction();
        try {
            $quotation->update([
                'status' => 'accepted',
                'approved_at' => now(),
            ]);

            $booking = $quotation->booking;
            if ($booking) {
                $booking->update([
                    'status' => 'confirmed',
                    'estimated_total' => $quotation->grand_total,
                ]);

                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'status' => 'confirmed',
                    'note' => "Quotation #{$quotation->quotation_number} accepted by client. Booking confirmed for ₹" . number_format($quotation->grand_total) . ". Advance required: ₹" . number_format($quotation->advance_amount),
                    'changed_by_user_id' => $user->id,
                ]);
            }

            AdminActivityLog::log(
                'Customer Accepted Quotation',
                'Quotation',
                $quotation->id,
                "Customer {$user->name} accepted quotation {$quotation->quotation_number}"
            );

            DB::commit();

            return redirect()->route('account.quotations.show', $quotation->id)->with('success', "🎉 Quotation #{$quotation->quotation_number} accepted successfully! Your wedding booking is now CONFIRMED. Please pay the advance of {$quotation->formatted_advance} to lock team availability.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error accepting quotation: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $quotation = Quotation::with('booking')->where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $quotation->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

            $booking = $quotation->booking;
            if ($booking && $booking->status === 'quoted') {
                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'status' => 'pending',
                    'note' => "Quotation #{$quotation->quotation_number} declined by customer. Reason: " . ($validated['reason'] ?? 'Not specified'),
                    'changed_by_user_id' => $user->id,
                ]);
            }

            AdminActivityLog::log(
                'Customer Declined Quotation',
                'Quotation',
                $quotation->id,
                "Customer {$user->name} declined quotation {$quotation->quotation_number}. Reason: " . ($validated['reason'] ?? 'None')
            );

            DB::commit();

            return redirect()->route('account.quotations.index')->with('info', "Quotation #{$quotation->quotation_number} has been declined. Our planner will reach out to tailor a package matching your budget.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error declining quotation: ' . $e->getMessage());
        }
    }
}
