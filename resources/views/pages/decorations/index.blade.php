@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' Decorations | ' : '') . 'Aditya Utsav Bihar')
@section('meta_description', 'Explore authentic Bihar wedding decoration setups including Jaimala stages, Vedic Mandaps, Haldi & Mehendi setups across Siwan and nearby districts.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            BIHAR WEDDING PORTFOLIO
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            {{ $selectedCategory ? $selectedCategory->name . ' Decorations' : 'All Ceremony Decorations' }}
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            {{ $selectedCategory ? $selectedCategory->description : 'Explore traditional and modern stage designs, sacred Mandaps, and floral setups across Bihar.' }}
        </p>
    </div>
</section>

<section class="py-12 bg-brand-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Category Filters -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            <a href="{{ route('decorations.index') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full {{ !$selectedCategory ? 'bg-brand-burgundy text-brand-cream border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }} shadow-sm transition-all">
                All Ceremonies
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('decorations.category', $cat->slug) }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-full {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'bg-brand-burgundy text-brand-cream border border-brand-gold' : 'bg-white text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border' }} shadow-sm transition-all">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Decorations Grid -->
        @if($decorations->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($decorations as $decoration)
                    <x-decoration-card :decoration="$decoration" />
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-2xl border border-brand-light-border p-8 max-w-md mx-auto">
                <i class="fas fa-search text-3xl text-brand-gold mb-3"></i>
                <h3 class="font-serif text-xl font-bold text-brand-charcoal">No decorations found</h3>
                <p class="text-xs text-brand-muted-brown mt-1 mb-4">Try clearing filters or browse all categories.</p>
                <a href="{{ route('decorations.index') }}" class="inline-block px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy rounded hover:bg-brand-deep-burgundy">
                    View All Decorations
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
