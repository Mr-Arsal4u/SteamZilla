@extends('layouts.app')

@section('title', 'Register - SteamZilla')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">Create Your Account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Join SteamZilla and start booking services</p>
        </div>
        
        <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('user.register.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" 
                        required autofocus>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" 
                        required>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                        placeholder="(555) 123-4567">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" id="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" 
                        required>
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" 
                        required>
                </div>
                
                <button type="submit" class="w-full bg-[#45A247] text-white py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition shadow-lg transform hover:scale-105">
                    Create Account
                </button>
            </form>
            
            <div class="mt-6 text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('user.login') }}" class="text-[#45A247] hover:text-[#3a8a3c] font-semibold">
                        Sign In
                    </a>
                </p>
                <p class="text-xs text-gray-500">
                    Admin? <a href="{{ route('admin.register') }}" class="text-[#45A247] hover:underline">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

