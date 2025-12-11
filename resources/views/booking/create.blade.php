@extends('layouts.app')

@section('title', 'Order Now - SteamZilla')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Order Now</h1>
        <p class="text-xl text-gray-600">Book your steam cleaning service today</p>
    </div>
</section>

<!-- Booking Form Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Booking Information</h2>
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        
                        @if($package)
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Selected Package</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $package->name }} - ${{ number_format($package->price, 2) }}</p>
                            </div>
                        @else
                            <div class="mb-6">
                                <label for="package_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Package *</label>
                                <select name="package_id" id="package_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                                    <option value="">Choose a package...</option>
                                    @foreach(\App\Models\Package::where('is_active', true)->get() as $pkg)
                                        <option value="{{ $pkg->id }}" {{ old('package_id') == $pkg->id ? 'selected' : '' }}>
                                            {{ $pkg->name }} - ${{ number_format($pkg->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="user_name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                                @error('user_name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="user_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="user_phone" id="user_phone" value="{{ old('user_phone') }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                                @error('user_phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="user_email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" name="user_email" id="user_email" value="{{ old('user_email') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                            @error('user_email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Service Address *</label>
                            <textarea name="address" id="address" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="vehicle_type" class="block text-sm font-semibold text-gray-700 mb-2">Vehicle Type</label>
                                <input type="text" name="vehicle_type" id="vehicle_type" value="{{ old('vehicle_type') }}" 
                                    placeholder="e.g., Sedan, SUV, Truck"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600">
                                @error('vehicle_type')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="booking_date" class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date *</label>
                                <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}" 
                                    min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                                @error('booking_date')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="booking_time" class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time *</label>
                            <input type="time" name="booking_time" id="booking_time" value="{{ old('booking_time') }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" required>
                            @error('booking_time')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Add-On Services</label>
                            <div class="border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                                @foreach(\App\Models\Addon::where('is_active', true)->get() as $addon)
                                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-0">
                                        <label class="flex items-start cursor-pointer">
                                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" 
                                                class="mt-1 mr-3 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-600"
                                                {{ in_array($addon->id, old('addons', [])) ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">{{ $addon->name }} - ${{ number_format($addon->price, 2) }}</div>
                                                @if($addon->description)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $addon->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                        @if($addon->has_quantity)
                                            <div class="ml-8 mt-2">
                                                <label class="text-sm text-gray-600">Quantity:</label>
                                                <input type="number" name="addon_quantities[{{ $addon->id }}]" 
                                                    value="{{ old('addon_quantities.'.$addon->id, 1) }}" min="1" 
                                                    class="ml-2 w-20 px-2 py-1 border border-gray-300 rounded">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600">{{ old('notes') }}</textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-full text-lg font-semibold hover:bg-green-700 transition shadow-lg">
                            Submit Booking
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 sticky top-24">
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Booking Summary</h3>
                    @if($package)
                        <div class="mb-4 pb-4 border-b border-green-200">
                            <p class="text-sm text-gray-600 mb-1">Package</p>
                            <p class="font-semibold text-gray-900">{{ $package->name }}</p>
                            <p class="text-green-600 font-bold">${{ number_format($package->price, 2) }}</p>
                        </div>
                    @endif
                    <div class="text-sm text-gray-600">
                        <p>Add-ons will be calculated at checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
