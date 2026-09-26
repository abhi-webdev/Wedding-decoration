<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerInvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $invoices = Invoice::with(['booking.decoration', 'quotation'])
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->paginate(10);

        return view('account.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $invoice = Invoice::with(['booking.decoration.category', 'booking.addons.addon', 'customer', 'quotation.items'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $settings = [
            'business_name' => SiteSetting::get('business_name', 'Aditya Utsav Wedding & Event Decorators'),
            'business_phone' => SiteSetting::get('business_phone', '+91 98765 43210'),
            'business_email' => SiteSetting::get('business_email', 'contact@adityautsav.in'),
            'business_address' => SiteSetting::get('business_address', 'Main Road, Station Chowk, Siwan, Bihar - 841226'),
            'gst_number' => SiteSetting::get('gst_number', '10AAACU1234F1Z5'),
        ];

        return view('account.invoices.show', compact('invoice', 'settings'));
    }
}
