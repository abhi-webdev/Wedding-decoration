@extends('layouts.admin')

@section('title', 'Payment Receipt ' . $payment->payment_reference)
@section('header', 'Payment: ' . $payment->payment_reference)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Payments</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs transition shadow flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Receipt</span>
            </button>
        </div>
    </div>

    <!-- Payment Voucher / Receipt Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="font-heading text-lg font-bold text-amber-800 tracking-wide">ADITYA UTSAV</span>
                <p class="text-xs text-slate-500">Official Money Receipt & Payment Voucher</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border bg-emerald-100 text-emerald-800 border-emerald-300">
                    Payment Verified ({{ $payment->status }})
                </span>
                <p class="text-xs font-mono font-bold text-slate-900 mt-2">{{ $payment->payment_reference }}</p>
                <p class="text-[11px] text-slate-400">Date: {{ $payment->payment_date ? $payment->payment_date->format('d F Y, h:i A') : $payment->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Received From:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $payment->customer->name ?? $payment->booking->customer_name }}</p>
                <p class="text-slate-600">{{ $payment->customer->phone ?? $payment->booking->customer_phone }}</p>
                <p class="text-slate-500">{{ $payment->customer->email ?? $payment->booking->customer_email }}</p>
            </div>
            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Linked Booking:</span>
                <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="font-bold text-amber-700 hover:underline font-mono text-sm">
                    #{{ $payment->booking->booking_reference ?? $payment->booking_id }}
                </a>
                <p class="text-slate-600 font-semibold">{{ $payment->booking->decoration->name ?? 'Theme' }}</p>
                <p class="text-slate-500">{{ $payment->booking ? $payment->booking->formatted_event_date : 'N/A' }} ({{ $payment->booking->city ?? 'Bihar' }})</p>
            </div>
        </div>

        <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-6 text-center space-y-1">
            <span class="text-xs uppercase font-bold text-amber-900 tracking-wider">Amount Received</span>
            <p class="text-3xl font-extrabold text-amber-900 font-mono">{{ $payment->formatted_amount }}</p>
            <p class="text-xs font-semibold text-amber-800 capitalize mt-1">{{ $payment->payment_type }} Payment via {{ $payment->payment_method_label }}</p>
            @if($payment->transaction_reference)
                <p class="text-[11px] text-slate-500 font-mono mt-0.5">Bank/UPI Ref: {{ $payment->transaction_reference }}</p>
            @endif
        </div>

        @if($payment->notes)
            <div class="text-xs">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Transaction Notes</span>
                <p class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-slate-700">{{ $payment->notes }}</p>
            </div>
        @endif

        <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
            <span>Recorded by: <strong>{{ $payment->recordedByUser->name ?? 'Accounts Desk' }}</strong></span>
            <span class="font-mono text-[10px]">Aditya Utsav Bihar & UP Operations</span>
        </div>
    </div>
</div>
@endsection
