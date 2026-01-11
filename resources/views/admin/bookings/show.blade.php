@extends('layouts.admin')

@section('title', 'Booking Details - SteamZilla')
@section('page-title', 'Booking Details #' . $booking->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings') }}" class="text-[#45A247] hover:text-[#3a8a3c]">
        <i class="fas fa-arrow-left mr-2"></i>Back to Bookings
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Details</h2>
        <div class="space-y-3">
            <div>
                <span class="text-sm text-gray-600">Booking ID:</span>
                <span class="font-semibold text-gray-900">#{{ $booking->id }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Status:</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Date:</span>
                <span class="font-semibold text-gray-900">{{ $booking->booking_date->format('F j, Y') }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Time:</span>
                <span class="font-semibold text-gray-900">{{ date('g:i A', strtotime($booking->booking_time)) }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Total Price:</span>
                <span class="font-semibold text-gray-900 text-lg text-[#45A247]">${{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Customer Information</h3>
        <div class="space-y-3">
            <div>
                <span class="text-sm text-gray-600">Name:</span>
                <span class="font-semibold text-gray-900">{{ $booking->user_name }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Email:</span>
                <span class="font-semibold text-gray-900">{{ $booking->user_email }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Phone:</span>
                <span class="font-semibold text-gray-900">{{ $booking->user_phone }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-600">Address:</span>
                <span class="font-semibold text-gray-900">{{ $booking->address }}</span>
            </div>
            @if($booking->vehicle_type)
                <div>
                    <span class="text-sm text-gray-600">Vehicle Type:</span>
                    <span class="font-semibold text-gray-900">{{ $booking->vehicle_type }}</span>
                </div>
            @endif
            @if($booking->notes)
                <div>
                    <span class="text-sm text-gray-600">Notes:</span>
                    <p class="font-semibold text-gray-900 mt-1">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        @if($booking->payment_method)
            <h3 class="text-lg font-semibold text-gray-900 mt-6 mb-4">Payment Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-600">Payment Method:</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $booking->payment_method === 'square' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $booking->payment_method === 'card' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $booking->payment_method === 'gift_card' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $booking->payment_method === 'cash' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->payment_method)) }}
                    </span>
                </div>
                @if($booking->payment_status)
                    <div>
                        <span class="text-sm text-gray-600">Payment Status:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->payment_status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->payment_status === 'refunded' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $booking->payment_status === 'cancelled' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                @endif
                @if($booking->square_payment_id)
                    <div>
                        <span class="text-sm text-gray-600">Square Payment ID:</span>
                        <span class="font-semibold text-gray-900 font-mono text-xs">{{ $booking->square_payment_id }}</span>
                    </div>
                @endif
                @if($booking->square_receipt_url)
                    <div>
                        <span class="text-sm text-gray-600">Receipt:</span>
                        <a href="{{ $booking->square_receipt_url }}" target="_blank" class="text-[#45A247] hover:text-[#3a8a3c] font-semibold">
                            View Receipt <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                @endif
                @if($booking->square_refund_id)
                    <div>
                        <span class="text-sm text-gray-600">Square Refund ID:</span>
                        <span class="font-semibold text-gray-900 font-mono text-xs">{{ $booking->square_refund_id }}</span>
                    </div>
                @endif
                @if(($booking->payment_method === 'square' || $booking->square_payment_id) && $booking->payment_status === 'paid' && !$booking->square_refund_id)
                    <div class="mt-4">
                        <form action="{{ route('admin.payment.refund', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this payment? This action cannot be undone.');">
                            @csrf
                            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                                <i class="fas fa-undo mr-2"></i>Refund Payment
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Package & Add-Ons</h2>
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-2">Package:</h3>
            <p class="text-lg font-bold text-gray-900">{{ $booking->package->name }}</p>
            <p class="text-sm text-gray-600">Price: ${{ number_format($booking->package->price, 2) }}</p>
            <p class="text-sm text-gray-600">Duration: {{ $booking->package->duration }}</p>
        </div>
        
        @if($booking->bookingAddons->count() > 0)
            <h3 class="font-semibold text-gray-900 mb-2">Add-Ons:</h3>
            <div class="space-y-2 mb-6">
                @foreach($booking->bookingAddons as $bookingAddon)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <div>
                            <span class="font-semibold text-gray-900">{{ $bookingAddon->addon->name }}</span>
                            <span class="text-sm text-gray-600">(x{{ $bookingAddon->quantity }})</span>
                        </div>
                        <span class="font-semibold text-gray-900">${{ number_format($bookingAddon->price_at_booking * $bookingAddon->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 mb-6">No add-ons selected.</p>
        @endif
        
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <select name="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                Update Status
            </button>
        </form>
    </div>
</div>
@endsection
