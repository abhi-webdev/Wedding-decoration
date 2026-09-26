@extends('layouts.admin')

@section('title', 'Payment Transactions')
@section('header', 'Payment Tracking & Transactions')

@section('content')
<div class="space-y-6">
    <!-- Top Metrics Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Collections</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($metrics['total_received']) }}</p>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">{{ $metrics['total_transactions'] }} total transactions</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Advance Received</span>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">₹{{ number_format($metrics['advance_received']) }}</p>
            <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">Booking lock-in advances</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Balance Settled</span>
            <p class="text-2xl font-extrabold text-purple-700 mt-1">₹{{ number_format($metrics['balance_received']) }}</p>
            <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">Post-event collections</span>
        </div>

        <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Record New Payment</span>
                <p class="text-xs text-slate-300 mt-1">Record manual UPI, cash, or bank transfers</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="mt-3 w-full py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-center rounded-xl text-xs transition shadow">
                + Record Transaction
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="w-full flex flex-col sm:flex-row gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment ref, transaction ID, client name/phone..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="payment_type" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Payment Types</option>
                <option value="advance" {{ request('payment_type') === 'advance' ? 'selected' : '' }}>Advance</option>
                <option value="balance" {{ request('payment_type') === 'balance' ? 'selected' : '' }}>Balance</option>
                <option value="full" {{ request('payment_type') === 'full' ? 'selected' : '' }}>Full Payment</option>
                <option value="refund" {{ request('payment_type') === 'refund' ? 'selected' : '' }}>Refund</option>
            </select>

            <select name="payment_method" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Methods</option>
                <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Payment Ref</th>
                        <th class="py-3.5 px-4">Booking & Client</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Type & Method</th>
                        <th class="py-3.5 px-4">Payment Date</th>
                        <th class="py-3.5 px-4">Recorded By</th>
                        <th class="py-3.5 px-4 text-right">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.payments.show', $pay->id) }}" class="font-bold text-amber-700 hover:underline font-mono block">
                                    {{ $pay->payment_reference }}
                                </a>
                                @if($pay->transaction_reference)
                                    <span class="text-[10px] text-slate-400 font-mono block">Txn: {{ $pay->transaction_reference }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $pay->customer->name ?? $pay->booking->customer_name }}</span>
                                    <a href="{{ route('admin.bookings.show', $pay->booking_id) }}" class="text-[10px] font-mono text-amber-600 hover:underline">
                                        Booking #{{ $pay->booking->booking_reference ?? $pay->booking_id }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                {{ $pay->formatted_amount }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pay->payment_type === 'advance' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $pay->payment_type }}
                                </span>
                                <span class="text-[10px] text-slate-500 block mt-0.5 capitalize">{{ $pay->payment_method_label }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">
                                {{ $pay->payment_date ? $pay->payment_date->format('d M Y, h:i A') : $pay->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                {{ $pay->recordedByUser->name ?? 'System Admin' }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.payments.show', $pay->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 font-bold text-xs text-slate-700 transition">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No payment transactions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
