<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Decoration;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::today()->format('Y-m'));
        $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $events = Booking::with(['decoration', 'user'])
            ->whereBetween('event_date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->orderBy('event_date', 'asc')
            ->get();

        // Group by event date
        $groupedEvents = $events->groupBy(function ($event) {
            return Carbon::parse($event->event_date)->format('Y-m-d');
        });

        $activeDecorations = Decoration::where('is_active', true)->get();

        return view('admin.calendar.index', compact('startOfMonth', 'endOfMonth', 'month', 'events', 'groupedEvents', 'activeDecorations'));
    }
}
