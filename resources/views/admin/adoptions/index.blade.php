<x-layouts.admin>
    @php
        $liveListingsJson = isset($liveListings) ? $liveListings->toJson() : '[]';
        $applicationsJson = isset($applications) ? $applications->toJson() : '[]';
        $successfulRehomingsCount = $successfulRehomings ?? 0;
    @endphp
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="adoptionManager({{ $liveListingsJson }}, {{ $applicationsJson }}, {{ $successfulRehomingsCount }})"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Adoption Management</h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Publish shelter pets to the mobile app and review adoption applications</p>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-sky-50 dark:bg-sky-950/20 text-sky-500 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="dog" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-2xl font-bold text-slate-800 dark:text-slate-100" x-text="liveListings.length"></span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300">Total Listed Pets</span>
                </div>
            </div>
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/20 text-amber-500 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-2xl font-bold text-slate-800 dark:text-slate-100" x-text="applications.length"></span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300">Pending Applications</span>
                </div>
            </div>
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center flex-shrink-0" style="color: #5BA44C;">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-2xl font-bold text-slate-800 dark:text-slate-100" x-text="successfulRehomings"></span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300">Successful Rehomings</span>
                </div>
            </div>
        </div>

        <!-- Tab Toggle -->
        <div class="flex items-center gap-6 border-b border-admin-border pb-0">
            <button @click="activeTab = 'listings'"
                    class="pb-3 text-xs font-bold uppercase tracking-widest transition-all duration-200 border-b-2 -mb-px"
                    :class="activeTab === 'listings' ? 'text-brand-orange border-brand-orange' : 'text-slate-400 dark:text-slate-300 border-transparent hover:text-slate-600 dark:text-slate-300'">
                <span>Live Mobile Listings</span>
            </button>
            <button @click="activeTab = 'applications'"
                    class="pb-3 text-xs font-bold uppercase tracking-widest transition-all duration-200 border-b-2 -mb-px flex items-center gap-2"
                    :class="activeTab === 'applications' ? 'text-brand-orange border-brand-orange' : 'text-slate-400 dark:text-slate-300 border-transparent hover:text-slate-600 dark:text-slate-300'">
                <span>Adoption Applications</span>
                <span class="bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 px-1.5 py-0.5 rounded-full text-[9px] font-extrabold" x-text="applications.length"></span>
            </button>
        </div>

        <!-- ============ TAB 1: LIVE MOBILE LISTINGS ============ -->
        <div x-show="activeTab === 'listings'">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <template x-for="(pet, idx) in liveListings" :key="pet.name + idx">
                    <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden">
                        <!-- Pet Image Banner -->
                        <template x-if="pet.primary_image">
                            <div class="h-36 overflow-hidden bg-slate-100 dark:bg-slate-800">
                                <img :src="pet.primary_image" :alt="pet.name"
                                     class="w-full h-full object-cover">
                            </div>
                        </template>
                        <template x-if="!pet.primary_image">
                            <div class="h-36 overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-bold text-white shadow-lg" :class="pet.color">
                                    <span x-text="pet.name.charAt(0)"></span>
                                </div>
                            </div>
                        </template>

                        <div class="p-4 space-y-3">
                            <!-- Pet Avatar + Name -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white uppercase shadow-sm flex-shrink-0"
                                         :class="pet.color">
                                        <span x-text="pet.name.charAt(0)"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-sm text-slate-800 dark:text-slate-100 block truncate" x-text="pet.name"></span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-300 truncate block" x-text="pet.breed || 'No breed specified'"></span>
                                    </div>
                                </div>
                                <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-1 rounded-full border border-emerald-200/50 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Live
                                </span>
                            </div>

                            <!-- Traits chips -->
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="trait in pet.traits" :key="trait">
                                    <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-[#111c2a] px-2 py-0.5 rounded-full border border-slate-200/50">
                                        <span x-text="trait"></span>
                                    </span>
                                </template>
                            </div>

                            <!-- Action Row -->
                            <div class="flex items-center justify-between pt-1 border-t border-admin-border/50">
                                <button @click="togglePetDetails(pet.name)"
                                        class="text-xs font-semibold text-brand-orange hover:text-brand-orange/80 transition-colors flex items-center gap-1">
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200"
                                       :class="expandedPet === pet.name ? 'rotate-180' : ''"></i>
                                    <span>View Details</span>
                                </button>
                                <button @click="openDelistModal(pet)"
                                        class="border border-amber-200 dark:border-amber-700/50 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-all duration-200">
                                    <i data-lucide="toggle-left" class="w-3.5 h-3.5"></i>
                                    <span>Delist from App</span>
                                </button>
                            </div>
                        </div>

                        <!-- Expandable Details Drawer -->
                        <div x-show="expandedPet === pet.name"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-[800px]"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 max-h-[800px]"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="border-t border-admin-border bg-gradient-to-b from-admin-bg/40 to-transparent"
                             style="display: none;">
                            <div class="p-4 space-y-4">
                                <!-- Left: Mobile Pet Card Summary -->
                                <div>
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3 flex items-center gap-1.5">
                                        <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                                        Mobile Pet Card Summary
                                    </span>
                                    <div class="space-y-2.5 text-xs">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500 dark:text-slate-300">Bio</span>
                                            <span class="font-semibold text-slate-700 dark:text-slate-300 text-right max-w-[200px]" x-text="pet.bio"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500 dark:text-slate-300">Age</span>
                                            <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="pet.age"></span>
                                        </div>
                                        <div class="pt-2 border-t border-admin-border/50">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300 block mb-2">Health Checklist</span>
                                            <div class="flex flex-wrap gap-1.5">
                                                <template x-for="(check, cIdx) in pet.healthChecklist" :key="cIdx">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                           :class="check.done ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-500/30' : 'bg-slate-100 dark:bg-[#111c2a] text-slate-400 dark:text-slate-300 border border-slate-200/50 dark:border-slate-700/40'">
                                                        <i data-lucide="check" class="w-3 h-3" x-show="check.done"></i>
                                                        <i data-lucide="x" class="w-3 h-3" x-show="!check.done"></i>
                                                        <span x-text="check.label"></span>
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Mobile App Org Data Footer -->
                                <div class="pt-3 border-t border-admin-border/50">
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3 flex items-center gap-1.5">
                                        <i data-lucide="building" class="w-3.5 h-3.5"></i>
                                        Mobile App Org Data Footer
                                    </span>
                                    <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 p-3.5 space-y-2 shadow-sm mt-1">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg bg-brand-orange/10 text-brand-orange flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="building-2" class="w-4 h-4"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200" x-text="pet.org_name || 'Animal Shelter'"></span>
                                                <span class="block text-[10px] text-slate-400 dark:text-slate-300">Listed by Partner</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mt-2 bg-slate-50 dark:bg-slate-800 p-2 rounded-lg border border-slate-100 dark:border-slate-700">
                                            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                                            <span>Platform Verified Organization</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty state -->
            <div x-show="liveListings.length === 0"
                 class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-8 text-center mt-4">
                <i data-lucide="package" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">No live listings</p>
                <p class="text-xs text-slate-400 dark:text-slate-300 mt-1">Publish pets to make them visible on the mobile app.</p>
            </div>
        </div>

        <!-- ============ TAB 2: ADOPTION APPLICATIONS ============ -->
        <div x-show="activeTab === 'applications'" style="display: none;">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden">
                <!-- Grid Header -->
                <div class="hidden md:grid grid-cols-[1fr_140px_120px_120px_360px] gap-0 bg-slate-50 dark:bg-[#111c2a] border-b border-admin-border text-slate-800 dark:text-slate-200 font-semibold text-xs uppercase tracking-wider">
                    <div class="px-6 py-4">Applicant</div>
                    <div class="px-4 py-4">Target Pet</div>
                    <div class="px-4 py-4">Date</div>
                    <div class="px-4 py-4">Status</div>
                    <div class="px-4 py-4 text-right">Actions</div>
                </div>
                <!-- Grid Rows -->
                <div class="divide-y divide-admin-border/60">
                    <template x-for="app in applications" :key="app.email + app.pet">
                        <div>
                            <div class="grid grid-cols-1 md:grid-cols-[1fr_140px_120px_120px_360px] gap-2 md:gap-0 items-center px-4 md:px-6 py-3 md:py-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <!-- Applicant -->
                                <div class="flex items-center gap-3 min-w-0 col-span-1 md:col-auto">
                                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm flex-shrink-0"
                                         x-text="app.name.charAt(0)">
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-800 dark:text-slate-100 block text-xs truncate" x-text="app.name"></span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-300 truncate block" x-text="app.email"></span>
                                    </div>
                                </div>
                                <!-- Target Pet -->
                                <div class="md:text-center pl-12 md:pl-0 -mt-1 md:mt-0">
                                    <span class="md:hidden text-[10px] font-bold uppercase text-slate-400 dark:text-slate-300 mr-2">Pet:</span>
                                    <span class="font-semibold text-xs text-slate-700 dark:text-slate-300" x-text="app.pet"></span>
                                </div>
                                <!-- Date -->
                                <div class="md:text-center pl-12 md:pl-0">
                                    <span class="md:hidden text-[10px] font-bold uppercase text-slate-400 dark:text-slate-300 mr-2">Date:</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-300" x-text="app.date"></span>
                                </div>
                                <!-- Status -->
                                <div class="md:text-center pl-12 md:pl-0">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase border shadow-sm"
                                           :class="app.status === 'Pending' ? 'bg-amber-50 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border-amber-200/50 dark:border-amber-500/30' : app.status === 'Approved' ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-200/50 dark:border-emerald-500/30' : 'bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border-rose-200/50 dark:border-rose-500/30'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="app.status === 'Pending' ? 'bg-amber-500' : app.status === 'Approved' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                        <span x-text="app.status"></span>
                                    </span>
                                </div>
                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-1.5 pl-12 md:pl-0 mt-1 md:mt-0">
                                    <button @click="toggleApplicationDetails(app.email)"
                                            class="text-xs text-brand-orange hover:bg-brand-cream/50 px-2 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1">
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200"
                                           :class="expandedApplication === app.email ? 'rotate-180' : ''"></i>
                                        <span class="hidden sm:inline">View Details</span>
                                    </button>
                                    <button @click="approveApplication(app.email)"
                                            class="bg-[#5BA44C] text-white text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1 transition-all duration-200 hover:bg-[#5BA44C]/90 active:scale-95 shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span class="hidden sm:inline">Approve</span>
                                    </button>
                                    <button @click="declineApplication(app.email)"
                                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1 transition-all duration-200">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                        <span class="hidden sm:inline">Decline</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Details Drawer -->
                            <div x-show="expandedApplication === app.email"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="border-t border-admin-border bg-slate-50/50 dark:bg-[#111c2a]/20"
                                 style="display: none;">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-4 md:px-6 py-4">
                                    <!-- Left: Applicant Profile -->
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3 flex items-center gap-1.5">
                                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                            Applicant Details
                                        </span>
                                        <div class="space-y-2.5 text-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-950/30 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Email</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="app.email"></span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-950/30 text-emerald-500 flex items-center justify-center flex-shrink-0">
                                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Phone</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="app.phone"></span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-950/30 text-amber-500 flex items-center justify-center flex-shrink-0">
                                                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Address</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="app.address"></span>
                                                </div>
                                            </div>
                                            <div class="pt-2 border-t border-admin-border/50">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300 block mb-1.5">Adoption Reason</span>
                                                <p class="text-xs text-slate-600 dark:text-slate-300 italic leading-relaxed" x-text="app.reason || 'No reason provided.'"></p>
                                            </div>
                                            
                                            <!-- Eligibility & Home Environment -->
                                            <div class="pt-3 border-t border-admin-border/50 mt-3">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300 block mb-2.5">Eligibility & Home Environment</span>
                                                <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Housing Type</span>
                                                        <span class="font-semibold text-slate-700 dark:text-slate-300 capitalize" x-text="app.housing_type || 'Unknown'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Employment</span>
                                                        <span class="font-semibold text-slate-700 dark:text-slate-300 capitalize" x-text="app.employment_status || 'Unknown'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Family Members</span>
                                                        <span class="font-semibold text-slate-700 dark:text-slate-300" x-text="app.family_members_count || 'Unknown'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Experience</span>
                                                        <span class="font-semibold text-slate-700 dark:text-slate-300 capitalize" x-text="app.experience_with_pets ? app.experience_with_pets.replace('_', ' ') : 'Unknown'"></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-wrap gap-2 mt-3">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                                          :class="app.has_yard ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border-emerald-200/50' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200/50'">
                                                        <i data-lucide="trees" class="w-3 h-3"></i>
                                                        <span x-text="app.has_yard ? 'Has Yard' : 'No Yard'"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                                          :class="app.has_other_pets ? 'bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-400 border-indigo-200/50' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200/50'">
                                                        <i data-lucide="paw-print" class="w-3 h-3"></i>
                                                        <span x-text="app.has_other_pets ? 'Has Other Pets' : 'No Other Pets'"></span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                                          :class="app.is_allergic ? 'bg-rose-50 dark:bg-rose-500/20 text-rose-700 dark:text-rose-400 border-rose-200/50' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200/50'">
                                                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                                        <span x-text="app.is_allergic ? 'Has Allergies' : 'No Allergies'"></span>
                                                    </span>
                                                </div>
                                                <template x-if="app.bio">
                                                    <div class="mt-3 p-3 bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/50 shadow-sm">
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block mb-1">Adopter Bio</span>
                                                        <p class="text-xs text-slate-700 dark:text-slate-300 italic" x-text="app.bio"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Right: Target Pet Details -->
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3 flex items-center gap-1.5">
                                            <i data-lucide="dog" class="w-3.5 h-3.5"></i>
                                            Target Pet Details
                                        </span>
                                        <div class="bg-white dark:bg-[#1a2333] rounded-xl border border-admin-border/60 overflow-hidden shadow-sm">
                                            <!-- Pet Image / Banner -->
                                            <div class="h-32 w-full bg-slate-100 dark:bg-slate-800 relative">
                                                <template x-if="app.pet_image">
                                                    <img :src="app.pet_image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!app.pet_image">
                                                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white uppercase shadow-sm" :class="getPetColor(app.pet)">
                                                        <span x-text="app.pet.charAt(0)"></span>
                                                    </div>
                                                </template>
                                                <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-lg border border-white/10">
                                                    <span class="text-sm font-bold text-white block leading-none" x-text="app.pet"></span>
                                                    <span class="text-[10px] text-white/70 block mt-0.5" x-text="app.pet_breed || getPetBreed(app.pet) || 'Mixed Breed'"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="p-4 space-y-2.5">
                                                <div class="grid grid-cols-2 gap-4 pb-2 border-b border-admin-border/40">
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Age</span>
                                                        <span class="font-semibold text-xs text-slate-700 dark:text-slate-300" x-text="app.pet_age || getPetAge(app.pet)"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-[10px] text-slate-400 dark:text-slate-300 block">Breed</span>
                                                        <span class="font-semibold text-xs text-slate-700 dark:text-slate-300 truncate block" x-text="app.pet_breed || getPetBreed(app.pet)"></span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-300 block mb-2">Traits</span>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        <template x-for="trait in (app.pet_traits || getPetTraits(app.pet))" :key="trait">
                                                            <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-[#111c2a] px-2 py-0.5 rounded-full border border-slate-200/50">
                                                                <span x-text="trait"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Empty state -->
            <div x-show="applications.length === 0"
                 class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-8 text-center mt-4">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">No pending applications</p>
                <p class="text-xs text-slate-400 dark:text-slate-300 mt-1">Adoption requests from mobile users will appear here.</p>
            </div>
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('adoptionManager', (liveListings, applications, successfulRehomings) => ({
                    activeTab: 'listings',
                    expandedPet: null,
                    expandedApplication: null,
                    successfulRehomings: successfulRehomings,
                    liveListings: liveListings,
                    applications: applications,
                    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                    declineReason: '',
                    decliningApp: null,
                    isDeclineModalOpen: false,

                    // Delist modal state
                    isDelistModalOpen: false,
                    delistingPet: null,
                    delistReason: '',
                    delistLoading: false,

                    init() {
                        // Listen for real-time new adoption request events from Echo
                        window.addEventListener('new-adoption-request', (e) => {
                            const app = e.detail?.application;
                            if (!app) return;

                            // Prevent duplicate entries
                            const exists = this.applications.some(a => a.id === app.id);
                            if (exists) return;

                            // Inject the new application at the top of the list
                            this.applications.unshift(app);

                            // Auto-switch to the applications tab
                            this.activeTab = 'applications';

                            // Re-render Lucide icons for the new DOM elements
                            if (typeof lucide !== 'undefined') {
                                this.$nextTick(() => lucide.createIcons());
                            }
                        });
                    },

                    togglePetDetails(name) {
                        this.expandedPet = this.expandedPet === name ? null : name;
                    },

                    toggleApplicationDetails(email) {
                        this.expandedApplication = this.expandedApplication === email ? null : email;
                    },

                    getPetData(name) {
                        return this.liveListings.find(p => p.name === name) || {};
                    },
                    getPetColor(name) { return this.getPetData(name).color || 'bg-slate-500'; },
                    getPetBreed(name) { return this.getPetData(name).breed || ''; },
                    getPetAge(name) { return this.getPetData(name).age || ''; },
                    getPetWeight(name) { return this.getPetData(name).weight || ''; },
                    getPetTraits(name) { return this.getPetData(name).traits || []; },

                    openDelistModal(pet) {
                        this.delistingPet = pet;
                        this.delistReason = '';
                        this.isDelistModalOpen = true;
                    },

                    async confirmDelist() {
                        const pet = this.delistingPet;
                        if (!pet) return;
                        this.delistLoading = true;
                        try {
                            const fd = new FormData();
                            fd.append('status', 'unavailable');
                            const res = await fetch(`/organization/pets/${pet.id}/status`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                                body: fd,
                            });
                            if (!res.ok) {
                                const err = await res.json();
                                alert(err.message || 'Failed to delist pet.');
                                return;
                            }
                            this.liveListings = this.liveListings.filter(p => p.id !== pet.id);
                            this.isDelistModalOpen = false;
                            this.delistingPet = null;
                        } catch (e) {
                            alert('An error occurred while delisting the pet.');
                        } finally {
                            this.delistLoading = false;
                        }
                    },

                    async approveApplication(email) {
                        const app = this.applications.find(a => a.email === email);
                        if (!app) return;
                        try {
                            const fd = new FormData();
                            fd.append('status', 'approved');
                            const res = await fetch(`/organization/adoptions/${app.id}/status`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                                body: fd,
                            });
                            if (!res.ok) {
                                const err = await res.json();
                                alert(err.message || 'Failed to approve application.');
                                return;
                            }
                            app.status = 'Approved';
                            this.successfulRehomings++;
                            this.liveListings = this.liveListings.filter(p => p.id !== app.pet_id);
                            setTimeout(() => {
                                this.applications = this.applications.filter(a => a.email !== email);
                            }, 1500);
                        } catch (e) {
                            alert('An error occurred while approving the application.');
                        }
                    },

                    declineApplication(email) {
                        const app = this.applications.find(a => a.email === email);
                        if (!app) return;
                        this.decliningApp = app;
                        this.declineReason = '';
                        this.isDeclineModalOpen = true;
                    },

                    async confirmDecline() {
                        const app = this.decliningApp;
                        if (!app) return;
                        try {
                            const fd = new FormData();
                            fd.append('status', 'rejected');
                            fd.append('organization_response', this.declineReason);
                            const res = await fetch(`/organization/adoptions/${app.id}/status`, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                                body: fd,
                            });
                            if (!res.ok) {
                                const err = await res.json();
                                alert(err.message || 'Failed to reject application.');
                                return;
                            }
                            app.status = 'Declined';
                            this.isDeclineModalOpen = false;
                            this.decliningApp = null;
                            setTimeout(() => {
                                this.applications = this.applications.filter(a => a.email !== app.email);
                            }, 1000);
                        } catch (e) {
                            alert('An error occurred while rejecting the application.');
                        }
                    }
                }));
            });
        </script>

        <!-- Decline Reason Modal -->
        <div x-show="isDeclineModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" @click.away="isDeclineModalOpen = false">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/20 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="x-circle" class="w-5 h-5 text-red-500"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-admin-textPrimary">Decline Application</h3>
                        <p class="text-xs text-admin-textSecondary/70">Provide a reason for the applicant</p>
                    </div>
                </div>
                <p class="text-sm text-admin-textSecondary mb-3">
                    Declining <span class="font-semibold text-admin-textPrimary" x-text="decliningApp?.name"></span>'s application for <span class="font-semibold text-admin-textPrimary" x-text="decliningApp?.pet"></span>.
                </p>
                <textarea x-model="declineReason" rows="4" maxlength="1000"
                          class="w-full border border-admin-border bg-admin-bg rounded-xl p-3 text-sm resize-none focus:ring-2 focus:ring-red-400 focus:border-red-400 text-admin-textPrimary placeholder-admin-textSecondary/50 outline-none transition"
                          placeholder="Enter the reason for declining..."></textarea>
                <div class="flex justify-end gap-3 mt-4">
                    <button @click="isDeclineModalOpen = false; decliningApp = null"
                            class="px-4 py-2 text-sm font-semibold text-admin-textSecondary bg-admin-bg border border-admin-border rounded-xl hover:bg-admin-surface transition-all">
                        Cancel
                    </button>
                    <button @click="confirmDecline()"
                            :disabled="!declineReason.trim()"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm">
                        Confirm Decline
                    </button>
                </div>
            </div>
        </div>

        <!-- Delist from App Modal -->
        <div x-show="isDelistModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" @click.away="isDelistModalOpen = false">
                <!-- Modal Header -->
                <div class="p-6 pb-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="toggle-left" class="w-5 h-5 text-amber-500"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-admin-textPrimary">Delist from App</h3>
                            <p class="text-xs text-admin-textSecondary/70">Pet will be marked as unavailable</p>
                        </div>
                    </div>

                    <!-- Pet preview -->
                    <template x-if="delistingPet">
                        <div class="flex items-center gap-3 bg-admin-bg border border-admin-border rounded-xl p-3 mb-4">
                            <template x-if="delistingPet.primary_image">
                                <img :src="delistingPet.primary_image" :alt="delistingPet.name"
                                     class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-admin-border">
                            </template>
                            <template x-if="!delistingPet.primary_image">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-lg font-bold text-white flex-shrink-0"
                                     :class="delistingPet.color">
                                    <span x-text="delistingPet.name?.charAt(0)"></span>
                                </div>
                            </template>
                            <div class="min-w-0">
                                <span class="font-bold text-sm text-admin-textPrimary block" x-text="delistingPet.name"></span>
                                <span class="text-xs text-admin-textSecondary/70" x-text="delistingPet.breed || 'No breed'"></span>
                            </div>
                            <span class="ml-auto flex items-center gap-1.5 text-[10px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-950/20 px-2 py-1 rounded-full border border-amber-200/50 flex-shrink-0">
                                <i data-lucide="eye-off" class="w-3 h-3"></i>
                                Will be hidden
                            </span>
                        </div>
                    </template>

                    <p class="text-sm text-admin-textSecondary mb-3">
                        This pet will be removed from the mobile app and marked as <span class="font-semibold text-amber-600">unavailable</span>. Provide an optional reason for internal records.
                    </p>
                    <textarea x-model="delistReason" rows="3" maxlength="500"
                              class="w-full border border-admin-border bg-admin-bg rounded-xl p-3 text-sm resize-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 text-admin-textPrimary placeholder-admin-textSecondary/50 outline-none transition"
                              placeholder="Reason for delisting (optional)..."></textarea>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 px-6 py-4 bg-admin-bg border-t border-admin-border">
                    <button @click="isDelistModalOpen = false; delistingPet = null"
                            class="px-4 py-2 text-sm font-semibold text-admin-textSecondary bg-admin-surface border border-admin-border rounded-xl hover:bg-admin-bg transition-all">
                        Cancel
                    </button>
                    <button @click="confirmDelist()"
                            :disabled="delistLoading"
                            class="px-5 py-2 text-sm font-bold text-white bg-amber-500 rounded-xl hover:bg-amber-600 disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-sm flex items-center gap-2">
                        <template x-if="delistLoading">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </template>
                        <i data-lucide="toggle-left" class="w-4 h-4" x-show="!delistLoading"></i>
                        <span x-text="delistLoading ? 'Delisting...' : 'Confirm Delist'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
