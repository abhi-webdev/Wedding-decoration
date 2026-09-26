@extends('layouts.admin')

@section('title', 'Customer Profile - ' . $customer->name)
@section('header', 'Customer Profile: ' . $customer->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Customers</span>
        </a>
    </div>

    <!-- Customer Overview Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-700 flex items-center justify-center font-heading font-bold text-white text-xl">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $customer->name }}</h3>
                    <p class="text-xs text-slate-500">Member since {{ $customer->created_at->format('d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.customers.toggleStatus', $customer->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl {{ $customer->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} font-bold text-xs transition">
                        {{ $customer->is_active ? 'Deactivate Customer Account' : 'Activate Customer Account' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Email Address</span>
                <p class="font-bold text-slate-800 mt-1">{{ $customer->email }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Phone Number</span>
                <p class="font-bold text-slate-800 mt-1">{{ $customer->phone ?? 'Not provided' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">City / Location</span>
                <p class="font-bold text-slate-800 mt-1">{{ $customer->city ?? 'Bihar' }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Confirmed Order Value</span>
                <p class="font-bold text-emerald-700 mt-1 text-sm">₹{{ number_format($totalSpent) }}</p>
            </div>
        </div>
    </div>

    <!-- Bookings History -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-900">Booking History ({{ $customer->bookings->count() }})</h4>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Ref</th>
                        <th class="py-3 px-4">Decoration</th>
                        <th class="py-3 px-4">Event Date</th>
                        <th class="py-3 px-4">Total Amount</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->bookings as $b)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-mono font-bold text-slate-900">#{{ $b->booking_reference }}</td>
                            <td class="py-3 px-4 font-medium text-slate-800">{{ $b->decoration ? $b->decoration->name : 'Custom' }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($b->event_date)->format('d M Y') }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">₹{{ number_format($b->total_price) }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    {{ $b->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' :
                                       ($b->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                       ($b->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                       ($b->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800'))) }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.bookings.show', $b->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-amber-50 hover:text-amber-700 rounded-lg text-slate-700 font-semibold text-xs">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No booking requests submitted by this customer yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
