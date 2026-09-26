@extends('layouts.account')

@section('title', 'My Quotations | Aditya Utsav')

@section('account_content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-brand-light-border">
        <div>
            <h1 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                Event Quotations & Estimates
            </h1>
            <p class="text-xs text-brand-muted-brown mt-0.5">
                Review formal decoration pricing proposals, accept terms, and secure your event date.
            </p>
        </div>
    </div>

    <!-- Quotation Cards List -->
    <div class="space-y-4">
        @forelse($quotations as $quote)
            <div class="bg-white rounded-2xl border border-brand-light-border p-5 shadow-soft-luxury hover:border-brand-gold/50 transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-sm text-brand-burgundy">{{ $quote->quotation_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $quote->status_badge_classes }}">
                            {{ $quote->status }}
                        </span>
                    </div>

                    <h4 class="font-bold text-brand-charcoal text-sm">
                        {{ $quote->booking->decoration->name ?? 'Custom Wedding Decoration Package' }}
                    </h4>

                    <p class="text-xs text-brand-muted-brown">
                        Event Date: <strong>{{ $quote->booking ? $quote->booking->formatted_event_date : 'Flexible' }}</strong> • 
                        City: <strong>{{ $quote->booking->city ?? 'Bihar' }}</strong> • 
                        Booking Ref: <strong>#{{ $quote->booking->booking_reference ?? $quote->booking_id }}</strong>
                    </p>

                    <div class="pt-1 flex items-center gap-4 text-xs">
                        <span>Grand Total: <strong class="text-brand-charcoal">{{ $quote->formatted_grand_total }}</strong></span>
                        <span>Advance Required: <strong class="text-emerald-700">{{ $quote->formatted_advance }}</strong> ({{ $quote->advance_percentage }}%)</span>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end gap-2 w-full sm:w-auto">
                    <span class="text-[11px] {{ $quote->isExpired() ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                        Valid until: {{ $quote->valid_until ? $quote->valid_until->format('d M Y') : 'N/A' }}
                    </span>

                    <a href="{{ route('account.quotations.show', $quote->id) }}" class="w-full sm:w-auto px-5 py-2 rounded-xl bg-brand-burgundy hover:bg-brand-deep-burgundy text-white text-xs font-bold text-center transition shadow">
                        Review Quotation &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-brand-light-border p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-brand-offwhite text-brand-muted-brown flex items-center justify-center mx-auto text-lg">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal">No Quotations Yet</h3>
                <p class="text-xs text-brand-muted-brown max-w-sm mx-auto">
                    Once our wedding planners review your decoration booking request, itemized quotations will be delivered here for your approval.
                </p>
                <a href="{{ route('decorations.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-brand-gold text-brand-charcoal font-bold text-xs">
                    Browse Decoration Themes
                </a>
            </div>
        @endforelse
    </div>

    @if($quotations->hasPages())
        <div class="pt-4">
            {{ $quotations->links() }}
        </div>
    @endif
</div>
@endsection
