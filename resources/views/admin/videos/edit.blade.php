@extends('layouts.admin')

@section('title', 'Edit Wedding Video - ' . $video->title)
@section('header', 'Edit Wedding Reel: ' . $video->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Back Link -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.videos.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Video Gallery</span>
        </a>

        <a href="{{ route('videos.show', $video->slug) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-amber-600 hover:underline">
            <span>View Public Player</span>
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
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

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Section: Basic Information -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">1. Video Details &amp; Categorization</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-xs font-semibold text-slate-700 mb-1">Video Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label for="video_type" class="block text-xs font-semibold text-slate-700 mb-1">Video Format / Type *</label>
                        <select name="video_type" id="video_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                            <option value="reel" {{ old('video_type', $video->video_type) === 'reel' ? 'selected' : '' }}>Reel (9:16 Vertical)</option>
                            <option value="short" {{ old('video_type', $video->video_type) === 'short' ? 'selected' : '' }}>Short Clip</option>
                            <option value="event_highlight" {{ old('video_type', $video->video_type) === 'event_highlight' ? 'selected' : '' }}>Event Highlight (16:9 Landscape)</option>
                            <option value="portfolio" {{ old('video_type', $video->video_type) === 'portfolio' ? 'selected' : '' }}>Decor Portfolio Showcase</option>
                            <option value="behind_the_scenes" {{ old('video_type', $video->video_type) === 'behind_the_scenes' ? 'selected' : '' }}>Behind the Scenes Setup</option>
                        </select>
                    </div>

                    <div>
                        <label for="event_type" class="block text-xs font-semibold text-slate-700 mb-1">Ritual / Event Ceremony *</label>
                        <select name="event_type" id="event_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                            <option value="jaimala" {{ old('event_type', $video->event_type) === 'jaimala' ? 'selected' : '' }}>Jaimala Stage</option>
                            <option value="mandap" {{ old('event_type', $video->event_type) === 'mandap' ? 'selected' : '' }}>Vedic Mandap</option>
                            <option value="haldi" {{ old('event_type', $video->event_type) === 'haldi' ? 'selected' : '' }}>Haldi Ceremony</option>
                            <option value="mehendi" {{ old('event_type', $video->event_type) === 'mehendi' ? 'selected' : '' }}>Mehendi Night</option>
                            <option value="sangeet" {{ old('event_type', $video->event_type) === 'sangeet' ? 'selected' : '' }}>Sangeet Ceremony</option>
                            <option value="reception" {{ old('event_type', $video->event_type) === 'reception' ? 'selected' : '' }}>Grand Reception</option>
                            <option value="general" {{ old('event_type', $video->event_type) === 'general' ? 'selected' : '' }}>General Wedding Highlight</option>
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-slate-700 mb-1">Decoration Category (Optional Link)</label>
                        <select name="category_id" id="category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                            <option value="">-- No Specific Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $video->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-semibold text-slate-700 mb-1">City / Venue Location *</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $video->location) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label for="duration" class="block text-xs font-semibold text-slate-700 mb-1">Duration (MM:SS)</label>
                        <input type="text" name="duration" id="duration" value="{{ old('duration', $video->duration) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-semibold text-slate-700 mb-1">Display Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $video->sort_order) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="short_description" class="block text-xs font-semibold text-slate-700 mb-1">Short One-Line Summary</label>
                        <input type="text" name="short_description" id="short_description" value="{{ old('short_description', $video->short_description) }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-xs font-semibold text-slate-700 mb-1">Full Ceremony Details &amp; Highlights</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('description', $video->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Section: Media Uploads -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">2. Video &amp; Thumbnail Media</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Video Source -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h4 class="font-bold text-xs text-slate-800">Current Video: <span class="font-mono text-[11px] text-slate-500 truncate block">{{ $video->video_path ?: 'Default Demo Video' }}</span></h4>
                        
                        <div id="dropZone_vid" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-xl p-4 text-center transition cursor-pointer">
                            <input type="file" name="video_file" id="video_file_input" accept="video/mp4,video/webm,video/quicktime,video/ogg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="prompt_vid" class="space-y-1">
                                <svg class="w-6 h-6 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <p class="text-xs font-semibold text-slate-700">Choose Replacement Video</p>
                                <p class="text-[10px] text-slate-400">MP4, WEBM, MOV (Max 100MB)</p>
                            </div>

                            <div id="card_vid" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-[10px] font-bold">New Video Chosen</span>
                                <p id="name_vid" class="font-bold text-[11px] text-slate-800 truncate max-w-[200px]"></p>
                                <p id="size_vid" class="text-[10px] text-slate-400"></p>
                                <button type="button" id="remove_vid" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail Source -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-16 rounded bg-stone-900 overflow-hidden shrink-0 border border-slate-200">
                                <img src="{{ $video->safe_thumbnail_url }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-800">Poster / Cover Image</h4>
                                <span class="text-[10px] text-slate-400">Current Thumbnail Preview</span>
                            </div>
                        </div>

                        <div id="dropZone_vthumb" class="relative border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white rounded-xl p-3 text-center transition cursor-pointer">
                            <input type="file" name="thumbnail_file" id="vthumb_file_input" accept="image/jpeg,image/png,image/jpg,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="prompt_vthumb" class="space-y-1">
                                <svg class="w-5 h-5 mx-auto text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-[11px] font-semibold text-slate-700">Replace Thumbnail Image</p>
                                <p class="text-[9px] text-slate-400">Max 5MB</p>
                            </div>

                            <div id="card_vthumb" class="hidden flex-col items-center justify-center gap-1 relative z-20 w-full">
                                <img id="img_vthumb" src="#" alt="Thumbnail Preview" class="w-12 h-14 object-cover rounded border border-slate-200 shadow-sm">
                                <p id="name_vthumb" class="font-bold text-[10px] text-slate-800 truncate max-w-[150px]"></p>
                                <button type="button" id="remove_vthumb" class="text-[10px] text-red-600 hover:underline">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section: Visibility & Flags -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">3. Visibility Settings</h3>

                <div class="flex flex-wrap gap-6 text-xs text-slate-700">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_homepage" value="1" {{ old('is_homepage', $video->is_homepage) ? 'checked' : '' }} class="rounded text-amber-500 focus:ring-amber-400 w-4 h-4">
                        <span class="font-semibold">Display on Homepage Reels Section</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $video->is_featured) ? 'checked' : '' }} class="rounded text-amber-500 focus:ring-amber-400 w-4 h-4">
                        <span class="font-semibold">Featured Badge</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }} class="rounded text-amber-500 focus:ring-amber-400 w-4 h-4">
                        <span class="font-semibold">Active &amp; Published</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.videos.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm">
                    Update Wedding Video
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Video File Selection Preview
    const vidInput = document.getElementById('video_file_input');
    const vidPrompt = document.getElementById('prompt_vid');
    const vidCard = document.getElementById('card_vid');
    const vidName = document.getElementById('name_vid');
    const vidSize = document.getElementById('size_vid');
    const vidRemove = document.getElementById('remove_vid');

    if (vidInput) {
        vidInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 100 * 1024 * 1024) {
                    alert('Video file exceeds 100MB limit.');
                    this.value = '';
                    return;
                }
                vidName.textContent = file.name;
                vidSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                vidPrompt.classList.add('hidden');
                vidCard.classList.remove('hidden');
                vidCard.classList.add('flex');
            }
        });

        vidRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            vidInput.value = '';
            vidPrompt.classList.remove('hidden');
            vidCard.classList.add('hidden');
            vidCard.classList.remove('flex');
        });
    }

    // Thumbnail File Selection Preview
    const thumbInput = document.getElementById('vthumb_file_input');
    const thumbPrompt = document.getElementById('prompt_vthumb');
    const thumbCard = document.getElementById('card_vthumb');
    const thumbImg = document.getElementById('img_vthumb');
    const thumbName = document.getElementById('name_vthumb');
    const thumbRemove = document.getElementById('remove_vthumb');

    if (thumbInput) {
        thumbInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('Thumbnail image exceeds 5MB limit.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbImg.src = e.target.result;
                    thumbName.textContent = file.name;
                    thumbPrompt.classList.add('hidden');
                    thumbCard.classList.remove('hidden');
                    thumbCard.classList.add('flex');
                };
                reader.readAsDataURL(file);
            }
        });

        thumbRemove.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            thumbInput.value = '';
            thumbImg.src = '#';
            thumbPrompt.classList.remove('hidden');
            thumbCard.classList.add('hidden');
            thumbCard.classList.remove('flex');
        });
    }
});
</script>
@endsection
