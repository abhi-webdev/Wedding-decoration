@extends('layouts.admin')

@section('title', 'Website Settings')
@section('header', 'Site & Business Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Brand Media & Visual Assets -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Brand Media &amp; Logos</h3>
                <p class="text-xs text-slate-500">Upload primary logo, favicon, and brand visuals.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <!-- Site Logo -->
                <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">Primary Brand Logo (PNG, WEBP, SVG)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center p-1 overflow-hidden shrink-0 shadow-sm">
                            @if(isset($settings['site_logo']) && $settings['site_logo']->value && file_exists(public_path($settings['site_logo']->value)))
                                <img src="{{ asset($settings['site_logo']->value) }}" alt="Logo" class="max-h-full object-contain">
                            @else
                                <span class="font-bold text-amber-600 text-sm">AU Logo</span>
                            @endif
                        </div>
                        <div class="flex-1 space-y-1">
                            <input type="file" name="site_logo" accept="image/png,image/webp,image/svg+xml,image/jpeg" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                            <p class="text-[10px] text-slate-400">Recommended: Transparent PNG or SVG (Max 5MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">Browser Favicon (PNG, ICO)</label>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-white border border-slate-200 flex items-center justify-center p-1 overflow-hidden shrink-0 shadow-sm">
                            @if(isset($settings['site_favicon']) && $settings['site_favicon']->value && file_exists(public_path($settings['site_favicon']->value)))
                                <img src="{{ asset($settings['site_favicon']->value) }}" alt="Favicon" class="max-h-full object-contain">
                            @else
                                <span class="font-bold text-slate-400 text-xs">ICO</span>
                            @endif
                        </div>
                        <div class="flex-1 space-y-1">
                            <input type="file" name="site_favicon" accept="image/png,image/x-icon,image/vnd.microsoft.icon" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200">
                            <p class="text-[10px] text-slate-400">Recommended: 32x32 or 64x64 PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Brand Settings -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Brand &amp; Company Information</h3>
                <p class="text-xs text-slate-500">Core public business identity settings.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Company Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name']->value ?? 'Aditya Utsav' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline']->value ?? 'Bihar Wedding Decoration & Event Services' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Primary Helpline / Phone</label>
                    <input type="text" name="phone" value="{{ $settings['phone']->value ?? '+91 98765 43210' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Support Email</label>
                    <input type="email" name="email" value="{{ $settings['email']->value ?? 'info@adityautsav.in' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Head Office Address</label>
                    <input type="text" name="address" value="{{ $settings['address']->value ?? 'Boring Road, Patna, Bihar - 800001' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        <!-- Social & Online Presence -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Social Media &amp; WhatsApp</h3>
                <p class="text-xs text-slate-500">Links displayed across public header and footer.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">WhatsApp Business Number</label>
                    <input type="text" name="social_whatsapp" value="{{ $settings['social_whatsapp']->value ?? '+919876543210' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Instagram URL</label>
                    <input type="text" name="social_instagram" value="{{ $settings['social_instagram']->value ?? 'https://instagram.com/adityautsav' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Facebook URL</label>
                    <input type="text" name="social_facebook" value="{{ $settings['social_facebook']->value ?? 'https://facebook.com/adityautsav' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">YouTube URL</label>
                    <input type="text" name="social_youtube" value="{{ $settings['social_youtube']->value ?? 'https://youtube.com/@adityautsav' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        <!-- Booking & Advance Terms -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">Booking Terms &amp; Advance Percentage</h3>
                <p class="text-xs text-slate-500">Parameters used in calculations and booking guidelines.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Advance Booking Percentage (%)</label>
                    <input type="number" name="booking_advance_percent" value="{{ $settings['booking_advance_percent']->value ?? '25' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Reschedule Cutoff Window (Days Prior)</label>
                    <input type="number" name="booking_reschedule_cutoff_days" value="{{ $settings['booking_reschedule_cutoff_days']->value ?? '7' }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow-md transition">
                Save Website Settings
            </button>
        </div>
    </form>
</div>
@endsection
