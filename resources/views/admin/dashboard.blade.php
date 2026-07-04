<x-layouts.admin>
    <!-- Welcome Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-slate-900 dark:text-slate-100 font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                Welcome back, Asmita 🐾
            </h1>
            <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">
                Here's a real-time summary of ResQNet's shelter activities, emergency rescues, and active adoptions today.
            </p>
        </div>
        
        <!-- Interactive Date Filter / Action Pill -->
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-secondary-50 border border-secondary-500/20 text-xs font-semibold text-secondary-500 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-secondary-500 animate-pulse"></span>
                Live Database
            </span>
            <button class="flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all duration-200">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Add Record</span>
            </button>
        </div>
    </div>

    <!-- Top Metric Row Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
        
        <!-- Card 1: Total Animals -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Total Animals
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        142
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+12%</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">vs last week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-emerald-600/10 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 dark:bg-emerald-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="paw-print" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 2: Active Rescues -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Active Rescues
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        5
                    </h3>
                </div>
                <!-- Pill / Decline -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 shadow-sm">
                        <i data-lucide="trending-down" class="w-3 h-3"></i>
                        <span>-10%</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">critical status</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-red-600/10 dark:bg-red-500/10 text-red-600 dark:text-red-400 group-hover:bg-red-600 dark:bg-red-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="shield-alert" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 3: Pets In Care -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Pets In Care
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        28
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+8%</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">vs last week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-[#F59E0B]/10 dark:bg-[#F59E0B]/20 text-[#D97706] dark:text-[#F59E0B] group-hover:bg-[#F59E0B] group-hover:text-white transition-all duration-300">
                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 4: Completed Adoptions -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Completed Adoptions
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        156
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+24%</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">this month</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-green-500/10 dark:bg-green-500/20 text-green-600 dark:text-green-400 group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
        </div>
        <!-- Card 5: Total Donations -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Total Donations
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        NPR 124,500
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+18%</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">this week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-[#FCC234]/10 dark:bg-[#FCC234]/20 text-[#9E7A1C] dark:text-[#FCC234] group-hover:bg-[#FCC234] group-hover:text-slate-900 dark:text-slate-100 transition-all duration-300">
                <i data-lucide="hand-coins" class="w-5 h-5"></i>
            </div>
        </div>

    </div>

    <!-- Donations this week Chart & Info Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        
        <!-- Left-Side Chart Box (Takes up roughly 2/3 width on large screens) -->
        <div class="bg-brand-cream border border-brand-orange/20 rounded-2xl p-6 lg:col-span-2 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header Area -->
                <div class="flex items-center justify-between pb-4 border-b border-brand-orange/10">
                    <div>
                        <h3 class="text-slate-900 dark:text-slate-100 font-bold text-lg tracking-tight">
                            Donations this week
                        </h3>
                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">
                            Every rupees fuels another rescue.
                        </p>
                    </div>
                    <!-- Right side: Trend pill and badge -->
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-secondary-50 text-secondary-500 shadow-sm">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+24%</span>
                        </span>
                        <span class="text-xs text-slate-500 dark:text-slate-300 hidden sm:inline">vs last week</span>
                    </div>
                </div>

                <!-- Clean Graph Area Container with Left Y-Axis and Right SVG Grid -->
                <div class="flex gap-4 mt-6">
                    <!-- Left Y-Axis Labels Column -->
                    <div class="flex flex-col justify-between text-[10px] font-semibold text-slate-500/60 dark:text-slate-300/60 h-48 py-1 select-none text-right w-16">
                        <span>NPR 50,000</span>
                        <span>NPR 37,500</span>
                        <span>NPR 25,000</span>
                        <span>NPR 12,500</span>
                        <span>NPR 0</span>
                    </div>

                    <!-- Right Chart Container -->
                    <div class="flex-1 flex flex-col justify-between">
                        <!-- SVG area has a fixed height of h-48 to hold the canvas cleanly -->
                        <div class="relative h-48 w-full">
                            <!-- Crisp responsive SVG -->
                            <svg class="w-full h-full" viewBox="0 0 600 160" preserveAspectRatio="none">
                                <defs>
                                    <!-- Salmon/Coral Gradient fading out smoothly at the base -->
                                    <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#EA7235" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#EA7235" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>

                                <!-- Horizontal dashed grid lines -->
                                <line x1="0" y1="10" x2="600" y2="10" stroke="#F1E6D4" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="45" x2="600" y2="45" stroke="#F1E6D4" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="80" x2="600" y2="80" stroke="#F1E6D4" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="115" x2="600" y2="115" stroke="#F1E6D4" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="150" x2="600" y2="150" stroke="#F1E6D4" stroke-dasharray="4 4" stroke-width="1" />

                                <!-- Elegant filled gradient path (salmon/coral) -->
                                <path d="M 0 130 
                                         C 80 120, 100 80, 180 90 
                                         C 260 100, 280 50, 360 60 
                                         C 440 70, 480 20, 600 15 
                                         L 600 150 L 0 150 Z" 
                                      fill="url(#chartGradient)" />

                                <!-- Thick overlay line path (salmon/coral) -->
                                <path d="M 0 130 
                                         C 80 120, 100 80, 180 90 
                                         C 260 100, 280 50, 360 60 
                                         C 440 70, 480 20, 600 15" 
                                      fill="none" stroke="#EA7235" stroke-width="3.5" stroke-linecap="round" />
                            </svg>
                        </div>

                        <!-- Days of the week at the bottom, perfectly aligned, no fixed height constraints -->
                        <div class="flex justify-between text-xs font-semibold text-slate-500/80 dark:text-slate-300/80 pt-3 border-t border-brand-orange/10 mt-3 select-none">
                            <span class="w-10 text-left">Mon</span>
                            <span class="w-10 text-center">Tue</span>
                            <span class="w-10 text-center">Wed</span>
                            <span class="w-10 text-center">Thu</span>
                            <span class="w-10 text-center">Fri</span>
                            <span class="w-10 text-center">Sat</span>
                            <span class="w-10 text-right">Sun</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right-Side Donation Goal/Campaign Card (1/3 width on large screens) -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="border-b border-slate-200 dark:border-slate-800/60 pb-4 mb-5">
                    <h3 class="text-slate-900 dark:text-slate-100 font-bold text-lg tracking-tight flex items-center gap-2">
                        <i data-lucide="target" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                        <span>Campaign Goal</span>
                    </h3>
                    <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">
                        Monthly milestone for shelter expansion.
                    </p>
                </div>

                <!-- Progress Section -->
                <div class="space-y-5">
                    <div class="flex items-baseline justify-between">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wider">Progress</span>
                        <div class="text-right">
                            <span class="text-xl font-extrabold text-slate-900 dark:text-slate-100">NPR 124,500</span>
                            <span class="text-xs text-slate-500 dark:text-slate-300">/ NPR 150,000</span>
                        </div>
                    </div>

                    <!-- Custom Progress Bar -->
                    <div class="w-full bg-slate-50 dark:bg-[#0b0f19] rounded-full h-3 overflow-hidden border border-slate-200 dark:border-slate-700/50">
                        <div class="bg-gradient-to-r from-emerald-600 dark:from-emerald-500 to-blue-500 h-full rounded-full transition-all duration-500 shadow-inner" style="width: 83%"></div>
                    </div>

                    <!-- Impact Breakdown List -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-slate-900 dark:text-slate-100 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 dark:bg-emerald-500"></span>
                                <span>Medical & Surgery</span>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-slate-100">60%</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-slate-900 dark:text-slate-100 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span>Pet Food & Shelter</span>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-slate-100">25%</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-slate-900 dark:text-slate-100 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                <span>Field Rescue Fuel</span>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-slate-100">15%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 text-[11px] text-slate-500 dark:text-slate-300 flex items-start gap-2.5">
                <i data-lucide="heart" class="w-4 h-4 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5 fill-red-600/25 dark:fill-red-400/25"></i>
                <p>
                    Every contribution directly funds shelter veterinary services and active field rescue operations.
                </p>
            </div>
        </div>

    </div>

    <!-- Mock Data Array & Main Dashboard Sub-grid exactly matching the reference mockup -->
    @php
    $highPriorityRescues = [
        [
            'id' => 1,
            'title' => 'Injured stray dog',
            'description' => 'A stray dog was hit by a vehicle near the main chowk. Visible leg injury, bleeding heavily. Requires immediate veterinary attention and rescue.',
            'status' => 'CRITICAL',
            'location' => 'Nayabazar-9, Pokhara',
            'time' => '8 min ago',
            'code' => 'RQ-2418',
            'reporter_name' => 'Ram Bahadur Thapa',
            'reporter_phone' => '+977-9841234567',
        ],
        [
            'id' => 2,
            'title' => 'Kitten stuck in drain',
            'description' => 'A small kitten has been trapped in a narrow drainage pipe for over two hours. Can hear crying but cannot climb out on its own.',
            'status' => 'URGENT',
            'location' => 'Chipledhunga, Pokhara near trademall',
            'time' => '26 min ago',
            'code' => 'RQ-2417',
            'reporter_name' => 'Sita Devi Sharma',
            'reporter_phone' => '+977-9859876543',
        ],
        [
            'id' => 3,
            'title' => 'Abandoned puppies (4)',
            'description' => 'Four newborn puppies found abandoned in a cardboard box near the temple. No mother in sight. Need immediate shelter and feeding.',
            'status' => 'URGENT',
            'location' => 'Chorepatan, Devis Fall',
            'time' => '1 h ago',
            'code' => 'RQ-2415',
            'reporter_name' => 'Hari Prasad Adhikari',
            'reporter_phone' => '+977-9801237890',
        ],
    ];

    $recentActivities = [
        [
            'title' => 'PAWS Pokhara',
            'action' => 'approved adoption for Mango',
            'time' => '2 min ago',
        ],
        [
            'title' => 'Donor: Maya Rana',
            'action' => 'donated NPR 2,500 to medical fund',
            'time' => '12 min ago',
        ],
        [
            'title' => 'Field team',
            'action' => 'rescued 4 kittens in Chorepatan',
            'time' => '38 min ago',
        ],
        [
            'title' => 'CARA Welfare',
            'action' => 'submitted org verification docs',
            'time' => '1 h ago',
        ],
    ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8"
         x-data="dashboardRescues()"
         @show-toast.window="showToast($event.detail.message)">

        <!-- Left Column: High-priority rescues -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 lg:col-span-2 shadow-sm">
            <div>
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200 dark:border-slate-800/60">
                    <div>
                        <h3 class="text-slate-900 dark:text-slate-100 font-extrabold text-lg tracking-tight">
                            High-priority rescues
                        </h3>
                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-0.5">
                            Help these animals now.
                        </p>
                    </div>
                    <a href="#" class="flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-600 dark:text-emerald-400Press transition-colors">
                        <span>View all</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($highPriorityRescues as $rescue)
                    <div x-data="rescueCard(@js($rescue))"
                         :style="currentStatus === 'CANCELLED' ? 'order: 9999;' : ''"
                         :class="{ 'bg-gray-50/80 dark:bg-[#111c2a]/40': currentStatus === 'CANCELLED' }"
                         class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl transition-all duration-300 hover:shadow-md hover:border-emerald-600/20 dark:border-emerald-500/20 overflow-hidden">

                        <div :class="{ 'opacity-60': currentStatus === 'RESOLVED', 'opacity-65': currentStatus === 'CANCELLED' }">
                            <div class="p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div class="flex items-start gap-4 min-w-0 flex-1">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <template x-if="currentStatus === 'CRITICAL'">
                                            <div class="relative">
                                                <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 animate-ping"></span>
                                                <div class="relative w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 flex items-center justify-center shadow-sm animate-pulse">
                                                    <i data-lucide="bell-ring" class="w-5 h-5 text-red-600 dark:text-red-400 fill-red-200 dark:fill-red-500/30"></i>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'URGENT'">
                                            <div class="w-12 h-12 rounded-full bg-orange-50 dark:bg-orange-500/20 text-brand-orange dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 flex items-center justify-center shadow-sm">
                                                <i data-lucide="alert-triangle" class="w-5 h-5 text-brand-orange dark:text-orange-400"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'MODERATE'">
                                            <div class="w-12 h-12 rounded-full bg-yellow-50 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/30 flex items-center justify-center shadow-sm">
                                                <i data-lucide="bell" class="w-5 h-5 text-yellow-500 dark:text-yellow-400"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'ASSIGNED'">
                                            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 flex items-center justify-center shadow-sm">
                                                <i data-lucide="truck" class="w-5 h-5 text-blue-500 dark:text-blue-400"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'RESOLVED'">
                                            <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center shadow-sm">
                                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 dark:text-emerald-400"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'CANCELLED'">
                                            <div class="w-12 h-12 rounded-full bg-slate-50 dark:bg-[#111c2a]/30 text-slate-400 dark:text-slate-300 border border-slate-200 dark:border-slate-700/40 flex items-center justify-center shadow-sm">
                                                <i data-lucide="slash" class="w-5 h-5 text-slate-400 dark:text-slate-300"></i>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-slate-900 dark:text-slate-100 font-bold text-sm sm:text-base tracking-tight truncate">
                                                {{ $rescue['title'] }}
                                            </h4>
                                            <span :class="badgeClasses" x-text="currentStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase">{{ $rescue['status'] }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-1.5 leading-relaxed line-clamp-2">{{ $rescue['description'] }}</p>
                                        <div class="flex items-center gap-3.5 mt-2 text-xs text-slate-500 dark:text-slate-300 flex-wrap">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500/50 dark:text-slate-300/50"></i>
                                                <span>{{ $rescue['location'] }}</span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-500/50 dark:text-slate-300/50"></i>
                                                <span>{{ $rescue['time'] }}</span>
                                            </span>
                                            <span class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase">{{ $rescue['code'] }}</span>
                                        </div>
                                        <template x-if="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED'">
                                            <div class="mt-2 flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-500/20 border border-blue-100 dark:border-blue-500/30 text-[10px] font-bold text-blue-700 dark:text-blue-400">
                                                    <i data-lucide="users" class="w-3 h-3"></i>
                                                    <span x-text="assignedTeam || 'Alpha Team'"></span>
                                                </span>
                                                <span class="text-[10px] text-slate-500 dark:text-slate-300">dispatched</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="flex-shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                    <template x-if="currentStatus === 'CRITICAL' || currentStatus === 'URGENT' || currentStatus === 'MODERATE'">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button @click="dispatchHelp()" class="bg-brand-orange hover:bg-[#E25338] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                                <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                                                Help Now
                                            </button>
                                            <button @click="openAssign()" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                                Assign Team
                                            </button>
                                            <button @click="markComplete()" class="border-2 border-[#5BA44C] text-[#5BA44C] hover:bg-[#5BA44C] hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                                Mark Complete
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="currentStatus === 'ASSIGNED'">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button @click="markComplete()" class="bg-[#5BA44C] dark:bg-[#4A8A3E] hover:bg-[#4A8A3E] dark:hover:bg-[#3D7A33] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                Mark Complete
                                            </button>
                                            <button @click="cancelCase()" class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95">
                                                Cancel
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="currentStatus === 'CANCELLED'">
                                        <span class="border border-slate-200 dark:border-slate-700/40 text-slate-400 dark:text-slate-300 font-medium px-4 py-2 rounded-lg text-xs inline-flex items-center gap-1.5 cursor-default">
                                            <i data-lucide="slash" class="w-3.5 h-3.5"></i>
                                            Passed / Cancelled
                                        </span>
                                    </template>

                                    <template x-if="currentStatus === 'RESOLVED'">
                                        <span class="bg-[#5BA44C] dark:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm inline-flex items-center gap-1.5 cursor-default">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            Resolved
                                        </span>
                                    </template>

                                    <button @click="expanded = !expanded" class="text-xs text-slate-400 dark:text-slate-300 hover:text-brand-orange transition-colors cursor-pointer flex items-center gap-1 whitespace-nowrap flex-shrink-0">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span x-text="expanded ? 'Hide Info' : 'View Info'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Expandable Detail Panel -->
                        <div x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border-t border-slate-200 dark:border-slate-800/60 bg-slate-50 dark:bg-[#111c2a]/30">
                            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4 space-y-4">
                                    <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        Case Details
                                    </h4>
                                    <div>
                                        <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Description</p>
                                        <p class="text-sm text-slate-900 dark:text-slate-100 mt-0.5 leading-relaxed">{{ $rescue['description'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Location</p>
                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-0.5 inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500/60 dark:text-slate-300/60"></i>
                                            {{ $rescue['location'] }}
                                        </p>
                                        <a href="#" class="block mt-1 text-[10px] font-bold text-brand-orange hover:underline">
                                            <i data-lucide="navigation" class="w-3 h-3 inline"></i> Get directions
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Image Evidence</p>
                                        <div class="mt-1.5 w-full h-28 rounded-lg bg-slate-100 dark:bg-[#0b0f19] border border-dashed border-slate-200 dark:border-slate-700/40 flex items-center justify-center text-slate-400 dark:text-slate-300">
                                            <div class="text-center">
                                                <i data-lucide="image" class="w-6 h-6 mx-auto mb-1"></i>
                                                <span class="text-[10px]">No image attached</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Case Code</p>
                                            <p class="text-sm font-mono font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $rescue['code'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Reported</p>
                                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 mt-0.5">{{ $rescue['time'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4 space-y-4">
                                    <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        Reporter Information
                                    </h4>
                                    <div>
                                        <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Full Name</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5">{{ $rescue['reporter_name'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-slate-500/70 dark:text-slate-300/70 uppercase tracking-wide">Contact</p>
                                        <a href="tel:{{ $rescue['reporter_phone'] }}" class="text-sm font-semibold text-brand-orange hover:text-brand-orange/80 transition-colors inline-flex items-center gap-1 mt-0.5">
                                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                            {{ $rescue['reporter_phone'] }}
                                        </a>
                                        <a href="#" class="block mt-1 text-[10px] font-bold text-brand-orange hover:underline">
                                            <i data-lucide="message-circle" class="w-3 h-3 inline"></i> Send SMS update
                                        </a>
                                    </div>
                                    <hr class="border-slate-200 dark:border-slate-800/60">
                                    <div>
                                        <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                            Assigned Team
                                        </h4>
                                        <template x-if="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED'">
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center gap-3 p-2.5 rounded-lg bg-blue-50 dark:bg-blue-500/20 border border-blue-100 dark:border-blue-500/30">
                                                    <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                                        <span x-text="(assignedTeam || 'Alpha Team').split(' ').map(w => w[0]).join('').substring(0, 2)"></span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-slate-900 dark:text-slate-100" x-text="assignedTeam || 'Alpha Team'"></p>
                                                        <p class="text-[10px] text-slate-500 dark:text-slate-300">4 members · En route</p>
                                                    </div>
                                                </div>
                                                <div class="flex -space-x-2">
                                                    <template x-for="i in 4" :key="i">
                                                        <div class="w-7 h-7 rounded-full border-2 border-white dark:border-slate-800/70 bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[9px] font-bold text-slate-500 dark:text-slate-300" x-text="['RP','SK','AD','MT'][i-1]"></div>
                                                    </template>
                                                    <span class="text-[9px] text-slate-500 dark:text-slate-300 ml-1 self-center">+ en route</span>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus !== 'ASSIGNED' && currentStatus !== 'RESOLVED'">
                                            <div class="mt-3 p-3 rounded-lg bg-slate-50 dark:bg-[#111c2a]/30 border border-dashed border-slate-200 dark:border-slate-700/40 text-center">
                                                <i data-lucide="users" class="w-5 h-5 mx-auto text-slate-300 mb-1"></i>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-300">No team assigned yet</p>
                                                <p class="text-[9px] text-slate-300 mt-0.5">Use "Assign Team" button above</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4">
                                    <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5 mb-4">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        Status Timeline
                                    </h4>
                                    <div class="space-y-0">
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div class="w-3 h-3 rounded-full bg-secondary-500 dark:bg-secondary-400 border-2 border-white dark:border-slate-800 ring-2 ring-secondary-500 dark:ring-secondary-400 z-10 flex-shrink-0"></div>
                                                <div class="w-0.5 bg-secondary-200 dark:bg-secondary-800 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p class="text-xs font-bold text-slate-900 dark:text-slate-100">Report Submitted</p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-300 mt-0.5">Case registered & queued for review</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 dark:bg-slate-600 ring-gray-200 dark:ring-slate-600'" class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800 ring-2 z-10 flex-shrink-0"></div>
                                                <div :class="currentStatus === 'RESOLVED' || currentStatus === 'CANCELLED' ? 'bg-secondary-200 dark:bg-secondary-800' : 'bg-gray-100 dark:bg-slate-700'" class="w-0.5 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500/70 dark:text-slate-300/70'" class="text-xs font-bold">Help Dispatched</p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-300 mt-0.5" x-text="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'Assistance on the way to location' : 'Pending dispatch'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 dark:bg-slate-600 ring-gray-200 dark:ring-slate-600'" class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800 ring-2 z-10 flex-shrink-0"></div>
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-secondary-200 dark:bg-secondary-800' : 'bg-gray-100 dark:bg-slate-700'" class="w-0.5 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500/70 dark:text-slate-300/70'" class="text-xs font-bold">Rescue Team Assigned</p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-300 mt-0.5" x-text="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'Team dispatched to location' : 'Awaiting team assignment'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-[#5BA44C] ring-[#5BA44C]' : 'bg-gray-200 dark:bg-slate-600 ring-gray-200 dark:ring-slate-600'" class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800 ring-2 z-10 flex-shrink-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'RESOLVED' ? 'text-slate-900 dark:text-slate-100' : 'text-slate-500/70 dark:text-slate-300/70'" class="text-xs font-bold">Rescue Completed</p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-300 mt-0.5" x-text="currentStatus === 'RESOLVED' ? 'Case resolved & closed' : 'Not yet completed'"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="currentStatus === 'RESOLVED'">
                                        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-2 text-[10px] text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg px-3 py-2">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            <span class="font-semibold">Case resolved at <span x-text="new Date().toLocaleTimeString()"></span></span>
                                        </div>
                                    </template>
                                    <template x-if="currentStatus === 'CANCELLED'">
                                        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700/50 flex items-center gap-2 text-[10px] text-slate-500 dark:text-slate-300 bg-slate-50 dark:bg-[#111c2a]/30 rounded-lg px-3 py-2">
                                            <i data-lucide="slash" class="w-3.5 h-3.5"></i>
                                            <span class="font-semibold">Case passed / cancelled</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Assign Team Modal -->
        <div x-show="assignModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="assignModalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-[#111c2a] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" @click.outside="assignModalOpen = false">
                <div class="bg-brand-navy px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base">Assign Rescue Team</h3>
                            <p class="text-white/60 text-xs" x-text="'Assigning team to ' + (assignTarget?.code || 'case')"></p>
                        </div>
                    </div>
                    <button @click="assignModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="space-y-3">
                        <template x-for="(team, idx) in rescueTeams" :key="idx">
                            <label :for="'dash-team-' + idx" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="selectedTeam === team.name ? 'border-brand-orange bg-brand-orange/5 dark:bg-brand-orange/10' : 'border-slate-200 dark:border-slate-700/40 hover:border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-700/50'">
                                <input type="radio" :id="'dash-team-' + idx" :value="team.name" x-model="selectedTeam" class="w-4 h-4 text-brand-orange accent-brand-orange flex-shrink-0">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" :style="'background-color: ' + team.color">
                                        <span x-text="team.initials"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="team.name"></p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-300" x-text="team.members + ' members · ' + team.status"></p>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button @click="confirmAssign()" :disabled="!selectedTeam" class="flex-1 py-2.5 rounded-xl bg-brand-orange text-white text-xs font-bold hover:bg-[#E25338] transition-colors disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                            Assign Team
                        </button>
                        <button @click="assignModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div x-show="toastVisible" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-6 right-6 z-[70] max-w-sm bg-white dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 rounded-xl shadow-2xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-[#5BA44C]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="check-circle" class="w-4 h-4 text-[#5BA44C]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100">Action Completed</p>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-300"></i>
            </button>
        </div>

        <!-- Right Column: Recent Activity Feed matching the mockup perfectly -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="border-b border-slate-200 dark:border-slate-800/60 pb-4 mb-6">
                    <h3 class="text-slate-900 dark:text-slate-100 font-extrabold text-lg tracking-tight">
                        Recent activity
                    </h3>
                    <p class="text-slate-500 dark:text-slate-300 text-xs mt-0.5">
                        Live feed across the network.
                    </p>
                </div>

                <!-- Timeline List of Feeds -->
                <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-800/60 space-y-6">
                    @foreach($recentActivities as $activity)
                        <div class="relative">
                            <!-- Bullet timeline dot connecting them -->
                            <span class="absolute -left-[31px] top-1.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-white dark:bg-[#1a2333] border-2 border-emerald-600 dark:border-emerald-500 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-emerald-600 dark:bg-emerald-500"></span>
                            </span>
                            
                            <!-- Feed Content -->
                            <div>
                                <p class="text-xs text-slate-900 dark:text-slate-100 leading-relaxed">
                                    <span class="font-extrabold text-slate-900 dark:text-slate-100">{{ $activity['title'] }}</span> 
                                    <span class="text-slate-500 dark:text-slate-300">{{ $activity['action'] }}</span>
                                </p>
                                <span class="block text-[10px] text-slate-500/60 dark:text-slate-300/60 mt-1 font-semibold">
                                    {{ $activity['time'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sleek info helper in footer of right card -->
            <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/50 text-[11px] text-slate-500 dark:text-slate-300 flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5"></i>
                <p>
                    Activities are streamed live from ResQNet, Animal Welfare Nepal and field rescuers.
                </p>
            </div>
        </div>

    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function dashboardRescues() {
            return {
                assignModalOpen: false,
                assignTarget: null,
                selectedTeam: '',
                rescueTeams: [
                    { name: 'Alpha Team', initials: 'AT', color: '#2563EB', members: 4, status: 'Available' },
                    { name: 'Bravo Team', initials: 'BT', color: '#7C3AED', members: 3, status: 'Available' },
                    { name: 'Charlie Team', initials: 'CT', color: '#DC2626', members: 5, status: 'On another call' },
                    { name: 'Delta Team', initials: 'DT', color: '#5BA44C', members: 2, status: 'Available' },
                ],
                toastMessage: '',
                toastVisible: false,
                toastTimeout: null,
                showToast(message) {
                    this.toastMessage = message;
                    this.toastVisible = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 3000);
                },
                openAssignModal(card) {
                    this.assignTarget = card;
                    this.selectedTeam = '';
                    this.assignModalOpen = true;
                },
                confirmAssign() {
                    if (!this.selectedTeam) return;
                    if (this.assignTarget) {
                        this.assignTarget.assignedTeam = this.selectedTeam;
                        if (this.assignTarget.currentStatus !== 'RESOLVED' && this.assignTarget.currentStatus !== 'CANCELLED') {
                            this.assignTarget.currentStatus = 'ASSIGNED';
                        }
                    }
                    this.assignModalOpen = false;
                    this.showToast('Rescue team ' + this.selectedTeam + ' has been assigned and notified.');
                }
            }
        }

        function rescueCard(rescue) {
            return {
                originalStatus: rescue.status,
                currentStatus: rescue.status,
                expanded: false,
                assignedTeam: null,
                get badgeClasses() {
                    const s = this.currentStatus;
                    if (s === 'CRITICAL') return 'bg-red-600 dark:bg-red-500 text-white';
                    if (s === 'URGENT') return 'bg-brand-orange/20 text-brand-orange border border-brand-orange/30';
                    if (s === 'MODERATE') return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                    if (s === 'ASSIGNED') return 'bg-[#2563EB] text-white';
                    if (s === 'RESOLVED') return 'bg-[#5BA44C] text-white';
                    if (s === 'CANCELLED') return 'bg-slate-100 dark:bg-[#111c2a] text-slate-500 dark:text-slate-300';
                    return 'bg-gray-100 dark:bg-[#111c2a] text-gray-600 dark:text-slate-300';
                },
                dispatchHelp() {
                    if (this.currentStatus === 'CRITICAL' || this.currentStatus === 'URGENT' || this.currentStatus === 'MODERATE') {
                        this.currentStatus = 'ASSIGNED';
                        this.assignedTeam = this.assignedTeam || 'Alpha Team';
                    }
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Help dispatched! Rescue team is en route to the location.' }
                    }));
                },
                openAssign() {
                    const app = document.querySelector('[x-data="dashboardRescues()"]')?.__x.$data;
                    if (app) app.openAssignModal(this);
                },
                markComplete() {
                    if (this.currentStatus === 'CANCELLED') return;
                    this.currentStatus = 'RESOLVED';
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Case marked as resolved. All tasks completed.' }
                    }));
                },
                cancelCase() {
                    this.currentStatus = 'CANCELLED';
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Case passed back to the global network. Other organizations can now accept this report.' }
                    }));
                }
            }
        }
    </script>
</x-layouts.admin>
