<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - SteamZilla')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col h-screen z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 flex-shrink-0 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#45A247]">SteamZilla</h1>
                    <p class="text-sm text-gray-400 mt-1">Admin Panel</p>
                </div>
                <button id="close-sidebar" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto overflow-x-hidden pb-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-chart-line w-5 mr-3"></i> Dashboard
                </a>
                <a href="{{ route('admin.bookings') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.bookings*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-calendar-check w-5 mr-3"></i> Bookings
                </a>
                <a href="{{ route('admin.payments') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.payments*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-dollar-sign w-5 mr-3"></i> Payments
                </a>
                <a href="{{ route('admin.packages') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.packages*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-box w-5 mr-3"></i> Packages
                </a>
                <a href="{{ route('admin.addons') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.addons*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-plus-circle w-5 mr-3"></i> Add-Ons
                </a>
                <a href="{{ route('admin.vehicle-types') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.vehicle-types*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-car w-5 mr-3"></i> Vehicle Types
                </a>
                <a href="{{ route('admin.time-slots') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.time-slots*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-clock w-5 mr-3"></i> Time Slots
                </a>
                <a href="{{ route('admin.social-links') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.social-links*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-share-alt w-5 mr-3"></i> Social Links
                </a>
                <div class="px-6 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Locations</div>
                <a href="{{ route('admin.countries') }}" class="flex items-center px-6 py-3 pl-12 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.countries*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-globe w-5 mr-3"></i> Countries
                </a>
                <a href="{{ route('admin.cities') }}" class="flex items-center px-6 py-3 pl-12 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.cities*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-city w-5 mr-3"></i> Cities
                </a>
                <a href="{{ route('admin.places') }}" class="flex items-center px-6 py-3 pl-12 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.places*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-map-marker-alt w-5 mr-3"></i> Service Areas
                </a>
                <a href="{{ route('admin.gallery') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.gallery*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-images w-5 mr-3"></i> Gallery
                </a>
                <a href="{{ route('admin.pages.content', 'home') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.pages*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-file-alt w-5 mr-3"></i> Page Content
                </a>
                <a href="{{ route('admin.contact-submissions') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.contact-submissions*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-envelope w-5 mr-3"></i> Contact Queries
                    @php
                        $unreadCount = \App\Models\ContactSubmission::where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white {{ request()->routeIs('admin.settings*') ? 'bg-gray-800 text-white border-r-4 border-[#45A247]' : '' }}">
                    <i class="fas fa-cog w-5 mr-3"></i> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full lg:w-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="open-sidebar" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <a href="{{ route('home') }}" target="_blank" class="text-gray-600 hover:text-[#45A247] text-sm sm:text-base">
                            <i class="fas fa-external-link-alt sm:mr-2"></i> <span class="hidden sm:inline">View Site</span>
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 text-sm sm:text-base">
                                <i class="fas fa-sign-out-alt sm:mr-2"></i> <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
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

    <script>
        // Mobile sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const openSidebarBtn = document.getElementById('open-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        openSidebarBtn?.addEventListener('click', openSidebar);
        closeSidebarBtn?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Close sidebar when clicking a link on mobile
        const sidebarLinks = sidebar?.querySelectorAll('a');
        sidebarLinks?.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>

