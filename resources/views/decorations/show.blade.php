@extends('layouts.app')

@section('title', $decoration->name . ' - ' . $decoration->category->name . ' Decoration in ' . $decoration->location . ' | Aditya Utsav Bihar')
@section('meta_description', $decoration->short_description ?? 'Book ' . $decoration->name . ' in ' . $decoration->location . ' with Aditya Utsav. Authentic Bihar wedding decoration with floral setups, lighting, stage decor, and custom styling.')

@section('og_title', $decoration->name . ' | Aditya Utsav Bihar Wedding Decor')
@section('og_description', $decoration->short_description ?? $decoration->description)
@section('og_image', $decoration->safe_primary_image)

@section('content')

    <!-- 2. Main Decoration Showcase Section -->
    <section class="py-8 sm:py-12 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Left: Responsive Image Gallery -->
                <div class="lg:col-span-7">
                    <x-image-gallery :decoration="$decoration" />
                </div>

                <!-- Right: Detailed Specs & Booking Actions -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
                        
                        <!-- Header Badges -->
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <a href="{{ route('decorations.category', $decoration->category->slug) }}" class="inline-flex items-center gap-1.5 bg-brand-burgundy/10 text-brand-burgundy text-xs font-bold uppercase tracking-wider px-3 py-1 rounded hover:bg-brand-burgundy hover:text-white transition-colors border border-brand-burgundy/20">
                                <i class="fas fa-tag text-[10px] text-brand-gold"></i>
                                {{ $decoration->category->name }}
                            </a>

                            <div class="flex items-center gap-2">
                                @if($decoration->is_featured)
                                    <span class="bg-brand-gold text-brand-charcoal text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded shadow-sm">
                                        <i class="fas fa-crown text-[10px] mr-1"></i> Featured
                                    </span>
                                @endif
                                @if($decoration->is_trending)
                                    <span class="bg-brand-royal-rose text-white text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded">
                                        <i class="fas fa-fire text-[10px] mr-1"></i> Trending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Decoration Title & Tagline -->
                        <div>
                            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal leading-tight">
                                {{ $decoration->name }}
                            </h1>
                            @if($decoration->tagline)
                                <p class="text-xs sm:text-sm text-brand-royal-rose font-medium mt-1">
                                    {{ $decoration->tagline }}
                                </p>
                            @endif
                        </div>

                        <!-- Rating & Review Count (If Available) -->
                        <div class="flex items-center gap-3 text-xs border-y border-brand-light-border/70 py-2.5">
                            <div class="flex items-center text-amber-500 font-bold gap-1">
                                <i class="fas fa-star text-sm"></i>
                                <span class="text-brand-charcoal font-semibold text-sm">{{ number_format($decoration->rating ?? 4.9, 1) }}</span>
                            </div>
                            <span class="text-brand-light-border">|</span>
                            <span class="text-brand-muted-brown">
                                <i class="fas fa-check-circle text-green-600 mr-1"></i> {{ $decoration->reviews_count ?? 18 }} Verified Bihar Bookings
                            </span>
                            <span class="text-brand-light-border">|</span>
                            <span class="text-green-700 font-semibold flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                Available
                            </span>
                        </div>

                        <!-- Pricing Card with Discount -->
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-brand-offwhite to-brand-cream border border-brand-light-border shadow-inner">
                            <div class="flex items-baseline justify-between flex-wrap gap-2">
                                <div>
                                    <span class="text-[11px] text-brand-muted-brown font-bold uppercase tracking-wider block">
                                        Package Starting Price
                                    </span>
                                    <div class="flex items-baseline gap-2.5 mt-0.5">
                                        <span class="font-serif text-3xl sm:text-4xl font-bold text-brand-burgundy">
                                            {{ $decoration->formatted_price }}
                                        </span>
                                        @if($decoration->has_discount)
                                            <span class="text-sm sm:text-base text-gray-400 line-through font-medium">
                                                ₹{{ number_format($decoration->base_price) }}
                                            </span>
                                            <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">
                                                Save ₹{{ number_format($decoration->base_price - $decoration->discount_price) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[11px] text-brand-muted-brown bg-white px-2.5 py-1 rounded border border-brand-light-border self-center">
                                    All-inclusive setup
                                </span>
                            </div>
                            <p class="text-[11px] text-brand-muted-brown mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle text-brand-gold"></i>
                                Price includes full on-site setup, dismantling &amp; dedicated team.
                            </p>
                        </div>

                        <!-- Short Description -->
                        <p class="text-xs sm:text-sm text-brand-charcoal/90 leading-relaxed font-normal">
                            {{ $decoration->display_description }}
                        </p>

                        <!-- Key Specification Attributes -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="p-3 rounded-lg bg-brand-offwhite/80 border border-brand-light-border flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-xs text-brand-burgundy"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-semibold">Service Area</span>
                                    <span class="text-xs font-bold text-brand-charcoal truncate block">{{ $decoration->location }}</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-brand-offwhite/80 border border-brand-light-border flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand-gold/20 text-brand-gold-dark flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-palette text-xs text-brand-gold-dark"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-semibold">Style</span>
                                    <span class="text-xs font-bold text-brand-charcoal truncate block">{{ $decoration->style ?? 'Traditional Bihar' }}</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-brand-offwhite/80 border border-brand-light-border flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand-royal-rose/10 text-brand-royal-rose flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-users text-xs text-brand-royal-rose"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-semibold">Capacity</span>
                                    <span class="text-xs font-bold text-brand-charcoal truncate block">{{ $decoration->guest_capacity ?? '200-500 Guests' }}</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-lg bg-brand-offwhite/80 border border-brand-light-border flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock text-xs text-blue-700"></i>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-semibold">Setup Time</span>
                                    <span class="text-xs font-bold text-brand-charcoal truncate block">{{ $decoration->setup_time ?? '4-6 Hours' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-brand-light-border space-y-3">
                            <!-- Primary Booking Trigger (Phase 3 Workflow) -->
                            <a 
                                href="{{ route('booking.create', $decoration->slug) }}" 
                                class="w-full py-3.5 px-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all flex items-center justify-center gap-2"
                            >
                                <i class="fas fa-calendar-check text-brand-gold text-base"></i>
                                <span>Book This Decoration</span>
                            </a>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Quote Button -->
                                <a 
                                    href="{{ route('quote') }}?decoration={{ urlencode($decoration->name) }}&category={{ urlencode($decoration->category->slug) }}" 
                                    class="py-2.5 px-3 text-xs font-semibold text-brand-charcoal bg-white hover:bg-brand-offwhite rounded-xl border border-brand-light-border hover:border-brand-gold transition-colors text-center flex items-center justify-center gap-1.5 shadow-sm"
                                >
                                    <i class="fas fa-file-invoice text-brand-gold"></i>
                                    <span>Get a Quote</span>
                                </a>

                                <!-- WhatsApp Consultation -->
                                <a 
                                    href="https://wa.me/919931200000?text={{ urlencode('Namaste Aditya Utsav! I am interested in booking the "' . $decoration->name . '" (' . $decoration->category->name . ') in ' . $decoration->location . '. Please share details & availability.') }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="py-2.5 px-3 text-xs font-semibold text-green-800 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-colors text-center flex items-center justify-center gap-1.5 shadow-sm"
                                >
                                    <i class="fab fa-whatsapp text-green-600 text-sm"></i>
                                    <span>Enquire on WhatsApp</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. "What's Included" - Inclusions & Stage Elements Section -->
    <section class="py-12 bg-white border-y border-brand-light-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose flex items-center gap-2 mb-2">
                    <i class="fas fa-check-double text-brand-gold"></i>
                    Complete Package Inclusions
                </span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                    What is Included in This Decoration
                </h2>
                <p class="text-xs sm:text-sm text-brand-muted-brown mt-1">
                    Every decoration by Aditya Utsav includes authentic Bihar craftsmanship, premium floral elements, professional sound/light setup, and hassle-free cleanup.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @if($decoration->items->count() > 0)
                    @foreach($decoration->items as $item)
                        <div class="p-4 rounded-xl bg-brand-cream/60 border border-brand-light-border hover:border-brand-gold/60 transition-all flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-xs text-brand-gold font-bold"></i>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-serif text-sm font-bold text-brand-charcoal">
                                        {{ $item->name }}
                                    </h4>
                                    @if($item->quantity && $item->quantity > 1)
                                        <span class="text-[10px] font-bold text-brand-burgundy bg-brand-gold/20 px-2 py-0.5 rounded">
                                            Qty: {{ $item->quantity }}
                                        </span>
                                    @endif
                                </div>
                                @if($item->description)
                                    <p class="text-xs text-brand-muted-brown mt-1 leading-relaxed">
                                        {{ $item->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback features if specific items not yet added -->
                    @php
                        $defaultInclusions = [
                            ['name' => 'Grand Floral Backdrop', 'desc' => 'High-grade fresh marigold, rose garland layers and traditional draping.'],
                            ['name' => 'Royal Stage Setup', 'desc' => 'Solid wooden platform stage with red velvet carpet and border frills.'],
                            ['name' => 'Carved Maharaja Chairs / Sofa', 'desc' => 'Gold-polished royal sofa set for the bride and groom.'],
                            ['name' => 'Warm LED & Halogen Focus Lights', 'desc' => 'Professional amber spotlighting for photography clarity.'],
                            ['name' => 'Entrance Passage Pillars & Rangoli', 'desc' => 'Coordinated welcome pillars with fresh floral toran.'],
                            ['name' => 'On-site Dedicated Setup Team', 'desc' => 'Experienced decorators handling setup, maintenance, and teardown.'],
                        ];
                    @endphp
                    @foreach($defaultInclusions as $item)
                        <div class="p-4 rounded-xl bg-brand-cream/60 border border-brand-light-border hover:border-brand-gold/60 transition-all flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-check text-xs text-brand-gold font-bold"></i>
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-serif text-sm font-bold text-brand-charcoal">
                                    {{ $item['name'] }}
                                </h4>
                                <p class="text-xs text-brand-muted-brown mt-1 leading-relaxed">
                                    {{ $item['desc'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- 4. "Make Your Decoration Special" - Available Add-ons Section -->
    <section class="py-12 bg-brand-cream/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose flex items-center gap-2 mb-2">
                        <i class="fas fa-sparkles text-brand-gold"></i>
                        Custom Upgrades &amp; Enhancements
                    </span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                        Make Your Decoration Special
                    </h2>
                    <p class="text-xs sm:text-sm text-brand-muted-brown mt-1">
                        Personalize your celebration with these popular traditional additions and stage upgrades.
                    </p>
                </div>
                <a href="{{ route('quote') }}?decoration={{ urlencode($decoration->name) }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-burgundy hover:text-brand-deep-burgundy uppercase tracking-wider">
                    <span>Inquire About Custom Addons</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($addons as $addon)
                    <x-addon-card :addon="$addon" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. "Similar Decorations" - 4 Related Setups Section -->
    @if($relatedDecorations->count() > 0)
        <section class="py-14 bg-white border-t border-brand-light-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose flex items-center gap-2 mb-2">
                            <i class="fas fa-heart text-brand-gold"></i>
                            More In {{ $decoration->category->name }}
                        </span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                            Similar Decorations You May Like
                        </h2>
                    </div>
                    <a href="{{ route('decorations.category', $decoration->category->slug) }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-burgundy hover:text-brand-deep-burgundy uppercase tracking-wider">
                        <span>View All {{ $decoration->category->name }} ({{ $decoration->category->decorations_count ?? 'Designs' }})</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedDecorations as $rel)
                        <x-decoration-card :decoration="$rel" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
