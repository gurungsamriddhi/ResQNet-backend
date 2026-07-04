<x-layouts.admin>
    <div class="bg-slate-50 dark:bg-slate-900 px-4 sm:px-6 md:px-8 py-8 space-y-6 max-w-4xl mx-auto min-h-screen"
         x-data="settingsManager()"
         x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })">

        <!-- Top Header Banner Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 dark:border-slate-700 pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                    Settings
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Manage your admin panel preferences and security
                </p>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-400">
                    <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                    Settings Panel
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-400 text-xs font-semibold px-4 py-3.5 rounded-xl flex items-center gap-2.5 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" class="ml-auto text-emerald-400 dark:text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('organization.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Organization Profile Card -->
            <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                        <i data-lucide="building" class="w-4.5 h-4.5"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Organization Profile</h2>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Update your organization name, logo, and contact details</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="flex flex-col items-center text-center lg:w-48 shrink-0">
                            <div class="relative w-24 h-24 mb-3">
                                <div class="w-full h-full rounded-xl bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                                    @if ($organization?->logo)
                                        <img src="{{ Storage::url($organization->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="building" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                                    @endif
                                </div>
                                <label class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-brand-orange text-white flex items-center justify-center cursor-pointer hover:bg-brand-orange/90 shadow-md transition-colors">
                                    <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                                </label>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">JPEG, PNG, WebP. Max 2MB.</p>
                        </div>
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Organization Name</label>
                                <input type="text" name="name" value="{{ old('name', $organization?->name) }}"
                                       class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('name') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                @error('name')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Description</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('description') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">{{ old('description', $organization?->description) }}</textarea>
                                @error('description')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email', $organization?->email) }}"
                                           class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('email') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                </div>
                                @error('email')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Phone</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone', $organization?->phone) }}"
                                           class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('phone') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                </div>
                                @error('phone')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" name="address" value="{{ old('address', $organization?->address) }}"
                                           class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('address') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                </div>
                                @error('address')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-end">
                        <button type="submit"
                                class="bg-brand-orange text-white hover:bg-brand-orange/90 text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-[0.98]">
                            <i data-lucide="save" class="w-4 h-4 inline-block mr-1.5"></i>
                            Save Organization
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="pt-4">
            <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Other Settings</h3>
        </div>

        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card Section 1: Security -->
            <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/20 text-rose-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Security</h2>
                            <p class="text-[10px] text-slate-400">Manage account access and verification settings</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Two-Factor Authentication -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Two-Factor Authentication</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Require a verification code in addition to your password</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="security.twoFactor" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-rose-500"></div>
                            </label>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Password -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Password</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Change password periodically for better protection</p>
                            </div>
                            <button @click="changePassword()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                                Change Password
                            </button>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Login Activity -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Login Activity</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Monitor active sessions and device logs</p>
                            </div>
                            <button @click="viewLoginHistory()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                                View Login History
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Section 2: Notifications -->
            <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/20 text-brand-orange flex items-center justify-center shadow-inner">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Notifications</h2>
                            <p class="text-[10px] text-slate-400">Configure alert channels and notification preferences</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Email Notifications</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Receive daily summary and alerts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="notifications.email" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-orange"></div>
                            </label>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- SMS Notifications -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">SMS Notifications</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Get critical alerts via text message</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="notifications.sms" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-orange"></div>
                            </label>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Emergency Alerts -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Emergency Alerts</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Instant notifications for critical rescue cases</p>
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
            <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Data & Privacy</h2>
                            <p class="text-[10px] text-slate-400">Configure data retention, logging, and backups</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Automatic Backups -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Automatic Backups</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Daily system backups enabled</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="dataPrivacy.backups" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Activity Logging -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Activity Logging</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Track all admin actions and changes</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer mt-0.5">
                                <input type="checkbox" x-model="dataPrivacy.logging" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Export Data -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Export Data</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Download your organization's entire profile data</p>
                            </div>
                            <button @click="downloadData()"
                                    class="text-[11px] font-bold border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                <span>Download Your Data</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Section 4: Rescue Configuration Options -->
            <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden p-5 flex flex-col justify-between">
                <div>
                    <!-- Header -->
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-200 dark:border-slate-700/60 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/20 text-blue-500 flex items-center justify-center shadow-inner">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Rescue & Shelter Operations</h2>
                            <p class="text-[10px] text-slate-400">Configure parameters for local rescue dispatches</p>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="space-y-4">
                        <!-- Maximum Shelter Capacity -->
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Maximum Shelter Capacity</h3>
                                <p class="text-[10px] text-slate-400 mt-0.5">Manage and track active occupied slots</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" x-model="rescueOps.occupiedSlots"
                                       class="w-16 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-center font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                                <span class="text-xs font-bold text-slate-400">/</span>
                                <input type="number" x-model="rescueOps.maxCapacity"
                                       class="w-16 px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-center font-bold text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                            </div>
                        </div>

                        <hr class="border-slate-200 dark:border-slate-700/40">

                        <!-- Mobile Alert Radius Range -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300">Mobile Alert Radius Range</h3>
                                <span class="text-[11px] font-extrabold text-blue-500 dark:text-blue-450 bg-blue-50 dark:bg-blue-950/30 px-2.5 py-0.5 rounded-full" x-text="`${rescueOps.alertRadius}km`"></span>
                            </div>
                            <p class="text-[10px] text-slate-400">Notify field teams within <span x-text="rescueOps.alertRadius"></span>km of reported incident location</p>
                            <input type="range" min="1" max="50" x-model="rescueOps.alertRadius"
                                   class="w-full accent-blue-500 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-lg cursor-pointer transition-all">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Page Control Bar Footer -->
        <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200 dark:border-slate-700 mt-8">
            <button @click="cancelChanges()"
                    class="bg-white border border-slate-200 dark:bg-slate-800 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-750 text-xs px-4 py-2 rounded-lg font-semibold transition-colors">
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
