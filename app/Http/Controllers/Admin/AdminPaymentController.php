<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.decoration', 'customer', 'recordedByUser']);

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

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_received' => Payment::where('status', 'paid')->sum('amount'),
            'advance_received' => Payment::where('status', 'paid')->where('payment_type', 'advance')->sum('amount'),
            'balance_received' => Payment::where('status', 'paid')->where('payment_type', 'balance')->sum('amount'),
            'total_transactions' => Payment::count(),
        ];

        return view('admin.payments.index', compact('payments', 'metrics'));
    }

    public function create(Request $request)
    {
        $booking = null;
        if ($request->filled('booking_id')) {
            $booking = Booking::with(['decoration', 'user', 'quotations', 'payments'])->findOrFail($request->booking_id);
        }

        $bookings = Booking::whereNotIn('status', ['cancelled', 'rejected'])
            ->with(['decoration', 'user', 'latestQuotation'])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('admin.payments.create', compact('booking', 'bookings'));
    }

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
            'create_invoice' => 'nullable|boolean',
        ]);

        $booking = Booking::with(['user', 'activeQuotation', 'payments'])->findOrFail($validated['booking_id']);

        DB::beginTransaction();
        try {
            // Generate unique AUP- reference
            $datePart = Carbon::today()->format('Ymd');
            $countToday = Payment::whereDate('created_at', Carbon::today())->count() + 1;
            $paymentReference = sprintf('AUP-%s-%05d', $datePart, $countToday);
            while (Payment::where('payment_reference', $paymentReference)->exists()) {
                $countToday++;
                $paymentReference = sprintf('AUP-%s-%05d', $datePart, $countToday);
            }

            $amount = (float) $validated['amount'];
            $quote = $booking->activeQuotation;

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'quotation_id' => $quote ? $quote->id : null,
                'user_id' => $booking->user_id,
                'payment_reference' => $paymentReference,
                'amount' => $amount,
                'payment_type' => $validated['payment_type'],
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'] ?? 'Manual payment verified by Aditya Utsav accounts.',
                'recorded_by' => Auth::id(),
            ]);

            // Check if advance requirements are met to progress booking state
            $currentTotalPaid = (float) $booking->payments()->whereIn('status', ['paid', 'successful'])->sum('amount');
            $advanceRequired = $quote ? (float) $quote->advance_amount : ((float) $booking->estimated_total * 0.40);

            if ($booking->status === 'confirmed' || $booking->status === 'quoted' || $booking->status === 'pending') {
                if ($currentTotalPaid >= $advanceRequired) {
                    $booking->update(['status' => 'advance_paid']);

                    BookingStatusHistory::create([
                        'booking_id' => $booking->id,
                        'status' => 'advance_paid',
                        'note' => "Advance payment of ₹" . number_format($amount) . " received via " . strtoupper($validated['payment_method']) . " (Ref: {$paymentReference}). Advance threshold of ₹" . number_format($advanceRequired) . " fulfilled.",
                        'changed_by_user_id' => Auth::id(),
                    ]);
                }
            }

            // Optionally generate receipt/invoice
            if ($request->boolean('create_invoice', true)) {
                $invDatePart = Carbon::today()->format('Ymd');
                $invCount = Invoice::whereDate('created_at', Carbon::today())->count() + 1;
                $invNum = sprintf('AUI-%s-%05d', $invDatePart, $invCount);
                while (Invoice::where('invoice_number', $invNum)->exists()) {
                    $invCount++;
                    $invNum = sprintf('AUI-%s-%05d', $invDatePart, $invCount);
                }

                $effectiveTotal = $booking->effective_total;
                $balanceDue = max(0, $effectiveTotal - $currentTotalPaid);

                Invoice::create([
                    'invoice_number' => $invNum,
                    'booking_id' => $booking->id,
                    'quotation_id' => $quote ? $quote->id : null,
                    'user_id' => $booking->user_id,
                    'invoice_type' => $validated['payment_type'] === 'advance' ? 'advance' : ($balanceDue <= 0 ? 'final' : 'receipt'),
                    'subtotal' => $quote ? $quote->subtotal : $booking->base_amount,
                    'discount' => $quote ? $quote->discount_amount : 0,
                    'tax' => $quote ? $quote->tax_amount : 0,
                    'total' => $effectiveTotal,
                    'amount_paid' => $currentTotalPaid,
                    'balance_due' => $balanceDue,
                    'status' => $balanceDue <= 0 ? 'paid' : 'partial',
                    'issued_at' => now(),
                    'due_at' => Carbon::parse($booking->event_date),
                    'notes' => "Payment receipt for transaction {$paymentReference}.",
                ]);
            }

            AdminActivityLog::log(
                'Recorded Payment',
                'Payment',
                $payment->id,
                "Recorded {$validated['payment_type']} payment of ₹" . number_format($amount) . " via {$validated['payment_method']} for booking #{$booking->booking_reference}"
            );

            DB::commit();

            return redirect()->route('admin.payments.index')->with('success', "Payment {$paymentReference} of ₹" . number_format($amount) . " recorded successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Error recording payment: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $payment = Payment::with(['booking.decoration', 'customer', 'recordedByUser', 'quotation'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }
}
