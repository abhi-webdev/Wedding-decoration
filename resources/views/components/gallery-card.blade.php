@props(['item'])

<div 
    class="group relative bg-white rounded-2xl overflow-hidden border border-brand-light-border shadow-soft-luxury hover:shadow-2xl hover:border-brand-gold transition-all duration-300 cursor-pointer"
    onclick="openGalleryLightbox('{{ $item->display_image }}', '{{ addslashes($item->title) }}', '{{ addslashes($item->caption ?: $item->description) }}', '{{ addslashes($item->location) }}', '{{ addslashes(ucfirst($item->category)) }}')"
>
    <!-- Aspect container -->
    <div class="aspect-[4/3] w-full overflow-hidden bg-brand-offwhite flex items-center justify-center">
        @if($item->display_image)
            <img 
                src="{{ $item->display_image }}" 
                alt="{{ $item->title }} - Bihar Wedding Decoration" 
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                loading="lazy"
            />
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-cream via-brand-offwhite to-amber-50/50 p-4 text-center">
                <i class="fas fa-camera text-brand-gold text-2xl mb-1"></i>
                <span class="text-xs font-serif font-bold text-brand-charcoal">{{ $item->title }}</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity"></div>
    </div>

    <!-- Category Pill -->
    <div class="absolute top-3 left-3 bg-brand-burgundy/90 text-brand-gold text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border border-brand-gold/40 shadow-sm backdrop-blur-sm">
        {{ ucfirst(str_replace('-', ' ', $item->category)) }}
    </div>

    <!-- Location Pill -->
    @if($item->location)
        <div class="absolute top-3 right-3 bg-black/60 text-white text-[10px] font-medium px-2 py-0.5 rounded-md backdrop-blur-sm flex items-center gap-1">
            <i class="fas fa-map-marker-alt text-brand-gold text-[9px]"></i> {{ $item->location }}
        </div>
    @endif

    <!-- Card Overlay Info -->
    <div class="absolute bottom-3 left-3 right-3 text-white space-y-1">
        <h4 class="font-serif text-sm font-bold leading-snug group-hover:text-brand-gold transition-colors drop-shadow">
            {{ $item->title }}
        </h4>
        @if($item->caption)
            <p class="text-[11px] text-gray-200 line-clamp-2 leading-tight">
                {{ $item->caption }}
            </p>
        @endif
        <div class="pt-1 flex items-center justify-between text-[10px] text-brand-gold">
            <span class="inline-flex items-center gap-1 font-semibold">
                <i class="fas fa-expand-alt"></i> Click to Zoom Fullscreen
            </span>
        </div>
    </div>
</div>
