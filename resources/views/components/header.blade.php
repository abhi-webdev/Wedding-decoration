<!-- Main Header & Navigation -->
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-brand-light-border/80 shadow-soft-luxury transition-all duration-300">
    <!-- Subtle Golden Top Accent Line -->
    <div class="h-[2px] bg-gradient-to-r from-brand-deep-burgundy via-brand-gold to-brand-deep-burgundy"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20 gap-2 lg:gap-6">
            
            <!-- 1. Left: Brand Logo Treatment -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5 sm:gap-3" aria-label="Aditya Utsav Home">
                    <!-- Brand Motif Badge -->
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-brand-deep-burgundy to-brand-burgundy border border-brand-gold/60 flex items-center justify-center text-brand-gold shadow-sm group-hover:shadow-gold-glow group-hover:scale-105 transition-all duration-300">
                        <i class="fas fa-om text-base sm:text-lg"></i>
                    </div>
                    <!-- Brand Typography -->
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1">
                            <span class="font-serif text-lg sm:text-2xl font-bold tracking-tight sm:tracking-wider text-brand-burgundy leading-none whitespace-nowrap">
                                ADITYA <span class="text-brand-gold font-normal">UTSAV</span>
                            </span>
                        </div>
                        <span class="text-[9px] sm:text-[10px] tracking-[0.2em] sm:tracking-[0.25em] font-semibold text-brand-muted-brown uppercase mt-0.5 whitespace-nowrap">
                            Bihar Wedding Decor
                        </span>
                    </div>
                </a>
            </div>

            <!-- 2. Center: Primary Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center space-x-5 xl:space-x-7 text-sm font-semibold tracking-wide text-brand-charcoal shrink-0" aria-label="Main Navigation">
                <a 
                    href="{{ route('home') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative {{ request()->routeIs('home') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    Home
                </a>

                <a 
                    href="{{ route('decorations.index') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative {{ request()->routeIs('decorations.*') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    Decorations
                </a>

                <a 
                    href="{{ route('packages.index') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative {{ request()->routeIs('packages.*') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    Packages
                </a>

                <a 
                    href="{{ route('videos.index') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative inline-flex items-center gap-1.5 {{ request()->routeIs('videos.*') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    <span>Reels &amp; Videos</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-brand-burgundy text-brand-cream border border-brand-gold/40">New</span>
                </a>

                <a 
                    href="{{ route('gallery.index') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative {{ request()->routeIs('gallery.*') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    Gallery
                </a>

                <a 
                    href="{{ route('offers.index') }}" 
                    class="whitespace-nowrap transition-colors py-1 relative {{ request()->routeIs('offers.*') ? 'text-brand-burgundy font-bold after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[2px] after:bg-brand-gold after:rounded-full' : 'hover:text-brand-burgundy text-brand-charcoal/90' }}"
                >
                    Offers
                </a>

                <!-- Accessible "More ▾" Dropdown -->
                <div class="relative" id="nav-more-dropdown-container">
                    <button 
                        type="button" 
                        id="nav-more-btn"
                        class="inline-flex items-center gap-1 py-1 text-sm font-semibold tracking-wide text-brand-charcoal/90 hover:text-brand-burgundy focus:outline-none transition-colors whitespace-nowrap"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        <span>More</span>
                        <i class="fas fa-chevron-down text-[10px] text-brand-muted-brown transition-transform duration-200" id="nav-more-icon"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        id="nav-more-menu"
                        class="absolute left-0 mt-3 w-64 rounded-2xl shadow-2xl bg-white border border-brand-light-border p-2 z-50 transition-all duration-200 opacity-0 translate-y-2 pointer-events-none"
                        role="menu"
                        aria-orientation="vertical"
                        tabindex="-1"
                    >
                        <div class="space-y-1 text-xs">
                            <a href="{{ route('about') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy transition-colors font-medium" role="menuitem">
                                <div class="w-6 h-6 rounded-lg bg-brand-gold/15 text-brand-burgundy flex items-center justify-center shrink-0">
                                    <i class="fas fa-heart text-xs text-brand-gold"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold">About Aditya Utsav</span>
                                    <span class="text-[10px] text-brand-muted-brown">12+ years of Bihar heritage</span>
                                </div>
                            </a>

                            <a href="{{ route('faq') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy transition-colors font-medium" role="menuitem">
                                <div class="w-6 h-6 rounded-lg bg-brand-gold/15 text-brand-burgundy flex items-center justify-center shrink-0">
                                    <i class="fas fa-question-circle text-xs text-brand-gold"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold">FAQs &amp; Help</span>
                                    <span class="text-[10px] text-brand-muted-brown">Pricing, booking &amp; themes</span>
                                </div>
                            </a>

                            <a href="{{ route('service-areas.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy transition-colors font-medium" role="menuitem">
                                <div class="w-6 h-6 rounded-lg bg-brand-gold/15 text-brand-burgundy flex items-center justify-center shrink-0">
                                    <i class="fas fa-map-marker-alt text-xs text-brand-gold"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold">Service Coverage</span>
                                    <span class="text-[10px] text-brand-muted-brown">Siwan, Patna &amp; Eastern UP</span>
                                </div>
                            </a>

                            <a href="{{ route('booking-guide') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy transition-colors font-medium" role="menuitem">
                                <div class="w-6 h-6 rounded-lg bg-brand-gold/15 text-brand-burgundy flex items-center justify-center shrink-0">
                                    <i class="fas fa-book-open text-xs text-brand-gold"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold">Booking Guide</span>
                                    <span class="text-[10px] text-brand-muted-brown">Step-by-step vivah planning</span>
                                </div>
                            </a>

                            <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy transition-colors font-medium" role="menuitem">
                                <div class="w-6 h-6 rounded-lg bg-brand-gold/15 text-brand-burgundy flex items-center justify-center shrink-0">
                                    <i class="fas fa-phone-alt text-xs text-brand-gold"></i>
                                </div>
                                <div>
                                    <span class="block font-semibold">Contact Siwan Office</span>
                                    <span class="text-[10px] text-brand-muted-brown">Direct decorator line</span>
                                </div>
                            </a>

                            <div class="pt-2 border-t border-brand-light-border/60 px-2 space-y-0.5">
                                <a href="{{ route('terms') }}" class="block px-2 py-1 text-[11px] text-brand-muted-brown hover:text-brand-burgundy rounded">
                                    Terms &amp; Conditions
                                </a>
                                <a href="{{ route('cancellation-policy') }}" class="block px-2 py-1 text-[11px] text-brand-muted-brown hover:text-brand-burgundy rounded">
                                    Cancellation &amp; Refund Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- 3. Right: User Actions, Search & CTAs (Desktop) -->
            <div class="hidden lg:flex items-center space-x-2.5 xl:space-x-3 shrink-0">
                <!-- Search Button -->
                <button 
                    type="button" 
                    onclick="document.getElementById('header-search-bar').classList.toggle('hidden'); document.getElementById('header-search-input')?.focus();" 
                    class="w-9 h-9 rounded-full hover:bg-brand-burgundy/10 text-brand-charcoal/80 hover:text-brand-burgundy flex items-center justify-center transition-colors" 
                    aria-label="Search wedding decorations"
                >
                    <i class="fas fa-search text-sm"></i>
                </button>

                <!-- Wishlist Trigger with Badge -->
                <button 
                    type="button" 
                    onclick="toggleWishlistDrawer(true)" 
                    class="relative w-9 h-9 rounded-full hover:bg-brand-burgundy/10 text-brand-charcoal/80 hover:text-brand-burgundy flex items-center justify-center transition-colors" 
                    aria-label="View Shortlisted Decorations"
                >
                    <i class="far fa-heart text-sm"></i>
                    <span class="wishlist-count-badge hidden absolute 0 -top-0.5 -right-0.5 bg-brand-burgundy text-white text-[9px] font-bold w-4 h-4 rounded-full items-center justify-center border border-white">
                        0
                    </span>
                </button>

                <!-- Auth / Guest Actions -->
                @guest
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-charcoal hover:text-brand-burgundy px-2.5 py-1.5 rounded-lg hover:bg-brand-offwhite transition-colors whitespace-nowrap">
                        Sign In
                    </a>
                @else
                    <div class="relative inline-block text-left" id="user-header-dropdown-container">
                        <button 
                            type="button" 
                            onclick="document.getElementById('user-header-menu').classList.toggle('hidden')" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-brand-burgundy bg-brand-cream hover:bg-brand-gold/15 rounded-full transition-all border border-brand-gold/50 shadow-sm focus:outline-none whitespace-nowrap"
                        >
                            <span class="w-5 h-5 rounded-full bg-brand-burgundy text-brand-gold text-[10px] flex items-center justify-center font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="max-w-[85px] xl:max-w-[110px] truncate">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-[9px] text-brand-burgundy/80"></i>
                        </button>

                        <div id="user-header-menu" class="hidden absolute right-0 mt-2 w-52 rounded-2xl shadow-2xl bg-white border border-brand-light-border divide-y divide-brand-light-border/60 z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-brand-offwhite/50">
                                <p class="text-[10px] text-brand-muted-brown uppercase tracking-wider font-bold">Signed in as</p>
                                <p class="text-xs font-bold text-brand-charcoal truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-1 text-xs">
                                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy font-medium">
                                    <i class="fas fa-th-large w-4 text-brand-gold"></i> Dashboard
                                </a>
                                <a href="{{ route('account.bookings') }}" class="flex items-center gap-2.5 px-4 py-2 text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy font-medium">
                                    <i class="fas fa-calendar-alt w-4 text-brand-gold"></i> My Bookings
                                </a>
                                <a href="{{ route('account.quotations.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy font-medium">
                                    <i class="fas fa-file-invoice w-4 text-brand-gold"></i> Quotations
                                </a>
                                <a href="{{ route('account.invoices.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy font-medium">
                                    <i class="fas fa-receipt w-4 text-brand-gold"></i> Invoices
                                </a>
                                <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-brand-charcoal hover:bg-brand-burgundy/10 hover:text-brand-burgundy font-medium">
                                    <i class="fas fa-user-cog w-4 text-brand-gold"></i> Profile
                                </a>
                            </div>
                            <div class="py-1 bg-red-50/50">
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2.5 w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-100 font-semibold transition-colors">
                                        <i class="fas fa-sign-out-alt w-4"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest

                <!-- Secondary CTA: Quote Inquiry -->
                <a 
                    href="{{ route('quote') }}" 
                    class="inline-flex items-center justify-center px-3.5 py-2 text-xs font-bold uppercase tracking-wider text-brand-burgundy bg-brand-gold/20 hover:bg-brand-gold/30 rounded-lg border border-brand-gold/60 transition-colors whitespace-nowrap"
                >
                    Get Quote
                </a>

                <!-- Primary CTA: Check Availability / Book Now -->
                <button 
                    type="button" 
                    onclick="openAvailabilityModal()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold uppercase tracking-wider text-brand-cream bg-gradient-to-r from-brand-deep-burgundy via-brand-burgundy to-brand-deep-burgundy hover:from-brand-burgundy hover:to-brand-deep-burgundy rounded-lg border border-brand-gold shadow-sm hover:shadow-gold-glow transition-all duration-200 whitespace-nowrap"
                >
                    <i class="fas fa-calendar-check text-brand-gold"></i>
                    <span>Book Now</span>
                </button>
            </div>

            <!-- 4. Mobile Right Controls (Mobile Only) -->
            <div class="flex items-center space-x-1 sm:space-x-2 lg:hidden shrink-0">
                <!-- Mobile Search Trigger -->
                <button 
                    type="button" 
                    onclick="document.getElementById('header-search-bar').classList.toggle('hidden'); document.getElementById('header-search-input')?.focus();" 
                    class="w-9 h-9 rounded-full hover:bg-brand-burgundy/10 text-brand-charcoal hover:text-brand-burgundy flex items-center justify-center" 
                    aria-label="Search"
                >
                    <i class="fas fa-search text-base"></i>
                </button>

                <!-- Mobile Wishlist Trigger -->
                <button 
                    type="button" 
                    onclick="toggleWishlistDrawer(true)" 
                    class="relative w-9 h-9 rounded-full hover:bg-brand-burgundy/10 text-brand-charcoal hover:text-brand-burgundy flex items-center justify-center" 
                    aria-label="Wishlist"
                >
                    <i class="far fa-heart text-base"></i>
                    <span class="wishlist-count-badge hidden absolute -top-0.5 -right-0.5 bg-brand-burgundy text-white text-[9px] font-bold w-4 h-4 rounded-full items-center justify-center border border-white">
                        0
                    </span>
                </button>

                <!-- Mobile Hamburger Button -->
                <button 
                    id="mobile-menu-btn" 
                    type="button" 
                    class="w-9 h-9 rounded-xl text-brand-charcoal hover:text-brand-burgundy bg-brand-offwhite border border-brand-light-border hover:bg-brand-cream flex items-center justify-center focus:outline-none transition-colors ml-1" 
                    aria-label="Toggle navigation menu" 
                    aria-expanded="false"
                >
                    <i class="fas fa-bars text-base" id="mobile-menu-icon"></i>
                </button>
            </div>

        </div>

        <!-- Collapsible Search Bar (Smooth Dropdown) -->
        <div id="header-search-bar" class="hidden py-3 border-t border-brand-light-border/80">
            <form action="{{ route('decorations.index') }}" method="GET" class="relative max-w-xl mx-auto">
                <input 
                    type="text" 
                    name="search" 
                    id="header-search-input" 
                    placeholder="Search Jaimala stages, Mandap, Haldi, Mehendi..." 
                    class="w-full pl-10 pr-24 py-2 text-xs sm:text-sm bg-brand-offwhite border border-brand-light-border rounded-full focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy"
                />
                <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs sm:text-sm"></i>
                <button type="submit" class="absolute right-1 top-1 bottom-1 px-3.5 sm:px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-full transition-colors">
                    Search
                </button>
            </form>
        </div>
    </div>
</header>

<!-- ======================================================= -->
<!-- MOBILE NAVIGATION OFF-CANVAS DRAWER (Smooth Slide-In) -->
<!-- ======================================================= -->
<div id="mobile-menu-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden transition-opacity duration-300 opacity-0 lg:hidden" onclick="closeMobileDrawer()">
    <div 
        id="mobile-menu-drawer" 
        class="fixed inset-y-0 right-0 w-full max-w-[310px] bg-white shadow-2xl z-50 flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out pointer-events-none invisible"
        onclick="event.stopPropagation()"
    >
        <!-- Mobile Drawer Header -->
        <div class="p-4 border-b border-brand-light-border flex items-center justify-between bg-brand-cream/80">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-deep-burgundy to-brand-burgundy border border-brand-gold/60 flex items-center justify-center text-brand-gold font-bold text-sm shadow-sm">
                    <i class="fas fa-om text-xs"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-serif text-base font-bold text-brand-burgundy leading-none">
                        ADITYA <span class="text-brand-gold font-normal">UTSAV</span>
                    </span>
                    <span class="text-[9px] text-brand-muted-brown uppercase tracking-wider font-semibold mt-0.5">
                        Bihar Wedding Decor
                    </span>
                </div>
            </div>
            <button type="button" onclick="closeMobileDrawer()" class="w-8 h-8 rounded-lg text-brand-muted-brown hover:text-brand-burgundy hover:bg-white flex items-center justify-center" aria-label="Close navigation menu">
                <i class="fas fa-times text-base"></i>
            </button>
        </div>

        <!-- Mobile Drawer Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            
            <!-- Quick Action Shortcuts -->
            <div class="grid grid-cols-2 gap-2">
                <a href="https://wa.me/919876543210?text=Hello%20Aditya%20Utsav,%20I%20want%20to%20inquire%20about%20wedding%20decoration%20services" target="_blank" class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition">
                    <i class="fab fa-whatsapp text-sm text-emerald-600"></i> WhatsApp
                </a>
                <a href="tel:{{ $settings['contact_phone'] ?? '+919876543210' }}" class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg bg-brand-burgundy/10 text-brand-burgundy border border-brand-burgundy/20 text-xs font-bold hover:bg-brand-burgundy/20 transition">
                    <i class="fas fa-phone-alt text-xs text-brand-gold"></i> Call Direct
                </a>
            </div>

            <!-- Primary Navigation Links -->
            <div class="space-y-1">
                <span class="text-[10px] font-bold text-brand-muted-brown uppercase tracking-wider px-2 block mb-1">
                    Explore Services
                </span>
                <a href="{{ route('home') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-brand-burgundy/10 text-brand-burgundy font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite' }}">
                    <i class="fas fa-home w-4 text-brand-gold"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('decorations.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('decorations.*') ? 'bg-brand-burgundy/10 text-brand-burgundy font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite' }}">
                    <i class="fas fa-th-large w-4 text-brand-gold"></i>
                    <span>Ceremony Decorations</span>
                </a>
                <a href="{{ route('packages.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('packages.*') ? 'bg-brand-burgundy/10 text-brand-burgundy font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite' }}">
                    <i class="fas fa-box-open w-4 text-brand-gold"></i>
                    <span>Wedding Packages</span>
                </a>
                <a href="{{ route('videos.index') }}" onclick="closeMobileDrawer()" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold bg-brand-gold/10 text-brand-burgundy hover:bg-brand-gold/20 border border-brand-gold/30">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-play-circle w-4 text-brand-gold"></i>
                        <span>Reels &amp; Short Videos</span>
                    </span>
                    <span class="text-[9px] bg-brand-burgundy text-white px-2 py-0.5 rounded-full font-bold uppercase">New</span>
                </a>
                <a href="{{ route('gallery.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('gallery.*') ? 'bg-brand-burgundy/10 text-brand-burgundy font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite' }}">
                    <i class="fas fa-images w-4 text-brand-gold"></i>
                    <span>Real Wedding Gallery</span>
                </a>
                <a href="{{ route('offers.index') }}" onclick="closeMobileDrawer()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('offers.*') ? 'bg-brand-burgundy/10 text-brand-burgundy font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite' }}">
                    <i class="fas fa-tag w-4 text-brand-gold"></i>
                    <span>Offers &amp; Coupons</span>
                </a>
            </div>

            <!-- Information & Support Links -->
            <div class="pt-2 border-t border-brand-light-border space-y-1">
                <span class="text-[10px] font-bold text-brand-muted-brown uppercase tracking-wider px-2 block mb-1">
                    Information &amp; Guidance
                </span>
                <a href="{{ route('about') }}" onclick="closeMobileDrawer()" class="block px-3 py-1.5 text-xs text-brand-charcoal hover:text-brand-burgundy">
                    <i class="fas fa-heart text-brand-gold mr-2 text-[10px]"></i> About Aditya Utsav
                </a>
                <a href="{{ route('faq') }}" onclick="closeMobileDrawer()" class="block px-3 py-1.5 text-xs text-brand-charcoal hover:text-brand-burgundy">
                    <i class="fas fa-question-circle text-brand-gold mr-2 text-[10px]"></i> Frequently Asked Questions
                </a>
                <a href="{{ route('service-areas.index') }}" onclick="closeMobileDrawer()" class="block px-3 py-1.5 text-xs text-brand-charcoal hover:text-brand-burgundy">
                    <i class="fas fa-map-marker-alt text-brand-gold mr-2 text-[10px]"></i> Service Areas (Bihar &amp; UP)
                </a>
                <a href="{{ route('booking-guide') }}" onclick="closeMobileDrawer()" class="block px-3 py-1.5 text-xs text-brand-charcoal hover:text-brand-burgundy">
                    <i class="fas fa-book-open text-brand-gold mr-2 text-[10px]"></i> Wedding Booking Guide
                </a>
                <a href="{{ route('contact') }}" onclick="closeMobileDrawer()" class="block px-3 py-1.5 text-xs text-brand-charcoal hover:text-brand-burgundy">
                    <i class="fas fa-envelope text-brand-gold mr-2 text-[10px]"></i> Contact Siwan Office
                </a>
                <a href="{{ route('terms') }}" onclick="closeMobileDrawer()" class="block px-3 py-1 text-[11px] text-brand-muted-brown hover:text-brand-burgundy">
                    Terms &amp; Conditions
                </a>
                <a href="{{ route('cancellation-policy') }}" onclick="closeMobileDrawer()" class="block px-3 py-1 text-[11px] text-brand-muted-brown hover:text-brand-burgundy">
                    Cancellation &amp; Refund Policy
                </a>
            </div>

            <!-- Mobile Auth Section -->
            <div class="pt-3 border-t border-brand-light-border">
                @guest
                    <div class="flex items-center justify-between px-3 py-2 bg-brand-offwhite rounded-xl mb-3">
                        <a href="{{ route('login') }}" onclick="closeMobileDrawer()" class="text-xs font-bold text-brand-burgundy py-1">
                            <i class="far fa-user mr-1"></i> Customer Sign In
                        </a>
                        <a href="{{ route('register') }}" onclick="closeMobileDrawer()" class="text-xs font-semibold text-brand-charcoal hover:text-brand-burgundy py-1">
                            Register
                        </a>
                    </div>
                @else
                    <div class="bg-brand-offwhite p-3 rounded-xl mb-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-brand-charcoal truncate max-w-[170px]">{{ Auth::user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 font-bold hover:underline">Sign Out</button>
                            </form>
                        </div>
                        <div class="grid grid-cols-2 gap-1.5 text-xs">
                            <a href="{{ route('account.dashboard') }}" onclick="closeMobileDrawer()" class="text-brand-burgundy font-semibold hover:underline">Dashboard</a>
                            <a href="{{ route('account.bookings') }}" onclick="closeMobileDrawer()" class="text-brand-burgundy font-semibold hover:underline">My Bookings</a>
                            <a href="{{ route('account.quotations.index') }}" onclick="closeMobileDrawer()" class="text-brand-burgundy font-semibold hover:underline">Quotations</a>
                            <a href="{{ route('account.invoices.index') }}" onclick="closeMobileDrawer()" class="text-brand-burgundy font-semibold hover:underline">Invoices</a>
                        </div>
                    </div>
                @endguest
            </div>
        </div>

        <!-- Mobile Drawer Sticky Action Buttons -->
        <div class="p-4 border-t border-brand-light-border bg-white grid grid-cols-2 gap-2">
            <a href="{{ route('quote') }}" onclick="closeMobileDrawer()" class="w-full text-center py-2.5 px-2 text-xs font-bold uppercase tracking-wider text-brand-burgundy bg-brand-gold/20 hover:bg-brand-gold/30 rounded-lg border border-brand-gold">
                Get Quote
            </a>
            <button type="button" onclick="closeMobileDrawer(); openAvailabilityModal()" class="w-full py-2.5 px-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow">
                Book Now
            </button>
        </div>
    </div>
</div>

<!-- Pure Vanilla JavaScript for Dropdown & Mobile Drawer Transitions -->
<script>
function openMobileDrawer() {
    const backdrop = document.getElementById('mobile-menu-backdrop');
    const drawer = document.getElementById('mobile-menu-drawer');
    const menuBtn = document.getElementById('mobile-menu-btn');
    if (!backdrop || !drawer) return;

    backdrop.classList.remove('hidden');
    drawer.classList.remove('invisible', 'pointer-events-none');
    void backdrop.offsetWidth; // Force reflow
    backdrop.classList.remove('opacity-0');
    drawer.classList.remove('translate-x-full');
    document.body.classList.add('overflow-hidden');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
}

function closeMobileDrawer() {
    const backdrop = document.getElementById('mobile-menu-backdrop');
    const drawer = document.getElementById('mobile-menu-drawer');
    const menuBtn = document.getElementById('mobile-menu-btn');
    if (!backdrop || !drawer) return;

    backdrop.classList.add('opacity-0');
    drawer.classList.add('translate-x-full');
    document.body.classList.remove('overflow-hidden');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');

    setTimeout(() => {
        backdrop.classList.add('hidden');
        drawer.classList.add('invisible', 'pointer-events-none');
    }, 300);
}

document.addEventListener('DOMContentLoaded', function () {
    // Desktop "More ▾" Dropdown
    const moreBtn = document.getElementById('nav-more-btn');
    const moreMenu = document.getElementById('nav-more-menu');
    const moreIcon = document.getElementById('nav-more-icon');
    const moreContainer = document.getElementById('nav-more-dropdown-container');

    if (moreBtn && moreMenu) {
        function toggleMoreMenu(open) {
            const shouldOpen = open !== undefined ? open : moreMenu.classList.contains('pointer-events-none');
            if (shouldOpen) {
                moreMenu.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                moreMenu.classList.add('opacity-100', 'translate-y-0');
                moreBtn.setAttribute('aria-expanded', 'true');
                if (moreIcon) moreIcon.classList.add('rotate-180');
            } else {
                moreMenu.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                moreMenu.classList.remove('opacity-100', 'translate-y-0');
                moreBtn.setAttribute('aria-expanded', 'false');
                if (moreIcon) moreIcon.classList.remove('rotate-180');
            }
        }

        moreBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleMoreMenu();
        });

        // Click outside closes More menu
        document.addEventListener('click', function (e) {
            if (moreContainer && !moreContainer.contains(e.target)) {
                toggleMoreMenu(false);
            }
        });

        // Escape key closes menus
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                toggleMoreMenu(false);
                closeMobileDrawer();
            }
        });
    }

    // Mobile Hamburger Button
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            openMobileDrawer();
        });
    }

    // User header menu click-outside close
    const userContainer = document.getElementById('user-header-dropdown-container');
    const userMenu = document.getElementById('user-header-menu');
    if (userContainer && userMenu) {
        document.addEventListener('click', function (e) {
            if (!userContainer.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
});
</script>
