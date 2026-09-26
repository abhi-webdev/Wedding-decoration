@extends('layouts.account')

@section('title', 'Personal Profile | Aditya Utsav Bihar')

@section('account_content')

<div class="bg-white rounded-2xl p-6 sm:p-8 border border-brand-light-border shadow-soft-luxury space-y-6">
    
    <div class="border-b border-brand-light-border/70 pb-4">
        <h1 class="font-serif text-xl sm:text-2xl font-bold text-brand-charcoal">
            Personal Profile &amp; Contact Information
        </h1>
        <p class="text-xs text-brand-muted-brown mt-0.5">
            Keep your contact details up-to-date for smooth booking verification and event coordination.
        </p>
    </div>

    <form action="{{ route('account.profile.update') }}" method="POST" class="space-y-5">
        @csrf
        @method('PATCH')

        <!-- Full Name -->
        <div class="space-y-1.5">
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                Full Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name', $user->name) }}" 
                required 
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
            />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                Email Address <span class="text-red-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email', $user->email) }}" 
                required 
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
            />
        </div>

        <!-- Phone Numbers -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    Mobile Phone <span class="text-red-500">*</span>
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}" 
                    required 
                    placeholder="e.g. 9876543210"
                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                />
            </div>

            <div class="space-y-1.5">
                <label for="whatsapp" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    WhatsApp Number (Optional)
                </label>
                <input 
                    type="tel" 
                    id="whatsapp" 
                    name="whatsapp" 
                    value="{{ old('whatsapp', $user->whatsapp) }}" 
                    placeholder="e.g. 9876543210"
                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                />
            </div>
        </div>

        <!-- Location Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label for="city" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    City / Town
                </label>
                <input 
                    type="text" 
                    id="city" 
                    name="city" 
                    value="{{ old('city', $user->city) }}" 
                    placeholder="e.g. Siwan, Mairwa, Gopalganj, Chapra"
                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                />
            </div>

            <div class="space-y-1.5">
                <label for="state" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                    State
                </label>
                <select 
                    id="state" 
                    name="state" 
                    class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-medium"
                >
                    <option value="Bihar" {{ old('state', $user->state) === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                    <option value="Uttar Pradesh" {{ old('state', $user->state) === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                    <option value="Other" {{ old('state', $user->state) === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Address -->
        <div class="space-y-1.5">
            <label for="address" class="block text-xs font-bold uppercase tracking-wider text-brand-charcoal">
                Default Residential / Venue Address
            </label>
            <textarea 
                id="address" 
                name="address" 
                rows="3" 
                placeholder="Enter street, locality, landmark..."
                class="w-full px-3.5 py-2.5 text-sm bg-brand-offwhite border border-brand-light-border rounded-xl text-brand-charcoal focus:outline-none focus:border-brand-burgundy font-normal leading-relaxed"
            >{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="pt-3 flex justify-end">
            <button 
                type="submit" 
                class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-xl border border-brand-gold shadow-md hover:shadow-gold-glow transition-all"
            >
                Save Profile Changes
            </button>
        </div>

    </form>

</div>

@endsection
