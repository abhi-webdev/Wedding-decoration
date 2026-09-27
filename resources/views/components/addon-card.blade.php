@props(['addon'])

<div class="bg-white rounded-xl p-4 border border-brand-light-border shadow-soft-luxury hover:border-brand-gold/60 transition-all flex flex-col sm:flex-row items-start sm:items-center gap-4">
    <!-- Addon Thumbnail Image -->
    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden bg-brand-offwhite flex-shrink-0 border border-brand-light-border flex items-center justify-center">
        @if($addon->safe_image)
            <img 
                src="{{ $addon->safe_image }}" 
                alt="{{ $addon->name }}" 
                class="w-full h-full object-cover"
                loading="lazy"
            />
        @else
            <div class="p-2 text-center text-brand-muted-brown">
                <i class="fas fa-sparkles text-brand-gold text-base"></i>
            </div>
        @endif
    </div>

    <!-- Details -->
    <div class="flex-grow min-w-0 space-y-1">
        <div class="flex items-start justify-between gap-2">
            <h4 class="font-serif text-base font-bold text-brand-charcoal truncate">
                {{ $addon->name }}
            </h4>
            <span class="font-serif text-base font-bold text-brand-burgundy whitespace-nowrap">
                +{{ $addon->formatted_price }}
            </span>
        </div>
        <p class="text-xs text-brand-muted-brown line-clamp-2 leading-relaxed">
            {{ $addon->description }}
        </p>
    </div>

    <!-- Action Tag -->
    <div class="sm:flex-shrink-0 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-brand-light-border/60 flex items-center justify-between sm:justify-end">
        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-royal-rose bg-brand-cream px-2.5 py-1 rounded border border-brand-gold/30">
            <i class="fas fa-plus-circle text-brand-gold text-[10px]"></i>
            Optional Add-on
        </span>
    </div>
</div>
