@extends('layouts.app')

@section('title', 'Aditya Utsav | Bihar Wedding Decoration & Event Services')
@section('meta_description', 'Aditya Utsav provides authentic traditional and modern wedding decorations for Jaimala, Mandap, Haldi, Mehendi, Sangeet and Reception celebrations across Siwan, Gopalganj, Chapra and nearby Bihar areas.')

@section('content')

<!-- ======================================================= -->
<!-- 1. OPTIMIZED HERO SECTION & COMPACT AVAILABILITY CHECKER -->
<!-- ======================================================= -->
<section class="relative bg-brand-deep-burgundy text-white overflow-hidden scroll-reveal">
    @php
        $heroBanner = \App\Models\SiteSetting::getSafeImage('hero_banner');
    @endphp
    <!-- Stage Background with Overlay -->
    <div class="absolute inset-0 z-0">
        @if($heroBanner)
            <img 
                src="{{ $heroBanner }}" 
                alt="Bihar wedding Jaimala stage and floral mandap decoration by Aditya Utsav" 
                class="w-full h-full object-cover object-center opacity-25 scale-105 transform duration-10000"
                loading="eager"
            />
        @endif
        <!-- Multi-layer gradient overlays to maintain deep burgundy luxury feel -->
        <div class="absolute inset-0 bg-gradient-to-r from-brand-deep-burgundy via-brand-burgundy/90 to-brand-deep-burgundy/80"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-brand-deep-burgundy via-transparent to-black/40"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12 sm:pt-14 sm:pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-8 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-4 sm:space-y-5 text-center lg:text-left">
                <!-- Eyebrow -->
                <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 py-1 rounded-full bg-brand-gold/15 border border-brand-gold/40 text-brand-gold-light text-[10px] sm:text-xs font-bold uppercase tracking-wider sm:tracking-[0.2em] shadow-sm">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-brand-gold animate-ping"></span>
                    BIHAR WEDDING DECORATION &amp; EVENT SERVICES
                </div>

                <!-- Main Heading -->
                <h1 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight sm:leading-[1.15]">
                    Make Your Wedding <br class="hidden sm:inline" />
                    <span class="gold-shimmer font-bold">Celebration Beautiful</span>
                </h1>

                <!-- Supporting Text -->
                <p class="text-xs sm:text-sm lg:text-base text-brand-cream/90 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                    Authentic traditional and modern wedding decorations crafted with love in Siwan. From grand floral Jaimala stages and sacred Vedic Vivah Mandap to joyful yellow Haldi and royal reception setups across Bihar.
                </p>

                <!-- Concise Hero CTAs -->
                <div class="pt-1 flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-2.5 sm:gap-3">
                    <a href="#ceremony-categories" class="px-5 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-deep-burgundy bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold rounded border border-brand-gold shadow-md hover:shadow-gold-glow transition-all duration-300 text-center">
                        <i class="fas fa-th-large mr-1.5"></i>
                        Explore Decorations
                    </a>
                    <a href="{{ route('quote') }}" class="px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-white/10 hover:bg-white/20 rounded border border-brand-gold/50 backdrop-blur-sm transition-all duration-300 text-center">
                        <i class="fas fa-file-invoice-dollar mr-1.5 text-brand-gold"></i>
                        Get a Custom Quote
                    </a>
                </div>

                <!-- Trust Points / Bihar Highlights -->
                <div class="pt-3 border-t border-brand-royal-rose/40 grid grid-cols-3 gap-2 sm:gap-3 max-w-md mx-auto lg:mx-0 text-center">
                    <div>
                        <span class="block font-serif text-xl sm:text-2xl font-bold text-brand-gold">12+</span>
                        <span class="text-[9px] sm:text-xs text-brand-cream/80 uppercase tracking-wider">Years in Bihar</span>
                    </div>
                    <div>
                        <span class="block font-serif text-xl sm:text-2xl font-bold text-brand-gold">650+</span>
                        <span class="text-[9px] sm:text-xs text-brand-cream/80 uppercase tracking-wider">Auspicious Vivahs</span>
                    </div>
                    <div>
                        <span class="block font-serif text-xl sm:text-2xl font-bold text-brand-gold">100%</span>
                        <span class="text-[9px] sm:text-xs text-brand-cream/80 uppercase tracking-wider">On-Time Setup</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card: Compact Availability Checker -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-2xl border-2 border-brand-gold relative overflow-hidden text-brand-charcoal">
                    <div class="mb-3 sm:mb-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-brand-burgundy block mb-0.5">
                            Lagan Season Dates
                        </span>
                        <h2 class="font-serif text-lg sm:text-2xl font-bold text-brand-charcoal leading-snug">
                            Check Decoration Availability
                        </h2>
                        <p class="text-[11px] sm:text-xs text-brand-muted-brown mt-0.5">
                            Serving Siwan, Gopalganj, Chapra &amp; nearby Bihar areas.
                        </p>
                    </div>

                    <!-- Quick Availability Checker Form -->
                    <form id="hero-checker-form" method="GET" action="{{ route('booking.availability') }}" class="space-y-2.5 sm:space-y-3">
                        <div>
                            <label for="hero_event_type" class="block text-[11px] sm:text-xs font-semibold text-brand-charcoal mb-1">
                                Ceremony / Event Type
                            </label>
                            <div class="relative">
                                <select id="hero_event_type" name="event_type" class="w-full min-w-0 max-w-full px-3 py-2 text-xs sm:text-sm bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy appearance-none">
                                    <option value="Wedding & Vivah">Wedding &amp; Vedic Vivah Mandap</option>
                                    <option value="Jaimala / Varmala">Jaimala / Varmala Stage</option>
                                    <option value="Haldi Ceremony">Haldi Ceremony Setup</option>
                                    <option value="Mehendi Ceremony">Mehendi Courtyard Setup</option>
                                    <option value="Sangeet Night">Sangeet Night Stage &amp; Lighting</option>
                                    <option value="Reception">Grand Wedding Reception</option>
                                    <option value="Complete Wedding Package">Complete Shubh Vivah Package</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-3 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="hero_city" class="block text-[11px] sm:text-xs font-semibold text-brand-charcoal mb-1">
                                City / District (Bihar &amp; UP)
                            </label>
                            <div class="relative">
                                <select id="hero_city" name="city" class="w-full min-w-0 max-w-full px-3 py-2 text-xs sm:text-sm bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy appearance-none">
                                    <option value="Siwan">Siwan (Primary Hub)</option>
                                    <option value="Mairwa">Mairwa</option>
                                    <option value="Gopalganj">Gopalganj</option>
                                    <option value="Chapra">Chapra / Saran</option>
                                    <option value="Barharia">Barharia</option>
                                    <option value="Maharajganj">Maharajganj</option>
                                    <option value="Patna">Patna</option>
                                    <option value="Gorakhpur">Gorakhpur (UP)</option>
                                    <option value="Deoria">Deoria (UP)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-3 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="hero_event_date" class="block text-[11px] sm:text-xs font-semibold text-brand-charcoal mb-1">
                                Event Date
                            </label>
                            <input 
                                type="date" 
                                id="hero_event_date" 
                                name="event_date" 
                                min="{{ date('Y-m-d') }}" 
                                value="{{ date('Y-m-d', strtotime('+14 days')) }}"
                                required 
                                class="w-full min-w-0 max-w-full px-3 py-2 text-xs sm:text-sm bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy"
                            />
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="w-full py-2.5 px-4 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-md hover:shadow-gold-glow transition-all duration-200 cursor-pointer">
                                <i class="fas fa-calendar-check mr-1.5 text-brand-gold"></i>
                                Check Availability Now
                            </button>
                        </div>
                    </form>

                    <div class="mt-3 pt-2.5 border-t border-brand-light-border/70 flex flex-wrap items-center justify-between gap-1 text-[10px] sm:text-[11px] text-brand-muted-brown">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-phone-alt text-brand-gold"></i>
                            Direct: {{ $settings['contact_phone'] ?? '+91 98765 43210' }}
                        </span>
                        <span class="text-brand-burgundy font-semibold">
                            Fast Response
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ======================================================= -->
<!-- 2. CEREMONY CATEGORIES (COMPACT 6-GRID) -->
<!-- ======================================================= -->
<section id="ceremony-categories" class="py-12 sm:py-16 bg-brand-cream relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading 
            eyebrow="TRADITIONAL BIHAR WEDDINGS"
            title="Decorations for Every Ceremony"
            subtitle="Authentic floral setups, sacred mandaps, and festive stages crafted for each cherished ritual of a Bihar wedding celebration."
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($categories->take(6) as $category)
                <x-category-card :category="$category" />
            @endforeach
        </div>

        <div class="mt-8 sm:mt-10 text-center">
            <a href="{{ route('decorations.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-burgundy bg-white hover:bg-brand-offwhite rounded-xl border border-brand-light-border shadow-soft-luxury hover:border-brand-gold transition-all">
                <span>View All Ceremony Categories</span>
                <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
            </a>
        </div>
    </div>
</section>


<!-- ======================================================= -->
<!-- 3. FEATURED WEDDING DECORATIONS -->
<!-- ======================================================= -->
@if($featuredDecorations->count() > 0)
<section class="py-12 sm:py-16 bg-white relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading 
            eyebrow="HANDPICKED DESIGNS"
            title="Featured Wedding Setups"
            subtitle="Explore our most celebrated stage backdrops, royal mandaps, and floral themes preferred by families across Bihar."
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($featuredDecorations->take(6) as $decoration)
                <x-decoration-card :decoration="$decoration" />
            @endforeach
        </div>

        <div class="mt-8 sm:mt-10 text-center">
            <a href="{{ route('decorations.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all">
                <span>Explore All Decorations</span>
                <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
            </a>
        </div>
    </div>
</section>
@endif


<!-- ======================================================= -->
<!-- 4. REAL WEDDINGS SHORT VIDEOS / REELS (PHASE 8 FEATURE) -->
<!-- ======================================================= -->
@if($homepageReels->count() > 0)
<section class="py-12 sm:py-16 bg-gradient-to-b from-stone-950 via-stone-900 to-stone-950 text-white relative overflow-hidden scroll-mt-20 scroll-reveal">
    <!-- Subtle Background Gold Grid Accent -->
    <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:24px_24px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 sm:mb-8 gap-3 sm:gap-4">
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-gold/15 border border-brand-gold/40 text-brand-gold-light text-[10px] sm:text-[11px] font-bold uppercase tracking-widest mb-2">
                    <i class="fas fa-play-circle text-brand-gold"></i>
                    REAL WEDDINGS. REAL TRANSFORMATIONS.
                </span>
                <h2 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight">
                    Watch Decoration Highlights &amp; Reels
                </h2>
                <p class="text-xs sm:text-sm text-stone-300 mt-1 max-w-xl">
                    See our latest real wedding decoration setups and venue transformations across Bihar in quick short videos.
                </p>
            </div>
            
            <a href="{{ route('videos.index') }}" class="hidden sm:inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-gold-light hover:text-white transition-colors shrink-0">
                <span>Watch All Reels</span>
                <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
            </a>
        </div>

        <!-- Reels Cards Horizontal Container / Mobile Swipeable Carousel -->
        <div class="flex overflow-x-auto snap-x snap-mandatory gap-3 sm:gap-6 pb-4 pt-1 no-scrollbar sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:overflow-visible">
            @foreach($homepageReels->take(4) as $reel)
                <x-reel-card :video="$reel" />
            @endforeach
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('videos.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-stone-900 bg-brand-gold hover:bg-brand-gold-light rounded-xl transition shadow">
                <span>Explore All Video Reels &rarr;</span>
            </a>
        </div>
    </div>
</section>
@endif


<!-- ======================================================= -->
<!-- 5. CURATED WEDDING PACKAGES (COMPACT 3-GRID) -->
<!-- ======================================================= -->
@if($packages->count() > 0)
<section id="wedding-packages" class="py-12 sm:py-16 bg-brand-offwhite relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading 
            eyebrow="COMPLETE VIVAH SOLUTIONS"
            title="All-in-One Wedding Packages"
            subtitle="Curated ceremony packages covering Haldi, Mehendi, Sangeet, Vedic Mandap, and Grand Receptions with dedicated on-site management."
        />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 items-stretch">
            @foreach($packages->take(3) as $index => $pkg)
                <x-package-card :package="$pkg" :featured="$index === 0" />
            @endforeach
        </div>

        <div class="mt-8 sm:mt-10 text-center">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-burgundy bg-white hover:bg-brand-offwhite rounded-xl border border-brand-light-border shadow-soft-luxury hover:border-brand-gold transition-all">
                <span>View All Wedding Packages</span>
                <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
            </a>
        </div>
    </div>
</section>
@endif


<!-- ======================================================= -->
<!-- 6. WHY CHOOSE ADITYA UTSAV (TRUST & VALUES) -->
<!-- ======================================================= -->
<section class="py-12 sm:py-16 bg-brand-cream relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading 
            eyebrow="THE ADITYA UTSAV PROMISE"
            title="Why Families Trust Aditya Utsav"
            subtitle="Dedicated to authentic traditions, exquisite floral artistry, and flawless execution for auspicious wedding milestones."
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-brand-light-border shadow-soft-luxury text-center">
                <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fas fa-map-marked-alt text-brand-gold"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal mb-1">Local Bihar Expertise</h3>
                <p class="text-xs text-brand-muted-brown leading-relaxed">
                    Rooted in Siwan with deep familiarity of local wedding traditions, venue requirements, and family rituals.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-brand-light-border shadow-soft-luxury text-center">
                <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fas fa-spa text-brand-gold"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal mb-1">Fresh Farm Florals</h3>
                <p class="text-xs text-brand-muted-brown leading-relaxed">
                    Daily sourced fresh Marigold, Rajnigandha, Orchids, and Red Roses arranged with perfection.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-brand-light-border shadow-soft-luxury text-center">
                <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fas fa-clock text-brand-gold"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal mb-1">100% On-Time Setup</h3>
                <p class="text-xs text-brand-muted-brown leading-relaxed">
                    Committed on-site decor team ensuring stage and mandap readiness hours before the muhurat.
                </p>
            </div>

            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-brand-light-border shadow-soft-luxury text-center">
                <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fas fa-hand-holding-usd text-brand-gold"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal mb-1">Transparent Pricing</h3>
                <p class="text-xs text-brand-muted-brown leading-relaxed">
                    Clear itemized quotations, official receipts, and zero hidden charges for complete peace of mind.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ======================================================= -->
<!-- 7. SMALL PHOTO GALLERY PREVIEW (6 PHOTOS) -->
<!-- ======================================================= -->
@if($galleryItems->count() > 0)
<section id="bihar-wedding-gallery" class="py-12 sm:py-16 bg-white border-t border-b border-brand-light-border relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-section-heading 
            eyebrow="REAL CELEBRATIONS"
            title="Wedding Moments Gallery"
            subtitle="A visual showcase of authentic floral stages, traditional Vedic mandaps, Haldi urlis, and festive entrances."
        />

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-4">
            @foreach($galleryItems->take(6) as $item)
                <a href="{{ route('gallery.index') }}" class="group relative block aspect-square rounded-xl overflow-hidden bg-brand-offwhite shadow-sm hover:shadow-card-hover transition-all flex items-center justify-center">
                    @if($item->display_image)
                        <img 
                            src="{{ $item->display_image }}" 
                            alt="{{ $item->title }} - Bihar Wedding Decoration by Aditya Utsav" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                        />
                    @else
                        <div class="p-3 text-center text-brand-muted-brown flex flex-col items-center justify-center">
                            <i class="fas fa-camera text-brand-gold text-lg mb-1"></i>
                            <span class="text-[9px] font-semibold uppercase tracking-wider">{{ $item->title }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2 sm:p-2.5">
                        <span class="text-white text-[10px] sm:text-[11px] font-semibold truncate">{{ $item->title }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6 sm:mt-8 text-center">
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-burgundy hover:text-brand-deep-burgundy bg-brand-gold/15 hover:bg-brand-gold/25 rounded-xl border border-brand-gold transition">
                <span>View Full Photo &amp; Video Gallery</span>
                <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
            </a>
        </div>
    </div>
</section>
@endif


<!-- ======================================================= -->
<!-- 8. COMPACT SERVICE AREA PREVIEW -->
<!-- ======================================================= -->
<section class="py-12 sm:py-16 bg-brand-cream relative scroll-mt-20 scroll-reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-5 sm:p-8 border border-brand-light-border shadow-soft-luxury flex flex-col md:flex-row items-center justify-between gap-5 sm:gap-6">
            <div class="space-y-2 text-center md:text-left">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-brand-burgundy block">
                    SERVICE COVERAGE ACROSS BIHAR &amp; UP
                </span>
                <h3 class="font-serif text-xl sm:text-3xl font-bold text-brand-charcoal">
                    Serving Siwan, Gopalganj, Chapra &amp; Nearby Districts
                </h3>
                <p class="text-xs sm:text-sm text-brand-muted-brown max-w-xl">
                    Dedicated decoration fleet and setup crews available for venues across Siwan, Mairwa, Gopalganj, Chapra, Barharia, Maharajganj, Patna, and nearby Eastern UP towns.
                </p>
                <div class="flex flex-wrap gap-1.5 pt-1 justify-center md:justify-start">
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Siwan</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Mairwa</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Gopalganj</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Chapra</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Barharia</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Patna</span>
                    <span class="px-2.5 py-1 rounded-md bg-brand-offwhite text-brand-charcoal text-[10px] sm:text-[11px] font-medium border border-brand-light-border">Gorakhpur (UP)</span>
                </div>
            </div>

            <div class="shrink-0 w-full sm:w-auto text-center">
                <a href="{{ route('service-areas.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition w-full sm:w-auto">
                    <span>View All Service Areas</span>
                    <i class="fas fa-arrow-right text-xs text-brand-gold"></i>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ======================================================= -->
<!-- 9. CONCISE FAQS PREVIEW (3 IMPORTANT QUESTIONS) -->
<!-- ======================================================= -->
@if($faqs->count() > 0)
<section class="py-12 sm:py-16 bg-white border-t border-brand-light-border relative scroll-mt-20 scroll-reveal">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6 sm:mb-8">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-burgundy block mb-1">FREQUENTLY ASKED QUESTIONS</span>
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">Have Questions About Your Wedding Decor?</h2>
            <p class="text-xs text-brand-muted-brown mt-1">Quick answers to common questions regarding booking, availability, and customizations.</p>
        </div>

        <div class="space-y-3">
            @foreach($faqs->take(3) as $faq)
                <div class="bg-brand-offwhite rounded-xl p-3.5 sm:p-5 border border-brand-light-border/80">
                    <button 
                        type="button" 
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.faq-chevron').classList.toggle('rotate-180');" 
                        class="w-full flex items-center justify-between text-left font-serif text-sm sm:text-base font-bold text-brand-charcoal hover:text-brand-burgundy focus:outline-none transition-colors"
                    >
                        <span class="pr-2">{{ $faq->question }}</span>
                        <i class="fas fa-chevron-down faq-chevron text-xs text-brand-gold transition-transform duration-200 shrink-0"></i>
                    </button>
                    <div class="mt-2.5 pt-2.5 border-t border-brand-light-border/50 text-xs sm:text-sm text-brand-muted-brown leading-relaxed hidden">
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-brand-burgundy hover:text-brand-royal-rose transition">
                <span>View More FAQs &amp; Helpful Guides &rarr;</span>
            </a>
        </div>
    </div>
</section>
@endif


<!-- ======================================================= -->
<!-- 10. FINAL CONVERSION CTA BANNER -->
<!-- ======================================================= -->
<section class="py-12 sm:py-16 bg-gradient-to-r from-brand-deep-burgundy via-brand-burgundy to-brand-deep-burgundy text-brand-cream border-t-2 border-brand-gold relative overflow-hidden scroll-mt-20 scroll-reveal">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-4">
        <span class="inline-block text-[10px] sm:text-xs font-bold tracking-[0.2em] text-brand-gold-light uppercase">
            PLANNING A WEDDING THIS SEASON?
        </span>

        <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight">
            Let's Make Your Wedding Celebration Grand &amp; Unforgettable
        </h2>

        <p class="text-xs sm:text-sm lg:text-base text-brand-cream/90 max-w-2xl mx-auto leading-relaxed">
            Reserve your auspicious wedding dates early. Speak directly with our master decorator or request an instant customized quotation.
        </p>

        <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button 
                type="button" 
                onclick="openAvailabilityModal()" 
                class="w-full sm:w-auto px-6 py-3.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-deep-burgundy bg-gradient-to-r from-brand-gold via-brand-gold-light to-brand-gold rounded border border-brand-gold shadow-lg hover:shadow-gold-glow transition-all"
            >
                <i class="fas fa-calendar-check mr-2"></i>
                Check Date Availability
            </button>

            <a 
                href="https://wa.me/919876543210?text=Hello%20Aditya%20Utsav,%20I%20would%20like%20to%20inquire%20about%20wedding%20decoration%20in%20Bihar." 
                target="_blank" 
                class="w-full sm:w-auto px-6 py-3.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-emerald-700 hover:bg-emerald-600 rounded border border-emerald-500 shadow-md transition-all flex items-center justify-center gap-2"
            >
                <i class="fab fa-whatsapp text-base"></i>
                <span>Chat on WhatsApp</span>
            </a>

            <a 
                href="{{ route('contact') }}" 
                class="w-full sm:w-auto px-5 py-3.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-white/10 hover:bg-white/20 rounded border border-brand-gold/40 transition-all flex items-center justify-center gap-2"
            >
                <i class="fas fa-envelope text-brand-gold"></i>
                <span>Contact Office</span>
            </a>
        </div>
    </div>
</section>

@endsection
