@extends('layouts.admin')

@section('title', 'Reschedule Requests')
@section('header', 'Booking Reschedule Requests')

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-500">Filter Status:</span>
            <a href="{{ route('admin.reschedule-requests.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>
            <a href="{{ route('admin.reschedule-requests.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'pending' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Pending</a>
            <a href="{{ route('admin.reschedule-requests.index', ['status' => 'approved']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Approved</a>
            <a href="{{ route('admin.reschedule-requests.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ request('status') === 'rejected' ? 'bg-red-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Rejected</a>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Booking Ref</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Original Date</th>
                        <th class="py-3.5 px-4">Requested New Date</th>
                        <th class="py-3.5 px-4">Reason / Notes</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('admin.bookings.show', $req->booking_id) }}" class="font-bold text-amber-700 hover:underline font-mono">
                                    #{{ $req->booking->booking_reference ?? $req->booking_id }}
                                </a>
                                <span class="text-[10px] text-slate-400 block">{{ $req->created_at->format('d M Y, h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 block">{{ $req->user->name ?? $req->booking->customer_name }}</span>
                                <span class="text-[11px] text-slate-500 block">{{ $req->user->phone ?? $req->booking->customer_phone }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap line-through text-slate-400">
                                {{ \Carbon\Carbon::parse($req->current_event_date ?? $req->booking->event_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-blue-700">
                                {{ \Carbon\Carbon::parse($req->requested_event_date)->format('d M Y') }}
                                <span class="text-[10px] text-slate-400 block font-normal">{{ $req->requested_event_slot ?? 'Full Day' }}</span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-xs text-slate-800 font-medium line-clamp-2">{{ $req->reason }}</p>
                                @if($req->admin_notes)
                                    <p class="text-[11px] text-slate-500 mt-1 italic">Admin: {{ $req->admin_notes }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                                       ($req->status === 'pending' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $req->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if($req->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="document.getElementById('approve-modal-{{ $req->id }}').classList.remove('hidden')" class="px-2.5 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition">
                                            Approve Date
                                        </button>
                                        <button onclick="document.getElementById('reject-modal-{{ $req->id }}').classList.remove('hidden')" class="px-2.5 py-1 rounded bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition">
                                            Reject
                                        </button>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div id="approve-modal-{{ $req->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl">
                                            <h4 class="text-base font-bold text-slate-900 mb-2">Approve Date Reschedule?</h4>
                                            <p class="text-xs text-slate-600 mb-4">
                                                This will shift the event date to <strong>{{ \Carbon\Carbon::parse($req->requested_event_date)->format('d M Y') }}</strong> after validating slot availability.
                                            </p>
                                            <form action="{{ route('admin.reschedule-requests.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admin Resolution Note (Optional)</label>
                                                    <textarea name="admin_notes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800" placeholder="e.g. Approved and confirmed on master calendar..."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="document.getElementById('approve-modal-{{ $req->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</button>
                                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs">Confirm Reschedule</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div id="reject-modal-{{ $req->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl">
                                            <h4 class="text-base font-bold text-slate-900 mb-2">Reject Reschedule?</h4>
                                            <p class="text-xs text-slate-600 mb-4">
                                                State the reason for rejecting this reschedule request (e.g., date slot fully booked).
                                            </p>
                                            <form action="{{ route('admin.reschedule-requests.reject', $req->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Rejection Reason (Required)</label>
                                                    <textarea name="admin_notes" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800" placeholder="e.g. Requested date slot already fully booked with existing confirmed weddings..."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="document.getElementById('reject-modal-{{ $req->id }}').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</button>
                                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-xs">Confirm Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No reschedule requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
