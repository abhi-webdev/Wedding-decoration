@extends('layouts.admin')

@section('title', 'Payment Transactions')
@section('header', 'Payment Tracking & Transactions')

@section('content')
<div class="space-y-6">
    <!-- Top Metrics Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Verified Collections</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">₹{{ number_format($metrics['total_received']) }}</p>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">{{ $metrics['total_transactions'] }} total recorded transaction(s)</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Pending Verification</span>
            <p class="text-2xl font-extrabold text-amber-700 mt-1">₹{{ number_format($metrics['pending_verification']) }}</p>
            <span class="text-[11px] text-amber-600 font-medium mt-0.5 block">{{ $metrics['pending_requests_count'] }} payment request(s) awaiting review</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Advance Received</span>
            <p class="text-2xl font-extrabold text-blue-700 mt-1">₹{{ number_format($metrics['advance_received']) }}</p>
            <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">Booking token deposits</span>
        </div>

        <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider block">Record Offline Payment</span>
                <p class="text-xs text-slate-300 mt-1">Record direct office UPI, cash, or bank transfers</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="mt-3 w-full py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-center rounded-xl text-xs transition shadow">
                + Record Transaction
            </a>
        </div>
    </div>

    <!-- Navigation Tabs & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <!-- Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !isset($isRequestsTab) && (!request('status') || request('status') === 'all') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                All Transactions ({{ $metrics['total_transactions'] }})
            </a>
            <a href="{{ route('admin.payments.requests') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ isset($isRequestsTab) || request('status') === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-900 hover:bg-amber-100 border border-amber-200' }}">
                <span>Pending Verification</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ isset($isRequestsTab) || request('status') === 'pending' ? 'bg-white text-amber-800' : 'bg-amber-600 text-white' }}">{{ $metrics['pending_requests_count'] }}</span>
            </a>
            <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') === 'paid' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Verified Payment History
            </a>
        </div>

        <form method="GET" action="{{ isset($isRequestsTab) ? route('admin.payments.requests') : route('admin.payments.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search payment ref, receipt #, transaction ID, client name/phone..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="payment_type" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Payment Types</option>
                <option value="advance" {{ request('payment_type') === 'advance' ? 'selected' : '' }}>Advance</option>
                <option value="balance" {{ request('payment_type') === 'balance' ? 'selected' : '' }}>Balance</option>
                <option value="full" {{ request('payment_type') === 'full' ? 'selected' : '' }}>Full Payment</option>
                <option value="other" {{ request('payment_type') === 'other' ? 'selected' : '' }}>Other</option>
            </select>

            <select name="payment_method" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Methods</option>
                <option value="upi" {{ request('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
            @if(request()->anyFilled(['search', 'payment_type', 'payment_method', 'status']))
                <a href="{{ isset($isRequestsTab) ? route('admin.payments.requests') : route('admin.payments.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-xs flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Payment Ref / Receipt</th>
                        <th class="py-3.5 px-4">Booking & Client</th>
                        <th class="py-3.5 px-4">Booked Item</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Type & Method</th>
                        <th class="py-3.5 px-4">Payment Date</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 font-mono block">{{ $pay->payment_reference }}</span>
                                @if($pay->receipt_number)
                                    <span class="text-[10px] text-emerald-700 font-mono font-bold block">Receipt #{{ $pay->receipt_number }}</span>
                                @endif
                                @if($pay->transaction_reference)
                                    <span class="text-[10px] text-slate-400 font-mono block">Txn: {{ $pay->transaction_reference }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $pay->customer->name ?? ($pay->booking ? $pay->booking->customer_name : 'Customer') }}</span>
                                    <a href="{{ route('admin.bookings.show', $pay->booking_id) }}" class="text-[10px] font-mono text-amber-600 hover:underline">
                                        Booking #{{ $pay->booking->booking_reference ?? $pay->booking_id }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800 max-w-[150px]">
                                <span class="truncate block font-semibold text-slate-900">{{ $pay->booking ? $pay->booking->booked_item_name : 'Custom Decoration' }}</span>
                                <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $pay->booking ? $pay->booking->booked_item_type_label : 'Item' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                {{ $pay->formatted_amount }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $pay->payment_type === 'advance' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $pay->payment_type }}
                                </span>
                                <span class="text-[10px] text-slate-500 block mt-0.5 capitalize">{{ $pay->payment_method_label }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">
                                {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') : $pay->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($pay->status === 'pending')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                                        Pending Verification
                                    </span>
                                @elseif(in_array($pay->status, ['paid', 'accepted', 'successful']))
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                                        Verified
                                    </span>
                                    @if($pay->verifiedByUser)
                                        <span class="text-[10px] text-slate-400 block">by {{ $pay->verifiedByUser->name }}</span>
                                    @endif
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-red-100 text-red-800">
                                        {{ $pay->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($pay->status === 'pending')
                                        <form action="{{ route('admin.payments.accept', $pay->id) }}" method="POST" class="inline" onsubmit="return confirm('Verify and ACCEPT this payment of {{ $pay->formatted_amount }}? A receipt will be generated.');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition shadow-sm">
                                                Accept
                                            </button>
                                        </form>
                                        <button type="button" onclick="openPaymentRejectModal('{{ $pay->id }}', '{{ $pay->payment_reference }}', '{{ number_format($pay->amount, 2) }}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg font-bold text-xs transition">
                                            Reject
                                        </button>
                                    @endif

                                    @if(in_array($pay->status, ['paid', 'accepted', 'successful']))
                                        <a href="{{ route('admin.payments.receipt', $pay->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold text-xs transition inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Receipt
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">No payment transactions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Reject Payment Modal -->
<div id="reject-payment-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-rose-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Reject Payment Request
            </h3>
            <button type="button" onclick="document.getElementById('reject-payment-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <p class="text-xs text-slate-600" id="reject-payment-modal-text">
            Please enter a reason for rejecting this payment request.
        </p>

        <form id="reject-payment-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="payment_rejection_reason" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Rejection Reason <span class="text-rose-500">*</span>
                </label>
                <textarea id="payment_rejection_reason" name="rejection_reason" required rows="3" placeholder="e.g., Transaction ID does not match our bank/UPI statement..." class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('reject-payment-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentRejectModal(paymentId, paymentRef, amount) {
    var form = document.getElementById('reject-payment-form');
    form.action = '/admin/payments/' + paymentId + '/reject';
    document.getElementById('reject-payment-modal-text').innerText = 'Enter rejection reason for Payment #' + paymentRef + ' of ₹' + amount + ':';
    document.getElementById('reject-payment-modal').classList.remove('hidden');
}
</script>
@endsection
