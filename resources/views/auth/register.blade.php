@extends('layouts.app')

@section('title', 'Register Customer Account | Aditya Utsav Bihar')
@section('meta_description', 'Create your Aditya Utsav customer account to manage wedding decoration bookings, track quotation requests, and customize ceremonies across Bihar and UP.')

@section('content')

<section class="py-12 sm:py-16 bg-brand-cream relative min-h-[calc(100vh-80px)] flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6">
        
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
            
            <!-- Header & Brand Logo Motif -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-brand-burgundy/10 border border-brand-gold/40 flex items-center justify-center text-brand-burgundy mx-auto text-xl">
                    <i class="fas fa-om"></i>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                    Create Your Account
                </h1>
                <p class="text-xs text-brand-muted-brown">
                    Register to manage your wedding decoration requests with Aditya Utsav.
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-rose-900">
                        <i class="fas fa-exclamation-circle text-rose-600"></i>
                        Please check the form inputs:
                    </div>
                    <ul class="list-disc list-inside pl-2 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        placeholder="e.g. Ramesh Kumar Singh"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="e.g. ramesh@example.com"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Mobile Phone -->
                <div class="space-y-1">
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Mobile Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="e.g. 9876543210"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="At least 8 characters"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        placeholder="Re-enter your password"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all"
                    >
                        Create Account
                    </button>
                </div>

            </form>

            <!-- Login Link Footer -->
            <div class="pt-4 border-t border-brand-light-border/70 text-center text-xs text-brand-muted-brown">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-brand-burgundy hover:underline ml-1">
                    Log in here
                </a>
            </div>

        </div>

    </div>
</section>

@endsection
