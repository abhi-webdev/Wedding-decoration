@extends('layouts.admin')

@section('title', 'Add New Decoration')
@section('header', 'Create Decoration Theme')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.decorations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Decorations</span>
        </a>
    </div>

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

    <form action="{{ route('admin.decorations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Theme Details</h3>
            <p class="text-xs text-slate-500">Provide details for the Bihar wedding decoration theme.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="md:col-span-2">
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Decoration Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Royal Mithila Mandap Setup">
            </div>

            <div>
                <label for="category_id" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Category *</label>
                <select name="category_id" id="category_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Custom Slug (Optional)</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="auto-generated-if-blank">
            </div>

            <div>
                <label for="base_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Base Price (₹) *</label>
                <input type="number" name="base_price" id="base_price" value="{{ old('base_price', old('price')) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="75000">
            </div>

            <div>
                <label for="discount_price" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discounted / Offer Price (₹)</label>
                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price') }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="68000">
            </div>

            <div>
                <label for="location" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Service Location / City</label>
                <input type="text" name="location" id="location" value="{{ old('location', 'Patna, Bihar') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Patna, Muzaffarpur, Gaya">
            </div>

            <div>
                <label for="style" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Decoration Style</label>
                <input type="text" name="style" id="style" value="{{ old('style', 'Traditional Bihari') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Traditional, Floral Luxury, Royal Heritage">
            </div>

            <div>
                <label for="primary_color" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Color Palette</label>
                <input type="text" name="primary_color" id="primary_color" value="{{ old('primary_color', 'Marigold Yellow & Royal Red') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="e.g. Gold & Crimson, Pastel Pink">
            </div>

            <div>
                <label for="guest_capacity" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Guest Capacity</label>
                <input type="text" name="guest_capacity" id="guest_capacity" value="{{ old('guest_capacity', '200–500 Guests') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="setup_time" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Estimated Setup Duration</label>
                <input type="text" name="setup_time" id="setup_time" value="{{ old('setup_time', '4–6 Hours') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="dimensions" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Stage / Setup Dimensions</label>
                <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', '24x14 ft') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <!-- PRIMARY IMAGE UPLOAD SECTION -->
            <div class="md:col-span-2 space-y-2">
                <label class="block font-bold text-slate-700 uppercase tracking-wider">Primary Theme Image *</label>
                <div id="dropZone_primary" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-slate-50/70 hover:bg-amber-50/30 rounded-2xl p-6 text-center transition cursor-pointer">
                    <input type="file" name="image" id="primary_image_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div id="uploadPrompt_primary" class="space-y-2">
                        <div class="w-12 h-12 mx-auto rounded-full bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="text-xs text-slate-600">
                            <span class="font-bold text-amber-600">Click to upload image</span> or drag and drop here
                        </div>
                        <p class="text-[11px] text-slate-400">JPG, JPEG, PNG, WEBP (Max 5 MB)</p>
                    </div>

                    <!-- Live Image Preview Card -->
                    <div id="previewCard_primary" class="hidden flex-col sm:flex-row items-center justify-between gap-4 p-3 bg-white border border-slate-200 rounded-xl relative z-20">
                        <div class="flex items-center gap-3">
                            <img id="previewImg_primary" src="#" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-slate-200 shadow-sm">
                            <div class="text-left">
                                <p id="previewName_primary" class="font-bold text-xs text-slate-800 truncate max-w-xs"></p>
                                <p id="previewSize_primary" class="text-[11px] text-slate-400"></p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-800 rounded">Ready to upload</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="primary_image_input" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold cursor-pointer">Change</label>
                            <button type="button" id="removeBtn_primary" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="short_description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Short Summary</label>
                <input type="text" name="short_description" id="short_description" value="{{ old('short_description') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="Brief 1-line hook about this decoration theme...">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Detailed Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                    placeholder="Detailed description of flowers, lighting, fabric, rituals supported...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-wrap gap-6 text-xs font-semibold text-slate-700">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active & Published on Website</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured on Homepage</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_popular" value="1" class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Popular / Best Seller</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.decorations.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save & Publish Decoration</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('primary_image_input');
    const prompt = document.getElementById('uploadPrompt_primary');
    const card = document.getElementById('previewCard_primary');
    const img = document.getElementById('previewImg_primary');
    const nameEl = document.getElementById('previewName_primary');
    const sizeEl = document.getElementById('previewSize_primary');
    const removeBtn = document.getElementById('removeBtn_primary');

    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validate size (5MB = 5 * 1024 * 1024 bytes)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit. Please choose a smaller image.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                nameEl.textContent = file.name;
                sizeEl.textContent = (file.size / 1024 > 1024) 
                    ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' 
                    : (file.size / 1024).toFixed(1) + ' KB';
                
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
