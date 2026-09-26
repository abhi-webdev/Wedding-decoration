@extends('layouts.admin')

@section('title', 'Billing & Invoices')
@section('header', 'Invoices & Client Billing')

@section('content')
<div class="space-y-6">
    <!-- Top Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Invoiced</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($metrics['total_invoiced']) }}</p>
            <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">All issued billing invoices</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Collected</span>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">₹{{ number_format($metrics['total_collected']) }}</p>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">Paid & cleared billing</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Outstanding Balance</span>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">₹{{ number_format($metrics['balance_due']) }}</p>
            <span class="text-[11px] text-amber-700 font-medium mt-0.5 block">Due on event execution</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.invoices.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice number, booking ref, client name/phone..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Fully Paid</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partially Paid</option>
                <option value="issued" {{ request('status') === 'issued' ? 'selected' : '' }}>Issued (Unpaid)</option>
            </select>

            <select name="invoice_type" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Invoice Types</option>
                <option value="advance" {{ request('invoice_type') === 'advance' ? 'selected' : '' }}>Advance Invoice</option>
                <option value="final" {{ request('invoice_type') === 'final' ? 'selected' : '' }}>Final Tax Invoice</option>
                <option value="receipt" {{ request('invoice_type') === 'receipt' ? 'selected' : '' }}>Receipt</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Invoice #</th>
                        <th class="py-3.5 px-4">Client & Booking</th>
                        <th class="py-3.5 px-4">Total Amount</th>
                        <th class="py-3.5 px-4">Paid</th>
                        <th class="py-3.5 px-4">Balance Due</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" class="font-bold text-amber-700 hover:underline font-mono block">
                                    {{ $inv->invoice_number }}
                                </a>
                                <span class="text-[10px] text-slate-400 capitalize">{{ $inv->invoice_type }} Invoice</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $inv->customer->name ?? $inv->booking->customer_name }}</span>
                                    <a href="{{ route('admin.bookings.show', $inv->booking_id) }}" class="text-[10px] font-mono text-amber-600 hover:underline">
                                        Booking #{{ $inv->booking->booking_reference ?? $inv->booking_id }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                {{ $inv->formatted_total }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-emerald-700 font-semibold">
                                {{ $inv->formatted_paid }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-semibold {{ $inv->balance_due > 0 ? 'text-amber-700' : 'text-slate-400' }}">
                                {{ $inv->formatted_balance }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $inv->status_badge_classes }}">
                                    {{ $inv->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs transition">
                                    View & Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No invoices generated yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
