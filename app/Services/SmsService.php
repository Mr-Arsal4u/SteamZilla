<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send SMS notification to admin about new booking
     */
    public static function sendBookingNotification($booking)
    {
        $adminPhone = config('sms.admin_phone');
        
        if (empty($adminPhone)) {
            Log::warning('SMS admin phone not configured. Skipping SMS notification.');
            return false;
        }

        $provider = config('sms.provider', 'twilio');
        
        try {
            switch ($provider) {
                case 'twilio':
                    return self::sendViaTwilio($adminPhone, self::formatBookingMessage($booking));
                case 'aws_sns':
                    return self::sendViaAwsSns($adminPhone, self::formatBookingMessage($booking));
                default:
                    Log::error("Unknown SMS provider: {$provider}");
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS confirmation to customer about their booking
     */
    public static function sendBookingConfirmation($booking)
    {
        $customerPhone = $booking->user_phone;
        
        if (empty($customerPhone)) {
            Log::warning('Customer phone not provided. Skipping SMS confirmation.');
            return false;
        }

        $provider = config('sms.provider', 'twilio');
        
        try {
            switch ($provider) {
                case 'twilio':
                    return self::sendViaTwilio($customerPhone, self::formatCustomerBookingMessage($booking));
                case 'aws_sns':
                    return self::sendViaAwsSns($customerPhone, self::formatCustomerBookingMessage($booking));
                default:
                    Log::error("Unknown SMS provider: {$provider}");
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Customer booking SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format booking message for SMS (admin notification)
     */
    private static function formatBookingMessage($booking)
    {
        $date = $booking->booking_date->format('M j, Y');
        $time = \Carbon\Carbon::parse($booking->booking_time)->format('g:i A');
        $total = number_format($booking->total_price, 2);
        
        return "🔔 NEW BOOKING #{$booking->id}\n\n" .
               "Customer: {$booking->user_name}\n" .
               "Phone: {$booking->user_phone}\n" .
               "Package: {$booking->package->name}\n" .
               "Date: {$date} at {$time}\n" .
               "Address: {$booking->address}\n" .
               "Total: \${$total}\n\n" .
               "Payment: {$booking->payment_status}";
    }

    /**
     * Format booking confirmation message for customer SMS
     */
    private static function formatCustomerBookingMessage($booking)
    {
        $date = $booking->booking_date->format('M j, Y');
        $time = \Carbon\Carbon::parse($booking->booking_time)->format('g:i A');
        $total = number_format($booking->total_price, 2);
        
        $message = "✅ Booking Confirmed!\n\n";
        $message .= "Hi {$booking->user_name},\n\n";
        $message .= "Your SteamZilla booking has been confirmed!\n\n";
        $message .= "Booking #{$booking->id}\n";
        $message .= "Package: {$booking->package->name}\n";
        $message .= "Date: {$date}\n";
        $message .= "Time: {$time}\n";
        $message .= "Address: {$booking->address}\n";
        $message .= "Total: \${$total}\n";
        
        if ($booking->payment_status === 'paid') {
            $message .= "Payment: Paid ✅\n";
        }
        
        $message .= "\nWe'll see you soon!";
        
        return $message;
    }

    /**
     * Send SMS via Twilio
     */
    private static function sendViaTwilio($to, $message)
    {
        $accountSid = config('sms.twilio.account_sid');
        $authToken = config('sms.twilio.auth_token');
        $fromNumber = config('sms.twilio.from_number');

        if (empty($accountSid) || empty($authToken) || empty($fromNumber)) {
            Log::error('Twilio credentials not configured');
            return false;
        }

        try {
            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $fromNumber,
                    'To' => self::formatPhoneNumber($to),
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully via Twilio', [
                    'to' => $to,
                    'message_sid' => $response->json('sid'),
                ]);
                return true;
            } else {
                Log::error('Twilio SMS failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Twilio SMS exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS via AWS SNS
     */
    private static function sendViaAwsSns($to, $message)
    {
        $accessKey = config('sms.aws_sns.access_key');
        $secretKey = config('sms.aws_sns.secret_key');
        $region = config('sms.aws_sns.region', 'us-east-1');

        if (empty($accessKey) || empty($secretKey)) {
            Log::error('AWS SNS credentials not configured');
            return false;
        }

        // AWS SNS requires AWS SDK
        // This is a simplified implementation - you may need to install aws/aws-sdk-php
        try {
            // Note: This requires aws/aws-sdk-php package
            // For now, we'll use HTTP client as a fallback
            Log::warning('AWS SNS implementation requires AWS SDK. Please install aws/aws-sdk-php or use Twilio.');
            return false;
        } catch (\Exception $e) {
            Log::error('AWS SNS SMS exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number to E.164 format
     */
    private static function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If it doesn't start with +, assume US number and add +1
        if (!str_starts_with($phone, '1') && strlen($phone) == 10) {
            $phone = '1' . $phone;
        }
        
        return '+' . $phone;
    }

    /**
     * Send SMS notification for gift card delivery
     */
    public static function sendGiftCardNotification($giftCard)
    {
        $recipientPhone = $giftCard->recipient_phone;
        
        if (empty($recipientPhone)) {
            Log::warning('Gift card recipient phone not provided. Skipping SMS notification.');
            return false;
        }

        $provider = config('sms.provider', 'twilio');
        
        try {
            switch ($provider) {
                case 'twilio':
                    return self::sendViaTwilio($recipientPhone, self::formatGiftCardMessage($giftCard));
                case 'aws_sns':
                    return self::sendViaAwsSns($recipientPhone, self::formatGiftCardMessage($giftCard));
                default:
                    Log::error("Unknown SMS provider: {$provider}");
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Gift card SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format gift card message for SMS
     */
    private static function formatGiftCardMessage($giftCard)
    {
        $message = "🎁 You've received a SteamZilla Gift Card!\n\n";
        
        if ($giftCard->sender_name) {
            $message .= "From: {$giftCard->sender_name}\n";
        }
        
        if ($giftCard->message) {
            $message .= "Message: {$giftCard->message}\n\n";
        }
        
        $message .= "Gift Card #: {$giftCard->gift_card_number}\n";
        
        if ($giftCard->pin) {
            $message .= "PIN: {$giftCard->pin}\n";
        }
        
        $message .= "Value: $" . number_format($giftCard->amount, 2) . "\n\n";
        $message .= "Use it at: " . route('gift-cards');
        
        return $message;
    }

    /**
     * Send custom SMS message
     */
    public static function send($to, $message)
    {
        $provider = config('sms.provider', 'twilio');
        
        try {
            switch ($provider) {
                case 'twilio':
                    return self::sendViaTwilio($to, $message);
                case 'aws_sns':
                    return self::sendViaAwsSns($to, $message);
                default:
                    Log::error("Unknown SMS provider: {$provider}");
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}

