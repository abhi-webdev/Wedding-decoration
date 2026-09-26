@extends('layouts.admin')

@section('title', 'Edit Service Area - ' . $area->name)
@section('header', 'Edit Service Area: ' . $area->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.service-areas.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Service Areas</span>
    </a>

    <form action="{{ route('admin.service-areas.update', $area->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div class="col-span-2">
                <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Area Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $area->name) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="city" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">City / Hub *</label>
                <input type="text" name="city" id="city" value="{{ old('city', $area->city) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="state" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">State *</label>
                <select name="state" id="state" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="Bihar" {{ old('state', $area->state) === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                    <option value="Uttar Pradesh" {{ old('state', $area->state) === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                </select>
            </div>

            <div>
                <label for="district" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">District</label>
                <input type="text" name="district" id="district" value="{{ old('district', $area->district) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="tier" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Coverage Tier *</label>
                <select name="tier" id="tier" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="primary" {{ old('tier', $area->tier) === 'primary' ? 'selected' : '' }}>Primary Hub (Zero Travel)</option>
                    <option value="secondary" {{ old('tier', $area->tier) === 'secondary' ? 'selected' : '' }}>Secondary District</option>
                    <option value="extended" {{ old('tier', $area->tier) === 'extended' ? 'selected' : '' }}>Extended / Inter-State</option>
                </select>
            </div>

            <div>
                <label for="travel_surcharge" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Travel Surcharge (₹)</label>
                <input type="number" name="travel_surcharge" id="travel_surcharge" value="{{ old('travel_surcharge', $area->travel_surcharge) }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="min_booking_amount" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Min. Booking Amount (₹)</label>
                <input type="number" name="min_booking_amount" id="min_booking_amount" value="{{ old('min_booking_amount', $area->min_booking_amount) }}" step="0.01"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="col-span-2">
                <label for="description" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Service Notes</label>
                <textarea name="description" id="description" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">{{ old('description', $area->description) }}</textarea>
            </div>
        </div>

        <div class="flex items-center pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $area->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Active Service Territory</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.service-areas.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Area</button>
        </div>
    </form>
</div>
@endsection
