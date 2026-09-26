@props(['video'])

<a 
    href="{{ route('videos.show', $video->slug) }}" 
    class="group relative block aspect-[9/16] w-[250px] min-w-[240px] max-w-[280px] sm:w-full sm:min-w-0 sm:max-w-none rounded-2xl overflow-hidden bg-stone-900 border border-brand-gold/30 shadow-soft-luxury hover:shadow-card-hover transition-all duration-500 transform hover:-translate-y-1.5 shrink-0 snap-start"
    aria-label="Watch {{ $video->title }} wedding decoration reel"
>
    <!-- Video Thumbnail Poster -->
    <img 
        src="{{ $video->safe_thumbnail_url }}" 
        alt="{{ $video->title }} - Traditional Bihar wedding decoration setup by Aditya Utsav" 
        class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out opacity-90"
        loading="lazy"
        onerror="this.src='https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=600&q=80'"
    />

    <!-- Multi-tier Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-black/40 group-hover:from-brand-deep-burgundy/95 group-hover:via-black/30 transition-colors duration-500"></div>

    <!-- Top Badges Row -->
    <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
        <!-- Event Type Badge -->
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-brand-deep-burgundy/90 border border-brand-gold/50 text-brand-gold-light text-[10px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-gold animate-pulse"></span>
            {{ $video->event_type_label }}
        </span>

        <!-- Duration Pill -->
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-black/60 border border-white/20 text-white text-[10px] font-mono font-semibold backdrop-blur-sm">
            <i class="fas fa-play text-[8px] text-brand-gold"></i>
            {{ $video->formatted_duration }}
        </span>
    </div>

    <!-- Center Play Icon with Glow -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-brand-gold/90 text-brand-deep-burgundy flex items-center justify-center shadow-gold-glow group-hover:scale-115 group-hover:bg-brand-gold transition-all duration-300 backdrop-blur-sm pl-0.5">
            <i class="fas fa-play text-base sm:text-xl"></i>
        </div>
    </div>

    <!-- Bottom Content Overlay -->
    <div class="absolute bottom-0 inset-x-0 p-3.5 sm:p-5 text-white pointer-events-none">
        <!-- Location -->
        <div class="flex items-center gap-1 text-[10px] sm:text-[11px] text-brand-gold-light font-medium mb-1">
            <i class="fas fa-map-marker-alt text-[9px] sm:text-[10px]"></i>
            <span>{{ $video->location }}</span>
        </div>

        <!-- Title -->
        <h3 class="font-serif text-sm sm:text-lg font-bold text-white group-hover:text-brand-gold-light transition-colors line-clamp-2 leading-snug">
            {{ $video->title }}
        </h3>

        @if($video->short_description)
            <p class="text-[10px] sm:text-[11px] text-stone-300 mt-1 line-clamp-1 opacity-90">
                {{ $video->short_description }}
            </p>
        @endif

        <!-- Watch Reel CTA hint -->
        <div class="mt-2 pt-1.5 border-t border-white/15 flex items-center justify-between text-[9px] sm:text-[10px] uppercase font-bold tracking-wider text-brand-gold-light">
            <span>Watch Transformation</span>
            <i class="fas fa-arrow-right text-[8px] sm:text-[9px] transform group-hover:translate-x-1 transition-transform"></i>
        </div>
    </div>
</a>
