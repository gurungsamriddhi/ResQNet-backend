<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 bg-slate-50 min-h-screen"
         x-data="organizationManager()"
         x-init="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())"
         x-effect="if (typeof lucide !== 'undefined') lucide.createIcons()">

        <!-- ===== PAGE HEADER ===== -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Partner organizations</h1>
                <p class="text-slate-600 font-medium text-sm mt-1">Verify and onboard rescue groups so they can save more lives.</p>
            </div>
            <button @click="inviteOrganization()"
                    class="border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-medium px-4 py-2 rounded-xl shadow-sm transition-all flex items-center gap-2 h-fit">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>Invite organization</span>
            </button>
        </div>

        <!-- ===== AWAITING VERIFICATION ===== -->
        <div x-show="pendingOrganizations.length > 0"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm mt-8 mb-6">
                <!-- Container Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-slate-900 font-bold text-lg">Awaiting verification</h2>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="`${pendingOrganizations.length} organizations are ready for review.`"></p>
                    </div>
                    <span class="bg-amber-100 text-amber-800 font-bold text-[10px] tracking-wide uppercase px-2.5 py-1 rounded-md whitespace-nowrap">
                        Action needed
                    </span>
                </div>

                <!-- Pending Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
                    <template x-for="org in pendingOrganizations" :key="org.id">
                        <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between"
                             x-show="!org.removing"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">

                            <!-- Header Meta Line -->
                            <div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="building" class="w-5 h-5 text-slate-500"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-slate-800 font-bold text-base block leading-snug" x-text="org.name"></span>
                                        <div class="flex flex-wrap items-center gap-x-2.5 gap-y-0.5 mt-1">
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                <span x-text="org.city"></span>
                                            </span>
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="layers" class="w-3 h-3"></i>
                                                <span x-text="org.capacity"></span>
                                            </span>
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <span>Submitted <span x-text="org.submittedAt"></span></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Access Row -->
                            <button @click="reviewDocs(org.id)"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-semibold py-2 px-3 rounded-lg flex items-center justify-center gap-2 mt-4 transition-colors cursor-pointer">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                <span>Review docs</span>
                            </button>

                            <!-- Action Forms Group -->
                            <div class="flex items-center justify-end gap-2 mt-4 pt-3 border-t border-slate-100">
                                <button @click="rejectOrg(org.id)"
                                        class="border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Reject</span>
                                </button>
                                <button @click="approveOrg(org.id)"
                                        class="bg-[#5BA44C] hover:bg-emerald-600 text-white text-xs font-semibold px-4 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors shadow-sm">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    <span>Approve</span>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- ===== VERIFIED PARTNERS ===== -->
        <div x-show="verifiedOrganizations.length > 0"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="text-slate-900 font-bold text-xl mb-4 mt-8">Verified partners</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template x-for="org in verifiedOrganizations" :key="org.id">
                    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm flex flex-col justify-between transition-all duration-300"
                         x-show="!org.removing"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95">

                        <!-- Top: Avatar + Name + City -->
                        <div>
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-full bg-teal-700 border-2 border-teal-600/20 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-slate-800 font-bold text-base block leading-snug" x-text="org.name"></span>
                                    <span class="text-slate-500 font-medium text-xs flex items-center gap-1 mt-0.5">
                                        <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                                        <span x-text="org.city"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="mt-3 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Established</span>
                                    <span class="font-semibold text-slate-700" x-text="org.established"></span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Animals in care</span>
                                    <span class="font-semibold text-slate-700" x-text="`${org.animalsInCare} animals`"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Controls -->
                        <div class="flex items-center justify-between gap-2 mt-4 pt-3 border-t border-slate-100">
                            <span class="bg-emerald-50 text-emerald-700 font-bold text-[11px] tracking-wide px-3 py-1 rounded-full flex items-center justify-center shadow-inner border border-emerald-200/50">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5 mr-1"></i>
                                Verified
                            </span>
                            <button @click="suspendOrg(org.id)"
                                    class="text-xs text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 px-2.5 py-1 rounded-md transition-colors flex items-center gap-1.5">
                                <i data-lucide="shield-off" class="w-3 h-3"></i>
                                Suspend Account
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ===== EMPTY STATE ===== -->
        <div x-show="pendingOrganizations.length === 0 && verifiedOrganizations.length === 0"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-14 text-center mt-8">
            <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="building-2" class="w-7 h-7 text-slate-300"></i>
            </div>
            <p class="text-base font-semibold text-slate-600">No partner organizations yet</p>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Invite rescue groups to join the network and start collaborating to save more animals.</p>
            <button @click="inviteOrganization()"
                    class="mt-5 inline-flex items-center gap-2 bg-[#5BA44C] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-emerald-600 active:scale-[0.97] shadow-sm transition-all">
                <i data-lucide="mail-plus" class="w-4 h-4"></i>
                <span>Send your first invitation</span>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('organizationManager', () => ({
                pendingOrganizations: [
                    {
                        id: 1,
                        name: 'Bahay Aspin',
                        city: 'Kathmandu, Nepal',
                        capacity: '45 animals',
                        submittedAt: '2 days ago'
                    },
                    {
                        id: 2,
                        name: 'Pawsitive Cebu',
                        city: 'Pokhara, Nepal',
                        capacity: '30 animals',
                        submittedAt: '5 days ago'
                    },
                    {
                        id: 3,
                        name: 'PAWS Philippines',
                        city: 'Lalitpur, Nepal',
                        capacity: '80 animals',
                        submittedAt: '1 week ago'
                    },
                    {
                        id: 4,
                        name: 'Animal Kingdom Rescue',
                        city: 'Bharatpur, Nepal',
                        capacity: '25 animals',
                        submittedAt: '2 weeks ago'
                    }
                ],
                verifiedOrganizations: [
                    {
                        id: 5,
                        name: 'PAWS Manila',
                        city: 'Kathmandu, Nepal',
                        established: '2019',
                        animalsInCare: 42
                    },
                    {
                        id: 6,
                        name: 'CARA Welfare',
                        city: 'Pokhara, Nepal',
                        established: '2020',
                        animalsInCare: 35
                    },
                    {
                        id: 7,
                        name: 'Compassion & Responsibility',
                        city: 'Lalitpur, Nepal',
                        established: '2018',
                        animalsInCare: 28
                    },
                    {
                        id: 8,
                        name: 'ResQNet Animal Shelter',
                        city: 'Pokhara, Nepal',
                        established: '2021',
                        animalsInCare: 19
                    },
                    {
                        id: 9,
                        name: 'Happy Tails Foundation',
                        city: 'Lalitpur, Nepal',
                        established: '2019',
                        animalsInCare: 31
                    }
                ],

                approveOrg(id) {
                    const org = this.pendingOrganizations.find(o => o.id === id);
                    if (org) {
                        org.removing = true;
                        setTimeout(() => {
                            this.pendingOrganizations = this.pendingOrganizations.filter(o => o.id !== id);
                            this.verifiedOrganizations.unshift({
                                id: id,
                                name: org.name,
                                city: org.city,
                                established: new Date().getFullYear().toString(),
                                animalsInCare: parseInt(org.capacity) || 0
                            });
                        }, 300);
                    }
                },

                rejectOrg(id) {
                    const org = this.pendingOrganizations.find(o => o.id === id);
                    if (org && confirm(`Reject ${org.name}'s verification request?`)) {
                        org.removing = true;
                        setTimeout(() => {
                            this.pendingOrganizations = this.pendingOrganizations.filter(o => o.id !== id);
                        }, 300);
                    }
                },

                reviewDocs(id) {
                    const org = this.pendingOrganizations.find(o => o.id === id);
                    if (org) {
                        alert(`Opening document review panel for ${org.name}...`);
                    }
                },

                suspendOrg(id) {
                    const org = this.verifiedOrganizations.find(o => o.id === id);
                    if (org && confirm(`Are you sure you want to suspend ${org.name}? This will immediately disable their access to the platform and remove all active listings.`)) {
                        org.removing = true;
                        setTimeout(() => {
                            this.verifiedOrganizations = this.verifiedOrganizations.filter(o => o.id !== id);
                        }, 300);
                    }
                },

                inviteOrganization() {
                    alert('An invitation link will be sent to the organization admin\'s email address.');
                }
            }));
        });
    </script>
</x-layouts.admin>
