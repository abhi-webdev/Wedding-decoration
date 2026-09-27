@extends('layouts.app')

@php
    $isPkg = ($bookingType ?? 'decoration') === 'package';
    $itemName = $isPkg ? $packageModel->name : $decorationModel->name;
    $itemTypeLabel = $isPkg ? 'Wedding Package' : 'Wedding Decoration';
    $basePriceVal = $isPkg ? (float)$packageModel->price : (float)$decorationModel->actual_booking_price;
    $defaultLocation = $isPkg ? 'Siwan' : ($decorationModel->location ?? 'Siwan');
@endphp

@section('title', 'Book ' . $itemName . ' | Aditya Utsav Bihar')
@section('meta_description', 'Submit a booking request for ' . $itemName . '. Choose event date, location, custom add-ons, and get confirmed reservation.')

@section('content')

    <!-- 2. Header Banner -->
    <section class="bg-gradient-to-r from-brand-deep-burgundy via-brand-burgundy to-brand-royal-rose text-white py-8 sm:py-12 border-b border-brand-gold relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:20px_20px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">
                        <i class="fas fa-calendar-alt text-brand-gold"></i>
                        STEP-BY-STEP BOOKING REQUEST
                    </span>
                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-white mt-1">
                        Book Your {{ $itemTypeLabel }}
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-cream/80 mt-1 max-w-xl">
                        Request reservation for <strong class="text-brand-gold-light">{{ $itemName }}</strong>. Our event managers in Siwan will verify slot availability and confirm your booking.
                    </p>
                </div>

                <!-- Step Counter Pill -->
                <div class="bg-black/30 backdrop-blur-sm border border-brand-gold/40 rounded-xl px-4 py-2.5 self-start md:self-auto flex items-center gap-3 text-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-gold animate-pulse"></span>
                    <span class="text-brand-cream/90 font-medium">Status: <strong class="text-brand-gold-light">Request / Direct Verification</strong></span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Main Multi-Step Form Section -->
    <section class="py-8 sm:py-12 bg-brand-offwhite min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Global Validation Alerts -->
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm space-y-1 shadow-sm">
                    <div class="font-bold flex items-center gap-2 text-red-900">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                        Please correct the following errors before submitting:
                    </div>
                    <ul class="list-disc list-inside pl-2 space-y-0.5 text-xs text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Multi-step Stepper Header -->
            <div class="mb-8 bg-white rounded-2xl p-4 sm:p-6 border border-brand-light-border shadow-soft-luxury">
                <nav aria-label="Progress">
                    <ol class="grid grid-cols-5 gap-2 text-center text-xs font-semibold">
                        <!-- Step 1 -->
                        <li id="stepper-tab-1" class="flex flex-col items-center gap-1.5 cursor-pointer" onclick="goToStep(1)">
                            <div class="stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-burgundy text-brand-cream border-2 border-brand-gold shadow-sm transition-all">
                                1
                            </div>
                            <span class="stepper-label text-[11px] sm:text-xs text-brand-burgundy font-bold">Event</span>
                        </li>

                        <!-- Step 2 -->
                        <li id="stepper-tab-2" class="flex flex-col items-center gap-1.5 cursor-pointer" onclick="goToStep(2)">
                            <div class="stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-offwhite text-brand-muted-brown border border-brand-light-border transition-all">
                                2
                            </div>
                            <span class="stepper-label text-[11px] sm:text-xs text-brand-muted-brown">Location</span>
                        </li>

                        <!-- Step 3 -->
                        <li id="stepper-tab-3" class="flex flex-col items-center gap-1.5 cursor-pointer" onclick="goToStep(3)">
                            <div class="stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-offwhite text-brand-muted-brown border border-brand-light-border transition-all">
                                3
                            </div>
                            <span class="stepper-label text-[11px] sm:text-xs text-brand-muted-brown">Add-ons</span>
                        </li>

                        <!-- Step 4 -->
                        <li id="stepper-tab-4" class="flex flex-col items-center gap-1.5 cursor-pointer" onclick="goToStep(4)">
                            <div class="stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-offwhite text-brand-muted-brown border border-brand-light-border transition-all">
                                4
                            </div>
                            <span class="stepper-label text-[11px] sm:text-xs text-brand-muted-brown">Account</span>
                        </li>

                        <!-- Step 5 -->
                        <li id="stepper-tab-5" class="flex flex-col items-center gap-1.5 cursor-pointer" onclick="goToStep(5)">
                            <div class="stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-offwhite text-brand-muted-brown border border-brand-light-border transition-all">
                                5
                            </div>
                            <span class="stepper-label text-[11px] sm:text-xs text-brand-muted-brown">Review</span>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Two-Column Layout: Form on Left (7 cols), Sticky Summary on Right (5 cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Multi-Step Form Container -->
                <div class="lg:col-span-7">
                    <form id="booking-request-form" action="{{ route('booking.store') }}" method="POST" class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
                        @csrf
                        <input type="hidden" name="booking_type" value="{{ $bookingType ?? 'decoration' }}">
                        @if($decorationModel)
                            <input type="hidden" name="decoration_id" id="form-decoration-id" value="{{ $decorationModel->id }}">
                        @endif
                        @if($packageModel)
                            <input type="hidden" name="package_id" id="form-package-id" value="{{ $packageModel->id }}">
                        @endif
                        
                        <!-- ================= STEP 1: EVENT DETAILS ================= -->
                        <div id="step-panel-1" class="step-panel space-y-6">
                            <div class="border-b border-brand-light-border pb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-royal-rose">Step 1 of 5</span>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-0.5">
                                    Ceremony &amp; Event Schedule
                                </h2>
                                <p class="text-xs text-brand-muted-brown mt-0.5">
                                    Tell us when and what celebration you are hosting.
                                </p>
                            </div>

                            <!-- Event Type -->
                            <div class="space-y-1.5">
                                <label for="event_type" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Ceremony / Event Type <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="event_type" 
                                    name="event_type" 
                                    required 
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                                    onchange="updateLiveReviewSummary()"
                                >
                                    @php
                                        $eventOptions = [
                                            'Wedding (Vivah)',
                                            'Wedding & Vivah',
                                            'Jaimala (Varmala)',
                                            'Jaimala / Varmala',
                                            'Wedding Mandap (Pheras)',
                                            'Haldi Ceremony',
                                            'Mehendi Ceremony',
                                            'Sangeet & Musical Night',
                                            'Sangeet Night',
                                            'Grand Wedding Reception',
                                            'Reception',
                                            'Tilak & Sagai (Engagement)',
                                            'Tilak / Sagai',
                                            'Baraat Swagat & Entrance',
                                            'Complete Wedding Package',
                                            'Complete Package',
                                            'Anniversary Celebration',
                                            'Other Religious / Cultural Event'
                                        ];
                                        $currentEventType = old('event_type', $prefill['event_type'] ?? ($decorationModel->category->name ?? 'Wedding & Vivah'));
                                    @endphp
                                    @foreach($eventOptions as $opt)
                                        <option value="{{ $opt }}" {{ ($opt === $currentEventType || str_contains($currentEventType, $opt)) ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Event Date & Live Availability Check -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Event Date <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-brand-muted-brown">Minimum date: Today</span>
                                </div>
                                <div class="relative">
                                    <input 
                                        type="date" 
                                        id="event_date" 
                                        name="event_date" 
                                        min="{{ date('Y-m-d') }}" 
                                        value="{{ old('event_date', $prefill['event_date'] ?? date('Y-m-d', strtotime('+14 days'))) }}" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                                        onchange="checkDateAvailability(this.value); updateLiveReviewSummary();"
                                    />
                                </div>

                                <!-- AJAX Availability Feedback Box -->
                                <div id="availability-feedback" class="hidden p-3 rounded-xl text-xs space-y-1 transition-all">
                                    <div class="flex items-center gap-2 font-bold" id="availability-title">
                                        <!-- Dynamic icon & title -->
                                    </div>
                                    <p id="availability-message" class="text-[11px] leading-relaxed"></p>
                                </div>
                            </div>

                            <!-- Time Window -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="start_time" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Event Start Time <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="time" 
                                        id="start_time" 
                                        name="start_time" 
                                        value="{{ old('start_time', '16:00') }}" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                        onchange="updateLiveReviewSummary()"
                                    />
                                    <span class="text-[10px] text-brand-muted-brown">Setup begins 4-6 hours prior</span>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="end_time" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Event End Time <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="time" 
                                        id="end_time" 
                                        name="end_time" 
                                        value="{{ old('end_time', '23:30') }}" 
                                        required 
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                        onchange="updateLiveReviewSummary()"
                                    />
                                    <span class="text-[10px] text-brand-muted-brown">Teardown starts immediately after</span>
                                </div>
                            </div>

                            <!-- Expected Guest Count -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="guest_count" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Expected Guests <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-brand-royal-rose font-medium">
                                        Recommended: {{ $decorationModel->guest_capacity ?? ($packageModel->guest_capacity ?? '200-500 Guests') }}
                                    </span>
                                </div>
                                <input 
                                    type="number" 
                                    id="guest_count" 
                                    name="guest_count" 
                                    min="1" 
                                    max="10000" 
                                    value="{{ old('guest_count', 300) }}" 
                                    required 
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    oninput="checkGuestCapacity(this.value); updateLiveReviewSummary();"
                                />
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="button" onclick="validateAndGoToStep(2)" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md flex items-center gap-2 cursor-pointer">
                                    <span>Proceed to Location</span>
                                    <i class="fas fa-arrow-right text-[11px] text-brand-gold"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ================= STEP 2: LOCATION ================= -->
                        <div id="step-panel-2" class="step-panel space-y-6 hidden">
                            <div class="border-b border-brand-light-border pb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-royal-rose">Step 2 of 5</span>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-0.5">
                                    Event Venue &amp; Location
                                </h2>
                                <p class="text-xs text-brand-muted-brown mt-0.5">
                                    Where will Aditya Utsav set up the decoration?
                                </p>
                            </div>

                            <!-- State Selection -->
                            <div class="space-y-1.5">
                                <label for="state" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="state" 
                                    name="state" 
                                    required 
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    onchange="updateLiveReviewSummary()"
                                >
                                    <option value="Bihar" {{ old('state', 'Bihar') === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                                    <option value="Uttar Pradesh" {{ old('state') === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh (Nearby)</option>
                                    <option value="Other" {{ old('state') === 'Other' ? 'selected' : '' }}>Other State</option>
                                </select>
                            </div>

                            <!-- City with Quick Select Pill Buttons -->
                            <div class="space-y-2">
                                <label for="city" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    City / District <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    value="{{ old('city', $prefill['city'] ?? $defaultLocation) }}" 
                                    required 
                                    placeholder="e.g. Siwan, Mairwa, Gopalganj, Chapra, Gorakhpur..."
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    oninput="updateLiveReviewSummary()"
                                />

                                <!-- Quick Location Buttons -->
                                <div class="space-y-1.5 pt-1">
                                    <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider font-semibold block">Quick Select Primary Service Areas:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="text-[10px] font-bold text-brand-burgundy self-center mr-1">Bihar:</span>
                                        @foreach(['Siwan', 'Mairwa', 'Gopalganj', 'Chapra / Saran', 'Maharajganj'] as $bCity)
                                            <button type="button" onclick="setCityState('{{ $bCity }}', 'Bihar')" class="px-2.5 py-1 text-[11px] bg-brand-offwhite hover:bg-brand-gold/20 border border-brand-light-border rounded-lg text-brand-charcoal transition-colors cursor-pointer">
                                                {{ $bCity }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 pt-1">
                                        <span class="text-[10px] font-bold text-brand-royal-rose self-center mr-1">Nearby UP:</span>
                                        @foreach(['Gorakhpur', 'Deoria', 'Bhatpar Rani', 'Salempur'] as $upCity)
                                            <button type="button" onclick="setCityState('{{ $upCity }}', 'Uttar Pradesh')" class="px-2.5 py-1 text-[11px] bg-brand-offwhite hover:bg-brand-gold/20 border border-brand-light-border rounded-lg text-brand-charcoal transition-colors cursor-pointer">
                                                {{ $upCity }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Address Line -->
                            <div class="space-y-1.5">
                                <label for="address_line" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Venue Name &amp; Address Line <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="address_line" 
                                    name="address_line" 
                                    value="{{ old('address_line') }}" 
                                    required 
                                    placeholder="e.g. Hotel Raj Mahal Lawn, Old Court Road, Siwan"
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    oninput="updateLiveReviewSummary()"
                                />
                            </div>

                            <!-- Locality & Pincode -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="locality" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Locality / Landmark (Optional)
                                    </label>
                                    <input 
                                        type="text" 
                                        id="locality" 
                                        name="locality" 
                                        value="{{ old('locality') }}" 
                                        placeholder="Near Gandhi Maidan / Station Road"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="pincode" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Pincode (Optional)
                                    </label>
                                    <input 
                                        type="text" 
                                        id="pincode" 
                                        name="pincode" 
                                        value="{{ old('pincode') }}" 
                                        placeholder="e.g. 841226"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                </div>
                            </div>

                            <!-- Step 2 Navigation Buttons -->
                            <div class="pt-4 flex items-center justify-between">
                                <button type="button" onclick="goToStep(1)" class="px-5 py-2.5 text-xs font-semibold text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border rounded-xl cursor-pointer">
                                    <i class="fas fa-arrow-left mr-1.5"></i> Back
                                </button>
                                <button type="button" onclick="validateAndGoToStep(3)" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md flex items-center gap-2 cursor-pointer">
                                    <span>Proceed to Add-ons</span>
                                    <i class="fas fa-arrow-right text-[11px] text-brand-gold"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ================= STEP 3: ADD-ONS ================= -->
                        <div id="step-panel-3" class="step-panel space-y-6 hidden">
                            <div class="border-b border-brand-light-border pb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-royal-rose">Step 3 of 5</span>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-0.5">
                                    Custom Add-ons &amp; Upgrades
                                </h2>
                                <p class="text-xs text-brand-muted-brown mt-0.5">
                                    Select optional enhancements to customize your setup.
                                </p>
                            </div>

                            <!-- Add-ons Selection List -->
                            <div class="space-y-3">
                                @forelse($addons as $addon)
                                    <label class="relative flex items-start gap-3.5 p-4 rounded-xl border border-brand-light-border hover:border-brand-gold cursor-pointer bg-brand-offwhite/50 transition-all">
                                        <div class="flex items-center h-5 mt-1">
                                            <input 
                                                type="checkbox" 
                                                name="selected_addons[]" 
                                                value="{{ $addon->id }}" 
                                                data-price="{{ $addon->price }}"
                                                data-name="{{ $addon->name }}"
                                                class="addon-checkbox w-4 h-4 text-brand-burgundy rounded border-brand-light-border focus:ring-brand-burgundy"
                                                onchange="calculateLiveAddonsTotal()"
                                                {{ (is_array(old('selected_addons')) && in_array($addon->id, old('selected_addons'))) ? 'checked' : '' }}
                                            />
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <div class="flex items-baseline justify-between gap-2">
                                                <h4 class="font-serif text-sm font-bold text-brand-charcoal">
                                                    {{ $addon->name }}
                                                </h4>
                                                <span class="font-serif text-sm font-bold text-brand-burgundy whitespace-nowrap">
                                                    +{{ $addon->formatted_price }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-brand-muted-brown mt-0.5 leading-relaxed">
                                                {{ $addon->description }}
                                            </p>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-xs text-brand-muted-brown italic">
                                        No custom add-ons required. You can proceed directly.
                                    </p>
                                @endforelse
                            </div>

                            <p class="text-[11px] text-brand-muted-brown bg-brand-offwhite p-3 rounded-lg border border-brand-light-border flex items-center gap-1.5">
                                <i class="fas fa-shield-alt text-brand-gold"></i>
                                Selected add-on prices are calculated by our server using certified pricing.
                            </p>

                            <!-- Step 3 Navigation Buttons -->
                            <div class="pt-4 flex items-center justify-between">
                                <button type="button" onclick="goToStep(2)" class="px-5 py-2.5 text-xs font-semibold text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border rounded-xl cursor-pointer">
                                    <i class="fas fa-arrow-left mr-1.5"></i> Back
                                </button>
                                <button type="button" onclick="goToStep(4)" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md flex items-center gap-2 cursor-pointer">
                                    <span>Proceed to Contact &amp; Account</span>
                                    <i class="fas fa-arrow-right text-[11px] text-brand-gold"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ================= STEP 4: CUSTOMER DETAILS & AUTO ACCOUNT ================= -->
                        <div id="step-panel-4" class="step-panel space-y-6 hidden">
                            <div class="border-b border-brand-light-border pb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-royal-rose">Step 4 of 5</span>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-0.5">
                                    Contact &amp; Customer Account
                                </h2>
                                <p class="text-xs text-brand-muted-brown mt-0.5">
                                    Enter your details to receive your booking reference, status updates, and quotation.
                                </p>
                            </div>

                            <!-- Full Name -->
                            <div class="space-y-1.5">
                                <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="customer_name" 
                                    name="customer_name" 
                                    value="{{ old('customer_name', $prefill['name'] ?? ($authUser->name ?? '')) }}" 
                                    required 
                                    placeholder="e.g. Rahul Kumar Singh"
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    oninput="updateLiveReviewSummary()"
                                />
                            </div>

                            <!-- Mobile Phone & WhatsApp -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        Mobile Number <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="customer_phone" 
                                        name="customer_phone" 
                                        value="{{ old('customer_phone', $prefill['phone'] ?? ($authUser->phone ?? '')) }}" 
                                        required 
                                        placeholder="e.g. 9876543210"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                        oninput="updateLiveReviewSummary()"
                                    />
                                    <span class="text-[10px] text-brand-muted-brown">For direct manager calls &amp; confirmation</span>
                                </div>

                                <div class="space-y-1.5">
                                    <label for="whatsapp_number" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                        WhatsApp Number (Optional)
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="whatsapp_number" 
                                        name="whatsapp_number" 
                                        value="{{ old('whatsapp_number', $prefill['phone'] ?? ($authUser->whatsapp ?? '')) }}" 
                                        placeholder="e.g. 9876543210"
                                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    />
                                    <span class="text-[10px] text-brand-muted-brown">To receive photos &amp; digital receipts</span>
                                </div>
                            </div>

                            <!-- Email Address (Required for automatic account creation) -->
                            <div class="space-y-1.5">
                                <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="customer_email" 
                                    name="customer_email" 
                                    value="{{ old('customer_email', $authUser->email ?? '') }}" 
                                    required
                                    placeholder="customer@example.com"
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                                    oninput="updateLiveReviewSummary()"
                                />
                            </div>

                            <!-- Automatic Account Notice -->
                            <div class="p-4 rounded-xl bg-brand-cream/80 border border-brand-gold/40 text-xs text-brand-charcoal space-y-1.5">
                                <div class="font-bold flex items-center gap-2 text-brand-burgundy">
                                    <i class="fas fa-user-shield text-brand-gold"></i>
                                    <span>Automatic Customer Account &amp; Portal Access</span>
                                </div>
                                <p class="text-[11px] leading-relaxed text-brand-muted-brown">
                                    If you do not already have an account, a customer account will be automatically created. A secure temporary login password and booking reference will be emailed to your address. You can log in anytime to track booking status, view receipts, and make payments.
                                </p>
                            </div>

                            <!-- Special Requirements -->
                            <div class="space-y-1.5">
                                <label for="special_requirements" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                                    Special Requirements / Decoration Preferences (Optional)
                                </label>
                                <textarea 
                                    id="special_requirements" 
                                    name="special_requirements" 
                                    rows="3" 
                                    placeholder="Tell us about custom flower color choices, stage height, lighting preferences, or specific rituals..."
                                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-normal leading-relaxed"
                                    oninput="updateLiveReviewSummary()"
                                >{{ old('special_requirements') }}</textarea>
                            </div>

                            <!-- Step 4 Navigation Buttons -->
                            <div class="pt-4 flex items-center justify-between">
                                <button type="button" onclick="goToStep(3)" class="px-5 py-2.5 text-xs font-semibold text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border rounded-xl cursor-pointer">
                                    <i class="fas fa-arrow-left mr-1.5"></i> Back
                                </button>
                                <button type="button" onclick="validateAndGoToStep(5)" class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md flex items-center gap-2 cursor-pointer">
                                    <span>Review Booking Request</span>
                                    <i class="fas fa-arrow-right text-[11px] text-brand-gold"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ================= STEP 5: REVIEW & SUBMIT ================= -->
                        <div id="step-panel-5" class="step-panel space-y-6 hidden">
                            <div class="border-b border-brand-light-border pb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-brand-royal-rose">Step 5 of 5</span>
                                <h2 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal mt-0.5">
                                    Review &amp; Submit Request
                                </h2>
                                <p class="text-xs text-brand-muted-brown mt-0.5">
                                    Please verify your ceremony details before final submission.
                                </p>
                            </div>

                            <!-- Review Sections Grid -->
                            <div class="space-y-4 text-xs">
                                
                                <!-- 1. Event Schedule Review -->
                                <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-2">
                                    <div class="flex items-center justify-between pb-1 border-b border-brand-light-border/60">
                                        <strong class="font-bold text-brand-charcoal uppercase tracking-wider">1. Event Schedule</strong>
                                        <button type="button" onclick="goToStep(1)" class="text-xs font-bold text-brand-burgundy hover:underline cursor-pointer">
                                            <i class="fas fa-edit mr-0.5"></i> Edit
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-brand-muted-brown">
                                        <div>Ceremony: <strong class="text-brand-charcoal block" id="rev-event-type">-</strong></div>
                                        <div>Date: <strong class="text-brand-burgundy block" id="rev-event-date">-</strong></div>
                                        <div>Time: <strong class="text-brand-charcoal block" id="rev-event-time">-</strong></div>
                                        <div>Guests: <strong class="text-brand-charcoal block" id="rev-guest-count">-</strong></div>
                                    </div>
                                </div>

                                <!-- 2. Venue Location Review -->
                                <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-2">
                                    <div class="flex items-center justify-between pb-1 border-b border-brand-light-border/60">
                                        <strong class="font-bold text-brand-charcoal uppercase tracking-wider">2. Venue Location</strong>
                                        <button type="button" onclick="goToStep(2)" class="text-xs font-bold text-brand-burgundy hover:underline cursor-pointer">
                                            <i class="fas fa-edit mr-0.5"></i> Edit
                                        </button>
                                    </div>
                                    <div class="text-brand-muted-brown space-y-0.5">
                                        <div>Address: <strong class="text-brand-charcoal block" id="rev-address">-</strong></div>
                                        <div>City &amp; State: <strong class="text-brand-charcoal block" id="rev-city-state">-</strong></div>
                                    </div>
                                </div>

                                <!-- 3. Add-ons Review -->
                                <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-2">
                                    <div class="flex items-center justify-between pb-1 border-b border-brand-light-border/60">
                                        <strong class="font-bold text-brand-charcoal uppercase tracking-wider">3. Selected Add-ons</strong>
                                        <button type="button" onclick="goToStep(3)" class="text-xs font-bold text-brand-burgundy hover:underline cursor-pointer">
                                            <i class="fas fa-edit mr-0.5"></i> Edit
                                        </button>
                                    </div>
                                    <div id="rev-addons-list" class="text-brand-muted-brown space-y-1">
                                        <span class="italic text-gray-500">No add-ons selected.</span>
                                    </div>
                                </div>

                                <!-- 4. Customer Info Review -->
                                <div class="p-4 rounded-xl bg-brand-offwhite border border-brand-light-border space-y-2">
                                    <div class="flex items-center justify-between pb-1 border-b border-brand-light-border/60">
                                        <strong class="font-bold text-brand-charcoal uppercase tracking-wider">4. Contact &amp; Account</strong>
                                        <button type="button" onclick="goToStep(4)" class="text-xs font-bold text-brand-burgundy hover:underline cursor-pointer">
                                            <i class="fas fa-edit mr-0.5"></i> Edit
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-brand-muted-brown">
                                        <div>Name: <strong class="text-brand-charcoal block" id="rev-customer-name">-</strong></div>
                                        <div>Phone: <strong class="text-brand-charcoal block" id="rev-customer-phone">-</strong></div>
                                        <div class="col-span-2">Login Email: <strong class="text-brand-burgundy block" id="rev-customer-email">-</strong></div>
                                    </div>
                                </div>

                            </div>

                            <!-- Important Advisory Notice -->
                            <div class="p-4 rounded-xl bg-amber-50/80 border border-amber-200 text-xs text-amber-900 space-y-1">
                                <div class="font-bold flex items-center gap-1.5 text-amber-950">
                                    <i class="fas fa-info-circle text-amber-600"></i>
                                    Important: Booking Request Lifecycle
                                </div>
                                <p class="leading-relaxed text-[11px] text-amber-800">
                                    Submitting this form initiates your booking request (Status: <strong>Pending</strong>). Our team in Siwan will review the request and accept it. Once accepted, you will receive a notification and can submit your token advance to formalize the reservation.
                                </p>
                            </div>

                            <!-- Step 5 Submit & Navigation Buttons -->
                            <div class="pt-4 flex items-center justify-between gap-4">
                                <button type="button" onclick="goToStep(4)" class="px-5 py-2.5 text-xs font-semibold text-brand-charcoal hover:bg-brand-offwhite border border-brand-light-border rounded-xl cursor-pointer">
                                    <i class="fas fa-arrow-left mr-1.5"></i> Back
                                </button>
                                
                                <button 
                                    type="submit" 
                                    id="submit-booking-btn" 
                                    class="w-full sm:w-auto px-8 py-3.5 text-xs sm:text-sm font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all flex items-center justify-center gap-2 cursor-pointer"
                                >
                                    <i class="fas fa-check-circle text-brand-gold text-base"></i>
                                    <span id="submit-btn-label">Submit Booking Request</span>
                                </button>
                            </div>

                        </div>

                    </form>
                </div>

                <!-- Right: Sticky Live Price Summary Component (5 cols) -->
                <aside class="lg:col-span-5 sticky top-24 space-y-6">
                    <x-booking-summary :decoration="$decorationModel" :package="$packageModel" />
                    
                    <!-- Direct Help Box -->
                    <div class="bg-brand-cream/80 rounded-2xl p-5 border border-brand-light-border text-xs space-y-3">
                        <h4 class="font-serif text-sm font-bold text-brand-charcoal flex items-center gap-2">
                            <i class="fas fa-headset text-brand-burgundy"></i>
                            Need Help or Custom Advice?
                        </h4>
                        <p class="text-brand-muted-brown leading-relaxed text-[11px]">
                            Have specific auspicious timings (Muhurat) or need a multi-ritual package across Siwan, Chapra, or Gorakhpur?
                        </p>
                        <a 
                            href="{{ \App\Services\NotificationService::getWhatsAppUrl('Namaste Aditya Utsav! I have a question regarding booking "' . $itemName . '".') }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center w-full py-2 px-3 text-xs font-bold text-green-800 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-colors gap-2"
                        >
                            <i class="fab fa-whatsapp text-green-600"></i>
                            <span>Chat with Decorator on WhatsApp</span>
                        </a>
                    </div>
                </aside>

            </div>

        </div>
    </section>

    <!-- Vanilla JavaScript for Multi-Step Form Logic, AJAX Availability & Live Price Calculation -->
    <script>
        const BASE_PRICE = {{ $basePriceVal }};
        const BOOKING_TYPE = '{{ $bookingType ?? 'decoration' }}';
        const ITEM_ID = {{ $isPkg ? $packageModel->id : $decorationModel->id }};

        let currentStep = 1;

        function goToStep(step) {
            if (step < 1 || step > 5) return;
            
            // Hide all panels
            document.querySelectorAll('.step-panel').forEach(p => p.classList.add('hidden'));
            
            // Show target panel
            const targetPanel = document.getElementById(`step-panel-${step}`);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
                currentStep = step;
            }

            // Update Stepper Navigation UI
            for (let i = 1; i <= 5; i++) {
                const tab = document.getElementById(`stepper-tab-${i}`);
                if (!tab) continue;
                const circle = tab.querySelector('.stepper-circle');
                const label = tab.querySelector('.stepper-label');

                if (i === step) {
                    circle.className = 'stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-burgundy text-brand-cream border-2 border-brand-gold shadow-sm';
                    label.className = 'stepper-label text-[11px] sm:text-xs text-brand-burgundy font-bold';
                } else if (i < step) {
                    circle.className = 'stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-green-700 text-white border border-green-800';
                    circle.innerHTML = '<i class="fas fa-check text-[10px]"></i>';
                    label.className = 'stepper-label text-[11px] sm:text-xs text-green-800 font-semibold';
                } else {
                    circle.className = 'stepper-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-brand-offwhite text-brand-muted-brown border border-brand-light-border';
                    circle.textContent = i;
                    label.className = 'stepper-label text-[11px] sm:text-xs text-brand-muted-brown';
                }
            }

            updateLiveReviewSummary();
            window.scrollTo({ top: 220, behavior: 'smooth' });
        }

        function validateAndGoToStep(targetStep) {
            if (currentStep === 1 && targetStep > 1) {
                const eventType = document.getElementById('event_type').value;
                const eventDate = document.getElementById('event_date').value;
                const startTime = document.getElementById('start_time').value;
                const endTime = document.getElementById('end_time').value;
                const guestCount = document.getElementById('guest_count').value;

                if (!eventType) {
                    alert('Please select the Ceremony / Event Type.');
                    document.getElementById('event_type').focus();
                    return;
                }
                if (!eventDate) {
                    alert('Please select your Event Date.');
                    document.getElementById('event_date').focus();
                    return;
                }
                if (!startTime || !endTime) {
                    alert('Please provide valid start and end times for the event.');
                    return;
                }
                if (endTime <= startTime) {
                    alert('Event end time must be later than start time.');
                    document.getElementById('end_time').focus();
                    return;
                }
                if (!guestCount || guestCount < 1) {
                    alert('Please specify the expected number of guests.');
                    document.getElementById('guest_count').focus();
                    return;
                }
            }

            if (currentStep === 2 && targetStep > 2) {
                const city = document.getElementById('city').value.trim();
                const address = document.getElementById('address_line').value.trim();
                const state = document.getElementById('state').value;

                if (!state) {
                    alert('Please select the state.');
                    return;
                }
                if (!city) {
                    alert('Please enter your venue city or town.');
                    document.getElementById('city').focus();
                    return;
                }
                if (!address) {
                    alert('Please enter the venue name and street address.');
                    document.getElementById('address_line').focus();
                    return;
                }
            }

            if (currentStep === 4 && targetStep > 4) {
                const name = document.getElementById('customer_name').value.trim();
                const phone = document.getElementById('customer_phone').value.trim();
                const email = document.getElementById('customer_email').value.trim();

                if (!name) {
                    alert('Please enter your full name.');
                    document.getElementById('customer_name').focus();
                    return;
                }
                if (!phone || phone.length < 10) {
                    alert('Please enter a valid mobile number.');
                    document.getElementById('customer_phone').focus();
                    return;
                }
                if (!email || !email.includes('@')) {
                    alert('Please enter a valid email address to receive your booking credentials.');
                    document.getElementById('customer_email').focus();
                    return;
                }
            }

            goToStep(targetStep);
        }

        // Quick City / State helper
        function setCityState(city, state) {
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            updateLiveReviewSummary();
        }

        // Live Add-ons & Total Price Calculation (Client Estimate)
        function calculateLiveAddonsTotal() {
            const checkboxes = document.querySelectorAll('.addon-checkbox:checked');
            let addonsTotal = 0;
            const selectedItems = [];

            checkboxes.forEach(cb => {
                const price = parseFloat(cb.getAttribute('data-price')) || 0;
                const name = cb.getAttribute('data-name') || 'Add-on';
                addonsTotal += price;
                selectedItems.push({ name, price });
            });

            const grandTotal = BASE_PRICE + addonsTotal;

            // Update Sticky Summary UI
            const subtotalEl = document.getElementById('summary-addons-subtotal');
            const totalEl = document.getElementById('summary-estimated-total');
            const containerEl = document.getElementById('summary-addons-list-container');
            const itemsEl = document.getElementById('summary-addons-items');

            if (subtotalEl) subtotalEl.textContent = '₹' + addonsTotal.toLocaleString('en-IN');
            if (totalEl) totalEl.textContent = '₹' + grandTotal.toLocaleString('en-IN');

            if (containerEl && itemsEl) {
                if (selectedItems.length > 0) {
                    containerEl.classList.remove('hidden');
                    itemsEl.innerHTML = selectedItems.map(item => `
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="truncate max-w-[180px]">${item.name}</span>
                            <span class="font-medium text-brand-charcoal">+₹${item.price.toLocaleString('en-IN')}</span>
                        </div>
                    `).join('');
                } else {
                    containerEl.classList.add('hidden');
                    itemsEl.innerHTML = '';
                }
            }

            // Update Review panel add-ons list
            const revAddonsList = document.getElementById('rev-addons-list');
            if (revAddonsList) {
                if (selectedItems.length > 0) {
                    revAddonsList.innerHTML = selectedItems.map(item => `
                        <div class="flex items-center justify-between">
                            <span>✓ ${item.name}</span>
                            <strong class="text-brand-charcoal">+₹${item.price.toLocaleString('en-IN')}</strong>
                        </div>
                    `).join('') + `
                        <div class="flex items-center justify-between pt-1 border-t border-brand-light-border/60 font-bold text-brand-charcoal">
                            <span>Add-ons Subtotal:</span>
                            <span>₹${addonsTotal.toLocaleString('en-IN')}</span>
                        </div>
                    `;
                } else {
                    revAddonsList.innerHTML = '<span class="italic text-gray-500">No add-ons selected (Base price only).</span>';
                }
            }
        }

        // Live Date Availability Check via AJAX
        function checkDateAvailability(dateVal) {
            if (!dateVal) return;

            const feedbackEl = document.getElementById('availability-feedback');
            const titleEl = document.getElementById('availability-title');
            const msgEl = document.getElementById('availability-message');

            if (!feedbackEl) return;

            feedbackEl.className = 'p-3 rounded-xl text-xs space-y-1 bg-brand-offwhite border border-brand-light-border text-brand-charcoal block';
            titleEl.innerHTML = '<i class="fas fa-spinner fa-spin text-brand-gold"></i> Checking slot availability...';
            msgEl.textContent = 'Connecting with Aditya Utsav booking calendar...';
            feedbackEl.classList.remove('hidden');

            const endpoint = (BOOKING_TYPE === 'decoration') 
                ? `{{ route('booking.checkAvailability') }}?decoration_id=${ITEM_ID}&event_date=${encodeURIComponent(dateVal)}`
                : `{{ route('booking.checkAvailability') }}?event_date=${encodeURIComponent(dateVal)}`;

            fetch(endpoint)
                .then(res => res.json())
                .then(data => {
                    if (data.available && data.status === 'available') {
                        feedbackEl.className = 'p-3 rounded-xl text-xs space-y-1 bg-emerald-50 border border-emerald-200 text-emerald-900 block';
                        titleEl.innerHTML = '<i class="fas fa-check-circle text-emerald-600"></i> Available for Booking Request';
                        msgEl.textContent = data.message;
                    } else if (data.available && data.status === 'pending_request') {
                        feedbackEl.className = 'p-3 rounded-xl text-xs space-y-1 bg-amber-50 border border-amber-200 text-amber-900 block';
                        titleEl.innerHTML = '<i class="fas fa-clock text-amber-600"></i> Pending Review On This Date';
                        msgEl.textContent = data.message;
                    } else {
                        feedbackEl.className = 'p-3 rounded-xl text-xs space-y-1 bg-rose-50 border border-rose-200 text-rose-900 block';
                        titleEl.innerHTML = '<i class="fas fa-calendar-times text-rose-600"></i> Date Unavailable';
                        msgEl.textContent = data.message;
                    }
                })
                .catch(err => {
                    feedbackEl.className = 'p-3 rounded-xl text-xs space-y-1 bg-gray-50 border border-gray-200 text-gray-700 block';
                    titleEl.innerHTML = '<i class="fas fa-info-circle text-brand-gold"></i> Date Selected';
                    msgEl.textContent = 'Our managers will verify final slot availability during quote review.';
                });
        }

        // Guest Capacity Helper
        function checkGuestCapacity(val) {
            // Optional capacity notifications
        }

        // Update Live Review Step text
        function updateLiveReviewSummary() {
            const eventType = document.getElementById('event_type')?.value || 'Wedding Celebration';
            const eventDate = document.getElementById('event_date')?.value || 'Select Date';
            const startTime = document.getElementById('start_time')?.value || '16:00';
            const endTime = document.getElementById('end_time')?.value || '23:30';
            const guestCount = document.getElementById('guest_count')?.value || '100';
            const address = document.getElementById('address_line')?.value || 'Not entered yet';
            const city = document.getElementById('city')?.value || 'Siwan';
            const state = document.getElementById('state')?.value || 'Bihar';
            const name = document.getElementById('customer_name')?.value || 'Not entered yet';
            const phone = document.getElementById('customer_phone')?.value || 'Not entered yet';
            const email = document.getElementById('customer_email')?.value || 'None provided';

            // Sidebar elements
            const sumType = document.getElementById('summary-event-type');
            const sumDate = document.getElementById('summary-event-date');
            const sumTime = document.getElementById('summary-event-time');
            const sumGuests = document.getElementById('summary-guest-count');

            if (sumType) sumType.textContent = eventType;
            if (sumDate) sumDate.textContent = eventDate;
            if (sumTime) sumTime.textContent = `${startTime} – ${endTime}`;
            if (sumGuests) sumGuests.textContent = `${guestCount} Guests`;

            // Step 5 Review panel elements
            const revType = document.getElementById('rev-event-type');
            const revDate = document.getElementById('rev-event-date');
            const revTime = document.getElementById('rev-event-time');
            const revGuests = document.getElementById('rev-guest-count');
            const revAddr = document.getElementById('rev-address');
            const revCityState = document.getElementById('rev-city-state');
            const revName = document.getElementById('rev-customer-name');
            const revPhone = document.getElementById('rev-customer-phone');
            const revEmail = document.getElementById('rev-customer-email');

            if (revType) revType.textContent = eventType;
            if (revDate) revDate.textContent = eventDate;
            if (revTime) revTime.textContent = `${startTime} – ${endTime}`;
            if (revGuests) revGuests.textContent = `${guestCount} Guests`;
            if (revAddr) revAddr.textContent = address;
            if (revCityState) revCityState.textContent = `${city}, ${state}`;
            if (revName) revName.textContent = name;
            if (revPhone) revPhone.textContent = phone;
            if (revEmail) revEmail.textContent = email;
        }

        // Duplicate Submission Prevention
        document.getElementById('booking-request-form')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submit-booking-btn');
            const labelEl = document.getElementById('submit-btn-label');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                if (labelEl) labelEl.textContent = 'Submitting Booking Request...';
            }
        });

        // Initialize state on load
        document.addEventListener('DOMContentLoaded', () => {
            calculateLiveAddonsTotal();
            updateLiveReviewSummary();
        });
    </script>

@endsection
