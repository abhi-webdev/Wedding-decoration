@extends('layouts.admin')

@section('title', 'Edit Decoration - ' . $decoration->name)
@section('header', 'Edit Decoration: ' . $decoration->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.decorations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Decorations</span>
        </a>

        <a href="{{ route('decorations.show', $decoration->slug) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-amber-600 hover:underline">
            <span>View Public Page</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

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

    <!-- MAIN DECORATION DETAILS & PRIMARY IMAGE FORM -->
    <form action="{{ route('admin.decorations.update', $decoration->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Decoration Theme Details</h3>
            <p class="text-xs text-slate-500">Edit pricing, style specifications, or replace the primary image.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="md:col-span-2">
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Decoration Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $decoration->name) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Category *</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $decoration->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Custom Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $decoration->slug) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="base_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Base Price (₹) *</label>
                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $decoration->base_price ?: $decoration->starting_price) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="discount_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discounted / Offer Price (₹)</label>
                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $decoration->discount_price) }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="location" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Service Location / City</label>
                <input type="text" name="location" id="location" value="{{ old('location', $decoration->location ?? 'Patna, Bihar') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="style" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Decoration Style</label>
                <input type="text" name="style" id="style" value="{{ old('style', $decoration->style ?? 'Traditional') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="primary_color" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Color Palette</label>
                <input type="text" name="primary_color" id="primary_color" value="{{ old('primary_color', $decoration->primary_color) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="guest_capacity" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Guest Capacity</label>
                <input type="text" name="guest_capacity" id="guest_capacity" value="{{ old('guest_capacity', $decoration->guest_capacity ?? '200–500 Guests') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="setup_time" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Estimated Setup Duration</label>
                <input type="text" name="setup_time" id="setup_time" value="{{ old('setup_time', $decoration->setup_time ?? '4–6 Hours') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="dimensions" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Stage Dimensions</label>
                <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $decoration->dimensions ?? '24x12 ft') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <!-- PRIMARY IMAGE UPLOAD & PREVIEW SECTION -->
            <div class="md:col-span-2 space-y-3 pt-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider">Primary Theme Image</label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Current Image Preview -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Current Image</span>
                            <span class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ basename($decoration->primary_image) }}</span>
                        </div>
                        <div class="h-40 rounded-lg overflow-hidden border border-slate-200 bg-white">
                            <img src="{{ $decoration->safe_primary_image }}" alt="{{ $decoration->name }}" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Replace Image Picker -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Upload New / Replace</span>
                        
                        <div id="dropZone_primary" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-xl p-4 text-center transition cursor-pointer min-h-[160px] flex flex-col items-center justify-center">
                            <input type="file" name="image" id="primary_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="uploadPrompt_primary" class="space-y-1">
                                <div class="w-8 h-8 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-700">Choose new image</p>
                                <p class="text-[10px] text-slate-400">JPG, PNG, WEBP (Max 5MB)</p>
                            </div>

                            <!-- Live New Image Preview -->
                            <div id="previewCard_primary" class="hidden flex-col items-center justify-center gap-2 relative z-20 w-full">
                                <img id="previewImg_primary" src="#" alt="New Preview" class="w-20 h-20 object-cover rounded-lg border border-slate-200 shadow-sm">
                                <div>
                                    <p id="previewName_primary" class="font-bold text-[11px] text-slate-800 truncate max-w-[180px]"></p>
                                    <p id="previewSize_primary" class="text-[10px] text-slate-400"></p>
                                </div>
                                <button type="button" id="removeBtn_primary" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-[10px] font-semibold">Cancel Selection</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="short_description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Short Summary</label>
                <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $decoration->short_description) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Detailed Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">{{ old('description', $decoration->description) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-wrap gap-6 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $decoration->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active & Published on Website</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $decoration->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured on Homepage</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $decoration->is_trending) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Trending / Popular Setup</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.decorations.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save Changes</button>
        </div>
    </form>

    <!-- MULTIPLE GALLERY IMAGES SECTION -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Decoration Gallery Images</h3>
                <p class="text-xs text-slate-500">Upload additional angles, closeup floral shots, and client setup photos.</p>
            </div>
            <span class="px-2.5 py-1 bg-amber-100 text-amber-900 rounded-full text-xs font-bold">{{ $decoration->images->count() }} Images</span>
        </div>

        <!-- Extra Image Upload Form -->
        <form action="{{ route('admin.decorations.images.upload', $decoration->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
            @csrf
            <div class="text-xs font-bold uppercase tracking-wider text-slate-700">Upload New Gallery Photo</div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Select Image File (JPG, PNG, WEBP, Max 5MB) *</label>
                    <input type="file" name="gallery_image" id="gallery_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" required
                        class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Caption / Angle Description</label>
                    <input type="text" name="caption" placeholder="e.g. Side Angle, Mandap Floral Pillars"
                        class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <!-- Preview for extra gallery upload -->
            <div id="galleryPreviewBox" class="hidden items-center gap-3 p-2 bg-white rounded-lg border border-slate-200">
                <img id="galleryPreviewImg" src="#" alt="Gallery Preview" class="w-12 h-12 object-cover rounded border border-slate-200">
                <div class="text-xs">
                    <p id="galleryPreviewName" class="font-bold text-slate-800"></p>
                    <p id="galleryPreviewSize" class="text-slate-400 text-[10px]"></p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg text-xs shadow flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Upload to Gallery</span>
                </button>
            </div>
        </form>

        <!-- Existing Gallery Images Grid -->
        @if($decoration->images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($decoration->images as $img)
                    <div class="group relative bg-slate-50 border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow transition">
                        <div class="h-32 bg-slate-100 overflow-hidden">
                            <img src="{{ str_starts_with($img->image_url, 'http') ? $img->image_url : asset($img->image_url) }}" alt="{{ $img->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-2.5 flex items-center justify-between text-xs bg-white">
                            <span class="truncate text-[11px] text-slate-700 font-medium max-w-[110px]" title="{{ $img->caption }}">{{ $img->caption ?: 'Gallery Image' }}</span>
                            <form action="{{ route('admin.decorations.images.delete', $img->id) }}" method="POST" onsubmit="return confirm('Remove this image from the gallery?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Delete Image">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic">No extra gallery images uploaded yet for this decoration.</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Primary Image Preview
    const primaryInput = document.getElementById('primary_image_input');
    const primaryPrompt = document.getElementById('uploadPrompt_primary');
    const primaryCard = document.getElementById('previewCard_primary');
    const primaryImg = document.getElementById('previewImg_primary');
    const primaryName = document.getElementById('previewName_primary');
    const primarySize = document.getElementById('previewSize_primary');
    const primaryRemoveBtn = document.getElementById('removeBtn_primary');

    if (primaryInput) {
        primaryInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size exceeds 5MB limit.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    primaryImg.src = e.target.result;
                    primaryName.textContent = file.name;
                    primarySize.textContent = (file.size / 1024 > 1024) 
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' 
                        : (file.size / 1024).toFixed(1) + ' KB';
                    primaryPrompt.classList.add('hidden');
                    primaryCard.classList.remove('hidden');
                    primaryCard.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }
        });

        primaryRemoveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            primaryInput.value = '';
            primaryImg.src = '#';
            primaryPrompt.classList.remove('hidden');
            primaryCard.classList.add('hidden');
            primaryCard.classList.remove('flex');
        });
    }

    // Gallery Image Preview
    const galleryInput = document.getElementById('gallery_image_input');
    const galleryBox = document.getElementById('galleryPreviewBox');
    const galleryImg = document.getElementById('galleryPreviewImg');
    const galleryName = document.getElementById('galleryPreviewName');
    const gallerySize = document.getElementById('galleryPreviewSize');

    if (galleryInput) {
        galleryInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('Gallery image exceeds 5MB limit.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    galleryImg.src = e.target.result;
                    galleryName.textContent = file.name;
                    gallerySize.textContent = (file.size / 1024).toFixed(1) + ' KB';
                    galleryBox.classList.remove('hidden');
                    galleryBox.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
