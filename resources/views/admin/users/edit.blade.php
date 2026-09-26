@extends('layouts.admin')

@section('title', 'Edit Staff Member - ' . $user->name)
@section('header', 'Edit Staff Member: ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Staff Users</span>
    </a>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Full Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500">
        </div>

        <div>
            <label for="email" class="block font-bold text-slate-700 uppercase tracking-wider text-xs mb-1">Staff Email Address *</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <label for="phone" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Phone Number</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label for="role" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Administrative Role *</label>
                <select name="role" id="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                    <option value="booking_manager" {{ old('role', $user->role) === 'booking_manager' ? 'selected' : '' }}>Booking Manager (Bookings & Reschedules)</option>
                    <option value="content_manager" {{ old('role', $user->role) === 'content_manager' ? 'selected' : '' }}>Content Manager (Decorations & Catalog)</option>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin (Unrestricted Master)</option>
                </select>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-400 mb-3">Leave password fields blank if you do not want to change the password.</p>
            <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                    <label for="password" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500"
                        placeholder="Leave blank to keep current">
                </div>

                <div>
                    <label for="password_confirmation" class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        <div class="flex items-center pt-2">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded bg-slate-100 border-slate-300 text-amber-600 focus:ring-amber-500">
                <span>Account Enabled (Can Log In)</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs shadow">Update Staff Member</button>
        </div>
    </form>
</div>
@endsection
