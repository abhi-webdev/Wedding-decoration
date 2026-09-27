@extends('layouts.admin')

@section('title', 'Edit Package - ' . $package->name)
@section('header', 'Edit Wedding Package: ' . $package->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Packages</span>
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

    <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Edit Package Details</h3>
            <p class="text-xs text-slate-500">Update pricing, tier, cover image, included themes, and highlights.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Package Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="tier" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tier *</label>
                <select name="tier" id="tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="Silver" {{ old('tier', $package->tier) === 'Silver' ? 'selected' : '' }}>Silver</option>
                    <option value="Gold" {{ old('tier', $package->tier) === 'Gold' ? 'selected' : '' }}>Gold</option>
                    <option value="Diamond" {{ old('tier', $package->tier) === 'Diamond' ? 'selected' : '' }}>Diamond</option>
                    <option value="Royal" {{ old('tier', $package->tier) === 'Royal' ? 'selected' : '' }}>Royal</option>
                    <option value="Custom" {{ old('tier', $package->tier) === 'Custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <div>
                <label for="base_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Package Price (₹) *</label>
                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $package->base_price ?: $package->starting_price) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="discount_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discounted / Offer Price (₹)</label>
                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $package->discount_price) }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="badge" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Text</label>
                <input type="text" name="badge" id="badge" value="{{ old('badge', $package->badge) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="guest_capacity" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Guest Capacity</label>
                <input type="text" name="guest_capacity" id="guest_capacity" value="{{ old('guest_capacity', $package->guest_capacity) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <!-- PACKAGE IMAGE & REPLACEMENT -->
            <div class="md:col-span-2 space-y-2 pt-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider">Package Cover Image</label>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Current Image -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Image</span>
                        <div class="h-32 rounded-lg overflow-hidden border border-slate-200 bg-white flex items-center justify-center">
                            @if($package->display_image)
                                <img src="{{ $package->display_image }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-3 text-slate-400 flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-[11px] font-medium">No image uploaded yet</span>
                                </div>
                            @endif
                        </div>
                        @if($package->display_image)
                            <label class="inline-flex items-center gap-1.5 text-xs text-red-600 cursor-pointer pt-1">
                                <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600 focus:ring-red-500 border-slate-300">
                                <span>Remove current image</span>
                            </label>
                        @endif
                    </div>

                    <!-- Replace Image -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Upload / Replace Image</span>
                        <div id="dropZone_pkg" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-lg p-3 text-center transition cursor-pointer h-32 flex flex-col items-center justify-center">
                            <input type="file" name="image" id="pkg_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="prompt_pkg" class="space-y-1">
                                <svg class="w-6 h-6 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[11px] font-semibold text-slate-700">Choose Replacement</p>
                                <p class="text-[9px] text-slate-400">Max 5MB</p>
                            </div>

                            <div id="card_pkg" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                                <img id="img_pkg" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                                <p id="name_pkg" class="font-bold text-[10px] text-slate-800 truncate max-w-[140px]"></p>
                                <button type="button" id="remove_pkg" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="short_description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Short Description</label>
                <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $package->short_description) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Associate Decoration Themes</label>
                @php
                    $selectedDecIds = $package->decorations->pluck('id')->toArray();
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-48 overflow-y-auto">
                    @foreach($decorations as $dec)
                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="decorations[]" value="{{ $dec->id }}" {{ in_array($dec->id, $selectedDecIds) ? 'checked' : '' }} class="rounded bg-white border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span class="truncate">{{ $dec->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center gap-6 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Package</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $package->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured on Homepage</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.packages.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Package</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('pkg_image_input');
    const prompt = document.getElementById('prompt_pkg');
    const card = document.getElementById('card_pkg');
    const img = document.getElementById('img_pkg');
    const nameEl = document.getElementById('name_pkg');
    const removeBtn = document.getElementById('remove_pkg');

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
