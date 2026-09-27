<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Booking;
use App\Models\Quotation;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\NotificationService;

class AccountController extends Controller
{
    /**
     * Display customer account dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Calculate real live stats for the authenticated customer only
        $totalBookings = Booking::where('user_id', $user->id)->count();
        $pendingBookings = Booking::where('user_id', $user->id)->where('status', 'pending')->count();
        $acceptedBookings = Booking::where('user_id', $user->id)->where('status', 'accepted')->count();
        $confirmedBookings = Booking::where('user_id', $user->id)->whereIn('status', ['confirmed', 'advance_paid', 'scheduled'])->count();
        $completedBookings = Booking::where('user_id', $user->id)->where('status', 'completed')->count();

        $totalQuotations = Quotation::where('user_id', $user->id)->count();
        $pendingQuotations = Quotation::where('user_id', $user->id)->whereIn('status', ['sent', 'viewed'])->count();
        $totalPaid = Payment::where('user_id', $user->id)->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');

        // Upcoming Event
        $upcomingEvent = Booking::with(['decoration', 'package'])
            ->where('user_id', $user->id)
            ->where('event_date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['accepted', 'confirmed', 'advance_paid', 'scheduled'])
            ->orderBy('event_date', 'asc')
            ->first();

        // Recent 5 bookings
        $recentBookings = Booking::with(['decoration.category', 'package'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $whatsappUrl = NotificationService::getWhatsAppUrl("Namaste Aditya Utsav, I am client {$user->name} checking my wedding booking status.");

        return view('account.dashboard', compact(
            'user',
            'totalBookings',
            'pendingBookings',
            'acceptedBookings',
            'confirmedBookings',
            'completedBookings',
            'totalQuotations',
            'pendingQuotations',
            'totalPaid',
            'upcomingEvent',
            'recentBookings',
            'whatsappUrl'
        ));
    }

    /**
     * Display customer profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Update customer profile with strict mass-assignment protection.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->whatsapp = $validated['whatsapp'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->state = $validated['state'] ?? 'Bihar';
        $user->address = $validated['address'] ?? null;
        $user->save();

        return back()->with('success', 'Your profile details have been successfully updated.');
    }

    /**
     * Display change password form.
     */
    public function password()
    {
        $user = Auth::user();
        return view('account.password', compact('user'));
    }

    /**
     * Handle password change with current password verification.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match your account password.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Your password has been changed securely.');
    }
}
