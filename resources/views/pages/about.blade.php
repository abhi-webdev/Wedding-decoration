@extends('layouts.app')

@section('title', 'About Aditya Utsav | Bihar Wedding Decoration & Event Services')
@section('meta_description', 'Learn about Aditya Utsav, dedicated wedding and event decoration specialists based in Siwan, Bihar, serving celebrations across North Bihar and Eastern UP.')

@section('content')

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-om text-[10px]"></i> Traditional Heritage &amp; Modern Craft
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                About Aditya Utsav
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Dedicated wedding and event decoration specialists serving families across Bihar and nearby Eastern Uttar Pradesh.
            </p>
        </div>
    </section>

    <!-- Brand Philosophy & Cultural Identity -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: Cultural Narrative (7 cols) -->
                <div class="lg:col-span-7 space-y-5">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Our Story &amp; Purpose</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal leading-snug">
                        Rooted in Traditional Bihar Vivah Customs, Designed with Modern Elegance
                    </h2>
                    
                    <div class="text-xs sm:text-sm text-brand-charcoal leading-relaxed space-y-3">
                        <p>
                            <strong>Aditya Utsav</strong> was established in Siwan to bring dedicated, professional wedding decoration and stage fabrication services to families in Bihar and nearby districts. We believe that every wedding ceremony—from the auspicious Tilak and vibrant Haldi to the grand Jaimala and sacred Saat Phere—deserves an authentic and dignified celebration environment.
                        </p>
                        <p>
                            Our team combines the rich visual heritage of North Indian celebrations (fragrant local marigold garlands, mango leaf torans, brass urlis, and velvet drapes) with modern stage architecture, crystal lighting, and structured on-site event logistics.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 text-xs">
                        <div class="p-4 rounded-2xl bg-white border border-brand-light-border space-y-1 shadow-sm">
                            <span class="font-bold text-brand-burgundy flex items-center gap-1.5">
                                <i class="fas fa-seedling text-brand-gold"></i> Authentic Ritual Focus
                            </span>
                            <p class="text-[11px] text-brand-muted-brown">Customized setups for Vedic Phere, Sindoor Daan, Kanyadan, and family customs.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-brand-light-border space-y-1 shadow-sm">
                            <span class="font-bold text-brand-burgundy flex items-center gap-1.5">
                                <i class="fas fa-truck text-brand-gold"></i> Local Bihar Hub
                            </span>
                            <p class="text-[11px] text-brand-muted-brown">On-ground operations center in Siwan with regular dispatch across Gopalganj, Chapra &amp; Gorakhpur.</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Authentic Wedding Culture Showcase (5 cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="rounded-3xl overflow-hidden border border-brand-gold/40 shadow-soft-luxury aspect-[4/3] bg-gradient-to-br from-brand-deep-burgundy via-brand-burgundy to-brand-royal-rose p-8 flex flex-col items-center justify-center text-center text-white relative">
                        <div class="w-16 h-16 rounded-2xl bg-brand-gold/20 border border-brand-gold/60 text-brand-gold flex items-center justify-center text-2xl shadow-gold-glow mb-4">
                            <i class="fas fa-om"></i>
                        </div>
                        <h4 class="font-serif text-2xl font-bold text-white tracking-wide">
                            Aditya Utsav
                        </h4>
                        <span class="text-xs text-brand-gold-light uppercase tracking-[0.25em] font-semibold mt-1">
                            Bihar Vivah Parampara
                        </span>
                        <p class="text-xs text-brand-cream/80 max-w-xs mt-3 leading-relaxed">
                            Crafting divine Vedic Mandaps, majestic Jaimala stages, and radiant Haldi celebrations across Siwan, Patna, and all of Bihar.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Core Principles Grid -->
            <div class="space-y-6 pt-6">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Our Service Commitments</span>
                    <h3 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                        How We Work With You
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                    
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xl border border-brand-gold/40">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4 class="font-serif text-base font-bold text-brand-charcoal">1. Tailored Decoration Plans</h4>
                        <p class="text-brand-muted-brown leading-relaxed">
                            We customize stage dimensions, color combinations, and flower varieties to suit your venue—whether an open village lawn or hotel banquet.
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xl border border-brand-gold/40">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="font-serif text-base font-bold text-brand-charcoal">2. Transparent Pricing</h4>
                        <p class="text-brand-muted-brown leading-relaxed">
                            Clear itemized digital quotations with no hidden surprises on event day. You know exactly what materials and services are included.
                        </p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xl border border-brand-gold/40">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="font-serif text-base font-bold text-brand-charcoal">3. Punctual Execution</h4>
                        <p class="text-brand-muted-brown leading-relaxed">
                            Our riggers and technicians arrive hours ahead of time to finish staging and lighting before the arrival of your guests and Baraat.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Service Coverage Callout -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Regional Coverage</span>
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                        Serving Siwan, Gopalganj, Patna, Gorakhpur &amp; Surrounding Areas
                    </h3>
                    <p class="text-xs text-brand-muted-brown max-w-xl">
                        Explore our complete list of active service territories and transit arrangements across Bihar and Eastern UP.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('service-areas.index') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-brand-burgundy bg-brand-burgundy/10 hover:bg-brand-burgundy hover:text-white rounded-xl border border-brand-burgundy/20 transition-all">
                        View Service Areas
                    </a>
                    <a href="{{ route('quote') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all">
                        Get a Quote
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection
