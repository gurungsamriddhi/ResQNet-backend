<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="userManager()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Top Header section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Users & Roles</h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Manage your organization's team members and system permissions</p>
            </div>
        </div>

        <!-- Search & Actions Container -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Search bar & Filter -->
            <div class="flex items-center gap-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-slate-400 dark:text-slate-300"></i>
                    </span>
                    <input type="text" x-model="search"
                           placeholder="Search by name or email..."
                           class="w-full pl-9 pr-4 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                </div>
                <!-- Filter Button -->
                <button class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-[#111c2a] border border-slate-200 dark:border-slate-750 text-slate-600 dark:text-slate-300 font-semibold text-xs transition-all flex items-center gap-1.5 hover:bg-slate-200 dark:hover:bg-slate-700">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
            </div>

            <!-- Add User button -->
            <button @click="openAddModal()"
                    class="bg-[#5BA44C] text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition-all duration-200 hover:bg-[#5BA44C]/95 active:scale-95 shadow-md shadow-[#5BA44C]/10 self-start md:self-auto">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>+ Add User</span>
            </button>
        </div>

        <!-- Users Table -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-[#111c2a] border-b border-admin-border text-slate-800 dark:text-slate-200 font-semibold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border/60">
                        <template x-for="user in filteredUsers()" :key="user.email">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <!-- User details with avatar letter -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm"
                                             :class="userAvatarClass(user.role)"
                                             x-text="user.name.charAt(0)">
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 dark:text-slate-100 block" x-text="user.name"></span>
                                        </div>
                                    </div>
                                </td>
                                <!-- Email -->
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-300" x-text="user.email"></td>
                                <!-- Role -->
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase border shadow-sm"
                                          :class="userRoleClass(user.role)">
                                        <template x-if="user.role === 'super_admin'">
                                            <i data-lucide="shield" class="w-3 h-3 stroke-[2.5]"></i>
                                        </template>
                                        <span x-text="user.role"></span>
                                    </span>
                                </td>
                                <!-- Status -->
                                <td class="px-6 py-4">
                                    <template x-if="user.is_active">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Active</span>
                                        </span>
                                    </template>
                                    <template x-if="!user.is_active">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200/50 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Suspended</span>
                                        </span>
                                    </template>
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <template x-if="user.is_active">
                                            <button @click="openSuspendModal(user)"
                                                    class="text-xs text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/20 px-2.5 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1">
                                                <i data-lucide="pause-circle" class="w-3.5 h-3.5"></i>
                                                <span>Suspend</span>
                                            </button>
                                        </template>
                                        <template x-if="!user.is_active">
                                            <form :action="`/admin/users/${user.id}/reactivate`" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 px-2.5 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1">
                                                    <i data-lucide="play-circle" class="w-3.5 h-3.5"></i>
                                                    <span>Reactivate</span>
                                                </button>
                                            </form>
                                        </template>
                                        <button @click="openDeleteModal(user)"
                                                class="text-xs text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 px-2.5 py-1.5 rounded-md font-semibold transition-colors flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Expandable Details Shelf for Team Members -->
                            <tr x-show="expandedRow === user.email"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                style="display: none;">
                                <td colspan="5" class="px-6 py-4 bg-slate-50/50 dark:bg-[#111c2a]/20 border-b border-admin-border/60">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Left Panel: Volunteer Profile -->
                                        <div>
                                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3">Volunteer Profile</span>
                                            <div class="space-y-2.5 text-xs">
                                                <div class="flex justify-between">
                                                    <span class="text-slate-500 dark:text-slate-300">Contact</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300">9851234567</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-slate-500 dark:text-slate-300">Availability Sector</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Pokhara Sector-9</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-slate-500 dark:text-slate-300">Emergency Contact</span>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Sita Thapa (9847654321)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Right Panel: Field Mission Log -->
                                        <div>
                                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-300 block mb-3">Field Mission Log</span>
                                            <div class="space-y-3">
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></div>
                                                    <div>
                                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Currently responding to case RQ-2417</span>
                                                        <span class="block text-[10px] text-slate-400 dark:text-slate-300">Active mission • Pokhara Zone</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></div>
                                                    <div>
                                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Resolved case RQ-2398</span>
                                                        <span class="block text-[10px] text-slate-400 dark:text-slate-300">Completed • 3 days ago</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-start gap-2.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-1.5 flex-shrink-0"></div>
                                                    <div>
                                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Resolved case RQ-2371</span>
                                                        <span class="block text-[10px] text-slate-400 dark:text-slate-300">Completed • 1 week ago</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= ADD USER MODAL ================= -->
        <div x-show="isAddUserModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isAddUserModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm"
                 @click="isAddUserModalOpen = false"></div>

            <div x-show="isAddUserModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-md bg-admin-surface border border-admin-border rounded-2xl shadow-2xl overflow-hidden"
                 @click.away="isAddUserModalOpen = false">

                <!-- Header -->
                <div class="px-6 py-5 border-b border-admin-border bg-admin-bg/25 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Add New User</h2>
                        <p class="text-[10px] text-slate-400 dark:text-slate-300">Invite a new member to your organization team</p>
                    </div>
                    <button @click="isAddUserModalOpen = false" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-400 dark:text-slate-300 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Form Content -->
                <form id="addUserForm" @submit.prevent="saveNewUser()" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" x-model="addUserForm.name" placeholder="E.g. Sarah Johnson"
                               class="w-full px-3 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                               :class="{'border-red-400': errors.name}">
                        <template x-if="errors.name">
                            <p class="mt-1 text-xs text-red-500" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" x-model="addUserForm.email" placeholder="sarah@shelter.org"
                               class="w-full px-3 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                               :class="{'border-red-400': errors.email}">
                        <template x-if="errors.email">
                            <p class="mt-1 text-xs text-red-500" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" x-model="addUserForm.password" placeholder="Min 8 characters"
                                   class="w-full px-3 py-2 pr-10 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                   :class="{'border-red-400': errors.password}">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition" aria-label="Show password">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <template x-if="errors.password">
                            <p class="mt-1 text-xs text-red-500" x-text="errors.password[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Confirm Password</label>
                        <div class="relative">
                            <input :type="showPasswordConfirmation ? 'text' : 'password'" x-model="addUserForm.password_confirmation" placeholder="Re-enter password"
                                   class="w-full px-3 py-2 pr-10 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                   :class="{'border-red-400': errors.password}">
                            <button type="button" @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition" aria-label="Show password">
                                <svg x-show="!showPasswordConfirmation" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPasswordConfirmation" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Assign Role</label>
                        <select x-model="addUserForm.role"
                                class="w-full px-3 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                :class="{'border-red-400': errors.role}">
                            <option value="super_admin">Super Admin</option>
                            <option value="organization">Organization</option>
                            <option value="public_user">Public User</option>
                        </select>
                        <template x-if="errors.role">
                            <p class="mt-1 text-xs text-red-500" x-text="errors.role[0]"></p>
                        </template>
                    </div>
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button @click="isAddUserModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-xs transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" form="addUserForm"
                            class="px-4 py-2 rounded-xl bg-[#5BA44C] hover:bg-[#5BA44C]/90 text-white font-semibold text-xs transition-all duration-200 shadow-md">
                        Save User
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= SUSPEND USER MODAL ================= -->
        <div x-show="isSuspendModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isSuspendModalOpen = false">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="isSuspendModalOpen = false"></div>
            <div x-show="isSuspendModalOpen"
                 class="relative w-full max-w-md bg-admin-surface border border-admin-border rounded-2xl shadow-2xl overflow-hidden"
                 @click.away="isSuspendModalOpen = false">
                <form :action="`/admin/users/${selectedUser?.id}/deactivate`" method="POST">
                    @csrf
                    <div class="px-6 py-5 border-b border-admin-border bg-admin-bg/25 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Suspend User</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Provide a reason for suspending this user</p>
                        </div>
                        <button type="button" @click="isSuspendModalOpen = false" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-400 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Reason for Suspension</label>
                        <textarea name="reason" rows="3" required
                                  class="w-full px-3 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                  placeholder="e.g., Violation of terms of service..."></textarea>
                    </div>
                    <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isSuspendModalOpen = false"
                                class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-slate-600 hover:bg-slate-100 font-semibold text-xs transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs transition-all duration-200 shadow-md">
                            Suspend User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= DELETE USER MODAL ================= -->
        <div x-show="isDeleteModalOpen"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;"
             @keydown.escape.window="isDeleteModalOpen = false">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="isDeleteModalOpen = false"></div>
            <div x-show="isDeleteModalOpen"
                 class="relative w-full max-w-md bg-admin-surface border border-admin-border rounded-2xl shadow-2xl overflow-hidden"
                 @click.away="isDeleteModalOpen = false">
                <form :action="`/admin/users/${selectedUser?.id}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="px-6 py-5 border-b border-admin-border bg-admin-bg/25 flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-rose-600 dark:text-rose-400">Permanently Remove User</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Provide a reason for deleting this user</p>
                        </div>
                        <button type="button" @click="isDeleteModalOpen = false" class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-400 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-300 uppercase tracking-wider mb-2">Reason for Deletion</label>
                        <textarea name="reason" rows="3" required
                                  class="w-full px-3 py-2 bg-admin-bg border border-admin-border rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                  placeholder="e.g., Requested by user..."></textarea>
                    </div>
                    <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="isDeleteModalOpen = false"
                                class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-slate-600 hover:bg-slate-100 font-semibold text-xs transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition-all duration-200 shadow-md">
                            Delete User
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Block for State Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userManager', () => ({
                search: '',
                isAddUserModalOpen: false,
                isSuspendModalOpen: false,
                isDeleteModalOpen: false,
                selectedUser: null,
                expandedRow: null,
                showPassword: false,
                showPasswordConfirmation: false,
                errors: {},
                addUserForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    role: 'super_admin'
                },
                users: @json($usersJson ?? []),

                filteredUsers() {
                    const q = this.search.toLowerCase().trim();
                    if (!q) return this.users;
                    return this.users.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q));
                },

                toggleDetails(email) {
                    this.expandedRow = this.expandedRow === email ? null : email;
                },

                openAddModal() {
                    this.addUserForm = { name: '', email: '', password: '', password_confirmation: '', role: 'super_admin' };
                    this.errors = {};
                    this.showPassword = false;
                    this.showPasswordConfirmation = false;
                    this.isAddUserModalOpen = true;
                },

                saveNewUser() {
                    this.errors = {};
                    fetch('/admin/users', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            _token: '{{ csrf_token() }}',
                            name: this.addUserForm.name,
                            email: this.addUserForm.email,
                            password: this.addUserForm.password,
                            password_confirmation: this.addUserForm.password_confirmation,
                            role: this.addUserForm.role,
                        })
                    })
                    .then(r => r.json().then(data => ({ ok: r.ok, status: r.status, data })))
                    .then(({ ok, status, data }) => {
                        if (!ok) {
                            if (status === 422 && data.errors) {
                                this.errors = data.errors;
                            } else {
                                alert(data.message || 'An error occurred while creating the user.');
                            }
                            return;
                        }
                        this.users.push(data.user);
                        this.isAddUserModalOpen = false;
                        this.addUserForm = { name: '', email: '', password: '', password_confirmation: '', role: 'super_admin' };
                    })
                    .catch(() => alert('Network error. Please try again.'));
                },

                openSuspendModal(user) {
                    this.selectedUser = user;
                    this.isSuspendModalOpen = true;
                },

                openDeleteModal(user) {
                    this.selectedUser = user;
                    this.isDeleteModalOpen = true;
                },

                userAvatarClass(role) {
                    if (role === 'super_admin') return 'bg-rose-500';
                    if (role === 'organization') return 'bg-emerald-500';
                    if (role === 'public_user') return 'bg-blue-500';
                    return 'bg-slate-500';
                },

                userRoleClass(role) {
                    if (role === 'super_admin') {
                        return 'bg-rose-50 text-rose-700 border-rose-200/50 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30';
                    }
                    if (role === 'organization') {
                        return 'bg-emerald-50 text-emerald-700 border-emerald-200/50 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30';
                    }
                    return 'bg-blue-50 text-blue-700 border-blue-200/50 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/30';
                }
            }));
        });
    </script>
</x-layouts.admin>
