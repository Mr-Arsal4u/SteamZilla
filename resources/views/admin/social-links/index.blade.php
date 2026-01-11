@extends('layouts.admin')

@section('title', 'Manage Social Links - SteamZilla')
@section('page-title', 'Social Links Management')

@section('content')
<div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Social Links</h2>
    <a href="{{ route('admin.social-links.create') }}" class="bg-[#45A247] text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition text-sm sm:text-base w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Add New Social Link
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Mobile Card View -->
    <div class="lg:hidden divide-y divide-gray-200">
        @forelse($socialLinks as $socialLink)
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <div class="flex items-center space-x-3">
                    @if($socialLink->icon)
                        <i class="{{ $socialLink->icon }} text-xl text-gray-600"></i>
                    @endif
                    <h3 class="font-semibold text-gray-900">{{ $socialLink->platform }}</h3>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $socialLink->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $socialLink->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="text-sm text-gray-600 mb-3">
                <a href="{{ $socialLink->url }}" target="_blank" class="text-[#45A247] hover:underline break-all">
                    {{ $socialLink->url }}
                </a>
            </div>
            <div class="text-sm text-gray-500 mb-3">
                Sort Order: {{ $socialLink->sort_order }}
            </div>
            <div class="flex space-x-3 text-sm">
                <a href="{{ route('admin.social-links.edit', $socialLink->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.social-links.delete', $socialLink->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500">No social links found</div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($socialLinks as $socialLink)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $socialLink->platform }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <a href="{{ $socialLink->url }}" target="_blank" class="text-[#45A247] hover:underline">
                            {{ \Illuminate\Support\Str::limit($socialLink->url, 40) }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($socialLink->icon)
                            <i class="{{ $socialLink->icon }} text-xl"></i>
                        @else
                            <span class="text-gray-400">Default</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $socialLink->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $socialLink->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $socialLink->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.social-links.edit', $socialLink->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.social-links.delete', $socialLink->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No social links found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

