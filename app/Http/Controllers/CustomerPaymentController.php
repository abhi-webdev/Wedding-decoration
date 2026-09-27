<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerPaymentController extends Controller
{
    /**
     * Display listing of customer's payments and pending payment requests.
     */
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::with(['booking.decoration', 'booking.package', 'verifiedByUser'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $bookings = Booking::where('user_id', $user->id)
            ->with(['activeQuotation', 'payments', 'decoration', 'package'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        $totalEstimated = $bookings->sum(fn($b) => $b->effective_total);
        $totalPaid = (float) Payment::where('user_id', $user->id)->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');
        $totalPending = (float) Payment::where('user_id', $user->id)->where('status', 'pending')->sum('amount');
        $balanceRemaining = max(0, $totalEstimated - $totalPaid);

        $bankDetails = [
            'account_name' => SiteSetting::get('bank_account_name', 'ADITYA UTSAV EVENT SERVICES'),
            'account_number' => SiteSetting::get('bank_account_no', '98765432101234'),
            'ifsc_code' => SiteSetting::get('bank_ifsc', 'SBIN0001234'),
            'bank_name' => SiteSetting::get('bank_name', 'State Bank of India, Siwan Main Branch'),
            'upi_id' => SiteSetting::get('upi_id', 'adityautsav@sbi'),
            'whatsapp' => SiteSetting::get('business_whatsapp', '+91 98765 43210'),
        ];

        return view('account.payments.index', compact(
            'payments',
            'bookings',
            'totalEstimated',
            'totalPaid',
            'totalPending',
            'balanceRemaining',
            'bankDetails'
        ));
    }

    /**
     * Submit a customer payment request for an accepted booking.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:advance,balance,full,other',
            'payment_method' => 'required|in:upi,bank_transfer,cash,card,online,other',
            'payment_date' => 'required|date',
            'transaction_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Strict customer ownership check
        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', $userId)
            ->firstOrFail();

        // Validate booking status allows payment
        $allowedStatuses = ['accepted', 'confirmed', 'advance_paid', 'scheduled', 'completed', 'quoted'];
        if (!in_array($booking->status, $allowedStatuses)) {
            return back()->with('error', 'Payment can only be submitted for bookings that have been accepted or confirmed by Aditya Utsav.');
        }

        // Validate amount does not exceed remaining balance
        $remainingBalance = $booking->balance_due;
        $amount = (float) $validated['amount'];

        if ($amount > $remainingBalance && $remainingBalance > 0) {
            return back()->withInput()->with('error', 'Payment amount (₹' . number_format($amount, 2) . ') cannot exceed the remaining balance of ₹' . number_format($remainingBalance, 2) . '.');
        }

        // Prevent duplicate pending payment with same transaction reference
        if (!empty($validated['transaction_reference'])) {
            $duplicate = Payment::where('booking_id', $booking->id)
                ->where('transaction_reference', $validated['transaction_reference'])
                ->where('status', 'pending')
                ->exists();

            if ($duplicate) {
                return back()->withInput()->with('error', 'A payment request with this transaction reference is already under verification.');
            }
        }

        // Generate unique payment reference: AUP-YYYYMMDD-XXXXX
        $paymentReference = Payment::generatePaymentReference();

        // Create Payment request with status = pending
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'quotation_id' => $booking->activeQuotation?->id,
            'user_id' => $userId,
            'payment_reference' => $paymentReference,
            'amount' => $amount,
            'payment_type' => $validated['payment_type'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? 'Payment submitted by customer via portal.',
            'recorded_by' => null,
        ]);

        // Send notification to customer
        NotificationService::notifyPaymentSubmitted($payment);

        return redirect()->route('account.payments.index')
            ->with('success', "Payment request {$paymentReference} of ₹" . number_format($amount, 2) . " submitted successfully! It is currently pending verification by our accounts team.");
    }

    /**
     * View and print official HTML receipt for a verified payment (Customer view).
     */
    public function receipt($id)
    {
        $userId = Auth::id();

        $payment = Payment::with(['booking.decoration', 'booking.package', 'booking.user', 'customer', 'verifiedByUser'])
            ->where('user_id', $userId)
            ->whereIn('status', ['paid', 'accepted', 'successful'])
            ->where('id', $id)
            ->firstOrFail();

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('receipts.show', compact('payment', 'settings'));
    }

    /**
     * View and print official HTML receipt by receipt number.
     */
    public function receiptByNumber($receiptNumber)
    {
        $query = Payment::with(['booking.decoration', 'booking.package', 'booking.user', 'customer', 'verifiedByUser'])
            ->whereIn('status', ['paid', 'accepted', 'successful'])
            ->where('receipt_number', $receiptNumber);

        if (Auth::check() && Auth::user()->isAdmin()) {
            // Admin can view any receipt
            $payment = $query->firstOrFail();
        } elseif (Auth::check()) {
            // Customer can view their own receipts
            $userId = Auth::id();
            $payment = $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('booking', function ($bq) use ($userId) {
                      $bq->where('user_id', $userId);
                  });
            })->firstOrFail();
        } else {
            // Public receipt link (e.g. from email with verified receipt number)
            $payment = $query->firstOrFail();
        }

        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('receipts.show', compact('payment', 'settings'));
    }
}
