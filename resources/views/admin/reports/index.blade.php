@extends('layouts.admin')

@section('title', 'Business Operations & Financial Reports | Aditya Utsav Admin')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-brand-deep-burgundy to-brand-burgundy rounded-2xl p-6 text-white border border-brand-gold shadow-soft-luxury flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.2em] text-brand-gold">
                <i class="fas fa-chart-line"></i> EXECUTIVE REPORTING
            </span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white mt-1">
                Business & Financial Reports
            </h1>
            <p class="text-xs sm:text-sm text-brand-cream/80 mt-1">
                Real-time booking pipelines, advance collections, and city-wise performance metrics.
            </p>
        </div>

        <!-- CSV Export Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.reports.export.bookings', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold bg-white text-brand-burgundy hover:bg-brand-cream rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-csv text-brand-gold"></i> Export Bookings
            </a>
            <a href="{{ route('admin.reports.export.payments', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold bg-white text-brand-burgundy hover:bg-brand-cream rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-invoice-dollar text-emerald-600"></i> Export Payments
            </a>
            <a href="{{ route('admin.reports.export.quotations', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold bg-white text-brand-burgundy hover:bg-brand-cream rounded-xl shadow-sm transition-all">
                <i class="fas fa-file-alt text-amber-600"></i> Export Quotations
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-bold text-brand-muted-brown mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ $fromDate }}" class="w-full px-3 py-2 text-xs rounded-xl border border-brand-light-border focus:ring-1 focus:ring-brand-burgundy focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-muted-brown mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ $toDate }}" class="w-full px-3 py-2 text-xs rounded-xl border border-brand-light-border focus:ring-1 focus:ring-brand-burgundy focus:border-brand-burgundy">
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-muted-brown mb-1">City / Region</label>
                <select name="city" class="w-full px-3 py-2 text-xs rounded-xl border border-brand-light-border focus:ring-1 focus:ring-brand-burgundy">
                    <option value="">All Service Areas</option>
                    @foreach($serviceAreas as $sCity)
                        <option value="{{ $sCity }}" {{ $city === $sCity ? 'selected' : '' }}>{{ $sCity }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-brand-muted-brown mb-1">Booking Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs rounded-xl border border-brand-light-border focus:ring-1 focus:ring-brand-burgundy">
                    <option value="all">All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="quoted" {{ $status === 'quoted' ? 'selected' : '' }}>Quoted</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="advance_paid" {{ $status === 'advance_paid' ? 'selected' : '' }}>Advance Paid</option>
                    <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full py-2 bg-brand-burgundy text-white text-xs font-bold rounded-xl hover:bg-brand-deep-burgundy transition-all">
                    <i class="fas fa-filter mr-1"></i> Apply Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-brand-offwhite text-brand-charcoal text-xs font-bold rounded-xl hover:bg-brand-cream border border-brand-light-border">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- 4 Primary Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-brand-muted-brown">
                <span class="text-[11px] font-bold uppercase tracking-wider">Confirmed Booking Value</span>
                <div class="w-8 h-8 rounded-full bg-brand-burgundy/10 text-brand-burgundy flex items-center justify-center text-xs">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-brand-charcoal">
                ₹{{ number_format($confirmedBookingValue) }}
            </div>
            <span class="text-[10px] text-brand-muted-brown block">{{ $confirmedBookingsCount }} Confirmed Bookings</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-emerald-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Total Received</span>
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-emerald-900">
                ₹{{ number_format($totalCollected) }}
            </div>
            <span class="text-[10px] text-emerald-700 block">Advance: ₹{{ number_format($advanceCollected) }} • Balance: ₹{{ number_format($balanceCollected) }}</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-amber-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Outstanding Balance</span>
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-xs">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-amber-900">
                ₹{{ number_format($outstandingBalance) }}
            </div>
            <span class="text-[10px] text-amber-700 block">Due across confirmed events</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-brand-light-border shadow-soft-luxury space-y-1">
            <div class="flex items-center justify-between text-purple-700">
                <span class="text-[11px] font-bold uppercase tracking-wider">Quoted Pipeline</span>
                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center text-xs">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
            <div class="font-serif text-2xl sm:text-3xl font-bold text-purple-900">
                ₹{{ number_format($totalQuotedValue) }}
            </div>
            <span class="text-[10px] text-purple-700 block">{{ $totalQuotationsCount }} Quotations ({{ $acceptedQuotationsCount }} Accepted)</span>
        </div>
    </div>

    <!-- Secondary Grids: Location Breakdown & Operational Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Service Area Distribution -->
        <div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-4">
            <h2 class="font-serif text-lg font-bold text-brand-charcoal">
                <i class="fas fa-map-marked-alt text-brand-gold mr-1.5"></i> Regional Distribution
            </h2>
            <div class="divide-y divide-brand-light-border/60">
                @forelse($cityBreakdown as $cityStat)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <span class="font-semibold text-brand-charcoal">{{ $cityStat->city }}</span>
                        <div class="text-right">
                            <span class="font-bold text-brand-burgundy">{{ $cityStat->total }} events</span>
                            <span class="text-[10px] text-brand-muted-brown block">₹{{ number_format($cityStat->value) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-brand-muted-brown py-4 text-center">No city data for selected filters.</p>
                @endforelse
            </div>
        </div>

        <!-- Operational Status Overview -->
        <div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-4">
            <h2 class="font-serif text-lg font-bold text-brand-charcoal">
                <i class="fas fa-tasks text-brand-gold mr-1.5"></i> Operational Status
            </h2>
            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between p-2.5 bg-brand-offwhite rounded-xl">
                    <span class="font-medium text-brand-charcoal">Total Logged Bookings:</span>
                    <span class="font-bold text-brand-burgundy text-sm">{{ $totalBookings }}</span>
                </div>
                <div class="flex items-center justify-between p-2.5 bg-emerald-50 text-emerald-800 rounded-xl">
                    <span class="font-medium">Completed Celebrations:</span>
                    <span class="font-bold text-sm">{{ $completedBookingsCount }}</span>
                </div>
                <div class="flex items-center justify-between p-2.5 bg-rose-50 text-rose-800 rounded-xl">
                    <span class="font-medium">Cancellations / Declined:</span>
                    <span class="font-bold text-sm">{{ $cancelledBookingsCount }}</span>
                </div>
                <div class="flex items-center justify-between p-2.5 bg-purple-50 text-purple-800 rounded-xl">
                    <span class="font-medium">Quotation Acceptance Rate:</span>
                    <span class="font-bold text-sm">
                        {{ $totalQuotationsCount > 0 ? round(($acceptedQuotationsCount / $totalQuotationsCount) * 100) : 0 }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Export Reference Box -->
        <div class="bg-gradient-to-br from-brand-offwhite to-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-4 flex flex-col justify-between">
            <div>
                <h2 class="font-serif text-lg font-bold text-brand-charcoal">
                    <i class="fas fa-download text-brand-gold mr-1.5"></i> Data Exports
                </h2>
                <p class="text-xs text-brand-muted-brown mt-1">
                    Download native CSV spreadsheets for client accounts, audited ledger receipts, and active proposal schedules.
                </p>
            </div>
            <div class="space-y-2 pt-2">
                <a href="{{ route('admin.reports.export.bookings') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 text-xs font-bold bg-white text-brand-charcoal border border-brand-light-border hover:border-brand-gold rounded-xl transition-all">
                    <span>Full Bookings Register</span>
                    <i class="fas fa-arrow-down text-brand-burgundy"></i>
                </a>
                <a href="{{ route('admin.reports.export.payments') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 text-xs font-bold bg-white text-brand-charcoal border border-brand-light-border hover:border-brand-gold rounded-xl transition-all">
                    <span>Payment Transactions Ledger</span>
                    <i class="fas fa-arrow-down text-emerald-600"></i>
                </a>
                <a href="{{ route('admin.reports.export.quotations') }}" class="w-full flex items-center justify-between px-3.5 py-2.5 text-xs font-bold bg-white text-brand-charcoal border border-brand-light-border hover:border-brand-gold rounded-xl transition-all">
                    <span>Proposals & Quotations Register</span>
                    <i class="fas fa-arrow-down text-amber-600"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-2xl p-6 border border-brand-light-border shadow-soft-luxury space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-lg font-bold text-brand-charcoal">
                <i class="fas fa-receipt text-brand-gold mr-1.5"></i> Recent Payment Receipts
            </h2>
            <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-brand-burgundy hover:underline">
                View All Payments <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-brand-light-border/70 text-brand-muted-brown uppercase tracking-wider font-semibold">
                        <th class="py-2.5 px-3">Receipt Ref</th>
                        <th class="py-2.5 px-3">Booking Ref</th>
                        <th class="py-2.5 px-3">Customer</th>
                        <th class="py-2.5 px-3">Amount</th>
                        <th class="py-2.5 px-3">Type / Method</th>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-light-border/50">
                    @forelse($recentPayments as $p)
                        <tr class="hover:bg-brand-offwhite/50">
                            <td class="py-3 px-3 font-mono font-bold text-brand-burgundy">
                                <a href="{{ route('admin.payments.show', $p->id) }}" class="hover:underline">{{ $p->payment_reference }}</a>
                            </td>
                            <td class="py-3 px-3 font-mono text-gray-700">
                                {{ $p->booking?->booking_reference ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-3 font-medium text-brand-charcoal">
                                {{ $p->customer?->name ?? 'Client' }}
                            </td>
                            <td class="py-3 px-3 font-bold text-emerald-800">
                                ₹{{ number_format($p->amount) }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="capitalize">{{ $p->payment_type }}</span> • <span class="uppercase text-[10px] text-gray-500">{{ $p->payment_method }}</span>
                            </td>
                            <td class="py-3 px-3 text-gray-500">
                                {{ $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p->status_badge_classes }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-500">No payment transactions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
