@props(['decoration'])

@php
    $images = $decoration->images;
    if ($images->isEmpty()) {
        $primaryUrl = $decoration->safe_primary_image;
        $imagesList = [
            (object)[
                'safe_url' => $primaryUrl,
                'caption' => $decoration->name,
                'alt_text' => 'Traditional Bihar ' . $decoration->name . ' in ' . $decoration->location
            ]
        ];
    } else {
        $primaryUrl = $images->first()->safe_url;
        $imagesList = $images;
    }
@endphp

<div class="space-y-4" id="gallery-component">
    <!-- Large Main Image Container -->
    <div class="relative rounded-2xl overflow-hidden bg-brand-charcoal border-2 border-brand-gold/40 shadow-card-hover group h-80 sm:h-[460px]">
        <img 
            id="gallery-main-display" 
            src="{{ $primaryUrl }}" 
            alt="Traditional Bihar {{ $decoration->name }} setup in {{ $decoration->location }}" 
            class="w-full h-full object-cover transition-opacity duration-300"
            onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
        />

        <!-- Gradient Vignette -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>

        <!-- Category & Style Badges -->
        <div class="absolute top-4 left-4 flex flex-wrap gap-2 pointer-events-none">
            <span class="bg-brand-burgundy/90 backdrop-blur-sm text-brand-cream text-xs font-bold uppercase tracking-wider px-3 py-1 rounded shadow-sm border border-brand-gold/30">
                {{ $decoration->category->name }}
            </span>
            @if($decoration->style)
                <span class="bg-black/70 backdrop-blur-sm text-brand-gold-light text-xs font-semibold uppercase tracking-wider px-2.5 py-1 rounded border border-white/20">
                    {{ $decoration->style }} Style
                </span>
            @endif
        </div>

        <!-- Lightbox Trigger Button -->
        <button 
            type="button" 
            onclick="openImageLightbox()" 
            class="absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 backdrop-blur-sm text-brand-charcoal hover:text-brand-burgundy hover:bg-white flex items-center justify-center shadow-lg transition-transform active:scale-95"
            title="View full screen photo"
            aria-label="View larger image"
        >
            <i class="fas fa-expand-alt text-sm"></i>
        </button>

        <!-- Image Caption Bar -->
        <div class="absolute bottom-3 left-4 right-4 text-white text-xs flex items-center justify-between pointer-events-none">
            <span id="gallery-current-caption" class="font-medium drop-shadow-md truncate max-w-sm">
                {{ $decoration->name }} — Main Stage View
            </span>
            <span class="text-[11px] text-brand-gold-light bg-black/50 px-2 py-0.5 rounded">
                <i class="fas fa-camera mr-1"></i> {{ count($imagesList) }} Photos
            </span>
        </div>
    </div>

    <!-- Thumbnails Strip -->
    @if(count($imagesList) > 1)
        <div class="grid grid-cols-4 sm:grid-cols-5 gap-3" id="gallery-thumbnails">
            @foreach($imagesList as $idx => $img)
                <button 
                    type="button" 
                    onclick="switchMainGalleryImage('{{ $img->safe_url }}', '{{ addslashes($img->caption ?? $decoration->name) }}', this)" 
                    class="gallery-thumb-btn relative rounded-xl overflow-hidden border-2 {{ $idx === 0 ? 'border-brand-gold ring-2 ring-brand-gold/40' : 'border-brand-light-border hover:border-brand-gold/60' }} h-20 bg-brand-offwhite focus:outline-none transition-all"
                    aria-label="View photo {{ $idx + 1 }}"
                >
                    <img 
                        src="{{ $img->safe_url }}" 
                        alt="{{ $img->alt_text ?? $decoration->name }}" 
                        class="w-full h-full object-cover"
                        loading="lazy"
                        onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
                    />
                </button>
            @endforeach
        </div>
    @endif
</div>

<!-- Fullscreen Lightbox Modal -->
<div id="detail-lightbox" class="hidden fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4" onclick="closeImageLightbox()">
    <div class="relative max-w-5xl w-full bg-brand-charcoal rounded-2xl overflow-hidden border border-brand-gold shadow-2xl" onclick="event.stopPropagation()">
        <button type="button" onclick="closeImageLightbox()" class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-black/70 text-white hover:text-brand-gold flex items-center justify-center transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>
        
        <div class="flex items-center justify-center max-h-[75vh] p-2 bg-black">
            <img id="lightbox-full-img" src="{{ $primaryUrl }}" alt="{{ $decoration->name }}" class="max-h-[70vh] w-auto max-w-full object-contain mx-auto" />
        </div>

        <div class="p-4 sm:p-5 bg-brand-deep-burgundy text-brand-cream flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-t border-brand-royal-rose">
            <div>
                <h4 class="font-serif text-lg font-bold text-brand-gold-light">{{ $decoration->name }}</h4>
                <p id="lightbox-full-caption" class="text-xs text-brand-cream/80 mt-0.5">{{ $decoration->tagline ?? $decoration->description }}</p>
            </div>
            <span class="text-xs px-3 py-1 rounded bg-brand-burgundy border border-brand-gold/30 text-brand-cream font-medium whitespace-nowrap">
                <i class="fas fa-map-marker-alt text-brand-gold mr-1"></i> {{ $decoration->location }}
            </span>
        </div>
    </div>
</div>

<script>
    function switchMainGalleryImage(src, caption, btn) {
        const mainImg = document.getElementById('gallery-main-display');
        const capEl = document.getElementById('gallery-current-caption');
        const lbImg = document.getElementById('lightbox-full-img');
        const lbCap = document.getElementById('lightbox-full-caption');

        if (mainImg) {
            mainImg.style.opacity = '0.3';
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 120);
        }
        if (capEl && caption) capEl.textContent = caption;
        if (lbImg) lbImg.src = src;
        if (lbCap && caption) lbCap.textContent = caption;

        // Update active border on thumbnails
        document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
            b.classList.remove('border-brand-gold', 'ring-2', 'ring-brand-gold/40');
            b.classList.add('border-brand-light-border');
        });
        if (btn) {
            btn.classList.remove('border-brand-light-border');
            btn.classList.add('border-brand-gold', 'ring-2', 'ring-brand-gold/40');
        }
    }

    function openImageLightbox() {
        const modal = document.getElementById('detail-lightbox');
        const mainImg = document.getElementById('gallery-main-display');
        const lbImg = document.getElementById('lightbox-full-img');
        if (modal && mainImg && lbImg) {
            lbImg.src = mainImg.src;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeImageLightbox() {
        const modal = document.getElementById('detail-lightbox');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
</script>
