@extends('layouts.app')

@section('title', 'Cancellation & Rescheduling Policy | Aditya Utsav Bihar')
@section('meta_description', 'Understand Aditya Utsav cancellation and date rescheduling guidelines for wedding and event decoration bookings in Bihar.')

@section('content')

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-12 sm:py-16 text-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold tracking-wide">
                Cancellation &amp; Rescheduling Policy
            </h1>
            <p class="text-xs sm:text-sm text-brand-cream/90 max-w-xl mx-auto font-light leading-relaxed">
                Clear and empathetic guidelines for booking changes, date modifications, and cancellations.
            </p>
        </div>
    </section>

    <!-- Policy Content Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury space-y-8 text-xs sm:text-sm text-brand-charcoal leading-relaxed">
                
                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        1. Digital Cancellation Requests
                    </h2>
                    <p>
                        Clients with confirmed or pending bookings can initiate a cancellation request directly through their online <strong>Customer Account &rarr; My Bookings</strong> page by clicking "Request Cancellation". Submitting a request does not immediately cancel the reservation; our Siwan management desk reviews the request, verifies logistics commitments, and contacts you within 24 hours.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        2. Date Rescheduling (Muhurat &amp; Schedule Adjustments)
                    </h2>
                    <p>
                        We understand that wedding dates in North India and Bihar may change due to family circumstances or astrological muhurat revisions. Clients can request a new date via the "Request Reschedule" button in their booking details. Reschedule requests submitted at least <strong>14 days prior to the original date</strong> will be adjusted to the new date without penalty, subject to slot and crew availability on the newly requested date.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        3. Token Advances &amp; Material Procurement
                    </h2>
                    <p>
                        Because fresh seasonal flowers and customized structural elements are procured 24 to 48 hours prior to the ceremony date, cancellation terms depend on the proximity to the event date. Token advances may be adjusted toward future family event bookings within the same calendar year upon managerial review.
                    </p>
                </div>

                <div class="space-y-2">
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy">
                        4. Force Majeure &amp; Weather Disruptions
                    </h2>
                    <p>
                        In events of unforeseen heavy rainfall, natural disruptions, or administrative restrictions, Aditya Utsav works closely with the client to provide suitable weather-protective setups (such as waterproof shamiana pandal covers) or reschedule the service to an alternative timing.
                    </p>
                </div>

                <div class="pt-4 border-t border-brand-light-border flex items-center justify-between">
                    <a href="{{ route('account.bookings') }}" class="text-xs font-bold text-brand-burgundy hover:underline">
                        &larr; Manage Your Bookings in Account
                    </a>
                    <a href="{{ route('contact') }}" class="text-xs font-bold text-brand-charcoal hover:text-brand-burgundy">
                        Contact Support &rarr;
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
