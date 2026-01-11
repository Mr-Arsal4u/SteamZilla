@extends('layouts.admin')

@section('title', 'Bookings Management - SteamZilla')
@section('page-title', 'Bookings Management')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-4 sm:mb-6">
    <form method="GET" action="{{ route('admin.bookings') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <div class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-r-lg hover:bg-[#3a8a3c]">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Mobile Card View -->
    <div class="lg:hidden divide-y divide-gray-200">
        @forelse($bookings as $booking)
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-semibold text-gray-900">#{{ $booking->id }}</h3>
                    <p class="text-sm text-gray-600">{{ $booking->user_name }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="text-sm text-gray-600 space-y-1 mb-3">
                <p><span class="font-medium">Email:</span> {{ $booking->user_email }}</p>
                <p><span class="font-medium">Phone:</span> {{ $booking->user_phone }}</p>
                <p><span class="font-medium">Package:</span> {{ $booking->package->name }}</p>
                <p><span class="font-medium">Date:</span> {{ $booking->booking_date->format('M j, Y') }} at {{ date('g:i A', strtotime($booking->booking_time)) }}</p>
                <p><span class="font-medium">Address:</span> {{ $booking->address }}</p>
                <p><span class="font-medium">Amount:</span> <span class="font-semibold">${{ number_format($booking->total_price, 2) }}</span></p>
            </div>
            <div class="flex space-x-3 text-sm">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                    <i class="fas fa-eye mr-1"></i> View
                </a>
                <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500">No bookings found</div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $booking->user_name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->user_email }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->user_phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->package->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>{{ $booking->booking_date->format('M j, Y') }}</div>
                        <div class="text-gray-500">{{ date('g:i A', strtotime($booking->booking_time)) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $booking->address }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($booking->total_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.bookings.delete', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-gray-50 px-4 sm:px-6 py-4">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
