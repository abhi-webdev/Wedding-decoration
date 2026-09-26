@extends('layouts.app')

@section('title', 'Get a Custom Wedding Decoration Quote | Aditya Utsav Bihar')
@section('meta_description', 'Request a personalized wedding decoration quote in Siwan, Gopalganj, Patna, or Gorakhpur. Upload reference photos and select budget ranges.')

@section('content')

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Get a Custom Quote', 'url' => '']
    ]" />

    <!-- Hero Banner Section -->
    <section class="relative bg-brand-burgundy py-12 sm:py-16 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 text-brand-gold border border-brand-gold/30">
                <i class="fas fa-file-invoice text-[10px]"></i> Tailored Estimation
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-bold tracking-wide">
                Get a Custom Quote
            </h1>
            <p class="text-xs sm:text-sm text-brand-cream/90 max-w-xl mx-auto font-light leading-relaxed">
                Tell us about your event and our team will help create the right decoration plan for you.
            </p>
        </div>
    </section>

    <!-- Quote Form Section (Two Column Layout) -->
    <section class="py-10 sm:py-16 bg-brand-cream relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Quote Request Form (8 cols) -->
                <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-10 border border-brand-light-border shadow-soft-luxury space-y-6">
                    
                    @if($errors->any())
                        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-xs text-red-800 space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-red-900">
                                <i class="fas fa-exclamation-circle"></i> Please resolve the following errors:
                            </div>
                            <ul class="list-disc list-inside pl-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('quote.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Section 1: Contact Information -->
                        <div class="space-y-4">
                            <h3 class="font-serif text-base font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-xs">1</span>
                                Contact Details
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="space-y-1.5 sm:col-span-2">
                                    <label for="customer_name" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="customer_name" 
                                        name="customer_name" 
                                        value="{{ old('customer_name', $authUser->name ?? '') }}" 
                                        required 
                                        placeholder="e.g. Ramesh Kumar Singh"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="customer_phone" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Mobile Phone <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="customer_phone" 
                                        name="customer_phone" 
                                        value="{{ old('customer_phone', $authUser->phone ?? '') }}" 
                                        required 
                                        placeholder="e.g. 9876543210"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="customer_email" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Email Address (Optional)
                                    </label>
                                    <input 
                                        type="email" 
                                        id="customer_email" 
                                        name="customer_email" 
                                        value="{{ old('customer_email', $authUser->email ?? '') }}" 
                                        placeholder="e.g. name@example.com"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Ceremony & Event Details -->
                        <div class="space-y-4 pt-2">
                            <h3 class="font-serif text-base font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-xs">2</span>
                                Event Schedule &amp; Scope
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div class="space-y-1.5">
                                    <label for="event_type" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Event Type <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        id="event_type" 
                                        name="event_type" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium cursor-pointer"
                                    >
                                        <option value="">-- Select Event --</option>
                                        @php
                                            $evOptions = [
                                                'Complete Wedding (Vivah)',
                                                'Jaimala / Varmala Stage',
                                                'Vedic Vivah Mandap',
                                                'Haldi Ceremony',
                                                'Mehendi Celebration',
                                                'Grand Sangeet Night',
                                                'Wedding Reception',
                                                'Tilak & Sagai (Engagement)',
                                                'Baraat & Entrance Gateway',
                                                'Other Cultural Event'
                                            ];
                                        @endphp
                                        @foreach($evOptions as $opt)
                                            <option value="{{ $opt }}" {{ old('event_type') === $opt ? 'selected' : '' }}>
                                                {{ $opt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="event_date" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Event Date <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="event_date" 
                                        name="event_date" 
                                        min="{{ date('Y-m-d') }}" 
                                        value="{{ old('event_date') }}" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="guest_count" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Expected Guests
                                    </label>
                                    <input 
                                        type="number" 
                                        id="guest_count" 
                                        name="guest_count" 
                                        min="1" 
                                        max="10000" 
                                        value="{{ old('guest_count', 300) }}" 
                                        placeholder="e.g. 400"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Location Details -->
                        <div class="space-y-4 pt-2">
                            <h3 class="font-serif text-base font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-xs">3</span>
                                Venue &amp; Location
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div class="space-y-1.5">
                                    <label for="state" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        State <span class="text-red-500">*</span>
                                    </label>
                                    <select 
                                        id="state" 
                                        name="state" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium cursor-pointer"
                                    >
                                        <option value="Bihar" {{ old('state', request('state')) === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                                        <option value="Uttar Pradesh" {{ old('state', request('state')) === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                                    </select>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="city" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        City / District <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="city" 
                                        name="city" 
                                        value="{{ old('city', request('city', 'Siwan')) }}" 
                                        required 
                                        placeholder="e.g. Siwan, Gopalganj, Patna"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="locality" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Area / Locality
                                    </label>
                                    <input 
                                        type="text" 
                                        id="locality" 
                                        name="locality" 
                                        value="{{ old('locality') }}" 
                                        placeholder="e.g. Gandhi Maidan, Bypass"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5 sm:col-span-3">
                                    <label for="venue_name" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                        Marriage Hall / Lawn Name (Optional)
                                    </label>
                                    <input 
                                        type="text" 
                                        id="venue_name" 
                                        name="venue_name" 
                                        value="{{ old('venue_name') }}" 
                                        placeholder="e.g. Hotel Grand / Royal Marriage Lawn / Home Lawn"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Decoration Preferences & Budget -->
                        <div class="space-y-4 pt-2">
                            <h3 class="font-serif text-base font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-xs">4</span>
                                Decoration Preference &amp; Budget
                            </h3>

                            <!-- Checkboxes for Setup Preferences -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Decoration Setups Needed (Select all that apply):
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                                    @php
                                        $decPrefs = [
                                            'Jaimala Stage',
                                            'Vedic Mandap',
                                            'Haldi Canopy',
                                            'Mehendi Setup',
                                            'Sangeet Stage',
                                            'Reception Backdrop',
                                            'Entrance Toran & Gate',
                                            'Venue Fairy Lighting',
                                            'VIP Dining Decor'
                                        ];
                                    @endphp
                                    @foreach($decPrefs as $pref)
                                        <label class="flex items-center gap-2 p-2.5 bg-brand-offwhite rounded-xl border border-brand-light-border hover:border-brand-gold cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="decoration_preference[]" 
                                                value="{{ $pref }}"
                                                class="w-4 h-4 text-brand-burgundy rounded border-brand-light-border focus:ring-brand-burgundy"
                                                {{ (is_array(old('decoration_preference')) && in_array($pref, old('decoration_preference'))) ? 'checked' : '' }}
                                            />
                                            <span class="text-brand-charcoal font-medium text-[11px]">{{ $pref }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Budget Range Selector -->
                            <div class="space-y-1.5 text-xs">
                                <label for="budget_range" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                    Estimated Budget Range:
                                </label>
                                <select 
                                    id="budget_range" 
                                    name="budget_range" 
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium cursor-pointer"
                                >
                                    <option value="Flexible / Need Guidance" {{ old('budget_range') === 'Flexible / Need Guidance' ? 'selected' : '' }}>Flexible / Need Guidance</option>
                                    <option value="Under ₹25,000" {{ old('budget_range') === 'Under ₹25,000' ? 'selected' : '' }}>Under ₹25,000</option>
                                    <option value="₹25,000 – ₹50,000" {{ old('budget_range') === '₹25,000 – ₹50,000' ? 'selected' : '' }}>₹25,000 – ₹50,000</option>
                                    <option value="₹50,000 – ₹1,00,000" {{ old('budget_range') === '₹50,000 – ₹1,00,000' ? 'selected' : '' }}>₹50,000 – ₹1,00,000</option>
                                    <option value="₹1,00,000 – ₹2,00,000" {{ old('budget_range') === '₹1,00,000 – ₹2,00,000' ? 'selected' : '' }}>₹1,00,000 – ₹2,00,000</option>
                                    <option value="₹2,00,000+" {{ old('budget_range') === '₹2,00,000+' ? 'selected' : '' }}>₹2,00,000+ (Grand / Multi-Day)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Section 5: Reference Image & Special Notes -->
                        <div class="space-y-4 pt-2">
                            <h3 class="font-serif text-base font-bold text-brand-charcoal uppercase tracking-wider pb-2 border-b border-brand-light-border flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand-burgundy text-white flex items-center justify-center text-xs">5</span>
                                Reference Photo &amp; Requirements
                            </h3>

                            <!-- Optional Image Upload -->
                            <div class="space-y-1.5 text-xs">
                                <label for="reference_image" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                    Upload Reference / Inspiration Image (Optional)
                                </label>
                                <div class="p-4 rounded-xl bg-brand-offwhite border-2 border-dashed border-brand-light-border hover:border-brand-gold text-center space-y-2">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-brand-gold"></i>
                                    <p class="text-brand-muted-brown text-xs">
                                        Upload photo from your phone or computer (JPG, PNG, WebP up to 5 MB).
                                    </p>
                                    <input 
                                        type="file" 
                                        id="reference_image" 
                                        name="reference_image" 
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="text-xs text-brand-charcoal file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-burgundy file:text-white hover:file:bg-brand-deep-burgundy cursor-pointer"
                                    />
                                </div>
                            </div>

                            <!-- Special Notes -->
                            <div class="space-y-1.5 text-xs">
                                <label for="special_requirements" class="block font-bold uppercase tracking-wider text-brand-charcoal">
                                    Special Requirements / Custom Color Palette Notes (Optional)
                                </label>
                                <textarea 
                                    id="special_requirements" 
                                    name="special_requirements" 
                                    rows="3" 
                                    placeholder="Tell us about your theme colors, preferred flowers, or timing details..."
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                                >{{ old('special_requirements', request('package') ? 'Interested in package: ' . request('package') : (request('offer') ? 'Applying offer: ' . request('offer') : '')) }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button 
                                type="submit" 
                                class="w-full py-4 px-6 text-sm font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-2xl border border-brand-gold shadow-lg hover:shadow-gold-glow transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i class="fas fa-paper-plane text-brand-gold"></i>
                                <span>Submit Custom Quote Request</span>
                            </button>
                            <p class="text-[11px] text-brand-muted-brown text-center mt-2">
                                Submitting a quote request is 100% free and does not require immediate advance payment.
                            </p>
                        </div>

                    </form>
                </div>

                <!-- Right: Why Choose Aditya Utsav & Guidance (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Guidance Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xl border border-brand-gold/40">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>

                        <h3 class="font-serif text-lg font-bold text-brand-charcoal">
                            Why Get a Custom Quote?
                        </h3>

                        <ul class="space-y-3 text-xs text-brand-muted-brown">
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-check-circle text-brand-gold text-sm mt-0.5 shrink-0"></i>
                                <span><strong>Personalized Design:</strong> We adapt stage dimensions and flower density specifically for your venue.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-check-circle text-brand-gold text-sm mt-0.5 shrink-0"></i>
                                <span><strong>Itemized Transparency:</strong> Detailed breakdown of flowers, fabrication, lighting, and labor.</span>
                            </li>
                            <li class="flex items-start gap-2.5">
                                <i class="fas fa-check-circle text-brand-gold text-sm mt-0.5 shrink-0"></i>
                                <span><strong>Local Operations:</strong> Dedicated supervisor on-site in Siwan, Gopalganj, Patna, or Gorakhpur.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Direct Contact Box -->
                    <div class="bg-brand-offwhite rounded-3xl p-6 border border-brand-gold/40 space-y-3 text-xs">
                        <h4 class="font-serif text-sm font-bold text-brand-burgundy">
                            Prefer Talking Directly?
                        </h4>
                        <p class="text-brand-muted-brown">
                            Call our Siwan office directly or connect with an event coordinator on WhatsApp.
                        </p>
                        <div class="pt-1 space-y-2">
                            <a href="tel:+919931200000" class="block font-bold text-brand-charcoal hover:text-brand-burgundy">
                                <i class="fas fa-phone-alt text-brand-gold mr-1.5"></i> +91 99312 00000
                            </a>
                            <a href="https://wa.me/919931200000" target="_blank" class="block font-bold text-green-700 hover:text-green-800">
                                <i class="fab fa-whatsapp text-green-600 mr-1.5"></i> WhatsApp Chat
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

@endsection
