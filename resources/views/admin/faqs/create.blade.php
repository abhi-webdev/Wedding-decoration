@extends('layouts.admin')

@section('title', 'Add New FAQ')
@section('header', 'Create FAQ')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to FAQs</span>
    </a>

    <form action="{{ route('admin.faqs.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label for="question" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Question *</label>
            <input type="text" name="question" id="question" value="{{ old('question') }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500"
                placeholder="e.g. What is the advance booking amount required?">
        </div>

        <div>
            <label for="category" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Category *</label>
            <select name="category" id="category" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                <option value="general" {{ old('category') === 'general' ? 'selected' : '' }}>General</option>
                <option value="booking" {{ old('category') === 'booking' ? 'selected' : '' }}>Booking & Reservations</option>
                <option value="pricing" {{ old('category') === 'pricing' ? 'selected' : '' }}>Pricing & Payments</option>
                <option value="customization" {{ old('category') === 'customization' ? 'selected' : '' }}>Theme Customization</option>
                <option value="logistics" {{ old('category') === 'logistics' ? 'selected' : '' }}>Setup & Logistics</option>
                <option value="cancellation" {{ old('category') === 'cancellation' ? 'selected' : '' }}>Cancellation & Reschedule</option>
                <option value="service_areas" {{ old('category') === 'service_areas' ? 'selected' : '' }}>Service Areas</option>
            </select>
        </div>

        <div>
            <label for="answer" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Answer *</label>
            <textarea name="answer" id="answer" rows="4" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                placeholder="Provide a clear and helpful explanation...">{{ old('answer') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="sort_order" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex items-center pt-5">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                    <span>Active FAQ</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.faqs.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Save FAQ</button>
        </div>
    </form>
</div>
@endsection
