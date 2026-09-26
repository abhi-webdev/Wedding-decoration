<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Decoration;
use App\Models\Package;
use App\Models\Offer;
use App\Models\QuoteRequest;
use App\Models\ContactMessage;
use App\Models\BookingCancellationRequest;
use App\Models\BookingRescheduleRequest;
use App\Models\Quotation;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with live database statistics & Phase 7 financial operations.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');

        $activeBookingsQuery = Booking::whereNotIn('status', ['cancelled', 'rejected']);
        $confirmedBookingsQuery = Booking::whereIn('status', ['confirmed', 'advance_paid', 'scheduled', 'completed']);

        $totalAdvanceReceived = (float) Payment::whereIn('status', ['paid', 'successful'])->sum('amount');
        $totalQuotedValue = (float) Quotation::whereIn('status', ['sent', 'accepted'])->sum('grand_total');
        $confirmedBookingValue = (float) $confirmedBookingsQuery->sum('estimated_total');
        $totalEstimatedPipeline = (float) $activeBookingsQuery->sum('estimated_total');
        $outstandingBalance = max(0, $confirmedBookingValue - $totalAdvanceReceived);

        $counts = [
            'total_bookings' => Booking::count(),
            'today_bookings' => Booking::whereDate('created_at', Carbon::today())->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'quoted_bookings' => Booking::where('status', 'quoted')->count(),
            'confirmed_bookings' => Booking::whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::whereIn('status', ['cancelled', 'rejected'])->count(),
            'pending_cancellations' => BookingCancellationRequest::where('status', 'pending')->count(),
            'pending_reschedules' => BookingRescheduleRequest::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_decorations' => Decoration::where('is_active', true)->count(),
            'total_packages' => Package::where('is_active', true)->count(),
            'total_offers' => Offer::where('is_active', true)->count(),
            'total_quotes' => QuoteRequest::count(),
            'pending_quotes' => QuoteRequest::whereIn('status', ['pending', 'new'])->count(),
            'new_messages' => ContactMessage::where('status', 'new')->count(),
            
            // Financial Operations KPIs
            'total_booking_value' => $totalEstimatedPipeline,
            'total_quoted_value' => $totalQuotedValue,
            'confirmed_booking_value' => $confirmedBookingValue,
            'advance_received' => $totalAdvanceReceived,
            'outstanding_balance' => $outstandingBalance,
            'total_quotations' => Quotation::count(),
            'total_payments' => Payment::count(),
            'total_invoices' => Invoice::count(),
        ];

        // Recent Bookings (Limit 8)
        $recentBookings = Booking::with(['user', 'decoration.category'])
            ->latest()
            ->take(8)
            ->get();

        // Recent Quotations (Limit 5)
        $recentQuotations = Quotation::with(['customer', 'booking.decoration'])
            ->latest()
            ->take(5)
            ->get();

        // Recent Payments (Limit 5)
        $recentPayments = Payment::with(['customer', 'booking'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming Confirmed/Scheduled Events
        $upcomingEvents = Booking::with(['user', 'decoration'])
            ->where('event_date', '>=', $today)
            ->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])
            ->orderBy('event_date', 'asc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('counts', 'recentBookings', 'recentQuotations', 'recentPayments', 'upcomingEvents'));
    }
}
