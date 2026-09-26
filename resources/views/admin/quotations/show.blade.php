@extends('layouts.admin')

@section('title', 'Quotation ' . $quotation->quotation_number)
@section('header', 'Quotation: ' . $quotation->quotation_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to All Quotations</span>
        </a>

        <div class="flex items-center gap-2">
            @if($quotation->status === 'draft')
                <a href="{{ route('admin.quotations.edit', $quotation->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                    Edit Draft
                </a>
                <form action="{{ route('admin.quotations.send', $quotation->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition shadow">
                        Send to Client
                    </button>
                </form>
            @endif

            @if(in_array($quotation->status, ['draft', 'sent', 'viewed']))
                <form action="{{ route('admin.quotations.cancel', $quotation->id) }}" method="POST" onsubmit="return confirm('Cancel this quotation?')">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 font-semibold text-xs transition">
                        Cancel
                    </button>
                </form>
            @endif

            @if($quotation->status === 'accepted')
                <a href="{{ route('admin.payments.create', ['booking_id' => $quotation->booking_id]) }}" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow">
                    Record Payment
                </a>
            @endif
        </div>
    </div>

    <!-- Official Quotation Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
        <!-- Header & Branding -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="font-heading text-lg font-bold text-amber-800 tracking-wide">ADITYA UTSAV</span>
                <p class="text-xs text-slate-500">Bihar Wedding Decoration & Event Services</p>
                <p class="text-xs text-slate-400">Siwan, Patna, Gopalganj, Saran (Bihar) & Gorakhpur (UP)</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $quotation->status_badge_classes }}">
                    {{ $quotation->status }}
                </span>
                <p class="text-xs font-mono font-bold text-slate-900 mt-2">{{ $quotation->quotation_number }}</p>
                <p class="text-[11px] text-slate-400">Date: {{ $quotation->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Prepared For Client:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $quotation->customer->name ?? $quotation->booking->customer_name }}</p>
                <p class="text-slate-600">{{ $quotation->customer->phone ?? $quotation->booking->customer_phone }}</p>
                <p class="text-slate-500">{{ $quotation->customer->email ?? $quotation->booking->customer_email }}</p>
            </div>
            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Event Details:</span>
                <p class="font-bold text-slate-900">{{ $quotation->booking->event_type ?? 'Wedding Celebration' }}</p>
                <p class="text-slate-700 font-semibold">{{ $quotation->booking ? $quotation->booking->formatted_event_date : 'TBD' }}</p>
                <p class="text-slate-500">{{ $quotation->booking->city ?? 'Bihar' }} (Booking #{{ $quotation->booking->booking_reference ?? $quotation->booking_id }})</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/80 uppercase font-semibold text-slate-600 text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Item / Service Description</th>
                        <th class="py-3 px-3 text-center">Qty</th>
                        <th class="py-3 px-3 text-right">Unit Price</th>
                        <th class="py-3 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($quotation->items as $idx => $item)
                        <tr>
                            <td class="py-3 px-3 text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3 px-3 font-semibold text-slate-900">
                                {{ $item->description }}
                                <span class="text-[10px] text-slate-400 block uppercase font-mono">{{ $item->item_type }}</span>
                            </td>
                            <td class="py-3 px-3 text-center font-medium">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-mono">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-right font-bold text-slate-900 font-mono">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Balance Breakdown -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 text-xs">
            <div class="space-y-3">
                @if($quotation->notes)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Notes</span>
                        <p class="text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100 whitespace-pre-line">{{ $quotation->notes }}</p>
                    </div>
                @endif

                @if($quotation->terms)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block mb-0.5">Terms of Service</span>
                        <p class="text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 whitespace-pre-line text-[11px] leading-relaxed">{{ $quotation->terms }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-semibold">₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>

                @if($quotation->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Seasonal Discount:</span>
                        <span class="font-mono">-₹{{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                @endif

                @if($quotation->additional_charges > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Transportation / Extras:</span>
                        <span class="font-mono">+₹{{ number_format($quotation->additional_charges, 2) }}</span>
                    </div>
                @endif

                @if($quotation->tax_amount > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>GST / Taxes:</span>
                        <span class="font-mono">+₹{{ number_format($quotation->tax_amount, 2) }}</span>
                    </div>
                @endif

                <div class="pt-2 border-t border-slate-200 flex justify-between items-center text-slate-900">
                    <span class="font-bold text-sm">Grand Total:</span>
                    <span class="text-base font-extrabold text-amber-800 font-mono">{{ $quotation->formatted_grand_total }}</span>
                </div>

                <div class="p-2.5 bg-amber-100/60 rounded-lg border border-amber-200/80 space-y-1 mt-2 text-[11px]">
                    <div class="flex justify-between font-bold text-amber-900">
                        <span>Advance Required ({{ $quotation->advance_percentage }}%):</span>
                        <span class="font-mono">{{ $quotation->formatted_advance }}</span>
                    </div>
                    <div class="flex justify-between text-amber-800">
                        <span>Balance Payable on Event:</span>
                        <span class="font-mono">{{ $quotation->formatted_balance }}</span>
                    </div>
                </div>

                <div class="pt-2 text-[11px] text-slate-500">
                    <span>Validity: </span>
                    <strong class="{{ $quotation->isExpired() ? 'text-rose-600' : 'text-slate-800' }}">
                        {{ $quotation->valid_until ? $quotation->valid_until->format('d F Y') : 'Open' }}
                    </strong>
                    @if($quotation->isExpired())
                        <span class="text-rose-600 font-bold ml-1">(Expired)</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
