@extends('layouts.app')

@section('title', 'Frequently Asked Questions | Aditya Utsav Bihar')
@section('meta_description', 'Find answers to common questions about wedding decoration bookings, lagan dates, pricing, and floral setups in Bihar.')

@section('content')
<section class="bg-brand-deep-burgundy text-white py-14 border-b border-brand-gold relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="inline-block text-xs font-bold uppercase tracking-[0.2em] text-brand-gold mb-2">
            HELP &amp; INFORMATION
        </span>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
            Frequently Asked Questions
        </h1>
        <p class="text-xs sm:text-sm text-brand-cream/80 max-w-xl mx-auto mt-2">
            Everything you need to know about booking, themes, timings, and payments for your wedding ceremonies.
        </p>
    </div>
</section>

<section class="py-16 bg-brand-cream min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @foreach($faqs as $index => $faq)
            <div class="bg-white rounded-xl border border-brand-light-border shadow-soft-luxury overflow-hidden">
                <button 
                    type="button" 
                    onclick="toggleFaqPage({{ $index }})" 
                    class="w-full px-6 py-4.5 text-left font-serif text-base sm:text-lg font-bold text-brand-charcoal hover:text-brand-burgundy flex items-center justify-between gap-4 transition-colors"
                >
                    <span>{{ $faq->question }}</span>
                    <i class="fas fa-chevron-down text-xs text-brand-gold transform transition-transform duration-300 {{ $index === 0 ? 'rotate-180' : '' }}" id="faq-page-icon-{{ $index }}"></i>
                </button>
                <div 
                    id="faq-page-content-{{ $index }}" 
                    class="{{ $index === 0 ? 'block' : 'hidden' }} px-6 pb-5 text-xs sm:text-sm text-brand-muted-brown leading-relaxed border-t border-brand-light-border/60 pt-3"
                >
                    {{ $faq->answer }}
                </div>
            </div>
        @endforeach

        <div class="mt-12 text-center p-8 bg-white rounded-2xl border border-brand-gold/40 shadow-soft-luxury space-y-3">
            <h3 class="font-serif text-xl font-bold text-brand-charcoal">Have a question not answered here?</h3>
            <p class="text-xs text-brand-muted-brown">Feel free to call our Siwan office directly or check your date availability.</p>
            <div class="pt-2 flex justify-center gap-3">
                <a href="{{ route('contact') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-brand-burgundy bg-brand-cream rounded border border-brand-gold">
                    Contact Us
                </a>
                <button type="button" onclick="openAvailabilityModal()" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy rounded">
                    Check Date Availability
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleFaqPage(index) {
        const content = document.getElementById(`faq-page-content-${index}`);
        const icon = document.getElementById(`faq-page-icon-${index}`);
        if (!content || !icon) return;

        const isHidden = content.classList.contains('hidden');
        if (isHidden) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
@endsection
