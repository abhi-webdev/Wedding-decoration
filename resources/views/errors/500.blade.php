@extends('layouts.app')

@section('title', '500 - Server Error | Aditya Utsav Bihar')
@section('meta_description', 'An unexpected error occurred. Our technical team has been notified.')

@section('content')
<div class="min-h-[70vh] bg-[#FFF8F0] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#D4AF37]/30 relative overflow-hidden">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-50 text-rose-700 mb-6 border border-rose-200 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
            Error 500 • Internal System Error
        </span>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#72002F] mb-4">
            Something Went Wrong
        </h1>

        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
            An unexpected server issue occurred while processing your request. Please rest assured that our team has logged the issue. You may return to the homepage or reach us directly on WhatsApp/Phone.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#72002F] text-white font-medium text-sm hover:bg-[#800033] shadow-md transition-all duration-200">
                Go to Homepage
            </a>
            <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-[#72002F] border border-[#D4AF37] font-medium text-sm hover:bg-[#FFF8F0] transition-all duration-200">
                Contact Management
            </a>
        </div>
    </div>
</div>
@endsection
