@extends('layouts.app')

@section('title', 'Gift Card Balance - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Gift Card Balance</h2>
            
            <div class="space-y-4">
                <div class="bg-green-50 border-2 border-[#45A247] rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700 font-semibold">Card Number:</span>
                        <span class="text-gray-900 font-mono text-lg">{{ $giftCard->gift_card_number }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700 font-semibold">Current Balance:</span>
                        <span class="text-3xl font-bold text-[#45A247]">${{ number_format($giftCard->amount, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-700 font-semibold">Status:</span>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold 
                            {{ $giftCard->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $giftCard->status === 'expired' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $giftCard->status === 'used_up' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ strtoupper($giftCard->status) }}
                        </span>
                    </div>
                    @if($giftCard->expires_at)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-semibold">Expires:</span>
                        <span class="text-gray-900">{{ $giftCard->expires_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ route('gift-cards', ['tab' => 'reload']) }}" class="inline-block bg-[#45A247] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition mr-4">
                        Reload Card
                    </a>
                    <a href="{{ route('gift-cards') }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition">
                        Back to Gift Cards
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

