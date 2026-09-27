@extends('layouts.app')

@section('title', 'Quote Request Received #' . $quoteRequest->quote_reference . ' | Aditya Utsav Bihar')
@section('meta_description', 'Your custom wedding decoration quote request #' . $quoteRequest->quote_reference . ' has been received. Our event team will contact you shortly.')

@section('content')

    <!-- Success Confirmation Section -->
    <section class="py-12 sm:py-20 bg-brand-cream relative">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-12 border border-brand-light-border shadow-soft-luxury text-center space-y-5">
                
                <!-- Animated Icon -->
                <div class="w-20 h-20 rounded-full bg-emerald-100 border-2 border-emerald-500 text-emerald-700 flex items-center justify-center mx-auto text-3xl shadow-sm">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $quoteRequest->status_badge_classes }} border">
                        Status: New Request Received
                    </span>
                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-brand-charcoal pt-2">
                        Your Quote Request Has Been Received!
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-muted-brown max-w-lg mx-auto leading-relaxed">
                        Thank you for reaching out to <strong class="text-brand-burgundy">Aditya Utsav</strong>. Our event specialists will review your requirements and contact you with a customized estimate.
                    </p>
                </div>

                <!-- Reference Badge -->
                <div class="inline-flex flex-col sm:flex-row items-center justify-center gap-2 p-3 sm:px-6 sm:py-3 rounded-2xl bg-brand-offwhite border border-brand-gold/50 max-w-md mx-auto">
                    <span class="text-xs text-brand-muted-brown uppercase tracking-wider font-semibold">Your Quote Reference:</span>
                    <span class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy tracking-wider selection:bg-brand-gold">
                        {{ $quoteRequest->quote_reference }}
                    </span>
                </div>

                <!-- Details Summary Table -->
                <div class="p-5 rounded-2xl bg-brand-offwhite border border-brand-light-border text-left text-xs space-y-3">
                    <h3 class="font-serif text-sm font-bold text-brand-charcoal uppercase tracking-wider pb-1 border-b border-brand-light-border">
                        Submitted Specifications
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-brand-muted-brown">
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Client Name:</span>
                            <span>{{ $quoteRequest->customer_name }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Contact Phone:</span>
                            <span>{{ $quoteRequest->customer_phone }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Event Type:</span>
                            <span>{{ $quoteRequest->event_type }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Event Date:</span>
                            <span class="font-bold text-brand-burgundy">{{ $quoteRequest->formatted_event_date }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Location:</span>
                            <span>{{ $quoteRequest->city }}, {{ $quoteRequest->state }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Budget Range:</span>
                            <span>{{ $quoteRequest->budget_range }}</span>
                        </div>
                    </div>
                </div>

                <!-- What Happens Next Banner -->
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-left text-xs text-amber-900 space-y-1">
                    <div class="font-bold flex items-center gap-2 text-amber-950">
                        <i class="fas fa-info-circle text-amber-600"></i> What Happens Next?
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Our regional event coordinator will contact you on <strong class="text-amber-950">{{ $quoteRequest->customer_phone }}</strong> within <strong>2–4 business hours</strong> to finalize stage dimensions and share your digital quote via WhatsApp/Email.
                    </p>
                </div>

                <!-- Action CTAs -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a 
                        href="https://wa.me/919931200000?text={{ urlencode('Namaste Aditya Utsav! I have submitted custom quote reference #' . $quoteRequest->quote_reference . ' for ' . $quoteRequest->event_type . ' on ' . $quoteRequest->formatted_event_date . ' in ' . $quoteRequest->city . '. Please review.') }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full sm:w-auto px-6 py-3 text-xs font-bold uppercase tracking-wider text-green-900 bg-green-100 hover:bg-green-200 rounded-xl border border-green-300 transition-all flex items-center justify-center gap-2 shadow-sm"
                    >
                        <i class="fab fa-whatsapp text-green-600 text-base"></i>
                        <span>Connect on WhatsApp with Reference ID</span>
                    </a>

                    <a 
                        href="{{ route('decorations.index') }}" 
                        class="w-full sm:w-auto px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all text-center"
                    >
                        Browse Decoration Catalog
                    </a>
                </div>

            </div>

        </div>
    </section>

@endsection
