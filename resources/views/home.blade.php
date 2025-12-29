@extends('layouts.app')

@section('title', 'SteamZilla Mobile Detailing - The Power of Clean, Unleashed')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
$heroBgUrl = $heroBackground ? asset('storage/' . $heroBackground) : 'images/hero.png';
@endphp
<!-- SECTION 1: HERO SECTION -->
<section class="relative h-[500px] sm:h-[600px] md:h-[700px] lg:h-[800px] flex items-center overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/75 to-black/65 z-10"></div>
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('{{ $heroBgUrl }}');"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-3xl bg-black/30 backdrop-blur-sm rounded-xl sm:rounded-2xl p-4 sm:p-6 md:p-8 lg:p-12">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 sm:mb-6 leading-tight animate-fade-in drop-shadow-2xl">
                {{ $heroTitle }}
            </h1>
            @if($heroSubtitle)
            <p class="text-lg sm:text-xl md:text-2xl text-white mb-4 sm:mb-6 font-semibold drop-shadow-lg">
                {{ $heroSubtitle }}
            </p>
            @endif
            @if($heroDescription)
            <p class="text-sm sm:text-base md:text-lg text-white mb-6 sm:mb-8 leading-relaxed drop-shadow-md">
                {{ $heroDescription }}
            </p>
            @endif
            <a href="{{ route('order-now') }}"
               class="inline-block bg-[#45A247] text-white px-6 sm:px-8 md:px-10 py-3 sm:py-4 md:py-5 rounded-full text-sm sm:text-base md:text-lg lg:text-xl font-bold hover:bg-[#3a8a3c] transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl uppercase tracking-wide text-center">
                SUMMON STEAMZILLA FOR A QUOTE
            </a>
        </div>
    </div>
</section>

<!-- SECTION 2: BENEFITS GRID -->
@if($benefitsTitle || $benefitsDescription)
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($benefitsTitle)
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-3 sm:mb-4 text-gray-900">{{ $benefitsTitle }}</h2>
        @endif
        @if($benefitsDescription)
            <p class="text-center text-gray-600 mb-8 sm:mb-12 text-base sm:text-lg max-w-3xl mx-auto px-4">{{ $benefitsDescription }}</p>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Benefit Card 1 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-2 border-gray-100">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                    <span class="text-3xl sm:text-4xl">☁️</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Chemical-Free Crusader</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Our primary weapon is pure, pressurized steam. We drastically reduce chemical use, making your car safe for kids, pets, and the planet.</p>
            </div>

            <!-- Benefit Card 2 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-2 border-gray-100">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                    <span class="text-3xl sm:text-4xl">⚡</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Sanitization Onslaught</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">SteamZilla's heat eliminates 99.9% of bacteria, viruses, and allergens. We don't just clean your interior; we sanitize it.</p>
            </div>

            <!-- Benefit Card 3 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-2 border-gray-100">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                    <span class="text-3xl sm:text-4xl">💧</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Deep-Pore Destruction</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Traditional methods smear dirt. Our steam penetrates and lifts contaminants from fabrics, leather, and crevices, which we then completely extract.</p>
            </div>

            <!-- Benefit Card 4 -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border-2 border-gray-100">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                    <span class="text-3xl sm:text-4xl">🦖</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Convenience That Dominates</h3>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">We come to you. Your home, your office, your life. Schedule online and watch the beast get to work.</p>
            </div>
        </div>
    </div>
</section>
@endif

<!-- SECTION 3: HOW IT WORKS -->
<section class="py-12 sm:py-16 md:py-20 bg-[#F7FFF7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($howItWorksTitle)
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-3 sm:mb-4 text-gray-900">{{ $howItWorksTitle }}</h2>
        @endif
        @if($howItWorksDescription)
            <p class="text-center text-gray-600 mb-8 sm:mb-12 text-base sm:text-lg max-w-3xl mx-auto px-4">{{ $howItWorksDescription }}</p>
        @endif
        <div class="mb-8 sm:mb-12 md:mb-16"></div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                    <i class="fas fa-calendar-check text-white text-2xl sm:text-3xl"></i>
                </div>
                <div class="w-12 h-1 bg-[#45A247] mx-auto mb-4 sm:mb-6 hidden lg:block"></div>
                <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-gray-900">Book Your Service</h3>
                <p class="text-sm sm:text-base text-gray-600">Choose preferred date and time.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                    <i class="fas fa-map-marker-alt text-white text-2xl sm:text-3xl"></i>
                </div>
                <div class="w-12 h-1 bg-[#45A247] mx-auto mb-4 sm:mb-6 hidden lg:block"></div>
                <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-gray-900">We Come to You</h3>
                <p class="text-sm sm:text-base text-gray-600">Our team arrives with advanced steam equipment.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                    <i class="fas fa-car text-white text-2xl sm:text-3xl"></i>
                </div>
                <div class="w-12 h-1 bg-[#45A247] mx-auto mb-4 sm:mb-6 hidden lg:block"></div>
                <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-gray-900">Professional Cleaning</h3>
                <p class="text-sm sm:text-base text-gray-600">We provide a complete exterior and interior steam treatment.</p>
            </div>

            <!-- Step 4 -->
            <div class="text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-[#45A247] rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                    <i class="fas fa-sparkles text-white text-2xl sm:text-3xl"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold mb-2 sm:mb-3 text-gray-900">Enjoy Your Fresh Car</h3>
                <p class="text-sm sm:text-base text-gray-600">Your car feels fresh, sanitized, and spotless.</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 4: PRICING CARDS -->
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-3 sm:mb-4 text-gray-900">Signature Service Packages</h2>
        <p class="text-center text-gray-600 mb-8 sm:mb-12 text-base sm:text-lg px-4">DEPLOY THE BEAST. CHOOSE YOUR MISSION.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($packages as $package)
            <div class="bg-white border-2 border-gray-200 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:border-[#45A247] hover:shadow-xl transition-all duration-300">
                <h3 class="text-lg sm:text-xl md:text-2xl font-bold mb-3 sm:mb-4 text-gray-900">{{ $package->name }}</h3>
                <div class="text-3xl sm:text-4xl font-bold mb-2" style="color: #45A247;">${{ number_format($package->price, 2) }}</div>
                <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6 font-semibold">{{ $package->duration }}</p>
                @if($package->description)
                    <p class="text-sm sm:text-base text-gray-700 mb-4 sm:mb-6 font-medium italic">{{ $package->description }}</p>
                @endif
                @if($package->features)
                    @php
                        $features = is_array($package->features) ? $package->features : (is_string($package->features) ? json_decode($package->features, true) : []);
                        if (!is_array($features)) {
                            $features = [];
                        }
                    @endphp
                    @if(count($features) > 0)
                        <ul class="space-y-2 sm:space-y-3 mb-6 sm:mb-8">
                            @foreach($features as $feature)
                                <li class="flex items-start text-gray-700">
                                    <i class="fas fa-check text-[#45A247] mr-2 sm:mr-3 mt-1 flex-shrink-0 text-xs sm:text-sm"></i>
                                    <span class="text-xs sm:text-sm leading-relaxed">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
                <a href="{{ route('order-now', ['package_id' => $package->id]) }}"
                   class="block w-full bg-[#45A247] text-white text-center py-2 sm:py-3 rounded-full hover:bg-[#3a8a3c] transition font-semibold shadow-md hover:shadow-lg uppercase tracking-wide text-sm sm:text-base">
                    Select
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:mt-12">
            <a href="{{ route('pricing') }}" class="text-[#45A247] hover:underline font-semibold text-base sm:text-lg">
                View All Packages →
            </a>
        </div>
    </div>
</section>

<!-- SECTION 5: ADDITIONAL SERVICES (ADD-ONS) -->
<section class="py-12 sm:py-16 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-3 sm:mb-4 text-gray-900">À La Carte Add-Ons</h2>
        <p class="text-center text-gray-600 mb-6 sm:mb-8 text-base sm:text-lg italic">The Zilla's Toolkit</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($addons as $addon)
            <div class="bg-white border border-gray-200 rounded-lg sm:rounded-xl p-4 sm:p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#45A247]/10 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                    <i class="fas fa-plus-circle text-[#45A247] text-lg sm:text-xl"></i>
                </div>
                <h3 class="text-base sm:text-lg font-semibold mb-2 text-gray-900">{{ $addon->name }}</h3>
                <div class="text-xl sm:text-2xl font-bold mb-2 sm:mb-3" style="color: #45A247;">${{ number_format($addon->price, 2) }}</div>
                @if($addon->category)
                    <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">{{ $addon->category }}</p>
                @endif
                @if($addon->description)
                    <p class="text-gray-600 text-xs sm:text-sm">{{ $addon->description }}</p>
                @endif
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8 sm:mt-12">
            <a href="{{ route('pricing') }}" class="text-[#45A247] hover:underline font-semibold text-base sm:text-lg">
                View All Add-Ons →
            </a>
        </div>
    </div>
</section>

<!-- SECTION 6: GALLERY -->
@if($galleryImages && $galleryImages->count() > 0)
<section class="py-12 sm:py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center mb-3 sm:mb-4 text-gray-900">Our Work Gallery</h2>
        <p class="text-center text-gray-600 mb-8 sm:mb-12 text-base sm:text-lg px-4">See the results of our professional steam cleaning services</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
            @foreach($galleryImages as $image)
            <div class="relative group overflow-hidden rounded-lg sm:rounded-xl cursor-pointer">
                <img src="{{ Storage::url($image->image_path) }}"
                     alt="{{ $image->title ?: 'Gallery image' }}"
                     class="w-full h-32 sm:h-40 md:h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                <div class="absolute inset-0 bg-[#45A247]/0 group-hover:bg-[#45A247]/20 transition-all duration-300"></div>
                @if($image->title)
                    <div class="absolute bottom-0 left-0 right-0 bg-black/60 text-white p-2 text-xs sm:text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                        {{ $image->title }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- SECTION 7: FEATURED SERVICES / HIGHLIGHTS -->
<section class="relative py-12 sm:py-16 md:py-20 lg:py-24 overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/70 to-gray-900/60 z-10"></div>
        <div class="w-full h-full bg-cover bg-center" style="background-image: url('images/interior.png');"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center">
            <div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 sm:mb-8">Why Choose Steam Zila</h2>
                <ul class="space-y-3 sm:space-y-4">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#45A247] text-xl sm:text-2xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                        <span class="text-white text-base sm:text-lg">Water-saving steam technology</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#45A247] text-xl sm:text-2xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                        <span class="text-white text-base sm:text-lg">Gentle on paint & interior</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#45A247] text-xl sm:text-2xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                        <span class="text-white text-base sm:text-lg">Removes stains & odors</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-[#45A247] text-xl sm:text-2xl mr-3 sm:mr-4 mt-1 flex-shrink-0"></i>
                        <span class="text-white text-base sm:text-lg">Eco-safe for kids & pets</span>
                    </li>
                </ul>
                <a href="{{ route('order-now') }}"
                   class="inline-block mt-6 sm:mt-8 bg-[#45A247] text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-semibold hover:bg-[#3a8a3c] transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Get Started
                </a>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 sm:p-8">
                    <img src="{{ asset('images/exterior.png') }}"
                         alt="Steam Cleaning"
                         class="rounded-xl shadow-2xl w-full">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
</style>
@endsection
