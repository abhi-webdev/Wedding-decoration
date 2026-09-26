@extends('layouts.app')

@php
    $pageTitle = $selectedCategory 
        ? $selectedCategory->name . ' Decoration in Bihar | Aditya Utsav' 
        : 'Wedding Decorations in Bihar | Aditya Utsav Catalog';
    
    $pageDescription = $selectedCategory 
        ? $selectedCategory->description 
        : 'Explore traditional and modern Bihar wedding decorations for Jaimala stages, Vedic Mandaps, Haldi urlis, Mehendi courtyards, and grand receptions.';
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@section('content')

<!-- 1. Breadcrumbs Navigation -->
<x-breadcrumb :items="array_filter([
    ['label' => 'Decorations', 'url' => route('decorations.index')],
    $selectedCategory ? ['label' => $selectedCategory->name, 'url' => ''] : null,
])" />

<!-- 2. Page Header Banner -->
<section class="bg-gradient-to-b from-brand-deep-burgundy to-brand-burgundy text-white py-12 sm:py-16 border-b border-brand-gold relative overflow-hidden">
    <!-- Subtle Gold Pattern Background -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:20px_20px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
        <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.25em] text-brand-gold">
            <span class="w-6 h-[1.5px] bg-brand-gold"></span>
            BIHAR WEDDING &amp; EVENT CATALOG
            <span class="w-6 h-[1.5px] bg-brand-gold"></span>
        </span>

        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight">
            {{ $selectedCategory ? $selectedCategory->name . ' Decorations' : 'Find the Perfect Decoration for Your Celebration' }}
        </h1>

        <p class="text-xs sm:text-sm text-brand-cream/85 max-w-2xl mx-auto leading-relaxed">
            {{ $selectedCategory ? $selectedCategory->description : 'Explore beautiful traditional and modern wedding and event decoration designs from Aditya Utsav across Siwan, Gopalganj, Chapra & nearby areas.' }}
        </p>
    </div>
</section>

<!-- 3. Top Category Cards Grid (When on main /decorations or category browsing) -->
<section class="py-10 bg-brand-cream border-b border-brand-light-border/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                    Ceremony Categories
                </h2>
                <p class="text-xs text-brand-muted-brown">
                    Select a ritual to discover tailored setups and themes
                </p>
            </div>
            @if($selectedCategory)
                <a href="{{ route('decorations.index') }}" class="text-xs font-bold text-brand-burgundy hover:text-brand-royal-rose transition-colors flex items-center gap-1">
                    <i class="fas fa-th-large text-brand-gold"></i> View All Categories
                </a>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
                <a 
                    href="{{ route('decorations.category', $cat->slug) }}" 
                    class="group relative rounded-xl overflow-hidden bg-white border {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'border-2 border-brand-gold shadow-card-hover ring-2 ring-brand-gold/30' : 'border-brand-light-border hover:border-brand-gold/60 shadow-soft-luxury' }} p-3 flex flex-col items-center text-center transition-all duration-300"
                >
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden mb-2.5 bg-brand-offwhite border border-brand-gold/30 flex-shrink-0">
                        <img 
                            src="{{ $cat->safe_image }}" 
                            alt="{{ $cat->name }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            loading="lazy"
                            onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
                        />
                    </div>
                    <span class="font-serif text-xs sm:text-sm font-bold text-brand-charcoal group-hover:text-brand-burgundy transition-colors truncate w-full">
                        {{ $cat->name }}
                    </span>
                    <span class="text-[10px] text-brand-muted-brown font-medium mt-0.5">
                        {{ $cat->decorations_count }} Designs
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. Main Catalog Section with Filter Sidebar & Grid -->
<section class="py-12 bg-brand-offwhite min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Desktop Persistent Filter Sidebar (3 cols) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-24">
                <x-filter-sidebar 
                    :categories="$categories" 
                    :selectedCategory="$selectedCategory" 
                    :styles="$styles" 
                    :colors="$colors" 
                    :guestCapacities="$guestCapacities" 
                    :biharLocations="$biharLocations" 
                    :upLocations="$upLocations" 
                />
            </aside>

            <!-- Right Column: Results Grid & Toolbar (9 cols) -->
            <main class="lg:col-span-9 space-y-6">
                
                <!-- Toolbar: Result Count, Mobile Filter Trigger, Sorting -->
                <div class="bg-white rounded-xl p-4 border border-brand-light-border shadow-soft-luxury flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <!-- Left: Count & Mobile Filter Button -->
                    <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-3">
                        <!-- Mobile Filter Button (Visible on mobile/tablet) -->
                        <button 
                            type="button" 
                            onclick="toggleMobileFilter(true)" 
                            class="lg:hidden inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy rounded-lg border border-brand-gold shadow-sm"
                        >
                            <i class="fas fa-sliders-h text-brand-gold"></i>
                            <span>Filters</span>
                            @if(request()->hasAny(['category', 'search', 'price_range', 'min_price', 'max_price', 'location', 'style', 'color', 'guest_capacity']))
                                <span class="w-2 h-2 rounded-full bg-brand-gold animate-ping"></span>
                            @endif
                        </button>

                        <div class="text-xs text-brand-muted-brown">
                            Showing <strong class="text-brand-charcoal">{{ $decorations->firstItem() ?? 0 }}–{{ $decorations->lastItem() ?? 0 }}</strong> of <strong class="text-brand-charcoal">{{ $decorations->total() }}</strong> decorations
                        </div>
                    </div>

                    <!-- Right: Sort By Dropdown -->
                    <div class="w-full sm:w-auto flex items-center justify-end gap-2">
                        <label for="sort-select" class="text-xs font-semibold text-brand-charcoal whitespace-nowrap">
                            Sort By:
                        </label>
                        <select 
                            id="sort-select" 
                            onchange="window.location.href = this.value" 
                            class="px-3 py-1.5 text-xs bg-brand-offwhite border border-brand-light-border rounded-lg text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                        >
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured', 'page' => 1]) }}" {{ $sort === 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => 1]) }}" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low', 'page' => 1]) }}" {{ $sort === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high', 'page' => 1]) }}" {{ $sort === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc', 'page' => 1]) }}" {{ $sort === 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc', 'page' => 1]) }}" {{ $sort === 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Chips (If any active) -->
                @if(request()->hasAny(['category', 'search', 'price_range', 'location', 'style', 'color', 'guest_capacity']))
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-muted-brown mr-1">Active Filters:</span>
                        
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Keyword: "{{ request('search') }}"</span>
                                <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @if(request('category') && request('category') !== 'all')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Category: {{ $selectedCategory ? $selectedCategory->name : request('category') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @if(request('price_range'))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Price Range: {{ str_replace('_', ' ', request('price_range')) }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['price_range' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @if(request('location') && request('location') !== 'all')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Location: {{ request('location') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['location' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @if(request('style') && request('style') !== 'all')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Style: {{ request('style') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['style' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        @if(request('color') && request('color') !== 'all')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs bg-white border border-brand-light-border text-brand-charcoal">
                                <span>Color: {{ request('color') }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['color' => null, 'page' => 1]) }}" class="text-gray-400 hover:text-red-500"><i class="fas fa-times"></i></a>
                            </span>
                        @endif

                        <a href="{{ route('decorations.index') }}" class="text-xs font-semibold text-brand-burgundy hover:underline ml-2">
                            Clear All Filters
                        </a>
                    </div>
                @endif

                <!-- 5. Decorations Cards Grid -->
                @if($decorations->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($decorations as $decoration)
                            <x-decoration-card :decoration="$decoration" />
                        @endforeach
                    </div>

                    <!-- 6. Pagination with preserved query parameters -->
                    <div class="pt-8 border-t border-brand-light-border/70 flex items-center justify-center">
                        {{ $decorations->links() }}
                    </div>
                @else
                    <!-- 7. Empty State -->
                    <div class="bg-white rounded-2xl border border-brand-light-border p-12 text-center max-w-lg mx-auto shadow-soft-luxury space-y-4">
                        <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="font-serif text-2xl font-bold text-brand-charcoal">
                            No decorations found
                        </h3>
                        <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed">
                            We couldn't find any decorations matching your active search or filters. Try adjusting your price budget, ceremony, or style.
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('decorations.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow">
                                <i class="fas fa-undo text-brand-gold"></i>
                                <span>Clear Filters &amp; Browse All</span>
                            </a>
                        </div>
                    </div>
                @endif

            </main>
        </div>

    </div>
</section>

<!-- Mobile Filter Modal Component -->
<x-mobile-filter-modal 
    :categories="$categories" 
    :selectedCategory="$selectedCategory" 
    :styles="$styles" 
    :colors="$colors" 
    :guestCapacities="$guestCapacities" 
/>

@endsection
