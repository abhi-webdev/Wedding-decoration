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
                   ($booking->status === 'accepted' ? 'bg-blue-100 text-blue-800' :
                   ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                   ($booking->status === 'in_progress' ? 'bg-purple-100 text-purple-800' :
                   ($booking->status === 'completed' ? 'bg-teal-100 text-teal-800' :
                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800'))))) }}">
                ● {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
            </span>

            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $booking->payment_status_badge_classes }}">
                {{ $booking->payment_status_label }}
            </span>
        </div>

        <!-- Quick Business Operation Actions -->
        <div class="flex flex-wrap items-center gap-2">
            @if($booking->status === 'pending')
                <!-- Accept Booking Button -->
                <form action="{{ route('admin.bookings.accept', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Accept this booking request? The customer will receive an email notification.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        ACCEPT BOOKING
                    </button>
                </form>

                <!-- Reject Booking Button (Opens modal) -->
                <button type="button" onclick="document.getElementById('reject-booking-modal').classList.remove('hidden')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    REJECT BOOKING
                </button>
            @endif

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
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Total Estimated Price</span>
            <span class="text-lg font-bold text-slate-900 mt-1 block">₹{{ number_format($booking->effective_total, 2) }}</span>
            <span class="text-[11px] text-slate-400">Base Price + Selected Add-ons</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Verified Paid</span>
            <span class="text-lg font-bold text-emerald-700 mt-1 block">₹{{ number_format($booking->total_paid, 2) }}</span>
            <span class="text-[11px] text-emerald-600 font-medium">
                {{ $booking->payments->whereIn('status', ['paid', 'accepted', 'successful'])->count() }} verified transaction(s)
            </span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Pending Verification</span>
            <span class="text-lg font-bold text-amber-700 mt-1 block">₹{{ number_format($booking->pending_payment_amount, 2) }}</span>
            <span class="text-[11px] text-amber-600">
                {{ $booking->payments->where('status', 'pending')->count() }} payment request(s)
            </span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider block">Remaining Balance</span>
            <span class="text-lg font-bold {{ $booking->balance_due > 0 ? 'text-rose-700' : 'text-emerald-700' }} mt-1 block">₹{{ number_format($booking->balance_due, 2) }}</span>
            <span class="text-[11px] text-slate-400">{{ $booking->balance_due <= 0 ? 'Fully Paid' : 'Outstanding due' }}</span>
        </div>
    </div>

    <!-- Main 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Details, Venue, Payments, Quotations, Add-ons -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Event & Theme/Package Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span>Booked Event & Ceremony Details</span>
                    <span class="text-xs text-slate-400 font-normal">Booked on {{ $booking->created_at->format('d M Y, h:i A') }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Booked Item</span>
                        <p class="text-sm font-bold text-slate-800 mt-0.5">
                            {{ $booking->booked_item_name }}
                        </p>
                        <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold uppercase mt-1 inline-block">
                            {{ $booking->booked_item_type_label }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Event Date & Time</span>
                        <p class="text-sm font-bold text-amber-800 mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->event_date)->format('l, d F Y') }}
                        </p>
                        <span class="text-slate-600 font-medium">{{ $booking->formatted_time_range }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Ceremony / Event Type</span>
                        <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $booking->event_type ?? 'Wedding / Reception' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Guest Count</span>
                        <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $booking->guest_count ?? 'N/A' }} Guests</p>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-slate-400 uppercase font-semibold block text-[10px]">Location & Venue Address</span>
                        <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $booking->venue_name ?? 'Client Venue' }}</p>
                        <p class="text-xs text-slate-600">{{ $booking->address_line ?? $booking->venue_address ?? $booking->event_address }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $booking->locality ? $booking->locality . ', ' : '' }}{{ $booking->city ?? 'Siwan' }}, {{ $booking->state ?? 'Bihar' }} {{ $booking->pincode ? '— ' . $booking->pincode : '' }}
                        </p>
                    </div>

                    @if($booking->special_requirements || $booking->special_instructions || $booking->notes)
                        <div class="sm:col-span-2 bg-amber-50/60 border border-amber-200/60 p-3.5 rounded-xl">
                            <span class="text-amber-800 font-bold block text-xs mb-1">Special Requirements & Preferences:</span>
                            <p class="text-xs text-slate-700 whitespace-pre-line">{{ $booking->special_requirements ?? $booking->special_instructions ?? $booking->notes }}</p>
                        </div>
                    @endif
                </div>
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
                                        @if($payment->status === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                                Pending Verification
                                            </span>
                                        @elseif(in_array($payment->status, ['paid', 'accepted', 'successful']))
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                                Verified & Accepted
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-red-100 text-red-800">
                                                {{ $payment->status }}
                                            </span>
                                        @endif
                                        <span class="text-slate-500 font-medium">({{ $payment->payment_method_label }})</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        Amount: <strong class="text-emerald-700 text-xs">₹{{ number_format($payment->amount, 2) }}</strong> | Date: {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : $payment->created_at->format('d M Y') }}
                                        @if($payment->transaction_reference)
                                            | Ref: <span class="font-mono">{{ $payment->transaction_reference }}</span>
                                        @endif
                                        @if($payment->receipt_number)
                                            | Receipt: <span class="font-mono font-bold text-slate-800">{{ $payment->receipt_number }}</span>
                                        @endif
                                    </div>
                                    @if($payment->rejection_reason)
                                        <div class="text-[11px] text-rose-700 mt-1">
                                            <strong>Rejection Reason:</strong> {{ $payment->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.accept', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Verify and ACCEPT this payment of ₹{{ number_format($payment->amount, 2) }}? A formal receipt will be generated.');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition shadow-sm">
                                                Verify & Accept
                                            </button>
                                        </form>
                                        <button type="button" onclick="openPaymentRejectModal('{{ $payment->id }}', '{{ $payment->payment_reference }}', '{{ number_format($payment->amount, 2) }}')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg font-bold text-xs transition">
                                            Reject
                                        </button>
                                    @endif

                                    @if(in_array($payment->status, ['paid', 'accepted', 'successful']))
                                        <a href="{{ route('admin.payments.receipt', $payment->id) }}" target="_blank" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-semibold transition inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            View Receipt
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic py-2">No payment transactions recorded for this booking yet.</p>
                @endif
            </div>
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
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Financial Breakdown</h3>
                
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Base Item Price</span>
                        <span class="font-semibold text-slate-800">₹{{ number_format($booking->base_price) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Add-ons Total</span>
                        <span class="font-semibold text-slate-800">₹{{ number_format($booking->addons_total ?? 0) }}</span>
                    </div>
                    @if($booking->travel_charge > 0)
                        <div class="flex justify-between text-slate-600">
                            <span>Travel / Logistics</span>
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
                        <span>Estimated Total</span>
                        <span class="text-base text-amber-700">₹{{ number_format($booking->effective_total) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-emerald-700 font-bold pt-1">
                        <span>Verified Paid Amount:</span>
                        <span>₹{{ number_format($booking->total_paid) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-rose-700 font-bold">
                        <span>Remaining Balance:</span>
                        <span>₹{{ number_format($booking->balance_due) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100">Customer Profile & Contact</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Full Name</span>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $booking->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Phone / WhatsApp</span>
                        <p class="font-semibold text-slate-900 mt-0.5">{{ $booking->customer_phone }}</p>
                        @if($booking->customer_whatsapp && $booking->customer_whatsapp !== $booking->customer_phone)
                            <p class="text-[11px] text-slate-500">WA: {{ $booking->customer_whatsapp }}</p>
                        @endif
                    </div>
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Email Address</span>
                        <p class="font-medium text-slate-700 mt-0.5 break-all">{{ $booking->customer_email }}</p>
                    </div>
                    
                    @php
                        $targetPhone = $booking->customer_whatsapp ?: $booking->customer_phone;
                        $cleanPhone = preg_replace('/[^0-9]/', '', $targetPhone);
                        if (strlen($cleanPhone) === 10) {
                            $cleanPhone = '91' . $cleanPhone;
                        }
                        $waMsg = urlencode("Namaste {$booking->customer_name}, regards from Aditya Utsav regarding your booking #{$booking->booking_reference} for {$booking->booked_item_name} on " . \Carbon\Carbon::parse($booking->event_date)->format('d M Y') . ".");
                    @endphp

                    <!-- Direct Communication Buttons -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="w-full py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold rounded-xl border border-emerald-200 transition flex items-center justify-center gap-2 text-xs">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-5.805 1.554zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp Customer</span>
                        </a>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="tel:{{ $booking->customer_phone }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold rounded-xl text-center text-xs transition">
                                Call Phone
                            </a>
                            <a href="mailto:{{ $booking->customer_email }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold rounded-xl text-center text-xs transition">
                                Send Email
                            </a>
                        </div>
                    </div>

                    @if($booking->user_id)
                        <div class="pt-2">
                            <a href="{{ route('admin.customers.show', $booking->user_id) }}" class="inline-block px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                View Customer Profile &rarr;
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
                            <option value="accepted" {{ $booking->status === 'accepted' ? 'selected' : '' }}>Accepted (Booking Verified)</option>
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

<!-- Modal 1: Reject Booking Request Modal -->
<div id="reject-booking-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-rose-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Reject Booking Request
            </h3>
            <button type="button" onclick="document.getElementById('reject-booking-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <p class="text-xs text-slate-600">
            Please enter a reason for rejecting booking <strong>#{{ $booking->booking_reference }}</strong>. This explanation will be logged and emailed to the customer.
        </p>

        <form action="{{ route('admin.bookings.reject', $booking->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="rejection_reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Rejection Reason <span class="text-rose-500">*</span>
                </label>
                <textarea id="rejection_reason" name="rejection_reason" required rows="3" placeholder="e.g., Unavailable due to overlapping booked weddings on this date in Siwan..." class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('reject-booking-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Reject Payment Modal -->
<div id="reject-payment-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-rose-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Reject Payment Request
            </h3>
            <button type="button" onclick="document.getElementById('reject-payment-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <p class="text-xs text-slate-600" id="reject-payment-modal-text">
            Please enter a reason for rejecting this payment request.
        </p>

        <form id="reject-payment-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="payment_rejection_reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Rejection Reason <span class="text-rose-500">*</span>
                </label>
                <textarea id="payment_rejection_reason" name="rejection_reason" required rows="3" placeholder="e.g., Transaction ID does not match our bank/UPI statement..." class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('reject-payment-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentRejectModal(paymentId, paymentRef, amount) {
    var form = document.getElementById('reject-payment-form');
    form.action = '/admin/payments/' + paymentId + '/reject';
    document.getElementById('reject-payment-modal-text').innerText = 'Enter rejection reason for Payment #' + paymentRef + ' of ₹' + amount + ':';
    document.getElementById('reject-payment-modal').classList.remove('hidden');
}
</script>
@endsection
