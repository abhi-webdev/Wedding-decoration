@extends('layouts.admin')

@section('title', 'Admin Activity Logs')
@section('header', 'System Audit & Activity Trail')

@section('content')
<div class="space-y-6">
    <!-- Top Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search action, description, IP address..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="action" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Actions</option>
                <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>Create</option>
                <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>Update</option>
                <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>Delete</option>
                <option value="status_change" {{ request('action') === 'status_change' ? 'selected' : '' }}>Status Change</option>
                <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Login</option>
                <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Logout</option>
            </select>

            <select name="entity_type" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Entities</option>
                <option value="booking" {{ request('entity_type') === 'booking' ? 'selected' : '' }}>Booking</option>
                <option value="decoration" {{ request('entity_type') === 'decoration' ? 'selected' : '' }}>Decoration</option>
                <option value="category" {{ request('entity_type') === 'category' ? 'selected' : '' }}>Category</option>
                <option value="package" {{ request('entity_type') === 'package' ? 'selected' : '' }}>Package</option>
                <option value="offer" {{ request('entity_type') === 'offer' ? 'selected' : '' }}>Offer</option>
                <option value="quote_request" {{ request('entity_type') === 'quote_request' ? 'selected' : '' }}>Quote Request</option>
                <option value="cancellation_request" {{ request('entity_type') === 'cancellation_request' ? 'selected' : '' }}>Cancellation Request</option>
                <option value="reschedule_request" {{ request('entity_type') === 'reschedule_request' ? 'selected' : '' }}>Reschedule Request</option>
                <option value="user" {{ request('entity_type') === 'user' ? 'selected' : '' }}>User</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Timestamp</th>
                        <th class="py-3.5 px-4">Staff User</th>
                        <th class="py-3.5 px-4">Action</th>
                        <th class="py-3.5 px-4">Entity</th>
                        <th class="py-3.5 px-4">Description</th>
                        <th class="py-3.5 px-4 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500">
                                {{ $log->created_at->format('d M Y, h:i:s A') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block">{{ $log->user->name ?? 'System' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $log->user->role ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap uppercase font-bold text-[10px]">
                                <span class="px-2 py-0.5 rounded
                                    {{ $log->action === 'create' ? 'bg-emerald-100 text-emerald-800' :
                                       ($log->action === 'update' ? 'bg-blue-100 text-blue-800' :
                                       ($log->action === 'delete' ? 'bg-red-100 text-red-800' :
                                       ($log->action === 'status_change' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-800'))) }}">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-700 font-medium">
                                {{ ucwords(str_replace('_', ' ', $log->entity_type ?? '-')) }}
                                @if($log->entity_id)
                                    <span class="text-slate-400 text-[10px]">#{{ $log->entity_id }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-800">
                                {{ $log->description }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-slate-400 text-[11px] whitespace-nowrap">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No activity logs recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
