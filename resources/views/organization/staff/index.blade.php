<x-layouts.admin>

    <div class="bg-[#f8fafc] dark:bg-slate-900 min-h-screen px-4 sm:px-6 md:px-8 py-8"
         x-data="staffManager()"
         x-init="init(); $nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                {{ __('Staff Members') }}
            </h2>
            <button type="button" @click="openInviteModal()" class="inline-flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Invite Staff
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400 p-4 rounded-xl flex items-center justify-between">
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 p-4 rounded-xl flex items-center justify-between">
                <span class="font-semibold text-sm">{{ $errors->first('error') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 shadow-sm sm:rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($staffMembers as $staff)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-bold text-sm shrink-0">
                                            {{ substr($staff->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $staff->name }}</div>
                                            @if($staff->phone)
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $staff->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $staff->email }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleColors = [
                                            'organization' => 'bg-purple-100 dark:bg-purple-500/20 text-purple-700 dark:text-purple-400',
                                            'staff' => 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400',
                                            'vet' => 'bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400',
                                        ];
                                        $role = $staff->roles->first()?->name ?? 'staff';
                                        $colorClass = $roleColors[$role] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize {{ $colorClass }}">
                                        {{ $role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($staff->is_active)
                                        <span class="px-2.5 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-full">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($staff->id !== auth()->id())
                                            <button type="button"
                                                    @click="confirmRemove({{ $staff->id }}, '{{ addslashes($staff->name) }}')"
                                                     class="p-2 text-slate-500 dark:text-slate-400 hover:text-red-500 bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors"
                                                    title="Remove">
                                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <span class="p-2 text-slate-300" title="You cannot remove yourself">
                                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                                        <div class="w-16 h-16 bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="users" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">No Staff Members</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">Invite team members to help manage your organization.</p>
                                        <button type="button" @click="openInviteModal()" class="mt-4 text-brand-orange hover:text-brand-orange/80 font-bold text-sm">Invite your first staff member &rarr;</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($staffMembers->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    {{ $staffMembers->links() }}
                </div>
            @endif
        </div>

        <!-- Invite Modal -->
        <template x-teleport="body">
            <div x-show="inviteModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="inviteModalOpen = false">
                <div @click.away="inviteModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="px-6 pt-6 pb-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-orange to-orange-600 flex items-center justify-center text-white shadow-md">
                                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100">Invite Staff Member</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">They'll receive an email to set up their account.</p>
                                </div>
                            </div>
                            <button @click="inviteModalOpen = false" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                            <div class="h-px bg-slate-200 dark:bg-slate-700 mt-6"></div>
                    </div>

                    <div class="px-6 py-6">
                        <form @submit.prevent="submitInvite" class="space-y-4">
                            <div x-show="formErrors.general" class="p-3 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 rounded-lg text-sm border border-red-200 dark:border-red-500/30 flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
                                <span x-text="formErrors.general"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" x-model="form.name" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200 focus:border-brand-orange focus:ring-brand-orange/20 focus:ring-2 sm:text-sm px-3.5 py-2.5 transition-all" required placeholder="e.g. Jane Smith">
                                <p x-show="formErrors.name" x-text="formErrors.name?.[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </div>
                                    <input type="email" x-model="form.email" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200 focus:border-brand-orange focus:ring-brand-orange/20 focus:ring-2 sm:text-sm pl-10 pr-3.5 py-2.5 transition-all" required placeholder="jane@example.com">
                                </div>
                                <p x-show="formErrors.email" x-text="formErrors.email?.[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" x-model="form.phone" class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200 focus:border-brand-orange focus:ring-brand-orange/20 focus:ring-2 sm:text-sm pl-10 pr-3.5 py-2.5 transition-all" placeholder="+1 234 567 8900">
                                </div>
                                <p x-show="formErrors.phone" x-text="formErrors.phone?.[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Role <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                            :class="form.role === 'staff' ? 'border-brand-orange bg-brand-orange/5 dark:bg-brand-orange/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                                        <input type="radio" x-model="form.role" value="staff" class="sr-only">
                                        <div class="text-center">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center mx-auto mb-1">
                                                <i data-lucide="user" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Staff</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                            :class="form.role === 'vet' ? 'border-brand-orange bg-brand-orange/5 dark:bg-brand-orange/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                                        <input type="radio" x-model="form.role" value="vet" class="sr-only">
                                        <div class="text-center">
                                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center mx-auto mb-1">
                                                <i data-lucide="stethoscope" class="w-4 h-4 text-amber-600 dark:text-amber-400"></i>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Vet</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                            :class="form.role === 'organization' ? 'border-brand-orange bg-brand-orange/5 dark:bg-brand-orange/10' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                                        <input type="radio" x-model="form.role" value="organization" class="sr-only">
                                        <div class="text-center">
                                            <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center mx-auto mb-1">
                                                <i data-lucide="shield" class="w-4 h-4 text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Admin</span>
                                        </div>
                                    </label>
                                </div>
                                <p x-show="formErrors.role" x-text="formErrors.role?.[0]" class="mt-1 text-xs text-red-600"></p>
                            </div>
                        </form>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3">
                        <button type="button" @click="inviteModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800/70 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-lg transition-colors">Cancel</button>
                        <button type="button" @click="submitInvite" :disabled="isSaving" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-orange hover:bg-brand-orange/90 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSaving" style="display: none;"></i>
                            <span x-text="isSaving ? 'Sending...' : 'Send Invitation'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Remove Confirmation Modal -->
        <template x-teleport="body">
            <div x-show="removeModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="removeModalOpen = false">
                <div @click.away="removeModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center flex-shrink-0 text-red-600 dark:text-red-400">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Remove Staff Member</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">This action cannot be undone.</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Are you sure you want to remove <strong class="text-slate-900 dark:text-slate-100" x-text="removeName"></strong> from your organization?
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="removeModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                        <button type="button" @click="submitRemove" :disabled="isRemoving" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isRemoving" style="display: none;"></i>
                            <span x-text="isRemoving ? 'Removing...' : 'Remove'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-cloak
             @show-toast.window="showToast($event.detail.message, $event.detail.type)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="fixed top-6 right-6 z-[70] max-w-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl p-4 flex items-start gap-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center border mt-0.5"
                 :class="toastType === 'success' ? 'bg-emerald-100 dark:bg-emerald-500/20 border-emerald-200 dark:border-emerald-500/30' : 'bg-red-100 dark:bg-red-500/20 border-red-200 dark:border-red-500/30'">
                <i data-lucide="check" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" x-show="toastType === 'success'"></i>
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 dark:text-red-400" x-show="toastType === 'error'"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="toastType === 'success' ? 'Success' : 'Error'"></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400"></i>
            </button>
        </div>

        <script>
            function staffManager() {
                return {
                    inviteModalOpen: false,
                    removeModalOpen: false,
                    isSaving: false,
                    isRemoving: false,
                    toastVisible: false,
                    toastMessage: '',
                    toastType: 'success',
                    toastTimeout: null,
                    form: { name: '', email: '', phone: '', role: 'staff' },
                    formErrors: {},
                    removeId: null,
                    removeName: '',

                    init() {},

                    showToast(message, type = 'success') {
                        this.toastMessage = message;
                        this.toastType = type;
                        this.toastVisible = true;
                        if (this.toastTimeout) clearTimeout(this.toastTimeout);
                        this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 4000);
                        if (typeof lucide !== 'undefined') setTimeout(() => lucide.createIcons(), 50);
                    },

                    openInviteModal() {
                        this.form = { name: '', email: '', phone: '', role: 'staff' };
                        this.formErrors = {};
                        this.inviteModalOpen = true;
                    },

                    async submitInvite() {
                        this.isSaving = true;
                        this.formErrors = {};
                        try {
                            const response = await fetch('{{ route("organization.staff.invite") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(this.form)
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                if (response.status === 422) {
                                    this.formErrors = data.errors || { general: data.message || 'Validation failed.' };
                                    this.showToast('Please check the form for errors.', 'error');
                                } else {
                                    this.formErrors = { general: data.message || 'An error occurred.' };
                                    this.showToast(data.message || 'Request failed.', 'error');
                                }
                                return;
                            }
                            this.inviteModalOpen = false;
                            this.showToast(data.message || 'Invitation sent successfully.', 'success');
                            setTimeout(() => { window.location.reload(); }, 1000);
                        } catch (error) {
                            this.showToast('A network error occurred.', 'error');
                        } finally {
                            this.isSaving = false;
                        }
                    },

                    confirmRemove(id, name) {
                        this.removeId = id;
                        this.removeName = name;
                        this.removeModalOpen = true;
                    },

                    async submitRemove() {
                        this.isRemoving = true;
                        try {
                            const response = await fetch('{{ url("organization/staff") }}/' + this.removeId, {
                                method: 'DELETE',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                this.showToast(data.message || 'Failed to remove staff member.', 'error');
                                this.removeModalOpen = false;
                                return;
                            }
                            this.removeModalOpen = false;
                            this.showToast(data.message || 'Staff member removed.', 'success');
                            setTimeout(() => { window.location.reload(); }, 1000);
                        } catch (error) {
                            this.showToast('A network error occurred.', 'error');
                            this.removeModalOpen = false;
                        } finally {
                            this.isRemoving = false;
                        }
                    }
                }
            }
        </script>
    </div>
</x-layouts.admin>