@extends('layouts.admin')

@section('title', 'Wedding Videos & Reels Management')
@section('header', 'Wedding Videos & Reels')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Metrics Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Video &amp; Reel Portfolio</h2>
            <p class="text-xs text-slate-500 mt-0.5">Manage short transformation reels, YouTube/MP4 uploads, and homepage featured highlights.</p>
        </div>
        <a href="{{ route('admin.videos.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            + Upload New Reel / Video
        </a>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs text-slate-500 font-medium">Total Videos</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ $counts['all'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs text-slate-500 font-medium">Active Videos</span>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ $counts['active'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs text-slate-500 font-medium">On Homepage</span>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ $counts['homepage'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center">
            <span class="text-xs text-slate-500 font-medium">Featured</span>
            <p class="text-xl font-bold text-purple-600 mt-1">{{ $counts['featured'] }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.videos.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or location..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-amber-500">
            </div>
            <div>
                <select name="event_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="all">All Ceremonies</option>
                    <option value="jaimala" {{ request('event_type') === 'jaimala' ? 'selected' : '' }}>Jaimala Stage</option>
                    <option value="mandap" {{ request('event_type') === 'mandap' ? 'selected' : '' }}>Vedic Mandap</option>
                    <option value="haldi" {{ request('event_type') === 'haldi' ? 'selected' : '' }}>Haldi Ceremony</option>
                    <option value="mehendi" {{ request('event_type') === 'mehendi' ? 'selected' : '' }}>Mehendi Setup</option>
                    <option value="sangeet" {{ request('event_type') === 'sangeet' ? 'selected' : '' }}>Sangeet Night</option>
                    <option value="reception" {{ request('event_type') === 'reception' ? 'selected' : '' }}>Grand Reception</option>
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-lg transition">Filter</button>
                <a href="{{ route('admin.videos.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg font-semibold flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Videos List Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if($videos->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                No wedding videos or reels found. Click "+ Upload New Reel / Video" above to add one.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 w-20">Preview</th>
                            <th class="py-3.5 px-4">Title &amp; Location</th>
                            <th class="py-3.5 px-4">Ceremony</th>
                            <th class="py-3.5 px-4">Type &amp; Duration</th>
                            <th class="py-3.5 px-4">Visibility</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($videos as $v)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4">
                                    <div class="w-14 h-20 rounded-lg overflow-hidden bg-stone-900 border border-slate-200 shrink-0 relative">
                                        <img src="{{ $v->safe_thumbnail_url }}" alt="{{ $v->title }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=200&q=80'">
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center text-white">
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-bold text-slate-900 block text-sm">{{ $v->title }}</span>
                                    <span class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ $v->location }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 border border-amber-200 font-semibold text-[11px]">
                                        {{ $v->event_type_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-slate-800 font-medium block">{{ $v->video_type_label }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $v->formatted_duration }}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($v->is_active)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Inactive</span>
                                        @endif
                                        @if($v->is_homepage)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Homepage</span>
                                        @endif
                                        @if($v->is_featured)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">Featured</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('videos.show', $v->slug) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Preview Public Page">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.videos.edit', $v->id) }}" class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition" title="Edit Reel">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.videos.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this video reel?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 transition" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($videos->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $videos->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
