<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceArea;

class ServiceAreaController extends Controller
{
    /**
     * Display service coverage areas strictly categorizing Bihar and Uttar Pradesh.
     */
    public function index()
    {
        $biharCore = ServiceArea::where('state', 'Bihar')
            ->where('category', 'Bihar Core')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $biharExtended = ServiceArea::where('state', 'Bihar')
            ->where('category', 'Bihar Extended')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $uttarPradesh = ServiceArea::where('state', 'Uttar Pradesh')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('service-areas.index', compact('biharCore', 'biharExtended', 'uttarPradesh'));
    }
}
