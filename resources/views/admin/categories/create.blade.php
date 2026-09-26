@extends('layouts.admin')

@section('title', 'Add Category')
@section('header', 'Create Decoration Category')

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

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Category Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. Mandap & Vedi Setup">
        </div>

        <div>
            <label for="tagline" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Tagline / Short Hook</label>
            <input type="text" name="tagline" id="tagline" value="{{ old('tagline') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. Authentic Bihari Vedic Ceremonies">
        </div>

        <div>
            <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Slug (Optional)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="auto-generated-if-blank">
        </div>

        <div>
            <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="Brief summary of what this category offers...">{{ old('description') }}</textarea>
        </div>

        <!-- IMAGE UPLOAD -->
        <div class="space-y-2">
            <label class="block font-bold text-slate-700 uppercase tracking-wider text-xs">Category Cover Image</label>
            <div id="dropZone_cat" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 rounded-xl p-4 text-center transition cursor-pointer">
                <input type="file" name="image" id="cat_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                
                <div id="prompt_cat" class="space-y-1">
                    <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-slate-600"><span class="font-bold text-amber-600">Choose category image</span> or drag and drop</p>
                    <p class="text-[10px] text-slate-400">JPG, PNG, WEBP (Max 5MB)</p>
                </div>

                <div id="card_cat" class="hidden flex-col sm:flex-row items-center justify-between gap-3 p-2.5 bg-white border border-slate-200 rounded-lg relative z-20">
                    <div class="flex items-center gap-3">
                        <img id="img_cat" src="#" alt="Preview" class="w-12 h-12 object-cover rounded border border-slate-200 shadow-sm">
                        <div class="text-left">
                            <p id="name_cat" class="font-bold text-xs text-slate-800 truncate max-w-[200px]"></p>
                            <p id="size_cat" class="text-[10px] text-slate-400"></p>
                        </div>
                    </div>
                    <button type="button" id="remove_cat" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-semibold">Remove</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="display_order" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Display Order</label>
                <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex items-center pt-5">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_featured" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                    <span>Featured / Active Category</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save Category</button>
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
    const sizeEl = document.getElementById('size_cat');
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
