<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Square\Environments;
use Square\SquareClient;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Payments\Requests\GetPaymentsRequest;
use Square\Refunds\Requests\RefundPaymentRequest;
use Square\Types\Money;
use Square\Exceptions\SquareApiException;
use App\Models\Booking;
use App\Models\GiftCard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TestSquarePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'square:test 
                            {--amount=10.00 : Amount to test with}
                            {--skip-payment : Skip actual payment creation}
                            {--skip-refund : Skip refund test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Square payment integration - verifies API connection, payment creation, and refund functionality';

    private $squareClient;
    private $testResults = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting Square Payment Integration Test...');
        $this->newLine();

        // Test 1: Configuration Check
        $this->testConfiguration();

        // Test 2: Square Client Initialization
        $this->testSquareClientInitialization();

        // Test 3: Location ID Validation
        $this->testLocationId();

        // Test 4: Payment Creation (if not skipped)
        if (!$this->option('skip-payment')) {
            $this->testPaymentCreation();
        } else {
            $this->warn('⏭️  Skipping payment creation test (--skip-payment flag)');
        }

        // Test 5: Refund Test (if payment was created and not skipped)
        if (!$this->option('skip-refund') && isset($this->testResults['payment_id'])) {
            $this->testRefund();
        } else {
            $this->warn('⏭️  Skipping refund test');
        }

        // Test 6: Database Fields Check
        $this->testDatabaseFields();

        // Summary
        $this->displaySummary();
    }

    /**
     * Test Square configuration
     */
    private function testConfiguration()
    {
        $this->info('📋 Test 1: Checking Configuration...');
        
        $accessToken = config('services.square.access_token');
        $applicationId = config('services.square.application_id');
        $locationId = config('services.square.location_id');
        $environment = config('services.square.environment', 'sandbox');

        $this->line("   Environment: {$environment}");
        
        if (empty($accessToken)) {
            $this->error('   ❌ SQUARE_ACCESS_TOKEN is not set');
            $this->testResults['config'] = false;
            return;
        } else {
            $this->line('   ✅ SQUARE_ACCESS_TOKEN is set');
        }

        if (empty($applicationId)) {
            $this->error('   ❌ SQUARE_APPLICATION_ID is not set');
            $this->testResults['config'] = false;
            return;
        } else {
            $this->line('   ✅ SQUARE_APPLICATION_ID is set');
        }

        if (empty($locationId)) {
            $this->warn('   ⚠️  SQUARE_LOCATION_ID is not set (will try to fetch from API)');
        } else {
            $this->line('   ✅ SQUARE_LOCATION_ID is set');
        }

        $this->testResults['config'] = true;
        $this->info('   ✅ Configuration check passed');
        $this->newLine();
    }

    /**
     * Test Square client initialization
     */
    private function testSquareClientInitialization()
    {
        $this->info('🔧 Test 2: Initializing Square Client...');
        
        try {
            $environment = config('services.square.environment') === 'production'
                ? Environments::Production
                : Environments::Sandbox;
            
            $this->squareClient = new SquareClient(
                config('services.square.access_token'),
                null,
                [
                    'baseUrl' => $environment->value,
                ]
            );

            $this->testResults['client_init'] = true;
            $this->info('   ✅ Square client initialized successfully');
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   ❌ Failed to initialize Square client: ' . $e->getMessage());
            $this->testResults['client_init'] = false;
            $this->newLine();
        }
    }

    /**
     * Test location ID
     */
    private function testLocationId()
    {
        $this->info('📍 Test 3: Validating Location ID...');
        
        if (!$this->squareClient) {
            $this->error('   ❌ Square client not initialized');
            $this->testResults['location'] = false;
            $this->newLine();
            return;
        }

        try {
            $locationId = config('services.square.location_id');
            
            if (empty($locationId)) {
                $this->line('   Fetching location ID from Square API...');
                $locationsResponse = $this->squareClient->locations->list();
                $errors = $locationsResponse->getErrors();
                
                if (!empty($errors)) {
                    $this->error('   ❌ Failed to fetch locations: ' . $errors[0]->getDetail());
                    $this->testResults['location'] = false;
                    $this->newLine();
                    return;
                }
                
                $locations = $locationsResponse->getLocations();
                if (empty($locations)) {
                    $this->error('   ❌ No locations found in Square account');
                    $this->testResults['location'] = false;
                    $this->newLine();
                    return;
                }
                
                $locationId = $locations[0]->getId();
                $this->line("   ✅ Fetched location ID: {$locationId}");
            } else {
                $this->line("   ✅ Using configured location ID: {$locationId}");
            }

            $this->testResults['location'] = $locationId;
            $this->info('   ✅ Location ID validated');
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   ❌ Location ID validation failed: ' . $e->getMessage());
            $this->testResults['location'] = false;
            $this->newLine();
        }
    }

    /**
     * Test payment creation
     */
    private function testPaymentCreation()
    {
        $this->info('💳 Test 4: Testing Payment Creation...');
        
        if (!$this->squareClient || !$this->testResults['location']) {
            $this->error('   ❌ Cannot test payment - prerequisites not met');
            $this->testResults['payment'] = false;
            $this->newLine();
            return;
        }

        $amount = (float) $this->option('amount');
        $amountInCents = (int) round($amount * 100);
        $locationId = $this->testResults['location'];

        $this->line("   Amount: \${$amount} ({$amountInCents} cents)");
        $this->line("   Location ID: {$locationId}");

        // Note: In a real test, you would need a valid sourceId from Square's card form
        // For this test, we'll use Square's test card token
        // In sandbox, you can use: cnon:card-nonce-ok (for successful payment)
        
        $this->warn('   ⚠️  Note: This test requires a valid card nonce.');
        $this->warn('   ⚠️  For sandbox testing, use Square\'s test card nonces.');
        $this->warn('   ⚠️  This test will attempt to create a payment but may fail without a valid nonce.');
        $this->newLine();

        if (!$this->confirm('   Do you want to proceed with payment creation test?', false)) {
            $this->warn('   ⏭️  Payment creation test skipped');
            $this->testResults['payment'] = 'skipped';
            $this->newLine();
            return;
        }

        try {
            $paymentsApi = $this->squareClient->payments;

            $amountMoney = new Money([
                'amount' => $amountInCents,
                'currency' => 'USD',
            ]);

            // For testing, we'll use a test nonce
            // In production, this would come from the frontend Square form
            $testSourceId = 'cnon:card-nonce-ok'; // Square sandbox test nonce

            $body = new CreatePaymentRequest([
                'sourceId' => $testSourceId,
                'idempotencyKey' => 'test-' . uniqid() . '-' . time(),
                'amountMoney' => $amountMoney,
                'locationId' => $locationId,
                'note' => 'Square Integration Test Payment',
                'buyerEmailAddress' => 'test@example.com',
            ]);

            $this->line('   Creating payment via Square API...');
            $paymentResponse = $paymentsApi->create($body);

            $errors = $paymentResponse->getErrors();
            if (!empty($errors)) {
                $errorMessage = $errors[0]->getDetail() ?? 'Payment failed';
                $this->error("   ❌ Payment creation failed: {$errorMessage}");
                $this->error("   Error Code: " . ($errors[0]->getCode() ?? 'N/A'));
                $this->testResults['payment'] = false;
                $this->newLine();
                return;
            }

            $payment = $paymentResponse->getPayment();
            
            if (!$payment || !$payment->getId()) {
                $this->error('   ❌ Payment created but no payment ID returned');
                $this->testResults['payment'] = false;
                $this->newLine();
                return;
            }

            $paymentId = $payment->getId();
            $receiptUrl = $payment->getReceiptUrl();
            $status = $payment->getStatus();

            $this->testResults['payment_id'] = $paymentId;
            $this->testResults['payment'] = true;

            $this->info("   ✅ Payment created successfully!");
            $this->line("   Payment ID: {$paymentId}");
            $this->line("   Status: {$status}");
            if ($receiptUrl) {
                $this->line("   Receipt URL: {$receiptUrl}");
            }
            $this->newLine();
        } catch (SquareApiException $e) {
            $this->error('   ❌ Square API Exception: ' . $e->getMessage());
            $this->testResults['payment'] = false;
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   ❌ Payment creation failed: ' . $e->getMessage());
            $this->testResults['payment'] = false;
            $this->newLine();
        }
    }

    /**
     * Test refund functionality
     */
    private function testRefund()
    {
        $this->info('↩️  Test 5: Testing Refund Functionality...');
        
        if (!isset($this->testResults['payment_id'])) {
            $this->warn('   ⏭️  No payment ID available for refund test');
            $this->testResults['refund'] = 'skipped';
            $this->newLine();
            return;
        }

        $paymentId = $this->testResults['payment_id'];
        $this->line("   Payment ID to refund: {$paymentId}");

        if (!$this->confirm('   Do you want to proceed with refund test?', false)) {
            $this->warn('   ⏭️  Refund test skipped');
            $this->testResults['refund'] = 'skipped';
            $this->newLine();
            return;
        }

        try {
            $refundsApi = $this->squareClient->refunds;

            // Get payment details first to get the amount
            $paymentsApi = $this->squareClient->payments;
            $getPaymentRequest = new GetPaymentsRequest([
                'paymentId' => $paymentId,
            ]);
            
            $paymentResponse = $paymentsApi->get($getPaymentRequest);
            $errors = $paymentResponse->getErrors();
            
            if (!empty($errors)) {
                $this->error('   ❌ Failed to get payment details: ' . $errors[0]->getDetail());
                $this->testResults['refund'] = false;
                $this->newLine();
                return;
            }

            $payment = $paymentResponse->getPayment();
            $amount = $payment->getAmountMoney()->getAmount();

            $amountMoney = new Money([
                'amount' => $amount,
                'currency' => 'USD',
            ]);

            $body = new RefundPaymentRequest([
                'idempotencyKey' => 'refund-test-' . uniqid() . '-' . time(),
                'amountMoney' => $amountMoney,
                'paymentId' => $paymentId,
                'reason' => 'Square Integration Test Refund',
            ]);

            $this->line('   Processing refund via Square API...');
            $refundResponse = $refundsApi->refundPayment($body);

            $errors = $refundResponse->getErrors();
            if (!empty($errors)) {
                $errorMessage = $errors[0]->getDetail() ?? 'Refund failed';
                $this->error("   ❌ Refund failed: {$errorMessage}");
                $this->testResults['refund'] = false;
                $this->newLine();
                return;
            }

            $refund = $refundResponse->getRefund();
            $refundId = $refund->getId();
            $refundStatus = $refund->getStatus();

            $this->testResults['refund_id'] = $refundId;
            $this->testResults['refund'] = true;

            $this->info("   ✅ Refund processed successfully!");
            $this->line("   Refund ID: {$refundId}");
            $this->line("   Status: {$refundStatus}");
            $this->newLine();
        } catch (SquareApiException $e) {
            $this->error('   ❌ Square API Exception: ' . $e->getMessage());
            $this->testResults['refund'] = false;
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   ❌ Refund failed: ' . $e->getMessage());
            $this->testResults['refund'] = false;
            $this->newLine();
        }
    }

    /**
     * Test database fields
     */
    private function testDatabaseFields()
    {
        $this->info('🗄️  Test 6: Checking Database Schema...');
        
        try {
            // Check bookings table
            $bookingColumns = Schema::getColumnListing('bookings');
            $requiredBookingFields = [
                'square_payment_id',
                'square_receipt_url',
                'square_refund_id',
                'payment_status',
                'payment_method'
            ];

            $allPresent = true;
            foreach ($requiredBookingFields as $field) {
                if (in_array($field, $bookingColumns)) {
                    $this->line("   ✅ Bookings table has '{$field}' column");
                } else {
                    $this->error("   ❌ Bookings table missing '{$field}' column");
                    $allPresent = false;
                }
            }

            // Check gift_cards table
            $giftCardColumns = Schema::getColumnListing('gift_cards');
            $requiredGiftCardFields = [
                'square_payment_id',
                'square_receipt_url',
                'payment_status'
            ];

            foreach ($requiredGiftCardFields as $field) {
                if (in_array($field, $giftCardColumns)) {
                    $this->line("   ✅ Gift cards table has '{$field}' column");
                } else {
                    $this->error("   ❌ Gift cards table missing '{$field}' column");
                    $allPresent = false;
                }
            }

            if ($allPresent) {
                $this->testResults['database'] = true;
                $this->info('   ✅ Database schema check passed');
            } else {
                $this->testResults['database'] = false;
                $this->error('   ❌ Database schema check failed');
            }
            $this->newLine();
        } catch (\Exception $e) {
            $this->error('   ❌ Database check failed: ' . $e->getMessage());
            $this->testResults['database'] = false;
            $this->newLine();
        }
    }

    /**
     * Display test summary
     */
    private function displaySummary()
    {
        $this->info('📊 Test Summary');
        $this->line(str_repeat('=', 50));
        
        $tests = [
            'Configuration' => $this->testResults['config'] ?? false,
            'Client Initialization' => $this->testResults['client_init'] ?? false,
            'Location ID' => $this->testResults['location'] !== false,
            'Payment Creation' => $this->testResults['payment'] ?? 'skipped',
            'Refund' => $this->testResults['refund'] ?? 'skipped',
            'Database Schema' => $this->testResults['database'] ?? false,
        ];

        foreach ($tests as $testName => $result) {
            if ($result === true) {
                $this->line("✅ {$testName}: PASSED");
            } elseif ($result === false) {
                $this->line("❌ {$testName}: FAILED");
            } else {
                $this->line("⏭️  {$testName}: SKIPPED");
            }
        }

        $this->newLine();
        
        $passed = count(array_filter($tests, fn($r) => $r === true));
        $failed = count(array_filter($tests, fn($r) => $r === false));
        $skipped = count(array_filter($tests, fn($r) => $r === 'skipped'));

        $this->line("Total: {$passed} passed, {$failed} failed, {$skipped} skipped");
        $this->newLine();

        if ($failed > 0) {
            $this->error('⚠️  Some tests failed. Please review the errors above.');
            return 1;
        } elseif ($passed > 0) {
            $this->info('✅ All executed tests passed!');
            return 0;
        } else {
            $this->warn('⚠️  No tests were executed.');
            return 0;
        }
    }
}

