@extends('layouts.admin')

@section('title', 'Create Quotation')
@section('header', 'Build New Quotation')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Quotations</span>
    </a>

    <form action="{{ route('admin.quotations.store') }}" method="POST" id="quotationForm" class="space-y-6">
        @csrf

        <!-- Booking Selection Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center justify-between">
                <span>1. Select Event Booking</span>
                <span class="text-xs text-amber-700 font-semibold font-mono">ADITYA UTSAV OPERATIONS</span>
            </h3>

            @if($booking)
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block font-mono">Booking #{{ $booking->booking_reference }}</span>
                        <span class="text-xs text-slate-700 font-semibold">{{ $booking->customer_name }} ({{ $booking->customer_phone }})</span>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Theme: <strong>{{ $booking->decoration->name ?? 'Custom Setup' }}</strong> • 
                            Event Date: <strong>{{ $booking->formatted_event_date }}</strong> • 
                            City: <strong>{{ $booking->city ?? 'Bihar' }}</strong>
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $booking->status_badge_classes }} shrink-0">
                        {{ $booking->status_label }}
                    </span>
                </div>
            @else
                <div>
                    <label for="booking_id" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Choose Booking *</label>
                    <select name="booking_id" id="booking_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500" onchange="if(this.value) window.location.href = '{{ route('admin.quotations.create') }}?booking_id=' + this.value">
                        <option value="">-- Select Pending or Quoted Booking --</option>
                        @foreach($bookings as $b)
                            <option value="{{ $b->id }}" {{ old('booking_id') == $b->id ? 'selected' : '' }}>
                                #{{ $b->booking_reference }} — {{ $b->customer_name }} ({{ $b->decoration->name ?? 'Theme' }} on {{ $b->formatted_event_date }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Line Items Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">2. Itemized Breakdown</h3>
                <button type="button" onclick="addItemRow()" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Line Item</span>
                </button>
            </div>

            <div id="itemsContainer" class="space-y-3">
                <!-- Base Decoration Item -->
                <div class="item-row grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200/70 items-center text-xs">
                    <div class="col-span-12 sm:col-span-5">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Item Description</label>
                        <input type="text" name="items[0][description]" value="{{ old('items.0.description', $booking ? 'Main Stage & Mandap Decoration: ' . ($booking->decoration->name ?? 'Selected Theme') : 'Main Stage & Floral Setup') }}" required
                            class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-semibold text-slate-800">
                        <input type="hidden" name="items[0][item_type]" value="decoration">
                        <input type="hidden" name="items[0][item_id]" value="{{ $booking->decoration_id ?? 1 }}">
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Qty</label>
                        <input type="number" name="items[0][quantity]" value="{{ old('items.0.quantity', 1) }}" min="1" required
                            class="item-qty w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 text-center" oninput="calculateQuotation()">
                    </div>
                    <div class="col-span-4 sm:col-span-3">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Unit Price (₹)</label>
                        <input type="number" name="items[0][unit_price]" value="{{ old('items.0.unit_price', $booking->base_amount ?? ($booking->decoration->base_price ?? 35000)) }}" step="0.01" min="0" required
                            class="item-price w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 font-semibold" oninput="calculateQuotation()">
                    </div>
                    <div class="col-span-4 sm:col-span-2 text-right">
                        <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Subtotal</label>
                        <span class="item-total-display font-bold text-slate-900 block pt-2 text-xs">₹0</span>
                    </div>
                </div>

                @if($booking && $booking->addons->count() > 0)
                    @foreach($booking->addons as $idx => $ba)
                        <div class="item-row grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200/70 items-center text-xs">
                            <div class="col-span-12 sm:col-span-5">
                                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Item Description</label>
                                <input type="text" name="items[{{ $idx + 1 }}][description]" value="{{ $ba->addon->name ?? 'Add-on Service' }}" required
                                    class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-semibold text-slate-800">
                                <input type="hidden" name="items[{{ $idx + 1 }}][item_type]" value="addon">
                                <input type="hidden" name="items[{{ $idx + 1 }}][item_id]" value="{{ $ba->addon_id }}">
                            </div>
                            <div class="col-span-4 sm:col-span-2">
                                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Qty</label>
                                <input type="number" name="items[{{ $idx + 1 }}][quantity]" value="{{ $ba->quantity ?? 1 }}" min="1" required
                                    class="item-qty w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 text-center" oninput="calculateQuotation()">
                            </div>
                            <div class="col-span-4 sm:col-span-3">
                                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Unit Price (₹)</label>
                                <input type="number" name="items[{{ $idx + 1 }}][unit_price]" value="{{ $ba->unit_price ?? ($ba->addon->price ?? 0) }}" step="0.01" min="0" required
                                    class="item-price w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 font-semibold" oninput="calculateQuotation()">
                            </div>
                            <div class="col-span-4 sm:col-span-2 flex items-center justify-between pt-4">
                                <span class="item-total-display font-bold text-slate-900 text-xs">₹0</span>
                                <button type="button" onclick="this.closest('.item-row').remove(); calculateQuotation();" class="text-rose-500 hover:text-rose-700 font-bold text-sm ml-2">&times;</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Financial Breakdown & Parameters Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left: Settings & Terms -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-2 border-b border-slate-100">3. Validity & Conditions</h3>

                <div>
                    <label for="valid_until" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Quotation Valid Until *</label>
                    <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', now()->addDays(7)->format('Y-m-d')) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label for="advance_percentage" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Required Advance (%) *</label>
                    <input type="number" name="advance_percentage" id="advance_percentage" value="{{ old('advance_percentage', 40) }}" min="10" max="100" step="1" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500" oninput="calculateQuotation()">
                </div>

                <div>
                    <label for="notes" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Client Notes</label>
                    <textarea name="notes" id="notes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800" placeholder="Special requirements or package notes...">{{ old('notes', 'Aditya Utsav premium floral and Mandap decoration package.') }}</textarea>
                </div>

                <div>
                    <label for="terms" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Terms & Conditions</label>
                    <textarea name="terms" id="terms" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800">{{ old('terms', "1. 40% advance required to block dates.\n2. Balance settlement on event day.\n3. Transportation within Bihar included.") }}</textarea>
                </div>
            </div>

            <!-- Right: Calculations & Totals -->
            <div class="bg-slate-900 text-white rounded-2xl border border-slate-800 shadow-xl p-6 space-y-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-bold text-amber-400 pb-2 border-b border-slate-800">4. Live Grand Total Calculation</h3>
                    
                    <div class="space-y-3 pt-3 text-xs">
                        <div class="flex justify-between items-center text-slate-300">
                            <span>Item Subtotal:</span>
                            <span id="displaySubtotal" class="font-bold text-white text-sm">₹0</span>
                        </div>

                        <div class="flex justify-between items-center text-slate-300">
                            <span class="flex items-center gap-1">
                                <span>Discount Amount (₹):</span>
                            </span>
                            <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="0.01"
                                class="w-28 bg-slate-800 border border-slate-700 rounded-lg p-1.5 text-xs text-white text-right font-bold focus:outline-none focus:border-amber-500" oninput="calculateQuotation()">
                        </div>

                        <div class="flex justify-between items-center text-slate-300">
                            <span>Transportation / Extra Charges (₹):</span>
                            <input type="number" name="additional_charges" id="additional_charges" value="{{ old('additional_charges', 0) }}" min="0" step="0.01"
                                class="w-28 bg-slate-800 border border-slate-700 rounded-lg p-1.5 text-xs text-white text-right font-bold focus:outline-none focus:border-amber-500" oninput="calculateQuotation()">
                        </div>

                        <div class="flex justify-between items-center text-slate-300">
                            <span>GST / Tax (₹):</span>
                            <input type="number" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', 0) }}" min="0" step="0.01"
                                class="w-28 bg-slate-800 border border-slate-700 rounded-lg p-1.5 text-xs text-white text-right font-bold focus:outline-none focus:border-amber-500" oninput="calculateQuotation()">
                        </div>

                        <div class="pt-3 border-t border-slate-800 flex justify-between items-center">
                            <span class="text-sm font-bold text-amber-400">Grand Total:</span>
                            <span id="displayGrandTotal" class="text-xl font-extrabold text-amber-400">₹0</span>
                        </div>

                        <div class="p-3 bg-slate-800/80 rounded-xl border border-slate-700 space-y-1.5">
                            <div class="flex justify-between text-[11px] text-slate-300">
                                <span>Advance Required (<span id="displayAdvancePct">40</span>%):</span>
                                <span id="displayAdvance" class="font-bold text-emerald-400">₹0</span>
                            </div>
                            <div class="flex justify-between text-[11px] text-slate-400">
                                <span>Balance Payable:</span>
                                <span id="displayBalance" class="font-semibold text-slate-300">₹0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex flex-col sm:flex-row gap-3">
                    <button type="submit" name="send_now" value="0" class="flex-1 py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs transition border border-slate-700 text-center">
                        Save as Draft
                    </button>
                    <button type="submit" name="send_now" value="1" class="flex-1 py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow text-center">
                        Save & Send to Client
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemIndex = 100;

    function addItemRow() {
        itemIndex++;
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-row grid grid-cols-12 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200/70 items-center text-xs';
        row.innerHTML = `
            <div class="col-span-12 sm:col-span-5">
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Item Description</label>
                <input type="text" name="items[${itemIndex}][description]" value="Custom Floral Service" required
                    class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-semibold text-slate-800">
                <input type="hidden" name="items[${itemIndex}][item_type]" value="custom">
            </div>
            <div class="col-span-4 sm:col-span-2">
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Qty</label>
                <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" required
                    class="item-qty w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 text-center" oninput="calculateQuotation()">
            </div>
            <div class="col-span-4 sm:col-span-3">
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-0.5">Unit Price (₹)</label>
                <input type="number" name="items[${itemIndex}][unit_price]" value="5000" step="0.01" min="0" required
                    class="item-price w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 font-semibold" oninput="calculateQuotation()">
            </div>
            <div class="col-span-4 sm:col-span-2 flex items-center justify-between pt-4">
                <span class="item-total-display font-bold text-slate-900 text-xs">₹0</span>
                <button type="button" onclick="this.closest('.item-row').remove(); calculateQuotation();" class="text-rose-500 hover:text-rose-700 font-bold text-sm ml-2">&times;</button>
            </div>
        `;
        container.appendChild(row);
        calculateQuotation();
    }

    function calculateQuotation() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = qty * price;
            row.querySelector('.item-total-display').innerText = '₹' + total.toLocaleString('en-IN');
            subtotal += total;
        });

        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        const extra = parseFloat(document.getElementById('additional_charges').value) || 0;
        const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
        const advancePct = parseFloat(document.getElementById('advance_percentage').value) || 40;

        const grandTotal = Math.max(0, subtotal + extra + tax - discount);
        const advance = Math.round((grandTotal * advancePct) / 100);
        const balance = Math.max(0, grandTotal - advance);

        document.getElementById('displaySubtotal').innerText = '₹' + subtotal.toLocaleString('en-IN');
        document.getElementById('displayGrandTotal').innerText = '₹' + grandTotal.toLocaleString('en-IN');
        document.getElementById('displayAdvancePct').innerText = advancePct;
        document.getElementById('displayAdvance').innerText = '₹' + advance.toLocaleString('en-IN');
        document.getElementById('displayBalance').innerText = '₹' + balance.toLocaleString('en-IN');
    }

    document.addEventListener('DOMContentLoaded', calculateQuotation);
</script>
@endpush
@endsection
