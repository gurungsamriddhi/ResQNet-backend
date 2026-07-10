<x-layouts.admin>
    <!-- We wrap the whole page in an Alpine component -->
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="healthManager()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Top Header Banner Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-admin-textPrimary font-extrabold text-3xl tracking-tight">
                    Health Records
                </h1>
                <p class="text-admin-textSecondary text-sm mt-1 font-medium">
                    Monitor and update medical histories, vaccination status, and upcoming checkups.
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-secondary-50 border border-secondary-500/20 text-xs font-semibold text-secondary-500 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-secondary-500 animate-pulse"></span>
                    Interactive Database
                </span>
            </div>
        </div>

        <!-- 1. Top Search & Command Bar -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center gap-4">
            <!-- Sleek Search Input -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="search" class="w-4.5 h-4.5"></i>
                </div>
                <input type="text"
                       x-model="search"
                       placeholder="Search by pet name or breed..."
                       class="w-full pl-10 pr-4 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-xs sm:text-sm text-admin-textPrimary placeholder-admin-textSecondary/50 focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all duration-200">
                <button x-show="search.length" @click="search = ''"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-admin-textSecondary/60 hover:text-admin-textPrimary transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Prominent action button "+ Add New Record" -->
            <button @click="isAddModalOpen = true; openAddModal()"
                    class="bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5 self-start md:self-auto w-full sm:w-auto">
                <i data-lucide="plus" class="w-3.5 h-3.5 stroke-[2.5]"></i>
                <span>+ Add New Record</span>
            </button>
        </div>

        <!-- Empty State -->
        <div x-show="filteredRecords.length === 0"
             class="flex flex-col items-center justify-center py-16 px-6 bg-admin-surface border border-admin-border rounded-2xl shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-admin-bg/50 flex items-center justify-center mb-4 border border-admin-border/60">
                <i data-lucide="search-x" class="w-8 h-8 text-admin-textSecondary/30" style="stroke-width: 1.5;"></i>
            </div>
            <h3 class="text-base font-extrabold text-admin-textPrimary mb-1">No medical records found</h3>
            <p class="text-xs text-admin-textSecondary/70">Try adjusting your search query.</p>
        </div>

        <!-- 2. Multi-Tiered Pet Health Row Cards -->
        <div class="space-y-4" x-show="filteredRecords.length > 0">
            <template x-for="pet in filteredRecords" :key="pet.id">
                <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    
                    <!-- Top Header Banner Row (Clickable to Expand) -->
                    <div class="p-5 flex items-center justify-between cursor-pointer select-none"
                         @click="toggleExpand(pet.id)">
                        
                        <!-- Left side avatar + info + status badge -->
                        <div class="flex items-center gap-4">
                            <!-- Avatar Letter Card Badge -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-extrabold text-lg shadow-sm border border-slate-100 dark:border-slate-700/50"
                                 :class="statusAvatarClass(pet.status)">
                                <span x-text="pet.avatarLetter"></span>
                            </div>
                            
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm sm:text-base font-bold text-admin-textPrimary" x-text="pet.name"></h3>
                                    
                                    <!-- Health Status Pill -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border"
                                          :class="statusPillClass(pet.status)">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse" :class="statusBulletClass(pet.status)"></span>
                                        <span x-text="pet.status"></span>
                                    </span>
                                </div>
                                <p class="text-xs text-admin-textSecondary mt-0.5 font-medium" x-text="pet.breed"></p>
                            </div>
                        </div>

                        <!-- Right side: Last Checkup & Expansion Indicator -->
                        <div class="flex items-center gap-4 text-xs font-medium text-admin-textSecondary">
                            <div class="hidden sm:block">
                                <span class="text-slate-400 dark:text-slate-300">Last Checkup:</span>
                                <span class="font-bold text-admin-textPrimary" x-text="pet.lastCheckup"></span>
                            </div>
                            
                            <!-- Chevron icon rotating on expand -->
                            <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300"
                               :class="{ 'rotate-180 text-brand-orange': expandedRecordId === pet.id, 'text-slate-400 dark:text-slate-300': expandedRecordId !== pet.id }"></i>
                        </div>

                    </div>

                    <!-- Middle Detailed Flex Columns (Expandable View) -->
                    <div x-show="expandedRecordId === pet.id"
                         class="border-t border-admin-border/60 bg-admin-bg/10 p-5 space-y-6">
                        
                        <!-- Grid layout split into two columns: Vaccinations and Appointments -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Left Column: VACCINATIONS -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-slate-400 dark:text-slate-300 font-bold text-[10px] uppercase tracking-wider">
                                    <i data-lucide="shield" class="w-4 h-4 text-emerald-500"></i>
                                    <span>VACCINATIONS</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <template x-for="vaccine in pet.vaccinations" :key="vaccine.name">
                                        <div class="flex items-center justify-between p-3 bg-admin-surface border border-admin-border/60 rounded-xl shadow-xs hover:shadow transition-shadow">
                                            <div class="flex items-center gap-3">
                                                <!-- Green checkmark for active, amber clock/alert for missing -->
                                                <template x-if="vaccine.status">
                                                    <div class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                                        <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                                                    </div>
                                                </template>
                                                <template x-if="!vaccine.status">
                                                    <div class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                                        <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                                    </div>
                                                </template>
                                                <span class="text-xs font-semibold text-admin-textPrimary" x-text="vaccine.name"></span>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded"
                                                  :class="vaccine.status ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400'">
                                                <span x-text="vaccine.status ? 'Up to Date' : 'Pending'"></span>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Right Column: APPOINTMENTS -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-slate-400 dark:text-slate-300 font-bold text-[10px] uppercase tracking-wider">
                                    <i data-lucide="calendar" class="w-4 h-4 text-brand-orange"></i>
                                    <span>APPOINTMENTS</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Last Checkup Card -->
                                    <div class="p-4 bg-admin-surface border border-admin-border/60 rounded-xl shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Last Checkup</span>
                                        <div class="mt-2 flex items-center gap-2.5">
                                            <div class="p-1.5 bg-slate-100 dark:bg-[#111c2a] rounded-lg text-slate-500 dark:text-slate-300">
                                                <i data-lucide="calendar-days" class="w-4.5 h-4.5"></i>
                                            </div>
                                            <span class="text-sm font-bold text-admin-textPrimary" x-text="pet.lastCheckup"></span>
                                        </div>
                                    </div>

                                    <!-- Next Appointment Card -->
                                    <div class="p-4 bg-admin-surface border border-admin-border/60 rounded-xl shadow-xs">
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Next Appointment</span>
                                        <div class="mt-2 flex items-center gap-2.5">
                                            <div class="p-1.5 bg-brand-orange/10 rounded-lg text-brand-orange">
                                                <i data-lucide="clock" class="w-4.5 h-4.5"></i>
                                            </div>
                                            <span class="text-sm font-bold text-admin-textPrimary" x-text="pet.nextAppointment || 'None Scheduled'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Management Action Triggers (Edit, View, Delete) -->
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-admin-border/60">
                            <!-- Generate QR -->
                            <button @click.stop="generateQrCode(pet)"
                                    class="text-xs border border-brand-navy/20 text-brand-navy dark:border-slate-700/40 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-md flex items-center gap-1.5 font-semibold transition-colors">
                                <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                                <span>Generate QR</span>
                            </button>

                            <!-- View History -->
                            <button @click.stop="openViewHistory(pet)"
                                    class="text-xs border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1.5">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i>
                                <span>View History</span>
                            </button>

                            <!-- Edit Record -->
                            <button @click.stop="openEditModal(pet)"
                                    class="text-xs border border-brand-orange/30 text-brand-orange hover:bg-brand-cream dark:hover:bg-brand-orange/10 px-3 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1.5">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                <span>Edit Record</span>
                            </button>

                            <!-- Delete -->
                            <button @click.stop="deleteRecord(pet.id)"
                                    class="text-xs text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 px-3 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1.5">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Delete</span>
                            </button>
                        </div>

                    </div>
                </div>
            </template>
        </div>

        <!-- ================= ADD RECORD MODAL ================= -->
        <div x-show="isAddModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isAddModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"
                 @click="isAddModalOpen = false"></div>

            <div x-show="isAddModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="isAddModalOpen = false">

                <!-- Header -->
                <div class="relative bg-gradient-to-br from-brand-orange/20 via-brand-orange/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                    <button @click="isAddModalOpen = false"
                            class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shadow-inner">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-admin-textPrimary">Add Health Record</h2>
                            <p class="text-xs text-admin-textSecondary">Initialize a new pet medical entry.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Pet Name</label>
                        <input type="text" x-model="addForm.name"
                               placeholder="e.g. Max"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Breed</label>
                        <input type="text" x-model="addForm.breed"
                               placeholder="e.g. German Shepherd"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Health Status</label>
                            <select x-model="addForm.status"
                                    class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                                <option value="Healthy">Healthy</option>
                                <option value="Needs Care">Needs Care</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Last Checkup</label>
                            <input type="date" x-model="addForm.lastCheckup"
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Next Appointment</label>
                        <input type="date" x-model="addForm.nextAppointment"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <!-- Vaccinations Setup -->
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-3">Vaccinations Status</label>
                        <div class="space-y-2.5">
                            <template x-for="(vaccine, idx) in addForm.vaccinations" :key="vaccine.name">
                                <div class="flex items-center justify-between p-3 bg-admin-bg/50 border border-admin-border rounded-xl">
                                    <span class="text-xs font-bold text-admin-textPrimary" x-text="vaccine.name"></span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="vaccine.status" class="sr-only peer">
                                        <div class="w-9 h-5 bg-slate-200 dark:bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 dark:after:border-slate-500 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                        <span class="ml-2 text-xs font-semibold text-admin-textSecondary" x-text="vaccine.status ? 'Active' : 'Pending'"></span>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button @click="isAddModalOpen = false"
                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 bg-white dark:bg-[#1a2333] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Cancel
                    </button>
                    <button @click="saveNewRecord()"
                            class="bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Create Record
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= EDIT RECORD MODAL ================= -->
        <div x-show="isEditModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isEditModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"
                 @click="isEditModalOpen = false"></div>

            <div x-show="isEditModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="isEditModalOpen = false">

                <!-- Header -->
                <div class="relative bg-gradient-to-br from-brand-orange/20 via-brand-orange/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                    <button @click="isEditModalOpen = false"
                            class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shadow-inner">
                            <i data-lucide="edit" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-admin-textPrimary">Edit Health Record</h2>
                            <p class="text-xs text-admin-textSecondary">Update medical information for <span x-text="editForm.name" class="font-bold text-brand-orange"></span>.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Pet Name</label>
                        <input type="text" x-model="editForm.name"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Breed</label>
                        <input type="text" x-model="editForm.breed"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Health Status</label>
                            <select x-model="editForm.status"
                                    class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                                <option value="Healthy">Healthy</option>
                                <option value="Needs Care">Needs Care</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Last Checkup</label>
                            <input type="date" x-model="editForm.lastCheckup"
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Next Appointment</label>
                        <input type="date" x-model="editForm.nextAppointment"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>

                    <!-- Vaccinations Setup -->
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-3">Vaccinations Status</label>
                        <div class="space-y-2.5">
                            <template x-for="(vaccine, idx) in editForm.vaccinations" :key="vaccine.name">
                                <div class="flex items-center justify-between p-3 bg-admin-bg/50 border border-admin-border rounded-xl">
                                    <span class="text-xs font-bold text-admin-textPrimary" x-text="vaccine.name"></span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" x-model="vaccine.status" class="sr-only peer">
                                        <div class="w-9 h-5 bg-slate-200 dark:bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 dark:after:border-slate-500 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                        <span class="ml-2 text-xs font-semibold text-admin-textSecondary" x-text="vaccine.status ? 'Active' : 'Pending'"></span>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button @click="isEditModalOpen = false"
                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 bg-white dark:bg-[#1a2333] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Cancel
                    </button>
                    <button @click="saveEditRecord()"
                            class="bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= VIEW HISTORY MODAL ================= -->
        <div x-show="isViewHistoryOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isViewHistoryOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"
                 @click="isViewHistoryOpen = false"></div>

            <div x-show="isViewHistoryOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="isViewHistoryOpen = false">

                <!-- Header -->
                <div class="relative bg-gradient-to-br from-brand-orange/20 via-brand-orange/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                    <button @click="isViewHistoryOpen = false"
                            class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shadow-inner">
                            <i data-lucide="history" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-admin-textPrimary">Medical History Timeline</h2>
                            <p class="text-xs text-admin-textSecondary">Full medical archive for <span x-text="activeRecord?.name" class="font-bold text-brand-orange"></span>.</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline Body -->
                <div class="p-6 space-y-6">
                    <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-800 space-y-6">
                        <template x-for="item in activeRecord?.history" :key="item.date">
                            <div class="relative">
                                <!-- Dot indicator -->
                                <span class="absolute -left-[31px] top-1 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-admin-surface border-2 border-brand-orange shadow-sm animate-pulse">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-orange"></span>
                                </span>
                                
                                <div>
                                    <span class="inline-block text-[10px] font-bold text-slate-400 dark:text-slate-300 bg-slate-100 dark:bg-[#111c2a] px-2 py-0.5 rounded" x-text="item.date"></span>
                                    <h4 class="text-sm font-extrabold text-admin-textPrimary mt-1.5" x-text="item.title"></h4>
                                    <p class="text-xs text-admin-textSecondary mt-1 leading-relaxed font-medium" x-text="item.notes"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end rounded-b-2xl">
                    <button @click="isViewHistoryOpen = false"
                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 bg-white dark:bg-[#1a2333] text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5">
                        Close History
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= QR CODE MODAL ================= -->
        <div x-show="isQrModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isQrModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"
                 @click="isQrModalOpen = false"></div>

            <div x-show="isQrModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-sm bg-brand-cream border border-brand-orange/20 rounded-2xl shadow-2xl overflow-hidden p-6 text-center"
                 @click.away="isQrModalOpen = false">

                <!-- Top: Header -->
                <div class="mb-4">
                    <h2 class="text-sm font-extrabold text-brand-navy tracking-wider uppercase">ResQNet Digital Animal ID Tag</h2>
                    <p class="text-xs text-brand-orange mt-1 font-bold">
                        <span x-text="activeRecord?.name"></span> &bull; <span x-text="activeRecord?.breed" class="font-semibold text-slate-500 dark:text-slate-300"></span>
                    </p>
                </div>

                <!-- Center: QR Code Container -->
                <div class="flex flex-col items-center my-6">
                    <div id="pet-qr-code" class="flex justify-center p-4 bg-white dark:bg-[#111c2a] rounded-xl shadow-sm border border-slate-100 dark:border-slate-700/40"></div>
                    
                    <!-- Simulated Scan Action Link -->
                    <a href="#" @click.prevent="search = activeRecord.name; isQrModalOpen = false; expandedRecordId = activeRecord.id"
                       class="text-xs text-brand-orange hover:text-brand-orange/80 hover:underline font-bold mt-4 inline-flex items-center gap-1">
                        <i data-lucide="scan" class="w-3.5 h-3.5"></i>
                        <span>Simulate Scan (Scan Link)</span>
                    </a>

                    <!-- Action Buttons: Download & Print -->
                    <div class="flex items-center justify-center gap-4 mt-4 w-full">
                        <button @click="downloadQrCode()"
                                class="text-xs font-bold text-brand-navy hover:text-brand-orange flex items-center gap-1.5 bg-slate-100 dark:bg-[#111c2a] hover:bg-slate-200 dark:hover:bg-slate-700 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            <span>Download</span>
                        </button>
                        <button @click="printQrCode()"
                                class="text-xs font-bold text-brand-navy hover:text-brand-orange flex items-center gap-1.5 bg-slate-100 dark:bg-[#111c2a] hover:bg-slate-200 dark:hover:bg-slate-700 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Print</span>
                        </button>
                    </div>
                </div>

                <!-- Bottom: Instruction and Close Button -->
                <div class="space-y-4">
                    <p class="text-[11px] text-slate-500 dark:text-slate-300 leading-relaxed font-medium">
                        Scan this QR code with a mobile device to instantly fetch and display this pet's live vaccination and checkup tracking history.
                    </p>
                    <button @click="isQrModalOpen = false"
                            class="border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 bg-white dark:bg-[#1a2333] text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 flex items-center justify-center gap-1.5 w-full">
                        Close
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Block for State Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('healthManager', () => ({
                search: '',
                isAddModalOpen: false,
                isEditModalOpen: false,
                isViewHistoryOpen: false,
                isQrModalOpen: false,
                expandedRecordId: null,
                activeRecord: null,
                
                // Form states
                addForm: {
                    name: '',
                    breed: '',
                    status: 'Healthy',
                    lastCheckup: '',
                    nextAppointment: '',
                    vaccinations: [
                        { name: 'Rabies', status: false },
                        { name: 'DHPP', status: false },
                        { name: 'Bordetella', status: false }
                    ]
                },
                editForm: {
                    id: null,
                    name: '',
                    breed: '',
                    status: 'Healthy',
                    lastCheckup: '',
                    nextAppointment: '',
                    vaccinations: []
                },

                records: [
                    {
                        id: 1,
                        name: 'Max',
                        breed: 'German Shepherd',
                        avatarLetter: 'M',
                        status: 'Healthy',
                        lastCheckup: '2026-05-10',
                        nextAppointment: '2026-07-15',
                        vaccinations: [
                            { name: 'Rabies', status: true },
                            { name: 'DHPP', status: true },
                            { name: 'Bordetella', status: false }
                        ],
                        history: [
                            { date: '2026-05-10', title: 'Routine Checkup', notes: 'Overall healthy condition. Weight: 32kg. No concerns.' },
                            { date: '2026-02-12', title: 'Vaccine Administered', notes: 'DHPP vaccine booster given.' },
                            { date: '2025-11-05', title: 'Minor Treatment', notes: 'Treated for mild ear infection. Prescribed ear drops for 7 days.' }
                        ]
                    },
                    {
                        id: 2,
                        name: 'Luna',
                        breed: 'Siberian Husky',
                        avatarLetter: 'L',
                        status: 'Needs Care',
                        lastCheckup: '2026-04-22',
                        nextAppointment: '2026-06-12',
                        vaccinations: [
                            { name: 'Rabies', status: true },
                            { name: 'DHPP', status: false },
                            { name: 'Bordetella', status: false }
                        ],
                        history: [
                            { date: '2026-04-22', title: 'Diagnostic Checkup', notes: 'Maxillary dental assessment. Needs cleaning. Slightly underweight (21kg).' },
                            { date: '2026-01-15', title: 'Vaccine Administered', notes: 'Rabies vaccine booster given.' }
                        ]
                    },
                    {
                        id: 3,
                        name: 'Charlie',
                        breed: 'Golden Retriever',
                        avatarLetter: 'C',
                        status: 'Healthy',
                        lastCheckup: '2026-06-01',
                        nextAppointment: '2026-08-01',
                        vaccinations: [
                            { name: 'Rabies', status: true },
                            { name: 'DHPP', status: true },
                            { name: 'Bordetella', status: true }
                        ],
                        history: [
                            { date: '2026-06-01', title: 'Semi-annual Wellness Exam', notes: 'Excellent health. Coat is shiny, teeth are clean. Weight: 30kg.' }
                        ]
                    }
                ],

                get filteredRecords() {
                    return this.records.filter(r => {
                        const q = this.search.toLowerCase();
                        return r.name.toLowerCase().includes(q) || r.breed.toLowerCase().includes(q);
                    });
                },

                toggleExpand(id) {
                    if (this.expandedRecordId === id) {
                        this.expandedRecordId = null;
                    } else {
                        this.expandedRecordId = id;
                    }
                },

                openAddModal() {
                    this.addForm = {
                        name: '',
                        breed: '',
                        status: 'Healthy',
                        lastCheckup: new Date().toISOString().split('T')[0],
                        nextAppointment: '',
                        vaccinations: [
                            { name: 'Rabies', status: false },
                            { name: 'DHPP', status: false },
                            { name: 'Bordetella', status: false }
                        ]
                    };
                    this.isAddModalOpen = true;
                },

                saveNewRecord() {
                    if (!this.addForm.name.trim() || !this.addForm.breed.trim()) {
                        alert('Please fill out the pet name and breed.');
                        return;
                    }
                    const newId = this.records.length ? Math.max(...this.records.map(r => r.id)) + 1 : 1;
                    const letter = this.addForm.name.charAt(0).toUpperCase() || 'P';
                    this.records.unshift({
                        id: newId,
                        name: this.addForm.name,
                        breed: this.addForm.breed,
                        avatarLetter: letter,
                        status: this.addForm.status,
                        lastCheckup: this.addForm.lastCheckup,
                        nextAppointment: this.addForm.nextAppointment,
                        vaccinations: JSON.parse(JSON.stringify(this.addForm.vaccinations)),
                        history: [
                            { date: this.addForm.lastCheckup, title: 'Record Created', notes: 'Initial health profile created.' }
                        ]
                    });
                    this.isAddModalOpen = false;
                    this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                },

                openEditModal(record) {
                    this.activeRecord = record;
                    this.editForm = JSON.parse(JSON.stringify(record));
                    this.isEditModalOpen = true;
                },

                saveEditRecord() {
                    if (!this.editForm.name.trim() || !this.editForm.breed.trim()) {
                        alert('Please fill out the pet name and breed.');
                        return;
                    }
                    const idx = this.records.findIndex(r => r.id === this.editForm.id);
                    if (idx !== -1) {
                        this.records[idx] = JSON.parse(JSON.stringify(this.editForm));
                        this.records[idx].avatarLetter = this.editForm.name.charAt(0).toUpperCase() || 'P';
                    }
                    this.isEditModalOpen = false;
                    this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                },

                openViewHistory(record) {
                    this.activeRecord = record;
                    this.isViewHistoryOpen = true;
                },

                deleteRecord(id) {
                    if (confirm("Are you sure you want to remove this animal's medical history?")) {
                        this.records = this.records.filter(r => r.id !== id);
                        if (this.expandedRecordId === id) {
                            this.expandedRecordId = null;
                        }
                    }
                },

                statusAvatarClass(status) {
                    if (status === 'Healthy') return 'bg-emerald-500 text-white';
                    if (status === 'Needs Care') return 'bg-amber-500 text-white';
                    return 'bg-rose-500 text-white';
                },

                statusPillClass(status) {
                    if (status === 'Healthy') return 'bg-emerald-50 text-emerald-700 border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30';
                    if (status === 'Needs Care') return 'bg-amber-50 text-amber-700 border-amber-200/50 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30';
                    return 'bg-rose-50 text-rose-700 border-rose-200/50 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30';
                },

                statusBulletClass(status) {
                    if (status === 'Healthy') return 'bg-emerald-500';
                    if (status === 'Needs Care') return 'bg-amber-500';
                    return 'bg-rose-500';
                },

                init() {
                    // Check URL parameters for simulated scan on page load
                    const urlParams = new URLSearchParams(window.location.search);
                    const searchQuery = urlParams.get('search');
                    if (searchQuery) {
                        this.search = searchQuery;
                        const matched = this.records.find(r => r.name.toLowerCase() === searchQuery.trim().toLowerCase());
                        if (matched) {
                            this.expandedRecordId = matched.id;
                        }
                    }
                    
                    // Watch search input to instantly filter & expand matching pet card
                    this.$watch('search', value => {
                        const cleanVal = value.trim().toLowerCase();
                        if (cleanVal) {
                            const matched = this.records.find(r => r.name.toLowerCase() === cleanVal);
                            if (matched) {
                                this.expandedRecordId = matched.id;
                            }
                        }
                    });
                },

                generateQrCode(pet) {
                    this.activeRecord = pet;
                    this.isQrModalOpen = true;
                    this.$nextTick(() => {
                        const qrContainer = document.getElementById('pet-qr-code');
                        if (qrContainer) {
                            qrContainer.innerHTML = '';
                            const scanUrl = `${window.location.origin}${window.location.pathname}?search=${encodeURIComponent(pet.name)}`;
                            new QRCode(qrContainer, {
                                text: scanUrl,
                                width: 160,
                                height: 160,
                                colorDark : "#111827",
                                colorLight : "#ffffff",
                                correctLevel : QRCode.CorrectLevel.H
                            });
                        }
                    });
                },

                downloadQrCode() {
                    const qrContainer = document.getElementById('pet-qr-code');
                    const img = qrContainer.querySelector('img');
                    const canvas = qrContainer.querySelector('canvas');
                    let dataUrl = '';
                    
                    if (img && img.src) {
                        dataUrl = img.src;
                    } else if (canvas) {
                        dataUrl = canvas.toDataURL('image/png');
                    }
                    
                    if (dataUrl) {
                        const link = document.createElement('a');
                        link.href = dataUrl;
                        link.download = `${this.activeRecord.name}_QR_Code.png`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        alert('Failed to download QR code. Please try again.');
                    }
                },

                printQrCode() {
                    const qrContainer = document.getElementById('pet-qr-code');
                    const img = qrContainer.querySelector('img');
                    const canvas = qrContainer.querySelector('canvas');
                    let imgHtml = '';
                    
                    if (img && img.src) {
                        imgHtml = `<img src="${img.src}" style="width: 250px; height: 250px;" />`;
                    } else if (canvas) {
                        imgHtml = `<img src="${canvas.toDataURL('image/png')}" style="width: 250px; height: 250px;" />`;
                    }
                    
                    if (imgHtml) {
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                            <html>
                                <head>
                                    <title>Print QR Code - ${this.activeRecord.name}</title>
                                    <style>
                                        body {
                                            font-family: system-ui, -apple-system, sans-serif;
                                            display: flex;
                                            flex-direction: column;
                                            align-items: center;
                                            justify-content: center;
                                            height: 100vh;
                                            margin: 0;
                                            text-align: center;
                                            background-color: #FEF7ED;
                                            color: #111827;
                                        }
                                        .container {
                                            border: 2px solid #EA7235;
                                            border-radius: 20px;
                                            padding: 30px;
                                            background-color: white;
                                            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                                        }
                                        h1 { font-size: 20px; margin: 0 0 5px 0; letter-spacing: 0.05em; text-transform: uppercase; color: #0F172A; }
                                        h2 { font-size: 16px; margin: 0 0 20px 0; color: #EA7235; }
                                        p { font-size: 11px; color: #64748B; max-width: 280px; margin-top: 15px; }
                                    </style>
                                </head>
                                <body>
                                    <div class="container">
                                        <h1>ResQNet Digital ID Tag</h1>
                                        <h2>${this.activeRecord.name} &bull; ${this.activeRecord.breed}</h2>
                                        ${imgHtml}
                                        <p>Scan this QR code with a mobile device to instantly fetch and display this pet's live vaccination and checkup tracking history.</p>
                                    </div>
                                    <script>
                                        window.onload = function() {
                                            window.print();
                                            setTimeout(function() { window.close(); }, 500);
                                        };
                                    <\/script>
                                </body>
                            </html>
                        `);
                        printWindow.document.close();
                    } else {
                        alert('Failed to print QR code. Please try again.');
                    }
                }
            }));
        });
    </script>
    
    <!-- QRCode Generator Engine Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</x-layouts.admin>
