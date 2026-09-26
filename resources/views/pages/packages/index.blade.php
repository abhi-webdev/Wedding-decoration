@extends('layouts.app')

@section('title', 'Bihar Wedding Packages | Aditya Utsav')
@section('meta_description', 'Complete wedding decoration packages for Haldi, Mehendi, Sangeet, Vedic Mandap, and Receptions across Siwan and Bihar.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            ALL-IN-ONE CELEBRATIONS
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Wedding Decoration Packages
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Curated ceremony bundles combining traditional Bihar artistry, fresh flowers, throne seating, and dedicated on-site decor management.
        </p>
    </div>
</section>

<section class="py-16 bg-brand-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            @foreach($packages as $index => $pkg)
                <x-package-card :package="$pkg" :featured="$index === 0" />
            @endforeach
        </div>
    </div>
</section>
@endsection
