<?php

namespace App\Services;

use App\Models\Booking;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send booking confirmation emails to customer and admin
     * 
     * @param Booking $booking
     * @return array ['customer' => bool, 'admin' => bool]
     */
    public static function sendBookingEmails(Booking $booking)
    {
        $results = [
            'customer' => false,
            'admin' => false,
        ];

        // Ensure relationships are loaded
        $booking->load(['package', 'bookingAddons.addon']);

        // Send email to customer
        try {
            if (empty($booking->user_email)) {
                Log::warning('Booking email not sent: Customer email is empty', [
                    'booking_id' => $booking->id,
                ]);
            } else {
                Mail::to($booking->user_email)->send(new BookingConfirmation($booking));
                $results['customer'] = true;
                Log::info('Booking confirmation email sent to customer', [
                    'booking_id' => $booking->id,
                    'email' => $booking->user_email,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email to customer', [
                'booking_id' => $booking->id,
                'email' => $booking->user_email,
                'error' => $e->getMessage(),
            ]);
        }

        // Send email to admin
        try {
            $adminEmail = config('mail.admin_email');
            
            if (empty($adminEmail)) {
                Log::warning('Admin email not configured. Skipping admin notification.', [
                    'booking_id' => $booking->id,
                ]);
            } else {
                Mail::to($adminEmail)->send(new NewBookingNotification($booking));
                $results['admin'] = true;
                Log::info('Booking notification email sent to admin', [
                    'booking_id' => $booking->id,
                    'admin_email' => $adminEmail,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send booking notification email to admin', [
                'booking_id' => $booking->id,
                'admin_email' => config('mail.admin_email'),
                'error' => $e->getMessage(),
            ]);
        }

        return $results;
    }

    /**
     * Send test email to verify SendGrid configuration
     * 
     * @param string $to
     * @return bool
     */
    public static function sendTestEmail($to)
    {
        try {
            Mail::raw('This is a test email from SteamZilla. Your SendGrid configuration is working correctly!', function ($message) use ($to) {
                $message->to($to)
                        ->subject('SteamZilla - SendGrid Test Email');
            });
            
            Log::info('Test email sent successfully', ['to' => $to]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}

