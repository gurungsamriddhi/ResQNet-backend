<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6 max-w-4xl mx-auto"
         x-data="settingsManager()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Top Header Banner Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    Settings
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                    Manage your admin panel preferences and security
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                    Settings Panel
                </span>
            </div>
        </div>

        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card Section 1: Security -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-admin-border/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Security</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Manage account access and verification settings</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Two-Factor Authentication -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Two-Factor Authentication</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Require a verification code in addition to your password</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="security.twoFactor" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-500"></div>
                            </label>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Password -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Password</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Change password periodically for better protection</p>
                            </div>
                            <button @click="changePassword()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700/40 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                                Change Password
                            </button>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Login Activity -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Login Activity</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Monitor active sessions and device logs</p>
                            </div>
                            <button @click="viewLoginHistory()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700/40 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                                View Login History
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Section 2: Notifications -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-admin-border/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/20 text-brand-orange flex items-center justify-center shadow-inner">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Notifications</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Configure alert channels and notification preferences</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Email Notifications</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Receive daily summary and alerts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="notifications.email" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-orange"></div>
                            </label>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- SMS Notifications -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">SMS Notifications</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Get critical alerts via text message</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="notifications.sms" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-orange"></div>
                            </label>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Emergency Alerts -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Emergency Alerts</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Instant notifications for critical rescue cases</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="notifications.emergency" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-orange"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Section 3: Data & Privacy -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-admin-border/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data & Privacy</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Configure data retention, logging, and backups</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Automatic Backups -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Automatic Backups</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Daily system backups enabled</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="dataPrivacy.backups" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Activity Logging -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Activity Logging</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Track all admin actions and changes</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="dataPrivacy.logging" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Export Data -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Export Data</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Download your organization's entire profile data</p>
                            </div>
                            <button @click="downloadData()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700/40 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                <span>Download Your Data</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Section 4: Rescue Configuration Options -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-admin-border/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/20 text-blue-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Rescue & Shelter Operations</h2>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Configure parameters for local rescue dispatches</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Maximum Shelter Capacity -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Maximum Shelter Capacity</h3>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Manage and track active occupied slots</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" x-model="rescueOps.occupiedSlots"
                                       class="w-16 px-2.5 py-1.5 bg-admin-bg border border-admin-border rounded-xl text-xs text-center font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-300">/</span>
                                <input type="number" x-model="rescueOps.maxCapacity"
                                       class="w-16 px-2.5 py-1.5 bg-admin-bg border border-admin-border rounded-xl text-xs text-center font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                            </div>
                        </div>

                        <hr class="border-admin-border/40">

                        <!-- Mobile Alert Radius Range -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Mobile Alert Radius Range</h3>
                                <span class="text-[11px] font-extrabold text-blue-500 dark:text-blue-450 bg-blue-50 dark:bg-blue-950/30 px-2.5 py-0.5 rounded-full" x-text="`${rescueOps.alertRadius}km`"></span>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300">Notify field teams within <span x-text="rescueOps.alertRadius"></span>km of reported incident location</p>
                            <input type="range" min="1" max="50" x-model="rescueOps.alertRadius"
                                   class="w-full accent-blue-500 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-lg cursor-pointer transition-all">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Page Control Bar Footer -->
        <div class="flex items-center justify-end gap-3 pt-5 border-t border-admin-border mt-8">
            <button @click="cancelChanges()"
                    class="bg-white border border-slate-200 dark:bg-[#111c2a] dark:border-slate-700/40 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs px-4 py-2 rounded-lg font-semibold transition-colors">
                Cancel
            </button>
            <button @click="saveChanges()"
                    class="bg-[#5BA44C] hover:bg-[#5BA44C]/90 text-white text-xs font-semibold px-4 py-2 rounded-lg flex items-center gap-1.5 transition-all duration-200 active:scale-95 shadow-md shadow-[#5BA44C]/10">
                <i data-lucide="check" class="w-4 h-4 stroke-[2.5]"></i>
                <span>Save Changes</span>
            </button>
        </div>

    </div>

    <!-- Script Block for State Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('settingsManager', () => ({
                security: {
                    twoFactor: false
                },
                notifications: {
                    email: true,
                    sms: false,
                    emergency: true
                },
                dataPrivacy: {
                    backups: true,
                    logging: true
                },
                rescueOps: {
                    maxCapacity: 50,
                    occupiedSlots: 28,
                    alertRadius: 10
                },

                changePassword() {
                    alert('Change password trigger. This will guide you to update security credentials.');
                },

                viewLoginHistory() {
                    alert('Displaying active administration panel session details...');
                },

                downloadData() {
                    alert('Exporting system metadata database files...');
                },

                cancelChanges() {
                    if (confirm('Are you sure you want to discard unsaved updates?')) {
                        window.location.reload();
                    }
                },

                saveChanges() {
                    alert('System configuration parameters saved successfully!');
                }
            }));
        });
    </script>
</x-layouts.admin>
