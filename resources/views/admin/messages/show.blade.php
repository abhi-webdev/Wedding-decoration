@extends('layouts.admin')

@section('title', 'Message from ' . $message->name)
@section('header', 'Message: ' . ($message->subject ?? 'Customer Inquiry'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Messages</span>
        </a>
    </div>

    <!-- Message Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $message->subject ?? 'General Inquiry' }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">Received on {{ $message->created_at->format('l, d F Y at h:i A') }}</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $message->status === 'replied' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                Status: {{ $message->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Sender Name</span>
                <p class="font-bold text-slate-800 mt-1">{{ $message->name }}</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 block uppercase font-semibold text-[10px]">Sender Email</span>
                <a href="mailto:{{ $message->email }}" class="font-bold text-amber-700 hover:underline mt-1 block">{{ $message->email }}</a>
            </div>
            @if($message->phone)
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block uppercase font-semibold text-[10px]">Contact Phone</span>
                    <a href="tel:{{ $message->phone }}" class="font-bold text-amber-700 hover:underline mt-1 block">{{ $message->phone }}</a>
                </div>
            @endif
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <span class="text-slate-400 uppercase font-semibold text-[10px] block mb-2">Message Body</span>
            <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
        </div>

        <!-- Status & Notes Updater -->
        <form action="{{ route('admin.messages.updateStatus', $message->id) }}" method="POST" class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3 items-end">
            @csrf
            @method('PATCH')

            <div class="w-full sm:w-48">
                <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Update Status</label>
                <select name="status" id="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="archived" {{ $message->status === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>

            <div class="flex-1 w-full">
                <label for="admin_notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Staff Note (Optional)</label>
                <input type="text" name="admin_notes" id="admin_notes" value="{{ old('admin_notes', $message->admin_notes) }}" placeholder="e.g. Replied via WhatsApp on 20 Sep..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-amber-500">
            </div>

            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl text-xs whitespace-nowrap shadow">
                Save Status
            </button>
        </form>
    </div>
</div>
@endsection
