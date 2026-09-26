@extends('layouts.admin')

@section('title', 'Record Payment')
@section('header', 'Record Customer Payment')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Payments</span>
    </a>

    <form action="{{ route('admin.payments.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <h3 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center justify-between">
            <span>Payment Details</span>
            <span class="text-xs text-amber-700 font-semibold font-mono">MANUAL TRANSACTION VERIFICATION</span>
        </h3>

        <!-- Booking Selection -->
        @if($booking)
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs">
                <div>
                    <span class="font-bold text-slate-900 font-mono text-sm block">Booking #{{ $booking->booking_reference }}</span>
                    <span class="text-slate-700 font-semibold">{{ $booking->customer_name }} ({{ $booking->customer_phone }})</span>
                    <p class="text-slate-500 mt-0.5">
                        Decoration: <strong>{{ $booking->decoration->name ?? 'Custom Setup' }}</strong> • 
                        Event Date: <strong>{{ $booking->formatted_event_date }}</strong>
                    </p>
                </div>
                <div class="text-left sm:text-right bg-white p-3 rounded-lg border border-slate-200">
                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Quotation / Total:</span>
                    <span class="font-bold text-slate-900 text-sm">₹{{ number_format($booking->effective_total) }}</span>
                    <span class="text-[10px] text-amber-800 font-semibold block">Balance Due: ₹{{ number_format($booking->balance_due) }}</span>
                </div>
            </div>
        @else
            <div>
                <label for="booking_id" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Select Event Booking *</label>
                <select name="booking_id" id="booking_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500" onchange="if(this.value) window.location.href = '{{ route('admin.payments.create') }}?booking_id=' + this.value">
                    <option value="">-- Choose Booking --</option>
                    @foreach($bookings as $b)
                        <option value="{{ $b->id }}" {{ old('booking_id') == $b->id ? 'selected' : '' }}>
                            #{{ $b->booking_reference }} — {{ $b->customer_name }} (Total: ₹{{ number_format($b->effective_total) }}, Balance: ₹{{ number_format($b->balance_due) }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="amount" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Payment Amount (₹) *</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $booking ? ($booking->total_paid == 0 ? ($booking->activeQuotation ? $booking->activeQuotation->advance_amount : $booking->estimated_total * 0.4) : $booking->balance_due) : '') }}" min="1" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 font-bold focus:outline-none focus:border-amber-500" placeholder="e.g. 25000">
            </div>

            <div>
                <label for="payment_type" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Payment Type *</label>
                <select name="payment_type" id="payment_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="advance" {{ old('payment_type', ($booking && $booking->total_paid == 0 ? 'advance' : 'balance')) === 'advance' ? 'selected' : '' }}>Advance Deposit (Date Lock-in)</option>
                    <option value="balance" {{ old('payment_type', ($booking && $booking->total_paid > 0 ? 'balance' : 'advance')) === 'balance' ? 'selected' : '' }}>Balance Settlement</option>
                    <option value="full" {{ old('payment_type') === 'full' ? 'selected' : '' }}>Full 100% Payment</option>
                    <option value="other" {{ old('payment_type') === 'other' ? 'selected' : '' }}>Additional Surcharge / Other</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="payment_method" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Payment Method *</label>
                <select name="payment_method" id="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI (GooglePay / PhonePe / Paytm / QR)</option>
                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>NEFT / RTGS / IMPS Bank Transfer</option>
                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash in Hand (Office / Venue Collection)</option>
                    <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Credit / Debit Card</option>
                    <option value="other" {{ old('payment_method') === 'other' ? 'selected' : '' }}>Other Method</option>
                </select>
            </div>

            <div>
                <label for="payment_date" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Transaction Date *</label>
                <input type="datetime-local" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label for="transaction_reference" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Bank Reference / UPI UTR Number (Optional)</label>
            <input type="text" name="transaction_reference" id="transaction_reference" value="{{ old('transaction_reference') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500" placeholder="e.g. UPI Ref 324598127419 or Cheque #">
        </div>

        <div>
            <label for="notes" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Internal Transaction Notes</label>
            <textarea name="notes" id="notes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800" placeholder="e.g. Received 40% advance from client via PhonePe QR at Patna office...">{{ old('notes') }}</textarea>
        </div>

        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="create_invoice" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Automatically generate and issue formal Invoice/Receipt for this transaction</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.payments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">
                Confirm & Record Transaction
            </button>
        </div>
    </form>
</div>
@endsection
