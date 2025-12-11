@extends('layouts.app')

@section('title', 'Gift Cards - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Discount Banner -->
        <div class="bg-[#45A247] text-white rounded-lg p-4 mb-8 text-center">
            <p class="text-lg font-semibold">Discount Pricing: 10% off items $100 and above</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="flex border-b border-gray-200">
                <button onclick="switchTab('buy')" id="tab-buy" class="flex-1 py-4 px-6 text-center font-semibold text-gray-700 hover:text-[#45A247] transition border-b-2 border-transparent hover:border-[#45A247] tab-button active">
                    Buy Gift Card
                </button>
                <button onclick="switchTab('reload')" id="tab-reload" class="flex-1 py-4 px-6 text-center font-semibold text-gray-700 hover:text-[#45A247] transition border-b-2 border-transparent hover:border-[#45A247] tab-button">
                    Reload Card
                </button>
                <button onclick="switchTab('check')" id="tab-check" class="flex-1 py-4 px-6 text-center font-semibold text-gray-700 hover:text-[#45A247] transition border-b-2 border-transparent hover:border-[#45A247] tab-button">
                    Check Balance
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            
            <!-- Buy Gift Card Tab -->
            <div id="content-buy" class="tab-content">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Give the Perfect Gift</h2>
                        <p class="text-gray-600">Get a voucher for yourself or gift one to a friend</p>
                    </div>
                    <div class="hidden md:block">
                        <i class="fas fa-gift text-6xl text-[#45A247]"></i>
                    </div>
                </div>

                <form action="{{ route('gift-cards.buy') }}" method="POST" id="buy-form">
                    @csrf
                    
                    <!-- Step 1: Type of Gift -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900">Step 1: Type of Gift</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="gift-type-card cursor-pointer">
                                <input type="radio" name="type" value="individual" checked class="hidden" onchange="updateGiftType()">
                                <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-[#45A247] transition">
                                    <i class="fas fa-user text-3xl text-[#45A247] mb-3"></i>
                                    <h4 class="font-semibold text-gray-900">For one individual</h4>
                                </div>
                            </label>
                            <label class="gift-type-card cursor-pointer opacity-50">
                                <input type="radio" name="type" value="bulk" disabled class="hidden">
                                <div class="border-2 border-gray-300 rounded-lg p-6 text-center">
                                    <i class="fas fa-users text-3xl text-gray-400 mb-3"></i>
                                    <h4 class="font-semibold text-gray-900">For multiple people</h4>
                                    <p class="text-sm text-gray-500 mt-2">Coming soon</p>
                                </div>
                            </label>
                            <label class="gift-type-card cursor-pointer opacity-50">
                                <input type="radio" name="type" value="group" disabled class="hidden">
                                <div class="border-2 border-gray-300 rounded-lg p-6 text-center">
                                    <i class="fas fa-hand-holding-heart text-3xl text-gray-400 mb-3"></i>
                                    <h4 class="font-semibold text-gray-900">Group gift card</h4>
                                    <p class="text-sm text-gray-500 mt-2">Coming soon</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Step 2: Select Amount -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900">Step 2: Select Amount</h3>
                        @php
                            $amounts = [100, 150, 200, 250];
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                            @foreach($amounts as $amt)
                                @php
                                    $discount = $amt >= 100 ? $amt * 0.10 : 0;
                                    $final = $amt - $discount;
                                @endphp
                                <label class="amount-card cursor-pointer">
                                    <input type="radio" name="amount" value="{{ $amt }}" class="hidden" onchange="updateAmount()">
                                    <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-[#45A247] transition">
                                        <div class="text-2xl font-bold text-gray-900">${{ number_format($amt, 0) }}</div>
                                        @if($discount > 0)
                                            <div class="text-sm text-gray-500 line-through mt-1">${{ number_format($amt, 0) }}</div>
                                            <div class="text-lg font-semibold text-[#45A247] mt-1">Pay ${{ number_format($final, 0) }}</div>
                                        @else
                                            <div class="text-lg font-semibold text-[#45A247] mt-1">Pay ${{ number_format($final, 0) }}</div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Custom Amount</label>
                            <input type="number" name="custom_amount" id="custom_amount" min="1" step="0.01" placeholder="Enter amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" onchange="updateCustomAmount()">
                            <div id="custom-amount-display" class="mt-2 text-sm text-gray-600 hidden"></div>
                        </div>
                    </div>

                    <!-- Step 3: Delivery Details -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900">Step 3: Delivery Details</h3>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="send_to_myself" id="send_to_myself" value="1" class="mr-2" onchange="toggleRecipientFields()">
                                <span class="text-gray-700">Send this card to myself</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Method</label>
                                <select name="delivery_method" id="delivery_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" onchange="toggleDeliveryFields()">
                                    <option value="email">Send by Email</option>
                                    <option value="sms">Send by Text</option>
                                    <option value="self">Send to Myself</option>
                                </select>
                            </div>
                            <div id="recipient_name_field">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Name</label>
                                <input type="text" name="recipient_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div id="recipient_email_field">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Email</label>
                                <input type="email" name="recipient_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                            <div id="recipient_phone_field" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Phone</label>
                                <input type="tel" name="recipient_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                                <input type="date" name="delivery_date" id="delivery_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Time</label>
                                <input type="time" name="delivery_time" id="delivery_time" value="12:00" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Sender Details -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-900">Step 4: Sender Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                <input type="text" name="sender_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Email *</label>
                                <input type="email" name="sender_email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personal Message (Optional)</label>
                            <textarea name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Add a personal message..."></textarea>
                        </div>
                    </div>

                    <!-- Step 5: Checkout -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition transform hover:scale-105">
                            Checkout
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reload Card Tab -->
            <div id="content-reload" class="tab-content hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Add money to your gift card.</h2>
                
                @if(session('reload_card_validated'))
                    <form action="{{ route('gift-cards.reload') }}" method="POST" id="reload-form">
                        @csrf
                        <div class="mb-6 p-4 bg-green-50 border border-[#45A247] rounded-lg">
                            <p class="text-sm text-gray-700 mb-1">Validated Card:</p>
                            <p class="font-mono font-semibold text-lg">{{ session('reload_card_number') }}</p>
                        </div>

                        <div id="reload-amount-section">
                            <h3 class="text-xl font-semibold mb-4 text-gray-900">Select Amount to Reload</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                                @foreach($amounts as $amt)
                                    @php
                                        $discount = $amt >= 100 ? $amt * 0.10 : 0;
                                        $final = $amt - $discount;
                                    @endphp
                                    <label class="amount-card cursor-pointer">
                                        <input type="radio" name="amount" value="{{ $amt }}" class="hidden" onchange="updateReloadAmount()">
                                        <div class="border-2 border-gray-300 rounded-lg p-6 text-center hover:border-[#45A247] transition">
                                            <div class="text-2xl font-bold text-gray-900">${{ number_format($amt, 0) }}</div>
                                            @if($discount > 0)
                                                <div class="text-sm text-gray-500 line-through mt-1">${{ number_format($amt, 0) }}</div>
                                                <div class="text-lg font-semibold text-[#45A247] mt-1">Pay ${{ number_format($final, 0) }}</div>
                                            @else
                                                <div class="text-lg font-semibold text-[#45A247] mt-1">Pay ${{ number_format($final, 0) }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Custom Amount</label>
                                <input type="number" name="custom_amount" id="reload_custom_amount" min="1" step="0.01" placeholder="Enter amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" onchange="updateReloadCustomAmount()">
                                <div id="reload-custom-amount-display" class="mt-2 text-sm text-gray-600 hidden"></div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Add Discount Code (Optional)</label>
                                <input type="text" name="discount_code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Enter discount code">
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition transform hover:scale-105">
                                    Continue
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <form action="{{ route('gift-cards.check-reload') }}" method="POST" id="reload-check-form">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gift Card Number</label>
                                <input type="text" name="gift_card_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Enter gift card number">
                                @error('gift_card_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                                <input type="text" name="pin" id="reload_pin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Enter PIN">
                                <label class="flex items-center mt-2">
                                    <input type="checkbox" name="no_pin" id="reload_no_pin" value="1" class="mr-2" onchange="toggleReloadPin()">
                                    <span class="text-sm text-gray-700">My gift card does not have a PIN</span>
                                </label>
                                @error('pin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition transform hover:scale-105">
                                    Check Balance
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Check Balance Tab -->
            <div id="content-check" class="tab-content hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Enter your gift card number to check your balance.</h2>
                
                <form action="{{ route('gift-cards.check-balance') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gift Card Number</label>
                            <input type="text" name="gift_card_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Enter gift card number">
                            @error('gift_card_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PIN</label>
                            <input type="text" name="pin" id="check_pin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-transparent" placeholder="Enter PIN">
                            <label class="flex items-center mt-2">
                                <input type="checkbox" name="no_pin" id="check_no_pin" value="1" class="mr-2" onchange="toggleCheckPin()">
                                <span class="text-sm text-gray-700">My gift card does not have a PIN</span>
                            </label>
                            @error('pin')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition transform hover:scale-105">
                                Check Balance
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching
    function switchTab(tab) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active from all tabs
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-[#45A247]', 'text-[#45A247]');
            btn.classList.add('text-gray-700');
        });
        
        // Show selected content
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Activate selected tab
        const activeTab = document.getElementById('tab-' + tab);
        activeTab.classList.add('active', 'border-[#45A247]', 'text-[#45A247]');
        activeTab.classList.remove('text-gray-700');
    }

    // Initialize tab from URL or default
    @if(request('tab'))
        switchTab('{{ request('tab') }}');
    @else
        switchTab('buy');
    @endif

    // Amount selection
    function updateAmount() {
        const selected = document.querySelector('input[name="amount"]:checked');
        if (selected) {
            document.getElementById('custom_amount').value = '';
            document.getElementById('custom-amount-display').classList.add('hidden');
        }
    }

    function updateCustomAmount() {
        const customAmount = parseFloat(document.getElementById('custom_amount').value);
        if (customAmount && customAmount > 0) {
            // Uncheck all radio buttons
            document.querySelectorAll('input[name="amount"]').forEach(radio => {
                radio.checked = false;
            });
            
            const discount = customAmount >= 100 ? customAmount * 0.10 : 0;
            const final = customAmount - discount;
            
            let displayText = `Original: $${customAmount.toFixed(2)}`;
            if (discount > 0) {
                displayText += ` | Discount: $${discount.toFixed(2)} | Pay: $${final.toFixed(2)}`;
            } else {
                displayText += ` | Pay: $${final.toFixed(2)}`;
            }
            
            document.getElementById('custom-amount-display').textContent = displayText;
            document.getElementById('custom-amount-display').classList.remove('hidden');
        } else {
            document.getElementById('custom-amount-display').classList.add('hidden');
        }
    }

    // Update form to use custom amount or selected amount
    document.getElementById('buy-form').addEventListener('submit', function(e) {
        const customAmount = parseFloat(document.getElementById('custom_amount').value);
        const selectedAmount = document.querySelector('input[name="amount"]:checked');
        
        if (customAmount && customAmount > 0) {
            // Create hidden input for custom amount
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'amount';
            hiddenInput.value = customAmount;
            this.appendChild(hiddenInput);
            
            // Uncheck radio buttons
            if (selectedAmount) selectedAmount.checked = false;
        }
    });

    // Toggle recipient fields
    function toggleRecipientFields() {
        const sendToMyself = document.getElementById('send_to_myself').checked;
        const recipientFields = ['recipient_name_field', 'recipient_email_field', 'recipient_phone_field'];
        
        recipientFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                if (sendToMyself) {
                    field.style.display = 'none';
                    const input = field.querySelector('input');
                    if (input) input.removeAttribute('required');
                } else {
                    field.style.display = 'block';
                    const input = field.querySelector('input');
                    if (input) input.setAttribute('required', 'required');
                }
            }
        });
    }

    function toggleDeliveryFields() {
        const method = document.getElementById('delivery_method').value;
        const emailField = document.getElementById('recipient_email_field');
        const phoneField = document.getElementById('recipient_phone_field');
        
        if (method === 'email') {
            emailField.classList.remove('hidden');
            phoneField.classList.add('hidden');
        } else if (method === 'sms') {
            emailField.classList.add('hidden');
            phoneField.classList.remove('hidden');
        } else {
            emailField.classList.add('hidden');
            phoneField.classList.add('hidden');
        }
    }

    // PIN toggles
    function toggleReloadPin() {
        const noPin = document.getElementById('reload_no_pin').checked;
        document.getElementById('reload_pin').disabled = noPin;
        if (noPin) {
            document.getElementById('reload_pin').value = '';
        }
    }

    function toggleCheckPin() {
        const noPin = document.getElementById('check_no_pin').checked;
        document.getElementById('check_pin').disabled = noPin;
        if (noPin) {
            document.getElementById('check_pin').value = '';
        }
    }

    // Reload card check is now handled server-side via form submission

    // Reload amount functions
    function updateReloadAmount() {
        const selected = document.querySelector('#reload-form input[name="amount"]:checked');
        if (selected) {
            document.getElementById('reload_custom_amount').value = '';
            document.getElementById('reload-custom-amount-display').classList.add('hidden');
        }
    }

    function updateReloadCustomAmount() {
        const customAmount = parseFloat(document.getElementById('reload_custom_amount').value);
        if (customAmount && customAmount > 0) {
            document.querySelectorAll('#reload-form input[name="amount"]').forEach(radio => {
                radio.checked = false;
            });
            
            const discount = customAmount >= 100 ? customAmount * 0.10 : 0;
            const final = customAmount - discount;
            
            let displayText = `Original: $${customAmount.toFixed(2)}`;
            if (discount > 0) {
                displayText += ` | Discount: $${discount.toFixed(2)} | Pay: $${final.toFixed(2)}`;
            } else {
                displayText += ` | Pay: $${final.toFixed(2)}`;
            }
            
            document.getElementById('reload-custom-amount-display').textContent = displayText;
            document.getElementById('reload-custom-amount-display').classList.remove('hidden');
        } else {
            document.getElementById('reload-custom-amount-display').classList.add('hidden');
        }
    }

    // Update reload form submission
    document.getElementById('reload-form').addEventListener('submit', function(e) {
        const customAmount = parseFloat(document.getElementById('reload_custom_amount').value);
        const selectedAmount = document.querySelector('#reload-form input[name="amount"]:checked');
        
        if (customAmount && customAmount > 0) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'amount';
            hiddenInput.value = customAmount;
            this.appendChild(hiddenInput);
            
            if (selectedAmount) selectedAmount.checked = false;
        }
    });

    // Style selected amount cards
    document.querySelectorAll('.amount-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.amount-card > div').forEach(card => {
                card.classList.remove('border-[#45A247]', 'bg-green-50');
            });
            if (this.checked) {
                this.closest('.amount-card').querySelector('div').classList.add('border-[#45A247]', 'bg-green-50');
            }
        });
    });
</script>

<style>
    .tab-button.active {
        border-bottom-color: #45A247 !important;
        color: #45A247 !important;
    }
</style>
@endsection
