<x-layouts.admin>
    <!-- Welcome Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-admin-textPrimary font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                Welcome back, Asmita 🐾
            </h1>
            <p class="text-admin-textSecondary text-sm mt-1">
                Here's a real-time summary of ResQNet's shelter activities, emergency rescues, and active adoptions today.
            </p>
        </div>
        
        <!-- Interactive Date Filter / Action Pill -->
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-admin-surface border border-admin-border text-xs font-semibold text-admin-textSecondary shadow-sm">
                <span class="w-2 h-2 rounded-full bg-admin-btnGreen animate-pulse"></span>
                Live Database
            </span>
            <button class="flex items-center gap-2 bg-admin-primary hover:bg-admin-primaryPress text-admin-textOnPrimary text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all duration-200">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Add Record</span>
            </button>
        </div>
    </div>

    <!-- Top Metric Row Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
        
        <!-- Card 1: Total Animals -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">
                        Total Animals
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        142
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-successBg text-admin-successText shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+12%</span>
                    </span>
                    <span class="text-[10px] text-admin-textSecondary">vs last week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-primary/10 text-admin-primary group-hover:bg-admin-primary group-hover:text-admin-textOnPrimary transition-all duration-300">
                <i data-lucide="paw-print" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 2: Active Rescues -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">
                        Active Rescues
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        5
                    </h3>
                </div>
                <!-- Pill / Decline -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-errorBg text-admin-errorText shadow-sm">
                        <i data-lucide="trending-down" class="w-3 h-3"></i>
                        <span>-10%</span>
                    </span>
                    <span class="text-[10px] text-admin-textSecondary">critical status</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-btnRed/10 text-admin-btnRed group-hover:bg-admin-btnRed group-hover:text-white transition-all duration-300">
                <i data-lucide="shield-alert" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 3: Pets In Care -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">
                        Pets In Care
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        28
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-successBg text-admin-successText shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+8%</span>
                    </span>
                    <span class="text-[10px] text-admin-textSecondary">vs last week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-[#F59E0B]/10 text-[#D97706] group-hover:bg-[#F59E0B] group-hover:text-white transition-all duration-300">
                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 4: Completed Adoptions -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">
                        Completed Adoptions
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        156
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-successBg text-admin-successText shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+24%</span>
                    </span>
                    <span class="text-[10px] text-admin-textSecondary">this month</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-btnGreen/10 text-admin-btnGreen group-hover:bg-admin-btnGreen group-hover:text-white transition-all duration-300">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
        </div>
        <!-- Card 5: Total Donations -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <!-- Title -->
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">
                        Total Donations
                    </span>
                    <!-- Main Value -->
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        NPR 124,500
                    </h3>
                </div>
                <!-- Pill / Growth -->
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-successBg text-admin-successText shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>+18%</span>
                    </span>
                    <span class="text-[10px] text-admin-textSecondary">this week</span>
                </div>
            </div>
            <!-- Circular Icon Placeholder Top Right -->
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-[#FCC234]/10 text-[#9E7A1C] group-hover:bg-[#FCC234] group-hover:text-admin-textPrimary transition-all duration-300">
                <i data-lucide="hand-coins" class="w-5 h-5"></i>
            </div>
        </div>

    </div>

    <!-- Donations this week Chart & Info Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        
        <!-- Left-Side Chart Box (Takes up roughly 2/3 width on large screens) -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 lg:col-span-2 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header Area -->
                <div class="flex items-center justify-between pb-4 border-b border-admin-border/60">
                    <div>
                        <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight">
                            Donations this week
                        </h3>
                        <p class="text-admin-textSecondary text-xs mt-1">
                            Every rupees fuels another rescue.
                        </p>
                    </div>
                    <!-- Right side: Trend pill and badge -->
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-admin-successBg text-admin-successText shadow-sm">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+24%</span>
                        </span>
                        <span class="text-xs text-admin-textSecondary hidden sm:inline">vs last week</span>
                    </div>
                </div>

                <!-- Clean Graph Area Container with Left Y-Axis and Right SVG Grid -->
                <div class="flex gap-4 mt-6">
                    <!-- Left Y-Axis Labels Column -->
                    <div class="flex flex-col justify-between text-[10px] font-semibold text-admin-textSecondary/60 h-48 py-1 select-none text-right w-16">
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
                                        <stop offset="0%" stop-color="#FA6E52" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#FA6E52" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>

                                <!-- Horizontal dashed grid lines -->
                                <line x1="0" y1="10" x2="600" y2="10" stroke="#E0E0E0" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="45" x2="600" y2="45" stroke="#E0E0E0" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="80" x2="600" y2="80" stroke="#E0E0E0" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="115" x2="600" y2="115" stroke="#E0E0E0" stroke-dasharray="4 4" stroke-width="1" />
                                <line x1="0" y1="150" x2="600" y2="150" stroke="#E0E0E0" stroke-dasharray="4 4" stroke-width="1" />

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
                                      fill="none" stroke="#FA6E52" stroke-width="3.5" stroke-linecap="round" />
                            </svg>
                        </div>

                        <!-- Days of the week at the bottom, perfectly aligned, no fixed height constraints -->
                        <div class="flex justify-between text-xs font-semibold text-admin-textSecondary/80 pt-3 border-t border-admin-border/40 mt-3 select-none">
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
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="border-b border-admin-border/60 pb-4 mb-5">
                    <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight flex items-center gap-2">
                        <i data-lucide="target" class="w-5 h-5 text-admin-primary"></i>
                        <span>Campaign Goal</span>
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-1">
                        Monthly milestone for shelter expansion.
                    </p>
                </div>

                <!-- Progress Section -->
                <div class="space-y-5">
                    <div class="flex items-baseline justify-between">
                        <span class="text-xs font-semibold text-admin-textSecondary uppercase tracking-wider">Progress</span>
                        <div class="text-right">
                            <span class="text-xl font-extrabold text-admin-textPrimary">NPR 124,500</span>
                            <span class="text-xs text-admin-textSecondary">/ NPR 150,000</span>
                        </div>
                    </div>

                    <!-- Custom Progress Bar -->
                    <div class="w-full bg-admin-bg rounded-full h-3 overflow-hidden border border-admin-border/50">
                        <div class="bg-gradient-to-r from-admin-primary to-admin-navActive h-full rounded-full transition-all duration-500 shadow-inner" style="width: 83%"></div>
                    </div>

                    <!-- Impact Breakdown List -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-admin-textPrimary font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-admin-primary"></span>
                                <span>Medical & Surgery</span>
                            </div>
                            <span class="font-bold text-admin-textPrimary">60%</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-admin-textPrimary font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-admin-navActive"></span>
                                <span>Pet Food & Shelter</span>
                            </div>
                            <span class="font-bold text-admin-textPrimary">25%</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 text-admin-textPrimary font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-admin-btnGreen"></span>
                                <span>Field Rescue Fuel</span>
                            </div>
                            <span class="font-bold text-admin-textPrimary">15%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="mt-6 p-4 rounded-xl bg-admin-bg border border-admin-border/40 text-[11px] text-admin-textSecondary flex items-start gap-2.5">
                <i data-lucide="heart" class="w-4 h-4 text-admin-btnRed flex-shrink-0 mt-0.5 fill-admin-btnRed/25"></i>
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
            'title' => 'Injured stray dog',
            'status' => 'CRITICAL',
            'location' => 'Nayabazar-9, Pokhara',
            'time' => '8 min ago',
            'code' => 'RQ-2418',
        ],
        [
            'title' => 'Kitten stuck in drain',
            'status' => 'URGENT',
            'location' => 'Chipledhunga, Pokhara near trademall',
            'time' => '26 min ago',
            'code' => 'RQ-2417',
        ],
        [
            'title' => 'Abandoned puppies (4)',
            'status' => 'URGENT',
            'location' => 'Chorepatan, Devis Fall',
            'time' => '1 h ago',
            'code' => 'RQ-2415',
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        
        <!-- Left Column: High-priority rescues Outer Container Card -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 lg:col-span-2 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header Row -->
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-admin-border/60">
                    <div>
                        <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                            High-priority rescues
                        </h3>
                        <p class="text-admin-textSecondary text-xs mt-0.5">
                            Help these animals now.
                        </p>
                    </div>
                    <a href="#" class="flex items-center gap-1 text-xs font-bold text-admin-primary hover:text-admin-primaryPress transition-colors">
                        <span>View all</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- List Row Item Grid -->
                <div class="space-y-4">
                    @foreach($highPriorityRescues as $rescue)
                        <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-200 hover:shadow-md hover:border-admin-primary/20">
                            
                            <!-- Left & Text Column -->
                            <div class="flex items-center gap-4">
                                <!-- Circular Icon Container with Dynamic Glowing/Pulsing Alert for CRITICAL status -->
                                @if($rescue['status'] === 'CRITICAL')
                                    <div class="relative flex-shrink-0">
                                        <!-- Popping red glowing backdrop ring (popping in and out) -->
                                        <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 animate-ping"></span>
                                        <!-- Pulsing inner circle -->
                                        <div class="relative w-12 h-12 rounded-full bg-red-100 text-red-600 border border-red-200 flex items-center justify-center shadow-sm animate-pulse">
                                            <i data-lucide="bell-ring" class="w-5 h-5 text-red-600 fill-red-200"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-400 border border-red-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <i data-lucide="bell" class="w-5 h-5 text-red-400"></i>
                                    </div>
                                @endif

                                <!-- Text Content Column -->
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-admin-textPrimary font-bold text-sm sm:text-base tracking-tight truncate">
                                            {{ $rescue['title'] }}
                                        </h4>
                                        
                                        <!-- Status Badge Logic -->
                                        @if($rescue['status'] === 'CRITICAL')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-admin-btnRed text-white tracking-wide">
                                                CRITICAL
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-[#FDF6E2] text-[#8F5F14] border border-[#F5E7C4] tracking-wide uppercase">
                                                {{ $rescue['status'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Lower Meta-data Row -->
                                    <div class="flex items-center gap-3.5 mt-2 text-xs text-admin-textSecondary flex-wrap">
                                        <!-- Location -->
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                            <span>{{ $rescue['location'] }}</span>
                                        </div>
                                        
                                        <!-- Time Ago -->
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                            <span>{{ $rescue['time'] }}</span>
                                        </div>
                                        
                                        <!-- ID Code -->
                                        <div class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase">
                                            {{ $rescue['code'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section: Help Now Coral Action Button -->
                            <div class="flex-shrink-0">
                                <button class="w-full sm:w-auto bg-[#FA6E52] hover:bg-[#E25338] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                                    Help now
                                </button>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Activity Feed matching the mockup perfectly -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="border-b border-admin-border/60 pb-4 mb-6">
                    <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                        Recent activity
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-0.5">
                        Live feed across the network.
                    </p>
                </div>

                <!-- Timeline List of Feeds -->
                <div class="relative pl-6 border-l-2 border-admin-border/60 space-y-6">
                    @foreach($recentActivities as $activity)
                        <div class="relative">
                            <!-- Bullet timeline dot connecting them -->
                            <span class="absolute -left-[31px] top-1.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-admin-surface border-2 border-admin-primary shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-admin-primary"></span>
                            </span>
                            
                            <!-- Feed Content -->
                            <div>
                                <p class="text-xs text-admin-textPrimary leading-relaxed">
                                    <span class="font-extrabold text-admin-textPrimary">{{ $activity['title'] }}</span> 
                                    <span class="text-admin-textSecondary">{{ $activity['action'] }}</span>
                                </p>
                                <span class="block text-[10px] text-admin-textSecondary/60 mt-1 font-semibold">
                                    {{ $activity['time'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sleek info helper in footer of right card -->
            <div class="mt-6 p-4 rounded-xl bg-admin-bg border border-admin-border/50 text-[11px] text-admin-textSecondary flex items-start gap-2.5">
                <i data-lucide="info" class="w-4 h-4 text-admin-primary flex-shrink-0 mt-0.5"></i>
                <p>
                    Activities are streamed live from ResQNet, Animal Welfare Nepal and field rescuers.
                </p>
            </div>
        </div>

    </div>
</x-layouts.admin>
