@extends('layouts.user')

@section('title', 'Activity - SteamZilla')
@section('page-title', 'My Activity')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">All Activity</h3>
        <p class="text-xs sm:text-sm text-gray-600 mt-1">View your complete activity history</p>
    </div>

    @if($activities->count() > 0)
    <div class="divide-y divide-gray-200">
        @foreach($activities as $activity)
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($activity['type'] === 'booking')
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#45A247] rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-sm sm:text-base"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-sm sm:text-base"></i>
                        </div>
                    @endif
                </div>
                <div class="ml-3 sm:ml-4 flex-1">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <div class="flex-1">
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900">{{ $activity['title'] }}</h4>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $activity['description'] }}</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-xs sm:text-sm text-gray-500">{{ $activity['date']->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $activity['date']->format('h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($activity['type'] === 'booking')
                        @php $booking = $activity['data']; @endphp
                        <div class="mt-3 sm:mt-4 bg-gray-50 rounded-lg p-3 sm:p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                                <div>
                                    <span class="text-gray-600">Service:</span>
                                    <span class="font-semibold text-gray-900 ml-2">{{ $booking->package->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-semibold text-gray-900 ml-2">{{ $booking->booking_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Amount:</span>
                                    <span class="font-semibold text-[#45A247] ml-2">${{ number_format($booking->total_price, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full ml-2
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
                                   class="text-[#45A247] hover:underline text-sm font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    @else
                        @php $submission = $activity['data']; @endphp
                        <div class="mt-3 sm:mt-4 bg-gray-50 rounded-lg p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-gray-700">{{ Str::limit($submission->message, 200) }}</p>
                            <div class="mt-2 flex items-center">
                                @if($submission->is_read)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Read</span>
                                @else
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Pending</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="p-8 sm:p-12 text-center">
        <i class="fas fa-history text-4xl sm:text-6xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-base sm:text-lg">No activity yet</p>
        <p class="text-gray-400 text-xs sm:text-sm mt-2">Your activity will appear here</p>
    </div>
    @endif
</div>
@endsection

