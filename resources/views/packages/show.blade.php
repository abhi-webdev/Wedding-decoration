@extends('layouts.app')

@section('title', $package->name . ' | Wedding Packages | Aditya Utsav Bihar')
@section('meta_description', $package->short_description ?: 'Book ' . $package->name . ' for your wedding celebrations in Bihar and Eastern UP with Aditya Utsav.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Packages', 'url' => route('packages.index')],
        ['label' => $package->name, 'url' => '']
    ]" />

    <!-- Main Package Details Section -->
    <section class="py-10 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Top Hero Grid: 7 cols Visual / 5 cols Pricing & Key Specs -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Visual Image & Badges (7 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="relative rounded-3xl overflow-hidden border border-brand-light-border shadow-soft-luxury aspect-[16/10] bg-brand-offwhite">
                        <img 
                            src="{{ $package->display_image }}" 
                            alt="{{ $package->name }} - Aditya Utsav" 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                        @if($package->badge)
                            <div class="absolute top-4 left-4 bg-brand-burgundy text-brand-gold px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-brand-gold/40 shadow-md">
                                <i class="fas fa-crown text-[10px] mr-1"></i> {{ $package->badge }}
                            </div>
                        @endif

                        @if($package->guest_capacity)
                            <div class="absolute bottom-4 left-4 bg-black/70 backdrop-blur-sm text-white px-3 py-1 rounded-xl text-xs font-semibold flex items-center gap-1.5 border border-white/20">
                                <i class="fas fa-users text-brand-gold"></i> Ideal for {{ $package->guest_capacity }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Package Pricing, Highlights & Booking Box (5 cols) -->
                <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Complete Wedding Package</span>
                        <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal leading-snug">
                            {{ $package->name }}
                        </h1>
                        <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed">
                            {{ $package->tagline ?: $package->short_description }}
                        </p>
                    </div>

                    <!-- Price Box -->
                    <div class="p-4 bg-brand-offwhite rounded-2xl border border-brand-gold/40 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-brand-muted-brown uppercase tracking-wider font-semibold block">Estimated Package Price</span>
                            <div class="flex items-baseline gap-2 mt-0.5">
                                <span class="font-serif text-2xl sm:text-3xl font-bold text-brand-burgundy">
                                    {{ $package->formatted_price }}
                                </span>
                                @if($package->formatted_discount_price && $package->base_price > $package->discount_price)
                                    <span class="text-xs text-brand-muted-brown line-through">
                                        {{ $package->formatted_base_price }}
                                    </span>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                                        Save {{ number_format((($package->base_price - $package->discount_price) / $package->base_price) * 100, 0) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($package->duration)
                            <div class="text-right text-xs text-brand-charcoal font-semibold">
                                <i class="far fa-clock text-brand-gold mr-1"></i> {{ $package->duration }}
                            </div>
                        @endif
                    </div>

                    <!-- Included Ceremonies / Summary Pills -->
                    @if(!empty($package->included_ceremonies))
                        <div class="space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-charcoal block">Covered Ceremonies:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($package->included_ceremonies as $ceremony)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-brand-burgundy/5 text-brand-burgundy rounded-lg border border-brand-burgundy/15">
                                        ✓ {{ $ceremony }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Primary Actions -->
                    <div class="space-y-3 pt-2">
                        <a 
                            href="{{ route('quote', ['package' => $package->name]) }}" 
                            class="w-full py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-file-signature text-brand-gold"></i>
                            <span>Request This Package Quote</span>
                        </a>

                        <a 
                            href="https://wa.me/919931200000?text={{ urlencode('Namaste Aditya Utsav! I am interested in the ' . $package->name . ' (' . $package->formatted_price . '). Please share package inclusions and date availability.') }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="w-full py-2.5 px-4 text-xs font-bold text-green-900 bg-green-50 hover:bg-green-100 border border-green-300 rounded-xl transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="fab fa-whatsapp text-green-600 text-sm"></i>
                            <span>Inquire on WhatsApp</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Deep Package Specifications (2 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Full Description & Included Features (8 cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Detailed Description -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4">
                        <h2 class="font-serif text-xl font-bold text-brand-charcoal pb-2 border-b border-brand-light-border">
                            About This Package
                        </h2>
                        <div class="text-xs sm:text-sm text-brand-charcoal leading-relaxed space-y-3">
                            <p>{{ $package->description }}</p>
                        </div>
                    </div>

                    <!-- Package Inclusions List -->
                    @if(!empty($package->highlights))
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4">
                            <h3 class="font-serif text-lg font-bold text-brand-charcoal pb-2 border-b border-brand-light-border">
                                What's Included in {{ $package->name }}
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                @foreach($package->highlights as $highlight)
                                    <div class="p-3 rounded-xl bg-brand-offwhite border border-brand-light-border flex items-start gap-2.5">
                                        <i class="fas fa-check-circle text-brand-gold text-sm mt-0.5 shrink-0"></i>
                                        <span class="text-brand-charcoal font-medium">{{ $highlight }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Included Decorations (if associated) -->
                    @if($package->decorations->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4">
                            <h3 class="font-serif text-lg font-bold text-brand-charcoal pb-2 border-b border-brand-light-border">
                                Featured Setup Elements Inside This Package
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($package->decorations as $dec)
                                    <div class="p-3 rounded-2xl bg-brand-offwhite border border-brand-light-border flex items-center gap-3">
                                        <img src="{{ $dec->primary_image_url }}" alt="{{ $dec->name }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                                        <div class="min-w-0">
                                            <h4 class="font-serif text-xs font-bold text-brand-charcoal truncate">{{ $dec->name }}</h4>
                                            <span class="text-[10px] text-brand-muted-brown block">{{ $dec->category->name ?? 'Wedding Decor' }}</span>
                                            <a href="{{ route('decorations.show', $dec->slug) }}" class="text-[10px] font-bold text-brand-burgundy hover:underline mt-0.5 inline-block">
                                                View Element Details &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Optional Upgrades & Add-ons -->
                    @if(!empty($availableAddons) && $availableAddons->isNotEmpty())
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-brand-light-border">
                                <h3 class="font-serif text-lg font-bold text-brand-charcoal">
                                    Recommended Custom Add-ons
                                </h3>
                                <span class="text-[11px] text-brand-muted-brown">Optional Enhancements</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                @foreach($availableAddons as $addon)
                                    <div class="p-3 rounded-xl bg-brand-offwhite border border-brand-light-border flex items-start justify-between gap-2">
                                        <div>
                                            <h4 class="font-bold text-brand-charcoal">{{ $addon->name }}</h4>
                                            <p class="text-[11px] text-brand-muted-brown mt-0.5">{{ $addon->description }}</p>
                                        </div>
                                        <span class="font-serif text-xs font-bold text-brand-burgundy whitespace-nowrap">
                                            +{{ $addon->formatted_price }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Right: Service Coverage & Trust Badges (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Service Coverage Box -->
                    <div class="bg-white rounded-3xl p-6 border border-brand-light-border shadow-soft-luxury space-y-3 text-xs">
                        <h3 class="font-serif text-sm font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border">
                            Service Area Coverage
                        </h3>
                        <p class="text-brand-muted-brown leading-relaxed">
                            This package is available for full on-site setup across:
                        </p>
                        <div class="space-y-1.5 text-brand-charcoal font-medium">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-pin text-brand-gold text-xs"></i>
                                <span><strong>Bihar Core:</strong> Siwan, Mairwa, Gopalganj, Chapra</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-pin text-brand-gold text-xs"></i>
                                <span><strong>Bihar Extended:</strong> Patna, Muzaffarpur, Darbhanga</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-pin text-brand-gold text-xs"></i>
                                <span><strong>Uttar Pradesh:</strong> Gorakhpur, Deoria, Salempur</span>
                            </div>
                        </div>
                    </div>

                    <!-- Assurance Box -->
                    <div class="bg-brand-offwhite rounded-3xl p-6 border border-brand-gold/40 space-y-3 text-xs">
                        <h4 class="font-serif text-sm font-bold text-brand-burgundy">
                            The Aditya Utsav Promise
                        </h4>
                        <ul class="space-y-2 text-brand-charcoal">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-shield-alt text-brand-gold text-xs mt-0.5"></i>
                                <span>Fresh, handpicked seasonal flowers</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-truck text-brand-gold text-xs mt-0.5"></i>
                                <span>Dedicated vehicle transport &amp; rigging crew</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-clock text-brand-gold text-xs mt-0.5"></i>
                                <span>Completed 4 hours prior to ceremony</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

            <!-- Related / Other Packages -->
            @if(!empty($otherPackages) && $otherPackages->isNotEmpty())
                <div class="pt-8 border-t border-brand-light-border space-y-6">
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal text-center">
                        Explore Other Popular Packages
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($otherPackages as $other)
                            <x-package-card :package="$other" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
