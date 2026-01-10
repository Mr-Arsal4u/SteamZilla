<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\EmailService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     * Send emails and SMS when a new booking is created
     * 
     * Note: This observer ensures emails are sent automatically whenever
     * a booking is created, regardless of where it's created from.
     */
    public function created(Booking $booking)
    {
        // Only send notifications if booking is confirmed/paid
        // This prevents sending emails for draft or pending bookings
        if (in_array($booking->status, ['confirmed', 'paid']) || $booking->payment_status === 'paid') {
            try {
                // Reload booking with relationships to ensure addons are loaded
                $booking->refresh();
                $booking->load(['package', 'bookingAddons.addon']);
                
                // Send emails (customer + admin)
                EmailService::sendBookingEmails($booking);
                
                // Send SMS to admin
                SmsService::sendBookingNotification($booking);
                
                // Send SMS confirmation to customer
                SmsService::sendBookingConfirmation($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send booking notifications via observer', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking)
    {
        // You can add logic here if you want to send emails on status changes
        // For example, send email when booking status changes to 'completed'
    }
}

