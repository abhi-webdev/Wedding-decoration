@extends('layouts.account')

@section('title', 'Payment History & Receipts - Aditya Utsav')

@section('account_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold font-serif text-stone-900">Payment History</h1>
            <p class="text-stone-600 text-sm mt-1">Track all your advance deposits and installment payments for Aditya Utsav wedding decoration bookings.</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-right">
            <span class="text-xs text-amber-800 uppercase font-semibold block tracking-wider">Total Paid by You</span>
            <span class="text-xl font-bold text-amber-900">₹{{ number_format($payments->where('status', 'successful')->sum('amount'), 2) }}</span>
        </div>
    </div>

    <!-- Payment Account / Instructions Card -->
    <div class="bg-gradient-to-br from-stone-900 to-stone-800 rounded-2xl p-6 text-white shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-amber-500/20 text-amber-300 rounded-lg text-xs font-semibold uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Official Payment Instructions
                </div>
                <h2 class="text-lg font-bold">Making a Payment for an Approved Quotation</h2>
                <p class="text-stone-300 text-xs max-w-xl">
                    For advance booking deposits or remaining stage balances, transfer directly via official UPI or Bank NEFT/RTGS. Once transferred, notify our team with the Transaction ID or UTR for immediate verification & instant receipt issuance.
                </p>
            </div>
            <div class="bg-stone-800/80 border border-stone-700 p-4 rounded-xl text-xs space-y-2 min-w-[240px]">
                <div class="text-amber-400 font-semibold uppercase tracking-wider text-[10px]">Direct UPI ID</div>
                <div class="font-mono text-sm font-bold select-all text-white bg-stone-900/90 px-2 py-1 rounded">adityautsav@upi</div>
                <div class="text-stone-400 text-[11px] pt-1">Helpline / WhatsApp: +91 94310 00000</div>
            </div>
        </div>
    </div>

    <!-- Payments List -->
    <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
        <div class="p-6 border-b border-stone-200 flex justify-between items-center">
            <h2 class="font-bold text-stone-900 text-lg">Transactions & Receipts</h2>
            <span class="text-xs text-stone-500">{{ $payments->total() }} Record(s)</span>
        </div>

        @if($payments->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-stone-900 mb-1">No payments recorded yet</h3>
                <p class="text-stone-500 text-xs max-w-md mx-auto mb-4">Once you accept a quotation and make an advance deposit, your verified payment receipts will appear here.</p>
                <a href="{{ route('account.quotations.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    View My Quotations
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50/75 text-stone-600 text-xs uppercase tracking-wider">
                            <th class="py-3 px-6 font-semibold">Payment Ref</th>
                            <th class="py-3 px-6 font-semibold">Booking / Event</th>
                            <th class="py-3 px-6 font-semibold">Payment Date</th>
                            <th class="py-3 px-6 font-semibold">Method & Note</th>
                            <th class="py-3 px-6 font-semibold">Amount</th>
                            <th class="py-3 px-6 font-semibold">Status</th>
                            <th class="py-3 px-6 font-semibold text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="py-4 px-6 font-mono font-bold text-stone-900 text-xs">
                                    {{ $payment->payment_reference }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-stone-800 text-xs">
                                        {{ $payment->booking ? $payment->booking->booking_reference : 'Direct Payment' }}
                                    </div>
                                    <div class="text-[11px] text-stone-500 font-medium">
                                        {{ $payment->booking ? $payment->booking->booked_item_name : 'Aditya Utsav Wedding Service' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-stone-600">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M, Y') : $payment->created_at->format('d M, Y') }}
                                </td>
                                <td class="py-4 px-6 text-xs">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-stone-100 text-stone-800 font-medium text-[11px]">
                                        {{ $payment->payment_method_label }}
                                    </span>
                                    @if($payment->transaction_id)
                                        <div class="text-[10px] text-stone-500 font-mono mt-0.5">TXN: {{ $payment->transaction_id }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-bold text-stone-900 text-sm">
                                    {{ $payment->formatted_amount }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($payment->status === 'successful')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            ✓ Verified & Paid
                                        </span>
                                    @elseif($payment->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            Pending Verification
                                        </span>
                                    @elseif($payment->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200" title="{{ $payment->rejection_reason }}">
                                            ✕ Verification Rejected
                                        </span>
                                        @if($payment->rejection_reason)
                                            <div class="text-[10px] text-red-600 mt-1 max-w-xs">{{ $payment->rejection_reason }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-800 border border-stone-200">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if($payment->status === 'successful')
                                        <a href="{{ route('account.payments.receipt', $payment) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 rounded-lg text-xs font-semibold transition">
                                            <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Receipt</span>
                                        </a>
                                    @elseif($payment->status === 'pending')
                                        <span class="text-xs text-stone-400 italic">Under Review</span>
                                    @else
                                        <span class="text-xs text-stone-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="p-4 border-t border-stone-100">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
