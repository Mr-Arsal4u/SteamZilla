@extends('layouts.admin')

@section('title', 'Create Social Link - SteamZilla')
@section('page-title', 'Create Social Link')

@section('content')
<div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.social-links') }}" class="text-[#45A247] hover:text-[#3a8a3c] text-sm sm:text-base">
        <i class="fas fa-arrow-left mr-2"></i>Back to Social Links
    </a>
</div>

<div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-3xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Add New Social Link</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.social-links.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="platform" class="block text-sm font-medium text-gray-700 mb-2">Platform Name *</label>
                <input type="text" name="platform" id="platform" value="{{ old('platform') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                    placeholder="e.g., Facebook, Instagram, Twitter, LinkedIn">
                <p class="mt-1 text-sm text-gray-500">Name of the social media platform</p>
            </div>
            
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL *</label>
                <input type="url" name="url" id="url" value="{{ old('url') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                    placeholder="https://www.facebook.com/yourpage">
                <p class="mt-1 text-sm text-gray-500">Full URL to your social media profile/page</p>
            </div>
            
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon Class (Optional)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                    placeholder="fab fa-facebook">
                <p class="mt-1 text-sm text-gray-500">Font Awesome icon class (e.g., fab fa-facebook, fab fa-instagram). Leave empty for auto-detection.</p>
            </div>
            
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in the footer</p>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible on website)</span>
                </label>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('admin.social-links') }}" class="bg-gray-200 text-gray-700 px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition text-center text-sm sm:text-base">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition text-sm sm:text-base">
                    Create Social Link
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

