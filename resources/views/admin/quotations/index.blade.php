@extends('layouts.admin')

@section('title', 'Manage Quotations')
@section('header', 'Quotation Management')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.quotations.index') }}" class="w-full flex flex-col sm:flex-row gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotation number, booking ref, customer name/phone..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Statuses ({{ $counts['all'] }})</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft ({{ $counts['draft'] }})</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent to Client ({{ $counts['sent'] }})</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted ({{ $counts['accepted'] }})</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected ({{ $counts['rejected'] }})</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>

        <a href="{{ route('admin.quotations.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shrink-0 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create New Quotation</span>
        </a>
    </div>

    <!-- Quotations Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Quotation #</th>
                        <th class="py-3.5 px-4">Booking & Client</th>
                        <th class="py-3.5 px-4">Grand Total</th>
                        <th class="py-3.5 px-4">Advance Req.</th>
                        <th class="py-3.5 px-4">Validity</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($quotations as $quote)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.quotations.show', $quote->id) }}" class="font-bold text-amber-700 hover:underline font-mono block">
                                    {{ $quote->quotation_number }}
                                </a>
                                <span class="text-[10px] text-slate-400">{{ $quote->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $quote->customer->name ?? $quote->booking->customer_name }}</span>
                                    <span class="text-[11px] text-slate-500">{{ $quote->customer->phone ?? $quote->booking->customer_phone }}</span>
                                    <a href="{{ route('admin.bookings.show', $quote->booking_id) }}" class="text-[10px] font-mono text-amber-600 hover:underline mt-0.5">
                                        Booking #{{ $quote->booking->booking_reference ?? $quote->booking_id }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                {{ $quote->formatted_grand_total }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-amber-800">{{ $quote->formatted_advance }}</span>
                                <span class="text-[10px] text-slate-400 block">({{ $quote->advance_percentage }}%)</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-600">
                                @if($quote->valid_until)
                                    <span class="{{ $quote->isExpired() ? 'text-rose-600 font-bold' : '' }}">
                                        {{ $quote->valid_until->format('d M Y') }}
                                    </span>
                                    @if($quote->isExpired())
                                        <span class="text-[10px] text-rose-500 block">Expired</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">N/A</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $quote->status_badge_classes }}">
                                    {{ $quote->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.quotations.show', $quote->id) }}" class="px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold transition">
                                        View
                                    </a>
                                    @if($quote->status === 'draft')
                                        <a href="{{ route('admin.quotations.edit', $quote->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.quotations.send', $quote->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition" title="Send to Customer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No quotations found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotations->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
