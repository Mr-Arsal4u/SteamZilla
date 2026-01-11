<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $currentUrl = url()->current();
        
        // Default values
        $seoTitle = 'TheSteamZilla - Professional Mobile Car Steam Detailing & Cleaning';
        $seoDescription = 'TheSteamZilla offers eco-friendly mobile car steam detailing services. Professional interior, exterior, and engine bay steam cleaning that eliminates 99.9% of germs and bacteria.';
        $seoKeywords = 'steam car wash, mobile car detailing, steam cleaning, car interior cleaning, engine bay cleaning, eco-friendly car wash, TheSteamZilla';
        $seoOgImage = null;

        try {
            if (class_exists(\App\Models\Setting::class)) {
                $seoTitle = \App\Models\Setting::get('seo_meta_title', $seoTitle);
                $seoDescription = \App\Models\Setting::get('seo_meta_description', $seoDescription);
                $seoKeywords = \App\Models\Setting::get('seo_meta_keywords', $seoKeywords);
                $seoOgImage = \App\Models\Setting::get('seo_og_image');
            }
        } catch (\Throwable $e) {
            // If settings table/model fails, we silently fall back to defaults
            // Report error to log if needed: \Log::warning('SEO Settings load failed: ' . $e->getMessage());
        }
    @endphp
    <title>@yield('title', $seoTitle)</title>
    <meta name="description" content="@yield('description', $seoDescription)">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <link rel="canonical" href="{{ $currentUrl }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $currentUrl }}" />
    <meta property="og:title" content="@yield('title', $seoTitle)" />
    <meta property="og:description" content="@yield('description', $seoDescription)" />
    @if($seoOgImage)
        <meta property="og:image" content="{{ asset('storage/' . $seoOgImage) }}">
    @endif

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ $currentUrl }}" />
    <meta name="twitter:title" content="@yield('title', $seoTitle)" />
    <meta name="twitter:description" content="@yield('description', $seoDescription)" />
    @if($seoOgImage)
        <meta name="twitter:image" content="{{ asset('storage/' . $seoOgImage) }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white text-gray-900 antialiased">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'SteamZilla');
        $siteLogo = \App\Models\Setting::get('site_logo');
    @endphp
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-16 sm:h-20 lg:h-28 w-auto mr-2 sm:mr-4">
                        @endif
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold" style="color: #45A247;">{{ $siteName }}</span>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-4 lg:space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 transition hover:[color:#45A247]">Home</a>
                    <a href="{{ route('about') }}" class="text-gray-700 transition hover:[color:#45A247]">About</a>
                    <a href="{{ route('pricing') }}" class="text-gray-700 transition hover:[color:#45A247]">Pricing</a>
                    {{-- <a href="{{ route('gift-cards') }}" class="text-gray-700 transition hover:[color:#45A247]">Gift Cards</a> --}}
                    <a href="{{ route('contact') }}" class="text-gray-700 transition hover:[color:#45A247]">Contact Us</a>
                    <a href="{{ route('order-now') }}" class="text-white px-4 lg:px-6 py-2 rounded-full hover:opacity-90 transition text-sm lg:text-base" style="background-color: #45A247;">Order Now</a>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 transition hover:[color:#45A247] text-sm lg:text-base">Admin</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="text-gray-700 transition hover:[color:#45A247] text-sm lg:text-base">My Account</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 transition hover:[color:#45A247] text-sm lg:text-base">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('user.login') }}" class="text-gray-700 transition hover:[color:#45A247] text-sm lg:text-base">Login</a>
                        <a href="{{ route('user.register') }}" class="text-white px-4 py-2 rounded-full hover:opacity-90 transition text-sm lg:text-base" style="background-color: #45A247;">Sign Up</a>
                    @endauth
                </div>
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl sm:text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Home</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">About</a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Pricing</a>
                {{-- <a href="{{ route('gift-cards') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Gift Cards</a> --}}
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Contact Us</a>
                <a href="{{ route('order-now') }}" class="block px-3 py-2 bg-green-600 text-white rounded">Order Now</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Admin</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">My Account</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="px-3 py-2">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:bg-green-50 rounded w-full text-left">Logout</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="block px-3 py-2 text-gray-700 hover:bg-green-50 rounded">Login</a>
                    <a href="{{ route('user.register') }}" class="block px-3 py-2 bg-green-600 text-white rounded">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#111] text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @php
                $siteDescription = \App\Models\Setting::get('site_description', 'Mobile Steam Detailing - The Power of Clean, Unleashed.');
                $contactEmail = \App\Models\Setting::get('contact_email', 'mrzilla89@thesteamzilla.com');
                $contactPhone = \App\Models\Setting::get('contact_phone', '(413) 352-9444');
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4" style="color: #45A247;">{{ $siteName }}</h3>
                    <p class="text-sm sm:text-base text-gray-400">{{ $siteDescription }}</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="{{ route('order-now') }}" class="hover:text-white transition">Order Now</a></li>
                        <li><a href="{{ route('admin.login') }}" class="hover:text-white transition">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        @if($contactEmail)
                            <li><i class="fas fa-envelope mr-2"></i> {{ $contactEmail }}</li>
                        @endif
                        @if($contactPhone)
                            <li><i class="fas fa-phone mr-2"></i> {{ $contactPhone }}</li>
                        @endif
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Mobile Service Area</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">Follow Us</h4>
                    <div class="flex space-x-4">
                        @php
                            $socialLinks = \App\Models\SocialLink::where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('platform')
                                ->get();
                        @endphp
                        @forelse($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" 
                               class="text-gray-400 hover:text-white transition" 
                               title="{{ $socialLink->platform }}">
                                <i class="{{ $socialLink->icon }} text-2xl"></i>
                            </a>
                        @empty
                            <p class="text-gray-400 text-sm">No social links available</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 md:space-x-6 mb-4">
                    <a href="{{ route('terms-and-conditions') }}" class="text-gray-400 hover:text-white transition text-sm">
                        Terms and Conditions
                    </a>
                    <span class="text-gray-600 hidden md:inline">|</span>
                    <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition text-sm">
                        Privacy Policy
                    </a>
                </div>
                <div class="text-center text-gray-400">
                    <p class="font-semibold mb-2">STEAMZILLA | MOBILE STEAM DETALLING | THE POWER OF CLEAN, UNLEASHED.</p>
                    <p>&copy; {{ date('Y') }} SteamZilla. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
