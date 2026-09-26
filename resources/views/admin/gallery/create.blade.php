@extends('layouts.admin')

@section('title', 'Add Gallery Photo')
@section('header', 'Upload Gallery Photo')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Gallery</span>
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

    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label for="title" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Photo Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. Grand Jaimala Ceremony Setup at Patna">
        </div>

        <!-- GALLERY PHOTO UPLOAD -->
        <div class="space-y-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Upload Photograph *</label>
            <div id="dropZone_gal" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 rounded-2xl p-6 text-center transition cursor-pointer">
                <input type="file" name="image" id="gal_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                
                <div id="prompt_gal" class="space-y-1.5">
                    <div class="w-12 h-12 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-slate-700"><span class="font-bold text-amber-600">Click to upload photo</span> or drag and drop</p>
                    <p class="text-[11px] text-slate-400">JPG, JPEG, PNG, WEBP (Max 5MB)</p>
                </div>

                <div id="card_gal" class="hidden flex-col sm:flex-row items-center justify-between gap-3 p-3 bg-white border border-slate-200 rounded-xl relative z-20">
                    <div class="flex items-center gap-3">
                        <img id="img_gal" src="#" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                        <div class="text-left">
                            <p id="name_gal" class="font-bold text-xs text-slate-800 truncate max-w-[200px]"></p>
                            <p id="size_gal" class="text-[11px] text-slate-400"></p>
                        </div>
                    </div>
                    <button type="button" id="remove_gal" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold">Remove</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <label for="gallery_category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Category</label>
                <select name="gallery_category_id" id="gallery_category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('gallery_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="location" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Location / Venue</label>
                <input type="text" name="location" id="location" value="{{ old('location', 'Patna, Bihar') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Patna, Bihar">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <label for="event_type" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Event Type</label>
                <input type="text" name="event_type" id="event_type" value="{{ old('event_type', 'Wedding') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Wedding, Reception, Haldi, Mandap">
            </div>

            <div>
                <label for="sort_order" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label for="caption" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Caption / Notes</label>
            <textarea name="caption" id="caption" rows="2"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                placeholder="Details about floral setup, stage decor, or lighting...">{{ old('caption') }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active in Gallery</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured Showcase</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.gallery.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Upload Photo</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('gal_image_input');
    const prompt = document.getElementById('prompt_gal');
    const card = document.getElementById('card_gal');
    const img = document.getElementById('img_gal');
    const nameEl = document.getElementById('name_gal');
    const sizeEl = document.getElementById('size_gal');
    const removeBtn = document.getElementById('remove_gal');

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
