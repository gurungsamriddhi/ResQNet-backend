<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="volunteerManager()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Volunteer Applications</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Review and manage pending volunteer signups for your organization</p>
            </div>
        </div>

        <!-- Success Toast -->
        <div x-show="successMessage"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-4 py-3 rounded-xl flex items-center gap-2"
             style="display: none;">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            <span x-text="successMessage"></span>
        </div>

        <!-- Volunteer Applications Cards -->
        <div class="space-y-4">
            <template x-for="app in applications" :key="app.email">
                <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <!-- Left: Avatar + Info -->
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold text-white uppercase shadow-sm flex-shrink-0"
                                     x-text="app.name.charAt(0)">
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-slate-800 dark:text-slate-100 block" x-text="app.name"></span>
                                    <span class="text-xs text-slate-500" x-text="app.email"></span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">
                                        Applied <span x-text="app.appliedAt"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Center: Skill badges -->
                            <div class="flex flex-wrap items-center gap-1.5">
                                <template x-for="skill in app.skills" :key="skill">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200/50 dark:bg-sky-950/20 dark:text-sky-400 dark:border-sky-900/30">
                                        <i data-lucide="badge-check" class="w-3 h-3"></i>
                                        <span x-text="skill"></span>
                                    </span>
                                </template>
                            </div>

                            <!-- Right: Accept / Reject -->
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button @click="acceptApplication(app.email)"
                                        class="bg-[#5BA44C] text-white text-xs font-semibold px-4 py-1.5 rounded-lg flex items-center gap-1.5 transition-all duration-200 hover:bg-[#5BA44C]/90 active:scale-95 shadow-sm">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                    <span>Accept</span>
                                </button>
                                <button @click="rejectApplication(app.email)"
                                        class="border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 transition-all duration-200">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Reject</span>
                                </button>
                            </div>
                        </div>

                        <!-- View Details Trigger -->
                        <div class="mt-3 pt-3 border-t border-admin-border/50">
                            <button @click="toggleDetails(app.email)"
                                    class="text-xs font-semibold text-brand-orange hover:text-brand-orange/80 transition-colors flex items-center gap-1">
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-200"
                                   :class="expandedRow === app.email ? 'rotate-180' : ''"></i>
                                <span>View Details</span>
                            </button>
                        </div>
                    </div>

                    <!-- Expandable Details Shelf -->
                    <div x-show="expandedRow === app.email"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-[800px]"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-[800px]"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="border-t border-admin-border bg-gradient-to-b from-admin-bg/40 to-transparent"
                         style="display: none;">
                        <div class="p-5 space-y-5">
                            <!-- Three-column data cards -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white dark:bg-slate-800/60 rounded-xl border border-admin-border/60 p-3.5 flex items-start gap-3 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Contact</span>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block truncate" x-text="app.contact"></span>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-slate-800/60 rounded-xl border border-admin-border/60 p-3.5 flex items-start gap-3 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Availability Sector</span>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block truncate" x-text="app.sector"></span>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-slate-800/60 rounded-xl border border-admin-border/60 p-3.5 flex items-start gap-3 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-500 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Emergency Contact</span>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 block truncate" x-text="app.emergencyContact"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Field Mission Timeline -->
                            <div class="bg-white dark:bg-slate-800/60 rounded-xl border border-admin-border/60 p-4 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-6 h-6 rounded-md bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                                        <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-500">Field Mission Log</span>
                                    <span class="ml-auto bg-amber-100 text-amber-700 text-[9px] font-extrabold px-2 py-0.5 rounded-full">Active</span>
                                </div>
                                <div class="relative pl-6 space-y-4 before:absolute before:left-[7px] before:top-1 before:bottom-1 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-700">
                                    <div class="relative">
                                        <div class="absolute -left-[18px] top-0.5 w-3.5 h-3.5 rounded-full bg-amber-400 border-2 border-white dark:border-slate-800 shadow-sm flex items-center justify-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                        </div>
                                        <div class="bg-amber-50 dark:bg-amber-950/20 rounded-lg px-3 py-2 border border-amber-200/40 dark:border-amber-900/20">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Responding to case RQ-2417</span>
                                                <span class="text-[9px] font-bold text-amber-600 uppercase bg-amber-100 dark:bg-amber-950/40 px-1.5 py-0.5 rounded">In Progress</span>
                                            </div>
                                            <span class="block text-[10px] text-slate-400 mt-0.5 flex items-center gap-1.5">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                Pokhara Zone
                                            </span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute -left-[18px] top-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white dark:border-slate-800 shadow-sm"></div>
                                        <div>
                                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Resolved case RQ-2398</span>
                                            <span class="block text-[10px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                                <i data-lucide="check-circle" class="w-3 h-3 text-emerald-400"></i>
                                                Completed • 3 days ago
                                            </span>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute -left-[18px] top-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white dark:border-slate-800 shadow-sm"></div>
                                        <div>
                                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Resolved case RQ-2371</span>
                                            <span class="block text-[10px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                                <i data-lucide="check-circle" class="w-3 h-3 text-emerald-400"></i>
                                                Completed • 1 week ago
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty state -->
            <div x-show="applications.length === 0"
                 class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-8 text-center">
                <i data-lucide="clipboard-check" class="w-10 h-10 text-emerald-400 mx-auto mb-3"></i>
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">All applications reviewed</p>
                <p class="text-xs text-slate-400 mt-1">No pending volunteer applications at this time.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('volunteerManager', () => ({
                expandedRow: null,
                successMessage: '',
                applications: [
                    {
                        name: 'Kiran Thapa',
                        email: 'kiran@example.com',
                        skills: ['Has Vehicle', 'First-Aid Trained'],
                        appliedAt: '2 hours ago',
                        contact: '9851234567',
                        sector: 'Pokhara Sector-9',
                        emergencyContact: 'Sita Thapa (9847654321)'
                    },
                    {
                        name: 'Anjali Shrestha',
                        email: 'anjali@example.com',
                        skills: ['Veterinary Assistant', 'Driver'],
                        appliedAt: '5 hours ago',
                        contact: '9849876543',
                        sector: 'Kathmandu Sector-3',
                        emergencyContact: 'Ramesh Shrestha (9855554433)'
                    }
                ],

                toggleDetails(email) {
                    this.expandedRow = this.expandedRow === email ? null : email;
                },

                acceptApplication(email) {
                    const app = this.applications.find(a => a.email === email);
                    if (app) {
                        this.applications = this.applications.filter(a => a.email !== email);
                        this.successMessage = `${app.name} has been accepted to the team!`;
                        setTimeout(() => { this.successMessage = ''; }, 4000);
                    }
                },

                rejectApplication(email) {
                    const app = this.applications.find(a => a.email === email);
                    if (app && confirm(`Reject ${app.name}'s application?`)) {
                        this.applications = this.applications.filter(a => a.email !== email);
                    }
                }
            }));
        });
    </script>
</x-layouts.admin>
