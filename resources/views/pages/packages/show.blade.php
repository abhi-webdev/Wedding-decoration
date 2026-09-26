@extends('layouts.app')

@section('title', $package->name . ' | Aditya Utsav Bihar')
@section('meta_description', $package->tagline ?? $package->description)

@section('content')
<div class="bg-brand-offwhite border-b border-brand-light-border py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-muted-brown flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-burgundy">Home</a>
        <span>/</span>
        <a href="{{ route('packages.index') }}" class="hover:text-brand-burgundy">Packages</a>
        <span>/</span>
        <span class="text-brand-burgundy font-semibold">{{ $package->name }}</span>
    </div>
</div>

<section class="py-12 bg-brand-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-8 space-y-8">
                <!-- Video Banner Container (No Cover Image, Direct Autoplay) -->
                <div class="rounded-2xl overflow-hidden bg-black border border-brand-gold/40 shadow-soft-luxury h-72 sm:h-96 relative">
                    <video 
                        autoplay 
                        muted 
                        loop 
                        playsinline 
                        class="w-full h-full object-cover">
                        <source src="{{ asset($package->video_url ?? 'packages/package1.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white pointer-events-none">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-gold">{{ $package->badge }}</span>
                        <h1 class="font-serif text-3xl sm:text-4xl font-bold">{{ $package->name }}</h1>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-7 border border-brand-light-border shadow-soft-luxury space-y-6">
                    <h3 class="font-serif text-2xl font-bold text-brand-charcoal">Ceremonies Included</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($package->included_ceremonies as $c)
                            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-brand-cream border border-brand-gold/30">
                                <div class="w-8 h-8 rounded-full bg-brand-burgundy text-brand-gold flex items-center justify-center text-xs">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="font-medium text-brand-charcoal text-sm">{{ $c }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-brand-light-border space-y-3">
                        <h3 class="font-serif text-2xl font-bold text-brand-charcoal">Package Inclusions &amp; Highlights</h3>
                        <ul class="space-y-2 text-sm text-brand-muted-brown">
                            @foreach($package->highlights as $hl)
                                <li class="flex items-start gap-2.5">
                                    <i class="fas fa-certificate text-brand-gold mt-1 text-xs"></i>
                                    <span>{{ $hl }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="sticky top-28 bg-white rounded-2xl p-7 border-2 border-brand-gold shadow-card-hover space-y-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-burgundy block">Package Pricing</span>
                    <div class="flex items-baseline justify-between">
                        <span class="font-serif text-3xl font-bold text-brand-burgundy">{{ $package->formatted_price }}</span>
                        <span class="text-xs text-brand-muted-brown">all-inclusive setup</span>
                    </div>

                    <p class="text-xs text-brand-muted-brown leading-relaxed">
                        Includes end-to-end transportation, floral decoration, throne seating, lighting, and dedicated coordinator throughout all ceremonies.
                    </p>

                    <div class="pt-2 space-y-3">
                        <button type="button" onclick="openAvailabilityModal('{{ $package->name }}', 'Siwan')" class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-md">
                            <i class="fas fa-calendar-check mr-2 text-brand-gold"></i>
                            Check Package Date
                        </button>
                        <a href="{{ route('quote') }}" class="block w-full text-center py-2.5 px-4 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-light-border/40 rounded-lg border border-brand-light-border">
                            Customize Inclusions
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection