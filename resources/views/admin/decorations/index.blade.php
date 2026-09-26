@extends('layouts.admin')

@section('title', 'Manage Decorations')
@section('header', 'Decoration Themes & Setups')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.decorations.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search decoration name, description..."
                class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 flex-1">

            <select name="category_id" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-700">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs transition">
                Filter
            </button>
        </form>

        <a href="{{ route('admin.decorations.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-amber-400 font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 shrink-0 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Decoration</span>
        </a>
    </div>

    <!-- Decorations Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold border-b border-slate-100 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4">Decoration Theme</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Base Price</th>
                        <th class="py-3.5 px-4">Setup Type</th>
                        <th class="py-3.5 px-4">Badges</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($decorations as $decoration)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($decoration->primary_image)
                                        <img src="{{ $decoration->primary_image }}" alt="" class="w-12 h-12 object-cover rounded-lg border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs shrink-0">AU</div>
                                    @endif
                                    <div>
                                        <a href="{{ route('decorations.show', $decoration->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-amber-600 block text-xs">
                                            {{ $decoration->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 block truncate max-w-xs">{{ $decoration->slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                {{ $decoration->category->name ?? 'Unassigned' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block text-sm">₹{{ number_format($decoration->price) }}</span>
                                @if($decoration->original_price)
                                    <span class="text-[10px] text-slate-400 line-through">₹{{ number_format($decoration->original_price) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 capitalize font-medium text-slate-600">
                                {{ $decoration->setup_type ?? 'Indoor / Outdoor' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap space-x-1">
                                @if($decoration->is_featured)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">Featured</span>
                                @endif
                                @if($decoration->is_popular)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Popular</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $decoration->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $decoration->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.decorations.edit', $decoration->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-700 font-semibold transition" title="Edit Decoration">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.decorations.destroy', $decoration->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to deactivate/delete this decoration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-700 font-semibold transition" title="Delete Decoration">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No decorations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($decorations->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $decorations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
