@extends('layouts.account')

@section('title', 'My Account Dashboard | Aditya Utsav Bihar')

@section('account_content')

<div class="space-y-6">
    
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-brand-deep-burgundy to-brand-burgundy rounded-2xl p-6 sm:p-8 text-white border border-brand-gold shadow-soft-luxury relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#D4AF37_1px,transparent_1px)] [background-size:20px_20px]"></div>
        
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">
                    <i class="fas fa-crown text-[11px]"></i> CUSTOMER DASHBOARD
                </span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white mt-1">
                    Namaste, {{ $user->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-brand-cream/80 mt-1 max-w-lg">
                    Manage your wedding decoration bookings, track quotation reviews, and request rescheduling across Siwan, Gopalganj, Chapra and nearby UP.
                </p>
            </div>

            <a 
                href="{{ route('decorations.index') }}" 
                class="self-start sm:self-auto px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-brand-charcoal bg-brand-gold hover:bg-brand-gold-light rounded-xl border border-white shadow-sm transition-all whitespace-nowrap"
            >
                <i class="fas fa-plus mr-1"></i> Browse Decorations
            </a>
        </div>
    </div>

    <!-- 5 Real Stat Counter Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Bookings -->
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-brand-muted-brown">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Bookings</span>
                <div class="w-8 h-8 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xs">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                {{ $totalBookings }}
            </div>
            <span class="text-[10px] text-brand-muted-brown block">All logged celebrations</span>
        </div>

        <!-- Pending Review -->
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-amber-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Pending Review</span>
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-amber-900">
                {{ $pendingBookings }}
            </div>
            <span class="text-[10px] text-amber-700 block">Manager review in progress</span>
        </div>

        <!-- Accepted (Ready for Payment) -->
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-blue-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Accepted</span>
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-blue-900">
                {{ $acceptedBookings ?? 0 }}
            </div>
            <span class="text-[10px] text-blue-700 block">Ready for advance deposit</span>
        </div>

        <!-- Confirmed / Scheduled -->
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-emerald-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Confirmed Slots</span>
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-emerald-900">
                {{ $confirmedBookings }}
            </div>
            <span class="text-[10px] text-emerald-700 block">Dates locked &amp; scheduled</span>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-purple-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Completed</span>
                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center text-xs">
                    <i class="fas fa-award"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-purple-900">
                {{ $completedBookings }}
            </div>
            <span class="text-[10px] text-purple-700 block">Executed events</span>
        </div>

    </div>

    <!-- Recent Bookings Section -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-5">
        
        <div class="flex items-center justify-between pb-3 border-b border-brand-light-border/70">
            <div>
                <h2 class="font-serif text-lg sm:text-xl font-bold text-brand-charcoal">
                    Recent Decoration Bookings
                </h2>
                <p class="text-xs text-brand-muted-brown">
                    Your latest ceremony requests and upcoming event statuses.
                </p>
            </div>

            @if($totalBookings > 0)
                <a href="{{ route('account.bookings') }}" class="text-xs font-bold text-brand-burgundy hover:underline flex items-center gap-1">
                    <span>View All ({{ $totalBookings }})</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            @endif
        </div>

        @if($recentBookings->count() > 0)
            <div class="divide-y divide-brand-light-border/60">
                @foreach($recentBookings as $booking)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        
                        <!-- Left Info -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-brand-offwhite flex-shrink-0 border border-brand-light-border flex items-center justify-center">
                                @if($booking->booked_item_image)
                                    <img 
                                        src="{{ $booking->booked_item_image }}" 
                                        alt="{{ $booking->booked_item_name }}" 
                                        class="w-full h-full object-cover"
                                    />
                                @else
                                    <i class="fas fa-camera text-brand-gold text-base"></i>
                                @endif
                            </div>
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-serif text-sm font-bold text-brand-charcoal truncate">
                                        {{ $booking->booked_item_name }}
                                    </span>
                                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $booking->status_badge_classes }}">
                                        {{ $booking->status_label }}
                                    </span>
                                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $booking->payment_status_badge_classes }}">
                                        {{ $booking->payment_status_label }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-brand-muted-brown flex-wrap">
                                    <span><i class="fas fa-tag text-brand-gold text-[10px] mr-1"></i>{{ $booking->event_type }}</span>
                                    <span><i class="fas fa-calendar-alt text-brand-gold text-[10px] mr-1"></i>{{ $booking->formatted_event_date }}</span>
                                    <span><i class="fas fa-map-marker-alt text-brand-gold text-[10px] mr-1"></i>{{ $booking->city }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <div class="text-right">
                                <span class="text-xs text-brand-muted-brown block font-medium">Estimated Total</span>
                                <span class="font-serif text-sm font-bold text-brand-burgundy block">
                                    {{ $booking->formatted_estimated_total }}
                                </span>
                            </div>
                            <a 
                                href="{{ route('account.bookings.show', $booking->booking_reference) }}" 
                                class="px-3.5 py-1.5 text-xs font-bold text-brand-charcoal bg-brand-offwhite hover:bg-brand-gold/20 rounded-xl border border-brand-light-border transition"
                            >
                                Details &rarr;
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-8 text-center space-y-3">
                <div class="w-14 h-14 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-xl">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <h3 class="font-serif text-base font-bold text-brand-charcoal">
                    No bookings yet
                </h3>
                <p class="text-xs text-brand-muted-brown max-w-sm mx-auto">
                    Explore our authentic Bihar wedding decorations and find the perfect stage, mandap, or haldi setup for your celebration.
                </p>
                <div class="pt-2">
                    <a href="{{ route('decorations.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow">
                        <i class="fas fa-compass text-brand-gold"></i>
                        <span>Browse Decorations</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!-- Financial & Quick Navigation Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Quotations Card -->
        <a href="{{ route('account.quotations.index') }}" class="bg-white p-5 rounded-2xl border border-brand-light-border shadow-soft-luxury hover:border-brand-gold transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold uppercase text-brand-muted-brown tracking-wider">Quotations</span>
                <div class="font-serif text-xl font-bold text-brand-charcoal mt-1">{{ $totalQuotations }} Total</div>
                <span class="text-[11px] text-amber-700 font-medium">{{ $pendingQuotations }} Pending Review</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </a>

        <!-- Total Paid Card -->
        <a href="{{ route('account.payments.index') }}" class="bg-white p-5 rounded-2xl border border-brand-light-border shadow-soft-luxury hover:border-brand-gold transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold uppercase text-brand-muted-brown tracking-wider">Payments Recorded</span>
                <div class="font-serif text-xl font-bold text-emerald-800 mt-1">₹{{ number_format($totalPaid) }}</div>
                <span class="text-[11px] text-emerald-700 font-medium">Verified Receipts</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-receipt"></i>
            </div>
        </a>

        <!-- Direct WhatsApp Support -->
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="bg-gradient-to-br from-emerald-600 to-emerald-700 p-5 rounded-2xl border border-emerald-500 text-white shadow-soft-luxury hover:shadow-lg transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold uppercase text-emerald-200 tracking-wider">Need Immediate Help?</span>
                <div class="font-serif text-lg font-bold text-white mt-1">Chat on WhatsApp</div>
                <span class="text-[11px] text-emerald-100">Direct support from team</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                <i class="fab fa-whatsapp"></i>
            </div>
        </a>
    </div>

</div>

@endsection
