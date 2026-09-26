@extends('layouts.app')

@section('title', 'Wedding Decoration FAQs | Aditya Utsav Bihar')
@section('meta_description', 'Frequently asked questions about wedding decoration bookings, prices, cancellation policies, and service areas in Bihar and Eastern UP.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Frequently Asked Questions', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-question-circle text-[10px]"></i> Clear &amp; Transparent Answers
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Frequently Asked Questions
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Everything you need to know about booking, floral customization, payments, and service execution with Aditya Utsav.
            </p>
        </div>
    </section>

    <!-- FAQ Categories & Accordion Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            @foreach($categories as $categoryName)
                @php
                    $faqsInCategory = $groupedFaqs[$categoryName] ?? collect();
                @endphp

                @if($faqsInCategory->isNotEmpty())
                    <div class="space-y-4">
                        
                        <!-- Category Header -->
                        <div class="flex items-center gap-3 pb-2 border-b border-brand-light-border">
                            <span class="w-8 h-8 rounded-full bg-brand-burgundy text-brand-gold flex items-center justify-center text-xs font-bold shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <div>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                                    {{ $categoryName }} Questions
                                </h2>
                            </div>
                        </div>

                        <!-- Accordion Items List -->
                        <div class="space-y-3">
                            @foreach($faqsInCategory as $faq)
                                <div class="faq-item bg-white rounded-2xl border border-brand-light-border shadow-soft-luxury overflow-hidden transition-all duration-200">
                                    <button 
                                        type="button" 
                                        class="faq-header w-full text-left p-5 sm:p-6 flex items-center justify-between gap-4 font-serif text-sm sm:text-base font-bold text-brand-charcoal hover:text-brand-burgundy transition-colors focus:outline-none"
                                        aria-expanded="false"
                                        onclick="toggleFaqAccordion(this)"
                                    >
                                        <span class="flex items-center gap-3">
                                            <i class="fas fa-question text-brand-gold text-xs shrink-0"></i>
                                            <span>{{ $faq->question }}</span>
                                        </span>
                                        <div class="w-7 h-7 rounded-full bg-brand-offwhite text-brand-charcoal flex items-center justify-center text-xs shrink-0 transition-transform duration-300 faq-icon">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </button>

                                    <!-- Accordion Answer Body -->
                                    <div class="faq-body hidden px-5 sm:px-6 pb-5 sm:pb-6 text-xs sm:text-sm text-brand-muted-brown leading-relaxed border-t border-brand-light-border/60 pt-4 bg-brand-offwhite/30">
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif
            @endforeach

            <!-- Still Have Questions Banner -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-brand-gold/40 shadow-soft-luxury text-center space-y-4">
                <div class="w-14 h-14 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl border border-brand-gold/40">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                    Have a Question Not Listed Here?
                </h3>
                <p class="text-xs sm:text-sm text-brand-muted-brown max-w-xl mx-auto leading-relaxed">
                    Our team in Siwan is happy to answer any custom wedding decor questions, date arrangements, or stage dimension queries.
                </p>
                <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('contact') }}" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all">
                        Contact Siwan Team
                    </a>
                    <a href="https://wa.me/919931200000" target="_blank" rel="noopener noreferrer" class="px-5 py-3 text-xs font-bold text-green-900 bg-green-50 hover:bg-green-100 border border-green-300 rounded-xl transition-colors flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-600 text-sm"></i>
                        <span>WhatsApp Us</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Accessible Vanilla JavaScript Accordion -->
    <script>
        function toggleFaqAccordion(button) {
            const body = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            const isExpanded = button.getAttribute('aria-expanded') === 'true';

            if (isExpanded) {
                body.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                button.setAttribute('aria-expanded', 'false');
            } else {
                body.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                button.setAttribute('aria-expanded', 'true');
            }
        }
    </script>

@endsection
