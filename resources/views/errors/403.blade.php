@extends('layouts.app')

@section('title', '403 - Access Denied | Aditya Utsav Bihar')
@section('meta_description', 'Access restricted. You do not have authorization to view this resource.')

@section('content')
<div class="min-h-[70vh] bg-[#FFF8F0] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#D4AF37]/30 relative overflow-hidden">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-rose-50 text-rose-700 mb-6 border border-rose-200 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
            Error 403 • Restricted Area
        </span>

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-[#72002F] mb-4">
            Access Restricted
        </h1>

        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-8">
            You do not possess the required permissions to view this administrative resource or private customer record. If you believe this is an error, please sign in with an authorized account.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-[#72002F] text-white font-medium text-sm hover:bg-[#800033] shadow-md transition-all duration-200">
                Go to Homepage
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-full bg-white text-[#72002F] border border-[#D4AF37] font-medium text-sm hover:bg-[#FFF8F0] transition-all duration-200">
                Sign In With Another Account
            </a>
        </div>
    </div>
</div>
@endsection
