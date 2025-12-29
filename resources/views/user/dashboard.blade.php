@extends('layouts.user')

@section('title', 'Dashboard - SteamZilla')
@section('page-title', 'Dashboard')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-[#45A247] to-[#3a8a3c] rounded-lg shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 text-white">
    <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold mb-2">Welcome back, {{ $user->name }}!</h2>
            <p class="text-gray-100 text-sm sm:text-base">Here's an overview of your account activity</p>
        </div>
        @if($user->avatar)
            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-white shadow-lg">
        @else
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white/20 flex items-center justify-center border-4 border-white">
                <i class="fas fa-user text-3xl sm:text-4xl text-white"></i>
            </div>
        @endif
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-xs sm:text-sm font-medium">Total Bookings</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] }}</p>
            </div>
            <div class="bg-blue-100 p-2 sm:p-3 rounded-full">
                <i class="fas fa-calendar-check text-blue-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-xs sm:text-sm font-medium">Pending</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_bookings'] }}</p>
            </div>
            <div class="bg-yellow-100 p-2 sm:p-3 rounded-full">
                <i class="fas fa-clock text-yellow-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-xs sm:text-sm font-medium">Completed</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">{{ $stats['completed_bookings'] }}</p>
            </div>
            <div class="bg-green-100 p-2 sm:p-3 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-xs sm:text-sm font-medium">Total Spent</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_spent'], 2) }}</p>
            </div>
            <div class="bg-purple-100 p-2 sm:p-3 rounded-full">
                <i class="fas fa-dollar-sign text-purple-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                <a href="{{ route('user.bookings') }}" class="text-[#45A247] hover:underline text-sm font-medium">
                    View All →
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($bookings as $booking)
            <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between flex-col sm:flex-row gap-2">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">{{ $booking->package->name }}</h4>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2">
                            <i class="fas fa-calendar mr-2"></i>{{ $booking->booking_date->format('M d, Y') }}
                            <span class="mx-2 hidden sm:inline">|</span>
                            <span class="block sm:inline"><i class="fas fa-clock mr-2"></i>{{ $booking->booking_time }}</span>
                        </p>
                        <p class="text-sm font-semibold text-gray-900">${{ number_format($booking->total_price, 2) }}</p>
                    </div>
                    <div>
                        <span class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full
                            {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('user.bookings.show', $booking->id) }}" 
                       class="text-[#45A247] hover:underline text-xs sm:text-sm font-medium">
                        View Details →
                    </a>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">No bookings yet</p>
                <a href="{{ route('order-now') }}" class="text-[#45A247] hover:underline mt-2 inline-block">
                    Book Your First Service →
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Contact Queries -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Queries</h3>
                <a href="{{ route('user.contact') }}" class="text-[#45A247] hover:underline text-sm font-medium">
                    Send Query →
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($contactSubmissions as $submission)
            <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between flex-col sm:flex-row gap-2">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($submission->message, 100) }}</p>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>{{ $submission->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if($submission->is_read)
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Read</span>
                    @else
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Pending</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <i class="fas fa-envelope-open text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">No queries yet</p>
                <a href="{{ route('user.contact') }}" class="text-[#45A247] hover:underline mt-2 inline-block">
                    Send Your First Query →
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 sm:mt-8 bg-white rounded-lg shadow p-4 sm:p-6">
    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('order-now') }}" class="flex items-center p-3 sm:p-4 bg-[#45A247]/10 rounded-lg hover:bg-[#45A247]/20 transition">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#45A247] rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                <i class="fas fa-plus text-white text-lg sm:text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Book Service</h4>
                <p class="text-xs sm:text-sm text-gray-600">Schedule a new cleaning</p>
            </div>
        </a>
        <a href="{{ route('user.contact') }}" class="flex items-center p-3 sm:p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                <i class="fas fa-envelope text-white text-lg sm:text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Contact Us</h4>
                <p class="text-xs sm:text-sm text-gray-600">Send us a message</p>
            </div>
        </a>
        <a href="{{ route('user.profile') }}" class="flex items-center p-3 sm:p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-500 rounded-full flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                <i class="fas fa-user text-white text-lg sm:text-xl"></i>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Update Profile</h4>
                <p class="text-xs sm:text-sm text-gray-600">Manage your account</p>
            </div>
        </a>
    </div>
</div>
@endsection

