@props(['decoration'])

<div class="group bg-white rounded-xl overflow-hidden border border-brand-light-border shadow-soft-luxury hover:shadow-card-hover transition-all duration-300 flex flex-col h-full relative">
    <!-- Image & Badges -->
    <div class="relative h-48 sm:h-60 overflow-hidden bg-brand-offwhite">
        <img 
            src="{{ $decoration->safe_primary_image }}" 
            alt="Traditional Bihar {{ $decoration->name }} setup in {{ $decoration->location }}" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
            loading="lazy"
            onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
        />
        
        <!-- Category & Feature Badges -->
        <div class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 flex flex-wrap gap-1.5 items-center z-10">
            <span class="bg-brand-burgundy/90 backdrop-blur-sm text-brand-cream text-[10px] sm:text-[11px] font-bold tracking-wider uppercase px-2 sm:px-2.5 py-0.5 sm:py-1 rounded shadow-sm">
                {{ $decoration->category->name ?? 'Wedding Decor' }}
            </span>
            @if($decoration->is_featured)
                <span class="bg-brand-gold text-brand-deep-burgundy text-[9px] sm:text-[10px] font-bold uppercase tracking-wider px-1.5 sm:px-2 py-0.5 rounded shadow">
                    Featured
                </span>
            @elseif($decoration->is_trending)
                <span class="bg-amber-600 text-white text-[9px] sm:text-[10px] font-bold uppercase tracking-wider px-1.5 sm:px-2 py-0.5 rounded shadow">
                    Trending
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button 
            type="button" 
            data-wishlist-id="{{ $decoration->id }}"
            onclick="Wishlist.add({ id: {{ $decoration->id }}, name: '{{ addslashes($decoration->name) }}', price: '{{ $decoration->formatted_price }}', image: '{{ $decoration->safe_primary_image }}', category: '{{ $decoration->category->name ?? '' }}' })"
            class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/90 backdrop-blur-sm text-brand-charcoal hover:text-brand-burgundy hover:bg-white flex items-center justify-center shadow-md transition-transform active:scale-90 z-10"
            aria-label="Shortlist {{ $decoration->name }}"
        >
            <i class="far fa-heart text-xs sm:text-sm"></i>
        </button>

        <!-- Location & Style Pills (Bottom Overlay) -->
        <div class="absolute bottom-2.5 left-2.5 right-2.5 sm:bottom-3 sm:left-3 sm:right-3 flex items-center justify-between text-[10px] sm:text-[11px] z-10">
            @if($decoration->style)
                <span class="px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-brand-gold-light font-medium">
                    {{ $decoration->style }} Style
                </span>
            @else
                <span class="px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-emerald-300 font-medium">
                    Available in Bihar
                </span>
            @endif

            <span class="px-2 py-0.5 rounded bg-black/60 backdrop-blur-sm text-brand-cream/90 font-medium truncate max-w-[120px] sm:max-w-[140px]">
                <i class="fas fa-map-marker-alt text-brand-gold mr-1 text-[9px] sm:text-[10px]"></i>
                {{ $decoration->location }}
            </span>
        </div>
    </div>

    <!-- Details Body -->
    <div class="p-4 sm:p-5 flex flex-col flex-grow justify-between bg-white">
        <div>
            <!-- Rating & Reviews -->
            <div class="flex items-center gap-1.5 text-xs text-amber-500 mb-1.5">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($decoration->rating))
                            <i class="fas fa-star text-amber-400 text-xs"></i>
                        @else
                            <i class="fas fa-star-half-alt text-amber-400 text-xs"></i>
                        @endif
                    @endfor
                </div>
                <span class="font-bold text-brand-charcoal ml-1 text-xs">{{ number_format($decoration->rating, 1) }}</span>
                <span class="text-brand-muted-brown text-[10px] sm:text-[11px]">({{ $decoration->reviews_count }} reviews)</span>
            </div>

            <!-- Title -->
            <h3 class="font-serif text-base sm:text-lg font-bold text-brand-charcoal group-hover:text-brand-burgundy transition-colors line-clamp-1 leading-snug">
                <a href="{{ route('decorations.show', $decoration->slug) }}">
                    {{ $decoration->name }}
                </a>
            </h3>

            <!-- Short Description / Tagline -->
            <p class="text-xs text-brand-muted-brown line-clamp-2 mt-1 leading-relaxed">
                {{ $decoration->display_description }}
            </p>

            <!-- Key Feature Badges -->
            @if(!empty($decoration->features) && is_array($decoration->features))
                <div class="flex flex-wrap gap-1.5 mt-2.5 sm:mt-3">
                    @foreach(array_slice($decoration->features, 0, 2) as $feat)
                        <span class="text-[9px] sm:text-[10px] font-medium bg-brand-offwhite text-brand-muted-brown px-2 py-0.5 rounded border border-brand-light-border/60">
                            {{ $feat }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pricing & CTAs -->
        <div class="mt-4 sm:mt-5 pt-3 sm:pt-3.5 border-t border-brand-light-border">
            <div class="flex items-baseline justify-between mb-2.5 sm:mb-3">
                <span class="text-[10px] sm:text-[11px] font-medium text-brand-muted-brown uppercase tracking-wider">
                    Starting from
                </span>
                <div class="text-right">
                    @if($decoration->has_discount)
                        <span class="text-[11px] sm:text-xs text-gray-400 line-through mr-1">
                            {{ $decoration->formatted_price }}
                        </span>
                        <span class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                            {{ $decoration->formatted_discount_price }}
                        </span>
                    @else
                        <span class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                            {{ $decoration->formatted_price }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a 
                    href="{{ route('decorations.show', $decoration->slug) }}" 
                    class="inline-flex items-center justify-center px-2 sm:px-3 py-2 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-light-border/50 rounded border border-brand-light-border transition-colors text-center"
                >
                    View Details
                </a>
                
                <button 
                    type="button" 
                    onclick="openAvailabilityModal('{{ addslashes($decoration->name) }}', '{{ addslashes($decoration->location) }}')"
                    class="inline-flex items-center justify-center px-2 sm:px-3 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded border border-brand-gold shadow-sm transition-all text-center"
                >
                    Book Now
                </button>
            </div>
        </div>
    </div>
</div>
