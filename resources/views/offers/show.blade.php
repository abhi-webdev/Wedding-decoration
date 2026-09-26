@extends('layouts.app')

@section('title', $offer->title . ' | Offers | Aditya Utsav Bihar')
@section('meta_description', $offer->short_description ?: 'Claim ' . $offer->title . ' on your wedding decoration with Aditya Utsav in Bihar and Eastern UP.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Special Offers', 'url' => route('offers.index')],
        ['label' => $offer->title, 'url' => '']
    ]" />

    <!-- Offer Detail Main Section -->
    <section class="py-10 sm:py-16 bg-brand-cream relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Hero Card -->
            <div class="bg-white rounded-3xl overflow-hidden border border-brand-light-border shadow-soft-luxury">
                <div class="relative aspect-[16/9] bg-brand-offwhite">
                    <img 
                        src="{{ $offer->display_image }}" 
                        alt="{{ $offer->title }}" 
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>

                    @if($offer->discount_text)
                        <div class="absolute top-4 right-4 bg-brand-burgundy text-brand-gold font-serif text-lg font-bold px-4 py-1.5 rounded-full border border-brand-gold shadow-md">
                            {{ $offer->discount_text }}
                        </div>
                    @endif

                    @if($offer->highlight_badge)
                        <div class="absolute top-4 left-4 bg-brand-gold text-brand-burgundy font-bold text-xs uppercase tracking-wider px-3.5 py-1 rounded-full border border-white/40 shadow">
                            <i class="fas fa-tag mr-1"></i> {{ $offer->highlight_badge }}
                        </div>
                    @endif

                    <div class="absolute bottom-4 left-6 right-6 text-white space-y-1">
                        <h1 class="font-serif text-2xl sm:text-4xl font-bold leading-tight">
                            {{ $offer->title }}
                        </h1>
                        @if($offer->subtitle)
                            <p class="text-xs sm:text-sm text-brand-cream/90 font-light">
                                {{ $offer->subtitle }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Offer Body Details -->
                <div class="p-6 sm:p-10 space-y-6">
                    
                    <div class="space-y-3 text-xs sm:text-sm text-brand-charcoal leading-relaxed">
                        <h3 class="font-serif text-lg font-bold text-brand-charcoal">
                            Offer Overview &amp; Benefits
                        </h3>
                        <p>{{ $offer->description }}</p>
                    </div>

                    <!-- Voucher & Validity Card -->
                    <div class="p-5 rounded-2xl bg-brand-offwhite border border-brand-gold/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 text-xs">
                        @if($offer->coupon_code)
                            <div>
                                <span class="text-brand-muted-brown text-[11px] font-semibold uppercase tracking-wider block">Use Promo Code:</span>
                                <div class="mt-1 inline-flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-brand-burgundy">
                                    <span class="font-mono text-base font-bold text-brand-burgundy select-all">{{ $offer->coupon_code }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $offer->coupon_code }}'); alert('Code copied: {{ $offer->coupon_code }}');" class="text-[11px] text-brand-royal-rose hover:underline font-semibold">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="text-left sm:text-right">
                            <span class="text-brand-muted-brown text-[11px] font-semibold uppercase tracking-wider block">Validity Period:</span>
                            <span class="font-bold text-brand-burgundy text-sm mt-1 block">
                                @if($offer->valid_until)
                                    Valid Until {{ $offer->valid_until->format('d F Y') }}
                                @else
                                    {{ $offer->valid_till ?: 'Ongoing Wedding Season' }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    @if($offer->terms)
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-amber-950">
                                <i class="fas fa-info-circle text-amber-600"></i> Terms &amp; Conditions
                            </div>
                            <p class="text-[11px] leading-relaxed text-amber-800">{{ $offer->terms }}</p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                        <a 
                            href="{{ route('quote', ['offer' => $offer->coupon_code ?: $offer->title]) }}" 
                            class="w-full sm:flex-1 py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all text-center flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-file-invoice text-brand-gold"></i>
                            <span>Apply Offer on Custom Quote</span>
                        </a>

                        <a 
                            href="{{ route('decorations.index') }}" 
                            class="w-full sm:w-auto py-3.5 px-6 text-xs font-bold text-brand-charcoal bg-brand-offwhite hover:bg-white rounded-xl border border-brand-light-border transition-colors text-center"
                        >
                            Explore Decorations
                        </a>
                    </div>

                </div>
            </div>

            <!-- Featured Packages Carousel/Grid -->
            @if(!empty($featuredPackages) && $featuredPackages->isNotEmpty())
                <div class="space-y-4 pt-6">
                    <h3 class="font-serif text-xl font-bold text-brand-charcoal text-center">
                        Popular Packages Eligible for This Offer
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($featuredPackages as $fp)
                            <x-package-card :package="$fp" />
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
