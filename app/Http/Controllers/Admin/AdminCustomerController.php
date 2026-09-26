<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\QuoteRequest;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount(['bookings', 'quoteRequests']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::where('role', 'customer')
            ->with([
                'bookings' => function ($q) {
                    $q->with('decoration')->orderBy('created_at', 'desc');
                },
                'quoteRequests' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }
            ])
            ->findOrFail($id);

        $totalSpent = $customer->bookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        return view('admin.customers.show', compact('customer', 'totalSpent'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->is_active = !$customer->is_active;
        $customer->save();

        $statusStr = $customer->is_active ? 'activated' : 'deactivated';

        AdminActivityLog::log(
            auth()->id(),
            'status_change',
            'user',
            $customer->id,
            "Customer account '{$customer->name}' ({$customer->email}) {$statusStr}"
        );

        return redirect()->back()->with('success', "Customer account {$statusStr} successfully.");
    }
}
