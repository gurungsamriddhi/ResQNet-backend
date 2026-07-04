<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="analyticsManager()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Analytics & Performance Reports</h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Long-term trends, response efficiency, and regional rescue metrics for our organization.</p>
            </div>
        </div>

        <!-- Top Row - Operational Efficiency Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card 1: Avg Response Time -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Avg Response Time</span>
                    <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/20 text-sky-500 flex items-center justify-center">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">24.5 <span class="text-sm font-bold text-slate-400">Mins</span></span>
                    <span class="flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-1 rounded-lg">
                        <i data-lucide="trending-down" class="w-3.5 h-3.5"></i>
                        <span>-12%</span>
                    </span>
                </div>
                <span class="block text-[10px] text-slate-400 mt-1.5">vs last month</span>
            </div>

            <!-- Card 2: Adoption Success Rate -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Adoption Success Rate</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 flex items-center justify-center" style="color: #5BA44C;">
                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">84<span class="text-sm font-bold text-slate-400">%</span></span>
                </div>
                <span class="block text-[10px] text-slate-400 mt-1.5">Application to placement funnel conversion</span>
            </div>

            <!-- Card 3: Active Volunteers -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Active Volunteers in Field</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/20 text-amber-500 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">12 <span class="text-sm font-bold text-slate-400">On-Duty</span></span>
                    <span class="flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-1 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Live</span>
                    </span>
                </div>
                <span class="block text-[10px] text-slate-400 mt-1.5">Currently deployed across sectors</span>
            </div>
        </div>

        <!-- Middle Section - Dual Column Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Left: Rescue Outcomes Donut -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Rescue Outcomes & Resolution Types</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5">Historical outcome distribution</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                        <i data-lucide="pie-chart" class="w-4 h-4"></i>
                    </div>
                </div>

                <!-- Donut Chart Placeholder -->
                <div class="flex flex-col items-center">
                    <div class="relative w-44 h-44 mb-4">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e2e8f0" stroke-width="3"></circle>
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#5BA44C" stroke-width="3" stroke-dasharray="62 100" stroke-dashoffset="0" stroke-linecap="round"></circle>
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#f59e0b" stroke-width="3" stroke-dasharray="20 100" stroke-dashoffset="-62" stroke-linecap="round"></circle>
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#94a3b8" stroke-width="3" stroke-dasharray="18 100" stroke-dashoffset="-82" stroke-linecap="round"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100">187</span>
                                <span class="block text-[9px] font-bold uppercase tracking-wider text-slate-400">Total Cases</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 text-xs justify-center">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: #5BA44C;"></span>
                            <span class="text-slate-600 dark:text-slate-300">Successfully Resolved <strong class="text-slate-800 dark:text-slate-200">62%</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="text-slate-600 dark:text-slate-300">Cancelled/Returned <strong class="text-slate-800 dark:text-slate-200">20%</strong></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                            <span class="text-slate-600 dark:text-slate-300">False Alarms <strong class="text-slate-800 dark:text-slate-200">18%</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Adoption Funnel -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Adoption Funnel Monthly Progression</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5">Mobile views to final placements</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-indigo-500 flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Mobile Views -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Mobile Views</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">1,200</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 dark:bg-[#111c2a] rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: 100%; background-color: #5BA44C;"></div>
                        </div>
                    </div>
                    <!-- Applications -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Applications</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">156</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 dark:bg-[#111c2a] rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-amber-400" style="width: 13%;"></div>
                        </div>
                    </div>
                    <!-- Final Placements -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">Final Placements</span>
                            <span class="font-bold text-slate-800 dark:text-slate-100">42</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 dark:bg-[#111c2a] rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-indigo-400" style="width: 3.5%;"></div>
                        </div>
                    </div>
                    <!-- Funnel connector arrows -->
                    <div class="flex items-center justify-center gap-6 pt-1 text-[10px] text-slate-400">
                        <span class="flex items-center gap-1"><i data-lucide="arrow-down" class="w-3 h-3"></i> 13% app rate</span>
                        <span class="flex items-center gap-1"><i data-lucide="arrow-down" class="w-3 h-3"></i> 27% placement rate</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section - Pokhara Rescue Hotspot Map -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-admin-border">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Regional Incident Hotspots (Pokhara Sector Grid)</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5">Case density map across key rescue sectors</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/20 text-rose-500 flex items-center justify-center">
                        <i data-lucide="map" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
                <!-- Map Grid Panel -->
                <div class="lg:col-span-3 p-5 bg-slate-50/50 dark:bg-[#111c2a]/20">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Sector: Lakeside -->
                        <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 p-4 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-10" style="background-color: #dc2626;"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Lakeside</span>
                            <span class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mt-1 block">24</span>
                            <span class="text-[10px] text-slate-400">active cases</span>
                            <div class="mt-2 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span class="text-[9px] font-bold text-red-600 uppercase">Critical</span>
                            </div>
                        </div>
                        <!-- Sector: Chipledhunga -->
                        <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 p-4 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-10" style="background-color: #ea580c;"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Chipledhunga</span>
                            <span class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mt-1 block">18</span>
                            <span class="text-[10px] text-slate-400">active cases</span>
                            <div class="mt-2 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                <span class="text-[9px] font-bold text-orange-600 uppercase">Urgent</span>
                            </div>
                        </div>
                        <!-- Sector: Chorepatan -->
                        <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 p-4 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-10" style="background-color: #f59e0b;"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Chorepatan</span>
                            <span class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mt-1 block">11</span>
                            <span class="text-[10px] text-slate-400">active cases</span>
                            <div class="mt-2 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span class="text-[9px] font-bold text-amber-600 uppercase">Moderate</span>
                            </div>
                        </div>
                        <!-- Sector: Nayabazar -->
                        <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 p-4 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-10" style="background-color: #f59e0b;"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Nayabazar</span>
                            <span class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mt-1 block">7</span>
                            <span class="text-[10px] text-slate-400">active cases</span>
                            <div class="mt-2 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                <span class="text-[9px] font-bold text-amber-600 uppercase">Moderate</span>
                            </div>
                        </div>
                    </div>
                    <!-- Map Legend -->
                    <div class="mt-4 flex flex-wrap items-center gap-4 text-[10px] text-slate-500 pt-3 border-t border-admin-border/50">
                        <span class="font-bold uppercase tracking-wider text-slate-400">Severity Legend:</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Critical</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-orange-500"></span> Urgent</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Moderate</span>
                        <span class="ml-auto text-[9px] text-slate-400">Updated real-time from field reports</span>
                    </div>
                </div>
                <!-- Sidebar Summary -->
                <div class="lg:col-span-1 p-5 border-t lg:border-t-0 lg:border-l border-admin-border bg-admin-bg/20">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-4">Sector Summary</span>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Total Active Cases</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">60</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Critical Hotspots</span>
                            <span class="font-bold text-red-600">1</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Teams Deployed</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">4</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Avg Response</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">18.2 min</span>
                        </div>
                        <div class="pt-3 border-t border-admin-border/50">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Priority Sectors</span>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="text-[9px] font-bold text-white px-2 py-0.5 rounded-full bg-red-500">Lakeside</span>
                                <span class="text-[9px] font-bold text-white px-2 py-0.5 rounded-full bg-orange-500">Chipledhunga</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
