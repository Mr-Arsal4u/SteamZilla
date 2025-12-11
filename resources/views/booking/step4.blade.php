@extends('layouts.app')

@section('title', 'Confirm Booking - SteamZilla')

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
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Order Info</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Date/Time</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">4</div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Confirm</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">Contact Information</h2>

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('booking.step4.store') }}" method="POST">
                        @csrf

                        <!-- Customer Information -->
                        <div class="mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="user_name" value="{{ old('user_name', Auth::check() ? Auth::user()->name : '') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="user_phone" value="{{ old('user_phone', Auth::check() ? Auth::user()->phone : '') }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="user_email" value="{{ old('user_email', Auth::check() ? Auth::user()->email : '') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                            </div>
                        </div>

                        <!-- Payment Notice -->
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Information</h3>
                                    <p class="text-gray-700 leading-relaxed">
                                        <strong>Our employee will contact you for the payment</strong> after confirming your booking. You can pay via cash, card, or other accepted payment methods when our team arrives for the service.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                            <textarea name="notes" rows="4" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                                placeholder="Any special instructions, access codes, or notes for our team...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- SMS Notification Disclaimer -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-sms text-green-600 mr-2"></i>
                                You will receive SMS notifications about your booking status and service updates.
                            </p>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between">
                            <a href="{{ route('booking.step3') }}" class="bg-gray-200 text-gray-700 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </a>
                            <button type="submit" class="bg-[#45A247] text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-[#3a8a3c] transition transform hover:scale-105 shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i> Confirm Booking
                            </button>
                        </div>
                    </form>
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
                        <p class="text-xs text-gray-600 mt-2 text-center">Payment collected on service day</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
