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
            <!-- Operations Section -->
            <div>
                <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Operations</span>
                <ul class="space-y-1.5">
                    <!-- Dashboard (Active Item) -->
                    <li>
                        <a href="/admin/dashboard" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ Request::is('admin/dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav font-semibold shadow-md shadow-admin-navActive/10 hover:scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary font-medium' }} text-sm transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 {{ Request::is('admin/dashboard') ? 'text-admin-textOnActiveNav' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            <!-- Subtle metric badge if needed -->
                            <span class="bg-admin-primary text-admin-textOnPrimary px-2 py-0.5 rounded-full text-[10px] font-bold">Live</span>
                        </a>
                    </li>

                    <!-- Pets -->
                    <li>
                        <a href="/admin/pets" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl {{ Request::is('admin/pets') ? 'bg-admin-navActive text-admin-textOnActiveNav font-semibold shadow-md shadow-admin-navActive/10 hover:scale-[1.02]' : 'text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary font-medium' }} text-sm transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="dog" class="w-4 h-4 {{ Request::is('admin/pets') ? 'text-admin-textOnActiveNav' : 'text-admin-textOnPrimary/50 group-hover:text-admin-navActive' }} transition-colors"></i>
                                <span>Pets Management</span>
                            </div>
                            <!-- Orange metric count badge -->
                            <span class="bg-[#F59E0B] text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-sm shadow-[#F59E0B]/20">42</span>
                        </a>
                    </li>

                    <!-- Rescues (Inactive Item) -->
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="shield-alert" class="w-4 h-4 text-admin-textOnPrimary/50 group-hover:text-admin-navActive transition-colors"></i>
                                <span>Rescue Cases</span>
                            </div>
                            <!-- Red indicator badge -->
                            <span class="bg-admin-btnRed text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-sm shadow-admin-btnRed/20">5</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Manage Section -->
            <div>
                <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Manage</span>
                <ul class="space-y-1.5">
                    <!-- Adoptions -->
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="heart-handshake" class="w-4 h-4 text-admin-textOnPrimary/50 group-hover:text-admin-navActive transition-colors"></i>
                                <span>Adoptions</span>
                            </div>
                            <!-- Orange counter pill '12' -->
                            <span class="bg-[#F59E0B] text-white px-2 py-0.5 rounded-full text-[10px] font-bold shadow-sm shadow-[#F59E0B]/20">12</span>
                        </a>
                    </li>

                    <!-- Volunteers -->
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="users" class="w-4 h-4 text-admin-textOnPrimary/50 group-hover:text-admin-navActive transition-colors"></i>
                                <span>Volunteers</span>
                            </div>
                            <!-- Inactive pill counter -->
                            <span class="bg-admin-primaryPress text-admin-textOnPrimary/60 px-2 py-0.5 rounded-full text-[10px] font-medium">18</span>
                        </a>
                    </li>

                    <!-- Reports -->
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="bar-chart-3" class="w-4 h-4 text-admin-textOnPrimary/50 group-hover:text-admin-navActive transition-colors"></i>
                                <span>Analytics</span>
                            </div>
                        </a>
                    </li>

                    <!-- System Settings -->
                    <li>
                        <a href="#" 
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-admin-textOnPrimary/75 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <i data-lucide="settings" class="w-4 h-4 text-admin-textOnPrimary/50 group-hover:text-admin-navActive transition-colors"></i>
                                <span>Settings</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar Footer/User Profile -->
        <div class="p-4 bg-admin-primaryPress border-t border-admin-primaryPress flex items-center justify-between gap-2">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-lg bg-admin-primary flex items-center justify-center text-sm border border-admin-primary/50 flex-shrink-0">
                    🦁
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-semibold truncate">ResQNet Wildlife</span>
                    <span class="text-[9px] text-admin-textOnPrimary/50 truncate">HQ Operations</span>
                </div>
            </div>
            <button class="p-1.5 hover:bg-admin-primary/50 rounded-lg text-admin-textOnPrimary/60 hover:text-admin-textOnPrimary transition-colors" aria-label="Sign out">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
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

                <!-- Mobile Sidebar Links -->
                <div class="mt-8 flex-1 h-0 overflow-y-auto px-2 space-y-6">
                    <!-- Operations Section -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Operations</span>
                        <ul class="space-y-1">
                             <li>
                                <a href="/admin/dashboard" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl {{ Request::is('admin/dashboard') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary font-medium' }} text-sm transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4 {{ Request::is('admin/dashboard') ? 'text-admin-textOnActiveNav' : 'text-admin-textOnPrimary/50' }}"></i>
                                        <span>Dashboard</span>
                                    </div>
                                    <span class="bg-admin-primary text-admin-textOnPrimary px-2 py-0.5 rounded-full text-[10px] font-bold">Live</span>
                                </a>
                             </li>
                             <li>
                                <a href="/admin/pets" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl {{ Request::is('admin/pets') ? 'bg-admin-navActive text-admin-textOnActiveNav font-bold' : 'text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary font-medium' }} text-sm transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="dog" class="w-4 h-4 {{ Request::is('admin/pets') ? 'text-admin-textOnActiveNav' : 'text-admin-textOnPrimary/50' }}"></i>
                                        <span>Pets Management</span>
                                    </div>
                                    <span class="bg-[#F59E0B] text-white px-2 py-0.5 rounded-full text-[10px] font-bold">42</span>
                                </a>
                             </li>
                             <li>
                                <a href="#" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield-alert" class="w-4 h-4 text-admin-textOnPrimary/50"></i>
                                        <span>Rescue Cases</span>
                                    </div>
                                    <span class="bg-admin-btnRed text-white px-2 py-0.5 rounded-full text-[10px] font-bold">5</span>
                                </a>
                             </li>
                        </ul>
                    </div>

                    <!-- Manage Section -->
                    <div>
                        <span class="px-3 text-[10px] font-bold text-admin-textOnPrimary/40 uppercase tracking-widest block mb-3">Manage</span>
                        <ul class="space-y-1">
                            <li>
                                <a href="#" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="heart-handshake" class="w-4 h-4 text-admin-textOnPrimary/50"></i>
                                        <span>Adoptions</span>
                                    </div>
                                    <span class="bg-[#F59E0B] text-white px-2 py-0.5 rounded-full text-[10px] font-bold">12</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="users" class="w-4 h-4 text-admin-textOnPrimary/50"></i>
                                        <span>Volunteers</span>
                                    </div>
                                    <span class="bg-admin-primaryPress text-admin-textOnPrimary/60 px-2 py-0.5 rounded-full text-[10px] font-medium">18</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-admin-textOnPrimary/50"></i>
                                        <span>Analytics</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" 
                                   class="flex items-center justify-between px-3 py-2 rounded-xl text-admin-textOnPrimary/85 hover:bg-admin-primaryPress hover:text-admin-textOnPrimary text-sm font-medium transition-all duration-200">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="settings" class="w-4 h-4 text-admin-textOnPrimary/50"></i>
                                        <span>Settings</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Mobile User Footer -->
                <div class="p-4 bg-admin-primaryPress border-t border-admin-primaryPress flex items-center justify-between gap-2 mt-auto">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-lg bg-admin-primary flex items-center justify-center text-sm border border-admin-primary/50 flex-shrink-0">
                            🦁
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-semibold truncate">ResQNet Wildlife</span>
                            <span class="text-[9px] text-admin-textOnPrimary/50 truncate">HQ Operations</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
