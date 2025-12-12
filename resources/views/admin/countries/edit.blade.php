@extends('layouts.admin')

@section('title', 'Edit Country - SteamZilla')
@section('page-title', 'Edit Country')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.countries') }}" class="text-[#45A247] hover:text-[#3a8a3c]">
        <i class="fas fa-arrow-left mr-2"></i>Back to Countries
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Country</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.countries.update', $country->id) }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Country Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $country->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Country Code (ISO)</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $country->code) }}" maxlength="3" placeholder="e.g., USA"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <p class="text-xs text-gray-500 mt-1">Optional: 3-letter ISO code</p>
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $country->sort_order) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $country->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.countries') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                    Update Country
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

