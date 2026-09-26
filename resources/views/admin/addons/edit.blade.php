@extends('layouts.admin')

@section('title', 'Edit Add-on - ' . $addon->name)
@section('header', 'Edit Add-on: ' . $addon->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.addons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Add-ons</span>
    </a>

    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Add-on Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $addon->name) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="slug" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $addon->slug) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Price (₹) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $addon->price) }}" step="0.01" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="pricing_type" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Pricing Model *</label>
                <select name="pricing_type" id="pricing_type" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="fixed" {{ old('pricing_type', $addon->pricing_type) === 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                    <option value="per_unit" {{ old('pricing_type', $addon->pricing_type) === 'per_unit' ? 'selected' : '' }}>Per Unit</option>
                    <option value="per_hour" {{ old('pricing_type', $addon->pricing_type) === 'per_hour' ? 'selected' : '' }}>Per Hour</option>
                    <option value="per_day" {{ old('pricing_type', $addon->pricing_type) === 'per_day' ? 'selected' : '' }}>Per Day</option>
                </select>
            </div>
        </div>

        <div>
            <label for="unit_label" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Unit Label</label>
            <input type="text" name="unit_label" id="unit_label" value="{{ old('unit_label', $addon->unit_label) }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">{{ old('description', $addon->description) }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Add-on</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $addon->is_featured) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Featured Add-on</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.addons.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Add-on</button>
        </div>
    </form>
</div>
@endsection
