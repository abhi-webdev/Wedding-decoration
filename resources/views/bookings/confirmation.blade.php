@extends('layouts.app')

@section('title', 'Booking Request Received #' . $booking->booking_reference . ' | Aditya Utsav Bihar')
@section('meta_description', 'Your booking request #' . $booking->booking_reference . ' has been received. Our Bihar event managers will review date availability and contact you with the finalized quote.')

@section('content')

    <!-- 1. Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Decorations', 'url' => route('decorations.index')],
        ['label' => $booking->decoration->name, 'url' => route('decorations.show', $booking->decoration->slug)],
        ['label' => 'Booking Request #' . $booking->booking_reference, 'url' => '']
    ]" />

    <!-- 2. Main Confirmation Section -->
    <section class="py-10 sm:py-16 bg-brand-cream relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Success Status Header Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury text-center space-y-4">
                
                <!-- Success Animated Check Icon -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-emerald-100 border-2 border-emerald-500 text-emerald-700 flex items-center justify-center mx-auto text-2xl sm:text-3xl shadow-sm">
                    <i class="fas fa-calendar-check"></i>
                </div>

                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $booking->status_badge_classes }} border">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        Status: {{ $booking->status_label }}
                    </span>

                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-brand-charcoal pt-2">
                        Booking Request Received!
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-muted-brown max-w-xl mx-auto leading-relaxed">
                        Thank you for choosing <strong class="text-brand-burgundy">Aditya Utsav</strong>. We have successfully received your celebration decoration request.
                    </p>
                </div>

                <!-- Booking Reference Bar -->
                <div class="inline-flex flex-col sm:flex-row items-center justify-center gap-2 p-3 sm:px-6 sm:py-2.5 rounded-xl bg-brand-offwhite border border-brand-gold/40 max-w-md mx-auto">
                    <span class="text-xs text-brand-muted-brown uppercase tracking-wider font-semibold">Your Reference ID:</span>
                    <span class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy tracking-wider selection:bg-brand-gold">
                        {{ $booking->booking_reference }}
                    </span>
                </div>

                <!-- Important Pending Notice Banner -->
                <div class="p-4 rounded-xl bg-amber-50/90 border border-amber-200 text-left text-xs text-amber-900 space-y-1 max-w-2xl mx-auto">
                    <div class="font-bold flex items-center gap-2 text-amber-950">
                        <i class="fas fa-info-circle text-amber-600 text-sm"></i>
                        Next Steps &amp; Confirmation Process
                    </div>
                    <p class="text-[11px] sm:text-xs text-amber-800 leading-relaxed">
                        Our event team in Siwan is currently verifying crew and materials availability for <strong class="text-amber-950">{{ $booking->formatted_event_date }}</strong> in <strong class="text-amber-950">{{ $booking->city }}, {{ $booking->state }}</strong>. We will call you on <strong class="text-amber-950">{{ $booking->customer_phone }}</strong> within <strong>2–4 hours</strong> with the confirmed quotation.
                    </p>
                </div>

            </div>

            <!-- Booking Summary Card -->
            <x-booking-summary 
                :decoration="$booking->decoration" 
                :booking="$booking"
                :isConfirmation="true"
            />

            <!-- Step-by-Step Fulfillment Timeline -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-5">
                <h3 class="font-serif text-lg sm:text-xl font-bold text-brand-charcoal">
                    What Happens Next?
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                    <!-- Step 1: Request Submitted -->
                    <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200 space-y-1.5">
                        <div class="flex items-center gap-2 text-emerald-800 font-bold">
                            <span class="w-5 h-5 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px]">1</span>
                            <span>Request Submitted</span>
                        </div>
                        <p class="text-[11px] text-emerald-900/80 leading-relaxed">
                            Your reservation request has been logged in our system under reference <strong>{{ $booking->booking_reference }}</strong>.
                        </p>
                    </div>

                    <!-- Step 2: Verification -->
                    <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 space-y-1.5">
                        <div class="flex items-center gap-2 text-amber-900 font-bold">
                            <span class="w-5 h-5 rounded-full bg-amber-600 text-white flex items-center justify-center text-[10px]">2</span>
                            <span>Slot Verification</span>
                        </div>
                        <p class="text-[11px] text-amber-900/80 leading-relaxed">
                            Our team verifies transportation, flower inventory, and technician schedules for your venue.
                        </p>
                    </div>

                    <!-- Step 3: Quotation & Token -->
                    <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-1.5">
                        <div class="flex items-center gap-2 text-brand-charcoal font-bold">
                            <span class="w-5 h-5 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-[10px]">3</span>
                            <span>Quotation &amp; Token</span>
                        </div>
                        <p class="text-[11px] text-brand-muted-brown leading-relaxed">
                            You receive a detailed digital quotation via WhatsApp/Email. Pay nominal advance token to lock the date.
                        </p>
                    </div>

                    <!-- Step 4: Setup Day -->
                    <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-1.5">
                        <div class="flex items-center gap-2 text-brand-charcoal font-bold">
                            <span class="w-5 h-5 rounded-full bg-brand-gold text-brand-charcoal flex items-center justify-center text-[10px]">4</span>
                            <span>Flawless Execution</span>
                        </div>
                        <p class="text-[11px] text-brand-muted-brown leading-relaxed">
                            Aditya Utsav crew arrives on-site 4–6 hours prior for complete stage fabrication and lighting.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4 text-xs">
                <h3 class="font-serif text-lg font-bold text-brand-charcoal">
                    Client &amp; Delivery Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-brand-muted-brown">
                    <div>
                        <span class="font-semibold text-brand-charcoal block">Client Name:</span>
                        <span>{{ $booking->customer_name }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-brand-charcoal block">Contact Phone:</span>
                        <span>{{ $booking->customer_phone }}</span>
                    </div>
                    @if($booking->customer_email)
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Email:</span>
                            <span>{{ $booking->customer_email }}</span>
                        </div>
                    @endif
                    @if($booking->whatsapp_number)
                        <div>
                            <span class="font-semibold text-brand-charcoal block">WhatsApp:</span>
                            <span>{{ $booking->whatsapp_number }}</span>
                        </div>
                    @endif
                    <div class="col-span-full">
                        <span class="font-semibold text-brand-charcoal block">Venue Address:</span>
                        <span>{{ $booking->address_line }}, {{ $booking->locality ? $booking->locality . ', ' : '' }}{{ $booking->city }}, {{ $booking->state }} {{ $booking->pincode ? '— ' . $booking->pincode : '' }}</span>
                    </div>
                    @if($booking->special_requirements)
                        <div class="col-span-full p-3 bg-brand-offwhite rounded-lg border border-brand-light-border">
                            <span class="font-semibold text-brand-charcoal block">Special Requirements / Notes:</span>
                            <p class="italic mt-0.5 text-brand-charcoal">{{ $booking->special_requirements }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Integration & Guest Notice -->
            @auth
                @if($booking->user_id == auth()->id())
                    <div class="p-5 rounded-2xl bg-brand-burgundy/5 border border-brand-gold/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3 text-left">
                            <div class="w-10 h-10 rounded-full bg-brand-burgundy text-brand-gold flex items-center justify-center shrink-0 text-base shadow">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-brand-charcoal">Track this Booking in Your Account</h4>
                                <p class="text-xs text-brand-muted-brown">You can view status timeline updates, download details, or request changes anytime.</p>
                            </div>
                        </div>
                        <a href="{{ route('account.bookings.show', $booking->booking_reference) }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all whitespace-nowrap">
                            <i class="fas fa-eye mr-1 text-brand-gold"></i> View in My Bookings
                        </a>
                    </div>
                @endif
            @else
                <div class="p-5 rounded-2xl bg-amber-50/80 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-left">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 text-base">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-amber-950">Create an Account to Track Your Bookings</h4>
                            <p class="text-xs text-amber-800">Register with your email to view order status, request rescheduling, and manage your decorations in one place.</p>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold text-brand-burgundy bg-white border border-brand-gold/60 rounded-xl hover:bg-brand-offwhite shadow-sm transition-all whitespace-nowrap">
                        Create Account
                    </a>
                </div>
            @endauth

            <!-- Actions Bar: WhatsApp & Return Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                <a 
                    href="https://wa.me/919931200000?text={{ urlencode('Namaste Aditya Utsav! I have submitted booking request reference #' . $booking->booking_reference . ' for "' . $booking->decoration->name . '" on ' . $booking->formatted_event_date . '. Please confirm receipt.') }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="w-full sm:w-auto px-6 py-3 text-xs font-bold uppercase tracking-wider text-green-900 bg-green-100 hover:bg-green-200 rounded-xl border border-green-300 transition-all flex items-center justify-center gap-2 shadow-sm"
                >
                    <i class="fab fa-whatsapp text-green-600 text-base"></i>
                    <span>Connect on WhatsApp with Reference ID</span>
                </a>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a 
                        href="{{ route('decorations.show', $booking->decoration->slug) }}" 
                        class="w-1/2 sm:w-auto px-4 py-2.5 text-xs font-semibold text-brand-charcoal bg-white hover:bg-brand-offwhite rounded-xl border border-brand-light-border transition-colors text-center"
                    >
                        View Decoration
                    </a>
                    <a 
                        href="{{ route('decorations.index') }}" 
                        class="w-1/2 sm:w-auto px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-colors text-center"
                    >
                        Browse Catalog
                    </a>
                </div>
            </div>

        </div>
    </section>

@endsection
