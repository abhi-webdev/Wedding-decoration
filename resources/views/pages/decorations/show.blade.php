@extends('layouts.app')

@section('title', $decoration->name . ' | Aditya Utsav Bihar')
@section('meta_description', $decoration->tagline ?? $decoration->description)

@section('content')
<!-- Breadcrumbs -->
<div class="bg-brand-offwhite border-b border-brand-light-border py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-muted-brown flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-brand-burgundy">Home</a>
        <span>/</span>
        <a href="{{ route('decorations.index') }}" class="hover:text-brand-burgundy">Decorations</a>
        <span>/</span>
        <a href="{{ route('decorations.category', $decoration->category->slug) }}" class="hover:text-brand-burgundy">{{ $decoration->category->name }}</a>
        <span>/</span>
        <span class="text-brand-burgundy font-semibold truncate">{{ $decoration->name }}</span>
    </div>
</div>

<section class="py-12 bg-brand-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left: Image Showcase -->
            <div class="lg:col-span-7 space-y-4">
                <div class="rounded-2xl overflow-hidden bg-brand-charcoal border border-brand-gold/40 shadow-card-hover h-96 sm:h-[480px]">
                    <img id="main-decoration-image" src="{{ $decoration->primary_image }}" alt="Traditional Bihar {{ $decoration->name }} in {{ $decoration->location }}" class="w-full h-full object-cover">
                </div>

                @if($decoration->images->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($decoration->images as $img)
                            <button type="button" onclick="document.getElementById('main-decoration-image').src='{{ $img->image_url }}'" class="rounded-lg overflow-hidden border-2 border-transparent hover:border-brand-gold h-20 bg-brand-offwhite">
                                <img src="{{ $img->image_url }}" alt="Traditional Bihar {{ $decoration->name }} - {{ $img->caption }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Details & Booking Action -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-5">
                    
                    <div class="flex items-center justify-between">
                        <span class="bg-brand-burgundy/10 text-brand-burgundy text-xs font-bold uppercase tracking-wider px-3 py-1 rounded">
                            {{ $decoration->category->name }}
                        </span>
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-bold">
                            <i class="fas fa-star"></i>
                            <span>{{ number_format($decoration->rating, 1) }}</span>
                            <span class="text-brand-muted-brown">({{ $decoration->reviews_count }} reviews)</span>
                        </div>
                    </div>

                    <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                        {{ $decoration->name }}
                    </h1>

                    @if($decoration->tagline)
                        <p class="text-xs sm:text-sm text-brand-royal-rose font-medium">
                            {{ $decoration->tagline }}
                        </p>
                    @endif

                    <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-brand-muted-brown uppercase tracking-wider block">Estimated Price</span>
                            <span class="font-serif text-2xl sm:text-3xl font-bold text-brand-burgundy">{{ $decoration->formatted_price }}</span>
                        </div>
                        <span class="text-xs text-brand-muted-brown">per setup / ritual</span>
                    </div>

                    <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed">
                        {{ $decoration->description }}
                    </p>

                    @if(!empty($decoration->features) && is_array($decoration->features))
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-brand-charcoal mb-2.5">
                                Included Key Elements:
                            </h4>
                            <ul class="space-y-1.5 text-xs text-brand-muted-brown">
                                @foreach($decoration->features as $feat)
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check-circle text-brand-gold text-[11px]"></i>
                                        <span>{{ $feat }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-brand-light-border space-y-3">
                        <button type="button" onclick="openAvailabilityModal('{{ $decoration->name }}', 'Siwan')" class="w-full py-3 px-4 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow-md hover:shadow-gold-glow transition-all">
                            <i class="fas fa-calendar-check mr-2 text-brand-gold"></i>
                            Check Date &amp; Book Decoration
                        </button>
                        <a href="{{ route('quote') }}" class="block w-full text-center py-2.5 px-4 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-light-border/50 rounded-lg border border-brand-light-border transition-colors">
                            Request Customized Quote
                        </a>
                    </div>

                </div>

                <!-- Trust Box -->
                <div class="bg-brand-offwhite rounded-xl p-4 border border-brand-light-border text-xs text-brand-muted-brown space-y-1.5">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-brand-gold"></i>
                        <span class="font-semibold text-brand-charcoal">Siwan Local Service Hub</span>
                    </div>
                    <p>Serving Siwan, Mairwa, Gopalganj, Chapra and selected nearby UP districts.</p>
                </div>
            </div>

        </div>

        <!-- Related Decorations -->
        @if($relatedDecorations->count() > 0)
            <div class="mt-16 pt-12 border-t border-brand-light-border">
                <h3 class="font-serif text-2xl font-bold text-brand-charcoal mb-8">
                    More {{ $decoration->category->name }} Designs
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedDecorations as $rel)
                        <x-decoration-card :decoration="$rel" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
