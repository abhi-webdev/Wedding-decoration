@extends('layouts.admin')

@section('title', 'Add New Offer')
@section('header', 'Create Promotional Offer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('admin.offers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Offers</span>
    </a>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 space-y-1">
            <p class="font-bold">Please correct the following errors:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="md:col-span-2">
                <label for="title" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Offer Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Early Bird Vivah Mahotsav 2026">
            </div>

            <div>
                <label for="coupon_code" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Coupon Code</label>
                <input type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 font-mono uppercase placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. BIHAR2026">
            </div>

            <div>
                <label for="badge_text" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Text (e.g. 15% OFF)</label>
                <input type="text" name="badge_text" id="badge_text" value="{{ old('badge_text') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="discount_type" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discount Type *</label>
                <select name="discount_type" id="discount_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                </select>
            </div>

            <div>
                <label for="discount_value" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discount Value *</label>
                <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="15">
            </div>

            <div>
                <label for="min_booking_amount" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Minimum Booking Amount (₹)</label>
                <input type="number" name="min_booking_amount" id="min_booking_amount" value="{{ old('min_booking_amount') }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="50000">
            </div>

            <div>
                <label for="max_discount" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Maximum Discount Cap (₹)</label>
                <input type="number" name="max_discount" id="max_discount" value="{{ old('max_discount') }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="15000">
            </div>

            <div>
                <label for="valid_from" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Valid From</label>
                <input type="date" name="valid_from" id="valid_from" value="{{ old('valid_from') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="valid_until" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Valid Until</label>
                <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <!-- OFFER BANNER IMAGE UPLOAD -->
            <div class="md:col-span-2 space-y-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider">Offer Banner / Promo Image (Optional)</label>
                <div id="dropZone_offer" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 rounded-2xl p-4 text-center transition cursor-pointer">
                    <input type="file" name="image" id="offer_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div id="prompt_offer" class="space-y-1">
                        <div class="w-8 h-8 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs text-slate-600"><span class="font-bold text-amber-600">Choose offer banner image</span> (Max 5MB)</p>
                    </div>

                    <div id="card_offer" class="hidden flex-col sm:flex-row items-center justify-between gap-3 p-2 bg-white border border-slate-200 rounded-lg relative z-20">
                        <div class="flex items-center gap-3">
                            <img id="img_offer" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                            <div class="text-left">
                                <p id="name_offer" class="font-bold text-xs text-slate-800 truncate max-w-xs"></p>
                                <p id="size_offer" class="text-[10px] text-slate-400"></p>
                            </div>
                        </div>
                        <button type="button" id="remove_offer" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-semibold">Remove</button>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Description</label>
                <textarea name="description" id="description" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="terms" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Offer Terms & Conditions</label>
                <textarea name="terms" id="terms" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('terms') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center gap-6 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Offer</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured on Homepage</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.offers.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save Offer</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('offer_image_input');
    const prompt = document.getElementById('prompt_offer');
    const card = document.getElementById('card_offer');
    const img = document.getElementById('img_offer');
    const nameEl = document.getElementById('name_offer');
    const sizeEl = document.getElementById('size_offer');
    const removeBtn = document.getElementById('remove_offer');

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                nameEl.textContent = file.name;
                sizeEl.textContent = (file.size / 1024).toFixed(1) + ' KB';
                prompt.classList.add('hidden');
                card.classList.remove('hidden');
                card.classList.add('flex');
            };
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        input.value = '';
        img.src = '#';
        prompt.classList.remove('hidden');
        card.classList.add('hidden');
        card.classList.remove('flex');
    });
});
</script>
@endsection
