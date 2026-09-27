<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $payment->receipt_number ?? $payment->payment_reference }} — Aditya Utsav</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            burgundy: '#72002F',
                            'deep-burgundy': '#4A001F',
                            gold: '#D4AF37',
                            'gold-light': '#F4E0A5',
                            cream: '#FFF8F0',
                            offwhite: '#FDFBF7',
                            charcoal: '#1F1F1F',
                            'muted-brown': '#6B5E57',
                            'light-border': '#EADBCE'
                        }
                    },
                    fontFamily: {
                        serif: ['Cinzel', 'Georgia', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-brand-charcoal min-h-screen py-6 sm:py-10 px-4">

    @php
        $booking = $payment->booking;
        $totalBookingAmount = $booking ? (float)$booking->effective_total : (float)$payment->amount;
        $totalPaidTillNow = (float) $booking->payments()->whereIn('status', ['paid', 'accepted', 'successful'])->sum('amount');
        $previousPaid = max(0, $totalPaidTillNow - (float)$payment->amount);
        $remainingAmount = max(0, $totalBookingAmount - $totalPaidTillNow);
        $isFullPaid = ($remainingAmount <= 0);
    @endphp

    <div class="max-w-3xl mx-auto space-y-4">
        
        <!-- Action Navigation Bar (Hidden on Print) -->
        <div class="no-print flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-xl border border-brand-light-border shadow-sm">
            <div class="flex items-center gap-2">
                @if(auth()->user() && auth()->user()->isAdminUser())
                    <a href="{{ route('admin.payments.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-brand-charcoal bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors flex items-center gap-1.5">
                        <i class="fas fa-arrow-left"></i> Back to Payments
                    </a>
                @else
                    <a href="{{ route('account.payments.index') }}" class="px-3.5 py-1.5 text-xs font-semibold text-brand-charcoal bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors flex items-center gap-1.5">
                        <i class="fas fa-arrow-left"></i> Back to My Payments
                    </a>
                @endif
                <span class="text-xs text-brand-muted-brown">| Official E-Receipt</span>
            </div>

            <button onclick="window.print()" class="px-5 py-2 text-xs font-bold uppercase tracking-wider text-white bg-brand-burgundy hover:bg-brand-deep-burgundy rounded-lg border border-brand-gold shadow flex items-center gap-2 cursor-pointer transition-all hover:scale-[1.02]">
                <i class="fas fa-print text-brand-gold"></i>
                <span>Print / Download Receipt</span>
            </button>
        </div>

        <!-- Official Receipt Document Card -->
        <div class="receipt-card bg-white rounded-2xl border-2 border-brand-gold/60 shadow-xl overflow-hidden p-6 sm:p-10 relative">
            
            <!-- Top Floral Accent Stripe -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-brand-burgundy via-brand-gold to-brand-burgundy"></div>

            <!-- 1. Header & Organization Identity -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b-2 border-brand-light-border pb-6 gap-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-brand-burgundy block">
                        Official Payment Receipt
                    </span>
                    <h1 class="font-serif text-2xl sm:text-3xl font-black text-brand-charcoal tracking-wide mt-0.5">
                        ADITYA UTSAV
                    </h1>
                    <p class="text-xs text-brand-muted-brown font-medium mt-0.5">
                        Bihar Wedding Decoration &amp; Event Management Services
                    </p>
                    <p class="text-[11px] text-brand-muted-brown">
                        {{ $settings['business_address'] ?? 'Station Chowk, Main Road, Siwan, Bihar - 841226' }} • Phone: {{ $settings['business_phone'] ?? '+91 98765 43210' }}
                    </p>
                </div>

                <div class="text-left sm:text-right bg-brand-offwhite p-3.5 rounded-xl border border-brand-light-border">
                    <span class="text-[10px] uppercase font-bold text-brand-muted-brown block">Receipt Number</span>
                    <span class="font-mono text-sm sm:text-base font-bold text-brand-burgundy block">
                        {{ $payment->receipt_number ?? $payment->payment_reference }}
                    </span>
                    <span class="text-[10px] text-brand-muted-brown block mt-1">
                        Date: <strong>{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : date('d M Y') }}</strong>
                    </span>
                </div>
            </div>

            <!-- 2. Status Badge & Transaction Metadata -->
            <div class="my-6 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-brand-cream/60 p-4 rounded-xl border border-brand-light-border text-xs">
                <div>
                    <span class="text-brand-muted-brown block text-[10px] uppercase font-semibold">Payment Status</span>
                    @if($isFullPaid)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 mt-1 bg-emerald-100 text-emerald-900 border border-emerald-300 font-bold rounded-full text-[11px]">
                            <i class="fas fa-check-circle text-emerald-600"></i> FULLY PAID
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 mt-1 bg-blue-100 text-blue-900 border border-blue-300 font-bold rounded-full text-[11px]">
                            <i class="fas fa-adjust text-blue-600"></i> PARTIALLY PAID
                        </span>
                    @endif
                </div>

                <div>
                    <span class="text-brand-muted-brown block text-[10px] uppercase font-semibold">Payment Reference</span>
                    <span class="font-mono font-bold text-brand-charcoal text-xs sm:text-sm mt-0.5 block">
                        {{ $payment->payment_reference }}
                    </span>
                </div>

                <div>
                    <span class="text-brand-muted-brown block text-[10px] uppercase font-semibold">Booking Reference</span>
                    <span class="font-mono font-bold text-brand-burgundy text-xs sm:text-sm mt-0.5 block">
                        {{ $booking->booking_reference ?? 'N/A' }}
                    </span>
                </div>
            </div>

            <!-- 3. Customer & Event Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 my-6 text-xs">
                <div class="space-y-1 bg-white p-4 rounded-xl border border-brand-light-border">
                    <span class="font-bold text-brand-burgundy uppercase text-[11px] block border-b border-brand-light-border pb-1">
                        Client Information
                    </span>
                    <div class="pt-1">
                        <span class="text-brand-muted-brown">Name:</span>
                        <strong class="text-brand-charcoal text-sm block">{{ $payment->customer->name ?? ($booking->customer_name ?? 'Valued Client') }}</strong>
                    </div>
                    <div>
                        <span class="text-brand-muted-brown">Phone:</span>
                        <strong class="text-brand-charcoal">{{ $payment->customer->phone ?? ($booking->customer_phone ?? 'N/A') }}</strong>
                    </div>
                    <div>
                        <span class="text-brand-muted-brown">Email:</span>
                        <strong class="text-brand-charcoal">{{ $payment->customer->email ?? ($booking->customer_email ?? 'N/A') }}</strong>
                    </div>
                </div>

                <div class="space-y-1 bg-white p-4 rounded-xl border border-brand-light-border">
                    <span class="font-bold text-brand-burgundy uppercase text-[11px] block border-b border-brand-light-border pb-1">
                        Event &amp; Setup Details
                    </span>
                    <div class="pt-1">
                        <span class="text-brand-muted-brown">Booked Setup:</span>
                        <strong class="text-brand-charcoal text-sm block">{{ $booking ? $booking->booked_item_name : 'Wedding Decoration Service' }}</strong>
                    </div>
                    <div>
                        <span class="text-brand-muted-brown">Event Date:</span>
                        <strong class="text-brand-charcoal">{{ $booking ? ($booking->formatted_event_date ?? $booking->event_date) : 'N/A' }}</strong>
                    </div>
                    <div>
                        <span class="text-brand-muted-brown">Venue Location:</span>
                        <strong class="text-brand-charcoal">{{ $booking ? ($booking->city . ', ' . $booking->state) : 'Siwan, Bihar' }}</strong>
                    </div>
                </div>
            </div>

            <!-- 4. Financial Calculation Table -->
            <div class="my-6 border border-brand-light-border rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-brand-burgundy text-brand-cream uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Financial Item</th>
                            <th class="py-3 px-4">Payment Method / Details</th>
                            <th class="py-3 px-4 text-right">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-light-border bg-white text-brand-charcoal">
                        <tr>
                            <td class="py-3 px-4 font-semibold">Total Estimated Booking Value</td>
                            <td class="py-3 px-4 text-brand-muted-brown">Standard Certified Price + Selected Add-ons</td>
                            <td class="py-3 px-4 text-right font-semibold">₹{{ number_format($totalBookingAmount, 2) }}</td>
                        </tr>
                        @if($previousPaid > 0)
                        <tr class="text-brand-muted-brown">
                            <td class="py-3 px-4 pl-6">↳ Previously Verified Payments</td>
                            <td class="py-3 px-4">Prior Advance / Partial Receipts</td>
                            <td class="py-3 px-4 text-right font-medium">₹{{ number_format($previousPaid, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="bg-emerald-50/50 font-bold text-emerald-950">
                            <td class="py-3.5 px-4 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                                <span>Current Verified Payment (This Receipt)</span>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <span>{{ $payment->payment_method_label }}</span>
                                @if($payment->transaction_reference)
                                    <span class="text-[10px] text-brand-muted-brown block font-mono">UTR: {{ $payment->transaction_reference }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-serif text-sm sm:text-base text-emerald-800">
                                ₹{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                        <tr class="bg-slate-50 font-semibold">
                            <td class="py-3 px-4">Cumulative Total Paid</td>
                            <td class="py-3 px-4 text-brand-muted-brown">All confirmed transactions to date</td>
                            <td class="py-3 px-4 text-right text-brand-charcoal font-bold">₹{{ number_format($totalPaidTillNow, 2) }}</td>
                        </tr>
                        <tr class="bg-amber-50/60 font-bold">
                            <td class="py-3.5 px-4 text-brand-charcoal">Remaining Balance Due</td>
                            <td class="py-3.5 px-4 text-[11px] text-brand-muted-brown">Payable on or before event setup day</td>
                            <td class="py-3.5 px-4 text-right font-serif text-sm sm:text-base {{ $remainingAmount > 0 ? 'text-amber-900' : 'text-emerald-700' }}">
                                ₹{{ number_format($remainingAmount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 5. Verification & Authorization Footer -->
            <div class="mt-8 pt-6 border-t-2 border-brand-light-border flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-brand-muted-brown">
                <div class="space-y-1 text-center sm:text-left">
                    <p class="font-bold text-brand-charcoal">Verified By: {{ $payment->verifiedByUser->name ?? 'Aditya Utsav Accounts Dept.' }}</p>
                    <p class="text-[10px]">Verification Timestamp: {{ $payment->verified_at ? $payment->verified_at->format('d M Y, h:i A') : ($payment->updated_at ? $payment->updated_at->format('d M Y, h:i A') : 'Verified') }}</p>
                    <p class="text-[10px] italic">This is a system-generated electronic receipt with certified digital ledger tracking.</p>
                </div>

                <div class="text-center sm:text-right border-t sm:border-t-0 sm:border-l sm:pl-6 border-brand-light-border pt-4 sm:pt-0">
                    <div class="font-serif text-sm font-bold text-brand-burgundy">ADITYA UTSAV</div>
                    <div class="w-32 border-b border-brand-charcoal/30 my-2 mx-auto sm:ml-auto"></div>
                    <span class="text-[10px] uppercase tracking-wider block font-semibold text-brand-charcoal">Authorized Signatory</span>
                    <span class="text-[9px] text-brand-muted-brown block">Siwan Operations Hub</span>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
