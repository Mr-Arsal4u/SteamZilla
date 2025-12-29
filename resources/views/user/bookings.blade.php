@extends('layouts.user')

@section('title', 'My Bookings - SteamZilla')
@section('page-title', 'My Bookings')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">All My Bookings</h3>
            <a href="{{ route('order-now') }}" class="bg-[#45A247] text-white px-4 py-2 rounded-lg hover:bg-[#3a8a3c] transition text-sm sm:text-base w-full sm:w-auto text-center">
                <i class="fas fa-plus mr-2"></i> New Booking
            </a>
        </div>
    </div>

    @if($bookings->count() > 0)
    <!-- Mobile Card View -->
    <div class="lg:hidden divide-y divide-gray-200">
        @foreach($bookings as $booking)
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base">{{ $booking->package->name }}</h3>
                    @if($booking->bookingAddons->count() > 0)
                        <p class="text-xs text-gray-500 mt-1">+{{ $booking->bookingAddons->count() }} add-on(s)</p>
                    @endif
                </div>
                <span class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full
                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="text-sm text-gray-600 space-y-1 mb-3">
                <p><span class="font-medium">Date:</span> {{ $booking->booking_date->format('M d, Y') }} at {{ $booking->booking_time }}</p>
                <p><span class="font-medium">Address:</span> {{ $booking->address }}</p>
                <p><span class="font-medium">Amount:</span> <span class="font-semibold">${{ number_format($booking->total_price, 2) }}</span></p>
            </div>
            <div>
                <a href="{{ route('user.bookings.show', $booking->id) }}" 
                   class="text-[#45A247] hover:text-[#3a8a3c] text-sm font-medium">
                    View Details →
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->package->name }}</div>
                        @if($booking->bookingAddons->count() > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                +{{ $booking->bookingAddons->count() }} add-on(s)
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->booking_date->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->booking_time }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $booking->address }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">${{ number_format($booking->total_price, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('user.bookings.show', $booking->id) }}" 
                           class="text-[#45A247] hover:text-[#3a8a3c]">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @else
    <div class="p-8 sm:p-12 text-center">
        <i class="fas fa-calendar-times text-4xl sm:text-6xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-base sm:text-lg mb-2">No bookings yet</p>
        <p class="text-gray-400 text-sm mb-6">Start booking your first steam cleaning service</p>
        <a href="{{ route('order-now') }}" class="inline-block bg-[#45A247] text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-[#3a8a3c] transition text-sm sm:text-base">
            Book Now
        </a>
    </div>
    @endif
</div>
@endsection

