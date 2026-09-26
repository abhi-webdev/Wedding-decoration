@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Business & Operations Overview')

@section('content')
<div class="space-y-8">
    <!-- Top Financial Performance KPI Grid -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Financial & Revenue Metrics</h2>
            <span class="text-xs text-slate-400">Live Business Calculations</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Booking Pipeline Value -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Booking Value</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">₹{{ number_format($counts['total_booking_value'], 2) }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Across {{ $counts['total_bookings'] }} active booking requests
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <!-- Total Quoted Value -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Quoted Amount</p>
                    <h3 class="text-2xl font-bold text-amber-700 mt-1">₹{{ number_format($counts['total_quoted_value'], 2) }}</h3>
                    <p class="text-[11px] text-amber-600 mt-1">
                        {{ $counts['total_quotations'] }} Formal Quotation(s) Issued
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            <!-- Advance Received -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Advance Received</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1">₹{{ number_format($counts['advance_received'], 2) }}</h3>
                    <p class="text-[11px] text-emerald-600 font-medium mt-1">
                        {{ $counts['total_payments'] }} Verified transaction(s)
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <!-- Outstanding Balance -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Outstanding Balance</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">₹{{ number_format($counts['outstanding_balance'], 2) }}</h3>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Due on/before event setups
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Operations & Workflow Status Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Total Bookings</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counts['total_bookings']) }}</p>
            <span class="text-[10px] text-amber-600 font-semibold mt-0.5">{{ $counts['pending_bookings'] }} pending review</span>
        </a>

        <a href="{{ route('admin.calendar.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Confirmed Events</span>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($counts['confirmed_bookings']) }}</p>
            <span class="text-[10px] text-emerald-700 font-semibold mt-0.5">On Event Calendar</span>
        </a>

        <a href="{{ route('admin.quotations.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Quotations</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counts['total_quotations']) }}</p>
            <span class="text-[10px] text-blue-600 font-semibold mt-0.5">Manage Quotes</span>
        </a>

        <a href="{{ route('admin.payments.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Payments</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counts['total_payments']) }}</p>
            <span class="text-[10px] text-emerald-600 font-semibold mt-0.5">Deposit Records</span>
        </a>

        <a href="{{ route('admin.invoices.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Tax Invoices</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counts['total_invoices']) }}</p>
            <span class="text-[10px] text-purple-600 font-semibold mt-0.5">Printable Bills</span>
        </a>

        <a href="{{ route('admin.customers.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-400 transition flex flex-col items-center text-center">
            <span class="text-xs text-slate-500 font-medium">Registered Clients</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($counts['total_customers']) }}</p>
            <span class="text-[10px] text-slate-400 font-semibold mt-0.5">Customer Base</span>
        </a>
    </div>

    <!-- Urgent Action Banners (if any pending requests) -->
    @if($counts['pending_reschedules'] > 0 || $counts['pending_cancellations'] > 0 || $counts['pending_quotes'] > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($counts['pending_reschedules'] > 0)
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-blue-900">{{ $counts['pending_reschedules'] }} Reschedule Request(s)</p>
                            <p class="text-xs text-blue-700">Customers requesting date updates</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.reschedule-requests.index') }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold">Review</a>
                </div>
            @endif

            @if($counts['pending_cancellations'] > 0)
                <div class="bg-red-50 border border-red-200 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 text-red-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-red-900">{{ $counts['pending_cancellations'] }} Cancellation Request(s)</p>
                            <p class="text-xs text-red-700">Customers requesting cancellations</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.cancellation-requests.index') }}" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold">Review</a>
                </div>
            @endif

            @if($counts['pending_quotes'] > 0)
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-100 text-amber-700 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-amber-900">{{ $counts['pending_quotes'] }} Custom Quote Request(s)</p>
                            <p class="text-xs text-amber-700">Custom inquiries awaiting estimation</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.quotes.index') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold">Review</a>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Workspace Grid: Recent Operations, Quotations, and Upcoming Schedule -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Bookings Table (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Recent Booking Requests</h2>
                        <p class="text-xs text-slate-500">Live booking activity across Bihar & UP</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">View All Bookings &rarr;</a>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                            <tr>
                                <th class="py-3 px-4">Ref / Customer</th>
                                <th class="py-3 px-4">Decoration</th>
                                <th class="py-3 px-4">Event Date</th>
                                <th class="py-3 px-4">Booking Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentBookings as $b)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 block">#{{ $b->booking_reference }}</span>
                                        <span class="text-[11px] text-slate-500">{{ $b->customer_name }}</span>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-800 truncate max-w-[150px]">
                                        {{ $b->decoration ? $b->decoration->name : 'Custom Decoration' }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="font-semibold text-slate-700 block">{{ \Carbon\Carbon::parse($b->event_date)->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $b->event_city ?? 'Bihar' }}</span>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-slate-900">
                                        ₹{{ number_format($b->total_price) }}
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ in_array($b->status, ['confirmed', 'advance_paid', 'scheduled']) ? 'bg-emerald-100 text-emerald-800' :
                                               ($b->status === 'quoted' ? 'bg-blue-100 text-blue-800' :
                                               ($b->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                               ($b->status === 'completed' ? 'bg-teal-100 text-teal-800' :
                                               ($b->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800')))) }}">
                                            {{ str_replace('_', ' ', $b->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $b->id) }}" class="inline-block px-2.5 py-1 rounded bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 font-semibold transition">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400">No booking requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Quotations & Payments Split Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Quotations -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <h3 class="font-bold text-slate-900 text-sm">Recent Quotations</h3>
                            <a href="{{ route('admin.quotations.index') }}" class="text-xs text-amber-700 font-semibold">View All &rarr;</a>
                        </div>
                        <div class="space-y-2.5 text-xs">
                            @forelse($recentQuotations as $q)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                                    <div>
                                        <span class="font-mono font-bold text-slate-800 block">{{ $q->quotation_number }}</span>
                                        <span class="text-[11px] text-slate-500">{{ $q->customer ? $q->customer->name : 'Customer' }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-slate-900 block">{{ $q->formatted_grand_total }}</span>
                                        <span class="text-[10px] font-semibold uppercase px-1.5 py-0.2 rounded {{ $q->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : ($q->status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-slate-200 text-slate-700') }}">
                                            {{ $q->status }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-xs py-4 text-center">No quotations generated yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                            <h3 class="font-bold text-slate-900 text-sm">Recent Receipts</h3>
                            <a href="{{ route('admin.payments.index') }}" class="text-xs text-emerald-700 font-semibold">View All &rarr;</a>
                        </div>
                        <div class="space-y-2.5 text-xs">
                            @forelse($recentPayments as $p)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-100">
                                    <div>
                                        <span class="font-mono font-bold text-slate-800 block">{{ $p->payment_reference }}</span>
                                        <span class="text-[11px] text-slate-500">{{ $p->customer ? $p->customer->name : 'Customer' }} ({{ $p->payment_method_label }})</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-emerald-700 block">{{ $p->formatted_amount }}</span>
                                        <span class="text-[10px] font-semibold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800">
                                            {{ $p->status }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-xs py-4 text-center">No payment transactions recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Upcoming Confirmed Events Schedule -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Upcoming Confirmed Events</h2>
                    <a href="{{ route('admin.calendar.index') }}" class="text-xs text-amber-700 font-bold hover:underline">Calendar &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingEvents as $event)
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-amber-50/40 transition">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">{{ $event->customer_name }}</span>
                                    <span class="text-[11px] text-slate-500">{{ $event->decoration ? $event->decoration->name : 'Wedding Theme' }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Confirmed</span>
                            </div>
                            <div class="mt-2 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-600">
                                <span class="flex items-center gap-1 font-semibold text-amber-700">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                </span>
                                <span class="text-slate-500">{{ $event->event_city ?? 'Bihar' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            No upcoming confirmed events scheduled currently.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Operations Quick Actions</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('admin.quotations.create') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-amber-500 hover:text-slate-950 font-medium transition text-center">
                        + New Quotation
                    </a>
                    <a href="{{ route('admin.payments.create') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-600 hover:text-white font-medium transition text-center">
                        + Record Payment
                    </a>
                    <a href="{{ route('admin.calendar.index') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-amber-500 hover:text-slate-950 font-medium transition text-center">
                        View Calendar
                    </a>
                    <a href="{{ route('admin.decorations.create') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-amber-500 hover:text-slate-950 font-medium transition text-center">
                        + New Decoration
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
