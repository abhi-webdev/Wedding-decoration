@extends('layouts.admin')

@section('title', 'Add New Add-on')
@section('header', 'Create Add-on Service')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.addons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Add-ons</span>
    </a>

    <form action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Add-on Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. Traditional Shehnai & Nagada Troupe">
        </div>

        <!-- ADDON IMAGE UPLOAD -->
        <div class="space-y-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Add-on Image (Optional)</label>
            <div id="dropZone_addon" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 rounded-2xl p-4 text-center transition cursor-pointer">
                <input type="file" name="image" id="addon_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                
                <div id="prompt_addon" class="space-y-1">
                    <div class="w-8 h-8 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-slate-600"><span class="font-bold text-amber-600">Choose add-on image</span> (Max 5MB)</p>
                </div>

                <div id="card_addon" class="hidden flex-col sm:flex-row items-center justify-between gap-3 p-2 bg-white border border-slate-200 rounded-lg relative z-20">
                    <div class="flex items-center gap-3">
                        <img id="img_addon" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                        <div class="text-left">
                            <p id="name_addon" class="font-bold text-xs text-slate-800 truncate max-w-xs"></p>
                            <p id="size_addon" class="text-[10px] text-slate-400"></p>
                        </div>
                    </div>
                    <button type="button" id="remove_addon" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-semibold">Remove</button>
                </div>
            </div>
        </div>

        <div>
            <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Slug (Optional)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Price (₹) *</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                    placeholder="15000">
            </div>

            <div>
                <label for="pricing_type" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Pricing Model *</label>
                <select name="pricing_type" id="pricing_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="fixed" {{ old('pricing_type') === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                    <option value="per_unit" {{ old('pricing_type') === 'per_unit' ? 'selected' : '' }}>Per Unit</option>
                    <option value="per_hour" {{ old('pricing_type') === 'per_hour' ? 'selected' : '' }}>Per Hour</option>
                    <option value="per_day" {{ old('pricing_type') === 'per_day' ? 'selected' : '' }}>Per Day</option>
                </select>
            </div>
        </div>

        <div>
            <label for="unit_label" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Unit Label (e.g. per set, per day, per pair)</label>
            <input type="text" name="unit_label" id="unit_label" value="{{ old('unit_label') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. per setup">
        </div>

        <div>
            <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="What this add-on entails...">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Add-on</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured Add-on</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.addons.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save Add-on</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('addon_image_input');
    const prompt = document.getElementById('prompt_addon');
    const card = document.getElementById('card_addon');
    const img = document.getElementById('img_addon');
    const nameEl = document.getElementById('name_addon');
    const sizeEl = document.getElementById('size_addon');
    const removeBtn = document.getElementById('remove_addon');

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
