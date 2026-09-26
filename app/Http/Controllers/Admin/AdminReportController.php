<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Payment;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * Display Business Operations & Financial Reports Dashboard.
     */
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $city = $request->input('city');
        $status = $request->input('status');

        // 1. Base Query Builders with Date & Location Filtering
        $bookingQuery = Booking::query();
        $paymentQuery = Payment::query();
        $quotationQuery = Quotation::query();

        if ($fromDate) {
            $bookingQuery->whereDate('event_date', '>=', $fromDate);
            $paymentQuery->whereDate('payment_date', '>=', $fromDate);
            $quotationQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $bookingQuery->whereDate('event_date', '<=', $toDate);
            $paymentQuery->whereDate('payment_date', '<=', $toDate);
            $quotationQuery->whereDate('created_at', '<=', $toDate);
        }

        if ($city) {
            $bookingQuery->where('city', $city);
        }

        if ($status && $status !== 'all') {
            $bookingQuery->where('status', $status);
        }

        // 2. Financial Metrics
        $totalBookings = (clone $bookingQuery)->count();
        $confirmedBookingsCount = (clone $bookingQuery)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled', 'completed'])->count();
        $completedBookingsCount = (clone $bookingQuery)->where('status', 'completed')->count();
        $cancelledBookingsCount = (clone $bookingQuery)->whereIn('status', ['cancelled', 'rejected'])->count();

        $activeBookingValue = (float) (clone $bookingQuery)->whereNotIn('status', ['cancelled', 'rejected'])->sum('estimated_total');
        $confirmedBookingValue = (float) (clone $bookingQuery)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled', 'completed'])->sum('estimated_total');

        $totalCollected = (float) (clone $paymentQuery)->whereIn('status', ['paid', 'successful'])->sum('amount');
        $advanceCollected = (float) (clone $paymentQuery)->whereIn('status', ['paid', 'successful'])->where('payment_type', 'advance')->sum('amount');
        $balanceCollected = (float) (clone $paymentQuery)->whereIn('status', ['paid', 'successful'])->where('payment_type', 'balance')->sum('amount');

        $outstandingBalance = max(0, $confirmedBookingValue - $totalCollected);

        // 3. Quotation Metrics
        $totalQuotationsCount = (clone $quotationQuery)->count();
        $acceptedQuotationsCount = (clone $quotationQuery)->where('status', 'accepted')->count();
        $totalQuotedValue = (float) (clone $quotationQuery)->whereIn('status', ['sent', 'accepted'])->sum('grand_total');

        // 4. City / Service Area Breakdown
        $cityBreakdown = Booking::selectRaw('city, count(*) as total, sum(estimated_total) as value')
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->take(8)
            ->get();

        // 5. Recent Transactions
        $recentPayments = Payment::with(['booking', 'customer'])
            ->orderBy('payment_date', 'desc')
            ->take(8)
            ->get();

        $serviceAreas = ServiceArea::orderBy('name')->pluck('name');

        return view('admin.reports.index', compact(
            'fromDate',
            'toDate',
            'city',
            'status',
            'totalBookings',
            'confirmedBookingsCount',
            'completedBookingsCount',
            'cancelledBookingsCount',
            'activeBookingValue',
            'confirmedBookingValue',
            'totalCollected',
            'advanceCollected',
            'balanceCollected',
            'outstandingBalance',
            'totalQuotationsCount',
            'acceptedQuotationsCount',
            'totalQuotedValue',
            'cityBreakdown',
            'recentPayments',
            'serviceAreas'
        ));
    }

    /**
     * Export Bookings data as native streamed CSV.
     */
    public function exportBookings(Request $request): StreamedResponse
    {
        $fileName = 'aditya_utsav_bookings_' . date('Y-m-d_His') . '.csv';

        $query = Booking::with('decoration');
        if ($request->filled('from_date')) {
            $query->whereDate('event_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('event_date', '<=', $request->to_date);
        }
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Write CSV Header
            fputcsv($handle, [
                'Booking Reference',
                'Customer Name',
                'Phone',
                'Email',
                'Decoration / Package',
                'Event Type',
                'Event Date',
                'Start Time',
                'End Time',
                'City',
                'State',
                'Base Amount (INR)',
                'Addon Amount (INR)',
                'Estimated Total (INR)',
                'Status',
                'Created At'
            ]);

            $query->chunk(200, function ($bookings) use ($handle) {
                foreach ($bookings as $b) {
                    fputcsv($handle, [
                        $b->booking_reference,
                        $b->customer_name,
                        $b->customer_phone,
                        $b->customer_email ?? 'N/A',
                        $b->decoration->name ?? 'Custom Setup',
                        $b->event_type,
                        $b->event_date,
                        $b->start_time,
                        $b->end_time,
                        $b->city,
                        $b->state,
                        $b->base_amount,
                        $b->addon_amount,
                        $b->estimated_total,
                        ucfirst($b->status),
                        $b->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Payments data as native streamed CSV.
     */
    public function exportPayments(Request $request): StreamedResponse
    {
        $fileName = 'aditya_utsav_payments_' . date('Y-m-d_His') . '.csv';

        $query = Payment::with(['booking', 'customer']);
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Payment Reference',
                'Booking Reference',
                'Customer Name',
                'Amount (INR)',
                'Payment Type',
                'Payment Method',
                'Status',
                'Transaction Ref',
                'Payment Date',
                'Notes'
            ]);

            $query->chunk(200, function ($payments) use ($handle) {
                foreach ($payments as $p) {
                    fputcsv($handle, [
                        $p->payment_reference,
                        $p->booking?->booking_reference ?? 'N/A',
                        $p->customer?->name ?? 'N/A',
                        $p->amount,
                        ucfirst($p->payment_type),
                        strtoupper($p->payment_method),
                        ucfirst($p->status),
                        $p->transaction_reference ?? 'N/A',
                        $p->payment_date ? Carbon::parse($p->payment_date)->format('Y-m-d H:i:s') : 'N/A',
                        $p->notes ?? ''
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Quotations data as native streamed CSV.
     */
    public function exportQuotations(Request $request): StreamedResponse
    {
        $fileName = 'aditya_utsav_quotations_' . date('Y-m-d_His') . '.csv';

        $query = Quotation::with(['booking', 'customer']);
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Quotation Number',
                'Booking Reference',
                'Customer Name',
                'Subtotal (INR)',
                'Addon Total (INR)',
                'Discount (INR)',
                'Tax (INR)',
                'Grand Total (INR)',
                'Advance Required (INR)',
                'Balance (INR)',
                'Valid Until',
                'Status',
                'Created At'
            ]);

            $query->chunk(200, function ($quotations) use ($handle) {
                foreach ($quotations as $q) {
                    fputcsv($handle, [
                        $q->quotation_number,
                        $q->booking?->booking_reference ?? 'N/A',
                        $q->customer?->name ?? 'N/A',
                        $q->subtotal,
                        $q->addon_total,
                        $q->discount_amount,
                        $q->tax_amount,
                        $q->grand_total,
                        $q->advance_amount,
                        $q->balance_amount,
                        $q->valid_until ? $q->valid_until->format('Y-m-d') : 'N/A',
                        ucfirst($q->status),
                        $q->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
