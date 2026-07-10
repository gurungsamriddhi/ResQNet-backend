<x-layouts.admin>
    <div x-data="dashboardViewer()">

    <!-- Welcome Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-slate-900 dark:text-slate-100 font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                Welcome back, {{ auth()->user()->name }} 🐾
            </h1>
            <p class="text-slate-500 dark:text-slate-300 text-sm mt-1">
                Platform-wide oversight hub — monitoring network rescues, partner NGOs, and system operations.
            </p>
        </div>
    </div>

    <!-- Top Hero Metric Cards Row (4 Equal Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">

        <!-- Card 1: Pets Listed (This Month) - Live WebSocket Counter -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Pets Listed (This Month)
                    </span>
                    <h3 id="pets-counter"
                        x-ref="petsCounter"
                        class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        <span x-text="numberFormat(petsListedThisMonth)"></span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>Network-wide</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">new registrations</span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-emerald-600/10 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-600 dark:group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="paw-print" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 2: Active Partner NGOs -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Active Partner NGOs
                    </span>
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        <span x-text="numberFormat(activeOrgs)"></span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>Verified</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">live on ResQNet</span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-blue-600/10 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 dark:group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="building-2" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 3: Pending Org Requests -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Pending Org Requests
                    </span>
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        <span x-text="numberFormat(pendingOrgs)"></span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/60 shadow-sm">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Awaiting Review</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">doc verification</span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-amber-600/10 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 group-hover:bg-amber-600 dark:group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Card 4: Funds Raised (Khalti Hub) -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">
                        Funds Raised (Khalti Hub)
                    </span>
                    <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        NPR <span x-text="numberFormat(Math.floor(totalDonations))"></span>
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        <span>Aggregated</span>
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-300">network donations</span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-yellow-600/10 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 group-hover:bg-yellow-600 dark:group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300">
                <i data-lucide="hand-coins" class="w-5 h-5"></i>
            </div>
        </div>

    </div>

    <!-- Funds Raised - Bar Chart with Filter -->
    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm mt-8">
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 mb-6 border-b border-slate-200 dark:border-slate-800/60">
                <div>
                    <h3 class="text-slate-900 dark:text-slate-100 font-extrabold text-lg tracking-tight">
                        Funds Raised
                    </h3>
                    <p class="text-slate-500 dark:text-slate-300 text-xs mt-0.5">
                        Donation revenue collected network-wide in NPR.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Date Range Dropdown -->
                    <div class="relative" id="dateDropdown">
                        <button id="dateDropdownToggle"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700/40 bg-white dark:bg-[#0b0f19] text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span id="dateRangeLabel">Date: Last 7 days</span>
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div id="dateDropdownMenu" class="hidden absolute right-0 mt-1 w-56 bg-white dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 rounded-lg shadow-lg z-50 py-1">
                            <button type="button" data-range="1" class="range-option w-full px-4 py-2 text-left text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1a2333] transition-colors">Last 24 hours</button>
                            <button type="button" data-range="7" class="range-option w-full px-4 py-2 text-left text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1a2333] transition-colors">Last 7 days</button>
                            <button type="button" data-range="28" class="range-option w-full px-4 py-2 text-left text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1a2333] transition-colors">Last 28 days</button>
                            <button type="button" data-range="90" class="range-option w-full px-4 py-2 text-left text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1a2333] transition-colors">Last 3 months</button>
                            <hr class="my-1 border-slate-200 dark:border-slate-700/40">
                            <button type="button" data-range="custom" class="range-option w-full px-4 py-2 text-left text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#1a2333] transition-colors">Custom Range...</button>
                        </div>
                    </div>

                    <!-- Currency Badge -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-secondary-50 border border-secondary-500/20 text-xs font-semibold text-secondary-500 shadow-sm">
                        NPR
                    </span>

                    <!-- Custom Range Date Pickers (hidden by default) -->
                    <div id="customRangeContainer" class="flex items-center gap-2" style="display:none">
                        <input type="date" id="customStartDate"
                               class="text-xs font-semibold bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">to</span>
                        <input type="date" id="customEndDate"
                               class="text-xs font-semibold bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-lg px-3 py-2 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                    </div>
                </div>
            </div>

            <div class="w-full" style="height: 380px;">
                <canvas id="donationChart" class="w-full h-full"></canvas>
            </div>

            <div class="mt-4 p-4 rounded-xl bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/50 text-[11px] text-slate-500 dark:text-slate-300 flex items-start gap-2.5">
                <i data-lucide="trending-up" class="w-4 h-4 text-brand-orange flex-shrink-0 mt-0.5"></i>
                <p>
                    Daily donation revenue sourced from Khalti payment gateway. Completed transactions only.
                </p>
            </div>
        </div>
    </div>

    <!-- Middle Layout Container (Split Column View) -->
    <div class="grid grid-cols-1 lg:grid-cols-[65%_35%] gap-6 mt-8">

        <!-- Left Side Column (65%) - Live Network Rescues (Read-Only) -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm">
            <div>
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200 dark:border-slate-800/60">
                    <div>
                        <h3 class="text-slate-900 dark:text-slate-100 font-extrabold text-lg tracking-tight">
                            Live Network Rescues (Read-Only)
                        </h3>
                        <p class="text-slate-500 dark:text-slate-300 text-xs mt-0.5">
                            Incoming public emergency rescue reports across all organizations.
                        </p>
                    </div>
                </div>

                <div x-show="liveRescues.length === 0">
                    <div class="text-center py-12 text-slate-500 dark:text-slate-300">
                        <i data-lucide="eye-off" class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-600"></i>
                        <p class="text-sm font-medium">No active rescue reports</p>
                        <p class="text-xs mt-1">All clear across the network.</p>
                    </div>
                </div>
                <div x-show="liveRescues.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700/40">
                                <th class="text-left pb-3 pr-3">Case ID</th>
                                <th class="text-left pb-3 pr-3">Reported Location</th>
                                <th class="text-left pb-3 pr-3">Urgency</th>
                                <th class="text-left pb-3 pr-3">Handling NGO</th>
                                <th class="text-left pb-3 pr-3">Status</th>
                                <th class="text-right pb-3 pl-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40">
                            <template x-for="rescue in liveRescues" :key="rescue.id">
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#111c2a]/40 transition-colors">
                                    <td class="py-3 pr-3">
                                        <span class="font-mono font-bold text-xs text-slate-900 dark:text-slate-100" x-text="formatCaseCode(rescue)"></span>
                                    </td>
                                    <td class="py-3 pr-3">
                                        <span class="text-xs text-slate-700 dark:text-slate-300 truncate block max-w-[160px]">
                                            <i data-lucide="map-pin" class="w-3 h-3 inline text-slate-400"></i>
                                            <span x-text="rescue.address || 'N/A'"></span>
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3">
                                        <span :class="'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide uppercase ' + urgencyBadge(rescue.urgency)">
                                            <span x-text="rescue.urgency"></span>
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3">
                                        <span class="text-xs text-slate-700 dark:text-slate-300" x-text="rescue.organization?.name || 'Unassigned'"></span>
                                    </td>
                                    <td class="py-3 pr-3">
                                        <span :class="'inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase ' + statusBadge(rescue.status)">
                                            <span x-text="rescue.status.replace('_', ' ')"></span>
                                        </span>
                                    </td>
                                    <td class="py-3 pl-3 text-right">
                                        <button @click="openViewer(rescue.id)"
                                                class="p-2 rounded-lg text-slate-400 dark:text-slate-400 hover:text-brand-orange hover:bg-brand-orange/10 dark:hover:bg-brand-orange/10 transition-all"
                                                title="View Details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side Column (35%) - High-Priority Action Blocks -->
        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm flex flex-col">
            <div>
                <div class="border-b border-slate-200 dark:border-slate-800/60 pb-4 mb-5">
                    <h3 class="text-slate-900 dark:text-slate-100 font-bold text-lg tracking-tight flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="w-5 h-5 text-amber-600 dark:text-amber-400"></i>
                        <span>Organization Registration Queue</span>
                    </h3>
                    <p class="text-slate-500 dark:text-slate-300 text-xs mt-1">
                        New shelter applications awaiting document verification.
                    </p>
                </div>

                <div x-show="pendingOrganizations.length === 0">
                    <div class="text-center py-8 text-slate-500 dark:text-slate-300">
                        <i data-lucide="check-circle" class="w-8 h-8 mx-auto mb-2 text-emerald-400"></i>
                        <p class="text-xs font-medium">All caught up!</p>
                        <p class="text-[10px] mt-1">No pending registration requests.</p>
                    </div>
                </div>
                <div x-show="pendingOrganizations.length > 0" class="space-y-3">
                    <template x-for="org in pendingOrganizations" :key="org.id">
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#111c2a]/40 border border-slate-200 dark:border-slate-700/40 hover:border-amber-400/30 transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate" x-text="org.name"></h4>
                                    <div class="mt-1.5 space-y-1">
                                        <p class="text-[10px] text-slate-500 dark:text-slate-300 flex items-center gap-1">
                                            <i data-lucide="file-text" class="w-3 h-3"></i>
                                            <span class="font-mono" x-text="org.registration_number || 'N/A'"></span>
                                        </p>
                                        <p class="text-[10px] text-slate-500 dark:text-slate-300 flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3"></i>
                                            <span x-text="org.address || 'N/A'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wide uppercase bg-amber-50 text-amber-600 border border-amber-200/60">
                                        <i data-lucide="clock" class="w-2.5 h-2.5 mr-1"></i>
                                        Review Pending
                                    </span>
                                    <a href="{{ route('admin.organizations.index') }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-brand-orange hover:bg-brand-orange/90 text-white text-[10px] font-bold transition-all active:scale-95">
                                        <i data-lucide="shield" class="w-3 h-3"></i>
                                        Verify Docs
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-auto pt-5">
                <a href="{{ route('admin.organizations.index') }}"
                   class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl border border-dashed border-slate-300 dark:border-slate-700/50 text-xs font-bold text-slate-500 dark:text-slate-300 hover:border-brand-orange/40 hover:text-brand-orange transition-all">
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    <span>View All Registration Requests</span>
                </a>
            </div>
        </div>

        <!-- View Details Modal (Read-Only) -->
        <div x-show="selectedRescueId"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data="{}">
            <div @click="selectedRescueId = null" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-[#111c2a] rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto"
                 @click.outside="selectedRescueId = null">
                <div class="bg-brand-navy px-6 py-5 flex items-center justify-between sticky top-0 z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base" x-text="'Case ' + (currentRescue.case_code || 'RSQ-' + currentRescue.id.toString().padStart(5, '0'))"></h3>
                            <p class="text-white/60 text-xs">Rescue Report Snapshot</p>
                        </div>
                    </div>
                    <button @click="selectedRescueId = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4">
                        <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                            <i data-lucide="align-left" class="w-3.5 h-3.5"></i>
                            Description
                        </h4>
                        <p class="text-sm text-slate-900 dark:text-slate-100 leading-relaxed" x-text="currentRescue.description"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4">
                            <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                Attached Image
                            </h4>
                            <template x-if="currentRescue.image_path">
                                <img :src="'/storage/' + currentRescue.image_path"
                                     alt="Rescue evidence"
                                     class="w-full h-40 object-cover rounded-lg border border-slate-200 dark:border-slate-700/40">
                            </template>
                            <template x-if="!currentRescue.image_path">
                                <div class="w-full h-40 rounded-lg bg-slate-100 dark:bg-[#0b0f19] border border-dashed border-slate-200 dark:border-slate-700/40 flex items-center justify-center text-slate-400 dark:text-slate-300">
                                    <div class="text-center">
                                        <i data-lucide="image-off" class="w-6 h-6 mx-auto mb-1"></i>
                                        <span class="text-xs">No image attached</span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-4">
                            <h4 class="text-[11px] font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                                <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                                GPS Coordinates
                            </h4>
                            <template x-if="currentRescue.latitude && currentRescue.longitude">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2 p-2.5 rounded-lg bg-white dark:bg-[#111827] border border-slate-200 dark:border-slate-700/40">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-brand-orange flex-shrink-0"></i>
                                        <div>
                                            <p class="text-xs font-mono font-bold text-slate-900 dark:text-slate-100">
                                                <span x-text="currentRescue.latitude"></span>, <span x-text="currentRescue.longitude"></span>
                                            </p>
                                            <p class="text-[10px] text-slate-500 dark:text-slate-300">Latitude, Longitude</p>
                                        </div>
                                    </div>
                                    <a :href="'https://www.google.com/maps?q=' + currentRescue.latitude + ',' + currentRescue.longitude"
                                       target="_blank"
                                       class="flex items-center justify-center gap-1.5 w-full py-2 rounded-lg bg-brand-orange/10 text-brand-orange text-xs font-bold hover:bg-brand-orange/20 transition-all">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        <span>Open in Google Maps</span>
                                    </a>
                                </div>
                            </template>
                            <template x-if="!currentRescue.latitude || !currentRescue.longitude">
                                <div class="w-full h-32 rounded-lg bg-slate-100 dark:bg-[#0b0f19] border border-dashed border-slate-200 dark:border-slate-700/40 flex items-center justify-center text-slate-400 dark:text-slate-300">
                                    <div class="text-center">
                                        <i data-lucide="map-off" class="w-6 h-6 mx-auto mb-1"></i>
                                        <span class="text-xs">GPS coordinates not available</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-3">
                            <p class="text-[9px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Urgency</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 capitalize" x-text="currentRescue.urgency"></p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-3">
                            <p class="text-[9px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Status</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 capitalize" x-text="currentRescue.status.replace('_', ' ')"></p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-3">
                            <p class="text-[9px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Organization</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 truncate" x-text="currentRescue.organization?.name || 'Unassigned'"></p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 rounded-xl p-3">
                            <p class="text-[9px] font-semibold text-slate-500 dark:text-slate-300 uppercase tracking-wide">Address</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-0.5 truncate" x-text="currentRescue.address || 'N/A'"></p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700/40 flex justify-end">
                    <button @click="selectedRescueId = null"
                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 bg-white dark:bg-[#1a2333] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Close
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Row Container (Full Width) - System-Wide Operational Stream -->
    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-6 shadow-sm mt-6">
        <div>
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-200 dark:border-slate-800/60">
                <div>
                    <h3 class="text-slate-900 dark:text-slate-100 font-extrabold text-lg tracking-tight">
                        System-Wide Operational Stream
                    </h3>
                    <p class="text-slate-500 dark:text-slate-300 text-xs mt-0.5">
                        Cross-organization audit trail of network activities.
                    </p>
                </div>
            </div>

            <div x-show="streamActivities.length === 0">
                <div class="text-center py-10 text-slate-500 dark:text-slate-300">
                    <i data-lucide="activity" class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-600"></i>
                    <p class="text-sm font-medium">No recent activities</p>
                    <p class="text-xs mt-1">System activity feed will appear here.</p>
                </div>
            </div>

            <div x-show="streamActivities.length > 0"
                 class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-800/60 space-y-5"
                 id="activity-stream">
                <template x-for="(activity, idx) in streamActivities" :key="idx">
                    <div class="relative"
                         x-show="true"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 -translate-y-3"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <span class="absolute -left-[31px] top-1.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-white dark:bg-[#1a2333] border-2 border-emerald-600 dark:border-emerald-500 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-emerald-600 dark:bg-emerald-500"></span>
                        </span>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-300 leading-relaxed" x-text="activity.description"></p>
                            <span class="block text-[10px] text-slate-500/60 dark:text-slate-300/60 mt-1 font-semibold" x-text="activity.time"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <script>
        function dashboardViewer() {
            return {
                selectedRescueId: null,
                liveRescues: @json($liveRescues),
                petsListedThisMonth: {{ $stats['pets_this_month'] }},
                activeOrgs: {{ $stats['active_orgs'] }},
                pendingOrgs: {{ $stats['pending_orgs'] }},
                totalDonations: {{ $stats['total_donations'] }},
                pendingOrganizations: @json($pendingOrganizations),
                streamActivities: @json($recentActivities),
                echoConnected: false,
                echoStatus: 'disconnected',

                get currentRescue() {
                    if (!this.selectedRescueId) return {};
                    return this.liveRescues.find(r => r.id === this.selectedRescueId) || {};
                },

                numberFormat(num) {
                    return Number(num).toLocaleString();
                },

                urgencyBadge(urgency) {
                    var map = {
                        critical: 'bg-red-600 text-white',
                        high: 'bg-brand-orange text-white',
                        medium: 'bg-amber-100 text-amber-700 border border-amber-200',
                        low: 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                    };
                    return map[urgency] || 'bg-slate-100 text-slate-600';
                },

                statusBadge(status) {
                    var map = {
                        reported: 'bg-blue-100 text-blue-700 border border-blue-200 dark:bg-blue-500/20 dark:text-blue-400',
                        assigned: 'bg-purple-100 text-purple-700 border border-purple-200 dark:bg-purple-500/20 dark:text-purple-400',
                        in_progress: 'bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-500/20 dark:text-amber-400',
                        resolved: 'bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400',
                    };
                    return map[status] || 'bg-slate-100 text-slate-600';
                },

                formatCaseCode(rescue) {
                    return rescue.case_code || 'RSQ-' + String(rescue.id).padStart(5, '0');
                },

                openViewer(id) {
                    this.selectedRescueId = id;
                },

                init() {
                    this.$nextTick(() => {
                        this.initEcho();
                        this.startPolling();
                    });
                },

                startPolling() {
                    var self = this;
                    setInterval(function () {
                        self.refreshDashboard();
                    }, 15000);
                },

                refreshDashboard() {
                    var self = this;
                    var prevDonations = self.totalDonations;
                    fetch('/admin/dashboard/refresh', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(function (response) { return response.json(); })
                    .then(function (data) {
                        self.petsListedThisMonth = data.stats.pets_this_month;
                        self.activeOrgs = data.stats.active_orgs;
                        self.pendingOrgs = data.stats.pending_orgs;
                        self.totalDonations = data.stats.total_donations;
                        self.liveRescues = data.liveRescues;
                        self.pendingOrganizations = data.pendingOrganizations;
                        self.streamActivities = data.recentActivities;

                        if (data.stats.total_donations > prevDonations && prevDonations > 0) {
                            var diff = data.stats.total_donations - prevDonations;
                            if (typeof window.showDonationToast === 'function') {
                                window.showDonationToast(
                                    'A donor',
                                    Number(diff).toLocaleString(),
                                    'just now'
                                );
                            }
                            self.prependActivity(
                                'Donation of NPR ' + Number(diff).toLocaleString() + ' completed'
                            );
                        }
                    })
                    .catch(function () {});
                },

                initEcho() {
                    var self = this;
                    var tryConnect = function () {
                        if (typeof window.Echo === 'undefined') {
                            setTimeout(tryConnect, 300);
                            return;
                        }
                        self.echoStatus = 'connecting';
                        self.setupEchoListeners();
                    };
                    tryConnect();
                },

                setupEchoListeners() {
                    if (this.echoConnected) return;
                    this.echoConnected = true;

                    var self = this;
                    var channel = window.Echo.join('rescue-updates');

                    channel.listen('.NewRescueReportEvent', function (e) {
                        self.petsListedThisMonth++;
                        self.prependActivity(
                            'New rescue case #' + (e.case_code || '?') + ' reported — ' + (e.title || '') + ' from ' + (e.address || 'unknown location')
                        );
                    });

                    channel.listen('.CaseStatusUpdatedEvent', function (e) {
                        self.prependActivity(
                            'Rescue Case #' + (e.case_code || '?') + ' shifted state from ' + (e.old_status || 'unknown') + ' to ' + e.new_status
                        );
                    });

                    channel.listen('.CaseClaimedEvent', function (e) {
                        self.prependActivity(
                            'Rescue Case #' + (e.case_code || '?') + ' claimed by ' + (e.claimed_by || 'an organization')
                        );
                    });

                    channel.listen('.CaseTeamAssignedEvent', function (e) {
                        self.prependActivity(
                            'Rescue Case #' + (e.case_code || '?') + ' — rescue team assigned'
                        );
                    });

                    channel.listen('.OrganizationStatusChangedEvent', function (e) {
                        if (e.old_status === 'pending' && e.new_status === 'active') {
                            self.activeOrgs++;
                            self.pendingOrgs = Math.max(0, self.pendingOrgs - 1);
                            self.removeFromPendingOrgs(e.org_id);
                        } else if (e.old_status === 'pending' && (e.new_status === 'rejected' || e.new_status === 'suspended')) {
                            self.pendingOrgs = Math.max(0, self.pendingOrgs - 1);
                            self.removeFromPendingOrgs(e.org_id);
                        } else if (e.old_status === 'active' && e.new_status === 'suspended') {
                            self.activeOrgs = Math.max(0, self.activeOrgs - 1);
                        }
                        self.prependActivity(
                            'Organization "' + (e.org_name || 'Unknown') + '" status changed from ' + (e.old_status || 'unknown') + ' to ' + e.new_status
                        );
                    });

                    channel.listen('.OrganizationRegisteredEvent', function (e) {
                        self.pendingOrgs++;
                        self.pendingOrganizations.unshift({
                            id: e.org_id,
                            name: e.org_name,
                            registration_number: e.registration_number,
                            address: e.address,
                        });
                        if (self.pendingOrganizations.length > 10) {
                            self.pendingOrganizations.pop();
                        }
                        self.prependActivity(
                            'New organization "' + (e.org_name || 'Unknown') + '" registered — pending review'
                        );
                    });

                    channel.listen('.DonationCompletedEvent', function (e) {
                        self.totalDonations += parseFloat(e.amount);
                        self.prependActivity(
                            'Donation of NPR ' + Number(e.amount).toLocaleString() + ' completed'
                        );

                        if (typeof window.showDonationToast === 'function') {
                            window.showDonationToast(
                                e.donor_name || 'Anonymous',
                                Number(e.amount).toLocaleString(),
                                e.timestamp ? new Date(e.timestamp).toLocaleTimeString() : 'just now'
                            );
                        }
                    });

                    channel.subscribed(function () {
                        self.echoStatus = 'connected';
                    });

                    channel.error(function (error) {
                        self.echoConnected = false;
                        self.echoStatus = 'disconnected';
                        console.warn('WebSocket connection dropped, falling back to polling.');
                    });
                },

                removeFromPendingOrgs(orgId) {
                    this.pendingOrganizations = this.pendingOrganizations.filter(function (o) { return o.id !== orgId; });
                },

                prependActivity(description) {
                    this.streamActivities.unshift({
                        description: description,
                        time: 'just now',
                    });
                    if (this.streamActivities.length > 50) {
                        this.streamActivities.pop();
                    }
                },
            }
        }
    </script>

    <script>
    let fundsChart = null;

    function initFundsChart() {
        const canvas = document.getElementById('donationChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const ctx = canvas.getContext('2d');

        const gradient = ctx.createLinearGradient(0, 0, 0, 380);
        gradient.addColorStop(0, 'rgba(234, 114, 53, 0.15)');
        gradient.addColorStop(1, 'rgba(234, 114, 53, 0)');

        fundsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Donations (NPR)',
                    data: [],
                    borderColor: '#EA7235',
                    borderWidth: 2.5,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#EA7235',
                    fill: true,
                    backgroundColor: gradient,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a2333',
                        titleColor: '#f8fafc',
                        bodyColor: '#f8fafc',
                        borderColor: 'rgba(234, 114, 53, 0.3)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            title: (items) => 'Day: ' + items[0].label,
                            label: (ctx) => 'Total: NPR ' + Number(ctx.parsed.y).toLocaleString(),
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            maxTicksLimit: 8,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            maxTicksLimit: 6,
                            callback: (val) => 'NPR ' + (val / 1000).toFixed(0) + 'k',
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }

    var activeRange = 7;

    function updateChartData() {
        var url;
        if (activeRange) {
            url = '/admin/donation-stats?range=' + activeRange;
        } else {
            var startDate = document.getElementById('customStartDate');
            var endDate   = document.getElementById('customEndDate');
            if (startDate && endDate && startDate.value && endDate.value) {
                url = '/admin/donation-stats?start_date=' + startDate.value + '&end_date=' + endDate.value;
            } else {
                return; // don't fire until both dates are filled
            }
        }

        fetch(url)
            .then(function (response) { return response.json(); })
            .then(function (resData) {
                if (!fundsChart) return;
                fundsChart.data.labels = resData.labels;
                fundsChart.data.datasets[0].data = resData.data;
                fundsChart.update();
            });
    }

    function setDateMaxToday() {
        var today = new Date().toISOString().split('T')[0];
        var startEl = document.getElementById('customStartDate');
        var endEl   = document.getElementById('customEndDate');
        if (startEl) startEl.setAttribute('max', today);
        if (endEl)   endEl.setAttribute('max', today);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initFundsChart();
        setDateMaxToday();

        // Date dropdown toggle
        var toggleBtn = document.getElementById('dateDropdownToggle');
        var dropdownMenu = document.getElementById('dateDropdownMenu');
        var dateDropdown = document.getElementById('dateDropdown');

        if (toggleBtn && dropdownMenu) {
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function (e) {
                if (dateDropdown && !dateDropdown.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }

        // Range option clicks
        document.querySelectorAll('.range-option').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var range = this.getAttribute('data-range');
                var label = document.getElementById('dateRangeLabel');
                var customContainer = document.getElementById('customRangeContainer');

                if (range === 'custom') {
                    activeRange = null;
                    if (customContainer) {
                        customContainer.style.display = 'flex';
                    }
                    if (label) label.textContent = 'Date: Custom Range';
                } else {
                    activeRange = parseInt(range);
                    if (customContainer) customContainer.style.display = 'none';
                    var names = {1: 'Last 24 hours', 7: 'Last 7 days', 28: 'Last 28 days', 90: 'Last 3 months'};
                    if (label) label.textContent = 'Date: ' + (names[activeRange] || 'Last ' + activeRange + ' days');
                }

                if (dropdownMenu) dropdownMenu.classList.add('hidden');
                updateChartData();
            });
        });

        // Custom date input changes
        var cs = document.getElementById('customStartDate');
        var ce = document.getElementById('customEndDate');
        if (cs) cs.addEventListener('change', function () {
            if (activeRange === null) updateChartData();
        });
        if (ce) ce.addEventListener('change', function () {
            if (activeRange === null) updateChartData();
        });

        updateChartData();
    });
    </script>
</x-layouts.admin>
