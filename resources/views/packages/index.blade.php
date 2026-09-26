@extends('layouts.app')

@section('title', 'Wedding Decoration Packages in Bihar | Aditya Utsav')
@section('meta_description', 'Explore comprehensive wedding decoration packages in Bihar & Eastern UP. From Royal Jaimala to Vedic Mandap and 3-Day Complete Vivah Packages.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Wedding Packages', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-crown text-[10px]"></i> Curated Celebration Combos
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Wedding Decoration Packages
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Beautifully planned decoration packages for every celebration across Siwan, Patna, Gopalganj, and nearby districts.
            </p>
        </div>
    </section>

    <!-- Packages Listing Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Controls Bar: Count & Sorting -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4 border-b border-brand-light-border">
                <div class="text-xs text-brand-muted-brown">
                    Showing <strong class="text-brand-charcoal">{{ $packages->count() }}</strong> complete decoration packages
                </div>

                <!-- Sort Filter -->
                <div class="flex items-center gap-2">
                    <label for="package-sort" class="text-xs font-semibold text-brand-charcoal whitespace-nowrap">Sort By:</label>
                    <form method="GET" action="{{ route('packages.index') }}" id="sort-form">
                        <select 
                            name="sort" 
                            id="package-sort" 
                            onchange="document.getElementById('sort-form').submit()"
                            class="px-3 py-1.5 text-xs bg-white border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium cursor-pointer"
                        >
                            <option value="featured" {{ ($sort ?? 'featured') === 'featured' ? 'selected' : '' }}>Featured First</option>
                            <option value="price_low" {{ ($sort ?? '') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ ($sort ?? '') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Packages Grid -->
            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($packages as $package)
                        <x-package-card :package="$package" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-brand-light-border p-8 max-w-md mx-auto space-y-3">
                    <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-brand-charcoal">Packages are being updated</h3>
                    <p class="text-xs text-brand-muted-brown">
                        Our wedding planners are curating new festive season packages. You can explore individual decorations or request a custom quote.
                    </p>
                    <a href="{{ route('decorations.index') }}" class="inline-block mt-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl">
                        Browse Decorations
                    </a>
                </div>
            @endif

            <!-- Custom Package Help Banner -->
            <div class="mt-12 bg-white rounded-2xl p-6 sm:p-10 border border-brand-gold/40 shadow-soft-luxury flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Need Custom Requirements?</span>
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                        Create Your Own Tailored Wedding Package
                    </h3>
                    <p class="text-xs text-brand-muted-brown max-w-xl leading-relaxed">
                        Have specific floral choices, stage sizes, or multi-venue logistics? Our Siwan operations managers can customize any package for your family.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('quote') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all">
                        <i class="fas fa-edit mr-1 text-brand-gold"></i> Get Custom Quote
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection
