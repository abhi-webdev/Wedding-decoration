<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Aditya Utsav Management</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        maroon: {
                            800: '#800020',
                            900: '#5c0017',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Cinzel', serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
@php
    $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
    $pendingCancelsCount = \App\Models\CancellationRequest::where('status', 'pending')->count();
    $pendingReschedulesCount = \App\Models\RescheduleRequest::where('status', 'pending')->count();
    $pendingQuotesCount = \App\Models\QuoteRequest::where('status', 'pending')->count();
    $newMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();
    $sentQuotationsCount = \App\Models\Quotation::where('status', 'sent')->count();
    $currentUser = auth()->user();
@endphp

<div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex lg:flex-col w-64 bg-slate-900 text-slate-300 min-h-screen border-r border-slate-800 shadow-xl shrink-0">
        <!-- Brand Header -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between bg-slate-950">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-700 flex items-center justify-center text-white font-bold shadow-md text-lg">
                    AU
                </div>
                <div>
                    <span class="block font-heading text-amber-400 font-bold tracking-wide text-sm leading-tight">ADITYA UTSAV</span>
                    <span class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Role Badge -->
        <div class="px-5 py-3 bg-slate-800/60 border-b border-slate-800/80 flex items-center justify-between text-xs">
            <span class="text-slate-400">Signed in as:</span>
            <span class="px-2 py-0.5 rounded text-[11px] font-semibold 
                {{ $currentUser->role === 'super_admin' ? 'bg-purple-900/60 text-purple-300 border border-purple-700/50' : 
                   ($currentUser->role === 'booking_manager' ? 'bg-blue-900/60 text-blue-300 border border-blue-700/50' : 
                   ($currentUser->role === 'content_manager' ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-700/50' : 'bg-amber-900/60 text-amber-300 border border-amber-700/50')) }}">
                {{ ucwords(str_replace('_', ' ', $currentUser->role)) }}
            </span>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto text-sm">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950 font-bold shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- SECTION: BUSINESS OPERATIONS -->
            <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3">Business Operations</div>
            
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Bookings</span>
                </div>
                @if($pendingBookingsCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-slate-950">{{ $pendingBookingsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.calendar.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.calendar.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Event Calendar</span>
            </a>

            <a href="{{ route('admin.quotations.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.quotations.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Quotations</span>
                </div>
                @if($sentQuotationsCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950">{{ $sentQuotationsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Payments & Receipts</span>
            </a>

            <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.invoices.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Tax Invoices</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Business Reports</span>
            </a>

            <a href="{{ route('admin.cancellation-requests.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.cancellation-requests.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs">Cancellation Requests</span>
                </div>
                @if($pendingCancelsCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-600 text-white">{{ $pendingCancelsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.reschedule-requests.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.reschedule-requests.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="text-xs">Reschedule Requests</span>
                </div>
                @if($pendingReschedulesCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-600 text-white">{{ $pendingReschedulesCount }}</span>
                @endif
            </a>

            <!-- SECTION: CATALOG & SERVICES -->
            <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3">Catalog & Services</div>

            <a href="{{ route('admin.decorations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.decorations.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Decorations</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.addons.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.addons.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Add-ons</span>
            </a>

            <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Wedding Packages</span>
            </a>

            <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.videos.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <span>Wedding Reels & Videos</span>
            </a>

            <a href="{{ route('admin.offers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.offers.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Offers & Coupons</span>
            </a>

            <!-- SECTION: INQUIRIES & CONTENT -->
            <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3">Inquiries & Content</div>

            <a href="{{ route('admin.quotes.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.quotes.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Quote Inquiries</span>
                </div>
                @if($pendingQuotesCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950">{{ $pendingQuotesCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Contact Messages</span>
                </div>
                @if($newMessagesCount > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-slate-950">{{ $newMessagesCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.gallery.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Gallery Portfolio</span>
            </a>

            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.media.*') ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-500' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <span>Media Library</span>
            </a>

            <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.faqs.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>FAQs</span>
            </a>

            <a href="{{ route('admin.service-areas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.service-areas.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <span>Service Areas (Bihar/UP)</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Registered Customers</span>
            </a>

            <!-- SECTION: ADMINISTRATION -->
            @if($currentUser->isSuperAdmin())
                <div class="pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500 px-3">System Administration</div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Staff Users</span>
                </a>

                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.activity-logs.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Audit Logs</span>
                </a>
            @endif

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-slate-800 text-amber-400 font-semibold' : 'hover:bg-slate-800/70 text-slate-300 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Site Settings</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center font-bold text-amber-400 text-xs shrink-0">
                    {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-slate-200 truncate">{{ $currentUser->name }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ $currentUser->email }}</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-red-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Top Header -->
    <div class="lg:hidden bg-slate-900 text-white p-4 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center font-bold text-slate-950">AU</div>
            <span class="font-heading text-amber-400 font-bold text-sm">ADITYA UTSAV ADMIN</span>
        </div>
        <button id="mobileMenuBtn" class="p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobileDrawer" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden lg:hidden">
        <div class="w-72 bg-slate-900 h-full p-4 flex flex-col text-slate-300">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <span class="font-heading text-amber-400 font-bold">Admin Navigation</span>
                <button id="closeDrawerBtn" class="p-1 text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto py-4 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-800 text-white">Dashboard</a>
                <a href="{{ route('admin.bookings.index') }}" class="flex justify-between items-center px-3 py-2 rounded hover:bg-slate-800">
                    <span>Bookings</span>
                    @if($pendingBookingsCount > 0)
                        <span class="px-2 py-0.5 rounded text-xs bg-amber-500 text-slate-950 font-bold">{{ $pendingBookingsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.calendar.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Event Calendar</a>
                <a href="{{ route('admin.quotations.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Quotations</a>
                <a href="{{ route('admin.payments.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Payments & Receipts</a>
                <a href="{{ route('admin.invoices.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Tax Invoices</a>
                <a href="{{ route('admin.decorations.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Decorations</a>
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Categories</a>
                <a href="{{ route('admin.packages.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Packages</a>
                <a href="{{ route('admin.videos.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 text-rose-300">Reels & Videos</a>
                <a href="{{ route('admin.gallery.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Gallery</a>
                <a href="{{ route('admin.media.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800 text-amber-400">Media Library</a>
                <a href="{{ route('admin.offers.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Offers</a>
                <a href="{{ route('admin.quotes.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Quotes</a>
                <a href="{{ route('admin.messages.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Messages</a>
                <a href="{{ route('admin.settings.index') }}" class="block px-3 py-2 rounded hover:bg-slate-800">Settings</a>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="pt-4 border-t border-slate-800">
                @csrf
                <button type="submit" class="w-full py-2 bg-red-600/20 text-red-400 hover:bg-red-600/30 rounded text-center font-medium text-sm">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('header', 'Dashboard')</h1>
                <div class="hidden sm:block">
                    <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition">
                        <span>View Live Website</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-slate-500 block">Current Time (IST)</span>
                    <span class="text-xs font-semibold text-slate-700">{{ now()->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}</span>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-1 overflow-y-auto p-6 bg-slate-50/70">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-sm font-bold">&times;</button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <p class="text-sm font-bold text-red-800 mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Vanilla JS Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const closeDrawerBtn = document.getElementById('closeDrawerBtn');

        if (mobileMenuBtn && mobileDrawer) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileDrawer.classList.remove('hidden');
            });
        }
        if (closeDrawerBtn && mobileDrawer) {
            closeDrawerBtn.addEventListener('click', () => {
                mobileDrawer.classList.add('hidden');
            });
        }
    });
</script>
@stack('scripts')
</body>
</html>
