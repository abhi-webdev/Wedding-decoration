<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $payments = Payment::with('booking.decoration')
            ->where('user_id', $user->id)
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        $bookings = Booking::where('user_id', $user->id)
            ->with(['activeQuotation', 'payments'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        $totalEstimated = $bookings->sum(fn($b) => $b->effective_total);
        $totalPaid = Payment::where('user_id', $user->id)->where('status', 'paid')->sum('amount');
        $balanceRemaining = max(0, $totalEstimated - $totalPaid);

        $bankDetails = [
            'account_name' => SiteSetting::get('bank_account_name', 'ADITYA UTSAV EVENT SERVICES'),
            'account_number' => SiteSetting::get('bank_account_no', '98765432101234'),
            'ifsc_code' => SiteSetting::get('bank_ifsc', 'SBIN0001234'),
            'bank_name' => SiteSetting::get('bank_name', 'State Bank of India, Siwan Main Branch'),
            'upi_id' => SiteSetting::get('upi_id', 'adityautsav@sbi'),
            'whatsapp' => SiteSetting::get('business_whatsapp', '+91 98765 43210'),
        ];

        return view('account.payments.index', compact('payments', 'bookings', 'totalEstimated', 'totalPaid', 'balanceRemaining', 'bankDetails'));
    }
}
