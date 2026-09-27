@extends('layouts.admin')

@section('title', 'Edit Category - ' . $category->name)
@section('header', 'Edit Category: ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Categories</span>
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

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Category Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="tagline" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Tagline</label>
            <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $category->tagline) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <!-- IMAGE UPLOAD & PREVIEW -->
        <div class="space-y-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Category Cover Image</label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Current Image -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Image</span>
                        @if($category->safe_image)
                            <span class="text-[10px] text-slate-400 truncate max-w-[120px]">{{ basename($category->image_url) }}</span>
                        @endif
                    </div>
                    <div class="h-28 rounded-lg overflow-hidden border border-slate-200 bg-white flex items-center justify-center">
                        @if($category->safe_image)
                            <img src="{{ $category->safe_image }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-2 text-slate-400">
                                <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[11px] font-semibold">No Image</p>
                            </div>
                        @endif
                    </div>
                    @if($category->safe_image)
                        <div>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-red-700 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600 focus:ring-red-500">
                                <span>Remove image</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Replace Picker -->
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Upload New</span>
                    <div id="dropZone_cat" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-lg p-3 text-center transition cursor-pointer h-28 flex flex-col items-center justify-center">
                        <input type="file" name="image" id="cat_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div id="prompt_cat" class="space-y-1">
                            <svg class="w-6 h-6 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[11px] font-semibold text-slate-700">Choose Replacement</p>
                            <p class="text-[9px] text-slate-400">Max 5MB</p>
                        </div>

                        <div id="card_cat" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                            <img id="img_cat" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                            <p id="name_cat" class="font-bold text-[10px] text-slate-800 truncate max-w-[120px]"></p>
                            <button type="button" id="remove_cat" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="display_order" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', $category->display_order) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex items-center pt-5">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                    <span>Featured / Active Category</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Category</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('cat_image_input');
    const prompt = document.getElementById('prompt_cat');
    const card = document.getElementById('card_cat');
    const img = document.getElementById('img_cat');
    const nameEl = document.getElementById('name_cat');
    const removeBtn = document.getElementById('remove_cat');

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
