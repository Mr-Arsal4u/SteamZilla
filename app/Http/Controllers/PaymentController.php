<?php

namespace App\Http\Controllers;

use Square\Environment;
use Square\SquareClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Square\Legacy\Exceptions\ApiException;
use App\Models\Booking; // Assuming you have a Booking model

class PaymentController extends Controller
{
    private $squareClient;

    public function __construct()
    {
        // Initialize Square client
        $this->squareClient = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT') === 'production'
                ? Environment::PRODUCTION
                : Environment::SANDBOX,
        ]);
    }

    /**
     * Show payment page
     */
    public function showPaymentPage(Request $request)
    {
        // Get booking details from session or request
        $bookingData = session('booking_data');

        return view('booking.payment', [
            'applicationId' => env('SQUARE_APPLICATION_ID'),
            'locationId' => env('SQUARE_LOCATION_ID'),
            'bookingData' => $bookingData,
        ]);
    }

    /**
     * Process payment from Square payment form
     */
    public function processPayment(Request $request)
    {
        try {
            // Validate incoming request
            $validated = $request->validate([
                'sourceId' => 'required|string', // Token from Square's card form
                'amount' => 'required|numeric|min:1',
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'service_type' => 'required|string',
                'booking_date' => 'required|date',
                'booking_time' => 'required|string',
            ]);

            // Convert amount to cents (Square uses smallest currency unit)
            $amountInCents = (int)($validated['amount'] * 100);

            // Create payment request
            $paymentsApi = $this->squareClient->getPaymentsApi();

            $paymentResponse = $paymentsApi->createPayment([
                'source_id' => $validated['sourceId'],
                'idempotency_key' => uniqid(), // Unique key to prevent duplicate charges
                'amount_money' => [
                    'amount' => $amountInCents,
                    'currency' => 'USD', // Change to your currency
                ],
                'location_id' => env('SQUARE_LOCATION_ID'),
                'note' => 'Car detailing service - ' . $validated['service_type'],
                'buyer_email_address' => $validated['customer_email'],
            ]);

            $payment = $paymentResponse->getResult()->getPayment();

            // Save booking to database
            $booking = $this->saveBooking($validated, $payment);

            // Send confirmation email
            $this->sendConfirmationEmail($booking);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'booking_id' => $booking->id,
                'transaction_id' => $payment->getId(),
                'redirect_url' => route('booking.success', $booking->id),
            ]);

        } catch (ApiException $e) {
            Log::error('Square API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    /**
     * Save booking to database
     */
    private function saveBooking($data, $payment)
    {
        $booking = Booking::create([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'service_type' => $data['service_type'],
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'],
            'amount' => $data['amount'],
            'payment_status' => 'paid',
            'transaction_id' => $payment->getId(),
            'payment_method' => 'square',
            'square_payment_id' => $payment->getId(),
            'square_receipt_url' => $payment->getReceiptUrl(),
        ]);

        return $booking;
    }

    /**
     * Send confirmation email to customer
     */
    private function sendConfirmationEmail($booking)
    {
        // Basic email sending - customize as needed
        try {
            Mail::send('emails.booking-confirmation', ['booking' => $booking], function ($message) use ($booking) {
                $message->to($booking->customer_email)
                        ->subject('Booking Confirmation - Car Detailing Service');
            });
        } catch (\Exception $e) {
            Log::error('Email Error: ' . $e->getMessage());
        }
    }

    /**
     * Show success page after payment
     */
    public function success($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        return view('booking.success', [
            'booking' => $booking,
        ]);
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        return view('booking.cancel');
    }

    /**
     * Refund a payment (if needed)
     */
    public function refundPayment($bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            if ($booking->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be refunded.',
                ], 400);
            }

            $refundsApi = $this->squareClient->getRefundsApi();

            $refundResponse = $refundsApi->refundPayment([
                'idempotency_key' => uniqid(),
                'amount_money' => [
                    'amount' => (int)($booking->amount * 100),
                    'currency' => 'USD',
                ],
                'payment_id' => $booking->square_payment_id,
                'reason' => 'Customer requested refund',
            ]);

            // Update booking status
            $booking->update([
                'payment_status' => 'refunded',
                'refund_id' => $refundResponse->getResult()->getRefund()->getId(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully.',
            ]);

        } catch (ApiException $e) {
            Log::error('Refund Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Refund failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Verify payment status (useful for double-checking)
     */
    public function verifyPayment($paymentId)
    {
        try {
            $paymentsApi = $this->squareClient->getPaymentsApi();
            $response = $paymentsApi->getPayment($paymentId);
            $payment = $response->getResult()->getPayment();

            return response()->json([
                'success' => true,
                'status' => $payment->getStatus(),
                'amount' => $payment->getAmountMoney()->getAmount() / 100,
            ]);

        } catch (ApiException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not verify payment.',
            ], 400);
        }
    }
}
