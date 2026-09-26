@extends('layouts.admin')

@section('title', 'Add New Package')
@section('header', 'Create Wedding Package')

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

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Package Details</h3>
            <p class="text-xs text-slate-500">Create a bundled multi-ceremony Bihar wedding package.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Package Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Royal Magadh Complete Wedding Package">
            </div>

            <div>
                <label for="tier" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tier *</label>
                <select name="tier" id="tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="Silver" {{ old('tier') === 'Silver' ? 'selected' : '' }}>Silver</option>
                    <option value="Gold" {{ old('tier') === 'Gold' ? 'selected' : '' }}>Gold</option>
                    <option value="Diamond" {{ old('tier') === 'Diamond' ? 'selected' : '' }}>Diamond</option>
                    <option value="Royal" {{ old('tier', 'Royal') === 'Royal' ? 'selected' : '' }}>Royal</option>
                    <option value="Custom" {{ old('tier') === 'Custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <div>
                <label for="base_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Package Price (₹) *</label>
                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', old('price')) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="250000">
            </div>

            <div>
                <label for="discount_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discounted / Offer Price (₹)</label>
                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price') }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="225000">
            </div>

            <div>
                <label for="badge" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Badge Text (e.g. Most Popular, 15% OFF)</label>
                <input type="text" name="badge" id="badge" value="{{ old('badge') }}" placeholder="e.g. Most Popular"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="guest_capacity" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Guest Capacity</label>
                <input type="text" name="guest_capacity" id="guest_capacity" value="{{ old('guest_capacity', '500+ Guests') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <!-- PACKAGE IMAGE UPLOAD -->
            <div class="md:col-span-2 space-y-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider">Package Cover Image</label>
                <div id="dropZone_pkg" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 rounded-2xl p-5 text-center transition cursor-pointer">
                    <input type="file" name="image" id="pkg_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div id="prompt_pkg" class="space-y-1">
                        <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="text-xs text-slate-600"><span class="font-bold text-amber-600">Choose package image</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400">JPG, PNG, WEBP (Max 5MB)</p>
                    </div>

                    <div id="card_pkg" class="hidden flex-col sm:flex-row items-center justify-between gap-3 p-2.5 bg-white border border-slate-200 rounded-xl relative z-20">
                        <div class="flex items-center gap-3">
                            <img id="img_pkg" src="#" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-200 shadow-sm">
                            <div class="text-left">
                                <p id="name_pkg" class="font-bold text-xs text-slate-800 truncate max-w-xs"></p>
                                <p id="size_pkg" class="text-[10px] text-slate-400"></p>
                            </div>
                        </div>
                        <button type="button" id="remove_pkg" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold">Remove</button>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="short_description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Short Description</label>
                <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="Full details regarding this wedding package...">{{ old('description') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-2">Associate Decoration Themes</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-48 overflow-y-auto">
                    @foreach($decorations as $dec)
                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                            <input type="checkbox" name="decorations[]" value="{{ $dec->id }}" class="rounded bg-white border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span class="truncate">{{ $dec->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center gap-6 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Package</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured on Homepage</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.packages.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save Package</button>
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
    const sizeEl = document.getElementById('size_pkg');
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
