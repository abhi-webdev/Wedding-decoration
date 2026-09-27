<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPaymentController extends Controller
{
    /**
     * Display listing of all payments with metrics, search, and status filters.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.decoration', 'booking.package', 'customer', 'recordedByUser', 'verifiedByUser']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('payment_reference', 'like', "%{$s}%")
                  ->orWhere('receipt_number', 'like', "%{$s}%")
                  ->orWhere('transaction_reference', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($userQ) use ($s) {
                      $userQ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%")
                            ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('booking', function ($bQ) use ($s) {
                      $bQ->where('booking_reference', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'paid') {
                $query->whereIn('status', ['paid', 'accepted', 'successful']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_received' => (float) Payment::whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount'),
            'pending_verification' => (float) Payment::where('status', 'pending')->sum('amount'),
            'pending_requests_count' => Payment::where('status', 'pending')->count(),
            'advance_received' => (float) Payment::whereIn('status', ['paid', 'accepted', 'successful'])->where('payment_type', 'advance')->sum('amount'),
            'total_transactions' => Payment::count(),
        ];

        return view('admin.payments.index', compact('payments', 'metrics'));
    }

    /**
     * Dedicated listing for Pending Payment Requests requiring verification.
     */
    public function requests(Request $request)
    {
        $query = Payment::with(['booking.decoration', 'booking.package', 'customer', 'recordedByUser'])
            ->where('status', 'pending');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('payment_reference', 'like', "%{$s}%")
                  ->orWhere('transaction_reference', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($userQ) use ($s) {
                      $userQ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%")
                            ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('booking', function ($bQ) use ($s) {
                      $bQ->where('booking_reference', 'like', "%{$s}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_received' => (float) Payment::whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount'),
            'pending_verification' => (float) Payment::where('status', 'pending')->sum('amount'),
            'pending_requests_count' => Payment::where('status', 'pending')->count(),
            'advance_received' => (float) Payment::whereIn('status', ['paid', 'accepted', 'successful'])->where('payment_type', 'advance')->sum('amount'),
            'total_transactions' => Payment::count(),
        ];

        return view('admin.payments.index', [
            'payments' => $payments,
            'metrics' => $metrics,
            'isRequestsTab' => true,
        ]);
    }

    /**
     * Accept and verify a customer payment request.
     * Moves payment into verified Payment History and generates an official Receipt.
     */
    public function acceptPayment(Request $request, $id)
    {
        $payment = Payment::with(['booking.user', 'booking.decoration', 'booking.package'])->findOrFail($id);
        $booking = $payment->booking;

        DB::beginTransaction();
        try {
            // Generate unique receipt number if not already generated: AUR-YYYYMMDD-XXXXX
            if (empty($payment->receipt_number)) {
                $datePart = Carbon::today()->format('Ymd');
                do {
                    $randPart = str_pad((string)random_int(100, 99999), 5, '0', STR_PAD_LEFT);
                    $receiptNumber = "AUR-{$datePart}-{$randPart}";
                } while (Payment::where('receipt_number', $receiptNumber)->exists());
            } else {
                $receiptNumber = $payment->receipt_number;
            }

            // Update payment status to paid / accepted
            $payment->update([
                'status' => 'paid',
                'receipt_number' => $receiptNumber,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Re-calculate verified total paid on booking
            $totalPaid = (float) $booking->payments()->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');
            $effectiveTotal = $booking->effective_total;

            // Advance booking status if applicable
            if ($booking->status === 'accepted' || $booking->status === 'pending') {
                $newStatus = ($totalPaid >= $effectiveTotal && $effectiveTotal > 0) ? 'confirmed' : 'advance_paid';
                $booking->update(['status' => $newStatus]);

                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'status' => $newStatus,
                    'note' => "Payment of ₹" . number_format($payment->amount, 2) . " verified (Receipt #{$receiptNumber}). Booking status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".",
                    'changed_by_user_id' => Auth::id(),
                ]);
            } else {
                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'status' => $booking->status,
                    'note' => "Payment of ₹" . number_format($payment->amount, 2) . " verified and accepted (Receipt #{$receiptNumber}). Total verified paid: ₹" . number_format($totalPaid, 2) . ".",
                    'changed_by_user_id' => Auth::id(),
                ]);
            }

            // Create/update Invoice record
            $invDatePart = Carbon::today()->format('Ymd');
            $invNum = sprintf('AUI-%s-%05d', $invDatePart, $payment->id);
            $balanceDue = max(0, $effectiveTotal - $totalPaid);

            Invoice::updateOrCreate(
                ['booking_id' => $booking->id, 'user_id' => $booking->user_id],
                [
                    'invoice_number' => $invNum,
                    'invoice_type' => ($balanceDue <= 0) ? 'final' : 'receipt',
                    'subtotal' => $booking->base_amount,
                    'total' => $effectiveTotal,
                    'amount_paid' => $totalPaid,
                    'balance_due' => $balanceDue,
                    'status' => ($balanceDue <= 0) ? 'paid' : 'partial',
                    'issued_at' => now(),
                    'notes' => "Official receipt generated for transaction #{$payment->payment_reference}.",
                ]
            );

            AdminActivityLog::log(
                'Verified & Accepted Payment',
                'Payment',
                $payment->id,
                "Accepted payment #{$payment->payment_reference} of ₹" . number_format($payment->amount, 2) . " for booking #{$booking->booking_reference}. Receipt #{$receiptNumber} generated."
            );

            DB::commit();

            // Notify customer via email with receipt
            NotificationService::notifyPaymentAccepted($payment);

            return back()->with('success', "Payment #{$payment->payment_reference} of ₹" . number_format($payment->amount, 2) . " has been VERIFIED and ACCEPTED. Receipt #{$receiptNumber} generated.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', "Failed to verify payment: " . $e->getMessage());
        }
    }

    /**
     * Reject a payment request with a mandatory rejection reason.
     */
    public function rejectPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $payment = Payment::with(['booking.user'])->findOrFail($id);
        $reason = $validated['rejection_reason'];

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        AdminActivityLog::log(
            'Rejected Payment',
            'Payment',
            $payment->id,
            "Rejected payment #{$payment->payment_reference}. Reason: {$reason}"
        );

        // Notify customer via email
        NotificationService::notifyPaymentRejected($payment, $reason);

        return back()->with('warning', "Payment #{$payment->payment_reference} has been REJECTED. Customer has been notified.");
    }

    /**
     * Show form to manually record offline / office cash payment.
     */
    public function create(Request $request)
    {
        $booking = null;
        if ($request->filled('booking_id')) {
            $booking = Booking::with(['decoration', 'package', 'user', 'quotations', 'payments'])->findOrFail($request->booking_id);
        }

        $bookings = Booking::whereNotIn('status', ['cancelled', 'rejected'])
            ->with(['decoration', 'package', 'user', 'latestQuotation'])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('admin.payments.create', compact('booking', 'bookings'));
    }

    /**
     * Store manual payment recorded by admin staff.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:advance,balance,full,refund,other',
            'payment_method' => 'required|in:cash,bank_transfer,upi,card,online,other',
            'transaction_reference' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::with(['user', 'activeQuotation', 'payments'])->findOrFail($validated['booking_id']);

        DB::beginTransaction();
        try {
            $datePart = Carbon::today()->format('Ymd');
            $paymentReference = Payment::generatePaymentReference();
            $receiptNumber = Payment::generateReceiptNumber();

            $amount = (float) $validated['amount'];

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'quotation_id' => $booking->activeQuotation?->id,
                'user_id' => $booking->user_id,
                'payment_reference' => $paymentReference,
                'receipt_number' => $receiptNumber,
                'amount' => $amount,
                'payment_type' => $validated['payment_type'],
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'] ?? 'Manual payment recorded by Aditya Utsav accounts.',
                'recorded_by' => Auth::id(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $totalPaid = (float) $booking->payments()->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');
            $effectiveTotal = $booking->effective_total;

            if ($booking->status === 'accepted' || $booking->status === 'pending') {
                $newStatus = ($totalPaid >= $effectiveTotal && $effectiveTotal > 0) ? 'confirmed' : 'advance_paid';
                $booking->update(['status' => $newStatus]);
            }

            BookingStatusHistory::create([
                'booking_id' => $booking->id,
                'status' => $booking->status,
                'note' => "Payment of ₹" . number_format($amount, 2) . " recorded via " . strtoupper($validated['payment_method']) . " (Receipt #{$receiptNumber}).",
                'changed_by_user_id' => Auth::id(),
            ]);

            AdminActivityLog::log(
                'Recorded Payment',
                'Payment',
                $payment->id,
                "Recorded payment of ₹" . number_format($amount, 2) . " for booking #{$booking->booking_reference}"
            );

            DB::commit();

            NotificationService::notifyPaymentAccepted($payment);

            return redirect()->route('admin.payments.index')->with('success', "Payment {$paymentReference} of ₹" . number_format($amount, 2) . " recorded successfully. Receipt #{$receiptNumber} generated.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Error recording payment: " . $e->getMessage());
        }
    }

    /**
     * Display a single payment detail.
     */
    public function show($id)
    {
        $payment = Payment::with(['booking.decoration', 'booking.package', 'customer', 'recordedByUser', 'verifiedByUser', 'quotation'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * View and print official HTML receipt for admin.
     */
    public function receipt($id)
    {
        $payment = Payment::with(['booking.decoration', 'booking.package', 'booking.user', 'customer', 'verifiedByUser'])->findOrFail($id);
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('receipts.show', compact('payment', 'settings'));
    }

    /**
     * View and print official HTML receipt by receipt number for admin.
     */
    public function receiptByNumber($receiptNumber)
    {
        $payment = Payment::with(['booking.decoration', 'booking.package', 'booking.user', 'customer', 'verifiedByUser'])
            ->where('receipt_number', $receiptNumber)
            ->firstOrFail();
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('receipts.show', compact('payment', 'settings'));
    }
}
