@extends('layouts.admin')

@section('title', 'Manage Bookings')
@section('header', 'Wedding Decoration Bookings')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by reference, customer name, email, phone, city..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
            </div>

            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-amber-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <select name="city" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700 focus:outline-none focus:border-amber-500">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">
                Filter
            </button>
            @if(request()->anyFilled(['search', 'status', 'city', 'date_from', 'date_to']))
                <a href="{{ route('admin.bookings.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Booking Ref</th>
                        <th class="py-3.5 px-4">Customer Details</th>
                        <th class="py-3.5 px-4">Decoration Theme</th>
                        <th class="py-3.5 px-4">Event Date & City</th>
                        <th class="py-3.5 px-4">Estimated Total</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block font-mono">#{{ $booking->booking_reference }}</span>
                                <span class="text-[10px] text-slate-400">{{ $booking->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $booking->customer_name }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $booking->customer_phone }}</span>
                                <span class="text-[11px] text-slate-400 block truncate max-w-[180px]">{{ $booking->customer_email }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800">
                                @if($booking->decoration)
                                    <span class="font-semibold text-slate-900 block">{{ $booking->decoration->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $booking->decoration->category->name ?? 'Category' }}</span>
                                @else
                                    <span class="text-slate-500 italic">Custom Request</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-amber-800 block">{{ \Carbon\Carbon::parse($booking->event_date)->format('d M Y') }}</span>
                                <span class="text-[11px] text-slate-600 block">{{ $booking->event_city ?? 'Bihar' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 text-sm block">₹{{ number_format($booking->total_price) }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-semibold">ESTIMATED</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' :
                                       ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                       ($booking->status === 'in_progress' ? 'bg-purple-100 text-purple-800' :
                                       ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                       ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800')))) }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold transition">
                                    <span>Manage</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No bookings matched your filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
