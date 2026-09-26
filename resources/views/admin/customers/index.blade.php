@extends('layouts.admin')

@section('title', 'Registered Customers')
@section('header', 'Customer Accounts & Profiles')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="w-full flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer name, email, phone, city..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">
            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Contact Phone</th>
                        <th class="py-3.5 px-4">Location</th>
                        <th class="py-3.5 px-4">Bookings</th>
                        <th class="py-3.5 px-4">Joined Date</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $cust)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block text-xs">{{ $cust->name }}</span>
                                <span class="text-[11px] text-slate-400 block">{{ $cust->email }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-700">
                                {{ $cust->phone ?? 'Not provided' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600">
                                {{ $cust->city ?? 'Bihar' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-amber-700">
                                {{ $cust->bookings_count }} bookings
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                {{ $cust->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $cust->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $cust->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.customers.show', $cust->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 font-semibold transition" title="View Profile & Bookings">
                                        View
                                    </a>
                                    <form action="{{ route('admin.customers.toggleStatus', $cust->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg {{ $cust->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} font-semibold text-xs transition">
                                            {{ $cust->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No registered customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
