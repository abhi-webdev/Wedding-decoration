@extends('layouts.account')

@section('title', 'My Bookings | Aditya Utsav Bihar')

@section('account_content')

<div class="space-y-6">
    
    <!-- Page Header & Filter Tabs -->
    <div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
                    My Wedding Decoration Bookings
                </h1>
                <p class="text-xs text-brand-muted-brown mt-0.5">
                    View schedule dates, check quotation statuses, and request modifications.
                </p>
            </div>
            
            <a 
                href="{{ route('decorations.index') }}" 
                class="self-start sm:self-auto px-4 py-2 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-sm transition-all whitespace-nowrap"
            >
                <i class="fas fa-plus mr-1 text-brand-gold"></i> Book New Decoration
            </a>
        </div>

        <!-- Filter Tabs (All, Pending, Accepted, Confirmed, Completed, Cancelled) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold scrollbar-none">
            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'all', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>All Bookings</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ ($statusFilter === 'all' || empty($statusFilter)) ? 'bg-white text-brand-burgundy' : 'bg-white border border-brand-light-border' }}">{{ $counts['all'] }}</span>
            </a>

            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'pending', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>Pending Review</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $statusFilter === 'pending' ? 'bg-white text-amber-800' : 'bg-white border border-brand-light-border' }}">{{ $counts['pending'] }}</span>
            </a>

            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'accepted', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'accepted' ? 'bg-blue-600 text-white shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>Accepted</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $statusFilter === 'accepted' ? 'bg-white text-blue-800' : 'bg-white border border-brand-light-border' }}">{{ $counts['accepted'] ?? 0 }}</span>
            </a>

            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'confirmed', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'confirmed' ? 'bg-emerald-700 text-white shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>Confirmed &amp; Scheduled</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $statusFilter === 'confirmed' ? 'bg-white text-emerald-800' : 'bg-white border border-brand-light-border' }}">{{ $counts['confirmed'] }}</span>
            </a>

            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'completed', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'completed' ? 'bg-purple-700 text-white shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>Completed</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $statusFilter === 'completed' ? 'bg-white text-purple-800' : 'bg-white border border-brand-light-border' }}">{{ $counts['completed'] }}</span>
            </a>

            <a 
                href="{{ request()->fullUrlWithQuery(['status' => 'cancelled', 'page' => 1]) }}" 
                class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap flex items-center gap-1.5 {{ $statusFilter === 'cancelled' ? 'bg-rose-700 text-white shadow-sm font-bold' : 'bg-brand-offwhite text-brand-charcoal hover:bg-brand-cream' }}"
            >
                <span>Cancelled</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $statusFilter === 'cancelled' ? 'bg-white text-rose-800' : 'bg-white border border-brand-light-border' }}">{{ $counts['cancelled'] }}</span>
            </a>
        </div>

        <!-- Search & Sorting Toolbar -->
        <div class="pt-2 border-t border-brand-light-border/60 flex flex-col sm:flex-row items-center justify-between gap-3">
            
            <!-- Search Form -->
            <form action="{{ route('account.bookings') }}" method="GET" class="w-full sm:w-auto flex-grow max-w-md relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $searchQuery }}" 
                    placeholder="Search by reference, decoration, ceremony, city..." 
                    class="w-full pl-9 pr-16 py-2 text-xs bg-brand-offwhite border border-brand-light-border rounded-xl focus:outline-none focus:border-brand-burgundy font-medium"
                />
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                @if(!empty($searchQuery))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute right-3 top-2 text-xs text-gray-400 hover:text-red-500">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>

            <!-- Sort By -->
            <div class="w-full sm:w-auto flex items-center justify-end gap-2 text-xs">
                <span class="text-brand-muted-brown whitespace-nowrap font-medium">Sort:</span>
                <select 
                    onchange="window.location.href = this.value" 
                    class="px-3 py-1.5 bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal text-xs font-medium focus:outline-none focus:border-brand-burgundy"
                >
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => 1]) }}" {{ $sortOption === 'newest' ? 'selected' : '' }}>Newest Request</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest', 'page' => 1]) }}" {{ $sortOption === 'oldest' ? 'selected' : '' }}>Oldest Request</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_soonest', 'page' => 1]) }}" {{ $sortOption === 'date_soonest' ? 'selected' : '' }}>Event Date: Soonest</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_latest', 'page' => 1]) }}" {{ $sortOption === 'date_latest' ? 'selected' : '' }}>Event Date: Latest</option>
                </select>
            </div>

        </div>

    </div>

    <!-- Bookings Cards List -->
    @if($bookings->count() > 0)
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-brand-light-border shadow-soft-luxury hover:border-brand-gold/60 transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                    
                    <!-- Left Thumbnail & Details -->
                    <div class="flex items-start gap-4 flex-grow min-w-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-brand-offwhite flex-shrink-0 border border-brand-light-border shadow-sm flex items-center justify-center">
                            @if($booking->booked_item_image)
                                <img 
                                    src="{{ $booking->booked_item_image }}" 
                                    alt="{{ $booking->booked_item_name }}" 
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <i class="fas fa-camera text-brand-gold text-lg"></i>
                            @endif
                        </div>

                        <div class="space-y-1.5 min-w-0 flex-grow">
                            <!-- Ref & Status Badge -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold text-brand-burgundy tracking-wider selection:bg-brand-gold">
                                    {{ $booking->booking_reference }}
                                </span>
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $booking->status_badge_classes }}">
                                    {{ $booking->status_label }}
                                </span>
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $booking->payment_status_badge_classes }}">
                                    {{ $booking->payment_status_label }}
                                </span>
                                @if($booking->has_pending_cancellation)
                                    <span class="text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Cancellation Pending
                                    </span>
                                @endif
                                @if($booking->has_pending_reschedule)
                                    <span class="text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-history mr-1"></i> Reschedule Pending
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-serif text-base sm:text-lg font-bold text-brand-charcoal truncate">
                                {{ $booking->booked_item_name }}
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-xs text-brand-muted-brown pt-0.5">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar-day text-brand-gold text-[11px]"></i>
                                    <span class="font-semibold text-brand-charcoal">{{ $booking->formatted_event_date }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-map-marker-alt text-brand-gold text-[11px]"></i>
                                    <span class="truncate">{{ $booking->city }}, {{ $booking->state }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-tag text-brand-gold text-[11px]"></i>
                                    <span>{{ $booking->event_type }} ({{ $booking->guest_count }} Guests)</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-clock text-brand-gold text-[11px]"></i>
                                    <span>{{ $booking->formatted_time_range }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Pricing & View Button -->
                    <div class="w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-brand-light-border/60 flex md:flex-col items-center md:items-end justify-between md:justify-center gap-3 flex-shrink-0">
                        <div class="text-left md:text-right">
                            <span class="text-[10px] text-brand-muted-brown uppercase tracking-wider block font-medium">Estimated Amount</span>
                            <span class="font-serif text-xl sm:text-2xl font-bold text-brand-burgundy">
                                {{ $booking->formatted_estimated_total }}
                            </span>
                        </div>

                        <a 
                            href="{{ route('account.bookings.show', $booking->booking_reference) }}" 
                            class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-sm hover:shadow-gold-glow transition-all inline-flex items-center gap-1.5 whitespace-nowrap"
                        >
                            <span>View Details</span>
                            <i class="fas fa-arrow-right text-[10px] text-brand-gold"></i>
                        </a>
                    </div>

                </div>
            @endforeach

            <!-- Pagination Links -->
            <div class="pt-6 flex justify-center">
                {{ $bookings->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-2xl p-10 border border-brand-light-border shadow-soft-luxury text-center space-y-4 max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto text-2xl">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="font-serif text-xl font-bold text-brand-charcoal">
                No matching bookings found
            </h3>
            <p class="text-xs sm:text-sm text-brand-muted-brown leading-relaxed">
                We couldn't find any decoration bookings matching your active filters. Explore our authentic Bihar wedding stages, mandaps, and haldi setups to submit a new request.
            </p>
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                @if(request()->hasAny(['status', 'search', 'sort']))
                    <a href="{{ route('account.bookings') }}" class="px-4 py-2 text-xs font-semibold text-brand-charcoal bg-brand-offwhite hover:bg-brand-cream rounded-xl border border-brand-light-border">
                        Clear Filters
                    </a>
                @endif
                <a href="{{ route('decorations.index') }}" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow">
                    Browse Decorations
                </a>
            </div>
        </div>
    @endif

</div>

@endsection
