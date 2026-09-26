@extends('layouts.app')

@section('title', '419 - Session Expired | Aditya Utsav Bihar')
@section('meta_description', 'Your security session has expired. Please refresh the page and try again.')

@section('content')
<div class="min-h-[70vh] bg-[#FFF8F0] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#D4AF37]/30 relative overflow-hidden">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-amber-50 text-amber-700 mb-6 border border-amber-200 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
            Error 419 • Page Inactive
        </span>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#72002F] mb-4">
            Security Session Expired
        </h1>

        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
            Your form submission token has timed out for security reasons. Please refresh the page and re-submit your request.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload();" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#72002F] text-white font-medium text-sm hover:bg-[#800033] shadow-md transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Page
            </button>
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-[#72002F] border border-[#D4AF37] font-medium text-sm hover:bg-[#FFF8F0] transition-all duration-200">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
