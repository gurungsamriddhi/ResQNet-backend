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
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js'])

    <!-- Alpine.js & Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        /* Sleek Premium Dark Mode Theme Variables & Class Overrides */
        .dark body, body.dark {
            background-color: #0F172A !important; /* deep slate-900 */
            color: #F8FAFC !important; /* slate-50 */
        }
        .dark .bg-admin-surface, body.dark .bg-admin-surface {
            background-color: #1E293B !important; /* slate-800 */
        }
        .dark .bg-admin-bg, body.dark .bg-admin-bg {
            background-color: #0F172A !important;
        }
        .dark .border-admin-border, body.dark .border-admin-border {
            border-color: #334155 !important; /* slate-700 */
        }
        .dark .border-admin-border\/60, body.dark .border-admin-border\/60 {
            border-color: rgba(51, 65, 85, 0.6) !important;
        }
        .dark .border-admin-border\/40, body.dark .border-admin-border\/40 {
            border-color: rgba(51, 65, 85, 0.4) !important;
        }
        .dark .border-admin-border\/50, body.dark .border-admin-border\/50 {
            border-color: rgba(51, 65, 85, 0.5) !important;
        }
        .dark .text-admin-textPrimary, body.dark .text-admin-textPrimary {
            color: #F8FAFC !important;
        }
        .dark .text-admin-textSecondary, body.dark .text-admin-textSecondary {
            color: #CBD5E1 !important; /* slate-300 premium off-white */
        }
        .dark .text-admin-textSecondary\/60, body.dark .text-admin-textSecondary\/60 {
            color: rgba(203, 213, 225, 0.6) !important;
        }
        .dark .text-admin-textSecondary\/80, body.dark .text-admin-textSecondary\/80 {
            color: rgba(203, 213, 225, 0.8) !important;
        }
        .dark input, .dark select, .dark textarea,
        body.dark input, body.dark select, body.dark textarea {
            background-color: #0F172A !important;
            border-color: #334155 !important;
            color: #F8FAFC !important;
        }
        /* Overrides for brand cream backgrounds to slate-800 in dark mode */
        .dark .bg-brand-cream, body.dark .bg-brand-cream {
            background-color: #1E293B !important;
            border-color: #334155 !important;
        }
        /* Keep nav indicators / icons contrast high in dark mode */
        .dark .bg-[#FCC234]\/10, body.dark .bg-[#FCC234]\/10 {
            background-color: rgba(252, 194, 52, 0.15) !important;
        }
        .dark .bg-admin-primary\/10, body.dark .bg-admin-primary\/10 {
            background-color: rgba(61, 90, 78, 0.25) !important;
        }
        .dark .bg-admin-btnGreen\/10, body.dark .bg-admin-btnGreen\/10 {
            background-color: rgba(34, 197, 94, 0.15) !important;
        }
        .dark .bg-admin-btnRed\/10, body.dark .bg-admin-btnRed\/10 {
            background-color: rgba(239, 68, 68, 0.15) !important;
        }
        .dark .bg-admin-bg-dark, body.dark .bg-admin-bg-dark {
            background-color: #0b0f19 !important;
        }
        /* Premium Dark Mode Sidebar Overrides */
        .dark aside, body.dark aside, 
        .dark [role="dialog"] .bg-admin-primary, body.dark [role="dialog"] .bg-admin-primary {
            background-color: #0B0F19 !important; /* ultra dark slate */
            border-color: #1E293B !important;
        }
        .dark aside .bg-admin-primaryPress, body.dark aside .bg-admin-primaryPress,
        .dark [role="dialog"] .bg-admin-primaryPress, body.dark [role="dialog"] .bg-admin-primaryPress {
            background-color: #090D16 !important;
            border-color: #1E293B !important;
        }
        .dark aside nav a:not(.bg-admin-navActive), body.dark aside nav a:not(.bg-admin-navActive),
        .dark [role="dialog"] nav a:not(.bg-admin-navActive), body.dark [role="dialog"] nav a:not(.bg-admin-navActive) {
            color: #CBD5E1 !important; /* slate-300 off-white */
        }
        .dark aside nav a:not(.bg-admin-navActive):hover, body.dark aside nav a:not(.bg-admin-navActive):hover,
        .dark [role="dialog"] nav a:not(.bg-admin-navActive):hover, body.dark [role="dialog"] nav a:not(.bg-admin-navActive):hover {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
        }
        .dark aside nav span.text-admin-textOnPrimary\/40, body.dark aside nav span.text-admin-textOnPrimary\/40,
        .dark [role="dialog"] nav span.text-admin-textOnPrimary\/40, body.dark [role="dialog"] nav span.text-admin-textOnPrimary\/40 {
            color: #475569 !important; /* slate-600 */
        }
        .dark aside nav .bg-admin-primaryPress, body.dark aside nav .bg-admin-primaryPress,
        .dark [role="dialog"] nav .bg-admin-primaryPress, body.dark [role="dialog"] nav .bg-admin-primaryPress {
            background-color: #1E293B !important;
        }
        .dark aside .border-admin-primaryPress, body.dark aside .border-admin-primaryPress,
        .dark [role="dialog"] .border-admin-primaryPress, body.dark [role="dialog"] .border-admin-primaryPress {
            border-color: #1E293B !important;
        }
    </style>
</head>
<body class="h-full bg-slate-50 dark:bg-[#111c2a] text-slate-900 dark:text-slate-100 antialiased transition-colors duration-150" 
      x-data="{ mobileSidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            if (val) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
            }
        });
        // Sync root classes immediately on Alpine load
        if (darkMode) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark');
        }
      "
      :class="{ 'dark': darkMode }">
    <script>
        // Prevent dark mode visual theme flashes by injecting immediate dark class
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        }
    </script>

    <!-- Overall Split Screen Shell -->
    <div class="flex h-full min-h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        @if(auth()->user()?->hasRole('super_admin'))
            <x-admin.sidebar />
        @else
            <x-organization.sidebar />
        @endif

        <!-- Flexible Right-side Content Area with Scroll & Scrolled Header Blur -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto" x-data="{ scrolled: false }" @scroll="scrolled = ($event.target.scrollTop > 10)">
            
            <!-- Sleek Top Header Navbar with Dynamic Scroll Transition padding/blur -->
            <header class="sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 md:px-8 py-5 transition-all duration-300"
                    :class="scrolled ? 'bg-white/80 dark:bg-[#1a2333] backdrop-blur-md shadow-md border-b border-slate-200 dark:border-slate-800/80' : 'bg-white dark:bg-[#1a2333] border-b border-slate-200 dark:border-slate-700/40 shadow-sm'">
                
                <!-- Left: Mobile Sidebar Toggle & Search Bar -->
                <div class="flex items-center gap-4 flex-1">
                    <!-- Mobile Hamburger Menu Button -->
                    <button @click="mobileSidebarOpen = true" class="p-2 -ml-2 rounded-lg text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100 lg:hidden focus:outline-none transition-colors" aria-label="Open sidebar">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <!-- Persistent Sleek Search Input -->
                    <div class="relative max-w-md w-full hidden sm:block">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500/60 dark:text-slate-300/60">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                         <input type="text" placeholder="Search pets, rescue requests, adoption status..." 
                                class="w-full pl-9 pr-4 py-2 bg-white dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800/60 rounded-xl text-sm text-slate-900 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-600/20 dark:focus:ring-emerald-500/20 focus:border-emerald-600 dark:focus:border-emerald-500 transition-all duration-200">
                    </div>
                </div>

                <!-- Right: Actions & User Profile -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Search Icon for Mobile (since main bar is hidden) -->
                    <button class="p-2 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100 rounded-xl transition-colors sm:hidden" aria-label="Search">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>

                    <!-- Dark/Light Theme Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100 rounded-xl transition-all duration-300 relative group" aria-label="Toggle dark mode">
                        <i data-lucide="sun" x-show="!darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-45"></i>
                        <i data-lucide="moon" x-show="darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:-rotate-12" style="display: none;"></i>
                        <span class="absolute top-full right-0 mt-2 hidden group-hover:block bg-slate-900 dark:bg-slate-100 text-white text-xs rounded py-1 px-2 whitespace-nowrap shadow-md">
                            Toggle Theme
                        </span>
                    </button>

                    <!-- Notification Bell with Alpine.js -->
                    <div x-data="notificationManager()" @click.away="open = false" class="relative">
                        <button @click="toggle()" class="p-2 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100 rounded-xl transition-colors relative group" aria-label="Notifications">
                            <i data-lucide="bell" class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>
                            
                            <!-- Red Badge Dot -->
                            <span x-show="unreadCount > 0" class="absolute top-1 right-1 flex h-3 w-3" style="display: none;">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-600 dark:bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600 dark:bg-red-500 border-2 border-white dark:border-[#1a2333]"></span>
                            </span>
                            
                            <!-- Unread Count Tooltip -->
                            <span class="absolute top-full right-0 mt-2 hidden group-hover:block bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 text-xs rounded py-1 px-2 whitespace-nowrap shadow-md z-50">
                                <span x-text="unreadCount"></span> unread notifications
                            </span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-xl overflow-hidden z-50"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700/40 flex items-center justify-between bg-slate-50 dark:bg-[#0b0f19]">
                                <h3 class="font-bold text-sm text-slate-900 dark:text-slate-100">Notifications</h3>
                                <button x-show="notifications.length > 0" @click="markAllAsRead()" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300">
                                    Mark all as read
                                </button>
                            </div>
                            
                            <div class="max-h-[28rem] overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-6 text-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400">
                                            <i data-lucide="bell-off" class="w-5 h-5"></i>
                                        </div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">You're all caught up!</p>
                                    </div>
                                </template>
                                
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group relative flex gap-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            <template x-if="notification.type === 'rescue'">
                                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center">
                                                    <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                                </div>
                                            </template>
                                            <template x-if="notification.type === 'adoption'">
                                                <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                                    <i data-lucide="paw-print" class="w-4 h-4"></i>
                                                </div>
                                            </template>
                                            <template x-if="notification.type !== 'rescue' && notification.type !== 'adoption'">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                                    <i data-lucide="info" class="w-4 h-4"></i>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <a :href="notification.action_url" @click="markAsRead(notification.id)" class="block outline-none">
                                                <p class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate" x-text="notification.title"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-relaxed" x-text="notification.message"></p>
                                                <p class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 mt-1.5 uppercase tracking-wider" x-text="notification.time"></p>
                                            </a>
                                        </div>
                                        
                                        <!-- Mark Read Button -->
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity absolute right-4 top-4">
                                            <button @click.prevent="markAsRead(notification.id)" class="p-1 rounded-md text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 transition-colors tooltip" title="Mark as read">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Vertical Separator -->
                    <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-700 hidden xs:block"></div>

                    <!-- User Profile Quick Info -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col text-right">
                            <span class="text-xs font-semibold text-slate-900 dark:text-slate-100">{{ auth()->user()?->name ?? 'Dev User' }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                                {{ auth()->user()?->hasRole('super_admin') ? 'Super Admin' : (auth()->user()?->hasRole('organization') ? 'Org Admin' : 'Guest') }}
                            </span>
                        </div>
                        @php $org = auth()->user()?->organization; @endphp
                        <div class="relative flex-shrink-0 group cursor-pointer" x-data="{ userMenuOpen: false }" @click.away="userMenuOpen = false">
                            <div @click="userMenuOpen = !userMenuOpen" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 dark:from-emerald-500 to-blue-500 p-[2px] shadow-sm transition-all duration-300 group-hover:scale-105">
                                <div class="w-full h-full rounded-[10px] bg-white dark:bg-[#1a2333] flex items-center justify-center overflow-hidden relative">
                                    @if($org && $org->logo)
                                        <img src="{{ Storage::url($org->logo) }}" alt="Org Logo" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-base font-bold text-emerald-600 dark:text-emerald-400">{{ substr(auth()->user()?->name ?? 'Dev User', 0, 1) }}</span>
                                    @endif
                                    @if($org)
                                    <div @click.stop="document.getElementById('nav-logo-upload').click()" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-[10px] cursor-pointer">
                                        <i data-lucide="camera" class="w-4 h-4 text-white"></i>
                                    </div>
                                    <form method="POST" action="{{ route('organization.settings.update') }}" enctype="multipart/form-data" class="hidden">
                                        @csrf
                                        @method('PATCH')
                                        <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp" id="nav-logo-upload" @change="this.form.submit()">
                                        <input type="hidden" name="name" value="{{ $org->name }}">
                                    </form>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-xl shadow-lg py-1 z-50"
                                 style="display: none;">
                                <form method="POST" action="{{ auth()->user()?->hasRole('super_admin') ? route('admin.logout') : route('organization.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 flex items-center gap-2 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </header>

            <!-- Main Content Grid Area -->
            <main class="flex-1 p-4 sm:p-6 md:p-8">
                @if(isset($header) && $header->isNotEmpty())
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endif
                @if(isset($slot) && $slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <!-- Sleek Footer -->
            <footer class="bg-white dark:bg-[#1a2333] border-t border-slate-200 dark:border-slate-700/40 px-6 py-4 text-center text-xs text-slate-500 dark:text-slate-300">
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

    <!-- Global Donation Toast Container (body-level, above all scrollable content) -->
    <div id="donation-toast-container"
         class="fixed top-20 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"
         x-data="donationToast()"
         @donation-toast.window="addToast($event.detail)">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                 class="pointer-events-auto max-w-sm w-full bg-white dark:bg-[#111c2a] border border-emerald-200 dark:border-emerald-500/30 rounded-2xl shadow-2xl shadow-emerald-500/10 p-4 flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-slate-100">New Donation Received</p>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                        <span class="font-semibold" x-text="toast.donor"></span> donated
                        <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="'NPR ' + toast.amount"></span>
                    </p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1" x-text="toast.time"></p>
                </div>
                <button @click="removeToast(toast.id)"
                        class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Donation Toast Alpine Component + Global Helper -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('donationToast', () => ({
                toasts: [],
                counter: 0,

                addToast(detail) {
                    var id = ++this.counter;
                    this.toasts.push({
                        id: id,
                        donor: detail.donor || 'Anonymous',
                        amount: detail.amount || '0',
                        time: detail.time || 'just now',
                        visible: true,
                    });

                    if (this.toasts.length > 3) {
                        this.toasts.shift();
                    }

                    var self = this;
                    setTimeout(function () { self.removeToast(id); }, 6000);
                },

                removeToast(id) {
                    var idx = this.toasts.findIndex(function (t) { return t.id === id; });
                    if (idx !== -1) {
                        this.toasts[idx].visible = false;
                        var self = this;
                        setTimeout(function () {
                            self.toasts = self.toasts.filter(function (t) { return t.id !== id; });
                        }, 350);
                    }
                },
            }));
        });

        window.showDonationToast = function (donor, amount, time) {
            window.dispatchEvent(new CustomEvent('donation-toast', {
                detail: {
                    donor: donor || 'Anonymous',
                    amount: amount || '0',
                    time: time || 'just now',
                }
            }));
        };
    </script>

    <!-- Notification Manager Alpine Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationManager', () => ({
                open: false,
                notifications: [],
                unreadCount: 0,
                
                init() {
                    this.fetchNotifications();
                    
                    // Listen to window events from Echo/Pusher (broadcasted from dashboard.js)
                    window.addEventListener('new-rescue-report', (e) => {
                        this.addNotification({
                            id: Date.now(), // temporary until fetch
                            type: 'rescue',
                            title: 'New Rescue Report',
                            message: `New rescue report submitted: ${e.detail.title}`,
                            action_url: `/organization/rescues/${e.detail.rescue_id}`,
                            time: 'Just now'
                        });
                        if(window.lucide) {
                            setTimeout(() => lucide.createIcons(), 50);
                        }
                    });
                    
                    window.addEventListener('new-adoption-request', (e) => {
                        this.addNotification({
                            id: Date.now(),
                            type: 'adoption',
                            title: 'New Adoption Request',
                            message: `${e.detail.user_name} applied to adopt ${e.detail.pet_name}.`,
                            action_url: `/organization/adoptions`,
                            time: 'Just now'
                        });
                        if(window.lucide) {
                            setTimeout(() => lucide.createIcons(), 50);
                        }
                    });
                    
                    window.addEventListener('pet-suspended', (e) => {
                        this.addNotification({
                            id: Date.now(),
                            type: 'alert',
                            title: 'Pet Profile Suspended',
                            message: e.detail.message,
                            action_url: `/organization/pets/${e.detail.pet_id}`,
                            time: 'Just now'
                        });
                        if(window.lucide) {
                            setTimeout(() => lucide.createIcons(), 50);
                        }
                    });
                },
                
                toggle() {
                    this.open = !this.open;
                },
                
                addNotification(notification) {
                    this.notifications.unshift(notification);
                    this.unreadCount++;
                    if (this.notifications.length > 20) {
                        this.notifications.pop();
                    }
                },
                
                async fetchNotifications() {
                    try {
                        const response = await fetch('/organization/notifications', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.notifications = await response.json();
                            this.unreadCount = this.notifications.length;
                            if(window.lucide) {
                                setTimeout(() => lucide.createIcons(), 100);
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching notifications', error);
                    }
                },
                
                async markAsRead(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                    
                    try {
                        await fetch(`/organization/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                    } catch (error) {
                        console.error('Error marking notification as read', error);
                    }
                },
                
                async markAllAsRead() {
                    this.notifications = [];
                    this.unreadCount = 0;
                    
                    try {
                        await fetch(`/organization/notifications/read-all`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                    } catch (error) {
                        console.error('Error marking all as read', error);
                    }
                }
            }));
        });
    </script>
</body>
</html>
