@extends('layouts.app')

@section('title', 'Terms & Conditions | Aditya Utsav Bihar')
@section('meta_description', 'Terms and conditions for wedding decoration services, booking requests, date availability, and on-site setup with Aditya Utsav.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Terms & Conditions', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-12 sm:py-16 text-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold tracking-wide">
                Terms &amp; Conditions
            </h1>
            <p class="text-xs sm:text-sm text-brand-cream/90 max-w-xl mx-auto font-light leading-relaxed">
                General business terms governing decoration service bookings with Aditya Utsav.
            </p>
        </div>
    </section>

    <!-- Terms Content Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury space-y-8 text-xs sm:text-sm text-brand-charcoal leading-relaxed">
                
                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        1. Service Booking &amp; Date Confirmation
                    </h2>
                    <p>
                        Submitting an online booking request or quote enquiry on this website does not automatically constitute a finalized contract or date lock. A booking is considered confirmed only upon verification of date and crew availability by Aditya Utsav management and subsequent payment of the mutually agreed token advance.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        2. Venue Access &amp; Power Requirements
                    </h2>
                    <p>
                        The client or venue host must ensure that Aditya Utsav fabrication crew has unrestricted access to the venue premises at least 4 to 6 hours prior to the scheduled ceremony start time. The client is responsible for ensuring basic electrical power access for stage focus lighting, chandeliers, and sound-reactive systems.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        3. Floral Selection &amp; Seasonal Availability
                    </h2>
                    <p>
                        While we strive to match all catalogue and reference photos precisely, certain fresh flower varieties (such as imported orchids, Dutch roses, or specific color shades) are subject to seasonal agricultural availability in local Bihar wholesale markets. In case of non-availability, suitable premium alternatives of equal or higher value will be used with prior client notification.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        4. Custom Add-ons &amp; On-Site Additions
                    </h2>
                    <p>
                        Any extra decoration elements, additional floral latkans, or extended lighting requested on event day outside the verified quote will be subject to material availability and additional invoicing at standard company rates.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        5. Teardown &amp; Material Custody
                    </h2>
                    <p>
                        All non-consumable structural elements (including iron trussing, wooden stages, artificial floral panels, decorative brass urlis, sofa thrones, chandeliers, and focus par lights) remain the exclusive property of Aditya Utsav. Dismantling commences after the completion of the ceremony.
                    </p>
                </div>

                <div class="pt-4 border-t border-brand-light-border text-xs text-brand-muted-brown">
                    <p>For any queries regarding our terms, please contact our Siwan management desk at <a href="mailto:info@adityautsav.in" class="text-brand-burgundy font-bold underline">info@adityautsav.in</a>.</p>
                </div>

            </div>
        </div>
    </section>

@endsection
