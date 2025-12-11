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
                
                <div class="mb-6">
                    <label for="address" class="block text-lg font-semibold text-gray-900 mb-3">Car Location:</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        rows="3"
                        value="{{ old('address', $bookingData['address'] ?? '') }}"
                        placeholder="Enter your full address (street, city, state, zip code)" 
                        required
                        class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition"
                        oninput="handleManualAddress()">{{ old('address', $bookingData['address'] ?? '') }}</textarea>
                    <p class="text-sm text-gray-600 mt-2">Please provide your complete address where the service will be performed</p>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $bookingData['latitude'] ?? '') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $bookingData['longitude'] ?? '') }}">
                    <input type="hidden" id="place_id" name="place_id" value="{{ old('place_id', $bookingData['place_id'] ?? '') }}">
                </div>

                <!-- Optional: Map Preview (only if Google Maps API is available) -->
                @if(env('GOOGLE_MAPS_API_KEY'))
                <div id="map-preview" class="hidden mb-6 rounded-lg overflow-hidden border-2 border-gray-200" style="height: 300px;">
                    <div id="map" style="height: 100%; width: 100%;"></div>
                </div>
                @endif

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

<!-- Google Places API (Optional - only loads if API key is configured) -->
@if(env('GOOGLE_MAPS_API_KEY'))
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
@endif

<script>
    let map;
    let marker;
    let autocomplete;
    let geocoder;

    // Handle manual address entry
    function handleManualAddress() {
        // Clear coordinates when user manually types
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('place_id').value = '';
        
        // Hide map if shown
        const mapPreview = document.getElementById('map-preview');
        if (mapPreview) {
            mapPreview.classList.add('hidden');
        }
    }

    // Initialize Google Places Autocomplete (only if API key is available)
    @if(env('GOOGLE_MAPS_API_KEY'))
    function initAutocomplete() {
        const addressInput = document.getElementById('address');
        
        // Initialize geocoder for manual addresses
        geocoder = new google.maps.Geocoder();
        
        // Try to set up autocomplete (may not work if API key is invalid)
        try {
            autocomplete = new google.maps.places.Autocomplete(addressInput, {
                types: ['address'],
                fields: ['formatted_address', 'geometry', 'place_id']
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                
                if (!place.geometry) {
                    console.error('No geometry found for place');
                    return;
                }

                // Set hidden fields
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
                document.getElementById('place_id').value = place.place_id;

                // Show and update map
                showMap(place.geometry.location);
            });
        } catch (error) {
            console.log('Google Places Autocomplete not available:', error);
        }

        // Also allow geocoding on blur (when user finishes typing manually)
        addressInput.addEventListener('blur', function() {
            const address = addressInput.value.trim();
            if (address && geocoder) {
                geocoder.geocode({ address: address }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const location = results[0].geometry.location;
                        document.getElementById('latitude').value = location.lat();
                        document.getElementById('longitude').value = location.lng();
                        document.getElementById('place_id').value = results[0].place_id;
                        showMap(location);
                    }
                });
            }
        });
    }

    function showMap(location) {
        const mapPreview = document.getElementById('map-preview');
        if (!mapPreview) return;
        
        mapPreview.classList.remove('hidden');

        // Initialize map if not already done
        if (!map) {
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
            });
        } else {
            map.setCenter(location);
        }

        // Add or update marker
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                map: map,
                position: location,
                animation: google.maps.Animation.DROP,
            });
        }
    }

    // Fallback if Google Maps API fails to load
    window.initAutocomplete = initAutocomplete || function() {};
    @else
    // No Google Maps API - manual entry only
    function handleManualAddress() {
        // User can manually enter address without API
    }
    @endif
</script>
@endsection

