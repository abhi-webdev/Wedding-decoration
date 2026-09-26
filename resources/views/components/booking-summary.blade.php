@props([
    'decoration',
    'booking' => null,
    'eventData' => null,
    'isConfirmation' => false
])

@php
    $basePrice = $booking ? $booking->base_amount : $decoration->actual_booking_price;
    $addonTotal = $booking ? $booking->addon_amount : 0;
    $total = $booking ? $booking->estimated_total : $basePrice;
@endphp

<div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-5">
    
    <!-- Decoration Snapshot Header -->
    <div class="flex items-start gap-4 pb-4 border-b border-brand-light-border/70">
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-brand-offwhite flex-shrink-0 border border-brand-light-border">
            <img 
                src="{{ $decoration->safe_primary_image }}" 
                alt="{{ $decoration->name }}" 
                class="w-full h-full object-cover"
                onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
            />
        </div>
        <div class="min-w-0 flex-grow space-y-1">
            <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-brand-burgundy bg-brand-burgundy/10 px-2 py-0.5 rounded">
                {{ $decoration->category->name ?? 'Wedding Decoration' }}
            </span>
            <h3 class="font-serif text-base sm:text-lg font-bold text-brand-charcoal truncate">
                {{ $decoration->name }}
            </h3>
            <div class="text-xs text-brand-muted-brown flex items-center gap-1">
                <i class="fas fa-map-marker-alt text-brand-gold text-[11px]"></i>
                <span class="truncate">{{ $decoration->location }}</span>
            </div>
        </div>
    </div>

    <!-- Event Key Details Grid (If Available) -->
    @if($booking || $eventData)
        <div class="p-3.5 rounded-xl bg-brand-offwhite/80 border border-brand-light-border/80 space-y-2 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-brand-muted-brown">Event Type:</span>
                <strong class="text-brand-charcoal" id="summary-event-type">{{ $booking->event_type ?? ($eventData['event_type'] ?? 'Wedding Celebration') }}</strong>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-brand-muted-brown">Event Date:</span>
                <strong class="text-brand-burgundy" id="summary-event-date">{{ $booking ? $booking->formatted_event_date : ($eventData['event_date'] ?? 'Select Date') }}</strong>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-brand-muted-brown">Time Window:</span>
                <strong class="text-brand-charcoal" id="summary-event-time">{{ $booking ? $booking->formatted_time_range : ($eventData['time_range'] ?? 'Standard Full Event Setup') }}</strong>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-brand-muted-brown">Guest Count:</span>
                <strong class="text-brand-charcoal" id="summary-guest-count">{{ $booking->guest_count ?? ($eventData['guest_count'] ?? '100') }} Guests</strong>
            </div>
            @if($booking && $booking->city)
                <div class="flex items-center justify-between pt-1 border-t border-brand-light-border/60">
                    <span class="text-brand-muted-brown">Venue City:</span>
                    <strong class="text-brand-charcoal">{{ $booking->city }}, {{ $booking->state }}</strong>
                </div>
            @endif
        </div>
    @endif

    <!-- Inclusions Brief -->
    <div class="text-xs text-brand-muted-brown space-y-1">
        <span class="font-bold text-brand-charcoal uppercase tracking-wider text-[11px] block">Includes:</span>
        <div class="flex items-center gap-1.5 text-emerald-700">
            <i class="fas fa-check-circle text-[11px]"></i>
            <span>Complete Setup, Transportation &amp; Teardown Crew</span>
        </div>
    </div>

    <!-- Live Cost Calculation Breakdown -->
    <div class="pt-3 border-t border-brand-light-border/80 space-y-2 text-xs">
        <div class="flex items-center justify-between text-brand-muted-brown">
            <span>Base Decoration Setup:</span>
            <span class="font-semibold text-brand-charcoal" id="summary-base-price">
                ₹{{ number_format($basePrice, 0) }}
            </span>
        </div>

        <!-- Add-ons Container (Dynamic in Form / Static in Confirmation) -->
        <div id="summary-addons-list-container" class="space-y-1.5 {{ ($booking && $booking->addons->count() > 0) ? '' : 'hidden' }}">
            <div class="text-[11px] font-bold text-brand-charcoal uppercase tracking-wider pt-1">
                Selected Add-ons:
            </div>
            <div id="summary-addons-items" class="space-y-1 pl-2 border-l-2 border-brand-gold/40 text-brand-muted-brown">
                @if($booking)
                    @foreach($booking->addons as $bAddon)
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="truncate max-w-[180px]">{{ $bAddon->addon->name ?? 'Custom Add-on' }}</span>
                            <span class="font-medium text-brand-charcoal">+{{ $bAddon->formatted_total_price }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="flex items-center justify-between text-brand-muted-brown pt-1">
                <span>Add-ons Subtotal:</span>
                <span class="font-semibold text-brand-charcoal" id="summary-addons-subtotal">
                    ₹{{ number_format($addonTotal, 0) }}
                </span>
            </div>
        </div>

        <!-- Grand Estimated Total -->
        <div class="p-3.5 rounded-xl bg-gradient-to-br from-brand-offwhite to-brand-cream border border-brand-gold/40 flex items-baseline justify-between mt-3">
            <div>
                <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-bold">
                    Estimated Total
                </span>
                <span class="font-serif text-2xl font-bold text-brand-burgundy" id="summary-estimated-total">
                    ₹{{ number_format($total, 0) }}
                </span>
            </div>
            <span class="text-[10px] text-brand-burgundy bg-white px-2 py-0.5 rounded border border-brand-light-border font-semibold">
                Estimate Only
            </span>
        </div>

        <p class="text-[11px] text-brand-muted-brown leading-relaxed italic pt-1">
            <i class="fas fa-info-circle text-brand-gold mr-1"></i>
            Final quotation may vary slightly depending on exact venue distance, stage dimensions, and custom flower preferences.
        </p>
    </div>

</div>
