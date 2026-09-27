@extends('layouts.app')

@section('title', 'Wedding Decoration Offers & Deals in Bihar | Aditya Utsav')
@section('meta_description', 'Discover special seasonal discounts and package offers for wedding decorations in Siwan, Gopalganj, Patna, and Gorakhpur.')

@section('content')

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-percentage text-[10px]"></i> Wedding Season Savings
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Special Wedding Offers &amp; Deals
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Exclusive seasonal privileges and combo savings on traditional Bihar and Eastern UP wedding setups.
            </p>
        </div>
    </section>

    <!-- Offers Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if($offers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($offers as $offer)
                        <x-offer-card :offer="$offer" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-3xl border border-brand-light-border p-8 max-w-md mx-auto space-y-3 shadow-soft-luxury">
                    <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-brand-charcoal">No current offers</h3>
                    <p class="text-xs text-brand-muted-brown">
                        We are updating our seasonal wedding offers. In the meantime, you can explore our full decoration catalog or get a tailored quote.
                    </p>
                    <a href="{{ route('decorations.index') }}" class="inline-block mt-2 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold">
                        Explore Decorations
                    </a>
                </div>
            @endif

            <!-- Help / Custom Quote Banner -->
            <div class="mt-12 bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Have a Specific Budget?</span>
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                        Get a Tailored Quote That Fits Your Budget
                    </h3>
                    <p class="text-xs text-brand-muted-brown max-w-xl">
                        Submit your event details and budget range. Our event team will design a customized floral and stage arrangement for your celebration.
                    </p>
                </div>
                <a href="{{ route('quote') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all shrink-0">
                    Request Custom Quote
                </a>
            </div>

        </div>
    </section>

@endsection
