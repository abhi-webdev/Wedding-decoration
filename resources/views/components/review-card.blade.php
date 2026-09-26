@props(['review'])

<div class="bg-white rounded-2xl p-7 border border-brand-light-border shadow-soft-luxury flex flex-col justify-between h-full relative">
    <!-- Top Quote Icon -->
    <div class="text-brand-gold/30 text-3xl font-serif absolute top-5 right-6 select-none">
        <i class="fas fa-quote-right"></i>
    </div>

    <div>
        <!-- Star Rating -->
        <div class="flex items-center gap-1 text-amber-400 mb-4">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-sm"></i>
            @endfor
            <span class="text-xs font-bold text-brand-charcoal ml-2">5.0</span>
        </div>

        <!-- Review Quote -->
        <p class="text-sm text-brand-charcoal/90 leading-relaxed italic mb-6">
            "{{ $review->review_text }}"
        </p>
    </div>

    <!-- Reviewer Info -->
    <div class="pt-4 border-t border-brand-light-border flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-full bg-brand-burgundy text-brand-gold font-serif font-bold text-sm flex items-center justify-center border border-brand-gold/40 flex-shrink-0">
            {{ $review->avatar_initials ?? 'AU' }}
        </div>
        <div>
            <h4 class="font-serif text-base font-bold text-brand-charcoal">
                {{ $review->customer_name }}
            </h4>
            <div class="flex flex-wrap items-center gap-x-2 text-[11px] text-brand-muted-brown mt-0.5">
                <span class="text-brand-burgundy font-medium">{{ $review->event_type }}</span>
                <span>•</span>
                <span>{{ $review->city }}</span>
            </div>
        </div>
    </div>
</div>
