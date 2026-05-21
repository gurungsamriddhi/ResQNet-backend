<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ResQNet Admin Hub</title>
    
    <!-- Google Fonts for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS / JS Bundles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons & Alpine.js CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-admin-bg text-admin-textPrimary antialiased" x-data="{ mobileSidebarOpen: false, darkMode: false }">

    <!-- Overall Split Screen Shell -->
    <div class="flex h-full min-h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <x-admin.sidebar />

        <!-- Flexible Right-side Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Sleek Top Header Navbar -->
            <header class="bg-admin-surface border-b border-admin-border h-16 sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 md:px-8 shadow-sm transition-all duration-300">
                
                <!-- Left: Mobile Sidebar Toggle & Search Bar -->
                <div class="flex items-center gap-4 flex-1">
                    <!-- Mobile Hamburger Menu Button -->
                    <button @click="mobileSidebarOpen = true" class="p-2 -ml-2 rounded-lg text-admin-textSecondary hover:bg-admin-bg hover:text-admin-textPrimary lg:hidden focus:outline-none transition-colors" aria-label="Open sidebar">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <!-- Persistent Sleek Search Input -->
                    <div class="relative max-w-md w-full hidden sm:block">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-admin-textSecondary/60">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" placeholder="Search pets, rescue requests, adoption status..." 
                               class="w-full pl-9 pr-4 py-2 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary placeholder-admin-textSecondary/50 focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all duration-200">
                    </div>
                </div>

                <!-- Right: Actions & User Profile -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Search Icon for Mobile (since main bar is hidden) -->
                    <button class="p-2 text-admin-textSecondary hover:bg-admin-bg hover:text-admin-textPrimary rounded-xl transition-colors sm:hidden" aria-label="Search">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>

                    <!-- Dark/Light Theme Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 text-admin-textSecondary hover:bg-admin-bg hover:text-admin-textPrimary rounded-xl transition-all duration-300 relative group" aria-label="Toggle dark mode">
                        <i data-lucide="sun" x-show="!darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-45"></i>
                        <i data-lucide="moon" x-show="darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:-rotate-12" style="display: none;"></i>
                        <span class="absolute top-full right-0 mt-2 hidden group-hover:block bg-admin-textPrimary text-admin-textOnPrimary text-xs rounded py-1 px-2 whitespace-nowrap shadow-md">
                            Toggle Theme
                        </span>
                    </button>

                    <!-- Notification Bell with bg-admin-btnRed Badge -->
                    <button class="p-2 text-admin-textSecondary hover:bg-admin-bg hover:text-admin-textPrimary rounded-xl transition-colors relative group" aria-label="Notifications">
                        <i data-lucide="bell" class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>
                        <!-- Red Badge Dot matching bg-admin-btnRed -->
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-admin-btnRed opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-admin-btnRed"></span>
                        </span>
                        <span class="absolute top-full right-0 mt-2 hidden group-hover:block bg-admin-textPrimary text-admin-textOnPrimary text-xs rounded py-1 px-2 whitespace-nowrap shadow-md">
                            4 notifications
                        </span>
                    </button>

                    <!-- Vertical Separator -->
                    <div class="h-6 w-[1px] bg-admin-border hidden xs:block"></div>

                    <!-- User Profile Quick Info -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-xs font-semibold text-admin-textPrimary">Asmita Karki</span>
                            <span class="text-[10px] text-admin-textSecondary uppercase tracking-wider">Super Admin</span>
                        </div>
                        <div class="relative flex-shrink-0 group cursor-pointer">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-admin-primary to-admin-navActive p-[2px] shadow-sm transition-all duration-300 group-hover:scale-105">
                                <div class="w-full h-full rounded-[10px] bg-admin-surface flex items-center justify-center overflow-hidden">
                                    <!-- A cute placeholder for Asmita 🐾 -->
                                    <span class="text-base">🐾</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </header>

            <!-- Main Content Grid Area -->
            <main class="flex-1 p-4 sm:p-6 md:p-8">
                @if(isset($slot) && $slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <!-- Sleek Footer -->
            <footer class="bg-admin-surface border-t border-admin-border px-6 py-4 text-center text-xs text-admin-textSecondary">
                &copy; 2026 ResQNet Admin Hub. Built for rapid wildlife and pet care coordination.
            </footer>

        </div>
    </div>

    <!-- Lucide Icon Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
