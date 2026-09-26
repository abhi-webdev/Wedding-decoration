@extends('layouts.app')

@section('title', '429 - Too Many Requests | Aditya Utsav Bihar')
@section('meta_description', 'Too many requests received. Please wait a moment and try again.')

@section('content')
<div class="min-h-[70vh] bg-[#FFF8F0] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#D4AF37]/30 relative overflow-hidden">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-700 mb-6 border border-blue-200 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
            Error 429 • Slow Down
        </span>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#72002F] mb-4">
            Too Many Requests
        </h1>

        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
            You have sent multiple requests in a short period. Please wait a few moments before trying again to ensure smooth service for all clients.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#72002F] text-white font-medium text-sm hover:bg-[#800033] shadow-md transition-all duration-200">
                Go to Homepage
            </a>
            <a href="{{ route('contact') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-[#72002F] border border-[#D4AF37] font-medium text-sm hover:bg-[#FFF8F0] transition-all duration-200">
                Contact Customer Support
            </a>
        </div>
    </div>
</div>
@endsection
