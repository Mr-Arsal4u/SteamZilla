@extends('layouts.app')

@section('title', 'Order Info - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Address</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">2</div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Order Info</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">3</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Date/Time</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">4</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Payment</span>
                </div>
            </div>
        </div>

        <!-- Step 2 Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Order Info</h2>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('booking.step2.store') }}" method="POST" id="order-form">
                @csrf

                <!-- Vehicle Type Selection -->
                <div class="mb-10">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">Vehicle Type *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $vehicleTypes = ['Sedan', 'SUV', 'Truck', 'Van', 'Coupe', 'Convertible', 'Hatchback', 'Other'];
                        @endphp
                        @foreach($vehicleTypes as $type)
                            <label class="vehicle-type-card cursor-pointer">
                                <input type="radio" name="vehicle_type" value="{{ $type }}" 
                                    {{ old('vehicle_type', $bookingData['vehicle_type'] ?? '') === $type ? 'checked' : '' }}
                                    required class="hidden" onchange="updateTotal()">
                                <div class="border-2 border-gray-300 rounded-lg p-4 text-center hover:border-[#45A247] transition">
                                    <i class="fas fa-car text-3xl text-gray-400 mb-2"></i>
                                    <div class="font-semibold text-gray-900">{{ $type }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Service Packages -->
                <div class="mb-10">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">Select Service Package *</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($packages as $package)
                            <label class="package-card cursor-pointer">
                                <input type="radio" name="package_id" value="{{ $package->id }}" 
                                    {{ old('package_id', $bookingData['package_id'] ?? '') == $package->id ? 'checked' : '' }}
                                    required class="hidden" onchange="updateTotal()">
                                <div class="border-2 border-gray-300 rounded-lg p-6 hover:border-[#45A247] transition h-full">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-4">{{ $package->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-[#45A247]">${{ number_format($package->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">{{ $package->duration }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Add-Ons -->
                <div class="mb-10">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">Add-On Services (Optional)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($addons as $addon)
                            <label class="addon-card cursor-pointer">
                                <input type="checkbox" name="addons[]" value="{{ $addon->id }}" 
                                    {{ in_array($addon->id, old('addons', [])) ? 'checked' : '' }}
                                    class="hidden" onchange="updateTotal()">
                                <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-[#45A247] transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $addon->name }}</h4>
                                            @if($addon->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $addon->description }}</p>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <span class="bg-[#45A247] text-white px-4 py-2 rounded-full text-sm font-bold">
                                                ${{ number_format($addon->price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($addon->has_quantity)
                                        <div class="mt-3">
                                            <label class="text-sm text-gray-600">Quantity:</label>
                                            <input type="number" name="addon_quantities[{{ $addon->id }}]" 
                                                value="{{ old('addon_quantities.'.$addon->id, 1) }}" 
                                                min="1" 
                                                class="ml-2 w-20 px-2 py-1 border border-gray-300 rounded"
                                                onchange="updateTotal()">
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Total Price Display -->
                <div class="bg-green-50 border-2 border-[#45A247] rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-semibold text-gray-900">Total:</span>
                        <span id="total-price" class="text-3xl font-bold text-[#45A247]">$0.00</span>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('booking.step1') }}" class="bg-gray-200 text-gray-700 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-300 transition">
                        Back
                    </a>
                    <button type="submit" class="bg-[#45A247] text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-[#3a8a3c] transition transform hover:scale-105 shadow-lg">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateTotal() {
        let total = 0;

        // Get selected package price
        const selectedPackage = document.querySelector('input[name="package_id"]:checked');
        if (selectedPackage) {
            const packageCard = selectedPackage.closest('.package-card');
            const priceText = packageCard.querySelector('.text-\\[\\#45A247\\]').textContent;
            total += parseFloat(priceText.replace('$', '').replace(',', ''));
        }

        // Get selected addons prices
        document.querySelectorAll('input[name="addons[]"]:checked').forEach(checkbox => {
            const addonCard = checkbox.closest('.addon-card');
            const priceText = addonCard.querySelector('.bg-\\[\\#45A247\\]').textContent;
            const addonPrice = parseFloat(priceText.replace('$', '').replace(',', ''));
            
            // Check for quantity
            const quantityInput = addonCard.querySelector('input[type="number"]');
            const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
            
            total += addonPrice * quantity;
        });

        // Update total display
        document.getElementById('total-price').textContent = '$' + total.toFixed(2);
    }

    // Initialize total on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
        
        // Style selected cards
        document.querySelectorAll('.package-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.package-card > div').forEach(card => {
                    card.classList.remove('border-[#45A247]', 'bg-green-50');
                });
                if (this.checked) {
                    this.closest('.package-card').querySelector('div').classList.add('border-[#45A247]', 'bg-green-50');
                }
            });
        });

        document.querySelectorAll('.addon-card input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.closest('.addon-card').querySelector('div').classList.add('border-[#45A247]', 'bg-green-50');
                } else {
                    this.closest('.addon-card').querySelector('div').classList.remove('border-[#45A247]', 'bg-green-50');
                }
            });
        });

        document.querySelectorAll('.vehicle-type-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.vehicle-type-card > div').forEach(card => {
                    card.classList.remove('border-[#45A247]', 'bg-green-50');
                });
                if (this.checked) {
                    this.closest('.vehicle-type-card').querySelector('div').classList.add('border-[#45A247]', 'bg-green-50');
                }
            });
        });
    });
</script>
@endsection

