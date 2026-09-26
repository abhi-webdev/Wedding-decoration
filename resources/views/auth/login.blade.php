@extends('layouts.app')

@section('title', 'Customer Login | Aditya Utsav Bihar')
@section('meta_description', 'Log in to your Aditya Utsav account to view your decoration bookings, check schedule status, and manage celebration details across Bihar and UP.')

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
                    Customer Login
                </h1>
                <p class="text-xs text-brand-muted-brown">
                    Access your Aditya Utsav dashboard &amp; manage your wedding bookings.
                </p>
            </div>

            <!-- Flash & Error Alerts -->
            @if(session('info'))
                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-rose-900">
                        <i class="fas fa-exclamation-circle text-rose-600"></i>
                        Login Error:
                    </div>
                    <ul class="list-disc list-inside pl-2 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

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
                        autofocus
                        placeholder="e.g. yourname@example.com"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                            Password <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy focus:ring-1 focus:ring-brand-burgundy font-medium"
                    />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-brand-charcoal">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-brand-burgundy rounded border-brand-light-border focus:ring-brand-burgundy"
                            {{ old('remember') ? 'checked' : '' }}
                        />
                        <span>Remember my login</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 text-xs font-bold uppercase tracking-wider text-brand-cream bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all"
                    >
                        Sign In to Account
                    </button>
                </div>

            </form>

            <!-- Registration Link Footer -->
            <div class="pt-4 border-t border-brand-light-border/70 text-center text-xs text-brand-muted-brown">
                Don't have an account yet? 
                <a href="{{ route('register') }}" class="font-bold text-brand-burgundy hover:underline ml-1">
                    Register here
                </a>
            </div>

        </div>

    </div>
</section>

@endsection
