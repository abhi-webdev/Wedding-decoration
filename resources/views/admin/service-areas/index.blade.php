@extends('layouts.admin')

@section('title', 'Manage Service Areas')
@section('header', 'Service Areas & Coverage (Bihar / UP)')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.service-areas.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search city, district, name..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="state" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All States</option>
                <option value="Bihar" {{ request('state') === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                <option value="Uttar Pradesh" {{ request('state') === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>

        <a href="{{ route('admin.service-areas.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shrink-0 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Service Area</span>
        </a>
    </div>

    <!-- Service Areas Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Area / City</th>
                        <th class="py-3.5 px-4">State & District</th>
                        <th class="py-3.5 px-4">Tier</th>
                        <th class="py-3.5 px-4">Travel Surcharge</th>
                        <th class="py-3.5 px-4">Min. Booking</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($serviceAreas as $area)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $area->name }}
                                <span class="text-[10px] text-slate-400 block font-normal">{{ $area->city }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 block">{{ $area->state }}</span>
                                <span class="text-[11px] text-slate-500">{{ $area->district ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap uppercase text-[10px] font-bold">
                                <span class="px-2 py-0.5 rounded {{ $area->state === 'Bihar' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ str_replace('_', ' ', $area->tier) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900">
                                {{ $area->travel_surcharge > 0 ? '₹' . number_format($area->travel_surcharge) : 'Free (₹0)' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">
                                {{ $area->min_booking_amount > 0 ? '₹' . number_format($area->min_booking_amount) : 'No min' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $area->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $area->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.service-areas.edit', $area->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 font-semibold transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.service-areas.destroy', $area->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service area?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-700 font-semibold transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No service areas found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($serviceAreas->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $serviceAreas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
