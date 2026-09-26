@props(['offer'])

<div class="group bg-white rounded-2xl border border-brand-light-border overflow-hidden shadow-soft-luxury hover:shadow-2xl hover:border-brand-gold transition-all duration-300 flex flex-col h-full">
    <!-- Header visual / Discount Banner -->
    <div class="relative aspect-[16/9] overflow-hidden bg-brand-burgundy/10">
        <img 
            src="{{ $offer->display_image }}" 
            alt="{{ $offer->title }}" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

        <!-- Highlight Badge -->
        @if($offer->highlight_badge)
            <div class="absolute top-3 left-3 bg-brand-gold text-brand-burgundy font-bold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full border border-white/40 shadow">
                <i class="fas fa-tag mr-1"></i> {{ $offer->highlight_badge }}
            </div>
        @endif

        @if($offer->discount_text)
            <div class="absolute top-3 right-3 bg-brand-burgundy text-white font-serif text-sm font-bold px-3 py-1 rounded-full border border-brand-gold shadow">
                {{ $offer->discount_text }}
            </div>
        @endif

        <!-- Title over Image -->
        <div class="absolute bottom-3 left-4 right-4 text-white">
            <h3 class="font-serif text-lg font-bold leading-tight drop-shadow">
                {{ $offer->title }}
            </h3>
            @if($offer->subtitle)
                <p class="text-[11px] text-brand-cream/90 mt-0.5 line-clamp-1">
                    {{ $offer->subtitle }}
                </p>
            @endif
        </div>
    </div>

    <!-- Body Content -->
    <div class="p-5 flex flex-col flex-grow justify-between space-y-4">
        <div class="space-y-3">
            <p class="text-xs text-brand-muted-brown leading-relaxed">
                {{ $offer->short_description ?: $offer->description }}
            </p>

            <!-- Coupon Code & Validity -->
            <div class="p-3 bg-brand-offwhite rounded-xl border border-brand-light-border space-y-2 text-xs">
                @if($offer->coupon_code)
                    <div class="flex items-center justify-between">
                        <span class="text-brand-muted-brown text-[11px] font-semibold uppercase tracking-wider">Coupon Code:</span>
                        <span class="px-2.5 py-0.5 bg-white border border-dashed border-brand-burgundy text-brand-burgundy font-mono font-bold rounded text-xs select-all">
                            {{ $offer->coupon_code }}
                        </span>
                    </div>
                @endif

                @if($offer->valid_until)
                    <div class="flex items-center justify-between text-[11px] text-brand-charcoal">
                        <span class="text-brand-muted-brown">Valid Until:</span>
                        <span class="font-bold text-brand-royal-rose">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $offer->valid_until->format('d M Y') }}
                        </span>
                    </div>
                @elseif($offer->valid_till)
                    <div class="flex items-center justify-between text-[11px] text-brand-charcoal">
                        <span class="text-brand-muted-brown">Valid Till:</span>
                        <span class="font-bold text-brand-royal-rose">{{ $offer->valid_till }}</span>
                    </div>
                @endif
            </div>

            @if($offer->terms)
                <p class="text-[10px] text-brand-muted-brown italic">
                    * {{ Str::limit($offer->terms, 90) }}
                </p>
            @endif
        </div>

        <!-- CTA Buttons -->
        <div class="pt-3 border-t border-brand-light-border flex items-center justify-between gap-2">
            <a 
                href="{{ route('offers.show', $offer->slug) }}" 
                class="flex-1 text-center py-2.5 px-3 text-xs font-bold text-brand-burgundy bg-brand-burgundy/10 hover:bg-brand-burgundy hover:text-white rounded-xl transition-all"
            >
                Offer Details
            </a>
            <a 
                href="{{ route('quote', ['offer' => $offer->coupon_code ?: $offer->title]) }}" 
                class="py-2.5 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all text-center"
            >
                Claim Offer
            </a>
        </div>
    </div>
</div>
