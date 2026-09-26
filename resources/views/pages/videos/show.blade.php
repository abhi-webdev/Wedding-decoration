@extends('layouts.app')

@section('title', $video->title . ' - Wedding Video Reel | Aditya Utsav')
@section('meta_description', $video->short_description ?: 'Watch ' . $video->title . ' wedding decoration setup in ' . $video->location . ' by Aditya Utsav.')

@section('og_title', $video->title . ' | Aditya Utsav Bihar Wedding Decor')
@section('og_description', $video->short_description ?: 'Explore this authentic Bihar wedding decoration setup by Aditya Utsav.')
@section('og_image', $video->safe_thumbnail_url)

@section('content')
<!-- Breadcrumbs -->
<div class="bg-brand-offwhite border-b border-brand-light-border/70 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-muted-brown flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-burgundy transition">Home</a>
        <i class="fas fa-chevron-right text-[9px] text-gray-400"></i>
        <a href="{{ route('videos.index') }}" class="hover:text-brand-burgundy transition">Reels &amp; Videos</a>
        <i class="fas fa-chevron-right text-[9px] text-gray-400"></i>
        <span class="text-brand-charcoal font-semibold truncate">{{ $video->title }}</span>
    </div>
</div>

<div class="bg-brand-cream py-10 sm:py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Main Video Presentation Grid (Player + Meta & Booking Card) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left 7/12: Video Player -->
            <div class="lg:col-span-7 bg-black rounded-3xl overflow-hidden shadow-2xl border-2 border-brand-gold/40 relative">
                <div class="relative aspect-[9/16] sm:aspect-video lg:aspect-[4/3] max-h-[620px] w-full bg-stone-950 flex items-center justify-center">
                    <video 
                        controls 
                        playsinline 
                        preload="metadata"
                        poster="{{ $video->safe_thumbnail_url }}"
                        class="w-full h-full object-contain"
                    >
                        <source src="{{ $video->safe_video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>

            <!-- Right 5/12: Video Info & Direct Booking Card -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Metadata Card -->
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-brand-light-border shadow-soft-luxury space-y-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-md bg-brand-deep-burgundy text-brand-gold-light text-xs font-bold uppercase tracking-wider">
                            {{ $video->event_type_label }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-xs font-medium border border-brand-light-border flex items-center gap-1">
                            <i class="fas fa-map-marker-alt text-brand-gold text-[10px]"></i>
                            {{ $video->location }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md bg-stone-100 text-stone-600 text-xs font-mono font-medium">
                            <i class="far fa-clock text-xs mr-0.5"></i> {{ $video->formatted_duration }}
                        </span>
                    </div>

                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal leading-tight">
                        {{ $video->title }}
                    </h1>

                    @if($video->short_description)
                        <p class="text-xs sm:text-sm text-brand-burgundy font-medium leading-relaxed">
                            {{ $video->short_description }}
                        </p>
                    @endif

                    @if($video->description)
                        <div class="pt-3 border-t border-brand-light-border/60 text-xs sm:text-sm text-brand-muted-brown leading-relaxed space-y-2">
                            <p>{{ $video->description }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="pt-4 border-t border-brand-light-border/60 space-y-2.5">
                        <button 
                            type="button" 
                            onclick="openAvailabilityModal('{{ $video->event_type_label }}', '{{ $video->location }}')"
                            class="w-full py-3 px-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-calendar-check text-brand-gold"></i>
                            Check Date Availability
                        </button>

                        <a 
                            href="{{ route('quote') }}" 
                            class="w-full py-3 px-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-burgundy bg-brand-gold/15 hover:bg-brand-gold/25 rounded-xl border border-brand-gold transition flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-file-invoice-dollar text-brand-gold"></i>
                            Get a Custom Quote for This Setup
                        </a>

                        @if($video->category)
                            <a 
                                href="{{ route('decorations.category', $video->category->slug) }}" 
                                class="w-full py-2.5 text-center text-xs font-semibold text-brand-charcoal hover:text-brand-burgundy block transition"
                            >
                                Browse All {{ $video->category->name }} Designs &rarr;
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Trust Guarantee Box -->
                <div class="bg-brand-offwhite p-4 rounded-xl border border-brand-light-border text-xs space-y-1.5 text-brand-muted-brown">
                    <p class="font-bold text-brand-charcoal flex items-center gap-1.5">
                        <i class="fas fa-shield-alt text-brand-gold text-sm"></i>
                        The Aditya Utsav Standard
                    </p>
                    <p>All decoration setups shown in our reels are executed with 100% fresh flowers, customized stage lighting, and professional on-site decor management across Bihar.</p>
                </div>
            </div>

        </div>

        <!-- Related Decorations from this Category -->
        @if($relatedDecorations->isNotEmpty())
            <div class="space-y-6 pt-6 border-t border-brand-light-border/80">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-burgundy block">MATCHING THEMES</span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">Related Decoration Designs</h2>
                    </div>
                    <a href="{{ route('decorations.index') }}" class="text-xs font-bold text-brand-burgundy hover:underline">
                        View All Designs &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedDecorations as $decoration)
                        <x-decoration-card :decoration="$decoration" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Video Reels -->
        @if($relatedVideos->isNotEmpty())
            <div class="space-y-6 pt-6 border-t border-brand-light-border/80">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-burgundy block">MORE INSPIRATION</span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">Explore More Wedding Reels</h2>
                    </div>
                    <a href="{{ route('videos.index') }}" class="text-xs font-bold text-brand-burgundy hover:underline">
                        Watch All Reels &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($relatedVideos as $relVideo)
                        <x-reel-card :video="$relVideo" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
