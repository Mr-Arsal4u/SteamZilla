@extends('layouts.app')

@section('title', 'Booking Confirmed - SteamZilla')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full">
        <div class="bg-white border-2 border-green-200 rounded-2xl p-8 md:p-12 text-center shadow-xl">
            <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Booking Confirmed!</h1>
            <p class="text-xl text-gray-600 mb-8">Thank you for choosing SteamZilla. Your booking has been submitted successfully.</p>
            
            <div class="bg-green-50 rounded-xl p-6 mb-8 text-left">
                <h2 class="text-xl font-bold mb-4 text-gray-900">Booking Details</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Booking ID:</span>
                        <span class="font-semibold text-gray-900">#{{ $booking->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phone:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->booking_date->format('F j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Package:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->package->name }}</span>
                    </div>
                    @if($booking->bookingAddons->count() > 0)
                        <div>
                            <span class="text-gray-600">Add-Ons:</span>
                            <ul class="mt-2 space-y-1">
                                @foreach($booking->bookingAddons as $bookingAddon)
                                    <li class="text-gray-900">{{ $bookingAddon->addon->name }} (x{{ $bookingAddon->quantity }})</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="flex justify-between pt-3 border-t border-green-200">
                        <span class="text-lg font-semibold text-gray-900">Total Price:</span>
                        <span class="text-lg font-bold text-green-600">${{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
            
            @if($booking->payment_status === 'paid')
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-4 mt-1"></i>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Payment Confirmed</h3>
                            <p class="text-gray-700 leading-relaxed">
                                Your payment has been successfully processed. A confirmation email with your receipt has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <p class="text-gray-600 mb-6">We'll send you a confirmation email shortly. Our team will contact you to confirm your appointment.</p>
            
            <a href="{{ route('home') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-green-700 transition shadow-lg">
                Return to Home
            </a>
        </div>
    </div>
</div>
@endsection
