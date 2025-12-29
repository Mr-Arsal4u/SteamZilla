@extends('layouts.admin')

@section('title', 'Edit Package - SteamZilla')
@section('page-title', 'Edit Package')

@section('content')
<div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.packages') }}" class="text-[#45A247] hover:text-[#3a8a3c] text-sm sm:text-base">
        <i class="fas fa-arrow-left mr-2"></i>Back to Packages
    </a>
</div>

<div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-3xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Edit Package</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Package Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $package->price) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
                
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration *</label>
                    <input type="text" name="duration" id="duration" value="{{ old('duration', $package->duration) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('description', $package->description) }}</textarea>
            </div>
            
            <div>
                <label for="features" class="block text-sm font-medium text-gray-700 mb-2">Features (one per line)</label>
                <textarea name="features" id="features" rows="5"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('features', is_array($package->features) ? implode("\n", $package->features) : (is_string($package->features) ? $package->features : '')) }}</textarea>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('admin.packages') }}" class="bg-gray-200 text-gray-700 px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition text-center text-sm sm:text-base">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition text-sm sm:text-base">
                    Update Package
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
