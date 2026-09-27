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
     * Notify customer on auto account creation during booking.
     */
    public static function notifyCustomerAccountCreated($user, string $tempPassword, $booking): void
    {
        if ($user->email) {
            self::sendEmail(
                $user->email,
                $user->name,
                "Your Aditya Utsav Account & Booking Reference #{$booking->booking_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Customer Account Created',
                    'heading' => 'Namaste ' . $user->name . '!',
                    'message' => 'Your Aditya Utsav customer account has been automatically created for managing your wedding decorations and booking requests.',
                    'extraDetails' => [
                        'Login Email' => $user->email,
                        'Temporary Password' => $tempPassword,
                        'Booking Reference' => $booking->booking_reference,
                        'Decoration / Package' => $booking->booked_item_name,
                        'Event Date' => $booking->formatted_event_date ?? $booking->event_date,
                        'Booking Status' => 'Pending Confirmation',
                    ],
                    'actionText' => 'Customer Login',
                    'actionUrl' => route('login'),
                ]
            );
        }
    }

    /**
     * Notify customer on booking submission.
     */
    public static function notifyBookingSubmitted($booking): void
    {
        $email = $booking->customer_email ?? $booking->user?->email;
        $name = $booking->customer_name ?? $booking->user?->name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Your Aditya Utsav Booking Request — #{$booking->booking_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Booking Request Received',
                    'heading' => 'Namaste ' . $name . '!',
                    'message' => 'Thank you for choosing Aditya Utsav. We have received your booking request for ' . $booking->booked_item_name . ' on ' . ($booking->formatted_event_date ?? $booking->event_date) . ' in ' . $booking->city . '. Our management is verifying slot availability and will confirm with you shortly.',
                    'booking' => $booking,
                    'actionText' => 'View My Booking',
                    'actionUrl' => route('account.bookings.show', $booking->id),
                ]
            );
        }
    }

    /**
     * Notify customer when booking is accepted by admin.
     */
    public static function notifyBookingAccepted($booking): void
    {
        $email = $booking->customer_email ?? $booking->user?->email;
        $name = $booking->customer_name ?? $booking->user?->name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Good News! Your Booking #{$booking->booking_reference} has been ACCEPTED",
                'emails.booking_notification',
                [
                    'title' => 'Booking Accepted',
                    'heading' => 'Congratulations ' . $name . '!',
                    'message' => 'Your booking for ' . $booking->booked_item_name . ' on ' . ($booking->formatted_event_date ?? $booking->event_date) . ' has been ACCEPTED by Aditya Utsav. You can now communicate with our team and submit your advance payment to lock your auspicious date.',
                    'extraDetails' => [
                        'Booking Reference' => $booking->booking_reference,
                        'Booked Item' => $booking->booked_item_name,
                        'Event Date' => $booking->formatted_event_date ?? $booking->event_date,
                        'Total Amount' => $booking->formatted_estimated_total,
                        'Booking Status' => 'ACCEPTED',
                    ],
                    'actionText' => 'View Booking & Pay Advance',
                    'actionUrl' => route('account.bookings.show', $booking->id),
                ]
            );
        }
    }

    /**
     * Notify customer when booking is rejected.
     */
    public static function notifyBookingRejected($booking, ?string $reason = null): void
    {
        $email = $booking->customer_email ?? $booking->user?->email;
        $name = $booking->customer_name ?? $booking->user?->name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Aditya Utsav Booking Update — #{$booking->booking_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Booking Update',
                    'heading' => 'Namaste ' . $name . ',',
                    'message' => 'We regret to inform you that we are unable to accept your booking request for ' . $booking->booked_item_name . ' on ' . ($booking->formatted_event_date ?? $booking->event_date) . ($reason ? '. Reason: ' . $reason : '. Our slots for this date are fully committed.') . ' Please contact our office for custom alternatives or alternate dates.',
                    'booking' => $booking,
                    'actionText' => 'Contact Aditya Utsav',
                    'actionUrl' => route('contact'),
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
     * Notify customer when payment request is submitted.
     */
    public static function notifyPaymentSubmitted($payment): void
    {
        $customer = $payment->customer ?? $payment->booking?->user;
        $email = $customer?->email ?? $payment->booking?->customer_email;
        $name = $customer?->name ?? $payment->booking?->customer_name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Payment Request Submitted — Ref #{$payment->payment_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Payment Verification Pending',
                    'heading' => 'Payment Submitted: ₹' . number_format($payment->amount, 2),
                    'message' => 'We have received your payment request of ₹' . number_format($payment->amount, 2) . ' via ' . $payment->payment_method_label . ' (Ref: ' . $payment->payment_reference . ') for booking #' . ($payment->booking?->booking_reference ?? '') . '. Our accounts team will verify the transaction and generate your official receipt shortly.',
                    'extraDetails' => [
                        'Payment Reference' => $payment->payment_reference,
                        'Booking Reference' => $payment->booking?->booking_reference,
                        'Payment Amount' => '₹' . number_format($payment->amount, 2),
                        'Payment Method' => $payment->payment_method_label,
                        'Payment Date' => $payment->payment_date ? $payment->payment_date->format('d M Y') : date('d M Y'),
                        'Verification Status' => 'Pending Verification',
                    ],
                    'actionText' => 'View My Payments',
                    'actionUrl' => route('account.payments.index'),
                ]
            );
        }
    }

    /**
     * Notify customer when payment is accepted & receipt is generated.
     */
    public static function notifyPaymentAccepted($payment): void
    {
        $customer = $payment->customer ?? $payment->booking?->user;
        $email = $customer?->email ?? $payment->booking?->customer_email;
        $name = $customer?->name ?? $payment->booking?->customer_name ?? 'Valued Client';
        $booking = $payment->booking;

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Payment Verified & Receipt Generated — #{$payment->receipt_number}",
                'emails.booking_notification',
                [
                    'title' => 'Payment Verified & Accepted',
                    'heading' => 'Payment Verified: ₹' . number_format($payment->amount, 2),
                    'message' => 'Your payment of ₹' . number_format($payment->amount, 2) . ' for booking #' . ($booking?->booking_reference ?? '') . ' has been verified and confirmed. Official receipt #' . ($payment->receipt_number ?? $payment->payment_reference) . ' has been generated.',
                    'extraDetails' => [
                        'Receipt Number' => $payment->receipt_number ?? $payment->payment_reference,
                        'Booking Reference' => $booking?->booking_reference,
                        'Decoration / Package' => $booking?->booked_item_name,
                        'Amount Verified' => '₹' . number_format($payment->amount, 2),
                        'Total Paid So Far' => $booking ? $booking->formatted_total_paid : '₹' . number_format($payment->amount, 2),
                        'Remaining Balance' => $booking ? $booking->formatted_balance_due : '₹0',
                        'Payment Status' => $booking ? $booking->payment_status_label : 'Verified',
                    ],
                    'actionText' => 'View & Print Receipt',
                    'actionUrl' => route('account.payments.receipt', $payment->id),
                ]
            );
        }
    }

    /**
     * Notify customer when payment is rejected.
     */
    public static function notifyPaymentRejected($payment, ?string $reason = null): void
    {
        $customer = $payment->customer ?? $payment->booking?->user;
        $email = $customer?->email ?? $payment->booking?->customer_email;
        $name = $customer?->name ?? $payment->booking?->customer_name ?? 'Valued Client';

        if ($email) {
            self::sendEmail(
                $email,
                $name,
                "Payment Request Update — Ref #{$payment->payment_reference}",
                'emails.booking_notification',
                [
                    'title' => 'Payment Verification Issue',
                    'heading' => 'Payment Request Not Verified',
                    'message' => 'Your submitted payment request of ₹' . number_format($payment->amount, 2) . ' (Ref: ' . $payment->payment_reference . ') could not be verified by our accounts department.' . ($reason ? ' Reason: ' . $reason : '') . ' Please verify your transaction reference / bank UTR and submit again, or contact our support team.',
                    'extraDetails' => [
                        'Payment Reference' => $payment->payment_reference,
                        'Booking Reference' => $payment->booking?->booking_reference,
                        'Status' => 'Rejected',
                        'Reason' => $reason ?? 'Transaction could not be matched with bank statement',
                    ],
                    'actionText' => 'View Payment Details',
                    'actionUrl' => route('account.payments.index'),
                ]
            );
        }
    }

    /**
     * Backward compatibility alias
     */
    public static function notifyPaymentRecorded($payment): void
    {
        self::notifyPaymentAccepted($payment);
    }
}
