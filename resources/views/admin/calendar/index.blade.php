@extends('layouts.admin')

@section('title', 'Event Operations Calendar')
@section('header', 'Event Operations & Availability Calendar')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Navigation Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            @php
                $prevMonth = $startOfMonth->copy()->subMonth()->format('Y-m');
                $nextMonth = $startOfMonth->copy()->addMonth()->format('Y-m');
            @endphp
            <a href="{{ route('admin.calendar.index', ['month' => $prevMonth]) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Previous Month">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="text-base font-bold text-slate-900 font-heading">
                {{ $startOfMonth->format('F Y') }}
            </h2>
            <a href="{{ route('admin.calendar.index', ['month' => $nextMonth]) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition" title="Next Month">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="flex items-center gap-2 text-xs">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Confirmed / Paid</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Setup Scheduled</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending / Quoted</span>
        </div>
    </div>

    <!-- Calendar View -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-4 sm:p-6">
        @php
            $daysInMonth = $startOfMonth->daysInMonth;
            $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sun, 1 = Mon ...
        @endphp

        <!-- Weekday Headers -->
        <div class="grid grid-cols-7 gap-2 mb-2 text-center text-[11px] font-bold uppercase text-slate-400">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        <!-- Days Grid -->
        <div class="grid grid-cols-7 gap-2">
            <!-- Empty offset days -->
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="min-h-[110px] bg-slate-50/50 rounded-xl border border-slate-100/60 p-2 text-slate-300"></div>
            @endfor

            <!-- Month Days -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = $startOfMonth->copy()->day($day)->format('Y-m-d');
                    $dayEvents = $groupedEvents->get($currentDate, collect());
                    $isToday = $currentDate === now()->format('Y-m-d');
                @endphp

                <div class="min-h-[110px] bg-white rounded-xl border {{ $isToday ? 'border-amber-500 ring-2 ring-amber-200/50' : 'border-slate-200/80' }} p-2 flex flex-col justify-between hover:bg-slate-50/70 transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold {{ $isToday ? 'text-amber-800 font-extrabold' : 'text-slate-700' }}">{{ $day }}</span>
                        @if($dayEvents->count() > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-amber-100 text-amber-900">
                                {{ $dayEvents->count() }} {{ Str::plural('event', $dayEvents->count()) }}
                            </span>
                        @endif
                    </div>

                    <div class="space-y-1 mt-1 flex-1 overflow-y-auto max-h-[80px]">
                        @foreach($dayEvents as $evt)
                            <a href="{{ route('admin.bookings.show', $evt->id) }}" class="block p-1 rounded text-[10px] font-semibold leading-tight truncate transition
                                {{ in_array($evt->status, ['confirmed', 'advance_paid']) ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' :
                                   ($evt->status === 'scheduled' ? 'bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100' : 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100') }}">
                                <span class="font-bold">#{{ $evt->booking_reference }}</span> {{ $evt->customer_name }}
                                <span class="text-[9px] text-slate-500 block truncate">{{ $evt->decoration->name ?? 'Setup' }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
@endsection
