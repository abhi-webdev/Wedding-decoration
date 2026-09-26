@extends('layouts.account')

@section('title', 'My Invoices - Aditya Utsav')

@section('account_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-stone-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold font-serif text-stone-900">Tax Invoices & Receipts</h1>
            <p class="text-stone-600 text-sm mt-1">View, download, and print official invoices issued for your wedding decoration bookings.</p>
        </div>
        <div class="bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 text-right">
            <span class="text-xs text-stone-500 uppercase font-semibold block tracking-wider">Total Invoiced</span>
            <span class="text-xl font-bold text-stone-900">₹{{ number_format($invoices->sum('grand_total'), 2) }}</span>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-stone-200 overflow-hidden">
        <div class="p-6 border-b border-stone-200 flex justify-between items-center">
            <h2 class="font-bold text-stone-900 text-lg">Issued Invoices</h2>
            <span class="text-xs text-stone-500">{{ $invoices->total() }} Invoices</span>
        </div>

        @if($invoices->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-stone-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-stone-900 mb-1">No invoices generated yet</h3>
                <p class="text-stone-500 text-xs max-w-md mx-auto mb-4">Invoices are automatically issued once your wedding booking quotation is approved and advance payment is recorded.</p>
                <a href="{{ route('account.bookings.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    View My Bookings
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50/75 text-stone-600 text-xs uppercase tracking-wider">
                            <th class="py-3 px-6 font-semibold">Invoice No</th>
                            <th class="py-3 px-6 font-semibold">Booking Reference</th>
                            <th class="py-3 px-6 font-semibold">Issue Date</th>
                            <th class="py-3 px-6 font-semibold">Total Amount</th>
                            <th class="py-3 px-6 font-semibold">Paid Amount</th>
                            <th class="py-3 px-6 font-semibold">Balance Due</th>
                            <th class="py-3 px-6 font-semibold">Status</th>
                            <th class="py-3 px-6 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="py-4 px-6 font-mono font-bold text-amber-900 text-xs">
                                    {{ $invoice->invoice_number }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-stone-800 text-xs">
                                        {{ $invoice->booking ? $invoice->booking->booking_reference : 'N/A' }}
                                    </div>
                                    <div class="text-[11px] text-stone-500">
                                        {{ $invoice->booking && $invoice->booking->decoration ? $invoice->booking->decoration->name : 'Wedding Package' }}
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-stone-600">
                                    {{ $invoice->issued_at ? $invoice->issued_at->format('d M, Y') : $invoice->created_at->format('d M, Y') }}
                                </td>
                                <td class="py-4 px-6 font-bold text-stone-900 text-xs">
                                    {{ $invoice->formatted_grand_total }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-emerald-700 text-xs">
                                    {{ $invoice->formatted_paid_amount }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-xs {{ $invoice->balance_due > 0 ? 'text-amber-700' : 'text-stone-500' }}">
                                    {{ $invoice->formatted_balance_due }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($invoice->status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            ✓ Fully Paid
                                        </span>
                                    @elseif($invoice->status === 'partially_paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            Partially Paid
                                        </span>
                                    @elseif($invoice->status === 'issued')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                            Issued
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('account.invoices.show', $invoice) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded-lg text-xs font-semibold transition border border-amber-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View & Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="p-4 border-t border-stone-100">
                    {{ $invoices->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
