<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard - SteamZilla')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6">
                <a href="{{ route('home') }}" class="flex items-center">
                    <h1 class="text-2xl font-bold text-[#45A247]">SteamZilla</h1>
                </a>
                <p class="text-sm text-gray-400 mt-1">User Portal</p>
            </div>
            <nav class="mt-8">
                <a href="{{ route('user.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('user.dashboard') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-home w-5 mr-3"></i> Dashboard
                </a>
                <a href="{{ route('user.bookings') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('user.bookings*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i> My Bookings
                </a>
                <a href="{{ route('user.contact') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('user.contact*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-envelope w-5 mr-3"></i> Contact Us
                </a>
                <a href="{{ route('user.activity') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('user.activity') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-history w-5 mr-3"></i> Activity
                </a>
                <a href="{{ route('user.profile') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('user.profile') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-user w-5 mr-3"></i> Profile
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-600 hover:text-[#45A247]">
                            <i class="fas fa-external-link-alt mr-2"></i> View Site
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

