@extends('layouts.admin')

@section('title', 'Payments Management - SteamZilla')
@section('page-title', 'Payments Management')

@section('content')
<!-- Payment Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($paymentStats['total_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <p class="text-gray-600 text-sm font-medium">Card Payments</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($paymentStats['card_payments'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <p class="text-gray-600 text-sm font-medium">Gift Card Payments</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($paymentStats['gift_card_payments'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
        <p class="text-gray-600 text-sm font-medium">Gift Card Discounts</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($paymentStats['gift_card_discounts'], 2) }}</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.payments') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
            <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <option value="">All Methods</option>
                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                <option value="gift_card" {{ request('payment_method') == 'gift_card' ? 'selected' : '' }}>Gift Card</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <div class="flex">
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-r-lg hover:bg-[#3a8a3c]">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gift Card Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $payment->user_name }}</div>
                        <div class="text-sm text-gray-500">{{ $payment->user_email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->package->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">${{ number_format($payment->total_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $payment->payment_method === 'card' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($payment->gift_card_discount > 0)
                            -${{ number_format($payment->gift_card_discount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->created_at->format('M j, Y g:i A') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.bookings.show', $payment->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No payments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-gray-50 px-6 py-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection

