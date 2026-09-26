@extends('layouts.account')

@section('title', 'Change Password | Aditya Utsav Bihar')

@section('account_content')

<div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6 max-w-xl">
    
    <div class="border-b border-brand-light-border/70 pb-4">
        <h1 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
            Change Account Password
        </h1>
        <p class="text-xs text-brand-muted-brown mt-0.5">
            Ensure your account is using a secure and unique password.
        </p>
    </div>

    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <div class="font-bold flex items-center gap-1.5 text-rose-900">
                <i class="fas fa-exclamation-circle text-rose-600"></i>
                Password Error:
            </div>
            <ul class="list-disc list-inside pl-2 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PATCH')

        <!-- Current Password -->
        <div class="space-y-1.5">
            <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                Current Password <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                id="current_password" 
                name="current_password" 
                required 
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
            />
        </div>

        <!-- New Password -->
        <div class="space-y-1.5">
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                New Password <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                placeholder="At least 8 characters"
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
            />
        </div>

        <!-- Confirm New Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                Confirm New Password <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                required 
                placeholder="Re-enter new password"
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
            />
        </div>

        <div class="pt-3">
            <button 
                type="submit" 
                class="w-full sm:w-auto px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all"
            >
                Update Password
            </button>
        </div>

    </form>

</div>

@endsection
