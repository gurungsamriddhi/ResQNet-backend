<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-8"
         x-data="volunteerManager()"
         x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons() })"
         id="volunteer-dashboard-container">

        <!-- Page Header & Action Controls -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 dark:border-slate-700 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span class="bg-brand-orange/10 text-brand-orange p-2 rounded-xl">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </span>
                    Volunteers Portal
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Manage your volunteer force, review applications, and coordinate dispatch schedules.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button @click="openAddModal()"
                        id="btn-add-volunteer"
                        class="bg-brand-orange text-white hover:bg-brand-orange/90 active:scale-95 text-sm font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-brand-orange/20 transition-all duration-200 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Add Volunteer</span>
                </button>
            </div>
        </div>

        <!-- Analytical Metrics Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Total Active -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Active Team</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="stats.active">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="font-bold text-emerald-500" x-text="stats.onCall">0</span>
                    <span>currently on-call today</span>
                </div>
            </div>

            <!-- Metric 2: Pending Applications -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Pending Reviews</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="stats.pending">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-500 flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="font-bold text-amber-500" x-text="stats.pending > 0 ? stats.pending + ' new' : 'No'">0</span>
                    <span>awaiting application review</span>
                </div>
            </div>

            <!-- Metric 3: Specialized Skills -->
            <div class="bg-brand-cream dark:bg-slate-800/40 border border-brand-orange/20 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-brand-orange dark:text-slate-400 uppercase tracking-widest block">Medical Responders</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="stats.medics">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                        <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span>First-Aid & Vet qualified members</span>
                </div>
            </div>

            <!-- Metric 4: Mobilized Cases -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Active Dispatches</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="stats.dispatched">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-500 flex items-center justify-center">
                        <i data-lucide="truck" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span>Assigned to field rescue cases</span>
                </div>
            </div>
        </div>

        <!-- Filters & Navigation Sub-Navbar -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Custom Navigation Tabs -->
                <div class="flex items-center bg-slate-100 dark:bg-slate-900/60 p-1.5 rounded-xl self-start">
                    <button @click="currentTab = 'active'; recreateIcons()"
                            id="tab-active"
                            class="px-4 py-2 text-xs font-extrabold rounded-lg transition-all duration-200 flex items-center gap-2"
                            :class="currentTab === 'active' ? 'bg-white dark:bg-slate-800/70 text-brand-orange shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        <span>Active Team</span>
                        <span class="bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded-md text-[10px] text-slate-600 dark:text-slate-400" x-text="filteredVolunteersCount('active')"></span>
                    </button>
                    <button @click="currentTab = 'pending'; recreateIcons()"
                            id="tab-pending"
                            class="px-4 py-2 text-xs font-extrabold rounded-lg transition-all duration-200 flex items-center gap-2"
                            :class="currentTab === 'pending' ? 'bg-white dark:bg-slate-800/70 text-brand-orange shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'">
                        <i data-lucide="inbox" class="w-3.5 h-3.5"></i>
                        <span>Applications</span>
                        <span class="bg-brand-orange/15 text-brand-orange px-2 py-0.5 rounded-md text-[10px] font-bold" x-text="filteredVolunteersCount('pending')"></span>
                    </button>
                    <button @click="currentTab = 'archived'; recreateIcons()"
                            id="tab-archived"
                            class="px-4 py-2 text-xs font-extrabold rounded-lg transition-all duration-200 flex items-center gap-2"
                            :class="currentTab === 'archived' ? 'bg-white dark:bg-slate-800/70 text-brand-orange shadow-sm font-extrabold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200'">
                        <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                        <span>Archived</span>
                        <span class="bg-slate-200 dark:bg-slate-800 px-2 py-0.5 rounded-md text-[10px] text-slate-600 dark:text-slate-400" x-text="filteredVolunteersCount('archived')"></span>
                    </button>
                </div>

                <!-- Live Search & Filtering Form Controls -->
                <div class="flex flex-wrap items-center gap-3 flex-1 lg:justify-end">
                    <!-- Search Input -->
                    <div class="relative w-full sm:max-w-xs">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" 
                               x-model="searchQuery"
                               id="search-volunteers-input"
                               placeholder="Search volunteers by name, email..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all duration-200">
                    </div>

                    <!-- Skills Filter Dropdown -->
                    <div class="w-full sm:w-auto">
                        <select x-model="selectedSkill"
                                id="filter-skill"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-350 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                            <option value="">All Skills</option>
                            <template x-for="skill in availableSkills" :key="skill">
                                <option :value="skill" x-text="skill"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Availability Status Filter -->
                    <div class="w-full sm:w-auto" x-show="currentTab === 'active'">
                        <select x-model="selectedAvailability"
                                id="filter-availability"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-350 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                            <option value="">All Availability</option>
                            <option value="Available">Available</option>
                            <option value="On-Call">On-Call</option>
                            <option value="Unavailable">Unavailable</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Toast Notifications -->
        <div x-show="successMessage"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-205"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             class="fixed bottom-5 right-5 z-[200] bg-emerald-600 text-white text-xs font-bold px-4 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 border border-emerald-500/20"
             style="display: none;">
            <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center">
                <i data-lucide="check" class="w-4 h-4 text-white"></i>
            </span>
            <span x-text="successMessage"></span>
        </div>

        <!-- Volunteer Cards List Grid -->
        <div class="space-y-4">
            <template x-for="vol in filteredVolunteers" :key="vol.email">
                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:border-slate-300 dark:hover:border-slate-650"
                     :id="'volunteer-card-' + vol.email.split('@')[0]">
                    <div class="p-5">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                            
                            <!-- Left Section: Avatar, Status indicator, Info -->
                            <div class="flex items-start gap-4 flex-1">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-extrabold text-white uppercase shadow-inner"
                                         :class="vol.status === 'Pending' ? 'bg-amber-500' : (vol.status === 'Archived' ? 'bg-slate-400' : 'bg-brand-orange')"
                                         x-text="vol.name.charAt(0) + (vol.name.includes(' ') ? vol.name.split(' ')[1].charAt(0) : '')">
                                    </div>
                                    <!-- Online Status Badge Indicator -->
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center"
                                          :class="vol.availability === 'On-Call' ? 'bg-amber-400' : (vol.availability === 'Available' ? 'bg-emerald-500' : 'bg-red-400')"
                                          x-show="vol.status === 'Active'">
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-sm text-slate-800 dark:text-slate-100 block truncate" x-text="vol.name"></span>
                                        <template x-if="vol.status === 'Active'">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider"
                                                  :class="vol.availability === 'On-Call' ? 'bg-amber-50 text-amber-700 border border-amber-200' : (vol.availability === 'Available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-650 border border-red-200')">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1" :class="vol.availability === 'On-Call' ? 'bg-amber-400' : (vol.availability === 'Available' ? 'bg-emerald-500' : 'bg-red-400')"></span>
                                                <span x-text="vol.availability"></span>
                                            </span>
                                        </template>
                                        <template x-if="vol.status === 'Pending'">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-amber-500/10 text-amber-600 border border-amber-500/20 uppercase tracking-wider">
                                                Pending Review
                                            </span>
                                        </template>
                                        <template x-if="vol.status === 'Archived'">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-650 uppercase tracking-wider">
                                                Archived
                                            </span>
                                        </template>
                                    </div>
                                    <span class="text-xs text-slate-400 block mt-0.5" x-text="vol.email"></span>
                                    <span class="block text-[10px] text-slate-450 dark:text-slate-500 mt-1 flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        Applied <span x-text="vol.appliedAt"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Center Section: Skill Tag Badges -->
                            <div class="flex flex-wrap items-center gap-1.5 flex-1 max-w-md">
                                <template x-for="skill in vol.skills" :key="skill">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-[10px] font-bold bg-slate-50 dark:bg-slate-900 border border-slate-200/60 dark:border-slate-750 text-slate-600 dark:text-slate-350">
                                        <i data-lucide="star" class="w-3.5 h-3.5 text-brand-orange"></i>
                                        <span x-text="skill"></span>
                                    </span>
                                </template>
                            </div>

                            <!-- Right Section: Context-sensitive Operations -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <!-- Application review actions -->
                                <template x-if="vol.status === 'Pending'">
                                    <div class="flex items-center gap-2">
                                        <button @click="acceptApplication(vol.email)"
                                                :id="'btn-accept-' + vol.email.split('@')[0]"
                                                class="bg-emerald-600 text-white text-xs font-extrabold px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200 hover:bg-emerald-700 active:scale-95 shadow-sm">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Accept</span>
                                        </button>
                                        <button @click="rejectApplication(vol.email)"
                                                :id="'btn-reject-' + vol.email.split('@')[0]"
                                                class="border border-rose-200 dark:border-rose-900 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                            <span>Reject</span>
                                        </button>
                                    </div>
                                </template>

                                <!-- Active volunteer actions -->
                                <template x-if="vol.status === 'Active'">
                                    <div class="flex items-center gap-2">
                                        <button @click="openAssignModal(vol)"
                                                :id="'btn-assign-' + vol.email.split('@')[0]"
                                                class="bg-[#3D5A4E] text-white hover:bg-[#3D5A4E]/90 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200 shadow-sm">
                                            <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                                            <span>Assign Dispatch</span>
                                        </button>
                                        <button @click="openContactModal(vol)"
                                                :id="'btn-contact-' + vol.email.split('@')[0]"
                                                class="border border-slate-200 dark:border-slate-700 text-slate-650 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs font-bold px-3.5 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200">
                                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                            <span>Contact</span>
                                        </button>
                                        <button @click="archiveVolunteer(vol.email)"
                                                :id="'btn-archive-' + vol.email.split('@')[0]"
                                                class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl transition-all duration-200 hover:bg-slate-100 dark:hover:bg-slate-700"
                                                title="Archive Volunteer">
                                            <i data-lucide="archive" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </template>

                                <!-- Archived actions -->
                                <template x-if="vol.status === 'Archived'">
                                    <button @click="restoreVolunteer(vol.email)"
                                            :id="'btn-restore-' + vol.email.split('@')[0]"
                                            class="border border-slate-200 dark:border-slate-750 text-slate-600 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs font-semibold px-4 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                            <span>Restore to Team</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Dropdown toggle link -->
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                            <button @click="toggleDetails(vol.email)"
                                    :id="'btn-details-' + vol.email.split('@')[0]"
                                    class="text-xs font-bold text-brand-orange hover:text-brand-orange/80 transition-colors flex items-center gap-1">
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                   :class="expandedRow === vol.email ? 'rotate-180' : ''"></i>
                                <span x-text="expandedRow === vol.email ? 'Hide Details' : 'View Details'"></span>
                            </button>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider" x-text="vol.sector"></span>
                        </div>
                    </div>

                    <!-- Expandable Details Shelf Drawer -->
                    <div x-show="expandedRow === vol.email"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-[1000px]"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-[1000px]"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="border-t border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/30"
                         style="display: none;">
                        <div class="p-6 space-y-6">
                            
                            <!-- Detail Info Columns -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/50 dark:border-slate-700 p-4 flex items-start gap-3 shadow-xs">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block">Phone Connection</span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block mt-0.5" x-text="vol.contact"></span>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/50 dark:border-slate-700 p-4 flex items-start gap-3 shadow-xs">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block">Operations Sector</span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block mt-0.5" x-text="vol.sector"></span>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/50 dark:border-slate-700 p-4 flex items-start gap-3 shadow-xs">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block">Emergency Relation</span>
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block mt-0.5" x-text="vol.emergencyContact"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferred timeslot details -->
                            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/50 dark:border-slate-700 p-5 shadow-xs">
                                <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 flex items-center gap-1.5 mb-3">
                                    <i data-lucide="calendar" class="w-4 h-4 text-brand-orange"></i>
                                    Preferred Schedule & Bio
                                </h4>
                                <p class="text-xs text-slate-650 dark:text-slate-350 leading-relaxed mb-3" x-text="vol.bio || 'No bio details provided by volunteer.'"></p>
                                <div class="flex items-center gap-1 text-xs">
                                    <span class="font-bold text-slate-600 dark:text-slate-450">Availability:</span>
                                    <span class="text-slate-800 dark:text-slate-200 bg-slate-100 dark:bg-slate-900 px-2.5 py-1 rounded-lg font-bold" x-text="vol.availabilityDetails || 'Weekdays & Weekends (On-Call)'"></span>
                                </div>
                            </div>

                            <!-- Interactive Field Mission Log Timeline -->
                            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/50 dark:border-slate-700 p-5 shadow-xs space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-md bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                                            <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500">Field Mission Log</span>
                                    </div>
                                    <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 text-[9px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider" 
                                          x-text="vol.missions.length + ' cases logged'">
                                    </span>
                                </div>
                                
                                <div class="relative pl-6 space-y-4 before:absolute before:left-[7px] before:top-1.5 before:bottom-1.5 before:w-0.5 before:bg-slate-100 dark:before:bg-slate-750">
                                    <template x-for="mission in vol.missions" :key="mission.caseId">
                                        <div class="relative">
                                            <!-- Timeline Pin -->
                                            <div class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-slate-800 shadow-sm flex items-center justify-center"
                                                 :class="mission.status === 'In Progress' ? 'bg-amber-400' : 'bg-emerald-500'">
                                                <span class="w-1 h-1 rounded-full bg-white"></span>
                                            </div>
                                            <!-- Log Box -->
                                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-3 border border-slate-100 dark:border-slate-700/40 flex items-start justify-between gap-4">
                                                <div class="min-w-0">
                                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block" x-text="mission.description"></span>
                                                    <span class="text-[10px] text-slate-400 block mt-1 flex items-center gap-1">
                                                        <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                        <span x-text="mission.location"></span>
                                                        <span class="mx-1.5">•</span>
                                                        <span x-text="mission.date"></span>
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-lg uppercase tracking-wider"
                                                          :class="mission.status === 'In Progress' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'"
                                                          x-text="mission.status">
                                                    </span>
                                                    <!-- Resolve quick action -->
                                                    <template x-if="mission.status === 'In Progress'">
                                                        <button @click="completeMission(vol.email, mission.caseId)"
                                                                class="p-1 hover:bg-emerald-50 text-emerald-600 rounded-lg hover:border-emerald-200 border border-transparent transition-colors"
                                                                title="Resolve case">
                                                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Empty Timeline Log State -->
                                    <template x-if="vol.missions.length === 0">
                                        <div class="text-slate-400 text-xs py-2 italic">
                                            No field missions have been logged for this volunteer.
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Zero State / Empty Filters -->
            <div x-show="filteredVolunteers.length === 0"
                 id="volunteer-empty-state"
                 class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 dark:border-slate-700/80 rounded-2xl shadow-sm p-12 text-center transition-all duration-300">
                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-900 text-slate-400 flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700/30">
                    <i data-lucide="users-round" class="w-8 h-8 text-slate-350"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-200">No volunteers found</h3>
                <p class="text-xs text-slate-400 mt-2 max-w-sm mx-auto">There are no records matching your active tab, keyword query, or specific filter parameters.</p>
                <button @click="resetFilters()"
                        class="mt-4 text-xs font-extrabold text-brand-orange hover:text-brand-orange/80 transition-colors">
                    Clear Active Filters
                </button>
            </div>
        </div>

        <!-- MODAL: ADD VOLUNTEER -->
        <div x-show="addModalOpen" 
             id="modal-add-volunteer"
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             @keydown.escape.window="addModalOpen = false">
            <div @click.away="addModalOpen = false" 
                 class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-750 transition-all transform scale-100">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 dark:border-slate-750 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-brand-orange"></i>
                        Add Volunteer to Database
                    </h3>
                    <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Modal Body Form -->
                <form @submit.prevent="submitAddVolunteer()" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Full Name</label>
                            <input type="text" x-model="addForm.name" required placeholder="e.g. John Doe"
                                   id="input-add-name"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Email Address</label>
                            <input type="email" x-model="addForm.email" required placeholder="name@example.com"
                                   id="input-add-email"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Contact Phone</label>
                            <input type="text" x-model="addForm.contact" required placeholder="98XXXXXXXX"
                                   id="input-add-phone"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Emergency Contact Details</label>
                            <input type="text" x-model="addForm.emergencyContact" placeholder="Name (Phone)"
                                   id="input-add-emergency"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Operation Sector</label>
                            <input type="text" x-model="addForm.sector" placeholder="e.g. Pokhara Sector-1"
                                   id="input-add-sector"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Availability Schedule</label>
                            <input type="text" x-model="addForm.availabilityDetails" placeholder="e.g. Weekends only"
                                   id="input-add-schedule"
                                   class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                        </div>
                    </div>

                    <!-- Skills selection checklist -->
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Skills & Qualifications</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl">
                            <template x-for="skill in availableSkills" :key="skill">
                                <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-750 dark:text-slate-300">
                                    <input type="checkbox" :value="skill" x-model="addForm.skills"
                                           class="rounded text-brand-orange focus:ring-brand-orange border-slate-350 w-4 h-4">
                                    <span x-text="skill"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Biography details -->
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Short Bio & Qualifications Notes</label>
                        <textarea x-model="addForm.bio" rows="2" placeholder="Write a short summary about the volunteer's experience..."
                                  id="input-add-bio"
                                  class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange"></textarea>
                    </div>

                    <!-- Submit / Cancel footer -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-750 flex items-center justify-end gap-3">
                        <button type="button" @click="addModalOpen = false"
                                class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-450 hover:bg-slate-100 dark:hover:bg-slate-750 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                id="btn-submit-add-volunteer"
                                class="bg-brand-orange text-white hover:bg-brand-orange/90 text-xs font-bold px-5 py-2 rounded-xl shadow-md transition-all duration-200">
                            Add Volunteer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: ASSIGN CASE TO VOLUNTEER -->
        <div x-show="assignModalOpen" 
             id="modal-assign-dispatch"
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             @keydown.escape.window="assignModalOpen = false">
            <div @click.away="assignModalOpen = false" 
                 class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-750 transition-all transform scale-100">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 dark:border-slate-750 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="navigation" class="w-5 h-5 text-[#3D5A4E]"></i>
                        Dispatch Volunteer
                    </h3>
                    <button @click="assignModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form @submit.prevent="submitAssignMission()" class="p-6 space-y-4">
                    <div>
                        <span class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1">Target Volunteer</span>
                        <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 block" x-text="activeTargetVolunteer ? activeTargetVolunteer.name : ''"></span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Select Rescue Case</label>
                        <select x-model="assignForm.caseId" required
                                id="select-dispatch-case"
                                class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-350 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                            <option value="">Choose a Rescue Mission...</option>
                            <option value="RQ-2450">Case RQ-2450: Injured Dog Rescue (Kathmandu-9)</option>
                            <option value="RQ-2458">Case RQ-2458: Stray Cat Vaccination (Lalitpur-3)</option>
                            <option value="RQ-2470">Case RQ-2470: Raptor Rehab Rescue (Pokhara-15)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Dispatch Instructions / Notes</label>
                        <textarea x-model="assignForm.instruction" rows="3" required placeholder="What should the volunteer prepare for or do at the scene?..."
                                  id="input-dispatch-instructions"
                                  class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange"></textarea>
                    </div>

                    <!-- Submit / Cancel -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-750 flex items-center justify-end gap-3">
                        <button type="button" @click="assignModalOpen = false"
                                class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-450 hover:bg-slate-100 dark:hover:bg-slate-750 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                id="btn-submit-dispatch"
                                class="bg-[#3D5A4E] text-white hover:bg-[#3D5A4E]/90 text-xs font-bold px-5 py-2 rounded-xl shadow-md transition-all duration-200">
                            Confirm Dispatch
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: CONTACT VOLUNTEER -->
        <div x-show="contactModalOpen" 
             id="modal-contact-volunteer"
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             style="display: none;"
             @keydown.escape.window="contactModalOpen = false">
            <div @click.away="contactModalOpen = false" 
                 class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-750 transition-all transform scale-100">
                
                <!-- Header -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 dark:border-slate-750 flex items-center justify-between">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="mail" class="w-5 h-5 text-brand-orange"></i>
                        Contact Volunteer
                    </h3>
                    <button @click="contactModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitContactVolunteer()" class="p-6 space-y-4">
                    <div>
                        <span class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1">To:</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block" x-text="activeTargetVolunteer ? activeTargetVolunteer.name + ' (' + activeTargetVolunteer.email + ')' : ''"></span>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Communication Medium</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800/80">
                                <input type="radio" name="medium" value="email" x-model="contactForm.medium" class="text-brand-orange focus:ring-brand-orange border-slate-300">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-350">Email</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800/80">
                                <input type="radio" name="medium" value="sms" x-model="contactForm.medium" class="text-brand-orange focus:ring-brand-orange border-slate-300">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-350">SMS</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Subject / Header</label>
                        <input type="text" x-model="contactForm.subject" placeholder="e.g. Schedule changes, rescue coordination..." required
                               id="input-contact-subject"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-450 dark:text-slate-400 mb-1.5">Message Text</label>
                        <textarea x-model="contactForm.message" rows="4" required placeholder="Type your message here..."
                                  id="input-contact-message"
                                  class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-750 flex items-center justify-end gap-3">
                        <button type="button" @click="contactModalOpen = false"
                                class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-450 hover:bg-slate-100 dark:hover:bg-slate-750 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                id="btn-submit-contact"
                                class="bg-brand-orange text-white hover:bg-brand-orange/90 text-xs font-bold px-5 py-2 rounded-xl shadow-md transition-all duration-200">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js Page Controller Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('volunteerManager', () => ({
                currentTab: 'active',
                searchQuery: '',
                selectedSkill: '',
                selectedAvailability: '',
                expandedRow: null,
                successMessage: '',

                recreateIcons() {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },
                
                // Modals state
                addModalOpen: false,
                assignModalOpen: false,
                contactModalOpen: false,
                activeTargetVolunteer: null,

                availableSkills: [
                    'Has Vehicle', 
                    'First-Aid Trained', 
                    'Veterinary Assistant', 
                    'Driver', 
                    'Search & Rescue',
                    'Feral Trapping'
                ],

                // Pre-populated data system
                volunteers: [
                    {
                        name: 'Kiran Thapa',
                        email: 'kiran@example.com',
                        skills: ['Has Vehicle', 'First-Aid Trained', 'Driver'],
                        appliedAt: '2 hours ago',
                        contact: '9851234567',
                        sector: 'Pokhara Sector-9',
                        emergencyContact: 'Sita Thapa (9847654321)',
                        status: 'Pending',
                        availability: 'Available',
                        availabilityDetails: 'Weekends & Evenings',
                        bio: 'Avid outdoorsman, trained in wilderness first aid and rescue response operations.',
                        missions: []
                    },
                    {
                        name: 'Anjali Shrestha',
                        email: 'anjali@example.com',
                        skills: ['Veterinary Assistant', 'Driver'],
                        appliedAt: '5 hours ago',
                        contact: '9849876543',
                        sector: 'Kathmandu Sector-3',
                        emergencyContact: 'Ramesh Shrestha (9855554433)',
                        status: 'Pending',
                        availability: 'Available',
                        availabilityDetails: 'Flexible hours, On-Call',
                        bio: 'Worked as an assistant at an animal clinic for 2 years. Willing to transport injured wildlife.',
                        missions: []
                    },
                    {
                        name: 'Bibek Bhandari',
                        email: 'bibek@example.com',
                        skills: ['First-Aid Trained', 'Search & Rescue', 'Driver'],
                        appliedAt: '3 days ago',
                        contact: '9812233445',
                        sector: 'Lalitpur Sector-2',
                        emergencyContact: 'Maya Bhandari (9811122233)',
                        status: 'Active',
                        availability: 'On-Call',
                        availabilityDetails: 'Weekdays 9 AM - 5 PM',
                        bio: 'Certified community rescue worker, highly experienced with handling dogs in high-stress rescue situations.',
                        missions: [
                            {
                                caseId: 'RQ-2417',
                                description: 'Responding to Case RQ-2417: Monkey Rescue',
                                location: 'Pokhara Zone',
                                date: '2 days ago',
                                status: 'In Progress'
                            },
                            {
                                caseId: 'RQ-2398',
                                description: 'Resolved Case RQ-2398: Injured Hawk',
                                location: 'Lalitpur-2',
                                date: '5 days ago',
                                status: 'Completed'
                            }
                        ]
                    },
                    {
                        name: 'Sandhya Giri',
                        email: 'sandhya@example.com',
                        skills: ['Veterinary Assistant', 'Feral Trapping'],
                        appliedAt: '1 week ago',
                        contact: '9801122334',
                        sector: 'Kathmandu Sector-7',
                        emergencyContact: 'Gopal Giri (9804455667)',
                        status: 'Active',
                        availability: 'Available',
                        availabilityDetails: 'Flexible Schedule',
                        bio: 'Active in TNR (Trap-Neuter-Return) community programs. Strong expertise in trapping feral cats.',
                        missions: [
                            {
                                caseId: 'RQ-2380',
                                description: 'Resolved Case RQ-2380: Stray Cat Spaying Drive',
                                location: 'Kathmandu Sector-7',
                                date: '1 week ago',
                                status: 'Completed'
                            }
                        ]
                    },
                    {
                        name: 'Rohan Shrestha',
                        email: 'rohan@example.com',
                        skills: ['Has Vehicle', 'Driver'],
                        appliedAt: '2 weeks ago',
                        contact: '9845566778',
                        sector: 'Bhaktapur Sector-1',
                        emergencyContact: 'Sunita Shrestha (9841122334)',
                        status: 'Archived',
                        availability: 'Unavailable',
                        availabilityDetails: 'Temporarily on leave',
                        bio: 'Professional driver. Available for long-distance animal transfers.',
                        missions: []
                    }
                ],

                // Forms binding state
                addForm: {
                    name: '',
                    email: '',
                    contact: '',
                    emergencyContact: '',
                    sector: '',
                    availabilityDetails: '',
                    skills: [],
                    bio: ''
                },

                assignForm: {
                    caseId: '',
                    instruction: ''
                },

                contactForm: {
                    medium: 'email',
                    subject: '',
                    message: ''
                },

                // Stats computed metrics
                get stats() {
                    return {
                        active: this.volunteers.filter(v => v.status === 'Active').length,
                        pending: this.volunteers.filter(v => v.status === 'Pending').length,
                        onCall: this.volunteers.filter(v => v.status === 'Active' && v.availability !== 'Unavailable').length,
                        medics: this.volunteers.filter(v => v.status === 'Active' && (v.skills.includes('First-Aid Trained') || v.skills.includes('Veterinary Assistant'))).length,
                        dispatched: this.volunteers.reduce((sum, v) => sum + v.missions.filter(m => m.status === 'In Progress').length, 0)
                    };
                },

                // Filters logic
                get filteredVolunteers() {
                    return this.volunteers.filter(vol => {
                        // Tab status check
                        if (this.currentTab === 'active' && vol.status !== 'Active') return false;
                        if (this.currentTab === 'pending' && vol.status !== 'Pending') return false;
                        if (this.currentTab === 'archived' && vol.status !== 'Archived') return false;

                        // Search query check
                        if (this.searchQuery) {
                            const query = this.searchQuery.toLowerCase();
                            const matchName = vol.name.toLowerCase().includes(query);
                            const matchEmail = vol.email.toLowerCase().includes(query);
                            const matchSector = vol.sector.toLowerCase().includes(query);
                            if (!matchName && !matchEmail && !matchSector) return false;
                        }

                        // Skill filter check
                        if (this.selectedSkill && !vol.skills.includes(this.selectedSkill)) {
                            return false;
                        }

                        // Availability status check
                        if (this.currentTab === 'active' && this.selectedAvailability && vol.availability !== this.selectedAvailability) {
                            return false;
                        }

                        return true;
                    });
                },

                filteredVolunteersCount(tab) {
                    return this.volunteers.filter(v => v.status === (tab === 'active' ? 'Active' : (tab === 'pending' ? 'Pending' : 'Archived'))).length;
                },

                toggleDetails(email) {
                    this.expandedRow = this.expandedRow === email ? null : email;
                    this.recreateIcons();
                },

                // Actions handlers
                acceptApplication(email) {
                    const vol = this.volunteers.find(v => v.email === email);
                    if (vol) {
                        vol.status = 'Active';
                        vol.availability = 'Available';
                        this.triggerToast(`${vol.name} has been welcomed onto the active volunteer team.`);
                        this.recreateIcons();
                    }
                },

                rejectApplication(email) {
                    const vol = this.volunteers.find(v => v.email === email);
                    if (vol && confirm(`Are you sure you want to reject ${vol.name}'s application?`)) {
                        this.volunteers = this.volunteers.filter(v => v.email !== email);
                        this.triggerToast(`${vol.name}'s application was removed.`);
                        this.recreateIcons();
                    }
                },

                archiveVolunteer(email) {
                    const vol = this.volunteers.find(v => v.email === email);
                    if (vol) {
                        vol.status = 'Archived';
                        vol.availability = 'Unavailable';
                        this.triggerToast(`${vol.name} has been archived successfully.`);
                        this.recreateIcons();
                    }
                },

                restoreVolunteer(email) {
                    const vol = this.volunteers.find(v => v.email === email);
                    if (vol) {
                        vol.status = 'Active';
                        vol.availability = 'Available';
                        this.triggerToast(`${vol.name} has been restored to the active team.`);
                        this.recreateIcons();
                    }
                },

                completeMission(email, caseId) {
                    const vol = this.volunteers.find(v => v.email === email);
                    if (vol) {
                        const mission = vol.missions.find(m => m.caseId === caseId);
                        if (mission) {
                            mission.status = 'Completed';
                            this.triggerToast(`Mission ${caseId} resolved and logged as completed.`);
                            this.recreateIcons();
                        }
                    }
                },

                // Modal triggers
                openAddModal() {
                    this.addForm = {
                        name: '',
                        email: '',
                        contact: '',
                        emergencyContact: '',
                        sector: '',
                        availabilityDetails: '',
                        skills: [],
                        bio: ''
                    };
                    this.addModalOpen = true;
                    this.recreateIcons();
                },

                submitAddVolunteer() {
                    const newVol = {
                        name: this.addForm.name,
                        email: this.addForm.email,
                        skills: [...this.addForm.skills],
                        appliedAt: 'Just now',
                        contact: this.addForm.contact,
                        sector: this.addForm.sector || 'Main Sector',
                        emergencyContact: this.addForm.emergencyContact || 'Not specified',
                        status: 'Active',
                        availability: 'Available',
                        availabilityDetails: this.addForm.availabilityDetails || 'Flexible hours',
                        bio: this.addForm.bio,
                        missions: []
                    };
                    this.volunteers.unshift(newVol);
                    this.addModalOpen = false;
                    this.triggerToast(`${newVol.name} added successfully as an Active volunteer.`);
                    this.recreateIcons();
                },

                openAssignModal(vol) {
                    this.activeTargetVolunteer = vol;
                    this.assignForm = {
                        caseId: '',
                        instruction: ''
                    };
                    this.assignModalOpen = true;
                    this.recreateIcons();
                },

                submitAssignMission() {
                    if (this.activeTargetVolunteer && this.assignForm.caseId) {
                        const vol = this.volunteers.find(v => v.email === this.activeTargetVolunteer.email);
                        if (vol) {
                            vol.missions.unshift({
                                caseId: this.assignForm.caseId,
                                description: `Responding to Case ${this.assignForm.caseId}: Dispatch Request`,
                                location: vol.sector,
                                date: 'Just now',
                                status: 'In Progress'
                            });
                            this.assignModalOpen = false;
                            this.triggerToast(`Successfully dispatched ${vol.name} to Case ${this.assignForm.caseId}.`);
                            this.recreateIcons();
                        }
                    }
                },

                openContactModal(vol) {
                    this.activeTargetVolunteer = vol;
                    this.contactForm = {
                        medium: 'email',
                        subject: '',
                        message: ''
                    };
                    this.contactModalOpen = true;
                    this.recreateIcons();
                },

                submitContactVolunteer() {
                    if (this.activeTargetVolunteer) {
                        this.contactModalOpen = false;
                        const mediumLabel = this.contactForm.medium === 'email' ? 'email' : 'SMS text message';
                        this.triggerToast(`Sent ${mediumLabel} to ${this.activeTargetVolunteer.name} successfully.`);
                        this.recreateIcons();
                    }
                },

                triggerToast(message) {
                    this.successMessage = message;
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 4000);
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.selectedSkill = '';
                    this.selectedAvailability = '';
                }
            }));
        });
    </script>
</x-layouts.admin>
