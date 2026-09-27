@extends('layouts.admin')

@section('title', 'Manage Gallery')
@section('header', 'Wedding Gallery Portfolio')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.gallery.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, location, event type..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="category_id" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">Filter</button>
        </form>

        <a href="{{ route('admin.gallery.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shrink-0 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Photo</span>
        </a>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($galleryItems as $item)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="relative aspect-video bg-slate-100 overflow-hidden group flex items-center justify-center">
                    @if($item->display_image)
                        <img src="{{ $item->display_image }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="p-4 text-center text-slate-400 flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px]">No image uploaded</span>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 flex gap-1">
                        @if($item->is_featured)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-slate-950 shadow">Featured</span>
                        @endif
                    </div>
                </div>

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-xs">{{ $item->title }}</h4>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 mt-1">
                            <span>{{ $item->category->name ?? 'Ceremony' }}</span>
                            <span class="text-amber-700 font-medium">{{ $item->location ?? 'Bihar' }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $item->is_active ? 'Active' : 'Hidden' }}
                        </span>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.gallery.edit', $item->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 text-xs font-semibold transition" title="Edit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-700 text-xs font-semibold transition" title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 text-xs">
                No gallery photos uploaded yet.
            </div>
        @endforelse
    </div>

    @if($galleryItems->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
            {{ $galleryItems->links() }}
        </div>
    @endif
</div>
@endsection
