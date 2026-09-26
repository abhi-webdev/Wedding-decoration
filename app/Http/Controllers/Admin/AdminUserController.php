<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        $query = User::where('role', '!=', 'customer');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'asc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,booking_manager,content_manager',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->has('is_active'),
        ]);

        AdminActivityLog::log(
            auth()->id(),
            'create',
            'user',
            $user->id,
            "Created staff user '{$user->name}' with role '{$user->role}'"
        );

        return redirect()->route('admin.users.index')->with('success', "Staff user '{$user->name}' created successfully.");
    }

    public function edit($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        $user = User::where('role', '!=', 'customer')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        $user = User::where('role', '!=', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,booking_manager,content_manager',
            'is_active' => 'nullable|boolean',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role = $validated['role'];
        $user->is_active = $request->has('is_active');

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AdminActivityLog::log(
            auth()->id(),
            'update',
            'user',
            $user->id,
            "Updated staff user '{$user->name}' (Role: {$user->role})"
        );

        return redirect()->route('admin.users.index')->with('success', "Staff user '{$user->name}' updated successfully.");
    }

    public function destroy($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized. Super Admin access required.');
        }

        $user = User::where('role', '!=', 'customer')->findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        AdminActivityLog::log(
            auth()->id(),
            'delete',
            'user',
            $id,
            "Deleted staff user '{$name}'"
        );

        return redirect()->route('admin.users.index')->with('success', "Staff user '{$name}' deleted successfully.");
    }
}
