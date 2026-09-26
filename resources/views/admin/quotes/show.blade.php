@extends('layouts.admin')

@section('title', 'Quote Request #' . $quote->quote_reference)
@section('header', 'Quote Request: #' . $quote->quote_reference)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.quotes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to All Quotes</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Inquiry Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span>Inquiry Details</span>
                    <span class="text-xs text-slate-400 font-normal">Submitted {{ $quote->created_at->format('d M Y, h:i A') }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Customer Name</span>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $quote->name }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Event Type</span>
                        <p class="font-bold text-slate-800 text-sm capitalize mt-0.5">{{ $quote->event_type ?? 'Wedding' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Phone</span>
                        <a href="tel:{{ $quote->phone }}" class="font-semibold text-amber-700 hover:underline mt-0.5 block">{{ $quote->phone }}</a>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Email</span>
                        <a href="mailto:{{ $quote->email }}" class="font-semibold text-slate-700 hover:underline mt-0.5 block break-all">{{ $quote->email }}</a>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Target Event Date</span>
                        <p class="font-bold text-slate-800 mt-0.5">
                            {{ $quote->event_date ? \Carbon\Carbon::parse($quote->event_date)->format('d F Y') : 'Date flexible / TBD' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Estimated Guest Count</span>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $quote->guest_count ?? 'Not specified' }} guests</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Event Venue & City</span>
                        <p class="font-semibold text-slate-800 mt-0.5">{{ $quote->venue_name ?? 'Client Venue' }} ({{ $quote->city ?? 'Bihar' }})</p>
                    </div>

                    <div>
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Budget Range</span>
                        <p class="font-bold text-amber-800 mt-0.5">{{ $quote->budget_range ?? ($quote->budget ? '₹' . number_format($quote->budget) : 'Not specified') }}</p>
                    </div>

                    <div class="sm:col-span-2 pt-2">
                        <span class="text-slate-400 uppercase font-semibold text-[10px] block">Customer Requirements & Special Notes</span>
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 mt-1 text-slate-800 leading-relaxed whitespace-pre-line">
                            {{ $quote->requirements ?? $quote->message ?? 'No additional notes provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Estimate Updater -->
        <div class="space-y-6">
            <div class="bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-xl p-6">
                <h3 class="text-base font-bold text-amber-400 mb-4 pb-2 border-b border-slate-800">Update Quote Status</h3>

                <form action="{{ route('admin.quotes.updateStatus', $quote->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Inquiry Status</label>
                        <select name="status" id="status" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                            <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="reviewed" {{ $quote->status === 'reviewed' ? 'selected' : '' }}>Reviewed by Planner</option>
                            <option value="contacted" {{ $quote->status === 'contacted' ? 'selected' : '' }}>Customer Contacted via Call/WhatsApp</option>
                            <option value="quoted" {{ $quote->status === 'quoted' ? 'selected' : '' }}>Formal Quote Sent</option>
                            <option value="converted" {{ $quote->status === 'converted' ? 'selected' : '' }}>Converted to Confirmed Booking</option>
                            <option value="declined" {{ $quote->status === 'declined' ? 'selected' : '' }}>Declined / Closed</option>
                        </select>
                    </div>

                    <div>
                        <label for="estimated_amount" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Estimated Amount (₹)</label>
                        <input type="number" name="estimated_amount" id="estimated_amount" value="{{ old('estimated_amount', $quote->estimated_amount) }}" step="0.01"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl p-2.5 text-xs text-white focus:outline-none focus:border-amber-500" placeholder="e.g. 150000">
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Internal Staff Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" placeholder="Notes from phone conversation with family..."
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl p-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 px-4 rounded-xl text-xs transition shadow">
                        Save Quote Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
