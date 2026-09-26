@extends('layouts.app')

@section('title', 'Wedding Decoration Service Areas in Bihar & UP | Aditya Utsav')
@section('meta_description', 'Aditya Utsav service areas across Bihar (Siwan, Gopalganj, Chapra, Patna) and Eastern Uttar Pradesh (Gorakhpur, Deoria, Salempur).')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Service Areas', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-map-marked-alt text-[10px]"></i> Regional Logistics &amp; Crew
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Wedding Decoration Service Areas
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Dedicated decoration logistics and fabrication teams serving celebrations across Bihar and Eastern Uttar Pradesh.
            </p>
        </div>
    </section>

    <!-- Service Territories Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">
            
            <!-- 1. Bihar Core Territories -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-brand-light-border">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-emerald-700 text-white flex items-center justify-center text-xs font-bold shrink-0">
                            1
                        </span>
                        <div>
                            <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                                Bihar — Core Service Territory
                            </h2>
                            <p class="text-xs text-brand-muted-brown">
                                Primary operations hub with zero extended transit charges and guaranteed 4-hour rigging response.
                            </p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full">
                        State: Bihar
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($biharCore as $area)
                        <x-service-area-card :area="$area" />
                    @endforeach
                </div>
            </div>

            <!-- 2. Bihar Extended Districts -->
            @if($biharExtended->isNotEmpty())
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-brand-light-border">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                2
                            </span>
                            <div>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                                    Bihar — Extended Districts &amp; Cities
                                </h2>
                                <p class="text-xs text-brand-muted-brown">
                                    Full event setups for grand marriage banquets and destination resort lawns across Central &amp; North Bihar.
                                </p>
                            </div>
                        </div>
                        <span class="hidden sm:inline-block text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full">
                            State: Bihar
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($biharExtended as $area)
                            <x-service-area-card :area="$area" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 3. Nearby Uttar Pradesh Districts -->
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-brand-light-border">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-blue-700 text-white flex items-center justify-center text-xs font-bold shrink-0">
                            3
                        </span>
                        <div>
                            <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                                Nearby Uttar Pradesh — Border &amp; Eastern Districts
                            </h2>
                            <p class="text-xs text-brand-muted-brown">
                                Dedicated transportation and crew dispatch for marriage halls and lawns in Eastern Uttar Pradesh.
                            </p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-block text-xs font-bold uppercase tracking-wider text-blue-800 bg-blue-100 px-3 py-1 rounded-full">
                        State: Uttar Pradesh
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($uttarPradesh as $area)
                        <x-service-area-card :area="$area" />
                    @endforeach
                </div>
            </div>

            <!-- Custom Location Callout -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-brand-gold/40 shadow-soft-luxury flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Venue in Another Town or Village?</span>
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                        We Can Travel to Your Village or Town
                    </h3>
                    <p class="text-xs text-brand-muted-brown max-w-xl">
                        If your marriage celebration is in a nearby town or village not listed here, submit a quote with your exact address. We accommodate custom road logistics across Bihar and UP.
                    </p>
                </div>
                <a href="{{ route('quote') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all shrink-0">
                    Request Custom Location Quote
                </a>
            </div>

        </div>
    </section>

@endsection
