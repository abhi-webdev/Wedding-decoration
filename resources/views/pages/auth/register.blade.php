@extends('layouts.app')

@section('title', 'Register | Aditya Utsav')
@section('meta_description', 'Create an account on Aditya Utsav to save preferred wedding decoration themes and track customized quotes.')

@section('content')
<section class="py-16 bg-brand-cream min-h-[80vh] flex items-center justify-center">
    <div class="max-w-md w-full mx-4 bg-white rounded-2xl p-8 border border-brand-gold/40 shadow-card-hover space-y-6">
        <div class="text-center">
            <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <h2 class="font-serif text-2xl font-bold text-brand-charcoal">Create an Account</h2>
            <p class="text-xs text-brand-muted-brown mt-1">Shortlist decorations and plan your wedding in Bihar</p>
        </div>

        <form onsubmit="event.preventDefault(); showToast('Demo Portal: Registration is currently in preview for Phase 1.', 'info');" class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">Full Name</label>
                <input type="text" required placeholder="e.g. Ramesh Chandra" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">Mobile Number (WhatsApp preferred)</label>
                <input type="tel" required placeholder="e.g. 9876543210" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">City / District in Bihar</label>
                <input type="text" required placeholder="e.g. Siwan, Gopalganj, Chapra" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-brand-charcoal mb-1">Create Password</label>
                <input type="password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-lg focus:outline-none focus:border-brand-burgundy">
            </div>

            <button type="submit" class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow">
                Create Account
            </button>
        </form>

        <div class="text-center text-xs text-brand-muted-brown pt-2 border-t border-brand-light-border">
            <span>Already have an account? </span>
            <a href="{{ route('login') }}" class="font-bold text-brand-burgundy hover:underline">Sign In here</a>
        </div>
    </div>
</section>
@endsection
