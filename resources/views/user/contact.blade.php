@extends('layouts.user')

@section('title', 'Contact Us - SteamZilla')
@section('page-title', 'Contact Us')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Send Us a Query</h3>
        <p class="text-xs sm:text-sm text-gray-600 mt-1">Have a question or need assistance? We're here to help!</p>
    </div>

    <form action="{{ route('user.contact.submit') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Your Information</label>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-900"><strong>Name:</strong> {{ $user->name }}</p>
                <p class="text-gray-900 mt-1"><strong>Email:</strong> {{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-gray-900 mt-1"><strong>Phone:</strong> {{ $user->phone }}</p>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message / Query *</label>
            <textarea name="message" id="message" rows="6" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                placeholder="Please describe your query or question...">{{ old('message') }}</textarea>
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                Upload Image (Optional)
            </label>
            <input type="file" name="image" id="image" accept="image/*"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            <p class="mt-2 text-sm text-gray-500">Accepted formats: JPG, PNG, GIF. Max size: 5MB</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#45A247] text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold hover:bg-[#3a8a3c] transition text-sm sm:text-base w-full sm:w-auto">
                <i class="fas fa-paper-plane mr-2"></i> Send Query
            </button>
        </div>
    </form>
</div>

<!-- Previous Queries -->
@php
    try {
        $previousQueries = \App\Models\ContactSubmission::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    } catch (\Exception $e) {
        $previousQueries = collect([]);
    }
@endphp

@if($previousQueries->count() > 0)
<div class="mt-4 sm:mt-6 bg-white rounded-lg shadow overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Previous Queries</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @foreach($previousQueries as $query)
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
            <div class="flex items-start justify-between flex-col sm:flex-row gap-2">
                <div class="flex-1">
                    <p class="text-sm sm:text-base text-gray-900 mb-2">{{ Str::limit($query->message, 150) }}</p>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>{{ $query->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
                <div>
                    @if($query->is_read)
                        <span class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Read</span>
                    @else
                        <span class="px-2 sm:px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Pending</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection

