<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminQuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['booking.decoration', 'customer', 'creator']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('quotation_number', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($userQ) use ($s) {
                      $userQ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%")
                            ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('booking', function ($bQ) use ($s) {
                      $bQ->where('booking_reference', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quotations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $counts = [
            'all' => Quotation::count(),
            'draft' => Quotation::where('status', 'draft')->count(),
            'sent' => Quotation::where('status', 'sent')->count(),
            'accepted' => Quotation::where('status', 'accepted')->count(),
            'rejected' => Quotation::where('status', 'rejected')->count(),
        ];

        return view('admin.quotations.index', compact('quotations', 'counts'));
    }

    public function create(Request $request)
    {
        $booking = null;
        if ($request->filled('booking_id')) {
            $booking = Booking::with(['decoration', 'addons.addon', 'user'])->findOrFail($request->booking_id);
        }

        $bookings = Booking::whereIn('status', ['pending', 'quoted'])
            ->with(['decoration', 'user'])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('admin.quotations.create', compact('booking', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'valid_until' => 'required|date|after_or_equal:today',
            'advance_percentage' => 'required|numeric|min:10|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'nullable|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'send_now' => 'nullable|boolean',
        ]);

        $booking = Booking::with('user')->findOrFail($validated['booking_id']);

        DB::beginTransaction();
        try {
            // Generate unique AUQ number
            $datePart = Carbon::today()->format('Ymd');
            $countToday = Quotation::whereDate('created_at', Carbon::today())->count() + 1;
            $quotationNumber = sprintf('AUQ-%s-%05d', $datePart, $countToday);
            while (Quotation::where('quotation_number', $quotationNumber)->exists()) {
                $countToday++;
                $quotationNumber = sprintf('AUQ-%s-%05d', $datePart, $countToday);
            }

            // Calculate Subtotal & Totals server-side
            $subtotal = 0;
            $processedItems = [];

            foreach ($validated['items'] as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);
                $itemTotal = max(0, ($qty * $price) - $disc);

                $subtotal += $itemTotal;
                $processedItems[] = [
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'total' => $itemTotal,
                ];
            }

            $discountAmount = (float) ($validated['discount_amount'] ?? 0);
            $additionalCharges = (float) ($validated['additional_charges'] ?? 0);
            $taxAmount = (float) ($validated['tax_amount'] ?? 0);

            $grandTotal = max(0, $subtotal + $additionalCharges + $taxAmount - $discountAmount);
            $advancePercentage = (float) $validated['advance_percentage'];
            $advanceAmount = round(($grandTotal * $advancePercentage) / 100, 2);
            $balanceAmount = max(0, $grandTotal - $advanceAmount);

            $status = $request->boolean('send_now') ? 'sent' : 'draft';

            $quotation = Quotation::create([
                'quotation_number' => $quotationNumber,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'decoration_id' => $booking->decoration_id,
                'subtotal' => $subtotal,
                'addon_total' => 0,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'additional_charges' => $additionalCharges,
                'grand_total' => $grandTotal,
                'advance_percentage' => $advancePercentage,
                'advance_amount' => $advanceAmount,
                'balance_amount' => $balanceAmount,
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'] ?? 'Thank you for choosing Aditya Utsav for your special event.',
                'terms' => $validated['terms'] ?? "1. 40% advance required to lock dates.\n2. Balance payable on event day before setup.\n3. Flower availability subject to seasonal market supply in Bihar.",
                'status' => $status,
                'created_by' => Auth::id(),
            ]);

            foreach ($processedItems as $pItem) {
                $quotation->items()->create($pItem);
            }

            if ($status === 'sent') {
                $booking->update(['status' => 'quoted']);

                BookingStatusHistory::create([
                    'booking_id' => $booking->id,
                    'status' => 'quoted',
                    'note' => "Quotation #{$quotation->quotation_number} generated for ₹" . number_format($grandTotal) . " with {$advancePercentage}% advance requirement.",
                    'changed_by_user_id' => Auth::id(),
                ]);
            }

            AdminActivityLog::log(
                'Created Quotation',
                'Quotation',
                $quotation->id,
                "Generated quotation {$quotation->quotation_number} for booking #{$booking->booking_reference} (Grand Total: ₹" . number_format($grandTotal) . ")"
            );

            DB::commit();

            return redirect()->route('admin.quotations.show', $quotation->id)->with('success', "Quotation {$quotation->quotation_number} created successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Error creating quotation: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $quotation = Quotation::with(['booking.decoration', 'customer', 'items', 'creator', 'payments', 'invoices'])->findOrFail($id);
        return view('admin.quotations.show', compact('quotation'));
    }

    public function edit($id)
    {
        $quotation = Quotation::with(['booking.decoration', 'items'])->findOrFail($id);

        if (in_array($quotation->status, ['accepted', 'cancelled'])) {
            return redirect()->route('admin.quotations.show', $id)->with('error', "Cannot edit a quotation that is already {$quotation->status}.");
        }

        return view('admin.quotations.edit', compact('quotation'));
    }

    public function update(Request $request, $id)
    {
        $quotation = Quotation::with('booking')->findOrFail($id);

        if (in_array($quotation->status, ['accepted', 'cancelled'])) {
            return back()->with('error', "Cannot edit a quotation that is already {$quotation->status}.");
        }

        $validated = $request->validate([
            'valid_until' => 'required|date',
            'advance_percentage' => 'required|numeric|min:10|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.item_type' => 'required|string',
            'items.*.item_id' => 'nullable|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $processedItems = [];

            foreach ($validated['items'] as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $disc = (float) ($item['discount'] ?? 0);
                $itemTotal = max(0, ($qty * $price) - $disc);

                $subtotal += $itemTotal;
                $processedItems[] = [
                    'item_type' => $item['item_type'],
                    'item_id' => $item['item_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $disc,
                    'total' => $itemTotal,
                ];
            }

            $discountAmount = (float) ($validated['discount_amount'] ?? 0);
            $additionalCharges = (float) ($validated['additional_charges'] ?? 0);
            $taxAmount = (float) ($validated['tax_amount'] ?? 0);

            $grandTotal = max(0, $subtotal + $additionalCharges + $taxAmount - $discountAmount);
            $advancePercentage = (float) $validated['advance_percentage'];
            $advanceAmount = round(($grandTotal * $advancePercentage) / 100, 2);
            $balanceAmount = max(0, $grandTotal - $advanceAmount);

            $quotation->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'additional_charges' => $additionalCharges,
                'grand_total' => $grandTotal,
                'advance_percentage' => $advancePercentage,
                'advance_amount' => $advanceAmount,
                'balance_amount' => $balanceAmount,
                'valid_until' => $validated['valid_until'],
                'notes' => $validated['notes'] ?? $quotation->notes,
                'terms' => $validated['terms'] ?? $quotation->terms,
            ]);

            // Recreate items
            $quotation->items()->delete();
            foreach ($processedItems as $pItem) {
                $quotation->items()->create($pItem);
            }

            AdminActivityLog::log(
                'Updated Quotation',
                'Quotation',
                $quotation->id,
                "Updated quotation {$quotation->quotation_number} (Grand Total: ₹" . number_format($grandTotal) . ")"
            );

            DB::commit();

            return redirect()->route('admin.quotations.show', $quotation->id)->with('success', "Quotation {$quotation->quotation_number} updated successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', "Error updating quotation: " . $e->getMessage());
        }
    }

    public function send($id)
    {
        $quotation = Quotation::with('booking')->findOrFail($id);

        if ($quotation->status === 'accepted') {
            return back()->with('info', "Quotation #{$quotation->quotation_number} is already accepted.");
        }

        $quotation->update(['status' => 'sent']);
        if ($quotation->booking && $quotation->booking->status === 'pending') {
            $quotation->booking->update(['status' => 'quoted']);

            BookingStatusHistory::create([
                'booking_id' => $quotation->booking->id,
                'status' => 'quoted',
                'note' => "Quotation #{$quotation->quotation_number} sent to customer for ₹" . number_format($quotation->grand_total),
                'changed_by_user_id' => Auth::id(),
            ]);
        }

        AdminActivityLog::log(
            'Sent Quotation',
            'Quotation',
            $quotation->id,
            "Transmitted quotation {$quotation->quotation_number} to customer {$quotation->customer->name}"
        );

        return back()->with('success', "Quotation {$quotation->quotation_number} has been sent to the customer.");
    }

    public function cancel($id)
    {
        $quotation = Quotation::with('booking')->findOrFail($id);
        $quotation->update(['status' => 'cancelled']);

        AdminActivityLog::log(
            'Cancelled Quotation',
            'Quotation',
            $quotation->id,
            "Cancelled quotation {$quotation->quotation_number}"
        );

        return back()->with('info', "Quotation {$quotation->quotation_number} has been cancelled.");
    }
}
