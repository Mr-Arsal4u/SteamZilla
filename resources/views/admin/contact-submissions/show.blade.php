@extends('layouts.admin')

@section('title', 'Contact Submission Details - SteamZilla')
@section('page-title', 'Contact Submission Details')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Submission from {{ $submission->name }}</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Submitted on {{ $submission->created_at->format('F d, Y \a\t h:i A') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @if(!$submission->is_read)
                    <form action="{{ route('admin.contact-submissions.mark-read', $submission->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                            Mark as Read
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm">Read</span>
                @endif
                <form action="{{ route('admin.contact-submissions.delete', $submission->id) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this submission?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <div class="bg-gray-50 rounded-lg p-4 text-gray-900">{{ $submission->name }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <a href="mailto:{{ $submission->email }}" class="text-[#45A247] hover:underline">{{ $submission->email }}</a>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $submission->phone) }}" class="text-[#45A247] hover:underline">{{ $submission->phone }}</a>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Submitted</label>
                <div class="bg-gray-50 rounded-lg p-4 text-gray-900">
                    {{ $submission->created_at->format('F d, Y \a\t h:i A') }}
                    <span class="text-gray-500 text-sm">({{ $submission->created_at->diffForHumans() }})</span>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Message / Query</label>
            <div class="bg-gray-50 rounded-lg p-4 text-gray-900 whitespace-pre-wrap">{{ $submission->message }}</div>
        </div>

        @if($submission->image_path)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Uploaded Image</label>
            <div class="bg-gray-50 rounded-lg p-4">
                <img src="{{ asset('storage/' . $submission->image_path) }}" 
                     alt="Submission image" 
                     class="max-w-full h-auto rounded-lg shadow-md">
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $submission->image_path) }}" 
                       target="_blank" 
                       class="text-[#45A247] hover:underline text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> Open Full Size
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <a href="{{ route('admin.contact-submissions') }}" 
           class="text-[#45A247] hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Back to All Submissions
        </a>
    </div>
</div>
@endsection

