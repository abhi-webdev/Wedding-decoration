@extends('layouts.admin')

@section('title', 'Edit Add-on - ' . $addon->name)
@section('header', 'Edit Add-on: ' . $addon->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.addons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Add-ons</span>
    </a>

    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Add-on Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $addon->name) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <!-- ADDON IMAGE & REPLACEMENT -->
        <div class="space-y-2 pt-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Add-on Image</label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Current Image -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Image</span>
                    <div class="h-28 rounded-lg overflow-hidden border border-slate-200 bg-white flex items-center justify-center">
                        @if($addon->display_image)
                            <img src="{{ $addon->display_image }}" alt="{{ $addon->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-3 text-slate-400 flex flex-col items-center justify-center">
                                <svg class="w-8 h-8 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[11px] font-medium">No image uploaded</span>
                            </div>
                        @endif
                    </div>
                    @if($addon->display_image)
                        <label class="inline-flex items-center gap-1.5 text-xs text-red-600 cursor-pointer pt-1">
                            <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600 focus:ring-red-500 border-slate-300">
                            <span>Remove current image</span>
                        </label>
                    @endif
                </div>

                <!-- Replace Image -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Upload / Replace Image</span>
                    <div id="dropZone_addon" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-lg p-3 text-center transition cursor-pointer h-28 flex flex-col items-center justify-center">
                        <input type="file" name="image" id="addon_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div id="prompt_addon" class="space-y-1">
                            <svg class="w-6 h-6 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[11px] font-semibold text-slate-700">Choose New Image</p>
                            <p class="text-[9px] text-slate-400">Max 5MB</p>
                        </div>

                        <div id="card_addon" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                            <img id="img_addon" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                            <p id="name_addon" class="font-bold text-[10px] text-slate-800 truncate max-w-[140px]"></p>
                            <button type="button" id="remove_addon" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $addon->slug) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Price (₹) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $addon->price) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="pricing_type" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Pricing Model *</label>
                <select name="pricing_type" id="pricing_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="fixed" {{ old('pricing_type', $addon->pricing_type) === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                    <option value="per_unit" {{ old('pricing_type', $addon->pricing_type) === 'per_unit' ? 'selected' : '' }}>Per Unit</option>
                    <option value="per_hour" {{ old('pricing_type', $addon->pricing_type) === 'per_hour' ? 'selected' : '' }}>Per Hour</option>
                    <option value="per_day" {{ old('pricing_type', $addon->pricing_type) === 'per_day' ? 'selected' : '' }}>Per Day</option>
                </select>
            </div>
        </div>

        <div>
            <label for="unit_label" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Unit Label</label>
            <input type="text" name="unit_label" id="unit_label" value="{{ old('unit_label', $addon->unit_label) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">{{ old('description', $addon->description) }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Add-on</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $addon->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured Add-on</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.addons.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Add-on</button>
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
