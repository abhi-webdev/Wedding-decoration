<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceArea;
use App\Models\ContactMessage;
use App\Models\SiteSetting;

class PageController extends Controller
{
    /**
     * Show About Aditya Utsav page with factual positioning.
     */
    public function about()
    {
        $biharCoreAreas = ServiceArea::where('category', 'Bihar Core')->where('is_active', true)->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.about', compact('biharCoreAreas', 'settings'));
    }

    /**
     * Show Contact page.
     */
    public function contact()
    {
        $biharAreas = ServiceArea::where('state', 'Bihar')->where('is_active', true)->get();
        $upAreas = ServiceArea::where('state', 'Uttar Pradesh')->where('is_active', true)->get();
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('pages.contact', compact('biharAreas', 'upAreas', 'settings'));
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'subject' => $validated['subject'] ?? 'General Enquiry',
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Thank you! Your message has been sent to our Aditya Utsav customer care team. We will get in touch with you shortly.');
    }

    /**
     * Terms & Conditions page.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Cancellation Policy page.
     */
    public function cancellationPolicy()
    {
        return view('pages.cancellation-policy');
    }

    /**
     * Step-by-Step Wedding Booking Guide.
     */
    public function bookingGuide()
    {
        return view('pages.booking-guide');
    }
}
