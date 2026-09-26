@extends('layouts.admin')

@section('title', 'Edit Photo - ' . $item->title)
@section('header', 'Edit Gallery Photo: ' . $item->title)

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

    <form action="{{ route('admin.gallery.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Photo Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <!-- GALLERY PHOTO UPLOAD & REPLACEMENT -->
        <div class="space-y-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Photograph File</label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Current Photo -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Photo</span>
                    <div class="h-32 rounded-lg overflow-hidden border border-slate-200 bg-white">
                        <img src="{{ $item->display_image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Replace Photo -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Upload New</span>
                    <div id="dropZone_gal" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-lg p-3 text-center transition cursor-pointer h-32 flex flex-col items-center justify-center">
                        <input type="file" name="image" id="gal_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div id="prompt_gal" class="space-y-1">
                            <svg class="w-6 h-6 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[11px] font-semibold text-slate-700">Choose Replacement</p>
                            <p class="text-[9px] text-slate-400">Max 5MB</p>
                        </div>

                        <div id="card_gal" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                            <img id="img_gal" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                            <p id="name_gal" class="font-bold text-[10px] text-slate-800 truncate max-w-[140px]"></p>
                            <button type="button" id="remove_gal" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <label for="gallery_category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Category</label>
                <select name="gallery_category_id" id="gallery_category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('gallery_category_id', $item->gallery_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="location" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Location / Venue</label>
                <input type="text" name="location" id="location" value="{{ old('location', $item->location) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <label for="event_type" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Event Type</label>
                <input type="text" name="event_type" id="event_type" value="{{ old('event_type', $item->event_type) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="sort_order" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $item->sort_order) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>
        </div>

        <div>
            <label for="caption" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Caption / Notes</label>
            <textarea name="caption" id="caption" rows="2"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('caption', $item->caption) }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active in Gallery</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured Showcase</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.gallery.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Photo</button>
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
