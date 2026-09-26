@extends('layouts.account')

@section('title', 'Booking #' . $booking->booking_reference . ' | Aditya Utsav Bihar')

@section('account_content')

<div class="space-y-6">
    
    <!-- Top Header Bar with Breadcrumb back to My Bookings -->
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('account.bookings') }}" class="inline-flex items-center gap-2 text-xs font-bold text-brand-burgundy hover:underline">
            <i class="fas fa-arrow-left text-[10px]"></i>
            <span>Back to All Bookings</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-xs text-brand-muted-brown">Created on: {{ $booking->created_at->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    <!-- Main Booking Summary Card -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
        
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-brand-light-border/70">
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="font-serif text-lg sm:text-xl font-bold text-brand-burgundy tracking-wider selection:bg-brand-gold">
                        {{ $booking->booking_reference }}
                    </span>
                    <span class="inline-block text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full border {{ $booking->status_badge_classes }}">
                        {{ $booking->status_label }}
                    </span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal mt-1">
                    {{ $booking->decoration->name }}
                </h1>
            </div>

            <!-- Total Amount Card -->
            <div class="p-3.5 rounded-xl bg-brand-offwhite border border-brand-light-border self-start sm:self-auto text-left sm:text-right">
                <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-medium">Estimated Total</span>
                <span class="font-serif text-2xl sm:text-3xl font-bold text-brand-burgundy">
                    {{ $booking->formatted_estimated_total }}
                </span>
            </div>
        </div>

        <!-- Pending Cancellation / Reschedule Warning Notices (If any) -->
        @if($booking->has_pending_cancellation)
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                <div class="font-bold flex items-center gap-2 text-rose-950">
                    <i class="fas fa-exclamation-circle text-rose-600"></i>
                    Cancellation Request Under Review
                </div>
                <p class="text-[11px] text-rose-800 leading-relaxed">
                    You have requested cancellation for this booking (Reason: <em>"{{ $booking->cancellationRequests()->where('status', 'pending')->first()->reason ?? 'Client request' }}"</em>). Our manager will review and confirm status within 24 hours.
                </p>
            </div>
        @endif

        @if($booking->has_pending_reschedule)
            @php $pRes = $booking->rescheduleRequests()->where('status', 'pending')->first(); @endphp
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-2 text-amber-950">
                    <i class="fas fa-history text-amber-600"></i>
                    Reschedule Request Under Review
                </div>
                <p class="text-[11px] text-amber-800 leading-relaxed">
                    You have requested a new date: <strong class="text-amber-950">{{ $pRes ? $pRes->formatted_requested_date : 'New Date' }}</strong> ({{ $pRes ? $pRes->formatted_time_range : '' }}). Our logistics team is checking flower inventory and crew allocation.
                </p>
            </div>
        @endif

        <!-- Two Columns: Left Image & Specs (7 cols), Right Inclusions & Cost (5 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left: Decoration Showcase & Event Specifications (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Decoration Snapshot -->
                <div class="rounded-2xl overflow-hidden bg-brand-charcoal border border-brand-light-border shadow-md h-60 sm:h-72 relative group">
                    <img 
                        src="{{ $booking->decoration->safe_primary_image }}" 
                        alt="{{ $booking->decoration->name }}" 
                        class="w-full h-full object-cover"
                        onerror="this.src='{{ asset('images/placeholders/decoration-placeholder.svg') }}'"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-4 right-4 text-white flex items-center justify-between">
                        <span class="text-xs font-bold text-brand-gold-light">
                            {{ $booking->decoration->category->name ?? 'Wedding Decor' }} • {{ $booking->decoration->style ?? 'Traditional' }}
                        </span>
                        <a href="{{ route('decorations.show', $booking->decoration->slug) }}" class="text-xs font-semibold text-white bg-black/50 hover:bg-black/80 px-2.5 py-1 rounded border border-white/20 transition-colors">
                            View Catalog Design <i class="fas fa-external-link-alt text-[10px] ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Event Details Grid -->
                <div class="p-5 rounded-2xl bg-brand-offwhite border border-brand-light-border space-y-3 text-xs">
                    <h3 class="font-serif text-sm font-bold text-brand-charcoal uppercase tracking-wider pb-1 border-b border-brand-light-border/60">
                        Ceremony Schedule &amp; Venue
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-brand-muted-brown">
                        <div>
                            <span class="font-semibold text-brand-charcoal block">Ceremony Type:</span>
                            <span>{{ $booking->event_type }}</span>
                        </div>

                        <div>
                            <span class="font-semibold text-brand-charcoal block">Event Date:</span>
                            <span class="font-bold text-brand-burgundy">{{ $booking->formatted_event_date }}</span>
                        </div>

                        <div>
                            <span class="font-semibold text-brand-charcoal block">Time Window:</span>
                            <span>{{ $booking->formatted_time_range }}</span>
                        </div>

                        <div>
                            <span class="font-semibold text-brand-charcoal block">Expected Guests:</span>
                            <span>{{ $booking->guest_count }} Guests</span>
                        </div>

                        <div class="col-span-full">
                            <span class="font-semibold text-brand-charcoal block">Venue Address:</span>
                            <span class="text-brand-charcoal">{{ $booking->address_line }}, {{ $booking->locality ? $booking->locality . ', ' : '' }}{{ $booking->city }}, {{ $booking->state }} {{ $booking->pincode ? '— ' . $booking->pincode : '' }}</span>
                        </div>

                        @if($booking->special_requirements)
                            <div class="col-span-full p-2.5 bg-white rounded-lg border border-brand-light-border">
                                <span class="font-semibold text-brand-charcoal block">Special Client Requests:</span>
                                <p class="italic text-brand-charcoal mt-0.5">{{ $booking->special_requirements }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right: Cost Calculation & Actions (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Financial Breakdown -->
                <div class="p-5 rounded-2xl bg-brand-offwhite border border-brand-light-border space-y-3 text-xs">
                    <h3 class="font-serif text-sm font-bold text-brand-charcoal uppercase tracking-wider pb-1 border-b border-brand-light-border/60">
                        Pricing Estimate
                    </h3>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-brand-muted-brown">
                            <span>Base Setup ({{ $booking->decoration->name }}):</span>
                            <span class="font-semibold text-brand-charcoal">{{ $booking->formatted_base_amount }}</span>
                        </div>

                        @if($booking->addons->count() > 0)
                            <div class="space-y-1.5 pt-2 border-t border-brand-light-border/60">
                                <span class="font-bold text-brand-charcoal uppercase tracking-wider text-[11px] block">Selected Add-ons:</span>
                                @foreach($booking->addons as $addonItem)
                                    <div class="flex items-center justify-between text-[11px] text-brand-muted-brown pl-2 border-l-2 border-brand-gold/40">
                                        <span class="truncate max-w-[180px]">{{ $addonItem->addon->name ?? 'Custom Addon' }}</span>
                                        <span class="font-medium text-brand-charcoal">+{{ $addonItem->formatted_total_price }}</span>
                                    </div>
                                @endforeach
                                <div class="flex items-center justify-between pt-1 text-brand-muted-brown">
                                    <span>Add-ons Subtotal:</span>
                                    <span class="font-semibold text-brand-charcoal">{{ $booking->formatted_addon_amount }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="p-3.5 rounded-xl bg-gradient-to-br from-white to-brand-cream border border-brand-gold/40 flex items-baseline justify-between mt-3">
                            <div>
                                <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-bold">Estimated Total</span>
                                <span class="font-serif text-2xl font-bold text-brand-burgundy">{{ $booking->formatted_estimated_total }}</span>
                            </div>
                            <span class="text-[10px] text-brand-burgundy bg-brand-offwhite px-2 py-0.5 rounded border border-brand-light-border font-semibold">
                                Pending Quote
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Booking Actions (Cancellation / Reschedule / WhatsApp) -->
                <div class="p-5 rounded-2xl bg-white border border-brand-light-border space-y-3 text-xs shadow-sm">
                    <h4 class="font-serif text-sm font-bold text-brand-charcoal">Booking Actions</h4>
                    
                    <!-- WhatsApp Support -->
                    <a 
                        href="https://wa.me/919931200000?text={{ urlencode('Namaste Aditya Utsav! I have a question regarding my booking reference #' . $booking->booking_reference . ' for "' . $booking->decoration->name . '".') }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="w-full py-2.5 px-3 text-xs font-bold text-green-900 bg-green-50 hover:bg-green-100 rounded-xl border border-green-200 transition-colors flex items-center justify-center gap-2"
                    >
                        <i class="fab fa-whatsapp text-green-600"></i>
                        <span>Inquire on WhatsApp</span>
                    </a>

                    <!-- Request Reschedule Trigger -->
                    @if(in_array($booking->status, ['pending', 'quoted', 'confirmed', 'advance_paid']) && !$booking->has_pending_reschedule)
                        <button 
                            type="button" 
                            onclick="document.getElementById('reschedule-modal').classList.remove('hidden')" 
                            class="w-full py-2.5 px-3 text-xs font-semibold text-brand-charcoal hover:text-brand-burgundy bg-brand-offwhite hover:bg-brand-cream rounded-xl border border-brand-light-border transition-colors flex items-center justify-center gap-1.5"
                        >
                            <i class="fas fa-calendar-alt text-brand-gold"></i>
                            <span>Request Reschedule / Date Change</span>
                        </button>
                    @endif

                    <!-- Request Cancellation Trigger -->
                    @if(in_array($booking->status, ['pending', 'quoted', 'confirmed', 'advance_paid', 'scheduled']) && !$booking->has_pending_cancellation)
                        <button 
                            type="button" 
                            onclick="document.getElementById('cancellation-modal').classList.remove('hidden')" 
                            class="w-full py-2.5 px-3 text-xs font-semibold text-rose-700 hover:bg-rose-50 rounded-xl border border-rose-200 transition-colors flex items-center justify-center gap-1.5"
                        >
                            <i class="fas fa-times-circle text-rose-500"></i>
                            <span>Request Cancellation</span>
                        </button>
                    @endif
                </div>

            </div>

        </div>

    </div>

    <!-- Booking Status Milestone Timeline Section -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
        <h3 class="font-serif text-lg font-bold text-brand-charcoal">
            Booking Progress Timeline
        </h3>

        <!-- Interactive Milestone Steps -->
        @php
            $milestones = [
                'pending' => ['label' => 'Request Submitted', 'desc' => 'Logged in Aditya Utsav system.'],
                'quoted' => ['label' => 'Quotation Sent', 'desc' => 'Customized quote shared via phone/email.'],
                'confirmed' => ['label' => 'Booking Confirmed', 'desc' => 'Slot locked with advance token.'],
                'scheduled' => ['label' => 'Setup Scheduled', 'desc' => 'Crew and transportation dispatched.'],
                'completed' => ['label' => 'Event Completed', 'desc' => 'Ceremony successfully decorated.'],
            ];

            $order = ['pending', 'quoted', 'confirmed', 'scheduled', 'completed'];
            $currentStatusIndex = array_search($booking->status, $order);
            if ($currentStatusIndex === false) $currentStatusIndex = 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs">
            @foreach($milestones as $key => $ms)
                @php
                    $thisIndex = array_search($key, $order);
                    $isPassed = ($thisIndex <= $currentStatusIndex);
                    $isCurrent = ($thisIndex === $currentStatusIndex);
                @endphp
                <div class="p-4 rounded-xl border {{ $isCurrent ? 'bg-brand-burgundy/5 border-brand-burgundy ring-1 ring-brand-burgundy/30' : ($isPassed ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-brand-offwhite/50 border-brand-light-border text-gray-400') }} space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold {{ $isCurrent ? 'bg-brand-burgundy text-white' : ($isPassed ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600') }}">
                            @if($isPassed) <i class="fas fa-check"></i> @else {{ $loop->iteration }} @endif
                        </span>
                        <strong class="{{ $isCurrent ? 'text-brand-burgundy font-bold' : ($isPassed ? 'text-emerald-900 font-bold' : 'text-gray-600') }}">
                            {{ $ms['label'] }}
                        </strong>
                    </div>
                    <p class="text-[11px] {{ $isPassed ? 'text-brand-muted-brown' : 'text-gray-400' }} leading-relaxed pl-7">
                        {{ $ms['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Recorded Status Log History from Database -->
        <div class="pt-4 border-t border-brand-light-border/70 space-y-3">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-charcoal block">Activity Log:</span>
            <div class="space-y-2 text-xs">
                @forelse($booking->statusHistories as $log)
                    <div class="p-3 rounded-xl bg-brand-offwhite border border-brand-light-border/70 flex items-start justify-between gap-4">
                        <div class="space-y-0.5">
                            <span class="font-bold text-brand-charcoal capitalize">{{ str_replace('_', ' ', $log->status) }}</span>
                            @if($log->note)
                                <p class="text-[11px] text-brand-muted-brown">{{ $log->note }}</p>
                            @endif
                        </div>
                        <span class="text-[10px] text-brand-muted-brown whitespace-nowrap">
                            {{ $log->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-brand-muted-brown italic">No previous logs recorded.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- Modal 1: Request Cancellation Modal -->
<div id="cancellation-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 border border-brand-light-border shadow-2xl space-y-5" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-light-border">
            <h3 class="font-serif text-lg font-bold text-brand-charcoal flex items-center gap-2">
                <i class="fas fa-times-circle text-rose-600"></i>
                Request Booking Cancellation
            </h3>
            <button type="button" onclick="document.getElementById('cancellation-modal').classList.add('hidden')" class="text-gray-400 hover:text-brand-charcoal">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <p class="text-xs text-brand-muted-brown leading-relaxed">
            Please tell us why you wish to cancel your booking request for <strong>{{ $booking->decoration->name }}</strong> on <strong>{{ $booking->formatted_event_date }}</strong>. Our manager will review and process your request.
        </p>

        <form action="{{ route('account.bookings.cancel', $booking->booking_reference) }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="cancel_reason" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    Primary Reason <span class="text-red-500">*</span>
                </label>
                <select id="cancel_reason" name="reason" required class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium">
                    <option value="">Select a reason...</option>
                    <option value="Wedding / Event Postponed">Wedding / Event Postponed</option>
                    <option value="Change of Venue">Change of Venue</option>
                    <option value="Selected a Different Decoration Design">Selected a Different Decoration Design</option>
                    <option value="Budget / Financial Constraints">Budget / Financial Constraints</option>
                    <option value="Family / Personal Reasons">Family / Personal Reasons</option>
                    <option value="Other">Other Reason</option>
                </select>
            </div>

            <div class="space-y-1">
                <label for="cancel_details" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    Additional Details (Optional)
                </label>
                <textarea id="cancel_details" name="details" rows="3" placeholder="Provide any details for our manager..." class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-normal"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('cancellation-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-cream rounded-xl border border-brand-light-border">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow transition-all">
                    Submit Cancellation Request
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Modal 2: Request Reschedule Modal -->
<div id="reschedule-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 border border-brand-light-border shadow-2xl space-y-5" onclick="event.stopPropagation()">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-light-border">
            <h3 class="font-serif text-lg font-bold text-brand-charcoal flex items-center gap-2">
                <i class="fas fa-calendar-alt text-brand-gold"></i>
                Request Date / Time Reschedule
            </h3>
            <button type="button" onclick="document.getElementById('reschedule-modal').classList.add('hidden')" class="text-gray-400 hover:text-brand-charcoal">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <p class="text-xs text-brand-muted-brown leading-relaxed">
            Select your preferred new event date and time window for <strong>{{ $booking->decoration->name }}</strong>.
        </p>

        <form action="{{ route('account.bookings.reschedule', $booking->booking_reference) }}" method="POST" class="space-y-4">
            @csrf

            <!-- New Date -->
            <div class="space-y-1">
                <label for="requested_date" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    New Event Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    id="requested_date" 
                    name="requested_date" 
                    min="{{ date('Y-m-d') }}" 
                    required 
                    class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                />
            </div>

            <!-- Start & End Time -->
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label for="requested_start_time" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="time" 
                        id="requested_start_time" 
                        name="requested_start_time" 
                        value="{{ $booking->start_time }}" 
                        required 
                        class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                    />
                </div>

                <div class="space-y-1">
                    <label for="requested_end_time" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="time" 
                        id="requested_end_time" 
                        name="requested_end_time" 
                        value="{{ $booking->end_time }}" 
                        required 
                        class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                    />
                </div>
            </div>

            <!-- Reason -->
            <div class="space-y-1">
                <label for="reschedule_reason" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    Reason for Reschedule (Optional)
                </label>
                <textarea id="reschedule_reason" name="reason" rows="2" placeholder="e.g. Auspicious muhurat timing shifted by family priest..." class="w-full px-3.5 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-normal"></textarea>
            </div>

            <div class="pt-2 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('reschedule-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-cream rounded-xl border border-brand-light-border">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow transition-all">
                    Submit Reschedule Request
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
