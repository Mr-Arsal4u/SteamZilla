@extends('layouts.app')

@section('title', 'Complete Gift Card Payment - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Complete Your Gift Card Purchase</h2>

            <div id="payment-status-message" class="hidden mb-6"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Payment Form -->
                <div>
                    <form id="payment-form">
                        @csrf
                        
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
                                <i class="fas fa-lock mr-2"></i> Pay ${{ number_format($giftCardData['final_paid_amount'] ?? 0, 2) }}
                            </span>
                            <span id="button-spinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Processing...
                            </span>
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('gift-cards', ['tab' => 'buy']) }}" class="text-gray-600 hover:text-[#45A247] transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Gift Card Form
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="bg-green-50 border-2 border-[#45A247] rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-6 text-gray-900">Gift Card Summary</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Gift Card Amount</p>
                                <p class="text-2xl font-bold text-[#45A247]">${{ number_format($giftCardData['amount'] ?? 0, 2) }}</p>
                            </div>
                            
                            @if(($giftCardData['discount_applied'] ?? 0) > 0)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Discount (10%)</p>
                                    <p class="text-lg font-semibold text-green-600">-${{ number_format($giftCardData['discount_applied'] ?? 0, 2) }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Recipient</p>
                                <p class="font-semibold text-gray-900">{{ $giftCardData['recipient_name'] ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Delivery Method</p>
                                <p class="font-semibold text-gray-900 capitalize">{{ $giftCardData['delivery_method'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="border-t-2 border-[#45A247] pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-[#45A247]">
                                    ${{ number_format($giftCardData['final_paid_amount'] ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Square Web Payments SDK -->
<script type="text/javascript" src="https://{{ $environment === 'production' ? 'web' : 'sandbox.web' }}.squarecdn.com/v1/square.js"></script>
<script>
    const applicationId = '{{ $applicationId }}';
    const locationId = '{{ $locationId }}';
    
    let payments;
    
    async function initializeSquarePayment() {
        try {
            payments = Square.payments(applicationId, locationId);
            const card = await payments.card();
            await card.attach('#card-container');
            
            return card;
        } catch (error) {
            console.error('Error initializing Square payment:', error);
            showError('Failed to initialize payment form. Please refresh the page.');
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
                    const formData = new FormData();
                    formData.append('sourceId', result.token);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    
                    const response = await fetch('{{ route("payment.gift-card.process") }}', {
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


