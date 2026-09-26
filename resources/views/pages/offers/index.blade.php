@extends('layouts.app')

@section('title', 'Wedding Season Offers | Aditya Utsav Bihar')
@section('meta_description', 'Exclusive wedding season decoration offers and complimentary inclusions for families booking in Siwan, Gopalganj and Chapra.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            LAGAN SEASON PRIVILEGES
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Wedding Season Offers
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Special package enhancements and complimentary floral setup additions for upcoming wedding dates.
        </p>
    </div>
</section>

<section class="py-16 bg-brand-cream min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        @foreach($offers as $offer)
            <div class="bg-white rounded-2xl overflow-hidden border-2 border-brand-gold shadow-card-hover grid grid-cols-1 md:grid-cols-12">
                <div class="md:col-span-5 h-64 md:h-auto bg-brand-charcoal relative">
                    <img src="{{ $offer->image_url }}" alt="Aditya Utsav {{ $offer->title }} - Traditional Bihar wedding decoration offer" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <span class="absolute top-4 left-4 bg-brand-gold text-brand-deep-burgundy text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full shadow">
                        {{ $offer->highlight_badge }}
                    </span>
                </div>
                <div class="md:col-span-7 p-7 sm:p-8 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">{{ $offer->title }}</h3>
                        <p class="text-xs font-semibold text-brand-royal-rose uppercase tracking-wider mt-1">{{ $offer->subtitle }}</p>
                        <p class="text-sm text-brand-muted-brown leading-relaxed mt-3">{{ $offer->description }}</p>
                        
                        <div class="mt-4 p-3.5 rounded-xl bg-brand-cream border border-brand-gold/40 text-xs text-brand-burgundy font-medium flex items-center gap-2">
                            <i class="fas fa-gift text-brand-gold text-base"></i>
                            <span>{{ $offer->discount_text }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-brand-light-border flex flex-wrap items-center justify-between gap-3">
                        <span class="text-xs text-brand-muted-brown">Valid For: <strong class="text-brand-charcoal">{{ $offer->valid_till }}</strong></span>
                        <button type="button" onclick="openAvailabilityModal('{{ $offer->title }}', 'Siwan')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded border border-brand-gold shadow transition-all">
                            Claim Offer &amp; Book
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
