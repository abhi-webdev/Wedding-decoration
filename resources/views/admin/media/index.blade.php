@extends('layouts.admin')

@section('title', 'Media Library')
@section('header', 'Media & Asset Library')

@section('content')
<div class="space-y-6">
    <!-- Top Stats & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="text-base font-bold text-slate-900">Uploaded Media Assets</h3>
            <p class="text-xs text-slate-500">Manage all decoration photos, package covers, gallery items, and videos stored on the server.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-3.5 py-1.5 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-900">
                <span>Total Files:</span> <span class="text-amber-600">{{ $totalCount }}</span>
            </div>
            <div class="px-3.5 py-1.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-700">
                <span>Disk Size:</span> <span class="text-slate-900">{{ $formattedTotalSize }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-xs font-semibold text-red-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Folder Pills -->
            <div class="flex flex-wrap items-center gap-1.5 w-full md:w-auto">
                @foreach($folders as $folderKey => $folderLabel)
                    <a href="{{ route('admin.media.index', ['folder' => $folderKey, 'search' => request('search')]) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $selectedFolder === $folderKey ? 'bg-slate-900 text-amber-400 font-bold shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-600' }}">
                        {{ $folderLabel }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.media.index') }}" method="GET" class="w-full md:w-64 flex items-center gap-2">
                <input type="hidden" name="folder" value="{{ $selectedFolder }}">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search filename..."
                        class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Grid -->
    @if(count($allFiles) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($allFiles as $file)
                <div class="group relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <!-- Media Preview Area -->
                    <div class="h-36 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                        @if($file['is_image'])
                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @elseif($file['is_video'])
                            <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-amber-400 p-2 text-center">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span class="text-[10px] uppercase font-bold text-slate-300">Video</span>
                            </div>
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold uppercase text-xs">
                                .{{ $file['extension'] }}
                            </div>
                        @endif

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                            <a href="{{ $file['url'] }}" target="_blank" class="p-1.5 rounded-lg bg-white/90 text-slate-800 hover:bg-white text-xs font-semibold" title="Open Full Preview">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Media Meta Details -->
                    <div class="p-3 space-y-1.5 bg-white text-xs">
                        <p class="font-bold text-slate-800 truncate text-[11px]" title="{{ $file['name'] }}">{{ $file['name'] }}</p>
                        
                        <div class="flex items-center justify-between text-[10px] text-slate-400">
                            <span class="px-1.5 py-0.5 rounded bg-slate-100 font-semibold text-slate-600 capitalize">{{ str_replace('-', ' ', $file['folder']) }}</span>
                            <span>{{ $file['formatted_size'] }}</span>
                        </div>

                        <!-- Card Bottom Actions -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $file['path'] }}'); alert('Path copied: {{ $file['path'] }}');" class="text-[10px] text-amber-600 hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Copy Path</span>
                            </button>

                            <form action="{{ route('admin.media.destroy') }}" method="POST" onsubmit="return confirm('Delete this media file permanently?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="path" value="{{ $file['path'] }}">
                                <button type="submit" class="text-red-500 hover:text-red-700 p-0.5" title="Delete File">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-2xl border border-slate-200/80 shadow-sm space-y-3">
            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h4 class="font-bold text-sm text-slate-800">No media files found</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">No files match your current folder or search filter in the public uploads directory.</p>
        </div>
    @endif
</div>
@endsection
