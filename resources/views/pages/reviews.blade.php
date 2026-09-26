@extends('layouts.app')

@section('title', 'Family Reviews & Testimonials | Aditya Utsav Bihar')
@section('meta_description', 'Customer reviews and family testimonials for wedding decorations, Jaimala stages, and Mandap setups across Siwan, Gopalganj, and Chapra.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            CLIENT EXPERIENCES
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Family Reviews &amp; Feedback
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Read authentic feedback from families who entrusted their weddings and ceremonies to Aditya Utsav.
        </p>
    </div>
</section>

<section class="py-16 bg-brand-cream min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                <x-review-card :review="$review" />
            @endforeach
        </div>
    </div>
</section>
@endsection
