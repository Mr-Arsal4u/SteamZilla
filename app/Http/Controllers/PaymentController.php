<?php

namespace App\Http\Controllers;

use Square\Environments;
use Square\SquareClient;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Payments\Requests\GetPaymentsRequest;
use Square\Refunds\Requests\RefundPaymentRequest;
use Square\Types\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Square\Exceptions\SquareApiException;
use App\Models\Booking;
use App\Models\GiftCard;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
use App\Services\SmsService;

class PaymentController extends Controller
{
    private $squareClient;

    public function __construct()
    {
        // Initialize Square client
        $environment = config('services.square.environment') === 'production'
            ? Environments::Production
            : Environments::Sandbox;
        
        $this->squareClient = new SquareClient(
            config('services.square.access_token'),
            null, // version (uses default)
            [
                'baseUrl' => $environment->value,
            ]
        );
    }

    /**
     * Get user-friendly error message from Square error codes
     */
    private function getFriendlyErrorMessage($errors)
    {
        if (empty($errors)) {
            return 'We encountered an issue processing your payment. Please try again or contact support.';
        }

        $errorCodes = [];
        $errorMessages = [];

        foreach ($errors as $error) {
            $code = $error->getCode() ?? '';
            $detail = $error->getDetail() ?? '';
            $category = $error->getCategory() ?? '';

            $errorCodes[] = $code;

            // Map error codes to user-friendly messages
            switch ($code) {
                case 'CVV_FAILURE':
                    $errorMessages[] = 'The security code (CVV) on your card is incorrect. Please check and try again.';
                    break;
                case 'GENERIC_DECLINE':
                case 'CARD_DECLINED':
                case 'PROCESSOR_DECLINE':
                    $errorMessages[] = 'Your card was declined. Please check your card details or try a different payment method.';
                    break;
                case 'INSUFFICIENT_FUNDS':
                    $errorMessages[] = 'Your card has insufficient funds. Please use a different payment method.';
                    break;
                case 'EXPIRED_CARD':
                    $errorMessages[] = 'Your card has expired. Please use a different card.';
                    break;
                case 'INVALID_EXPIRATION':
                    $errorMessages[] = 'The card expiration date is invalid. Please check and try again.';
                    break;
                case 'INVALID_POSTAL_CODE':
                    $errorMessages[] = 'The postal code you entered is invalid. Please check and try again.';
                    break;
                case 'ADDRESS_VERIFICATION_FAILURE':
                    $errorMessages[] = 'The billing address could not be verified. Please check your address and try again.';
                    break;
                case 'CARD_NOT_SUPPORTED':
                    $errorMessages[] = 'This card type is not supported. Please use a different payment method.';
                    break;
                case 'INVALID_CARD':
                    $errorMessages[] = 'The card information is invalid. Please check your card details and try again.';
                    break;
                case 'PAYMENT_METHOD_ERROR':
                    if (strpos($detail, 'CVV') !== false) {
                        $errorMessages[] = 'The security code (CVV) on your card is incorrect. Please check and try again.';
                    } else {
                        $errorMessages[] = 'There was an issue with your payment method. Please check your card details and try again.';
                    }
                    break;
                default:
                    // For unknown errors, provide a generic but helpful message
                    if (!empty($detail) && strpos($detail, 'Authorization error') === false) {
                        // Use detail if it's user-friendly, otherwise use generic message
                        $errorMessages[] = 'We encountered an issue processing your payment. Please verify your card details and try again.';
                    } else {
                        $errorMessages[] = 'Your payment could not be processed. Please check your card details or try a different payment method.';
                    }
                    break;
            }
        }

        // Return the first meaningful error message, or a generic one
        if (!empty($errorMessages)) {
            return $errorMessages[0];
        }

        return 'We encountered an issue processing your payment. Please try again or contact support if the problem persists.';
    }

    /**
     * Show payment page for booking
     */
    public function showPaymentPage(Request $request)
    {
        // Get booking details from session
        $bookingData = Session::get('booking_data', []);

        // Log session data for debugging
        Log::info('Payment page - booking data check', [
            'has_data' => !empty($bookingData),
            'keys' => !empty($bookingData) ? array_keys($bookingData) : [],
            'address' => $bookingData['address'] ?? 'missing',
            'package_id' => $bookingData['package_id'] ?? 'missing',
            'total_price' => $bookingData['total_price'] ?? 'missing',
        ]);

        // Check if essential booking data is present
        if (empty($bookingData) || 
            !isset($bookingData['address']) || 
            empty($bookingData['address']) ||
            !isset($bookingData['package_id']) || 
            !isset($bookingData['total_price']) ||
            !isset($bookingData['vehicle_type'])) {
            Log::warning('Payment page accessed with incomplete booking data', [
                'booking_data_keys' => array_keys($bookingData ?? []),
                'session_id' => Session::getId(),
            ]);
            return redirect()->route('booking.step1')
                ->with('error', 'Please complete the booking steps first. Your session may have expired.');
        }

        $applicationId = config('services.square.application_id');
        $locationId = config('services.square.location_id');

        // If location ID is not set, try to fetch it from Square API
        if (empty($locationId)) {
            try {
                $locationsResponse = $this->squareClient->locations->list();
                $errors = $locationsResponse->getErrors();
                
                if (empty($errors)) {
                    $locations = $locationsResponse->getLocations();
                    if (!empty($locations) && isset($locations[0])) {
                        $locationId = $locations[0]->getId();
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch Square locations: ' . $e->getMessage());
            }
        }

        if (empty($locationId)) {
            return redirect()->route('booking.step4')
                ->with('error', 'Square location ID is not configured. Please set SQUARE_LOCATION_ID in your .env file.');
        }

        return view('booking.payment', [
            'applicationId' => $applicationId,
            'locationId' => $locationId,
            'bookingData' => $bookingData,
            'environment' => config('services.square.environment', 'sandbox'),
        ]);
    }

    /**
     * Process payment from Square payment form for booking
     */
    public function processPayment(Request $request)
    {
        try {
            $bookingData = Session::get('booking_data');
            
            if (empty($bookingData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking data not found. Please start over.',
                ], 400);
            }

            // Validate required booking data is present
            $requiredFields = ['address', 'vehicle_type', 'booking_date', 'booking_time', 'package_id', 'total_price'];
            foreach ($requiredFields as $field) {
                if (!isset($bookingData[$field]) || empty($bookingData[$field])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Incomplete booking information. Please start over.',
                    ], 400);
                }
            }

            // Validate incoming request
            $validated = $request->validate([
                'sourceId' => 'required|string', // Token from Square's card form
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|max:255',
                'user_phone' => 'required|string|max:20',
                'notes' => 'nullable|string',
            ]);

            // Validate and convert amount to cents (Square uses smallest currency unit)
            $totalPrice = (float)($bookingData['total_price'] ?? 0);
            
            if ($totalPrice <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid booking amount. Please start over.',
                ], 400);
            }
            
            $amountInCents = (int)round($totalPrice * 100);

            // Get location ID (fetch if not set)
            $locationId = config('services.square.location_id');
            if (empty($locationId)) {
                try {
                    $locationsResponse = $this->squareClient->locations->list();
                    $errors = $locationsResponse->getErrors();
                    
                    if (empty($errors)) {
                        $locations = $locationsResponse->getLocations();
                        if (!empty($locations) && isset($locations[0])) {
                            $locationId = $locations[0]->getId();
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch Square locations in processPayment: ' . $e->getMessage());
                }
            }

            if (empty($locationId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Square location ID is not configured. Please contact support.',
                ], 400);
            }

            // Create payment request
            $paymentsApi = $this->squareClient->payments;

            $amountMoney = new Money([
                'amount' => $amountInCents,
                'currency' => 'USD',
            ]);

            $body = new CreatePaymentRequest([
                'sourceId' => $validated['sourceId'],
                'idempotencyKey' => uniqid(),
                'amountMoney' => $amountMoney,
                'locationId' => $locationId,
                'note' => 'Car detailing service booking',
                'buyerEmailAddress' => $validated['user_email'],
            ]);

            $paymentResponse = $paymentsApi->create($body);

            $errors = $paymentResponse->getErrors();
            if (!empty($errors)) {
                // Log full error details for debugging
                Log::error('Square Payment Error (Booking)', [
                    'errors' => array_map(function($error) {
                        return [
                            'code' => $error->getCode(),
                            'detail' => $error->getDetail(),
                            'category' => $error->getCategory(),
                        ];
                    }, $errors),
                ]);

                $errorMessage = $this->getFriendlyErrorMessage($errors);
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 400);
            }

            $payment = $paymentResponse->getPayment();

            if (!$payment || !$payment->getId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment was processed but no payment ID was returned. Please contact support.',
                ], 500);
            }

            // Save booking to database
            $booking = $this->saveBooking($bookingData, $validated, $payment);

            // Emails and SMS are automatically sent via BookingObserver
            // when booking is created with status 'confirmed' and payment_status 'paid'

            // Clear session
            Session::forget('booking_data');

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'booking_id' => $booking->id,
                'transaction_id' => $payment->getId(),
                'redirect_url' => route('booking.success', $booking->id),
            ]);

        } catch (SquareApiException $e) {
            // Log full error details for debugging
            Log::error('Square API Exception (Booking)', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            // Provide user-friendly error message
            $errorMessage = 'We encountered an issue processing your payment. Please verify your card details and try again.';
            
            // Try to extract error code from message if possible
            $message = $e->getMessage();
            if (stripos($message, 'CVV') !== false || stripos($message, 'cvv') !== false) {
                $errorMessage = 'The security code (CVV) on your card is incorrect. Please check and try again.';
            } elseif (stripos($message, 'decline') !== false || stripos($message, 'DECLINE') !== false) {
                $errorMessage = 'Your card was declined. Please check your card details or try a different payment method.';
            } elseif (stripos($message, 'insufficient') !== false || stripos($message, 'funds') !== false) {
                $errorMessage = 'Your card has insufficient funds. Please use a different payment method.';
            } elseif (stripos($message, 'expired') !== false) {
                $errorMessage = 'Your card has expired. Please use a different card.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
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
    private function saveBooking($bookingData, $validated, $payment)
    {
        $booking = Booking::create([
            'user_name' => $validated['user_name'],
            'user_email' => $validated['user_email'],
            'user_phone' => $validated['user_phone'],
            'address' => $bookingData['address'],
            'latitude' => $bookingData['latitude'] ?? null,
            'longitude' => $bookingData['longitude'] ?? null,
            'place_id' => $bookingData['place_id'] ?? null,
            'vehicle_type' => $bookingData['vehicle_type'],
            'booking_date' => $bookingData['booking_date'],
            'booking_time' => $bookingData['booking_time'],
            'package_id' => $bookingData['package_id'],
            'status' => 'confirmed',
            'notes' => $validated['notes'] ?? null,
            'total_price' => $bookingData['total_price'],
            'payment_method' => 'square',
            'gift_card_id' => null,
            'gift_card_discount' => 0,
            'square_payment_id' => $payment->getId(),
            'square_receipt_url' => $payment->getReceiptUrl() ?? null,
            'payment_status' => 'paid',
        ]);

        // Attach addons
        if (!empty($bookingData['addons'])) {
            foreach ($bookingData['addons'] as $addonData) {
                $booking->bookingAddons()->create([
                    'addon_id' => $addonData['id'],
                    'quantity' => $addonData['quantity'],
                    'price_at_booking' => $addonData['price'],
                ]);
            }
        }

        // Load relationships for email templates
        $booking->load(['package', 'bookingAddons.addon']);

        return $booking;
    }

    /**
     * Process gift card payment
     */
    public function processGiftCardPayment(Request $request)
    {
        try {
            $giftCardData = Session::get('gift_card_data');
            
            if (empty($giftCardData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gift card data not found. Please start over.',
                ], 400);
            }

            // Validate incoming request
            $validated = $request->validate([
                'sourceId' => 'required|string',
            ]);

            // Calculate discount
            $pricing = GiftCard::calculateDiscount($giftCardData['amount']);
            $amountInCents = (int)($pricing['final'] * 100);

            // Get location ID (fetch if not set)
            $locationId = config('services.square.location_id');
            if (empty($locationId)) {
                try {
                    $locationsResponse = $this->squareClient->locations->list();
                    $errors = $locationsResponse->getErrors();
                    
                    if (empty($errors)) {
                        $locations = $locationsResponse->getLocations();
                        if (!empty($locations) && isset($locations[0])) {
                            $locationId = $locations[0]->getId();
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to fetch Square locations in processGiftCardPayment: ' . $e->getMessage());
                }
            }

            if (empty($locationId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Square location ID is not configured. Please contact support.',
                ], 400);
            }

            // Create payment request
            $paymentsApi = $this->squareClient->payments;

            $amountMoney = new Money([
                'amount' => $amountInCents,
                'currency' => 'USD',
            ]);

            $body = new CreatePaymentRequest([
                'sourceId' => $validated['sourceId'],
                'idempotencyKey' => uniqid(),
                'amountMoney' => $amountMoney,
                'locationId' => $locationId,
                'note' => 'Gift card purchase',
                'buyerEmailAddress' => $giftCardData['sender_email'],
            ]);

            $paymentResponse = $paymentsApi->create($body);

            $errors = $paymentResponse->getErrors();
            if (!empty($errors)) {
                // Log full error details for debugging
                Log::error('Square Payment Error (Gift Card)', [
                    'errors' => array_map(function($error) {
                        return [
                            'code' => $error->getCode(),
                            'detail' => $error->getDetail(),
                            'category' => $error->getCategory(),
                        ];
                    }, $errors),
                ]);

                $errorMessage = $this->getFriendlyErrorMessage($errors);
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 400);
            }

            $payment = $paymentResponse->getPayment();

            // Create gift card
            $giftCard = GiftCard::create([
                'gift_card_number' => GiftCard::generateCardNumber(),
                'pin' => GiftCard::generatePIN(),
                'amount' => $giftCardData['amount'],
                'original_purchase_amount' => $giftCardData['amount'],
                'discount_applied' => $pricing['discount'],
                'sender_name' => $giftCardData['sender_name'],
                'sender_email' => $giftCardData['sender_email'],
                'recipient_name' => $giftCardData['recipient_name'],
                'recipient_email' => $giftCardData['recipient_email'] ?? null,
                'recipient_phone' => $giftCardData['recipient_phone'] ?? null,
                'delivery_method' => $giftCardData['delivery_method'],
                'delivery_datetime' => $giftCardData['delivery_datetime'],
                'message' => $giftCardData['message'] ?? null,
                'status' => 'active',
                'expires_at' => now()->addYears(2),
                'square_payment_id' => $payment->getId(),
                'square_receipt_url' => $payment->getReceiptUrl() ?? null,
                'payment_status' => 'paid',
            ]);

            // Create transaction
            \App\Models\GiftCardTransaction::create([
                'gift_card_id' => $giftCard->id,
                'type' => 'purchase',
                'amount' => $giftCardData['amount'],
                'discount_amount' => $pricing['discount'],
                'final_paid_amount' => $pricing['final'],
            ]);

            // Send delivery email or SMS
            try {
                if ($giftCard->delivery_method === 'email' && $giftCard->recipient_email) {
                    Mail::to($giftCard->recipient_email)->send(new \App\Mail\GiftCardDelivery($giftCard));
                } elseif ($giftCard->delivery_method === 'self' && $giftCard->sender_email) {
                    Mail::to($giftCard->sender_email)->send(new \App\Mail\GiftCardDelivery($giftCard));
                } elseif ($giftCard->delivery_method === 'sms' && $giftCard->recipient_phone) {
                    SmsService::sendGiftCardNotification($giftCard);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send gift card delivery notification: ' . $e->getMessage());
            }

            Session::forget('gift_card_data');

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'gift_card_id' => $giftCard->id,
                'redirect_url' => route('gift-cards.success', $giftCard->id),
            ]);

        } catch (SquareApiException $e) {
            // Log full error details for debugging
            Log::error('Square API Exception (Gift Card)', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            // Provide user-friendly error message
            $errorMessage = 'We encountered an issue processing your payment. Please verify your card details and try again.';
            
            // Try to extract error code from message if possible
            $message = $e->getMessage();
            if (stripos($message, 'CVV') !== false || stripos($message, 'cvv') !== false) {
                $errorMessage = 'The security code (CVV) on your card is incorrect. Please check and try again.';
            } elseif (stripos($message, 'decline') !== false || stripos($message, 'DECLINE') !== false) {
                $errorMessage = 'Your card was declined. Please check your card details or try a different payment method.';
            } elseif (stripos($message, 'insufficient') !== false || stripos($message, 'funds') !== false) {
                $errorMessage = 'Your card has insufficient funds. Please use a different payment method.';
            } elseif (stripos($message, 'expired') !== false) {
                $errorMessage = 'Your card has expired. Please use a different card.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
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

            $refundsApi = $this->squareClient->refunds;

            $amountMoney = new Money([
                'amount' => (int)($booking->total_price * 100),
                'currency' => 'USD',
            ]);

            $body = new RefundPaymentRequest([
                'idempotencyKey' => uniqid(),
                'amountMoney' => $amountMoney,
                'paymentId' => $booking->square_payment_id,
                'reason' => 'Customer requested refund',
            ]);

            $refundResponse = $refundsApi->refundPayment($body);

            $errors = $refundResponse->getErrors();
            if (!empty($errors)) {
                $errorMessage = $errors[0]->getDetail() ?? 'Refund failed';
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 400);
            }

            $refund = $refundResponse->getRefund();

            // Update booking status
            $booking->update([
                'payment_status' => 'refunded',
                'square_refund_id' => $refund->getId(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully.',
            ]);

        } catch (SquareApiException $e) {
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
            $paymentsApi = $this->squareClient->payments;
            $request = new GetPaymentsRequest([
                'paymentId' => $paymentId,
            ]);
            $response = $paymentsApi->get($request);
            
            $errors = $response->getErrors();
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not verify payment.',
                ], 400);
            }

            $payment = $response->getPayment();

            return response()->json([
                'success' => true,
                'status' => $payment->getStatus(),
                'amount' => $payment->getAmountMoney()->getAmount() / 100,
            ]);

        } catch (SquareApiException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not verify payment.',
            ], 400);
        }
    }

    /**
     * Handle Square webhook notifications
     */
    public function handleWebhook(Request $request)
    {
        try {
            $signature = $request->header('X-Square-Signature');
            $body = $request->getContent();
            
            // Verify webhook signature (implement based on Square's webhook verification)
            // For now, we'll process the webhook
            
            $data = json_decode($body, true);
            
            if (isset($data['type']) && $data['type'] === 'payment.updated') {
                $paymentId = $data['data']['object']['payment']['id'];
                $status = $data['data']['object']['payment']['status'];
                
                // Update booking if exists
                $booking = Booking::where('square_payment_id', $paymentId)->first();
                if ($booking) {
                    $paymentStatus = match($status) {
                        'COMPLETED' => 'paid',
                        'FAILED', 'CANCELED' => 'failed',
                        default => 'pending',
                    };
                    $booking->update(['payment_status' => $paymentStatus]);
                }
                
                // Update gift card if exists
                $giftCard = GiftCard::where('square_payment_id', $paymentId)->first();
                if ($giftCard) {
                    $paymentStatus = match($status) {
                        'COMPLETED' => 'paid',
                        'FAILED', 'CANCELED' => 'failed',
                        default => 'pending',
                    };
                    $giftCard->update(['payment_status' => $paymentStatus]);
                }
            }
            
            return response()->json(['success' => true], 200);
            
        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
