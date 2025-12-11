@extends('layouts.admin')

@section('title', 'Admin Dashboard - SteamZilla')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Monthly Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['monthly_revenue'], 2) }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend (Last 6 Months)</h3>
        <div style="height: 250px; position: relative;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Status</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Pending</span>
                </div>
                <span class="font-semibold text-gray-900">{{ $stats['pending_bookings'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Confirmed</span>
                </div>
                <span class="font-semibold text-gray-900">{{ $stats['confirmed_bookings'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Completed</span>
                </div>
                <span class="font-semibold text-gray-900">{{ $stats['completed_bookings'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Cancelled</span>
                </div>
                <span class="font-semibold text-gray-900">{{ $stats['cancelled_bookings'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Contact Submissions Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Contact Queries</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_contact_submissions'] }}</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <i class="fas fa-envelope text-indigo-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Unread Queries</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['unread_contact_submissions'] }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-full">
                <i class="fas fa-envelope-open text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Contact Submissions -->
@if($recentContactSubmissions->count() > 0)
<div class="bg-white rounded-lg shadow mb-8">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Contact Queries</h3>
            <a href="{{ route('admin.contact-submissions') }}" class="text-[#45A247] hover:underline text-sm font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="divide-y divide-gray-200">
        @foreach($recentContactSubmissions as $submission)
        <div class="p-6 hover:bg-gray-50 transition {{ !$submission->is_read ? 'bg-blue-50' : '' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h4 class="font-semibold text-gray-900 mr-3">{{ $submission->name }}</h4>
                        @if(!$submission->is_read)
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">New</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-envelope mr-2"></i>{{ $submission->email }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-phone mr-2"></i>{{ $submission->phone }}
                    </p>
                    <p class="text-gray-700 text-sm line-clamp-2">{{ Str::limit($submission->message, 150) }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-clock mr-1"></i>{{ $submission->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="ml-4">
                    <a href="{{ route('admin.contact-submissions.show', $submission->id) }}" 
                       class="text-[#45A247] hover:underline text-sm font-medium">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
            <a href="{{ route('admin.bookings') }}" class="text-[#45A247] hover:text-[#3a8a3c] font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $booking->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->user_name }}</div>
                        <div class="text-sm text-gray-500">{{ $booking->user_email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->package->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->booking_date->format('M j, Y') }}</td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($revenueData, 'month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode(array_column($revenueData, 'revenue')) !!},
                borderColor: '#45A247',
                backgroundColor: 'rgba(69, 162, 71, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
