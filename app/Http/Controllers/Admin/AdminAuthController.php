<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminActivityLog;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    /**
     * Show the dedicated Admin Login form.
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isAdminUser()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Authenticate admin credentials and verify administrative role.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Verify role permission
            if (!$user->isAdminUser()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access Denied: You do not have permission to access the Aditya Utsav Management Panel.',
                ])->onlyInput('email');
            }

            // Update last login
            $user->update(['last_login_at' => Carbon::now()]);

            $request->session()->regenerate();

            AdminActivityLog::log('Admin Login', 'User', $user->id, "{$user->name} logged into admin panel.");

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our administrative records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the administrative user and invalidate session.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AdminActivityLog::log('Admin Logout', 'User', $user->id, "{$user->name} logged out.");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('info', 'You have been successfully signed out.');
    }
}
