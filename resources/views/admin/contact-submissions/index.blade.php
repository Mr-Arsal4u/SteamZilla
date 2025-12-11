@extends('layouts.admin')

@section('title', 'Contact Submissions - SteamZilla')
@section('page-title', 'Contact Submissions')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">All Contact Submissions</h3>
            <div class="text-sm text-gray-600">
                Total: {{ $submissions->total() }} | 
                Unread: <span class="font-semibold text-orange-600">{{ \App\Models\ContactSubmission::where('is_read', false)->count() }}</span>
            </div>
        </div>
    </div>

    @if($submissions->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($submissions as $submission)
                <tr class="{{ !$submission->is_read ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $submission->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $submission->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $submission->phone }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 max-w-xs truncate">{{ Str::limit($submission->message, 80) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $submission->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $submission->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($submission->is_read)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Read</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Unread</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.contact-submissions.show', $submission->id) }}" 
                           class="text-[#45A247] hover:text-[#3a8a3c] mr-3">View</a>
                        <form action="{{ route('admin.contact-submissions.delete', $submission->id) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this submission?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $submissions->links() }}
    </div>
    @else
    <div class="p-12 text-center">
        <i class="fas fa-inbox text-6xl text-gray-400 mb-4"></i>
        <p class="text-gray-500 text-lg">No contact submissions yet</p>
    </div>
    @endif
</div>
@endsection

