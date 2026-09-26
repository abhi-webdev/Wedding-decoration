<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\SiteSetting;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['booking.decoration', 'customer', 'quotation']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($userQ) use ($s) {
                      $userQ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%");
                  })
                  ->orWhereHas('booking', function ($bQ) use ($s) {
                      $bQ->where('booking_reference', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('invoice_type')) {
            $query->where('invoice_type', $request->invoice_type);
        }

        $invoices = $query->orderBy('issued_at', 'desc')->paginate(15)->withQueryString();

        $metrics = [
            'total_invoiced' => Invoice::sum('total'),
            'total_collected' => Invoice::sum('amount_paid'),
            'balance_due' => Invoice::sum('balance_due'),
        ];

        return view('admin.invoices.index', compact('invoices', 'metrics'));
    }

    public function generate(Request $request, $booking_id)
    {
        $booking = Booking::with(['user', 'activeQuotation', 'payments'])->findOrFail($booking_id);

        $invDatePart = Carbon::today()->format('Ymd');
        $invCount = Invoice::whereDate('created_at', Carbon::today())->count() + 1;
        $invNum = sprintf('AUI-%s-%05d', $invDatePart, $invCount);
        while (Invoice::where('invoice_number', $invNum)->exists()) {
            $invCount++;
            $invNum = sprintf('AUI-%s-%05d', $invDatePart, $invCount);
        }

        $quote = $booking->activeQuotation;
        $effectiveTotal = $booking->effective_total;
        $totalPaid = $booking->total_paid;
        $balanceDue = max(0, $effectiveTotal - $totalPaid);

        $invoice = Invoice::create([
            'invoice_number' => $invNum,
            'booking_id' => $booking->id,
            'quotation_id' => $quote ? $quote->id : null,
            'user_id' => $booking->user_id,
            'invoice_type' => $balanceDue <= 0 ? 'final' : ($totalPaid > 0 ? 'advance' : 'receipt'),
            'subtotal' => $quote ? $quote->subtotal : $booking->base_amount,
            'discount' => $quote ? $quote->discount_amount : 0,
            'tax' => $quote ? $quote->tax_amount : 0,
            'total' => $effectiveTotal,
            'amount_paid' => $totalPaid,
            'balance_due' => $balanceDue,
            'status' => $balanceDue <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'issued'),
            'issued_at' => now(),
            'due_at' => Carbon::parse($booking->event_date),
            'notes' => "Invoice generated for booking #{$booking->booking_reference}.",
        ]);

        AdminActivityLog::log(
            'Generated Invoice',
            'Invoice',
            $invoice->id,
            "Generated invoice {$invoice->invoice_number} for booking #{$booking->booking_reference}"
        );

        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', "Invoice {$invoice->invoice_number} generated successfully.");
    }

    public function show($id)
    {
        $invoice = Invoice::with(['booking.decoration.category', 'booking.addons.addon', 'customer', 'quotation.items'])->findOrFail($id);
        
        $settings = [
            'business_name' => SiteSetting::get('business_name', 'Aditya Utsav Wedding & Event Decorators'),
            'business_phone' => SiteSetting::get('business_phone', '+91 98765 43210'),
            'business_email' => SiteSetting::get('business_email', 'contact@adityautsav.in'),
            'business_address' => SiteSetting::get('business_address', 'Main Road, Station Chowk, Siwan, Bihar - 841226'),
            'gst_number' => SiteSetting::get('gst_number', '10AAACU1234F1Z5'),
        ];

        return view('admin.invoices.show', compact('invoice', 'settings'));
    }
}
