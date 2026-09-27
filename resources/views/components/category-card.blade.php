@props(['category'])

<div class="group relative bg-white rounded-xl overflow-hidden border border-brand-light-border shadow-soft-luxury hover:shadow-card-hover transition-all duration-300 flex flex-col h-full">
    <!-- Category Image Container -->
    <div class="relative h-48 sm:h-64 overflow-hidden bg-brand-offwhite flex items-center justify-center">
        @if($category->safe_image)
            <img 
                src="{{ $category->safe_image }}" 
                alt="Traditional Bihar {{ $category->name }} ceremony decoration by Aditya Utsav" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                loading="lazy"
            />
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-deep-burgundy via-brand-burgundy to-brand-royal-rose p-6 text-center text-white">
                <div class="w-14 h-14 rounded-2xl bg-brand-gold/20 text-brand-gold flex items-center justify-center mb-2 border border-brand-gold/40 shadow-sm">
                    <i class="fas fa-sparkles text-xl"></i>
                </div>
                <span class="text-xs text-brand-gold-light uppercase tracking-wider font-semibold">Aditya Utsav Bihar</span>
            </div>
        @endif
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-brand-charcoal/85 via-brand-charcoal/30 to-transparent"></div>

        <!-- Category Tagline Badge -->
        <div class="absolute top-3 left-3 sm:top-3.5 sm:left-3.5 flex flex-wrap gap-1.5">
            @if($category->tagline)
                <span class="bg-brand-deep-burgundy/90 backdrop-blur-sm text-brand-gold-light text-[10px] sm:text-[11px] font-semibold tracking-wider uppercase px-2 sm:px-2.5 py-0.5 sm:py-1 rounded border border-brand-gold/40 shadow-sm">
                    {{ $category->tagline }}
                </span>
            @endif
        </div>

        <!-- Category Count Badge -->
        @if(isset($category->decorations_count))
            <span class="absolute top-3 right-3 sm:top-3.5 sm:right-3.5 bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded border border-white/20">
                {{ $category->decorations_count }} Designs
            </span>
        @endif

        <!-- Floating Category Title (Overlay) -->
        <div class="absolute bottom-3 left-3 sm:left-4 right-3 sm:right-4">
            <h3 class="font-serif text-xl sm:text-2xl font-bold text-white drop-shadow-md leading-tight">
                {{ $category->name }}
            </h3>
        </div>
    </div>

    <!-- Content & Action -->
    <div class="p-4 sm:p-5 flex flex-col flex-grow justify-between bg-white">
        <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed line-clamp-2 mb-3 sm:mb-4">
            {{ $category->description }}
        </p>

        <div class="pt-3 border-t border-brand-light-border/60 flex items-center justify-between">
            <a 
                href="{{ route('decorations.category', $category->slug) }}" 
                class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-brand-burgundy group-hover:text-brand-royal-rose transition-colors"
            >
                <span>View Category</span>
                <i class="fas fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition-transform"></i>
            </a>

            <button 
                type="button" 
                onclick="openAvailabilityModal('{{ $category->name }}', 'Siwan')"
                class="text-xs text-brand-muted-brown hover:text-brand-burgundy font-medium flex items-center gap-1"
                title="Check availability for {{ $category->name }}"
            >
                <i class="far fa-calendar-alt text-brand-gold"></i>
                <span>Check Date</span>
            </button>
        </div>
    </div>
</div>
