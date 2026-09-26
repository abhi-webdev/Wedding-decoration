@extends('layouts.app')

@section('content')

<!-- Account Layout Container -->
<div class="bg-brand-cream/60 min-h-[calc(100vh-80px)] py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Flash Message Alerts -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-rose-600 text-base"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs sm:text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Sidebar Navigation (Desktop 3.5 cols) -->
            <aside class="lg:col-span-4 xl:col-span-3 space-y-6">
                
                <!-- Customer Profile Card in Sidebar -->
                <div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury text-center space-y-3">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-brand-burgundy to-brand-royal-rose text-brand-gold text-2xl font-bold flex items-center justify-center mx-auto shadow-md border-2 border-brand-gold/40">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-serif text-lg font-bold text-brand-charcoal truncate">
                            {{ auth()->user()->name }}
                        </h3>
                        <p class="text-xs text-brand-muted-brown truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                    <div class="pt-2 border-t border-brand-light-border/70 flex items-center justify-center gap-2 text-[11px] text-brand-muted-brown font-medium">
                        <i class="fas fa-phone-alt text-brand-gold text-[10px]"></i>
                        <span>{{ auth()->user()->phone ?? 'Phone not set' }}</span>
                    </div>
                </div>

                <!-- Account Navigation Menu -->
                <nav class="bg-white rounded-2xl p-3 border border-brand-light-border shadow-soft-luxury space-y-1 text-xs font-semibold" aria-label="Customer Account Navigation">
                    
                    <!-- Dashboard -->
                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.dashboard') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <i class="fas fa-th-large w-4 text-center {{ request()->routeIs('account.dashboard') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                        <span>Account Dashboard</span>
                    </a>

                    <!-- My Bookings -->
                    <a href="{{ route('account.bookings') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.bookings*') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-calendar-check w-4 text-center {{ request()->routeIs('account.bookings*') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                            <span>My Bookings</span>
                        </div>
                        @php
                            $userBookingCount = auth()->user()->bookings()->count();
                        @endphp
                        @if($userBookingCount > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ request()->routeIs('account.bookings*') ? 'bg-white text-brand-burgundy' : 'bg-brand-offwhite text-brand-charcoal border border-brand-light-border' }}">
                                {{ $userBookingCount }}
                            </span>
                        @endif
                    </a>

                    <!-- My Quotations (Phase 7) -->
                    <a href="{{ route('account.quotations.index') }}" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.quotations*') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-invoice-dollar w-4 text-center {{ request()->routeIs('account.quotations*') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                            <span>My Quotations</span>
                        </div>
                        @php
                            $pendingQuotesCount = auth()->user()->quotations()->whereIn('status', ['sent', 'viewed'])->count();
                        @endphp
                        @if($pendingQuotesCount > 0)
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500 text-slate-950 font-bold">
                                {{ $pendingQuotesCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Payments & Statement (Phase 7) -->
                    <a href="{{ route('account.payments.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.payments*') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <i class="fas fa-receipt w-4 text-center {{ request()->routeIs('account.payments*') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                        <span>Payments & Bank Info</span>
                    </a>

                    <!-- Invoices (Phase 7) -->
                    <a href="{{ route('account.invoices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.invoices*') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <i class="fas fa-file-invoice w-4 text-center {{ request()->routeIs('account.invoices*') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                        <span>Billing Invoices</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('account.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.profile') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <i class="fas fa-user w-4 text-center {{ request()->routeIs('account.profile') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                        <span>Personal Profile</span>
                    </a>

                    <!-- Password -->
                    <a href="{{ route('account.password') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('account.password') ? 'bg-brand-burgundy text-brand-cream shadow-sm font-bold' : 'text-brand-charcoal hover:bg-brand-offwhite hover:text-brand-burgundy' }}">
                        <i class="fas fa-key w-4 text-center {{ request()->routeIs('account.password') ? 'text-brand-gold' : 'text-brand-muted-brown' }}"></i>
                        <span>Change Password</span>
                    </a>

                    <!-- Logout Button (Strictly POST) -->
                    <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-brand-light-border/70 mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-700 hover:bg-rose-50 hover:text-rose-900 transition-colors">
                            <i class="fas fa-sign-out-alt w-4 text-center text-rose-500"></i>
                            <span>Log Out of Account</span>
                        </button>
                    </form>

                </nav>

                <!-- Quick Help Support Card -->
                <div class="p-5 rounded-2xl bg-gradient-to-br from-brand-offwhite to-brand-cream border border-brand-gold/30 text-xs space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-royal-rose block">Aditya Utsav Concierge</span>
                    <h4 class="font-serif font-bold text-brand-charcoal">Need Direct Assistance?</h4>
                    <p class="text-[11px] text-brand-muted-brown leading-relaxed">
                        Call our Siwan office directly for urgent date inquiries or customized pandal setups.
                    </p>
                    <a href="tel:+919931200000" class="inline-flex items-center gap-1.5 font-bold text-brand-burgundy hover:underline pt-1">
                        <i class="fas fa-phone-alt text-brand-gold text-[10px]"></i>
                        <span>+91 99312 00000</span>
                    </a>
                </div>

            </aside>

            <!-- Right Main Account Content (8.5 cols) -->
            <main class="lg:col-span-8 xl:col-span-9">
                @yield('account_content')
            </main>

        </div>

    </div>
</div>

@endsection
