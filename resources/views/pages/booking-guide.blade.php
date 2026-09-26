@extends('layouts.app')

@section('title', 'Step-by-Step Wedding Booking Guide | Aditya Utsav Bihar')
@section('meta_description', 'Learn how to book wedding and stage decorations with Aditya Utsav. A simple 7-step process from catalog browsing to on-site ceremony execution.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Booking Guide', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-list-ol text-[10px]"></i> Simple &amp; Transparent Workflow
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                How Wedding Decoration Booking Works
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                From choosing your favorite setup to flawless event-day execution in 7 easy steps.
            </p>
        </div>
    </section>

    <!-- 7-Step Guide Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Notice Callout -->
            <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-2 text-amber-950 text-sm">
                    <i class="fas fa-info-circle text-amber-600"></i> Important Note for Clients
                </div>
                <p class="leading-relaxed text-amber-800">
                    Submitting an initial booking request on our website reserves your date slot for review. Final confirmation is formalized after our Siwan operations team verifies materials availability and locks the date with you.
                </p>
            </div>

            <!-- Steps List -->
            <div class="space-y-6">
                @php
                    $steps = [
                        [
                            'step' => 1,
                            'title' => 'Browse Decoration Designs & Packages',
                            'desc' => 'Explore our catalog of authentic Bihar wedding setups—including Jaimala stages, Vedic Mandap pavilions, Haldi canopies, and complete multi-day celebration packages.',
                            'icon' => 'fa-search',
                            'link' => route('decorations.index'),
                            'link_text' => 'Browse Catalog'
                        ],
                        [
                            'step' => 2,
                            'title' => 'Select Your Favorite Setup',
                            'desc' => 'Click on any decoration to view high-resolution photography, included furniture and floral items, dimensions, and certified starting prices.',
                            'icon' => 'fa-eye',
                            'link' => null,
                            'link_text' => null
                        ],
                        [
                            'step' => 3,
                            'title' => 'Specify Event Date, Time & Venue Location',
                            'desc' => 'Select your ceremony date to run a live availability check. Enter your venue address in Siwan, Gopalganj, Patna, Gorakhpur, or nearby towns.',
                            'icon' => 'fa-calendar-check',
                            'link' => null,
                            'link_text' => null
                        ],
                        [
                            'step' => 4,
                            'title' => 'Add Custom Add-ons & Submit Request',
                            'desc' => 'Optionally choose cold pyro machines, extra brass urlis, or sofa upgrades. Review your calculated price estimate and submit your booking request with zero upfront payment.',
                            'icon' => 'fa-plus-circle',
                            'link' => null,
                            'link_text' => null
                        ],
                        [
                            'step' => 5,
                            'title' => 'Aditya Utsav Reviews Slot Availability',
                            'desc' => 'Our Siwan operations desk receives your unique Reference ID (e.g. AU-20260920-00125), verifies local transport and flower inventory, and calls you within 2–4 hours.',
                            'icon' => 'fa-phone-volume',
                            'link' => null,
                            'link_text' => null
                        ],
                        [
                            'step' => 6,
                            'title' => 'Final Quotation & Token Advance',
                            'desc' => 'You receive an itemized digital quotation via WhatsApp and Email. Pay a nominal token advance to officially lock your wedding date and crew.',
                            'icon' => 'fa-file-invoice-dollar',
                            'link' => null,
                            'link_text' => null
                        ],
                        [
                            'step' => 7,
                            'title' => 'Flawless On-Site Execution',
                            'desc' => 'On your wedding day, our 8–12 member fabrication and floral crew arrives 4–6 hours prior to the ceremony to erect the stage, arrange fresh flowers, and test all lighting.',
                            'icon' => 'fa-sparkles',
                            'link' => null,
                            'link_text' => null
                        ],
                    ];
                @endphp

                @foreach($steps as $s)
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury flex flex-col sm:flex-row items-start gap-6 transition-all hover:border-brand-gold/60">
                        
                        <!-- Step Number Badge -->
                        <div class="w-14 h-14 rounded-2xl bg-brand-burgundy text-brand-gold flex items-center justify-center font-serif text-xl font-bold shrink-0 shadow-md border border-brand-gold/40">
                            {{ $s['step'] }}
                        </div>

                        <!-- Step Content -->
                        <div class="space-y-2 flex-grow">
                            <h3 class="font-serif text-lg sm:text-xl font-bold text-brand-charcoal">
                                {{ $s['title'] }}
                            </h3>
                            <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed">
                                {{ $s['desc'] }}
                            </p>
                            @if($s['link'])
                                <div class="pt-2">
                                    <a href="{{ $s['link'] }}" class="text-xs font-bold text-brand-burgundy hover:underline inline-flex items-center gap-1">
                                        <span>{{ $s['link_text'] }}</span>
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Ready to Start CTA -->
            <div class="bg-white rounded-3xl p-8 sm:p-12 border border-brand-gold/40 shadow-soft-luxury text-center space-y-4">
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                    Ready to Plan Your Wedding Decoration?
                </h3>
                <p class="text-xs sm:text-sm text-brand-muted-brown max-w-xl mx-auto leading-relaxed">
                    Explore our curated wedding packages or request a custom quote for your celebration in Bihar and Eastern UP.
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('packages.index') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all">
                        View Packages
                    </a>
                    <a href="{{ route('quote') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-brand-charcoal bg-brand-offwhite hover:bg-white rounded-xl border border-brand-light-border transition-all">
                        Get Custom Quote
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection
