@extends('layouts.app')

@section('title', 'Complete Payment - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Complete</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">5</div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Payment</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Complete Your Payment</h2>

                    <div id="payment-status-message" class="hidden mb-6"></div>

                    <!-- Square Payment Form -->
                    <form id="payment-form">
                        @csrf
                        <input type="hidden" name="user_name" value="{{ $bookingData['user_name'] ?? '' }}">
                        <input type="hidden" name="user_email" value="{{ $bookingData['user_email'] ?? '' }}">
                        <input type="hidden" name="user_phone" value="{{ $bookingData['user_phone'] ?? '' }}">
                        <input type="hidden" name="notes" value="{{ $bookingData['notes'] ?? '' }}">

                        <!-- Card Container -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Information</label>
                            <div id="card-container" class="p-4 border border-gray-300 rounded-lg bg-white">
                                <!-- Square Card form will be inserted here -->
                            </div>
                            <div id="card-errors" class="mt-2 text-sm text-red-600"></div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="card-button" class="w-full bg-[#45A247] text-white px-8 py-4 rounded-full text-lg font-bold hover:bg-[#3a8a3c] transition transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">
                                <i class="fas fa-lock mr-2"></i> Pay ${{ number_format($bookingData['total_price'] ?? 0, 2) }}
                            </span>
                            <span id="button-spinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                            </span>
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('booking.step4') }}" class="text-gray-600 hover:text-[#45A247] transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Booking Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-green-50 border-2 border-[#45A247] rounded-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-6 text-gray-900">Order Summary</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Service Address</p>
                            <p class="font-semibold text-gray-900 text-sm">{{ $bookingData['address'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Vehicle Type</p>
                            <p class="font-semibold text-gray-900">{{ $bookingData['vehicle_type'] ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Service Package</p>
                            <p class="font-semibold text-gray-900">{{ $bookingData['package_name'] ?? 'N/A' }}</p>
                            <p class="text-[#45A247] font-bold">${{ number_format($bookingData['package_price'] ?? 0, 2) }}</p>
                        </div>
                        
                        @if(!empty($bookingData['addons']))
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Add-Ons</p>
                                @foreach($bookingData['addons'] as $addon)
                                    <p class="text-sm text-gray-900 mb-1">
                                        {{ $addon['name'] }} 
                                        @if($addon['quantity'] > 1)
                                            <span class="text-gray-600">(x{{ $addon['quantity'] }})</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-[#45A247] font-semibold mb-2">
                                        ${{ number_format($addon['price'] * $addon['quantity'], 2) }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Scheduled Date</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($bookingData['booking_date']) ? date('M d, Y', strtotime($bookingData['booking_date'])) : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Scheduled Time</p>
                            <p class="font-semibold text-gray-900">
                                {{ isset($bookingData['booking_time']) ? date('g:i A', strtotime($bookingData['booking_time'])) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-t-2 border-[#45A247] pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-[#45A247]">
                                ${{ number_format($bookingData['total_price'] ?? 0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Square Web Payments SDK -->
<script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
<script>
    const applicationId = '{{ $applicationId }}';
    const locationId = '{{ $locationId }}';
    
    // Check if required Square credentials are present
    if (!applicationId || !locationId) {
        document.addEventListener('DOMContentLoaded', function() {
            showError('Square payment configuration is missing. Please contact support.');
            document.getElementById('card-button').disabled = true;
        });
    }
    
    let payments;
    
    async function initializeSquarePayment() {
        if (!applicationId || !locationId) {
            showError('Square payment configuration is missing. Please contact support.');
            return null;
        }
        
        // Check if we're in a secure context (HTTPS or localhost)
        const isSecureContext = window.isSecureContext || 
                                window.location.protocol === 'https:' || 
                                window.location.hostname === 'localhost' ||
                                window.location.hostname === '127.0.0.1';
        
        if (!isSecureContext && window.location.protocol !== 'https:') {
            const currentHost = window.location.hostname;
            let errorMsg = 'Square Web Payments SDK requires HTTPS or a secure context. ';
            
            if (currentHost === '127.0.0.1') {
                errorMsg += 'Please access this page using "localhost" instead of "127.0.0.1", or set up HTTPS for local development.';
            } else {
                errorMsg += 'This page must be served over HTTPS in production.';
            }
            
            showError(errorMsg);
            return null;
        }
        
        try {
            if (typeof Square === 'undefined') {
                showError('Square Web Payments SDK failed to load. Please refresh the page.');
                return null;
            }
            
            payments = Square.payments(applicationId, locationId);
            const card = await payments.card();
            await card.attach('#card-container');
            
            return card;
        } catch (error) {
            console.error('Error initializing Square payment:', error);
            let errorMessage = 'Failed to initialize payment form. ';
            
            if (error.message && error.message.includes('secure context')) {
                const currentHost = window.location.hostname;
                if (currentHost === '127.0.0.1') {
                    errorMessage = 'Square requires a secure context. Please access this page using "localhost" instead of "127.0.0.1". ';
                    errorMessage += 'Try: http://localhost:8001' + window.location.pathname;
                } else {
                    errorMessage = 'Square Web Payments SDK requires HTTPS. This page must be served over HTTPS.';
                }
            } else if (error.message) {
                errorMessage += error.message;
            } else {
                errorMessage += 'Please check your Square configuration and refresh the page.';
            }
            
            showError(errorMessage);
            return null;
        }
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('card-errors');
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    function hideError() {
        const errorDiv = document.getElementById('card-errors');
        errorDiv.textContent = '';
        errorDiv.classList.add('hidden');
    }
    
    function showStatusMessage(message, type = 'error') {
        const statusDiv = document.getElementById('payment-status-message');
        statusDiv.className = type === 'success' 
            ? 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg' 
            : 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg';
        statusDiv.textContent = message;
        statusDiv.classList.remove('hidden');
    }
    
    function setLoading(loading) {
        const button = document.getElementById('card-button');
        const buttonText = document.getElementById('button-text');
        const buttonSpinner = document.getElementById('button-spinner');
        
        if (loading) {
            button.disabled = true;
            buttonText.classList.add('hidden');
            buttonSpinner.classList.remove('hidden');
        } else {
            button.disabled = false;
            buttonText.classList.remove('hidden');
            buttonSpinner.classList.add('hidden');
        }
    }
    
    let cardInstance = null;
    
    // Initialize payment form when page loads
    document.addEventListener('DOMContentLoaded', async function() {
        cardInstance = await initializeSquarePayment();
        
        if (!cardInstance) {
            return;
        }
        
        // Handle form submission
        document.getElementById('payment-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            hideError();
            setLoading(true);
            
            try {
                const result = await cardInstance.tokenize();
                
                if (result.status === 'OK') {
                    // Send token to server
                    const formData = new FormData(document.getElementById('payment-form'));
                    formData.append('sourceId', result.token);
                    
                    const response = await fetch('{{ route("payment.process") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showStatusMessage('Payment successful! Redirecting...', 'success');
                        window.location.href = data.redirect_url;
                    } else {
                        showError(data.message || 'Payment failed. Please try again.');
                        setLoading(false);
                    }
                } else {
                    showError('Card tokenization failed. Please check your card details.');
                    setLoading(false);
                }
            } catch (error) {
                console.error('Payment error:', error);
                showError('An error occurred during payment. Please try again.');
                setLoading(false);
            }
        });
    });
</script>
@endsection


