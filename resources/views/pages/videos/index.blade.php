@extends('layouts.app')

@section('title', 'Wedding Reels & Short Videos | Real Transformations - Aditya Utsav')
@section('meta_description', 'Watch real wedding decoration videos and transformation reels by Aditya Utsav across Bihar. Explore Jaimala stage setup, Vedic Vivah Mandap, Haldi, Mehendi, and Reception highlights in Siwan.')

@section('content')
<!-- Top Hero Banner -->
<section class="bg-gradient-to-r from-brand-deep-burgundy via-brand-burgundy to-brand-deep-burgundy text-white py-12 sm:py-16 border-b border-brand-gold/30 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-gold/15 border border-brand-gold/40 text-brand-gold-light text-xs font-bold uppercase tracking-widest mb-3">
            <i class="fas fa-play text-brand-gold text-[10px]"></i>
            REAL WEDDING FOOTAGE
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight">
            Wedding Reels &amp; Transformations
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/90 max-w-2xl mx-auto mt-2 leading-relaxed">
            Experience our real decoration setups, stage lighting, floral mandap installations, and behind-the-scenes transformations across Siwan and Bihar.
        </p>
    </div>
</section>

<!-- Filter Navigation Bar -->
<section class="bg-white border-b border-brand-light-border sticky top-16 sm:top-20 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <div class="flex items-center justify-start sm:justify-center overflow-x-auto gap-2 no-scrollbar py-0.5">
            <a href="{{ route('videos.index') }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'all' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                All Ceremonies
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'jaimala']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'jaimala' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Jaimala Stage
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'mandap']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'mandap' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Vedic Mandap
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'haldi']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'haldi' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Haldi Ceremony
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'mehendi']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'mehendi' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Mehendi Courtyard
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'sangeet']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'sangeet' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Sangeet Night
            </a>
            <a href="{{ route('videos.index', ['event_type' => 'reception']) }}" class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition shrink-0 {{ $activeEventType === 'reception' ? 'bg-brand-burgundy text-white border border-brand-gold shadow-sm' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy border border-brand-light-border' }}">
                Reception Stage
            </a>
        </div>
    </div>
</section>

<!-- Reels Grid Showcase -->
<section class="py-12 sm:py-16 bg-brand-cream min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($videos->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center max-w-lg mx-auto border border-brand-light-border shadow-soft-luxury">
                <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fas fa-video-slash"></i>
                </div>
                <h3 class="font-serif text-xl font-bold text-brand-charcoal mb-1">No videos found for this filter</h3>
                <p class="text-xs text-brand-muted-brown mb-6">Explore other ceremony categories or watch all available wedding reels.</p>
                <a href="{{ route('videos.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-brand-burgundy text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-brand-deep-burgundy transition">
                    View All Reels
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($videos as $video)
                    <x-reel-card :video="$video" />
                @endforeach
            </div>

            @if($videos->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $videos->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
