@extends('layouts.app')

@section('title', 'Contact Aditya Utsav | Wedding Decoration Services in Siwan & Bihar')
@section('meta_description', 'Get in touch with Aditya Utsav wedding decorators in Siwan, Bihar. Inquire via phone, WhatsApp, or contact form for date availability and pricing.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Contact Us', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-14 sm:py-20 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-headset text-[10px]"></i> Client Support &amp; Enquiries
            </span>
            <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-wide">
                Contact Aditya Utsav
            </h1>
            <p class="text-sm sm:text-base text-brand-cream/90 max-w-2xl mx-auto font-light leading-relaxed">
                Connect with our wedding decoration managers in Siwan for custom event arrangements, quotations, and scheduling.
            </p>
        </div>
    </section>

    <!-- Main Contact Section -->
    <section class="py-12 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Contact Form (7 cols) -->
                <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury space-y-6">
                    
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-brand-royal-rose">Send a Direct Message</span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-1">
                            How Can We Help You Celebrate?
                        </h2>
                        <p class="text-xs text-brand-muted-brown mt-1">
                            Fill out the quick contact form below and our operations team will reply within 24 hours.
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-emerald-950">
                                <i class="fas fa-check-circle text-emerald-600"></i> Message Sent Successfully!
                            </div>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-800 space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-red-900">
                                <i class="fas fa-exclamation-circle"></i> Please check your input:
                            </div>
                            <ul class="list-disc list-inside pl-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="space-y-1.5 text-xs">
                            <label for="name" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                Your Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', Auth::user()->name ?? '') }}" 
                                required 
                                placeholder="e.g. Anand Kumar"
                                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="space-y-1.5">
                                <label for="phone" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                    Phone Number
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                    placeholder="e.g. 9876543210"
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                />
                            </div>

                            <div class="space-y-1.5">
                                <label for="email" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', Auth::user()->email ?? '') }}" 
                                    placeholder="e.g. name@example.com"
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                />
                            </div>
                        </div>

                        <div class="space-y-1.5 text-xs">
                            <label for="subject" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                Subject / Purpose of Enquiry
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                value="{{ old('subject') }}" 
                                placeholder="e.g. Wedding Mandap Availability in Gopalganj"
                                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                            />
                        </div>

                        <div class="space-y-1.5 text-xs">
                            <label for="message" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                Message Details <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="4" 
                                required 
                                placeholder="Tell us about your event date, venue location, or specific questions..."
                                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                            >{{ old('message') }}</textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all cursor-pointer"
                        >
                            <i class="fas fa-paper-plane mr-1.5 text-brand-gold"></i> Send Message
                        </button>
                    </form>

                </div>

                <!-- Right: Office Information & Coverage (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Official Address & Phone Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-5 text-xs">
                        <h3 class="font-serif text-lg font-bold text-brand-charcoal pb-2 border-b border-brand-light-border">
                            Head Office &amp; Operations Center
                        </h3>

                        <div class="space-y-4 text-brand-charcoal">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center shrink-0 text-sm border border-brand-gold/40">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <span class="font-bold block text-sm">Siwan Main Hub</span>
                                    <p class="text-brand-muted-brown mt-0.5">Station Road, Near Rajendra Park, Siwan, Bihar 841226</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center shrink-0 text-sm border border-brand-gold/40">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <span class="font-bold block text-sm">Telephone / Booking Inquiries</span>
                                    <a href="tel:+919931200000" class="text-brand-burgundy font-semibold hover:underline block mt-0.5">+91 99312 00000</a>
                                    <span class="text-[11px] text-brand-muted-brown">Mon–Sun: 9:00 AM – 9:00 PM</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-green-100 text-green-700 flex items-center justify-center shrink-0 text-sm border border-green-300">
                                    <i class="fab fa-whatsapp text-base"></i>
                                </div>
                                <div>
                                    <span class="font-bold block text-sm">WhatsApp Concierge</span>
                                    <a href="https://wa.me/919931200000" target="_blank" class="text-green-700 font-semibold hover:underline block mt-0.5">+91 99312 00000 (Instant Chat)</a>
                                    <span class="text-[11px] text-brand-muted-brown">Share photos &amp; venue locations directly</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center shrink-0 text-sm border border-brand-gold/40">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <span class="font-bold block text-sm">Email Support</span>
                                    <a href="mailto:info@adityautsav.in" class="text-brand-charcoal hover:text-brand-burgundy block mt-0.5 font-medium">info@adityautsav.in</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Regional Coverage Summary -->
                    <div class="bg-brand-offwhite rounded-3xl p-6 border border-brand-gold/40 space-y-3 text-xs">
                        <h4 class="font-serif text-sm font-bold text-brand-burgundy">
                            Active Service Districts
                        </h4>
                        <p class="text-brand-muted-brown leading-relaxed">
                            We deploy fabrication teams across <strong>Siwan, Mairwa, Gopalganj, Chapra (Saran), Patna, Muzaffarpur, Darbhanga</strong> in Bihar, and <strong>Gorakhpur, Deoria, Bhatpar Rani, Salempur</strong> in Eastern Uttar Pradesh.
                        </p>
                        <a href="{{ route('service-areas.index') }}" class="font-bold text-brand-burgundy hover:underline inline-block pt-1">
                            Explore Detailed Service Territories &rarr;
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
