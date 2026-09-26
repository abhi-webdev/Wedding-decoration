@extends('layouts.app')

@section('title', 'Customer Login | Aditya Utsav')
@section('meta_description', 'Customer login portal for Aditya Utsav wedding decoration bookings and quotation tracking.')

@section('content')
<section class="py-16 bg-brand-cream min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full mx-4 bg-white rounded-2xl p-8 border border-brand-gold/40 shadow-card-hover space-y-6">
        <div class="text-center">
            <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <h2 class="font-serif text-2xl font-bold text-brand-charcoal">Customer Login</h2>
            <p class="text-xs text-brand-muted-brown mt-1">Access your wedding decoration inquiries and shortlist</p>
        </div>

        <form onsubmit="event.preventDefault(); showToast('Demo Portal: Customer accounts will be enabled in Phase 2.', 'info');" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">Mobile Number or Email</label>
                <input type="text" required placeholder="e.g. 9876543210" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">Password</label>
                <input type="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-1.5 text-brand-muted-brown">
                    <input type="checkbox" class="text-brand-burgundy rounded">
                    <span>Remember me</span>
                </label>
                <a href="#" onclick="showToast('Password recovery support available via Siwan office.', 'info')" class="text-brand-burgundy hover:underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow">
                Sign In
            </button>
        </form>

        <div class="text-center text-xs text-brand-muted-brown pt-2 border-t border-brand-light-border">
            <span>Don't have an account yet? </span>
            <a href="{{ route('register') }}" class="font-bold text-brand-burgundy hover:underline">Register here</a>
        </div>
    </div>
</section>
@endsection
