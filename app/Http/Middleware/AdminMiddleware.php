<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $permission = null)
    {
        // 1. Must be authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login')->with('error', 'Please log in to access the management panel.');
        }

        $user = Auth::user();

        // 2. Must be an active admin user
        if (!$user->isAdminUser()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized admin role.'], 403);
            }
            abort(403, 'Access Denied: You do not have permission to access the Aditya Utsav Admin Panel.');
        }

        // 3. Optional specific permission check
        if ($permission) {
            $isAuthorized = match ($permission) {
                'super_admin' => $user->isSuperAdmin(),
                'admin' => $user->isAdmin(),
                'bookings' => $user->canManageBookings(),
                'content' => $user->canManageContent(),
                default => true,
            };

            if (!$isAuthorized) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Insufficient permissions for this action.'], 403);
                }
                abort(403, 'Access Denied: Your assigned administrative role does not permit this action.');
            }
        }

        return $next($request);
    }
}
