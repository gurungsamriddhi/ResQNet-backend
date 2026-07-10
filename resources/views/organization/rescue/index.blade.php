<x-layouts.admin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
     <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
          x-data="rescuesApp()"
          x-effect="const _f = filteredRescues; const _a = activeFilter; if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-5 right-5 z-[10000] max-w-sm bg-white dark:bg-[#111c2a] border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-[#5BA44C]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="check-circle" class="w-4 h-4 text-[#5BA44C]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-admin-textPrimary">Action Completed</p>
                <p class="text-xs text-admin-textSecondary mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-admin-textSecondary"></i>
            </button>
        </div>

        <!-- Assign Team Modal -->
        <div x-show="assignModalOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4">
            <div @click="assignModalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-[#111c2a] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden mx-auto" @click.outside="assignModalOpen = false">
                <div class="bg-brand-navy px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base">Assign Rescue Team</h3>
                            <p class="text-white/60 text-xs" x-text="'Assigning team to ' + (assignTarget?.case_code || 'case')"></p>
                        </div>
                    </div>
                    <button @click="assignModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <p class="text-xs text-admin-textSecondary">Select team members to dispatch:</p>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        <template x-for="(member, idx) in availableTeamMembers" :key="member.id">
                            <label :for="'member-' + idx" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all"
                                    :class="selectedMembers.some(m => m.team_member_id === member.id) ? 'border-brand-orange bg-brand-orange/5 dark:bg-brand-orange/10' : 'border-admin-border hover:border-admin-border/80 bg-white dark:bg-slate-700/50'">
                                <input type="checkbox" :id="'member-' + idx" :value="member.id"
                                       @change="toggleMember(member.id, member.name)"
                                       class="w-4 h-4 text-brand-orange accent-brand-orange flex-shrink-0 rounded">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-brand-navy flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        <span x-text="member.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-admin-textPrimary" x-text="member.name"></p>
                                        <p class="text-[10px] text-admin-textSecondary" x-text="member.email"></p>
                                    </div>
                                </div>
                            </label>
                        </template>
                        <template x-if="availableTeamMembers.length === 0">
                            <div class="text-center py-6 text-admin-textSecondary text-xs">
                                <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No team members available. Invite staff or volunteers first.</p>
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button @click="confirmAssign()" :disabled="selectedMembers.length === 0"
                                class="flex-1 py-2.5 rounded-xl bg-brand-orange text-white text-xs font-bold hover:bg-[#E25338] transition-colors disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                            Assign &amp; Dispatch (<span x-text="selectedMembers.length"></span>)
                        </button>
                        <button @click="assignModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-admin-textPrimary font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                    Rescue Reports
                </h1>
                <p class="text-admin-textSecondary text-sm mt-1">
                    Real-time active rescue cases across the region.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-secondary-50 border border-secondary-500/20 text-xs font-semibold text-secondary-500 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary-500 animate-pulse"></span>
                    Live Updates
                </span>
            </div>
        </div>

        <!-- Map -->
        <div class="relative z-10 bg-brand-cream border border-brand-orange/20 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-brand-orange/10">
                <div>
                    <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight">
                        Active rescue reports
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-1">
                        Live geolocation of incoming rescue requests.
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-xs font-bold text-red-600 dark:text-red-400 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    <span x-text="activeCount"></span> Active
                </span>
            </div>
            <div id="rescue-map" class="w-full h-[200px] sm:h-[300px] md:h-[350px] rounded-xl overflow-hidden shadow-inner"></div>
        </div>

        <!-- Main Content: Rescue Cards + Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 sm:p-6 lg:col-span-2 shadow-sm">
                <!-- Filter tabs -->
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-admin-border/60">
                    <button @click="activeFilter = 'all'"
                            :class="activeFilter === 'all' ? 'bg-[#2563EB] text-white shadow-sm' : 'bg-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                        All Cases
                    </button>
                    <button @click="activeFilter = 'claimed'"
                            :class="activeFilter === 'claimed' ? 'bg-[#2563EB] text-white shadow-sm' : 'bg-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                        Our Claimed Cases
                    </button>
                    <button @click="activeFilter = 'completed'"
                            :class="activeFilter === 'completed' ? 'bg-[#5BA44C] text-white shadow-sm' : 'bg-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all duration-200">
                        Completed Cases
                    </button>
                </div>
                <div class="flex items-center justify-between pb-4 mb-6 border-b border-admin-border/60">
                    <div>
                        <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                            All rescue requests
                        </h3>
                        <p class="text-admin-textSecondary text-xs mt-0.5">
                            Prioritized by severity.
                        </p>
                    </div>
                    <a href="#" class="flex items-center gap-1 text-xs font-bold text-admin-primary hover:text-admin-primaryPress transition-colors">
                        <span>View all</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>

                <div class="flex flex-col gap-4 w-full">
                    <template x-for="rescue in filteredRescues" :key="rescue.id">
                        <div x-data="{ expanded: false }"
                             :style="rescue.status === 'resolved' ? 'order: 9999;' : ''"
                             :class="{ 'bg-gray-50/80 dark:bg-[#111c2a]/40': rescue.status === 'resolved' }"
                             class="bg-admin-surface border border-admin-border rounded-2xl transition-all duration-300 hover:shadow-md hover:border-admin-primary/20 overflow-hidden">

                            <!-- Handled by another org overlay -->
                            <div x-show="rescue.claimedByOther"
                                 x-cloak class="bg-slate-100/90 dark:bg-slate-800/90 px-4 py-2 flex items-center gap-2 border-b border-slate-200 dark:border-slate-700">
                                <i data-lucide="shield-off" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-text="rescue.claimedByName ? 'Handled by ' + rescue.claimedByName : 'Handled by Another Organization'"></span>
                            </div>

                            <div :class="{ 'opacity-60': rescue.status === 'resolved' || rescue.claimedByOther }">
                                <div class="p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <!-- Left: Icon + Content -->
                                    <div class="flex items-start gap-3 sm:gap-4 min-w-0 flex-1">
                                        <!-- Status + Urgency Icon -->
                                        <div class="flex-shrink-0 mt-0.5 relative">
                                            <template x-if="rescue.urgency === 'critical'">
                                                <div class="relative">
                                                    <span class="absolute inset-0 rounded-full bg-red-500 opacity-25" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-ping' : ''"></span>
                                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 flex items-center justify-center shadow-sm" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-pulse' : ''">
                                                        <i data-lucide="bell-ring" class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 fill-red-200"></i>
                                                    </div>
                                                    <template x-if="rescue.status === 'resolved'">
                                                        <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center">
                                                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="rescue.urgency === 'high'">
                                                <div class="relative">
                                                    <span class="absolute inset-0 rounded-full bg-orange-500 opacity-25" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-ping' : ''"></span>
                                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 dark:bg-orange-500/20 text-brand-orange dark:text-orange-400 border border-orange-200 dark:border-orange-500/30 flex items-center justify-center shadow-sm" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-pulse' : ''">
                                                        <i data-lucide="alert-triangle" class="w-4 h-4 sm:w-5 sm:h-5 text-brand-orange"></i>
                                                    </div>
                                                    <template x-if="rescue.status === 'resolved'">
                                                        <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center">
                                                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="rescue.urgency !== 'critical' && rescue.urgency !== 'high'">
                                                <div class="relative">
                                                    <span class="absolute inset-0 rounded-full bg-yellow-500 opacity-25" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-ping' : ''"></span>
                                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/30 flex items-center justify-center shadow-sm" :class="rescue.status === 'reported' && !rescue.claimedByOther ? 'animate-pulse' : ''">
                                                        <i data-lucide="bell" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500"></i>
                                                    </div>
                                                    <template x-if="rescue.status === 'resolved'">
                                                        <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center">
                                                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Title & meta -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="text-admin-textPrimary font-bold text-sm sm:text-base tracking-tight truncate" x-text="rescue.title"></h4>
                                                <span :class="badgeClasses(rescue)" x-text="displayStatus(rescue)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase"></span>
                                            </div>
                                            <p class="text-xs text-admin-textSecondary mt-1.5 leading-relaxed line-clamp-2" x-text="rescue.description"></p>
                                            <div class="flex items-center gap-2 sm:gap-3.5 mt-2 text-xs text-admin-textSecondary flex-wrap">
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                                    <span x-text="rescue.address || 'Location not specified'"></span>
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                                    <span x-text="timeAgo(rescue.created_at)"></span>
                                                </span>
                                                <span class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase" x-text="rescue.case_code"></span>
                                            </div>
                                            <!-- Assigned team badge -->
                                            <div x-show="(rescue.status === 'assigned' || rescue.status === 'in_progress' || rescue.status === 'resolved') && rescue.assignments?.length > 0"
                                                 x-cloak class="mt-2 flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-500/20 border border-blue-100 dark:border-blue-500/30 text-[10px] font-bold text-blue-700 dark:text-blue-400">
                                                    <i data-lucide="users" class="w-3 h-3"></i>
                                                    <span x-text="rescue.assignments?.map(a => a.name || a.team_member?.name).filter(Boolean).join(', ')"></span>
                                                </span>
                                                <span class="text-[10px] text-admin-textSecondary" x-text="rescue.status === 'resolved' ? 'completed' : 'dispatched'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Action buttons -->
                                    <div class="flex-shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                                        <div x-show="rescue.status === 'reported' && rescue.organization_id === null"
                                             x-cloak class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                            <button @click="acceptCaseFromCard(rescue.id)" :disabled="isAccepting"
                                                    class="flex-1 sm:flex-none bg-brand-orange hover:bg-[#E25338] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5 disabled:opacity-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                                <span x-text="isAccepting ? 'Claiming...' : 'Help Now'"></span>
                                            </button>
                                        </div>

                                        <div x-show="rescue.status === 'assigned' && !rescue.claimedByOther"
                                             x-cloak class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                            <button @click="openAssignModal({id: rescue.id, case_code: rescue.case_code, title: rescue.title})" class="flex-1 sm:flex-none bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                Assign Team
                                            </button>
                                        </div>

                                        <div x-show="rescue.status === 'in_progress' && !rescue.claimedByOther"
                                             x-cloak class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                            <button @click="updateCaseStatus(rescue.id, 'resolved')" class="flex-1 sm:flex-none bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                                Mark Complete
                                            </button>
                                        </div>

                                        <span x-show="rescue.status === 'resolved'"
                                              x-cloak class="w-full sm:w-auto bg-[#5BA44C] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm inline-flex items-center justify-center gap-1.5 cursor-default">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                            Resolved
                                        </span>

                                        <span x-show="rescue.claimedByOther"
                                              x-cloak class="w-full sm:w-auto border border-slate-200 text-slate-400 dark:text-slate-300 font-medium px-4 py-2 rounded-lg text-xs inline-flex items-center justify-center gap-1.5 cursor-default">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><path d="M19.69 14a6.9 6.9 0 0 0 .31-2V5l-8-3-3.16 1.18"></path><path d="m4.73 4.73-1.42-1.42"></path><path d="M2 2l20 20"></path><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path></svg>
                                            Claimed by another org
                                        </span>

                                        <button @click="expanded = !expanded" class="text-xs text-slate-400 dark:text-slate-300 hover:text-brand-orange transition-colors cursor-pointer flex items-center justify-center gap-1 whitespace-nowrap flex-shrink-0 py-2 sm:py-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            <span x-text="expanded ? 'Hide Info' : 'View Info'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Expandable Detail Panel -->
                            <div x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border-t border-admin-border/60 bg-gray-50/50 dark:bg-[#111c2a]/30">
                                <div class="p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                                    <div class="bg-white dark:bg-[#111c2a] border border-admin-border rounded-xl p-4 space-y-4">
                                        <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                            Case Details
                                        </h4>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Description</p>
                                            <p class="text-sm text-admin-textPrimary mt-0.5 leading-relaxed" x-text="rescue.description"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Location</p>
                                            <p class="text-sm font-medium text-admin-textPrimary mt-0.5 inline-flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                <span x-text="rescue.address || 'Not specified'"></span>
                                            </p>
                                            <a :href="'https://www.google.com/maps?q=' + rescue.latitude + ',' + rescue.longitude" target="_blank" class="block mt-1 text-[10px] font-bold text-brand-orange hover:underline">
                                                <i data-lucide="navigation" class="w-3 h-3 inline"></i> Get directions
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Urgency</p>
                                            <p class="text-sm font-bold mt-0.5" :class="rescue.urgency === 'critical' ? 'text-red-600' : rescue.urgency === 'high' ? 'text-brand-orange' : 'text-yellow-600'" x-text="rescue.urgency.toUpperCase()"></p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Case Code</p>
                                                <p class="text-sm font-mono font-bold text-admin-textPrimary mt-0.5" x-text="rescue.case_code"></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Reported</p>
                                                <p class="text-sm font-medium text-admin-textPrimary mt-0.5" x-text="timeAgo(rescue.created_at)"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-[#111c2a] border border-admin-border rounded-xl p-4 space-y-4">
                                        <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                            Reporter Information
                                        </h4>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Reported By</p>
                                            <p class="text-sm font-bold text-admin-textPrimary mt-0.5" x-text="rescue.user?.name || 'Anonymous'"></p>
                                        </div>

                                        <hr class="border-admin-border/60">

                                        <div>
                                            <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                                <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                                Assigned Team
                                            </h4>
                                            <template x-if="(rescue.assignments?.length || 0) > 0">
                                                <div class="mt-3 space-y-2">
                                                    <template x-for="member in (rescue.assignments || [])" :key="member.id">
                                                        <div class="flex items-center gap-3 p-2.5 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                                                            <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                                                <span x-text="(member.name || member.team_member?.name || '?').charAt(0)"></span>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <p class="text-xs font-bold text-admin-textPrimary" x-text="member.name || member.team_member?.name || 'Unknown'"></p>
                                                                <p class="text-[10px] text-admin-textSecondary" x-text="member.role || 'Rescuer'"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="(rescue.assignments?.length || 0) === 0">
                                                <div class="mt-3 p-3 rounded-lg bg-slate-50 dark:bg-[#111c2a]/30 border border-dashed border-slate-200 dark:border-slate-700/40 text-center">
                                                    <i data-lucide="users" class="w-5 h-5 mx-auto text-slate-300 mb-1"></i>
                                                    <p class="text-[10px] text-slate-400 dark:text-slate-300">No team assigned yet</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-[#111c2a] border border-admin-border rounded-xl p-4">
                                        <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5 mb-4">
                                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                            Status Timeline
                                        </h4>
                                        <div class="space-y-0">
                                            <div class="flex items-start gap-3 relative pb-7">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-3 h-3 rounded-full bg-secondary-500 border-2 border-white ring-2 ring-secondary-500 z-10 flex-shrink-0"></div>
                                                    <div class="w-0.5 bg-secondary-200 absolute top-3 bottom-0"></div>
                                                </div>
                                                <div class="pt-0.5">
                                                    <p class="text-xs font-bold text-admin-textPrimary">Report Submitted</p>
                                                    <p class="text-[10px] text-admin-textSecondary mt-0.5">Case registered & queued</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3 relative pb-7">
                                                <div class="flex flex-col items-center">
                                                    <div :class="rescue.status !== 'reported' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 dark:bg-slate-600 ring-gray-200 dark:ring-slate-600'" class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800 ring-2 z-10 flex-shrink-0"></div>
                                                    <div :class="rescue.status === 'resolved' ? 'bg-secondary-200 dark:bg-secondary-800' : 'bg-gray-100 dark:bg-slate-700'" class="w-0.5 absolute top-3 bottom-0"></div>
                                                </div>
                                                <div class="pt-0.5">
                                                    <p :class="rescue.status !== 'reported' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Case Accepted</p>
                                                    <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="rescue.status !== 'reported' ? 'Organization assigned' : 'Pending acceptance'"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3 relative pb-7">
                                                <div class="flex flex-col items-center">
                                                    <div :class="rescue.status === 'in_progress' || rescue.status === 'resolved' ? 'bg-indigo-500 ring-indigo-500' : 'bg-gray-200 dark:bg-slate-600 ring-gray-200 dark:ring-slate-600'" class="w-3 h-3 rounded-full border-2 border-white dark:border-slate-800 ring-2 z-10 flex-shrink-0"></div>
                                                    <div :class="rescue.status === 'resolved' ? 'bg-secondary-200 dark:bg-secondary-800' : 'bg-gray-100 dark:bg-slate-700'" class="w-0.5 absolute top-3 bottom-0"></div>
                                                </div>
                                                <div class="pt-0.5">
                                                    <p :class="rescue.status === 'in_progress' || rescue.status === 'resolved' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Rescue In Progress</p>
                                                    <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="rescue.status === 'in_progress' || rescue.status === 'resolved' ? 'Team on-site' : 'Not yet started'"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3 relative">
                                                <div class="flex flex-col items-center">
                                                    <div :class="rescue.status === 'resolved' ? 'bg-[#5BA44C] ring-[#5BA44C]' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                                </div>
                                                <div class="pt-0.5">
                                                    <p :class="rescue.status === 'resolved' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Rescue Completed</p>
                                                    <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="rescue.status === 'resolved' ? 'Case resolved & closed' : 'Not yet completed'"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <template x-if="rescue.status === 'resolved'">
                                            <div class="mt-4 pt-3 border-t border-admin-border/50 flex items-center gap-2 text-[10px] text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg px-3 py-2">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                                <span class="font-semibold">Resolved <span x-text="timeAgo(rescue.created_at)"></span></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty state -->
                    <template x-if="filteredRescues.length === 0">
                        <div class="bg-admin-surface border border-dashed border-admin-border rounded-2xl p-12 text-center">
                            <i data-lucide="shield-alert" class="w-12 h-12 mx-auto text-slate-300 mb-3"></i>
                            <h3 class="text-base font-bold text-admin-textPrimary">No rescue cases</h3>
                            <p class="text-xs text-admin-textSecondary mt-1">All clear! No rescue reports at the moment.</p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Sidebar: Rescue Summary -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 sm:p-6 shadow-sm">
                <div class="border-b border-admin-border/60 pb-4 mb-6">
                    <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                        Rescue Summary
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-0.5">
                        Case breakdown by priority.
                    </p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-xs font-bold text-red-700 dark:text-red-400">Critical</span>
                        </div>
                        <span class="text-xs font-extrabold text-red-700 dark:text-red-400" x-text="stats.critical"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-orange-50 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-brand-orange"></span>
                            <span class="text-xs font-bold text-orange-700 dark:text-orange-400">High</span>
                        </div>
                        <span class="text-xs font-extrabold text-orange-700 dark:text-orange-400" x-text="stats.high"></span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-100 dark:border-yellow-500/20">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                            <span class="text-xs font-bold text-yellow-700 dark:text-yellow-400">Medium / Low</span>
                        </div>
                        <span class="text-xs font-extrabold text-yellow-700 dark:text-yellow-400" x-text="stats.medium"></span>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-admin-border/60">
                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider mb-3">Quick Stats</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Our Cases (Help Now)</span>
                            <span class="font-bold text-[#2563EB]" x-text="claimedCount"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Total cases</span>
                            <span class="font-bold text-admin-textPrimary" x-text="rescues.length"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Active (open)</span>
                            <span class="font-bold text-admin-textPrimary" x-text="activeCount"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Resolved</span>
                            <span class="font-bold text-admin-textPrimary" x-text="stats.resolved"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .leaflet-marker-critical .pulse-ring {
            position: absolute;
            top: 50%; left: 50%;
            width: 44px; height: 44px;
            margin: -22px 0 0 -22px;
            border-radius: 50%;
            background: rgba(220, 38, 38, 0.3);
            animation: leaflet-pulse 1.5s ease-in-out infinite;
        }
        @keyframes leaflet-pulse {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>

    
<script>
        function rescuesApp() {
            return {
                rescues: @json($rescues->toArray() ?? []).map(r => ({
                    ...r,
                    claimedByOther: r.organization_id && r.organization_id !== {{ auth()->user()->organization_id ?? 'null' }},
                    claimedByName: r.organization ? r.organization.name : 'Another Organization'
                })),
                teamMembers: @json($teamMembers->toArray() ?? []),
                assignModalOpen: false,
                assignTarget: null,
                selectedMembers: [],
                toastMessage: '',
                toastVisible: false,
                toastTimeout: null,
                isAccepting: false,
                activeFilter: 'all',
                orgId: {{ auth()->user()->organization_id ?? 'null' }},
                
                // NEW: Store the Leaflet map instance here so we can add markers to it later
                mapInstance: null, 

                get filteredRescues() {
                    if (this.activeFilter === 'claimed') {
                        return this.rescues.filter(r => r.organization_id === this.orgId && r.status !== 'resolved');
                    }
                    if (this.activeFilter === 'completed') {
                        return this.rescues.filter(r => r.organization_id === this.orgId && r.status === 'resolved');
                    }
                    return this.rescues;
                },

                get availableTeamMembers() {
                    return this.teamMembers;
                },

                get claimedCount() {
                    return this.rescues.filter(r => r.organization_id === this.orgId).length;
                },

                get activeCount() {
                    return this.rescues.filter(r => r.status === 'reported' || r.status === 'assigned' || r.status === 'in_progress').length;
                },

                get stats() {
                    const critical = this.rescues.filter(r => r.urgency === 'critical' && r.status !== 'resolved').length;
                    const high = this.rescues.filter(r => r.urgency === 'high' && r.status !== 'resolved').length;
                    const medium = this.rescues.filter(r => (r.urgency === 'medium' || r.urgency === 'low') && r.status !== 'resolved').length;
                    const resolved = this.rescues.filter(r => r.status === 'resolved').length;
                    return { critical, high, medium, resolved };
                },

                showToast(message) {
                    this.toastMessage = message;
                    this.toastVisible = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 4000);
                },

                toggleMember(memberId, userName) {
                    const idx = this.selectedMembers.findIndex(m => m.team_member_id === memberId);
                    if (idx > -1) {
                        this.selectedMembers.splice(idx, 1);
                    } else {
                        this.selectedMembers.push({ team_member_id: memberId, role: 'rescuer' });
                    }
                },

                openAssignModal(card) {
                    this.assignTarget = card;
                    this.selectedMembers = [];
                    this.assignModalOpen = true;
                },

                async confirmAssign() {
                    if (this.selectedMembers.length === 0 || !this.assignTarget) return;
                    const targetId = this.assignTarget.id;
                    try {
                        const response = await fetch('{{ route("organization.rescues.assign-team", ["report" => "__ID__"], false) }}'.replace('__ID__', targetId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ team_members: this.selectedMembers }),
                        });
                        const data = await response.json().catch(() => ({}));
                        if (response.ok) {
                            const teamData = data.data?.assignments || this.selectedMembers.map(m => ({
                                id: m.team_member_id,
                                name: this.teamMembers.find(t => t.id === m.team_member_id)?.name || 'Unknown',
                                role: m.role,
                            }));
                            this.rescues = this.rescues.map(r => {
                                if (r.id !== targetId) return r;
                                return {
                                    ...r,
                                    status: data.data?.status || 'in_progress',
                                    assigned_at: new Date().toISOString(),
                                    assignments: teamData,
                                };
                            });
                            this.assignModalOpen = false;
                            this.assignTarget = null;
                            this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                            this.showToast(data.message || 'Team assigned successfully.');
                        } else {
                            this.showToast('Error: ' + (data.message || 'Failed to assign team'));
                        }
                    } catch (e) {
                        this.showToast('Network error. Please try again.');
                    }
                },

                async acceptCaseFromCard(cardId) {
                    if (this.isAccepting) return;
                    this.isAccepting = true;
                    try {
                        const response = await fetch('{{ route("organization.rescues.accept", ["report" => "__ID__"], false) }}'.replace('__ID__', cardId), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        });
                        const data = await response.json().catch(() => ({}));
                        if (response.ok) {
                            this.rescues = this.rescues.map(r => {
                                if (r.id !== cardId) return r;
                                return {
                                    ...r,
                                    status: data.data?.status || 'assigned',
                                    organization_id: {{ auth()->user()->organization_id ?? 'null' }},
                                    assigned_at: new Date().toISOString(),
                                };
                            });
                            this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                            const updated = this.rescues.find(r => r.id === cardId);
                            this.openAssignModal({
                                id: cardId,
                                case_code: updated?.case_code ?? '',
                                title: updated?.title ?? '',
                            });
                            this.showToast('Case accepted! Now assign your team.');
                        } else if (response.status === 409) {
                            this.rescues = this.rescues.map(r => {
                                if (r.id !== cardId) return r;
                                return { ...r, claimedByOther: true };
                            });
                            this.showToast('This case was just claimed by another organization.');
                        } else {
                            this.showToast('Error: ' + (data.message || 'Failed to accept case'));
                        }
                    } catch (e) {
                        this.showToast('Network error. Please try again.');
                    } finally {
                        this.isAccepting = false;
                        this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                    }
                },

                displayStatus(rescue) {
                    if (rescue.claimedByOther) return 'Claimed';
                    const map = { reported: 'REPORTED', assigned: 'ASSIGNED', in_progress: 'IN PROGRESS', resolved: 'RESOLVED' };
                    return map[rescue.status] || rescue.status?.toUpperCase();
                },

                badgeClasses(rescue) {
                    if (rescue.claimedByOther) return 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300';
                    const s = rescue.status;
                    if (s === 'reported' && rescue.urgency === 'critical') return 'bg-admin-btnRed text-white';
                    if (s === 'reported' && rescue.urgency === 'high') return 'bg-brand-orange/20 text-brand-orange border border-brand-orange/30';
                    if (s === 'reported') return 'bg-yellow-100 dark:bg-yellow-500/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200';
                    if (s === 'assigned') return 'bg-[#2563EB] text-white';
                    if (s === 'in_progress') return 'bg-indigo-500 text-white';
                    if (s === 'resolved') return 'bg-[#5BA44C] text-white';
                    return 'bg-gray-100 text-gray-600';
                },

                timeAgo(date) {
                    if (!date) return '';
                    const diff = Date.now() - new Date(date).getTime();
                    const mins = Math.floor(diff / 60000);
                    if (mins < 1) return 'Just now';
                    if (mins < 60) return mins + ' min ago';
                    const hrs = Math.floor(mins / 60);
                    if (hrs < 24) return hrs + 'h ago';
                    const days = Math.floor(hrs / 24);
                    return days + 'd ago';
                },

                async updateCaseStatus(reportId, newStatus) {
                    try {
                        const response = await fetch('{{ route("organization.rescues.update", ["report" => "__ID__"], false) }}'.replace('__ID__', reportId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ status: newStatus }),
                        });
                        const data = await response.json().catch(() => ({}));
                        if (response.ok) {
                            this.rescues = this.rescues.map(r => {
                                if (r.id !== reportId) return r;
                                return { ...r, status: newStatus };
                            });
                            this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                            this.showToast(data.message);
                        } else {
                            this.showToast(data.message || 'Error updating status');
                        }
                    } catch (e) {
                        this.showToast('Network error');
                    }
                },

                init() {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });

                    if (typeof Echo !== 'undefined') {

                        // IMPROVEMENT 1: Handle WebSocket connection errors
                        if (Echo.connector && Echo.connector.pusher) {
                            Echo.connector.pusher.connection.bind('error', (err) => {
                                console.error('WebSocket Error:', err);
                                this.showToast('Live connection error. Realtime updates may be delayed.');
                            });
                        }

                        // IMPROVEMENT 2: Leave channel cleanly on page unload
                        window.addEventListener('beforeunload', () => {
                            Echo.leave('rescue-updates');
                        });

                        Echo.join('rescue-updates')
                            .here(() => {})
                            .listen('.NewRescueReportEvent', (e) => {
                                if (!this.rescues.some(r => r.id === e.rescue_id)) {
                                    // Create the new rescue object first
                                    const newRescue = {
                                        id: e.rescue_id,
                                        case_code: e.case_code,
                                        title: e.title,
                                        urgency: e.urgency,
                                        address: e.address,
                                        latitude: e.latitude,
                                        longitude: e.longitude,
                                        status: e.status,
                                        created_at: e.created_at,
                                        user: null,
                                        assignments: [],
                                        organization_id: null,
                                    };
                                    
                                    // Add to Alpine state
                                    this.rescues = [newRescue, ...this.rescues];
                                    this.showToast('New rescue case reported: ' + e.title);
                                    
                                    // IMPROVEMENT 3: Add marker dynamically instead of re-running initMap
                                    this.addMarkerToMap(newRescue);
                                }
                            })
                            .listen('.CaseClaimedEvent', (e) => {
                                const orgId = {{ auth()->user()->organization_id ?? 'null' }};
                                this.rescues = this.rescues.map(r => {
                                    if (r.id !== e.rescue_id) return r;
                                    return {
                                        ...r,
                                        claimedByOther: e.organization_id !== orgId ? true : r.claimedByOther,
                                        claimedByName: e.claimed_by || 'Another Organization',
                                        organization_id: e.organization_id,
                                        status: e.organization_id === orgId ? 'assigned' : r.status,
                                    };
                                });
                            })
                            .listen('.CaseTeamAssignedEvent', (e) => {
                                const orgId = {{ auth()->user()->organization_id ?? 'null' }};
                                this.rescues = this.rescues.map(r => {
                                    if (r.id !== e.rescue_id || r.organization_id !== orgId) return r;
                                    return {
                                        ...r,
                                        status: e.status,
                                        assignments: e.team_members || [],
                                    };
                                });
                            })
                            .listen('.CaseStatusUpdatedEvent', (e) => {
                                const orgId = {{ auth()->user()->organization_id ?? 'null' }};
                                this.rescues = this.rescues.map(r => {
                                    if (r.id !== e.rescue_id || r.organization_id !== orgId) return r;
                                    return { ...r, status: e.new_status };
                                });
                            });
                    }

                    this.$nextTick(() => this.initMap());
                },

                // NEW FUNCTION: Extracted marker creation logic so it can be reused
                addMarkerToMap(r) {
                    if (!this.mapInstance || !r.latitude || !r.longitude) return;
                    
                    const lat = parseFloat(r.latitude);
                    const lng = parseFloat(r.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;

                    let icon, popupContent;
                    const isCritical = r.urgency === 'critical' && r.status === 'reported';

                    if (isCritical) {
                        icon = L.divIcon({
                            html: '<div class="leaflet-marker-critical" style="width:28px;height:28px;position:relative"><div class="pulse-ring"></div><div style="position:absolute;inset:3px;border-radius:50%;background:#DC2626;border:3px solid #991B1B"></div></div>',
                            className: '',
                            iconSize: [28, 28],
                            iconAnchor: [14, 14],
                        });
                        popupContent = `<div style="background:#0F1A2E;padding:12px 16px;border-radius:8px;min-width:200px;"><p style="color:#fff;font-size:14px;font-weight:700;margin:0;">&#9888;&#65039; ${r.title}</p><p style="color:#94A3B8;font-size:12px;margin:6px 0 0;">${r.address || ''}</p></div>`;
                    } else {
                        const color = r.urgency === 'high' ? '#EA7235' : '#FCD34D';
                        icon = L.divIcon({
                            html: `<div style="width:20px;height:20px;border-radius:50%;background:${color};border:3px solid #C2410C"></div>`,
                            className: '',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10],
                        });
                        popupContent = `<div style="padding:8px 12px;"><p style="font-size:13px;font-weight:600;margin:0;">${r.title}</p></div>`;
                    }

                    const marker = L.marker([lat, lng], { icon });
                    marker.bindPopup(popupContent);
                    marker.addTo(this.mapInstance); // Add to the tracked map instance
                },

                initMap() {

                    // --- ADD THESE 4 LINES ---
                    if (typeof L === 'undefined') {
                        setTimeout(() => this.initMap(), 100); // Check again in 100ms
                        return;
                    }
                    //
                    const mapElement = document.getElementById('rescue-map');
                    if (!mapElement || this._mapInitialized) return;
                    this._mapInitialized = true;

                    // Assign to this.mapInstance instead of a local const 'map'
                    this.mapInstance = L.map(mapElement, {
                        center: [28.2096, 83.9856],
                        zoom: 13,
                        zoomControl: false,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap',
                        maxZoom: 19,
                    }).addTo(this.mapInstance);

                    // Loop through initial rescues and use our new function
                    this.rescues.forEach(r => {
                        this.addMarkerToMap(r);
                    });

                    setTimeout(() => this.mapInstance.invalidateSize(), 300);
                },
            }
        }
    </script>
</x-layouts.admin>