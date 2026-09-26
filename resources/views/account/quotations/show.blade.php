@extends('layouts.account')

@section('title', 'Quotation ' . $quotation->quotation_number . ' | Aditya Utsav')

@section('account_content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('account.quotations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-muted-brown hover:text-brand-burgundy transition">
            <i class="fas fa-arrow-left text-[10px]"></i>
            <span>Back to My Quotations</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $quotation->status_badge_classes }}">
                {{ $quotation->status }}
            </span>
        </div>
    </div>

    <!-- Official Quotation Card -->
    <div class="bg-white rounded-2xl border border-brand-light-border shadow-soft-luxury p-6 sm:p-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-brand-light-border gap-4">
            <div>
                <span class="font-serif text-lg font-bold text-brand-burgundy">ADITYA UTSAV WEDDING DECORATION</span>
                <p class="text-xs text-brand-muted-brown">Professional Event Planners • Siwan, Patna, Gopalganj & UP</p>
                <p class="text-[11px] text-brand-muted-brown mt-0.5">Helpline: <strong>+91 99312 00000</strong></p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs font-mono font-bold text-brand-charcoal block">{{ $quotation->quotation_number }}</span>
                <p class="text-xs text-brand-muted-brown">Issued: <strong>{{ $quotation->created_at->format('d F Y') }}</strong></p>
                <p class="text-xs {{ $quotation->isExpired() ? 'text-rose-600 font-bold' : 'text-brand-muted-brown' }}">
                    Valid Until: <strong>{{ $quotation->valid_until ? $quotation->valid_until->format('d F Y') : 'Open' }}</strong>
                </p>
            </div>
        </div>

        <!-- Event Details Banner -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-brand-cream/50 p-4 rounded-xl border border-brand-gold/30">
            <div>
                <span class="text-[10px] uppercase font-bold text-brand-royal-rose block mb-0.5">Booking Information:</span>
                <p class="font-bold text-brand-charcoal text-sm">Booking #{{ $quotation->booking->booking_reference ?? $quotation->booking_id }}</p>
                <p class="text-brand-muted-brown">Theme: <strong>{{ $quotation->booking->decoration->name ?? 'Custom Package' }}</strong></p>
            </div>
            <div class="sm:text-right">
                <span class="text-[10px] uppercase font-bold text-brand-royal-rose block mb-0.5">Venue & Date:</span>
                <p class="font-bold text-brand-charcoal">{{ $quotation->booking ? $quotation->booking->formatted_event_date : 'Date TBD' }}</p>
                <p class="text-brand-muted-brown">{{ $quotation->booking->address_line ?? '' }} ({{ $quotation->booking->city ?? 'Bihar' }})</p>
            </div>
        </div>

        <!-- Line Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-brand-charcoal">
                <thead class="bg-brand-offwhite uppercase font-semibold text-brand-muted-brown text-[11px] border-b border-brand-light-border">
                    <tr>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3 px-3">Included Item / Service</th>
                        <th class="py-3 px-3 text-center">Qty</th>
                        <th class="py-3 px-3 text-right">Unit Price</th>
                        <th class="py-3 px-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-light-border/60">
                    @foreach($quotation->items as $idx => $item)
                        <tr>
                            <td class="py-3 px-3 text-brand-muted-brown">{{ $idx + 1 }}</td>
                            <td class="py-3 px-3 font-semibold text-brand-charcoal">
                                {{ $item->description }}
                            </td>
                            <td class="py-3 px-3 text-center">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-right font-mono">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-right font-bold font-mono">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Breakdown -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-brand-light-border text-xs">
            <div class="space-y-3">
                @if($quotation->terms)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-brand-royal-rose block mb-1">Booking Terms & Floral Policy:</span>
                        <div class="bg-brand-offwhite p-3 rounded-xl border border-brand-light-border text-brand-muted-brown text-[11px] whitespace-pre-line leading-relaxed">
                            {{ $quotation->terms }}
                        </div>
                    </div>
                @endif

                @if($quotation->notes)
                    <div>
                        <span class="text-[10px] uppercase font-bold text-brand-royal-rose block mb-1">Planner Special Notes:</span>
                        <div class="bg-brand-offwhite p-3 rounded-xl border border-brand-light-border text-brand-muted-brown text-[11px] whitespace-pre-line">
                            {{ $quotation->notes }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-brand-offwhite p-5 rounded-2xl border border-brand-light-border space-y-2">
                <div class="flex justify-between text-brand-muted-brown">
                    <span>Subtotal:</span>
                    <span class="font-mono font-semibold">₹{{ number_format($quotation->subtotal, 2) }}</span>
                </div>

                @if($quotation->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Discount Savings:</span>
                        <span class="font-mono">-₹{{ number_format($quotation->discount_amount, 2) }}</span>
                    </div>
                @endif

                @if($quotation->additional_charges > 0)
                    <div class="flex justify-between text-brand-muted-brown">
                        <span>Transportation / Extras:</span>
                        <span class="font-mono">+₹{{ number_format($quotation->additional_charges, 2) }}</span>
                    </div>
                @endif

                <div class="pt-2 border-t border-brand-light-border flex justify-between items-center text-brand-charcoal">
                    <span class="font-bold text-sm">Grand Total:</span>
                    <span class="text-lg font-extrabold text-brand-burgundy font-mono">{{ $quotation->formatted_grand_total }}</span>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 space-y-1 mt-2 text-[11px]">
                    <div class="flex justify-between font-bold text-amber-900">
                        <span>Required Advance to Lock ({{ $quotation->advance_percentage }}%):</span>
                        <span class="font-mono text-sm">{{ $quotation->formatted_advance }}</span>
                    </div>
                    <div class="flex justify-between text-amber-800">
                        <span>Balance on Event Day:</span>
                        <span class="font-mono">{{ $quotation->formatted_balance }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Decision Actions -->
        @if($quotation->canBeAccepted())
            <div class="pt-6 border-t border-brand-light-border flex flex-col sm:flex-row justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-700 font-semibold text-xs transition">
                    Decline Quotation
                </button>

                <form action="{{ route('account.quotations.accept', $quotation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-7 py-2.5 rounded-xl bg-brand-gold hover:bg-brand-gold-light text-brand-charcoal font-bold text-xs transition shadow-sm">
                        <i class="fas fa-check-circle mr-1 text-brand-burgundy"></i> Accept Quotation & Confirm Booking
                    </button>
                </form>
            </div>
        @elseif($quotation->status === 'accepted')
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-emerald-600 text-base"></i>
                    <span>You accepted this quotation on <strong>{{ $quotation->approved_at ? $quotation->approved_at->format('d M Y') : 'recently' }}</strong>. Your booking is confirmed.</span>
                </div>
                <a href="{{ route('account.payments.index') }}" class="px-4 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition">
                    View Payment Info
                </a>
            </div>
        @elseif($quotation->isExpired())
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                <span>This quotation expired on <strong>{{ $quotation->valid_until->format('d F Y') }}</strong>. Please contact our team at <strong>+91 99312 00000</strong> to renew date availability.</span>
            </div>
        @endif
    </div>
</div>

<!-- Reject Reason Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 text-left shadow-2xl space-y-4">
        <h4 class="font-serif text-base font-bold text-brand-charcoal">Decline Quotation?</h4>
        <p class="text-xs text-brand-muted-brown">
            Let us know why this quotation didn't work for you. Our planners will contact you to tailor a package matching your budget.
        </p>

        <form action="{{ route('account.quotations.reject', $quotation->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-brand-charcoal mb-1">Reason (Optional)</label>
                <textarea name="reason" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800" placeholder="e.g. Budget adjustment needed, changes in guest capacity, etc..."></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs">Decline Quotation</button>
            </div>
        </form>
    </div>
</div>
@endsection
