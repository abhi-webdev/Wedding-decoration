@extends('layouts.app')

@section('title', '404 - Page Not Found | Aditya Utsav Bihar')
@section('meta_description', 'The wedding decoration or page you are looking for could not be found. Explore our traditional and royal Bihar wedding decor packages at Aditya Utsav.')

@section('content')
<div class="min-h-[70vh] bg-[#FFF8F0] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#D4AF37]/30 relative overflow-hidden">
        <!-- Decorative Glow Top -->
        <div class="absolute -top-12 -right-12 w-36 h-36 bg-[#D4AF37]/15 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-[#72002F]/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#72002F]/10 text-[#72002F] mb-6 border border-[#D4AF37]/40 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-[#D4AF37]/15 text-[#72002F] text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
            Error 404 • Page Not Found
        </span>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#72002F] mb-4">
            Looking for a Ceremony Setup?
        </h1>

        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
            The page, decoration stage, or package you requested might have been updated, relocated, or is momentarily unavailable. Let us help you find the perfect setup for your special day.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#72002F] text-white font-medium text-sm hover:bg-[#800033] shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return to Homepage
            </a>
            <a href="{{ route('decorations.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-[#72002F] border border-[#D4AF37] font-medium text-sm hover:bg-[#FFF8F0] transition-all duration-200">
                Explore Decorations
            </a>
        </div>
    </div>
</div>
@endsection
