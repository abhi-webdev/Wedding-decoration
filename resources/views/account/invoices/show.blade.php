@extends('layouts.account')

@section('title', 'Invoice ' . $invoice->invoice_number . ' - Aditya Utsav')

@section('account_content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar (hidden when printing) -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-stone-200 print:hidden">
        <a href="{{ route('account.invoices.index') }}" class="inline-flex items-center gap-2 text-stone-600 hover:text-stone-900 text-sm font-semibold transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Invoices
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Download Invoice
            </button>
        </div>
    </div>

    <!-- Printable Invoice Sheet -->
    <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-stone-200 printable-area text-stone-800 font-sans">
        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-8 border-b-2 border-amber-900/10 gap-6">
            <div>
                <div class="text-2xl sm:text-3xl font-bold font-serif text-amber-950 tracking-tight">
                    ADITYA UTSAV
                </div>
                <p class="text-xs uppercase tracking-widest text-amber-700 font-bold mt-0.5">Bihar Wedding Decoration & Event Services</p>
                <div class="text-xs text-stone-500 mt-2 space-y-0.5">
                    <p>{{ $settings['business_address'] }}</p>
                    <p>Phone: {{ $settings['business_phone'] }} | Email: {{ $settings['business_email'] }}</p>
                    <p class="font-mono font-semibold text-stone-700">GSTIN: {{ $settings['gst_number'] }}</p>
                </div>
            </div>
            <div class="sm:text-right">
                <div class="inline-block px-3 py-1 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg text-xs font-bold uppercase tracking-wider mb-2">
                    TAX INVOICE
                </div>
                <div class="text-lg sm:text-xl font-bold font-mono text-stone-900">{{ $invoice->invoice_number }}</div>
                <p class="text-xs text-stone-500 mt-1">Date of Issue: <span class="font-semibold text-stone-700">{{ $invoice->issued_at ? $invoice->issued_at->format('d M, Y') : $invoice->created_at->format('d M, Y') }}</span></p>
                @if($invoice->due_date)
                    <p class="text-xs text-stone-500">Due Date: <span class="font-semibold text-stone-700">{{ $invoice->due_date->format('d M, Y') }}</span></p>
                @endif
            </div>
        </div>

        <!-- Bill To & Event Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-6 border-b border-stone-100 text-xs">
            <div>
                <h3 class="text-stone-400 font-bold uppercase tracking-wider text-[10px] mb-2">Billed To</h3>
                <p class="text-base font-bold text-stone-900">{{ $invoice->customer ? $invoice->customer->name : ($invoice->booking ? $invoice->booking->customer_name : 'Customer') }}</p>
                <p class="text-stone-600 mt-0.5">{{ $invoice->customer ? $invoice->customer->email : ($invoice->booking ? $invoice->booking->customer_email : '') }}</p>
                <p class="text-stone-600">{{ $invoice->customer ? $invoice->customer->phone : ($invoice->booking ? $invoice->booking->customer_phone : '') }}</p>
                @if($invoice->booking && $invoice->booking->event_address)
                    <p class="text-stone-500 mt-1.5 leading-relaxed"><span class="font-semibold">Event Venue:</span> {{ $invoice->booking->event_address }}, {{ $invoice->booking->event_city }} ({{ $invoice->booking->event_pincode }})</p>
                @endif
            </div>
            <div class="sm:text-right space-y-1.5">
                <h3 class="text-stone-400 font-bold uppercase tracking-wider text-[10px] mb-2 sm:text-right">Booking & Reference</h3>
                @if($invoice->booking)
                    <p class="text-stone-700"><span class="text-stone-400">Booking Ref:</span> <span class="font-mono font-bold text-stone-900">{{ $invoice->booking->booking_reference }}</span></p>
                    <p class="text-stone-700"><span class="text-stone-400">Event Date:</span> <span class="font-semibold text-stone-800">{{ $invoice->booking->event_date ? $invoice->booking->event_date->format('d M, Y') : 'N/A' }}</span></p>
                    <p class="text-stone-700"><span class="text-stone-400">Occasion:</span> {{ ucfirst($invoice->booking->event_type ?? 'Wedding') }}</p>
                @endif
                @if($invoice->quotation)
                    <p class="text-stone-700"><span class="text-stone-400">Quotation Ref:</span> <span class="font-mono text-stone-800">{{ $invoice->quotation->quotation_number }}</span></p>
                @endif
                <div class="pt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($invoice->status === 'partially_paid' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                        Invoice Status: {{ strtoupper(str_replace('_', ' ', $invoice->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Invoice Breakdown Table -->
        <div class="py-6">
            <h3 class="text-stone-400 font-bold uppercase tracking-wider text-[10px] mb-3">Service & Cost Breakdown</h3>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b-2 border-stone-200 bg-stone-50/75 text-stone-700 font-semibold uppercase tracking-wider">
                        <th class="py-2.5 px-4">Item & Description</th>
                        <th class="py-2.5 px-4 text-center w-16">Qty</th>
                        <th class="py-2.5 px-4 text-right w-28">Rate (₹)</th>
                        <th class="py-2.5 px-4 text-right w-28">Total (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @if($invoice->quotation && $invoice->quotation->items->isNotEmpty())
                        @foreach($invoice->quotation->items as $item)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-stone-900">{{ $item->item_name }}</div>
                                    @if($item->description)
                                        <div class="text-[11px] text-stone-500">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center text-stone-700">{{ $item->quantity }}</td>
                                <td class="py-3 px-4 text-right font-mono text-stone-700">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-stone-900">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    @elseif($invoice->booking)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-stone-900">{{ $invoice->booking->decoration ? $invoice->booking->decoration->name : 'Wedding Decoration Package' }}</div>
                                <div class="text-[11px] text-stone-500">Theme setup, stage decoration, mandap & floral installations</div>
                            </td>
                            <td class="py-3 px-4 text-center text-stone-700">1</td>
                            <td class="py-3 px-4 text-right font-mono text-stone-700">{{ number_format($invoice->booking->base_price, 2) }}</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-stone-900">{{ number_format($invoice->booking->base_price, 2) }}</td>
                        </tr>
                        @if($invoice->booking->addons && $invoice->booking->addons->isNotEmpty())
                            @foreach($invoice->booking->addons as $bAddon)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-stone-900">{{ $bAddon->addon ? $bAddon->addon->name : 'Add-on Service' }}</div>
                                        <div class="text-[11px] text-stone-500">Optional extra wedding accessory service</div>
                                    </td>
                                    <td class="py-3 px-4 text-center text-stone-700">{{ $bAddon->quantity ?? 1 }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-stone-700">{{ number_format($bAddon->price, 2) }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-stone-900">{{ number_format(($bAddon->price * ($bAddon->quantity ?? 1)), 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Totals & Payment Summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start pt-4 border-t-2 border-stone-200 gap-6">
            <div class="w-full sm:w-1/2 space-y-3 text-xs">
                <div class="bg-stone-50 p-4 rounded-xl border border-stone-100">
                    <h4 class="font-bold text-stone-800 uppercase tracking-wider text-[10px] mb-1">Official Bank & UPI Details</h4>
                    <p class="text-stone-600">Bank: <span class="font-semibold text-stone-800">State Bank of India</span></p>
                    <p class="text-stone-600">Account: <span class="font-semibold font-mono text-stone-800">38947289123</span> (IFSC: SBIN0001234)</p>
                    <p class="text-stone-600">UPI ID: <span class="font-semibold font-mono text-stone-800">adityautsav@upi</span></p>
                </div>
                @if($invoice->notes)
                    <p class="text-stone-500 text-[11px] italic leading-relaxed"><strong>Note:</strong> {{ $invoice->notes }}</p>
                @endif
            </div>
            <div class="w-full sm:w-5/12 space-y-2 text-xs">
                <div class="flex justify-between text-stone-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-semibold">{{ $invoice->formatted_subtotal }}</span>
                </div>
                @if($invoice->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Discount:</span>
                        <span class="font-mono font-semibold">- {{ $invoice->formatted_discount_amount }}</span>
                    </div>
                @endif
                @if($invoice->tax_amount > 0)
                    <div class="flex justify-between text-stone-600">
                        <span>GST / Service Tax:</span>
                        <span class="font-mono font-semibold">{{ $invoice->formatted_tax_amount }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm font-bold text-stone-900 pt-2 border-t border-stone-200">
                    <span>Grand Total:</span>
                    <span class="font-mono text-base">{{ $invoice->formatted_grand_total }}</span>
                </div>
                <div class="flex justify-between text-emerald-800 font-semibold pt-1">
                    <span>Amount Paid:</span>
                    <span class="font-mono font-bold">{{ $invoice->formatted_paid_amount }}</span>
                </div>
                <div class="flex justify-between text-xs font-bold pt-2 border-t border-dashed border-stone-200 {{ $invoice->balance_due > 0 ? 'text-amber-900 bg-amber-50 p-2 rounded-lg' : 'text-stone-600' }}">
                    <span>Balance Due:</span>
                    <span class="font-mono text-sm">{{ $invoice->formatted_balance_due }}</span>
                </div>
            </div>
        </div>

        <!-- Footer / Signature -->
        <div class="mt-12 pt-8 border-t border-stone-100 flex flex-col sm:flex-row justify-between items-center text-xs text-stone-500 gap-4">
            <p>Thank you for choosing Aditya Utsav for your auspicious wedding celebrations!</p>
            <div class="text-center sm:text-right">
                <div class="w-36 border-b border-stone-300 pb-1 mx-auto sm:ml-auto"></div>
                <p class="mt-1 font-semibold text-stone-700">Authorized Signatory</p>
                <p class="text-[10px] text-stone-400">Aditya Utsav Decorators</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body {
        background: #ffffff !important;
        font-size: 12pt;
    }
    nav, footer, .print\:hidden, header, aside {
        display: none !important;
    }
    .printable-area {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
}
</style>
@endsection
