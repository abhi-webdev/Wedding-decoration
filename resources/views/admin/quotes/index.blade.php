@extends('layouts.admin')

@section('title', 'Manage Quotes')
@section('header', 'Custom Quote Inquiries')

@section('content')
<div class="space-y-6">
    <!-- Top Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.quotes.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quote ref, customer name, email, phone, city..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="quoted" {{ request('status') === 'quoted' ? 'selected' : '' }}>Quoted</option>
                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Declined</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>
    </div>

    <!-- Quotes Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Quote Ref</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Event Type & City</th>
                        <th class="py-3.5 px-4">Budget Range</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($quotes as $q)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block font-mono">#{{ $q->quote_reference }}</span>
                                <span class="text-[10px] text-slate-400">{{ $q->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block">{{ $q->name }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $q->phone }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 block capitalize">{{ $q->event_type ?? 'Wedding' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $q->city ?? 'Bihar' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-700">
                                {{ $q->budget_range ?? ($q->budget ? '₹' . number_format($q->budget) : 'Not specified') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $q->status === 'converted' ? 'bg-emerald-100 text-emerald-800' :
                                       ($q->status === 'pending' ? 'bg-amber-100 text-amber-800' :
                                       ($q->status === 'quoted' ? 'bg-blue-100 text-blue-800' :
                                       ($q->status === 'declined' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800'))) }}">
                                    {{ $q->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.quotes.show', $q->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold transition">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No quote inquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
