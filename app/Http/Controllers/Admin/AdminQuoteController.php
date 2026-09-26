<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminQuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = QuoteRequest::with('user');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%")
                  ->orWhere('quote_reference', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        $quotes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.quotes.index', compact('quotes'));
    }

    public function show($id)
    {
        $quote = QuoteRequest::with('user')->findOrFail($id);
        return view('admin.quotes.show', compact('quote'));
    }

    public function updateStatus(Request $request, $id)
    {
        $quote = QuoteRequest::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,pending,reviewed,contacted,quoted,converted,declined,closed',
            'admin_notes' => 'nullable|string',
            'estimated_amount' => 'nullable|numeric|min:0',
        ]);

        $oldStatus = $quote->status;
        $quote->status = $validated['status'];
        $quote->save();

        AdminActivityLog::log(
            'Updated Quote Status',
            'QuoteRequest',
            $quote->id,
            "Quote #{$quote->quote_reference} status updated from '{$oldStatus}' to '{$quote->status}'"
        );

        return redirect()->back()->with('success', "Quote request status updated to '{$quote->status}'.");
    }
}
