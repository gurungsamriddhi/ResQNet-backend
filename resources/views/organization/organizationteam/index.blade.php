<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-8"
         x-data="volunteerManager()"
         x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons() })"
         id="volunteer-dashboard-container">

        <!-- Page Header & Action Controls -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 dark:border-slate-700/40 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <span class="bg-brand-orange/10 text-brand-orange p-2 rounded-xl">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </span>
                    Team Members Portal
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-300 mt-2">Team members ready for rescue task dispatching.</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showInviteModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-orange hover:bg-brand-orange-dark text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add Team Member
                </button>
            </div>
        </div>

        <!-- Team Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Active Team</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="stats.active">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-300">
                    <span class="font-bold text-emerald-500" x-text="stats.available">0</span>
                    <span>available for dispatch</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Total Members</span>
                        <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 mt-1 block" x-text="orgteams.length">0</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-500 flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-300">
                    <span>Registered under your organization</span>
                </div>
            </div>
        </div>

        <!-- Team Members List -->
        <div class="bg-white dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-700/50">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-brand-orange"></i>
                    Team Members
                </h3>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-300 bg-slate-100 dark:bg-[#111c2a] px-2.5 py-1 rounded-lg" x-text="orgteams.length + ' members'"></span>
            </div>

            <div class="space-y-2">
                <template x-for="member in orgteams" :key="member.id">
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700/40">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-brand-navy flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                <span x-text="member.name.charAt(0).toUpperCase()"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate" x-text="member.name"></p>
                                <p class="text-xs text-slate-400 dark:text-slate-300 truncate" x-text="member.email"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold"
                                  :class="member.availability === 'Available' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="member.availability === 'Available' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                <span x-text="member.availability"></span>
                            </span>
                            <span class="text-xs text-slate-400 dark:text-slate-300" x-text="member.contact"></span>
                            <button @click="showInfo(member)"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-orange hover:bg-brand-orange/10 dark:hover:bg-brand-orange/20 transition-colors">
                                <i data-lucide="info" class="w-4 h-4"></i>
                            </button>
                            <button @click="confirmRemove(member)"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="orgteams.length === 0">
                    <div class="text-center py-10">
                        <i data-lucide="users" class="w-10 h-10 mx-auto text-slate-300 mb-3"></i>
                        <h3 class="text-sm font-bold text-slate-500">No team members yet</h3>
                        <p class="text-xs text-slate-400 mt-1">Click "Add Team Member" to build your team.</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- Invite Modal -->
        <div x-cloak x-show="showInviteModal" style="display: none;"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showInviteModal = false"></div>
            <div class="relative bg-white dark:bg-[#0f1a28] rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700/40 w-full max-w-lg p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 @click.outside="showInviteModal = false">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-brand-orange"></i>
                        Add Team Member
                    </h3>
                    <button @click="showInviteModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form @submit.prevent="submitInvite" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" x-model="inviteForm.name" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-[#111c2a] text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-brand-orange/50 focus:border-brand-orange outline-none transition-all placeholder:text-slate-400"
                               placeholder="e.g. Jane Smith">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Email Address</label>
                        <input type="email" x-model="inviteForm.email" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-[#111c2a] text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-brand-orange/50 focus:border-brand-orange outline-none transition-all placeholder:text-slate-400"
                               placeholder="jane@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Phone (optional)</label>
                        <input type="text" x-model="inviteForm.phone"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-[#111c2a] text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-brand-orange/50 focus:border-brand-orange outline-none transition-all placeholder:text-slate-400"
                               placeholder="+1 234 567 890">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Role</label>
                        <select x-model="inviteForm.role" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-[#111c2a] text-slate-800 dark:text-slate-100 text-sm focus:ring-2 focus:ring-brand-orange/50 focus:border-brand-orange outline-none transition-all">
                            <option value="staff">Staff</option>
                            <option value="vet">Veterinarian</option>
                            <option value="volunteer">Volunteer</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="inviteSubmitting"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-orange hover:bg-brand-orange-dark disabled:opacity-50 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-sm">
                            <i data-lucide="check" class="w-4 h-4" x-show="!inviteSubmitting"></i>
                            <svg x-show="inviteSubmitting" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span x-text="inviteSubmitting ? 'Adding...' : 'Add Member'"></span>
                        </button>
                        <button type="button" @click="showInviteModal = false"
                                class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Remove Confirmation Modal -->
        <div x-cloak x-show="showRemoveModal" style="display: none;"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showRemoveModal = false"></div>
            <div class="relative bg-white dark:bg-[#0f1a28] rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700/40 w-full max-w-sm p-6 text-center"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                <div class="w-12 h-12 mx-auto rounded-full bg-red-50 dark:bg-red-950/30 text-red-500 flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mb-2">Remove Team Member?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                    Are you sure you want to remove <span class="font-bold text-slate-700 dark:text-slate-300" x-text="removeTarget?.name"></span>?
                </p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="submitRemove" :disabled="removeSubmitting"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 disabled:opacity-50 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-sm">
                        <svg x-show="removeSubmitting" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span x-text="removeSubmitting ? 'Removing...' : 'Yes, Remove'"></span>
                    </button>
                    <button @click="showRemoveModal = false"
                            class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <!-- Info Modal -->
        <div x-cloak x-show="showInfoModal" style="display: none;"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showInfoModal = false"></div>
            <div class="relative bg-white dark:bg-[#0f1a28] rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700/40 w-full max-w-sm p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 @click.outside="showInfoModal = false">
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100 dark:border-slate-700/50">
                    <h3 class="text-lg font-extrabold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-brand-orange"></i>
                        Team Member Info
                    </h3>
                    <button @click="showInfoModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <template x-if="infoTarget">
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800/50">
                            <span class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">Name</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="infoTarget.name"></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800/50">
                            <span class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">Email</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="infoTarget.email"></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800/50">
                            <span class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">Contact</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="infoTarget.contact"></span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-50 dark:border-slate-800/50">
                            <span class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">Joined Date</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="infoTarget.joinedAt || infoTarget.appliedAt"></span>
                        </div>
                    </div>
                </template>
                
                <div class="mt-6 flex justify-end">
                    <button @click="showInfoModal = false"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold rounded-xl transition-all duration-200 shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('volunteerManager', () => ({
                orgteams: @json($teamMembers ?? []),

                showInviteModal: false,
                inviteForm: { name: '', email: '', phone: '', role: 'staff' },
                inviteSubmitting: false,

                showRemoveModal: false,
                removeTarget: null,
                removeSubmitting: false,

                showInfoModal: false,
                infoTarget: null,

                get stats() {
                    return {
                        active: this.orgteams.filter(v => v.status === 'Active').length,
                        available: this.orgteams.filter(v => v.availability === 'Available').length,
                    };
                },

                async submitInvite() {
                    this.inviteSubmitting = true;
                    try {
                        const res = await fetch('{{ route('organization.orgteams.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.inviteForm),
                        });
                        const data = await res.json();
                        if (res.ok) {
                            this.orgteams.push(data.user);
                            this.showInviteModal = false;
                            this.inviteForm = { name: '', email: '', phone: '', role: 'staff' };
                        } else {
                            alert(data.message || 'Failed to add team member.');
                        }
                    } catch {
                        alert('Network error. Please try again.');
                    } finally {
                        this.inviteSubmitting = false;
                        this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                    }
                },

                confirmRemove(member) {
                    this.removeTarget = member;
                    this.showRemoveModal = true;
                },

                showInfo(member) {
                    this.infoTarget = member;
                    this.showInfoModal = true;
                },

                async submitRemove() {
                    if (!this.removeTarget) return;
                    this.removeSubmitting = true;
                    try {
                        const res = await fetch('{{ route('organization.orgteams.remove', ['user' => '__ID__']) }}'.replace('__ID__', this.removeTarget.id), {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        });
                        if (res.ok) {
                            this.orgteams = this.orgteams.filter(v => v.id !== this.removeTarget.id);
                            this.showRemoveModal = false;
                            this.removeTarget = null;
                        } else {
                            const data = await res.json();
                            alert(data.message || 'Failed to remove team member.');
                        }
                    } catch {
                        alert('Network error. Please try again.');
                    } finally {
                        this.removeSubmitting = false;
                        this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                    }
                },
            }));
        });
    </script>
</x-layouts.admin>
