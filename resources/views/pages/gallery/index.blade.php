@extends('layouts.app')

@section('title', 'Bihar Wedding Photo Gallery | Aditya Utsav')
@section('meta_description', 'Visual gallery of authentic Bihar wedding decorations, Jaimala stages, Vedic Mandaps, Haldi urlis and receptions in Siwan, Gopalganj and Chapra.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            AUTHENTIC VISUAL ARCHIVE
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Bihar Wedding Photo Gallery
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Real celebration photography featuring traditional floral canopies, Jaimala stages, Vedic mandaps, and festive Haldi courtyards.
        </p>
    </div>
</section>

<section class="py-12 bg-brand-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <a href="{{ route('gallery.index') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full {{ $category === 'all' ? 'bg-brand-burgundy text-brand-cream border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }} shadow-sm transition-all">
                All Photos
            </a>
            @foreach(['Jaimala', 'Mandap', 'Haldi', 'Mehendi', 'Sangeet', 'Reception', 'Baraat', 'Entrance'] as $catName)
                <a href="{{ route('gallery.index', ['category' => $catName]) }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full {{ $category === $catName ? 'bg-brand-burgundy text-brand-cream border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }} shadow-sm transition-all">
                    {{ $catName }}
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($galleryItems as $item)
                <x-gallery-card :item="$item" />
            @endforeach
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="gallery-lightbox" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4" onclick="closeGalleryPreview()">
    <div class="relative max-w-4xl w-full bg-brand-charcoal rounded-xl overflow-hidden border border-brand-gold shadow-2xl" onclick="event.stopPropagation()">
        <button type="button" onclick="closeGalleryPreview()" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/70 text-white hover:text-brand-gold flex items-center justify-center">
            <i class="fas fa-times text-base"></i>
        </button>
        <img id="lightbox-img" src="" alt="Gallery Preview" class="w-full max-h-[70vh] object-contain bg-black" />
        <div class="p-5 bg-brand-charcoal text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-t border-gray-700">
            <div>
                <h4 id="lightbox-title" class="font-serif text-lg font-bold text-brand-gold-light"></h4>
                <p id="lightbox-caption" class="text-xs text-brand-cream/80 mt-0.5"></p>
            </div>
            <span id="lightbox-location" class="text-xs px-2.5 py-1 rounded bg-brand-burgundy text-white font-medium"></span>
        </div>
    </div>
</div>

<script>
    function openGalleryPreview(imageUrl, title, caption, location) {
        const modal = document.getElementById('gallery-lightbox');
        const img = document.getElementById('lightbox-img');
        const titleEl = document.getElementById('lightbox-title');
        const capEl = document.getElementById('lightbox-caption');
        const locEl = document.getElementById('lightbox-location');

        if (!modal || !img) return;

        img.src = imageUrl;
        titleEl.textContent = title;
        capEl.textContent = caption;
        locEl.textContent = location;

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeGalleryPreview() {
        const modal = document.getElementById('gallery-lightbox');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endsection
