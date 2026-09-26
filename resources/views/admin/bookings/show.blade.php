@extends('layouts.admin')

@section('title', 'Booking #' . $booking->booking_reference)
@section('header', 'Booking Operations: #' . $booking->booking_reference)

@section('content')
<div class="space-y-6">
    <!-- Top Action & Navigation Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition bg-slate-100 hover:bg-slate-200 px-3 py-2 rounded-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Bookings</span>
            </a>

            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                {{ in_array($booking->status, ['confirmed', 'advance_paid', 'scheduled']) ? 'bg-emerald-100 text-emerald-800' :
                   ($booking->status === 'quoted' ? 'bg-blue-100 text-blue-800' :
                   ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                   ($booking->status === 'in_progress' ? 'bg-purple-100 text-purple-800' :
                   ($booking->status === 'completed' ? 'bg-teal-100 text-teal-800' :
                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800'))))) }}">
                ● {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>

        <!-- Quick Business Operation Actions -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.quotations.create', ['booking_id' => $booking->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded-xl text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                + Create Quotation
            </a>

            <a href="{{ route('admin.payments.create', ['booking_id' => $booking->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-300 rounded-xl text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                + Record Payment
            </a>

            <form action="{{ route('admin.invoices.createFromBooking', $booking->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Generate Invoice
                </button>
            </form>
        </div>
    </div>

    <!-- Financial KPI Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Contract Value</span>
            <span class="text-lg font-bold text-slate-900 mt-1 block">₹{{ number_format($booking->effective_total, 2) }}</span>
            <span class="text-[11px] text-slate-400">{{ $booking->activeQuotation ? 'From Approved Quotation' : 'Estimated Base + Add-ons' }}</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Advance Requirement</span>
            @php
                $activeQ = $booking->activeQuotation ?? $booking->latestQuotation;
                $advReq = $activeQ ? $activeQ->advance_amount : ($booking->effective_total * 0.4);
            @endphp
            <span class="text-lg font-bold text-amber-700 mt-1 block">₹{{ number_format($advReq, 2) }}</span>
            <span class="text-[11px] text-slate-400">40% Booking Deposit Target</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Total Received</span>
            <span class="text-lg font-bold text-emerald-700 mt-1 block">₹{{ number_format($booking->total_paid, 2) }}</span>
            <span class="text-[11px] {{ $booking->total_paid >= $advReq ? 'text-emerald-600 font-medium' : 'text-amber-600' }}">
                {{ $booking->total_paid >= $advReq ? '✓ Advance Threshold Met' : 'Pending Advance Payment' }}
            </span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Outstanding Balance</span>
            <span class="text-lg font-bold {{ $booking->balance_due > 0 ? 'text-rose-700' : 'text-emerald-700' }} mt-1 block">₹{{ number_format($booking->balance_due, 2) }}</span>
            <span class="text-[11px] text-slate-400">Due before/on event setup</span>
        </div>
    </div>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Details, Venue, Quotations, Payments, Invoices, Add-ons -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Quotations Hub Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Formal Quotations</h3>
                    </div>
                    <a href="{{ route('admin.quotations.create', ['booking_id' => $booking->id]) }}" class="text-xs font-bold text-amber-700 hover:text-amber-800">
                        + New Quote
                    </a>
                </div>

                @if($booking->quotations && $booking->quotations->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($booking->quotations as $quotation)
                            <div class="p-3.5 rounded-xl border {{ $quotation->status === 'accepted' ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-100 bg-slate-50/50' }} flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-slate-900">{{ $quotation->quotation_number }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $quotation->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' :
                                               ($quotation->status === 'sent' ? 'bg-blue-100 text-blue-800' :
                                               ($quotation->status === 'draft' ? 'bg-slate-200 text-slate-700' :
                                               ($quotation->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-stone-200 text-stone-700'))) }}">
                                            {{ $quotation->status }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        Total: <strong class="text-slate-800">{{ $quotation->formatted_grand_total }}</strong> | Advance Required: <strong class="text-amber-800">{{ $quotation->formatted_advance_amount }} ({{ $quotation->advance_percentage }}%)</strong> | Valid Until: {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.quotations.show', $quotation) }}" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-lg font-semibold transition">
                                        View Details
                                    </a>
                                    @if($quotation->status === 'draft')
                                        <form action="{{ route('admin.quotations.send', $quotation) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-semibold transition">
                                                Send to Client
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">No formal quotation has been generated for this booking yet.</p>
                @endif
            </div>

            <!-- Payments & Receipts Hub Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Payments & Receipts</h3>
                    </div>
                    <a href="{{ route('admin.payments.create', ['booking_id' => $booking->id]) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">
                        + Record Payment
                    </a>
                </div>

                @if($booking->payments && $booking->payments->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($booking->payments as $payment)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-slate-900">{{ $payment->payment_reference }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $payment->status === 'successful' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $payment->status }}
                                        </span>
                                        <span class="text-slate-500 font-medium">({{ $payment->payment_method_label }})</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        Amount: <strong class="text-emerald-700 text-xs">{{ $payment->formatted_amount }}</strong> | Date: {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : $payment->created_at->format('d M Y') }}
                                        @if($payment->transaction_id)
                                            | TXN: <span class="font-mono">{{ $payment->transaction_id }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-lg font-semibold transition inline-block">
                                        Receipt
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">No payment transactions recorded for this booking yet.</p>
                @endif
            </div>

            <!-- Invoices Hub Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Tax Invoices</h3>
                    </div>
                    <form action="{{ route('admin.invoices.createFromBooking', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-blue-700 hover:text-blue-800">
                            + Generate Invoice
                        </button>
                    </form>
                </div>

                @if($booking->invoices && $booking->invoices->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($booking->invoices as $invoice)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-slate-900">{{ $invoice->invoice_number }}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        Total: <strong class="text-slate-800">{{ $invoice->formatted_grand_total }}</strong> | Paid: <strong class="text-emerald-700">{{ $invoice->formatted_paid_amount }}</strong> | Due: <strong class="text-rose-700">{{ $invoice->formatted_balance_due }}</strong>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-semibold transition inline-block">
                                        View & Print
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">No tax invoice generated for this booking yet.</p>
                @endif
            </div>

            <!-- Event & Theme Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span>Wedding & Event Information</span>
                    <span class="text-xs text-slate-400 font-normal">Booked on {{ $booking->created_at->format('d M Y, h:i A') }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Decoration Theme</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">
                            {{ $booking->decoration ? $booking->decoration->name : 'Custom Decoration Request' }}
                        </p>
                        @if($booking->decoration && $booking->decoration->category)
                            <span class="text-slate-500">Category: {{ $booking->decoration->category->name }}</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Event Date & Slot</span>
                        <p class="text-sm font-bold text-amber-800 mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->event_date)->format('l, d F Y') }}
                        </p>
                        <span class="text-slate-600 font-medium capitalize">{{ $booking->event_slot ?? 'Full Day Setup' }}</span>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Venue Location</span>
                        <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $booking->venue_name ?? 'Client Venue' }}</p>
                        <p class="text-xs text-slate-600">{{ $booking->event_address ?? $booking->venue_address }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $booking->event_city ?? $booking->city ?? 'Bihar' }} - {{ $booking->event_pincode ?? $booking->pincode ?? '' }}
                        </p>
                    </div>

                    @if($booking->special_instructions || $booking->notes)
                        <div class="sm:col-span-2 bg-amber-50/60 border border-amber-200/60 p-3.5 rounded-xl">
                            <span class="text-amber-800 font-bold block text-xs mb-1">Customer Special Instructions:</span>
                            <p class="text-xs text-slate-700 whitespace-pre-line">{{ $booking->special_instructions ?? $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Add-ons Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Selected Add-ons</h3>
                @if($booking->addons && $booking->addons->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($booking->addons as $addon)
                            <div class="py-2.5 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">{{ $addon->addon ? $addon->addon->name : 'Add-on' }}</span>
                                    <span class="text-slate-400 block text-[11px]">Qty: {{ $addon->quantity ?? 1 }} &times; ₹{{ number_format($addon->price) }}</span>
                                </div>
                                <span class="font-bold text-slate-900">₹{{ number_format($addon->price * ($addon->quantity ?? 1)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">No additional add-on items selected for this booking.</p>
                @endif
            </div>

            <!-- Status History Timeline -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Booking Status History & Audit Log</h3>
                
                <div class="space-y-4">
                    @forelse($booking->statusHistories as $history)
                        <div class="flex items-start gap-3 text-xs">
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 mt-1 shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 uppercase tracking-wide">
                                        Status: {{ str_replace('_', ' ', $history->to_status ?? $history->status) }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">{{ $history->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                @if($history->changedByUser)
                                    <p class="text-[11px] text-slate-500 mt-0.5">Updated by: {{ $history->changedByUser->name }} ({{ ucwords(str_replace('_', ' ', $history->changedByUser->role)) }})</p>
                                @endif
                                @if($history->note || $history->notes)
                                    <p class="text-slate-600 bg-slate-50 p-2 rounded-lg mt-1 border border-slate-100">
                                        {{ $history->note ?? $history->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No status transition log recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Col: Price Breakdown, Customer Info, Admin Status Updater -->
        <div class="space-y-6">
            <!-- Price Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Price Estimate</h3>
                
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Decoration Base Price</span>
                        <span class="font-semibold text-slate-800">₹{{ number_format($booking->base_price) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Add-ons Total</span>
                        <span class="font-semibold text-slate-800">₹{{ number_format($booking->addons_total ?? 0) }}</span>
                    </div>
                    @if($booking->travel_charge > 0)
                        <div class="flex justify-between text-slate-600">
                            <span>Travel / Surcharge</span>
                            <span class="font-semibold text-slate-800">₹{{ number_format($booking->travel_charge) }}</span>
                        </div>
                    @endif
                    @if($booking->discount_amount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>Discount</span>
                            <span class="font-semibold">-₹{{ number_format($booking->discount_amount) }}</span>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-sm font-bold text-slate-900">
                        <span>Booking Total</span>
                        <span class="text-base text-amber-700">₹{{ number_format($booking->effective_total) }}</span>
                    </div>
                    <p class="text-[10px] text-slate-400 text-center mt-2 italic">Official offline quote/advance payable per contract terms</p>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Customer Profile</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Full Name</span>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Contact Phone</span>
                        <a href="tel:{{ $booking->customer_phone }}" class="font-semibold text-amber-700 hover:underline block mt-0.5">
                            {{ $booking->customer_phone }}
                        </a>
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Email Address</span>
                        <a href="mailto:{{ $booking->customer_email }}" class="font-medium text-slate-700 hover:underline block mt-0.5 break-all">
                            {{ $booking->customer_email }}
                        </a>
                    </div>
                    @if($booking->user_id)
                        <div class="pt-2">
                            <a href="{{ route('admin.customers.show', $booking->user_id) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                View Customer History &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Transition Controller Card -->
            <div class="bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-xl p-6">
                <h3 class="text-base font-bold text-amber-400 mb-4 pb-2 border-b border-slate-800">Update Booking Status</h3>
                
                <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Select Next Status</label>
                        <select name="status" id="status" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending (Under Review)</option>
                            <option value="quoted" {{ $booking->status === 'quoted' ? 'selected' : '' }}>Quoted (Quotation Sent)</option>
                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed (Quote Accepted)</option>
                            <option value="advance_paid" {{ $booking->status === 'advance_paid' ? 'selected' : '' }}>Advance Paid (Deposit Received)</option>
                            <option value="scheduled" {{ $booking->status === 'scheduled' ? 'selected' : '' }}>Scheduled (On Calendar)</option>
                            <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>In Progress (Setup Underway)</option>
                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed (Event Concluded)</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rejected" {{ $booking->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label for="admin_note" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Audit Note / Reason</label>
                        <textarea name="admin_note" id="admin_note" rows="3" placeholder="Enter reason or operational details for this status change..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl p-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 px-4 rounded-xl text-xs transition shadow">
                        Save Status & Log Change
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
