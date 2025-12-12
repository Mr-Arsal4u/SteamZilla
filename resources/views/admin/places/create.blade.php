@extends('layouts.admin')

@section('title', 'Create Place - SteamZilla')
@section('page-title', 'Create Place')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.places') }}" class="text-[#45A247] hover:text-[#3a8a3c]">
        <i class="fas fa-arrow-left mr-2"></i>Back to Places
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Place</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.places.store') }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="country_id" class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                <select name="country_id" id="country_id" required onchange="updateCities()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <option value="">Select a Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                <select name="city_id" id="city_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <option value="">Select a Country first</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" data-country="{{ $city->country_id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Place Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g., Downtown Area, Central Park"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="2" placeholder="Full street address"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('address') }}</textarea>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                    <input type="number" name="latitude" id="latitude" step="any" value="{{ old('latitude') }}" placeholder="e.g., 40.7128"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
                
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                    <input type="number" name="longitude" id="longitude" step="any" value="{{ old('longitude') }}" placeholder="e.g., -74.0060"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
                
                <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" maxlength="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.places') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                    Create Place
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Store all city options on page load
let allCityOptions = [];

document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('city_id');
    // Store all original options (except the first placeholder)
    allCityOptions = Array.from(citySelect.querySelectorAll('option')).slice(1);
    updateCities();
});

function updateCities() {
    const countryId = document.getElementById('country_id').value;
    const citySelect = document.getElementById('city_id');
    
    // Reset city select
    citySelect.innerHTML = '<option value="">Select a City</option>';
    
    if (!countryId) {
        citySelect.disabled = true;
        return;
    }
    
    // Filter and add cities from selected country
    allCityOptions.forEach(option => {
        if (option.dataset.country === countryId) {
            const newOption = option.cloneNode(true);
            citySelect.appendChild(newOption);
        }
    });
    
    citySelect.disabled = false;
}
</script>
@endsection

