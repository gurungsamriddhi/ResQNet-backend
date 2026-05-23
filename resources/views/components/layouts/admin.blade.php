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

    <!-- Lucide Icons, Alpine.js, & Turbo Drive CDN for SPA transitions -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Draw Lucide icons instantly on SPA page transitions
        document.addEventListener("turbo:load", function() {
            lucide.createIcons();
        });
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        /* Sleek Premium Dark Mode Theme Variables & Class Overrides */
        body.dark {
            background-color: #0F172A !important; /* deep slate-900 */
            color: #F8FAFC !important; /* slate-50 */
        }
        body.dark .bg-admin-surface {
            background-color: #1E293B !important; /* slate-800 */
        }
        body.dark .bg-admin-bg {
            background-color: #0F172A !important;
        }
        body.dark .border-admin-border {
            border-color: #334155 !important; /* slate-700 */
        }
        body.dark .border-admin-border\/60 {
            border-color: rgba(51, 65, 85, 0.6) !important;
        }
        body.dark .border-admin-border\/40 {
            border-color: rgba(51, 65, 85, 0.4) !important;
        }
        body.dark .border-admin-border\/50 {
            border-color: rgba(51, 65, 85, 0.5) !important;
        }
        body.dark .text-admin-textPrimary {
            color: #F8FAFC !important;
        }
        body.dark .text-admin-textSecondary {
            color: #CBD5E1 !important; /* slate-300 premium off-white */
        }
        body.dark .text-admin-textSecondary\/60 {
            color: rgba(203, 213, 225, 0.6) !important;
        }
        body.dark .text-admin-textSecondary\/80 {
            color: rgba(203, 213, 225, 0.8) !important;
        }
        body.dark input, body.dark select, body.dark textarea {
            background-color: #0F172A !important;
            border-color: #334155 !important;
            color: #F8FAFC !important;
        }
        /* Keep nav indicators / icons contrast high in dark mode */
        body.dark .bg-[#FCC234]\/10 {
            background-color: rgba(252, 194, 52, 0.15) !important;
        }
        body.dark .bg-admin-primary\/10 {
            background-color: rgba(61, 90, 78, 0.25) !important;
        }
        body.dark .bg-admin-btnGreen\/10 {
            background-color: rgba(34, 197, 94, 0.15) !important;
        }
        body.dark .bg-admin-btnRed\/10 {
            background-color: rgba(239, 68, 68, 0.15) !important;
        }
        body.dark .bg-admin-bg-dark {
            background-color: #0b0f19 !important;
        }
        /* Premium Dark Mode Sidebar Overrides */
        body.dark aside, 
        body.dark [role="dialog"] .bg-admin-primary {
            background-color: #0B0F19 !important; /* ultra dark slate */
            border-color: #1E293B !important;
        }
        body.dark aside .bg-admin-primaryPress,
        body.dark [role="dialog"] .bg-admin-primaryPress {
            background-color: #090D16 !important;
            border-color: #1E293B !important;
        }
        body.dark aside nav a:not(.bg-admin-navActive),
        body.dark [role="dialog"] nav a:not(.bg-admin-navActive) {
            color: #CBD5E1 !important; /* slate-300 off-white */
        }
        body.dark aside nav a:not(.bg-admin-navActive):hover,
        body.dark [role="dialog"] nav a:not(.bg-admin-navActive):hover {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
        }
        body.dark aside nav span.text-admin-textOnPrimary\/40,
        body.dark [role="dialog"] nav span.text-admin-textOnPrimary\/40 {
            color: #475569 !important; /* slate-600 */
        }
        body.dark aside nav .bg-admin-primaryPress,
        body.dark [role="dialog"] nav .bg-admin-primaryPress {
            background-color: #1E293B !important;
        }
        body.dark aside .border-admin-primaryPress,
        body.dark [role="dialog"] .border-admin-primaryPress {
            border-color: #1E293B !important;
        }
    </style>
</head>
<body class="h-full bg-admin-bg text-admin-textPrimary antialiased" 
      x-data="{ mobileSidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
    <script>
        // Prevent dark mode visual theme flashes by injecting immediate dark class
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
    </script>

    <!-- Overall Split Screen Shell -->
    <div class="flex h-full min-h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <x-admin.sidebar />

        <!-- Flexible Right-side Content Area with Scroll & Scrolled Header Blur -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto" x-data="{ scrolled: false }" @scroll="scrolled = ($event.target.scrollTop > 10)">
            
            <!-- Sleek Top Header Navbar with Dynamic Scroll Transition padding/blur -->
            <header class="sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 md:px-8 py-5 transition-all duration-300"
                    :class="scrolled ? 'bg-admin-surface/80 backdrop-blur-md shadow-md border-b border-admin-border/80' : 'bg-admin-surface border-b border-admin-border shadow-sm'">
                
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
