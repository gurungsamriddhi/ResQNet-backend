<x-layouts.admin>
    <div class="bg-slate-50 dark:bg-[#111c2a] px-4 sm:px-6 md:px-8 py-8 space-y-8 max-w-5xl mx-auto min-h-screen"
         x-data="profileManager()">

        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-600/5 to-brand-orange/5 rounded-2xl p-6 border border-slate-200 dark:border-slate-700/40">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shadow-sm">
                        <i data-lucide="building-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">Organization Profile</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-300 mt-0.5">Manage your organization's public details and security</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 text-xs font-bold text-slate-600 dark:text-slate-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $organization->name ?? 'Organization' }}
                    </span>
                </div>
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

        @if (session('error'))
            <div class="bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 text-rose-800 dark:text-rose-400 text-xs font-semibold px-4 py-3.5 rounded-xl flex items-center gap-2.5 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0"></i>
                <span>{{ session('error') }}</span>
                <button type="button" @click="show = false" class="ml-auto text-rose-400 dark:text-rose-500 hover:text-rose-600 dark:hover:text-rose-400">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('organization.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Left Column: Logo -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-sm p-6 sticky top-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="relative w-28 h-28 mb-4">
                                <div class="w-full h-full rounded-full bg-slate-100 dark:bg-[#111c2a] border-4 border-slate-200 dark:border-slate-700/40 flex items-center justify-center overflow-hidden">
                                    <template x-if="logoPreview">
                                        <img :src="logoPreview" alt="Logo Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!logoPreview">
                                        @if ($organization->getFirstMediaUrl('logo'))
                                            <img src="{{ $organization->getFirstMediaUrl('logo') }}" alt="Logo" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-3xl font-bold text-slate-400 dark:text-slate-300">{{ strtoupper(substr($organization->name ?? 'O', 0, 1)) }}</span>
                                        @endif
                                    </template>
                                </div>
                                <label class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-brand-orange text-white flex items-center justify-center cursor-pointer hover:bg-brand-orange/90 shadow-md transition-colors">
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                    <input type="file" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" @change="previewLogo($event)">
                                </label>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $organization->name }}</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-300 mt-0.5">Verified Organization</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Profile Form -->
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/60 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-500/20 text-blue-500 dark:text-blue-400 flex items-center justify-center">
                                <i data-lucide="building" class="w-4.5 h-4.5"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Organization Details</h2>
                                <p class="text-[10px] text-slate-400 dark:text-slate-300">Update your public details</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Organization Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                            <i data-lucide="building-2" class="w-4 h-4"></i>
                                        </div>
                                        <input type="text" name="name" value="{{ old('name', $organization->name) }}"
                                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('name') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                    </div>
                                    @error('name')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Contact Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                            <i data-lucide="mail" class="w-4 h-4"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ old('email', $organization->email) }}"
                                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('email') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                    </div>
                                    @error('email')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Phone Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                            <i data-lucide="phone" class="w-4 h-4"></i>
                                        </div>
                                        <input type="text" name="phone" value="{{ old('phone', $organization->phone) }}"
                                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('phone') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                    </div>
                                    @error('phone')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Registration Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                        </div>
                                        <input type="text" name="registration_number" value="{{ old('registration_number', $organization->registration_number) }}"
                                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('registration_number') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                    </div>
                                    @error('registration_number')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none text-slate-400 dark:text-slate-300">
                                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                                        </div>
                                        <input type="text" name="address" value="{{ old('address', $organization->address) }}"
                                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('address') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                    </div>
                                    @error('address')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Description</label>
                                    <textarea name="description" rows="3"
                                              class="w-full p-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('description') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">{{ old('description', $organization->description) }}</textarea>
                                    @error('description')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Footer -->
                    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-sm p-4 flex items-center justify-end">
                        <button type="submit"
                                class="bg-brand-orange text-white hover:bg-brand-orange/90 text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-[0.98]">
                            <i data-lucide="save" class="w-4 h-4 inline-block mr-1.5"></i>
                            Save Profile
                        </button>
                    </div>

                </div>
            </div>
        </form>

        <!-- Password Change Form -->
        <form method="POST" action="{{ route('organization.password.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-1"></div>
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-rose-50 dark:bg-rose-500/20 text-rose-500 dark:text-rose-400 flex items-center justify-center">
                                    <i data-lucide="lock" class="w-4.5 h-4.5"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Security</h2>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-300">Change your account password</p>
                                </div>
                            </div>
                            <button type="button" @click="showPasswordFields = !showPasswordFields"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700/40 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
                                    x-text="showPasswordFields ? 'Cancel' : 'Change Password'">
                            </button>
                        </div>
                        <div x-show="showPasswordFields" x-collapse.duration.300ms>
                            <div class="p-6 border-t border-slate-100 dark:border-slate-800/60">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Current Password</label>
                                        <div class="relative" x-data="{ show: false }">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                                <i data-lucide="key" class="w-4 h-4"></i>
                                            </div>
                                            <input :type="show ? 'text' : 'password'" name="current_password"
                                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('current_password') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                                <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                                                <i data-lucide="eye-off" class="w-4 h-4" x-show="show"></i>
                                            </button>
                                        </div>
                                        @error('current_password')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">New Password</label>
                                        <div class="relative" x-data="{ show: false }">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                                <i data-lucide="key" class="w-4 h-4"></i>
                                            </div>
                                            <input :type="show ? 'text' : 'password'" name="new_password"
                                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all @error('new_password') border-red-300 bg-red-50 dark:bg-red-500/10 @enderror">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                                <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                                                <i data-lucide="eye-off" class="w-4 h-4" x-show="show"></i>
                                            </button>
                                        </div>
                                        @error('new_password')<p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                                        <div class="relative" x-data="{ show: false }">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-300">
                                                <i data-lucide="key" class="w-4 h-4"></i>
                                            </div>
                                            <input :type="show ? 'text' : 'password'" name="new_password_confirmation"
                                                   class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-700/40 dark:text-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                                <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                                                <i data-lucide="eye-off" class="w-4 h-4" x-show="show"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 pb-6 pt-2 border-t border-slate-100 dark:border-slate-800/60 flex justify-end">
                                <button type="submit"
                                        class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-6 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-[0.98]">
                                    <i data-lucide="lock" class="w-4 h-4 inline-block mr-1.5"></i>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileManager', () => ({
                showPasswordFields: false,
                logoPreview: '',

                previewLogo(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.logoPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                init() {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                }
            }));
        });
    </script>
</x-layouts.admin>
