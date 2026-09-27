@props(['package'])

<div class="group bg-white rounded-2xl border border-brand-light-border overflow-hidden shadow-soft-luxury hover:shadow-2xl hover:border-brand-gold/60 transition-all duration-300 flex flex-col h-full">
    <!-- Image with Badge & Capacity -->
    <div class="relative aspect-[16/10] overflow-hidden bg-brand-offwhite flex items-center justify-center">
        @if($package->display_image)
            <img 
                src="{{ $package->display_image }}" 
                alt="{{ $package->name }} - Aditya Utsav Wedding Decor" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            />
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-deep-burgundy via-brand-burgundy to-brand-royal-rose p-6 text-center text-white">
                <div class="w-12 h-12 rounded-2xl bg-brand-gold/20 text-brand-gold flex items-center justify-center mb-1.5 border border-brand-gold/40 shadow-sm">
                    <i class="fas fa-gem text-lg"></i>
                </div>
                <span class="text-xs font-serif font-bold text-brand-gold-light">{{ $package->tier }} Package</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>

        <!-- Badge -->
        @if($package->badge)
            <div class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 bg-brand-burgundy text-brand-gold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-bold tracking-wider uppercase border border-brand-gold/40 shadow-sm">
                <i class="fas fa-crown text-[9px] sm:text-[10px] mr-1"></i> {{ $package->badge }}
            </div>
        @endif

        @if($package->guest_capacity)
            <div class="absolute top-2.5 right-2.5 sm:top-3 sm:right-3 bg-black/60 backdrop-blur-sm text-white px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-md text-[9px] sm:text-[10px] font-semibold flex items-center gap-1 border border-white/20">
                <i class="fas fa-users text-brand-gold text-[9px]"></i> {{ $package->guest_capacity }}
            </div>
        @endif

        <!-- Starting Price Tag on Image -->
        <div class="absolute bottom-2.5 left-2.5 right-2.5 sm:bottom-3 sm:left-3 sm:right-3 flex items-end justify-between text-white">
            <div>
                <span class="text-[9px] sm:text-[10px] text-brand-cream/80 block uppercase tracking-wider font-semibold">Starting Package</span>
                <div class="flex items-baseline gap-1.5 sm:gap-2">
                    <span class="font-serif text-lg sm:text-2xl font-bold text-brand-gold">
                        {{ $package->formatted_price }}
                    </span>
                    @if($package->formatted_discount_price && $package->base_price > $package->discount_price)
                        <span class="text-[11px] sm:text-xs text-gray-300 line-through">
                            {{ $package->formatted_base_price }}
                        </span>
                    @endif
                </div>
            </div>
            @if($package->duration)
                <span class="text-[9px] sm:text-[10px] text-brand-cream/90 bg-white/20 px-2 py-0.5 rounded font-medium">
                    <i class="far fa-clock mr-1 text-[9px]"></i> {{ $package->duration }}
                </span>
            @endif
        </div>
    </div>

    <!-- Package Content Details -->
    <div class="p-4 sm:p-5 flex flex-col flex-grow justify-between space-y-3 sm:space-y-4">
        <div class="space-y-1.5 sm:space-y-2">
            <h3 class="font-serif text-base sm:text-lg font-bold text-brand-charcoal group-hover:text-brand-burgundy transition-colors leading-snug">
                <a href="{{ route('packages.show', $package->slug) }}">
                    {{ $package->name }}
                </a>
            </h3>

            <p class="text-xs text-brand-muted-brown line-clamp-2 leading-relaxed">
                {{ $package->short_description ?: $package->tagline }}
            </p>

            <!-- Ceremonies / Highlights -->
            @if(!empty($package->highlights) && is_array($package->highlights))
                <ul class="pt-2 border-t border-brand-light-border/60 space-y-1.5 text-xs text-brand-charcoal">
                    @foreach(array_slice($package->highlights, 0, 3) as $hl)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-brand-gold text-[10px] sm:text-[11px] shrink-0"></i>
                            <span class="truncate">{{ $hl }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Card Footer CTA -->
        <div class="pt-3 border-t border-brand-light-border flex items-center justify-between gap-2">
            <a 
                href="{{ route('packages.show', $package->slug) }}" 
                class="flex-1 text-center py-2 px-3 sm:py-2.5 sm:px-4 text-xs font-bold uppercase tracking-wider text-brand-burgundy bg-brand-burgundy/10 hover:bg-brand-burgundy hover:text-white rounded-xl transition-all border border-brand-burgundy/20"
            >
                View Details
            </a>
            <a 
                href="{{ route('quote', ['package' => $package->name]) }}" 
                class="py-2 px-3 sm:py-2.5 sm:px-3.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-sm transition-all text-center"
                title="Get Custom Quote for {{ $package->name }}"
            >
                <i class="fas fa-file-invoice mr-1 text-brand-gold"></i> Quote
            </a>
        </div>
    </div>
</div>
