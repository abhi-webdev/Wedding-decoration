@extends('layouts.app')

@section('title', 'Available Decorations & Packages for ' . ($parsedDate->format('d M Y') ?? 'Your Date') . ' — Aditya Utsav')
@section('meta_description', 'Browse available wedding decorations and complete wedding packages in ' . $city . ', Bihar for ' . ($parsedDate->format('d M Y') ?? 'your selected date') . '.')

@section('content')
<div class="bg-brand-cream/30 min-h-screen py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 1. Availability Status Banner -->
        <div class="bg-gradient-to-r from-brand-burgundy via-brand-deep-burgundy to-brand-burgundy rounded-2xl p-6 sm:p-8 text-white shadow-xl border border-brand-gold/50 relative overflow-hidden mb-10">
            <!-- Background ornament -->
            <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl pointer-events-none text-brand-gold">
                <i class="fas fa-om"></i>
            </div>

            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-900/60 border border-emerald-400/50 rounded-full text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                        <i class="fas fa-check-circle text-emerald-400"></i>
                        <span>Your Date Is Available</span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl lg:text-4xl font-bold text-brand-cream leading-tight">
                        {{ $parsedDate->format('d F Y') }} <span class="text-brand-gold font-normal">({{ $parsedDate->format('l') }})</span>
                    </h1>
                    <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs sm:text-sm text-brand-cream/90 mt-2">
                        <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-brand-gold"></i> {{ $city }}, Bihar</span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-ring text-brand-gold"></i> {{ $eventType }}</span>
                        @if(!empty($name))
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-user text-brand-gold"></i> Host: {{ $name }}</span>
                        @endif
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 text-center sm:text-right">
                    <p class="text-[11px] uppercase tracking-wider text-brand-gold font-semibold">Ready To Book?</p>
                    <p class="text-xs text-white/80 mt-0.5">Select a decoration or complete package below to reserve your auspicious date.</p>
                </div>
            </div>
        </div>

        <!-- 2. Step Progress Bar -->
        <div class="bg-white rounded-xl p-4 border border-brand-light-border shadow-sm mb-10">
            <div class="flex items-center justify-between max-w-2xl mx-auto text-xs font-semibold">
                <div class="flex items-center gap-2 text-emerald-700">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 border border-emerald-500 flex items-center justify-center font-bold text-[11px]"><i class="fas fa-check text-[10px]"></i></span>
                    <span class="hidden sm:inline">1. Date Available</span>
                </div>
                <div class="h-0.5 w-12 sm:w-20 bg-emerald-400"></div>
                <div class="flex items-center gap-2 text-brand-burgundy">
                    <span class="w-6 h-6 rounded-full bg-brand-burgundy text-brand-cream flex items-center justify-center font-bold text-[11px]">2</span>
                    <span class="font-bold">Select Setup / Package</span>
                </div>
                <div class="h-0.5 w-12 sm:w-20 bg-gray-200"></div>
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="w-6 h-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-[11px]">3</span>
                    <span class="hidden sm:inline">Submit Details</span>
                </div>
            </div>
        </div>

        <!-- 3. SECTION: AVAILABLE DECORATIONS -->
        <div class="mb-14">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 pb-3 border-b border-brand-gold/30">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-burgundy">Live Catalog Selection</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal mt-1">Available Decorations</h2>
                    <p class="text-xs sm:text-sm text-brand-muted-brown mt-1">Authentic Vedic mandaps, royal jaimala stages, haldi &amp; sangeet setups available on {{ $parsedDate->format('d M Y') }}.</p>
                </div>
                <span class="text-xs font-semibold text-brand-burgundy bg-brand-burgundy/10 px-3 py-1 rounded-full mt-2 sm:mt-0 self-start sm:self-auto">
                    {{ $decorations->count() }} Setups Ready
                </span>
            </div>

            @if($decorations->isEmpty())
                <div class="bg-white rounded-2xl p-8 text-center border border-brand-light-border shadow-sm">
                    <i class="fas fa-info-circle text-4xl text-brand-gold mb-3"></i>
                    <h3 class="font-serif text-xl font-bold text-brand-charcoal">No Decorations Listed Currently</h3>
                    <p class="text-xs text-brand-muted-brown mt-1">Please contact our team directly for custom decoration options.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($decorations as $decoration)
                        <div class="bg-white rounded-2xl border border-brand-light-border overflow-hidden shadow-sm hover:shadow-xl hover:border-brand-gold/60 transition-all duration-300 flex flex-col group">
                            <!-- Image Container -->
                            <div class="relative aspect-[4/3] bg-brand-offwhite overflow-hidden">
                                @if($decoration->safe_primary_image)
                                    <img src="{{ $decoration->safe_primary_image }}" alt="{{ $decoration->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-burgundy/10 to-brand-gold/10 text-brand-burgundy p-4 text-center">
                                        <i class="fas fa-camera text-3xl text-brand-gold/60 mb-2"></i>
                                        <span class="font-serif font-semibold text-sm">{{ $decoration->name }}</span>
                                    </div>
                                @endif

                                @if($decoration->category)
                                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-brand-burgundy/90 backdrop-blur-sm text-brand-cream text-[10px] font-bold uppercase tracking-wider rounded-md border border-brand-gold/40 shadow">
                                        {{ $decoration->category->name }}
                                    </span>
                                @endif

                                <span class="absolute bottom-3 right-3 px-2.5 py-1 bg-white/95 backdrop-blur-sm text-brand-charcoal text-[11px] font-bold rounded-md shadow flex items-center gap-1">
                                    <i class="fas fa-users text-brand-burgundy text-[10px]"></i>
                                    {{ $decoration->guest_capacity ?: '200-500 Guests' }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-serif text-lg font-bold text-brand-charcoal group-hover:text-brand-burgundy transition-colors line-clamp-1">
                                        {{ $decoration->name }}
                                    </h3>
                                    <p class="text-xs text-brand-muted-brown mt-1 line-clamp-2">
                                        {{ $decoration->tagline ?: ($decoration->short_description ?: 'Complete stage, mandap, backdrop, and floral decoration setup in ' . $city . '.') }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-4 border-t border-brand-light-border/70 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-brand-muted-brown uppercase block">Base Price</span>
                                        <span class="font-serif text-lg font-bold text-brand-burgundy">
                                            {{ $decoration->formatted_price }}
                                        </span>
                                    </div>

                                    <a href="{{ route('booking.create', [
                                        'decoration' => $decoration->slug ?: $decoration->id,
                                        'event_type' => $eventType,
                                        'city' => $city,
                                        'event_date' => $eventDate,
                                        'phone' => $phone,
                                        'name' => $name
                                    ]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brand-burgundy hover:bg-brand-deep-burgundy text-white text-xs font-bold uppercase tracking-wider rounded-lg border border-brand-gold shadow transition-all hover:scale-[1.02]">
                                        <span>Book This</span>
                                        <i class="fas fa-arrow-right text-[10px] text-brand-gold"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 4. SECTION: AVAILABLE PACKAGES -->
        <div class="mb-14">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 pb-3 border-b border-brand-gold/30">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-burgundy">All-In-One Wedding Solutions</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal mt-1">Available Packages</h2>
                    <p class="text-xs sm:text-sm text-brand-muted-brown mt-1">Multi-ceremony wedding packages covering Vedic Mandap, Jaimala, Haldi, Entry &amp; Lighting.</p>
                </div>
                <span class="text-xs font-semibold text-brand-burgundy bg-brand-burgundy/10 px-3 py-1 rounded-full mt-2 sm:mt-0 self-start sm:self-auto">
                    {{ $packages->count() }} Packages Ready
                </span>
            </div>

            @if($packages->isEmpty())
                <div class="bg-white rounded-2xl p-8 text-center border border-brand-light-border shadow-sm">
                    <i class="fas fa-gift text-4xl text-brand-gold mb-3"></i>
                    <h3 class="font-serif text-xl font-bold text-brand-charcoal">No Packages Available</h3>
                    <p class="text-xs text-brand-muted-brown mt-1">Please select an individual decoration or contact us for a customized package.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($packages as $package)
                        <div class="bg-white rounded-2xl border-2 border-brand-gold/60 overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col relative group">
                            @if($package->badge)
                                <div class="absolute top-3 right-3 z-10 px-3 py-1 bg-gradient-to-r from-brand-gold to-amber-500 text-brand-burgundy text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow">
                                    {{ $package->badge }}
                                </div>
                            @endif

                            <!-- Package Image -->
                            <div class="relative aspect-[16/9] bg-brand-offwhite overflow-hidden">
                                @if($package->display_image)
                                    <img src="{{ $package->display_image }}" alt="{{ $package->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-brand-burgundy/20 to-brand-gold/20 text-brand-burgundy p-4 text-center">
                                        <i class="fas fa-crown text-3xl text-brand-gold mb-1"></i>
                                        <span class="font-serif font-bold text-sm">{{ $package->name }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                <div class="absolute bottom-3 left-3 text-white">
                                    <p class="font-serif text-lg font-bold text-brand-cream">{{ $package->name }}</p>
                                </div>
                            </div>

                            <!-- Package Body -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <p class="text-xs text-brand-muted-brown line-clamp-2">
                                        {{ $package->tagline ?: ($package->short_description ?: 'All-inclusive decor setup for complete wedding festivities in ' . $city . '.') }}
                                    </p>

                                    <!-- Highlights / Ceremonies list -->
                                    @if(!empty($package->included_ceremonies) && is_array($package->included_ceremonies))
                                        <div class="mt-3 space-y-1.5">
                                            @foreach(array_slice($package->included_ceremonies, 0, 4) as $ceremony)
                                                <div class="flex items-center gap-2 text-xs text-brand-charcoal">
                                                    <i class="fas fa-check-circle text-emerald-600 text-[11px]"></i>
                                                    <span class="line-clamp-1">{{ is_array($ceremony) ? ($ceremony['name'] ?? 'Ceremony Setup') : $ceremony }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-5 pt-4 border-t border-brand-light-border/70 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-brand-muted-brown uppercase block">Package Price</span>
                                        <span class="font-serif text-xl font-bold text-brand-burgundy">
                                            {{ $package->formatted_price }}
                                        </span>
                                    </div>

                                    <a href="{{ route('booking.createPackage', [
                                        'package' => $package->slug ?: $package->id,
                                        'event_type' => $eventType,
                                        'city' => $city,
                                        'event_date' => $eventDate,
                                        'phone' => $phone,
                                        'name' => $name
                                    ]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-brand-burgundy to-brand-deep-burgundy hover:from-brand-deep-burgundy hover:to-brand-burgundy text-brand-cream text-xs font-bold uppercase tracking-wider rounded-lg border border-brand-gold shadow-md hover:shadow-gold-glow transition-all hover:scale-[1.02]">
                                        <i class="fas fa-crown text-brand-gold text-[11px]"></i>
                                        <span>Book Package</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 5. Need Assistance Callout -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border text-center shadow-sm">
            <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mb-2">Need a Customized Decor Concept or Muhurat Date Consultation?</h3>
            <p class="text-xs sm:text-sm text-brand-muted-brown max-w-xl mx-auto mb-5">
                Our master event decorators in Siwan design bespoke floral theme setups across Bihar and Eastern UP.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ \App\Services\NotificationService::getWhatsAppUrl('Namaste Aditya Utsav, I checked availability for ' . $eventDate . ' in ' . $city . ' and would like to discuss custom wedding decoration options.') }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider shadow">
                    <i class="fab fa-whatsapp text-sm"></i>
                    <span>Chat on WhatsApp</span>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-offwhite hover:bg-brand-cream text-brand-burgundy border border-brand-light-border rounded-lg text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-phone-alt text-sm text-brand-gold"></i>
                    <span>Contact Siwan Office</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
