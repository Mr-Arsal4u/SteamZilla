@extends('layouts.app')

@section('title', 'New Order - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">1</div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Address</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">2</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Order Info</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">3</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Date/Time</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">4</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Payment</span>
                </div>
            </div>
        </div>

        <!-- Step 1 Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">New Order</h1>
            <p class="text-xl text-gray-600 mb-8">Please provide an address for your car wash</p>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('booking.step1.store') }}" method="POST" id="address-form">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label for="country_id" class="block text-lg font-semibold text-gray-900 mb-3">Select Country *</label>
                        <select 
                            id="country_id" 
                            name="country_id" 
                            required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition"
                            onchange="loadCities(this.value)">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $bookingData['country_id'] ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="city_id" class="block text-lg font-semibold text-gray-900 mb-3">Select City *</label>
                        <select 
                            id="city_id" 
                            name="city_id" 
                            required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition"
                            onchange="loadPlaces(this.value)"
                            {{ empty($bookingData['country_id']) ? 'disabled' : '' }}>
                            <option value="">-- Select City --</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $bookingData['city_id'] ?? '') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="place_id" class="block text-lg font-semibold text-gray-900 mb-3">Select Service Area *</label>
                        <select 
                            id="place_id" 
                            name="place_id" 
                            required
                            class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition"
                            {{ empty($bookingData['city_id']) ? 'disabled' : '' }}>
                            <option value="">-- Select Service Area --</option>
                            @foreach($places as $place)
                                <option value="{{ $place->id }}" {{ old('place_id', $bookingData['place_id'] ?? '') == $place->id ? 'selected' : '' }}>
                                    {{ $place->name }}@if($place->address) - {{ $place->address }}@endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-600 mt-2">Select the area where SteamZilla provides services</p>
                    </div>

                    <div>
                        <label for="address" class="block text-lg font-semibold text-gray-900 mb-3">Additional Address Details (Optional)</label>
                        <textarea 
                            id="address" 
                            name="address" 
                            rows="2"
                            placeholder="Enter any additional address details (e.g., apartment number, building name)"
                            class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">{{ old('address', $bookingData['address'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="bg-[#45A247] text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-[#3a8a3c] transition transform hover:scale-105 shadow-lg">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Load cities when country is selected
    function loadCities(countryId) {
        const citySelect = document.getElementById('city_id');
        const placeSelect = document.getElementById('place_id');
        
        // Reset cities and places
        citySelect.innerHTML = '<option value="">-- Select City --</option>';
        citySelect.disabled = !countryId;
        
        placeSelect.innerHTML = '<option value="">-- Select Service Area --</option>';
        placeSelect.disabled = true;
        
        if (!countryId) {
            return;
        }
        
        // Fetch cities via API
        fetch(`/api/cities/${countryId}`)
            .then(response => response.json())
            .then(cities => {
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
                citySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading cities:', error);
            });
    }
    
    // Load places when city is selected
    function loadPlaces(cityId) {
        const placeSelect = document.getElementById('place_id');
        
        // Reset places
        placeSelect.innerHTML = '<option value="">-- Select Service Area --</option>';
        placeSelect.disabled = !cityId;
        
        if (!cityId) {
            return;
        }
        
        // Fetch places via API
        fetch(`/api/places/${cityId}`)
            .then(response => response.json())
            .then(places => {
                places.forEach(place => {
                    const option = document.createElement('option');
                    option.value = place.id;
                    let text = place.name;
                    if (place.address) {
                        text += ' - ' + place.address;
                    }
                    if (place.zip_code) {
                        text += ' (' + place.zip_code + ')';
                    }
                    option.textContent = text;
                    placeSelect.appendChild(option);
                });
                placeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading places:', error);
            });
    }
    
    // Initialize on page load if country/city is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const countryId = document.getElementById('country_id').value;
        const cityId = document.getElementById('city_id').value;
        
        if (countryId) {
            loadCities(countryId);
            if (cityId) {
                // Wait a bit for cities to load, then load places
                setTimeout(() => {
                    loadPlaces(cityId);
                }, 100);
            }
        }
    });
</script>
@endsection

