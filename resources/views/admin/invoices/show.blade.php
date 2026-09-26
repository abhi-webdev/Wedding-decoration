<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }} | Aditya Utsav</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Cinzel', serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { border: none !important; box-shadow: none !important; width: 100% !important; max-width: 100% !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 px-4 text-slate-800 antialiased">

<div class="max-w-4xl mx-auto space-y-4">
    <!-- Action Bar (Hidden in Print) -->
    <div class="no-print bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <a href="{{ auth()->user() && auth()->user()->isAdminUser() ? route('admin.invoices.index') : route('account.invoices.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Invoices</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    <!-- Official Printable Invoice Card -->
    <div class="print-card bg-white rounded-2xl border border-slate-200 shadow-xl p-8 sm:p-12 space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-amber-500/30 gap-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-amber-600 to-amber-800 flex items-center justify-center text-white font-bold text-sm shadow">
                        AU
                    </div>
                    <span class="font-heading text-xl font-bold text-amber-800 tracking-wide">{{ $settings['business_name'] }}</span>
                </div>
                <p class="text-xs text-slate-600 max-w-sm">{{ $settings['business_address'] }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Phone: <strong>{{ $settings['business_phone'] }}</strong> • Email: <strong>{{ $settings['business_email'] }}</strong></p>
                @if(!empty($settings['gst_number']))
                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">GSTIN: {{ $settings['gst_number'] }}</p>
                @endif
            </div>

            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border bg-amber-50 text-amber-900 border-amber-200 inline-block mb-1">
                    {{ strtoupper($invoice->invoice_type) }} INVOICE
                </span>
                <p class="text-base font-mono font-extrabold text-slate-900">{{ $invoice->invoice_number }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Date: <strong>{{ $invoice->issued_at->format('d F Y') }}</strong></p>
                @if($invoice->due_at)
                    <p class="text-xs text-slate-500">Event Date: <strong>{{ $invoice->due_at->format('d F Y') }}</strong></p>
                @endif
            </div>
        </div>

        <!-- Billed To & Event Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs bg-slate-50 p-5 rounded-xl border border-slate-100">
            <div>
                <span class="text-[10px] uppercase font-bold text-amber-800 tracking-wider block mb-1">Billed To (Customer):</span>
                <p class="font-bold text-slate-900 text-sm">{{ $invoice->customer->name ?? $invoice->booking->customer_name }}</p>
                <p class="text-slate-700">{{ $invoice->customer->phone ?? $invoice->booking->customer_phone }}</p>
                <p class="text-slate-500">{{ $invoice->customer->email ?? $invoice->booking->customer_email }}</p>
                <p class="text-slate-500 mt-1">{{ $invoice->booking->address_line ?? '' }} {{ $invoice->booking->city ?? 'Bihar' }}</p>
            </div>

            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-bold text-amber-800 tracking-wider block mb-1">Event Reference:</span>
                <p class="font-bold text-slate-900 text-sm">Booking #{{ $invoice->booking->booking_reference ?? $invoice->booking_id }}</p>
                <p class="text-slate-700 font-semibold">{{ $invoice->booking->decoration->name ?? 'Wedding Setup' }}</p>
                <p class="text-slate-500">{{ $invoice->booking ? $invoice->booking->formatted_event_date : 'N/A' }} • {{ $invoice->booking->event_type ?? 'Wedding' }}</p>
                <p class="text-slate-500">{{ $invoice->booking->city ?? 'Bihar' }}, {{ $invoice->booking->state ?? 'Bihar' }}</p>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/90 uppercase font-semibold text-slate-700 text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Service Description</th>
                        <th class="py-3 px-3 text-center">Qty</th>
                        <th class="py-3 px-3 text-right">Price</th>
                        <th class="py-3 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($invoice->quotation && $invoice->quotation->items->count() > 0)
                        @foreach($invoice->quotation->items as $idx => $item)
                            <tr>
                                <td class="py-3 px-3 text-slate-400">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-900">{{ $item->description }}</td>
                                <td class="py-3 px-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 px-3 text-right font-mono">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 px-3 text-right font-bold font-mono">₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="py-3 px-3 text-slate-400">1</td>
                            <td class="py-3 px-3 font-semibold text-slate-900">
                                {{ $invoice->booking->decoration->name ?? 'Wedding Decoration Setup' }}
                                <span class="text-[10px] text-slate-400 block">Stage, Floral Setup, Lighting & Entry Design</span>
                            </td>
                            <td class="py-3 px-3 text-center">1</td>
                            <td class="py-3 px-3 text-right font-mono">₹{{ number_format($invoice->subtotal, 2) }}</td>
                            <td class="py-3 px-3 text-right font-bold font-mono">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
            <div class="space-y-2">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Payment & Bank Terms:</span>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    1. Advance payment locks team date availability.<br>
                    2. Balance settlement required on event date before flower handover.<br>
                    3. For questions regarding this bill, contact <strong>{{ $settings['business_phone'] }}</strong>.
                </p>
                @if($invoice->notes)
                    <div class="mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-slate-600">
                        {{ $invoice->notes }}
                    </div>
                @endif
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal Amount:</span>
                    <span class="font-mono font-semibold">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>

                @if($invoice->discount > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Discount Applied:</span>
                        <span class="font-mono">-₹{{ number_format($invoice->discount, 2) }}</span>
                    </div>
                @endif

                @if($invoice->tax > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>GST / Taxes:</span>
                        <span class="font-mono">+₹{{ number_format($invoice->tax, 2) }}</span>
                    </div>
                @endif

                <div class="pt-2 border-t border-slate-200 flex justify-between items-center text-slate-900">
                    <span class="font-bold text-sm">Total Invoiced:</span>
                    <span class="text-base font-extrabold text-slate-900 font-mono">{{ $invoice->formatted_total }}</span>
                </div>

                <div class="flex justify-between items-center text-emerald-700 font-bold">
                    <span>Amount Paid to Date:</span>
                    <span class="font-mono">{{ $invoice->formatted_paid }}</span>
                </div>

                <div class="p-2.5 bg-amber-100/70 rounded-lg border border-amber-200 flex justify-between items-center text-amber-900 font-extrabold mt-2">
                    <span>Balance Due:</span>
                    <span class="font-mono text-sm">{{ $invoice->formatted_balance }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-6 border-t border-slate-100 text-center space-y-1">
            <p class="font-heading text-xs font-bold text-amber-800 uppercase tracking-wider">Thank you for making Aditya Utsav a part of your auspicious celebrations!</p>
            <p class="text-[10px] text-slate-400">This is a computer-generated invoice and does not require a physical signature.</p>
        </div>
    </div>
</div>

</body>
</html>
