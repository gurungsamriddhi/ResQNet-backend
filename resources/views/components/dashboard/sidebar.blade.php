<!-- Sidebar Wrapper Component -->
@php
    $isSuperAdmin = auth()->user()?->hasRole('super_admin');
    $petsUrl = $isSuperAdmin ? '/admin/pets' : '/organization/pets';
    $dashboardUrl = $isSuperAdmin ? '/admin/dashboard' : '/organization/dashboard';
    $rescueUrl = route('organization.rescues.index');
    $healthUrl = $isSuperAdmin ? '/admin/health' : '/organization/health';
    $isPetsActive = request()->is('admin/pets') || request()->is('organization/pets') || request()->routeIs('organization.pets.*');
    $isDashboardActive = request()->is('admin/dashboard') || request()->is('organization/dashboard');
    $isRescueActive = request()->is('admin/rescue') || request()->is('organization/rescues') || request()->routeIs('organization.rescues.*');
    $isHealthActive = request()->is('admin/health') || request()->is('organization/health') || request()->routeIs('organization.health.*');
    $settingsUrl = $isSuperAdmin ? '/admin/settings' : '/organization/settings';
    $isSettingsActive = request()->is('admin/settings') || request()->is('organization/settings') || request()->routeIs('organization.settings.*');
    $usersUrl = $isSuperAdmin ? '/admin/users' : '/organization/users';
    $isUsersActive = request()->is('admin/users') || request()->is('organization/users') || request()->routeIs('organization.users.*');
    $organizationsUrl = '/admin/organizations';
    $isOrganizationsActive = request()->is('admin/organizations') || request()->routeIs('admin.organizations.*');
    $adoptionsUrl = $isSuperAdmin ? '/admin/adoptions' : '/organization/adoptions';
    $isAdoptionsActive = request()->is('admin/adoptions') || request()->is('organization/adoptions') || request()->routeIs('organization.adoptions.*');
    $volunteersUrl = $isSuperAdmin ? '/admin/volunteers' : '/organization/volunteers';
    $isVolunteersActive = request()->is('admin/volunteers') || request()->is('organization/volunteers') || request()->routeIs('organization.volunteers.*');
    $analyticsUrl = $isSuperAdmin ? '/admin/analytics' : '/organization/analytics';
    $isAnalyticsActive = request()->is('admin/analytics') || request()->is('organization/analytics') || request()->routeIs('organization.analytics.*');
@endphp
<div>
    <!-- 1. DESKTOP SIDEBAR (Visible on lg screens and up) -->
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:flex-shrink-0 h-full bg-brand-navy text-white border-r border-slate-800/80 shadow-2xl">
        <!-- Logo Branding Header -->
        <div class="h-16 flex items-center px-6 bg-slate-950/30 border-b border-slate-800/60 gap-3">
            <div class="bg-brand-orange/20 text-brand-orange p-1.5 rounded-lg flex items-center justify-center shadow-md">
                <i data-lucide="paw-print" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="font-bold text-sm tracking-wider uppercase text-slate-100">ResQNet</span>
                <span class="block text-[9px] text-brand-orange font-bold tracking-widest uppercase">Admin Hub</span>
            </div>
        </div>

        <!-- Scrollable Navigation Area -->
        <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-7">
            <!-- Operations Section -->
            <div>
                <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-3">Operations</span>
                <ul class="space-y-1.5">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ $dashboardUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isDashboardActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 {{ $isDashboardActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            <span class="bg-secondary-50 text-secondary-500 px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase">Live</span>
                        </a>
                    </li>

                    <!-- Pets -->
                    <li>
                        <a href="{{ $petsUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isPetsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="dog" class="w-4 h-4 {{ $isPetsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Pet Directory</span>
                            </div>
                            <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded-full text-[10px] font-bold">42</span>
                        </a>
                    </li>

                    <!-- Health Records -->
                    <li>
                        <a href="{{ $healthUrl }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isHealthActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="heart-pulse" class="w-4 h-4 {{ $isHealthActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Health Records</span>
                            </div>
                            <span class="bg-white/5 text-slate-400 px-2 py-0.5 rounded-full text-[10px] font-medium">8</span>
                        </a>
                    </li>

                    <!-- Rescues -->
                    <li>
                        <a href="{{ $rescueUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isRescueActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="shield-alert" class="w-4 h-4 {{ $isRescueActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Rescue Cases</span>
                            </div>
                            <span class="bg-brand-orange/20 text-brand-orange px-2 py-0.5 rounded-full text-[10px] font-bold">5</span>
                        </a>
                    </li>

                    <!-- Users -->
                    <li>
                        <a href="{{ $usersUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isUsersActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="users" class="w-4 h-4 {{ $isUsersActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Users</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Manage Section -->
            <div>
                <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-3">Manage</span>
                <ul class="space-y-1.5">
                    <!-- Adoptions -->
                    <li>
                        <a href="{{ $adoptionsUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isAdoptionsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="heart-handshake" class="w-4 h-4 {{ $isAdoptionsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Adoptions</span>
                            </div>
                            <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded-full text-[10px] font-bold">12</span>
                        </a>
                    </li>

                    <!-- Volunteers -->
                    <li>
                        <a href="{{ $volunteersUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isVolunteersActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="users" class="w-4 h-4 {{ $isVolunteersActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Volunteers</span>
                            </div>
                            <span class="bg-white/5 text-slate-400 px-2 py-0.5 rounded-full text-[10px] font-medium">18</span>
                        </a>
                    </li>

                    <!-- Partner Orgs -->
                    <li>
                        <a href="{{ $organizationsUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isOrganizationsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="building-2" class="w-4 h-4 {{ $isOrganizationsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Partner Orgs</span>
                            </div>
                        </a>
                    </li>

                    <!-- Analytics -->
                    <li>
                        <a href="{{ $analyticsUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isAnalyticsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="bar-chart-3" class="w-4 h-4 {{ $isAnalyticsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Analytics</span>
                            </div>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a href="{{ $settingsUrl }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isSettingsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2 shadow-sm' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="settings" class="w-4 h-4 {{ $isSettingsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors"></i>
                                <span>Settings</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar Footer/User Profile -->
        <div class="p-4 bg-slate-950/30 border-t border-slate-800/60 flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-sm font-bold text-brand-orange flex-shrink-0">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold truncate text-slate-200">{{ auth()->user()->name ?? 'User' }}</span>
                    <span class="text-[9px] text-slate-500 truncate uppercase tracking-wider font-bold">{{ $isSuperAdmin ? 'Super Admin' : 'Org Admin' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ $isSuperAdmin ? route('admin.logout') : route('organization.logout') }}">
                @csrf
                <button type="submit" class="p-1.5 hover:bg-brand-orange/20 hover:text-brand-orange rounded-lg text-slate-400 transition-colors" aria-label="Sign out">
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
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-brand-navy text-white pt-5 pb-4">
                
                <!-- Close Button -->
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button" 
                            @click="mobileSidebarOpen = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white text-white">
                        <span class="sr-only">Close sidebar</span>
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Mobile Logo Section -->
                <div class="flex-shrink-0 flex items-center px-4 gap-3">
                    <div class="bg-brand-orange/20 text-brand-orange p-1.5 rounded-lg flex items-center justify-center shadow-md">
                        <i data-lucide="paw-print" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-bold text-sm tracking-wider uppercase text-slate-100">ResQNet</span>
                        <span class="block text-[9px] text-brand-orange font-bold tracking-widest uppercase text-left">Admin Hub</span>
                    </div>
                </div>

                <!-- Mobile Sidebar Links -->
                <div class="mt-8 flex-1 h-0 overflow-y-auto px-2 space-y-6">
                    <!-- Operations Section -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-3">Operations</span>
                        <ul class="space-y-1">
                             <li>
                                <a href="{{ $dashboardUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isDashboardActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 {{ $isDashboardActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Dashboard</span>
                                    </div>
                                    <span class="bg-secondary-50 text-secondary-500 px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase">Live</span>
                                </a>
                             </li>
                             <li>
                                <a href="{{ $petsUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isPetsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="dog" class="w-4 h-4 {{ $isPetsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Pet Directory</span>
                                    </div>
                                    <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded-full text-[10px] font-bold">42</span>
                                </a>
                             </li>
                             <li>
                                <a href="{{ $healthUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isHealthActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="heart-pulse" class="w-4 h-4 {{ $isHealthActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Health Records</span>
                                    </div>
                                    <span class="bg-white/5 text-slate-400 px-2 py-0.5 rounded-full text-[10px] font-medium">8</span>
                                </a>
                             </li>
                             <li>
                                 <a href="{{ $rescueUrl }}" 
                                    class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isRescueActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                                     <div class="flex items-center gap-3">
                                         <i data-lucide="shield-alert" class="w-4 h-4 {{ $isRescueActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                         <span>Rescue Cases</span>
                                     </div>
                                     <span class="bg-brand-orange/20 text-brand-orange px-2 py-0.5 rounded-full text-[10px] font-bold">5</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="{{ $usersUrl }}" 
                                    class="flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-200 group text-[13px] {{ $isUsersActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }}">
                                     <div class="flex items-center gap-3">
                                         <i data-lucide="users" class="w-4 h-4 {{ $isUsersActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                         <span>Users</span>
                                     </div>
                                 </a>
                              </li>
                         </ul>
                    </div>

                    <!-- Manage Section -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest block mb-3">Manage</span>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ $adoptionsUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] {{ $isAdoptionsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }} transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="heart-handshake" class="w-4 h-4 {{ $isAdoptionsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Adoptions</span>
                                    </div>
                                    <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded-full text-[10px] font-bold">12</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $volunteersUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] {{ $isVolunteersActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }} transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="users" class="w-4 h-4 {{ $isVolunteersActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Volunteers</span>
                                    </div>
                                    <span class="bg-white/5 text-slate-400 px-2 py-0.5 rounded-full text-[10px] font-medium">18</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $organizationsUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] {{ $isOrganizationsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }} transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="building-2" class="w-4 h-4 {{ $isOrganizationsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Partner Orgs</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $analyticsUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] {{ $isAnalyticsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }} transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4 {{ $isAnalyticsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Analytics</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $settingsUrl }}" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-[13px] {{ $isSettingsActive ? 'bg-white/10 text-white font-semibold border-l-4 border-brand-orange rounded-l-none pl-2' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium' }} transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="settings" class="w-4 h-4 {{ $isSettingsActive ? 'text-brand-orange' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                                        <span>Settings</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Mobile User Footer -->
                <div class="p-4 bg-slate-950/30 border-t border-slate-800/60 flex items-center justify-between gap-2 mt-auto">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-sm font-bold text-brand-orange flex-shrink-0">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-semibold truncate text-slate-200">{{ auth()->user()->name ?? 'User' }}</span>
                            <span class="text-[9px] text-slate-500 truncate uppercase tracking-wider font-bold">{{ $isSuperAdmin ? 'Super Admin' : 'Org Admin' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
