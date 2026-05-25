<!-- Sidebar Wrapper Component -->
<div>
    <!-- 1. DESKTOP SIDEBAR (Visible on lg screens and up) -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:flex-shrink-0 h-full bg-admin-primary text-admin-textOnPrimary border-r border-admin-primaryPress shadow-xl">
        <!-- Logo Branding Header -->
        <div class="h-16 flex items-center px-6 bg-admin-primaryPress border-b border-admin-primaryPress gap-3">
            <div class="bg-admin-navActive text-admin-textOnActiveNav p-1.5 rounded-lg flex items-center justify-center shadow-md">
                <i data-lucide="paw-print" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="font-bold text-sm tracking-wider uppercase">ResQNet</span>
                <span class="block text-[10px] text-admin-navActive/90 font-medium tracking-widest uppercase">Admin Hub</span>
            </div>
        </div>

        <!-- Scrollable Navigation Area -->
        <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-7">
            <!-- Dynamic Navigation Links -->
            @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                <!-- SUPER ADMIN MENU -->
                <div>
                    <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">System Admin</span>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.organizations.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.organizations.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="building-2" class="w-4 h-4 {{ request()->routeIs('admin.organizations.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Organizations</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Users</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            @elseif(auth()->check() && auth()->user()->hasRole('organization'))
                <!-- ORGANIZATION ADMIN MENU -->
                <div>
                    <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Shelter Management</span>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('organization.dashboard') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('organization.dashboard') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Dashboard</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.pets.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.pets.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="dog" class="w-4 h-4 {{ request()->routeIs('organization.pets.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Pet Directory</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.rescues.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.rescues.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="shield-alert" class="w-4 h-4 {{ request()->routeIs('organization.rescues.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Rescue Cases</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.adoptions.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.adoptions.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="heart-handshake" class="w-4 h-4 {{ request()->routeIs('organization.adoptions.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Adoptions</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.health.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.health.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="activity" class="w-4 h-4 {{ request()->routeIs('organization.health.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Health Records</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.finance.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.finance.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="banknote" class="w-4 h-4 {{ request()->routeIs('organization.finance.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Donations</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3 mt-4">Settings</span>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('organization.staff.index') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.staff.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('organization.staff.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Staff</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('organization.profile.edit') }}" 
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ request()->routeIs('organization.profile.*') ? 'bg-admin-navActive text-admin-textOnActiveNav shadow-md shadow-admin-navActive/10 scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary group' }} font-semibold text-sm transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="settings" class="w-4 h-4 {{ request()->routeIs('organization.profile.*') ? '' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }}"></i>
                                    <span>Org Profile</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>

        <!-- Sidebar Footer/User Profile -->
        <div class="p-4 bg-admin-primaryPress border-t border-admin-primaryPress flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-lg bg-admin-primary flex items-center justify-center text-sm border border-admin-primary/50 flex-shrink-0 text-white font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold truncate">{{ auth()->user()->name }}</span>
                    <span class="text-[9px] text-admin-textOnPrimary/50 truncate">{{ auth()->user()->hasRole('super_admin') ? 'Super Admin' : 'Org Admin' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ auth()->user()->hasRole('super_admin') ? route('admin.logout') : route('organization.logout') }}">
                @csrf
                <button type="submit" class="p-1.5 hover:bg-admin-btnRed rounded-lg text-admin-textOnPrimary/60 hover:text-admin-textOnPrimary transition-colors" aria-label="Sign out">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- 2. MOBILE DRAWER SIDEBAR (Visible only when mobileSidebarOpen is true) -->
    <div x-show="mobileSidebarOpen" 
         class="relative z-50 lg:hidden" 
         style="display: none;"
         x-description="Mobile sidebar, show/hide based on slide-over state." 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop Backdrop Overlay -->
        <div x-show="mobileSidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm"
             @click="mobileSidebarOpen = false"></div>

        <!-- Sidebar Panel Container -->
        <div class="fixed inset-y-0 left-0 flex max-w-xs w-full">
            
            <!-- Sliding Sidebar Panel -->
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-admin-primary text-admin-textOnPrimary pt-5 pb-4">
                
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" 
                            @click="mobileSidebarOpen = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white text-admin-textOnPrimary">
                        <span class="sr-only">Close sidebar</span>
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Mobile Logo Section -->
                <div class="flex-shrink-0 flex items-center px-4 gap-3">
                    <div class="bg-admin-navActive text-admin-textOnActiveNav p-1.5 rounded-lg flex items-center justify-center">
                        <i data-lucide="paw-print" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-bold text-sm tracking-wider uppercase">ResQNet</span>
                        <span class="block text-[10px] text-admin-navActive/90 font-medium tracking-widest uppercase text-left">Admin Hub</span>
                    </div>
                </div>

                <!-- Mobile Sidebar Links (Duplicate of Desktop logic) -->
                <div class="mt-8 flex-1 h-0 overflow-y-auto px-2 space-y-6">
                    @if(auth()->check() && auth()->user()->hasRole('super_admin'))
                        <div>
                            <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">System Admin</span>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.organizations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.organizations.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="building-2" class="w-4 h-4"></i>
                                        <span>Organizations</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                        <span>Users</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @elseif(auth()->check() && auth()->user()->hasRole('organization'))
                        <div>
                            <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Shelter Management</span>
                            <ul class="space-y-1">
                                <li>
                                    <a href="{{ route('organization.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.pets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.pets.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="dog" class="w-4 h-4"></i>
                                        <span>Pet Directory</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.rescues.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.rescues.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="shield-alert" class="w-4 h-4"></i>
                                        <span>Rescue Cases</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.adoptions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.adoptions.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                                        <span>Adoptions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.health.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.health.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="activity" class="w-4 h-4"></i>
                                        <span>Health Records</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.finance.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.finance.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="banknote" class="w-4 h-4"></i>
                                        <span>Donations</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.staff.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.staff.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                        <span>Staff</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('organization.profile.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('organization.profile.*') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold text-sm' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress text-sm font-medium' }}">
                                        <i data-lucide="settings" class="w-4 h-4"></i>
                                        <span>Org Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Mobile User Footer -->
                <div class="p-4 bg-admin-primaryPress border-t border-admin-primaryPress flex items-center justify-between gap-2 mt-auto">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-admin-primary flex items-center justify-center text-sm border border-admin-primary/50 flex-shrink-0 text-white font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-semibold truncate">{{ auth()->user()->name }}</span>
                            <span class="text-[9px] text-admin-textOnPrimary/50 truncate">{{ auth()->user()->hasRole('super_admin') ? 'Super Admin' : 'Org Admin' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
