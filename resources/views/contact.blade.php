@extends('layouts.app')

@section('title', 'Contact Us - SteamZilla')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-gray-900 mb-6">Contact Us</h1>
        <p class="text-xl text-gray-600">Have a question or query? We'd love to hear from you!</p>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Send Us a Message</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">
                    </div>
                </div>

                <!-- Phone -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message / Query *</label>
                    <textarea id="message" 
                              name="message" 
                              rows="6" 
                              required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">{{ old('message') }}</textarea>
                </div>

                <!-- Image Upload (Optional) -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Image (Optional)
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] transition">
                    <p class="mt-2 text-sm text-gray-500">Accepted formats: JPG, PNG, GIF. Max size: 5MB</p>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" 
                            class="bg-[#45A247] text-white px-10 py-4 rounded-full text-lg font-semibold hover:bg-[#3a8a3c] transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
@if($contactEmail || $contactPhone || $contactAddress)
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">Get in Touch</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            @if($contactEmail)
            <div>
                <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                <p class="text-gray-600"><a href="mailto:{{ $contactEmail }}" class="hover:text-[#45A247]">{{ $contactEmail }}</a></p>
            </div>
            @endif
            
            @if($contactPhone)
            <div>
                <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-phone text-white text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Phone</h3>
                <p class="text-gray-600"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" class="hover:text-[#45A247]">{{ $contactPhone }}</a></p>
            </div>
            @endif
            
            @if($contactAddress)
            <div>
                <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">Address</h3>
                <p class="text-gray-600 whitespace-pre-line">{{ $contactAddress }}</p>
            </div>
            @endif
        </div>
        
        @if($contactHours)
        <div class="mt-12 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Business Hours</h3>
            <p class="text-gray-600 whitespace-pre-line">{{ $contactHours }}</p>
        </div>
        @endif
    </div>
</section>
@endif
@endsection
