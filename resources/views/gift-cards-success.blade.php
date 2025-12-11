@extends('layouts.app')

@section('title', 'Gift Card Success - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <i class="fas fa-check-circle text-6xl text-[#45A247] mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Success!</h2>
                <p class="text-gray-600">Your gift card has been processed successfully.</p>
            </div>

            <div class="bg-green-50 border-2 border-[#45A247] rounded-lg p-6 mb-6 text-left">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Gift Card Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700 font-semibold">Card Number:</span>
                        <span class="text-gray-900 font-mono">{{ $giftCard->gift_card_number }}</span>
                    </div>
                    @if($giftCard->pin)
                    <div class="flex justify-between">
                        <span class="text-gray-700 font-semibold">PIN:</span>
                        <span class="text-gray-900 font-mono">{{ $giftCard->pin }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-700 font-semibold">Amount:</span>
                        <span class="text-2xl font-bold text-[#45A247]">${{ number_format($giftCard->amount, 2) }}</span>
                    </div>
                    @if($giftCard->discount_applied > 0)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Discount Applied:</span>
                        <span>-${{ number_format($giftCard->discount_applied, 2) }}</span>
                    </div>
                    @endif
                    @if($giftCard->expires_at)
                    <div class="flex justify-between">
                        <span class="text-gray-700 font-semibold">Expires:</span>
                        <span class="text-gray-900">{{ $giftCard->expires_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if($giftCard->message)
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-gray-600 mb-1">Message:</p>
                <p class="text-gray-900 italic">"{{ $giftCard->message }}"</p>
            </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('gift-cards') }}" class="inline-block bg-[#45A247] text-white px-8 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition transform hover:scale-105">
                    Back to Gift Cards
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

