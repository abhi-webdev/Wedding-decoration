<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Generate a direct WhatsApp click-to-chat URL with pre-filled message.
     */
    public static function getWhatsAppUrl(?string $message = null): string
    {
        $phone = SiteSetting::get('business_phone', '+91 98765 43210');
        // Clean phone number (strip spaces, dashes, plus)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '91' . $cleanPhone;
        }

        $defaultMsg = "Namaste Aditya Utsav, I would like to inquire about wedding decoration services in Bihar.";
        $encodedMsg = urlencode($message ?: $defaultMsg);

        return "https://wa.me/{$cleanPhone}?text={$encodedMsg}";
    }

    /**
     * Send an email notification gracefully without throwing unhandled exceptions if SMTP is unconfigured.
     */
    public static function sendEmail(string $recipientEmail, string $recipientName, string $subject, string $view, array $data = []): bool
    {
        try {
            if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            // Enrich data with site settings
            $data['business_name'] = SiteSetting::get('business_name', 'Aditya Utsav — Bihar Wedding Decoration');
            $data['business_phone'] = SiteSetting::get('business_phone', '+91 98765 43210');
            $data['business_email'] = SiteSetting::get('business_email', 'contact@adityautsav.in');
            $data['business_address'] = SiteSetting::get('business_address', 'Main Road, Station Chowk, Siwan, Bihar - 841226');
            $data['recipientName'] = $recipientName;
            $data['subject'] = $subject;

            Mail::send($view, $data, function ($message) use ($recipientEmail, $recipientName, $subject, $data) {
                $message->to($recipientEmail, $recipientName)
                        ->subject($subject)
                        ->from(config('mail.from.address', 'noreply@adityautsav.in'), $data['business_name']);
            });

            return true;
        } catch (\Throwable $e) {
            // Log warning gracefully without breaking user workflow
            Log::warning("NotificationService email dispatch failed: " . $e->getMessage(), [
                'recipient' => $recipientEmail,
                'subject' => $subject,
            ]);
            return false;
        }
    }

    /**
     * Notify customer on booking submission.
     */
    public static function notifyBookingSubmitted($booking): void
    {
        if ($booking->customer_email) {
            self::sendEmail(
                $booking->customer_email,
                $booking->customer_name,
                "Your Aditya Utsav Booking Request — #{$booking->booking_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Booking Request Received',
                    'heading' => 'Namaste ' . $booking->customer_name . '!',
                    'message' => 'Thank you for choosing Aditya Utsav. We have received your booking request for ' . ($booking->decoration->name ?? 'Wedding Decoration') . ' on ' . ($booking->formatted_event_date ?? $booking->event_date) . ' in ' . $booking->city . '. Our manager is reviewing date availability and will prepare your quotation shortly.',
                    'booking' => $booking,
                    'actionText' => 'View Booking in Portal',
                    'actionUrl' => route('account.bookings'),
                ]
            );
        }
    }

    /**
     * Notify customer when a quotation is sent.
     */
    public static function notifyQuotationSent($quotation): void
    {
        $customer = $quotation->customer ?? $quotation->booking?->user;
        $email = $customer?->email ?? $quotation->booking?->customer_email;
        $name = $customer?->name ?? $quotation->booking?->customer_name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Quotation Prepared for Booking #{$quotation->booking?->booking_reference} — #{$quotation->quotation_number}",
                'emails.booking_notification',
                [
                    'title' => 'Official Wedding Decor Quotation',
                    'heading' => 'Quotation Prepared for ' . $name,
                    'message' => 'Our planning team has crafted a detailed quotation for your event on ' . ($quotation->booking?->formatted_event_date ?? 'your requested date') . '. Total Package: ' . $quotation->formatted_grand_total . ' (Advance Required: ' . $quotation->formatted_advance_amount . '). Please review and accept to lock your wedding date.',
                    'quotation' => $quotation,
                    'actionText' => 'Review & Accept Quotation',
                    'actionUrl' => route('account.quotations.show', $quotation->id),
                ]
            );
        }
    }

    /**
     * Notify customer when payment is recorded.
     */
    public static function notifyPaymentRecorded($payment): void
    {
        $customer = $payment->customer ?? $payment->booking?->user;
        $email = $customer?->email ?? $payment->booking?->customer_email;
        $name = $customer?->name ?? $payment->booking?->customer_name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Payment Receipt — #{$payment->payment_reference} (₹" . number_format($payment->amount) . ")",
                'emails.booking_notification',
                [
                    'title' => 'Payment Verified & Receipt Generated',
                    'heading' => 'Payment Received: ₹' . number_format($payment->amount),
                    'message' => 'We have successfully recorded your ' . $payment->payment_type . ' payment of ₹' . number_format($payment->amount) . ' via ' . strtoupper($payment->payment_method) . ' for booking #' . ($payment->booking?->booking_reference ?? '') . '.',
                    'payment' => $payment,
                    'actionText' => 'View Payment Receipts',
                    'actionUrl' => route('account.payments.index'),
                ]
            );
        }
    }
}
