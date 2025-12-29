@extends('layouts.user')

@section('title', 'Booking Details - SteamZilla')
@section('page-title', 'Booking Details')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Booking #{{ $booking->id }}</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Created on {{ $booking->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <span class="px-3 sm:px-4 py-1 sm:py-2 text-xs sm:text-sm font-semibold rounded-full
                {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>
    </div>

    <div class="p-4 sm:p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <!-- Service Package -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Package</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900">{{ $booking->package->name }}</h4>
                    <p class="text-sm text-gray-600 mt-1">{{ $booking->package->duration }}</p>
                    <p class="text-lg font-bold text-[#45A247] mt-2">${{ number_format($booking->package->price, 2) }}</p>
                </div>
            </div>

            <!-- Date & Time -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date & Time</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900">
                        <i class="fas fa-calendar mr-2 text-[#45A247]"></i>
                        {{ $booking->booking_date->format('F d, Y') }}
                    </p>
                    <p class="text-gray-900 mt-2">
                        <i class="fas fa-clock mr-2 text-[#45A247]"></i>
                        {{ $booking->booking_time }}
                    </p>
                </div>
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Address</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900">{{ $booking->address }}</p>
                </div>
            </div>

            <!-- Vehicle Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Type</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900">{{ $booking->vehicle_type }}</p>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900">{{ ucfirst($booking->payment_method ?? 'N/A') }}</p>
                    @if($booking->giftCard)
                        <p class="text-sm text-gray-600 mt-1">
                            Gift Card: {{ $booking->giftCard->gift_card_number }}
                            @if($booking->gift_card_discount > 0)
                                <span class="text-green-600">(-${{ number_format($booking->gift_card_discount, 2) }})</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add-ons -->
        @if($booking->bookingAddons->count() > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Add-Ons</label>
            <div class="bg-gray-50 rounded-lg p-4">
                <ul class="space-y-2">
                    @foreach($booking->bookingAddons as $bookingAddon)
                    <li class="flex items-center justify-between">
                        <span class="text-gray-900">{{ $bookingAddon->addon->name }} 
                            @if($bookingAddon->quantity > 1)
                                <span class="text-gray-500">(x{{ $bookingAddon->quantity }})</span>
                            @endif
                        </span>
                        <span class="font-semibold text-gray-900">${{ number_format($bookingAddon->price_at_booking * $bookingAddon->quantity, 2) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($booking->notes)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes</label>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-900">{{ $booking->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Total Price -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between">
                <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                <span class="text-2xl font-bold text-[#45A247]">${{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
        <a href="{{ route('user.bookings') }}" class="text-[#45A247] hover:underline text-sm sm:text-base">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
        </a>
    </div>
</div>
@endsection

