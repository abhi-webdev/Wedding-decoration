@extends('layouts.app')

@section('title', 'Bihar Wedding Decoration Portfolio | Photos & Reels - Aditya Utsav')
@section('meta_description', 'Explore real wedding decoration photographs and transformation video reels from Siwan, Gopalganj, Patna, and Gorakhpur. Traditional Jaimala stages, Vedic Mandap, Haldi, Mehendi, and Grand Receptions.')

@section('content')

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-18 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-camera-retro text-[10px]"></i> Real Wedding Portfolio
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Bihar Wedding Gallery &amp; Reels
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Authentic celebratory photographs and transformation video reels across Siwan, Gopalganj, Patna, and Eastern UP.
            </p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-10 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Media Type Switcher & Category Filter Pills Bar -->
            <div class="space-y-4 border-b border-brand-light-border pb-4">
                <!-- Media Type Tabs -->
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <a 
                        href="{{ route('gallery.index', ['type' => 'all', 'category' => $selectedCategorySlug]) }}" 
                        class="px-3.5 sm:px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all {{ ($mediaType === 'all' || empty($mediaType)) ? 'bg-brand-burgundy text-white shadow-md border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }}"
                    >
                        <i class="fas fa-layer-group mr-1.5 text-xs text-brand-gold"></i> All Media
                    </a>
                    <a 
                        href="{{ route('gallery.index', ['type' => 'photos', 'category' => $selectedCategorySlug]) }}" 
                        class="px-3.5 sm:px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all {{ ($mediaType === 'photos') ? 'bg-brand-burgundy text-white shadow-md border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }}"
                    >
                        <i class="fas fa-camera mr-1.5 text-xs text-brand-gold"></i> Photos
                    </a>
                    <a 
                        href="{{ route('gallery.index', ['type' => 'videos', 'category' => $selectedCategorySlug]) }}" 
                        class="px-3.5 sm:px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all {{ ($mediaType === 'videos' || $mediaType === 'reels') ? 'bg-brand-burgundy text-white shadow-md border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }}"
                    >
                        <i class="fas fa-play-circle mr-1.5 text-xs text-brand-gold"></i> Reels &amp; Videos
                    </a>
                </div>

                <!-- Ceremony Category Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none justify-start sm:justify-center">
                    <a 
                        href="{{ route('gallery.index', ['type' => $mediaType, 'category' => 'all']) }}" 
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ ($selectedCategorySlug === 'all' || empty($selectedCategorySlug)) ? 'bg-brand-gold text-brand-deep-burgundy font-bold shadow-sm' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }}"
                    >
                        All Categories
                    </a>

                    @foreach($categories as $cat)
                        <a 
                            href="{{ route('gallery.index', ['type' => $mediaType, 'category' => $cat->slug]) }}" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ ($selectedCategorySlug === $cat->slug) ? 'bg-brand-gold text-brand-deep-burgundy font-bold shadow-sm' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }}"
                        >
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Active Category Header -->
            @if($selectedCategory)
                <div class="p-4 rounded-2xl bg-white border border-brand-light-border flex items-center justify-between">
                    <div>
                        <h2 class="font-serif text-lg font-bold text-brand-charcoal">{{ $selectedCategory->name }}</h2>
                        <p class="text-xs text-brand-muted-brown">{{ $selectedCategory->description }}</p>
                    </div>
                    <a href="{{ route('gallery.index', ['type' => $mediaType]) }}" class="text-xs text-brand-royal-rose font-semibold hover:underline">
                        Show All &times;
                    </a>
                </div>
            @endif

            <!-- Video Reels Showcase (if any) -->
            @if(isset($videos) && $videos->isNotEmpty())
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-serif text-xl font-bold text-brand-charcoal flex items-center gap-2">
                            <i class="fas fa-play-circle text-brand-gold"></i>
                            Transformation Video Reels
                        </h3>
                        <a href="{{ route('videos.index') }}" class="text-xs font-bold text-brand-burgundy hover:underline">
                            View All Reels &rarr;
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($videos as $video)
                            <x-reel-card :video="$video" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Photos Grid -->
            @if(isset($galleryItems) && $galleryItems->count() > 0)
                <div class="space-y-4 pt-4">
                    @if(isset($videos) && $videos->isNotEmpty())
                        <h3 class="font-serif text-xl font-bold text-brand-charcoal flex items-center gap-2">
                            <i class="fas fa-camera text-brand-gold"></i>
                            Ceremony Photographs
                        </h3>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                        @foreach($galleryItems as $item)
                            <x-gallery-card :item="$item" />
                        @endforeach
                    </div>

                    @if(method_exists($galleryItems, 'hasPages') && $galleryItems->hasPages())
                        <div class="pt-6">
                            {{ $galleryItems->links() }}
                        </div>
                    @endif
                </div>
            @elseif(!isset($videos) || $videos->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border border-brand-light-border p-8 max-w-md mx-auto space-y-3 shadow-soft-luxury">
                    <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl">
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-brand-charcoal">Gallery items coming soon</h3>
                    <p class="text-xs text-brand-muted-brown">
                        We are currently updating our high-resolution wedding media for this category.
                    </p>
                    <a href="{{ route('gallery.index') }}" class="inline-block mt-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy rounded-xl">
                        View All Media
                    </a>
                </div>
            @endif

        </div>
    </section>

    <!-- Lightweight Vanilla JS Lightbox Modal for Photos -->
    <div id="gallery-lightbox" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4" onclick="closeGalleryLightbox()">
        <div class="relative max-w-4xl w-full bg-brand-charcoal rounded-2xl overflow-hidden border border-brand-gold/30 shadow-2xl" onclick="event.stopPropagation()">
            
            <!-- Close Button -->
            <button 
                type="button" 
                onclick="closeGalleryLightbox()" 
                class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-black/60 text-white hover:bg-brand-burgundy hover:text-brand-gold flex items-center justify-center transition-colors border border-white/20"
                aria-label="Close Lightbox"
            >
                <i class="fas fa-times text-base"></i>
            </button>

            <!-- Lightbox Image -->
            <div class="relative aspect-[16/10] bg-black flex items-center justify-center">
                <img id="lightbox-img" src="" alt="" class="max-h-full max-w-full object-contain mx-auto" />
            </div>

            <!-- Lightbox Caption & Info -->
            <div class="p-4 sm:p-6 bg-brand-charcoal text-white space-y-1.5 border-t border-white/10">
                <div class="flex items-center justify-between gap-4">
                    <span id="lightbox-category" class="text-[10px] uppercase font-bold text-brand-gold tracking-wider"></span>
                    <span id="lightbox-location" class="text-xs text-gray-300 font-medium"></span>
                </div>
                <h3 id="lightbox-title" class="font-serif text-lg sm:text-xl font-bold text-brand-cream"></h3>
                <p id="lightbox-caption" class="text-xs text-gray-300 leading-relaxed"></p>
                <div class="pt-2 flex items-center justify-end">
                    <a href="{{ route('quote') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold">
                        Request Similar Setup Quote
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Vanilla JavaScript for Gallery Lightbox -->
    <script>
        function openGalleryLightbox(imgSrc, title, caption, location, category) {
            const modal = document.getElementById('gallery-lightbox');
            document.getElementById('lightbox-img').src = imgSrc;
            document.getElementById('lightbox-img').alt = title;
            document.getElementById('lightbox-title').textContent = title;
            document.getElementById('lightbox-caption').textContent = caption || '';
            document.getElementById('lightbox-location').innerHTML = '<i class="fas fa-map-marker-alt text-brand-gold mr-1"></i>' + (location || 'Bihar');
            document.getElementById('lightbox-category').textContent = category || 'Wedding Decor';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGalleryLightbox() {
            const modal = document.getElementById('gallery-lightbox');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeGalleryLightbox();
            }
        });
    </script>

@endsection
