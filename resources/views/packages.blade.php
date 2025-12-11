@extends('layouts.app')

@section('title', 'Deploy the Beast - Choose Your Mission | SteamZilla')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-50 to-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-gray-900 mb-6">DEPLOY THE BEAST. CHOOSE YOUR MISSION.</h1>
    </div>
</section>

<!-- Signature Service Packages -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">Signature Service Packages</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($packages as $package)
            <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-[#45A247] hover:shadow-xl transition-all duration-300">
                <h3 class="text-xl md:text-2xl font-bold mb-4 text-gray-900">{{ $package->name }}</h3>
                <div class="text-4xl font-bold mb-2" style="color: #45A247;">${{ number_format($package->price, 2) }}</div>
                <p class="text-gray-600 mb-6 font-semibold">{{ $package->duration }}</p>
                @if($package->description)
                    <p class="text-gray-700 mb-6 text-base font-medium italic">{{ $package->description }}</p>
                @endif
                @if($package->features)
                    @php
                        $features = is_array($package->features) ? $package->features : (is_string($package->features) ? json_decode($package->features, true) : []);
                        if (!is_array($features)) {
                            $features = [];
                        }
                    @endphp
                    @if(count($features) > 0)
                        <ul class="space-y-3 mb-8">
                            @foreach($features as $feature)
                                <li class="flex items-start text-gray-700">
                                    <i class="fas fa-check text-[#45A247] mr-3 mt-1 flex-shrink-0"></i>
                                    <span class="text-sm leading-relaxed">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
                <a href="{{ route('order-now', ['package_id' => $package->id]) }}" 
                   class="block w-full bg-[#45A247] text-white text-center py-3 rounded-full hover:bg-[#3a8a3c] transition font-semibold shadow-md hover:shadow-lg uppercase tracking-wide">
                    Select
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- À La Carte Add-Ons -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-gray-900">À La Carte Add-Ons</h2>
        <p class="text-center text-gray-600 mb-4 text-lg italic">The Zilla's Toolkit</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($addons as $addon)
            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-plus-circle text-[#45A247] text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-2 text-gray-900">{{ $addon->name }}</h3>
                <div class="text-2xl font-bold mb-3" style="color: #45A247;">${{ number_format($addon->price, 2) }}</div>
                @if($addon->category)
                    <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">{{ $addon->category }}</p>
                @endif
                @if($addon->description)
                    <p class="text-gray-600 text-sm">{{ $addon->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
