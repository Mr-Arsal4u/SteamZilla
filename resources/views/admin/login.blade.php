@extends('layouts.app')

@section('title', 'Admin Login - SteamZilla')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">Admin Login</h2>
            <p class="mt-2 text-center text-sm text-gray-600">Sign in to access the admin panel</p>
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
            
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" 
                        required autofocus>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600" 
                        required>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" 
                        class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-600">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                </div>
                
                <button type="submit" class="w-full bg-[#45A247] text-white py-3 rounded-full font-semibold hover:bg-[#3a8a3c] transition shadow-lg">
                    Sign In
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('admin.register') }}" class="text-sm text-[#45A247] hover:text-[#3a8a3c] font-semibold">
                    Don't have an account? Register
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

