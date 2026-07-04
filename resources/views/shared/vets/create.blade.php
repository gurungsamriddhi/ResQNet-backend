<x-layouts.admin>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route($routePrefix . 'index') }}" class="p-2 bg-admin-surface hover:bg-admin-bg text-admin-textSecondary hover:text-brand-orange border border-admin-border rounded-xl transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-bold text-2xl text-admin-textPrimary tracking-tight">
                {{ __('Add New Veterinarian') }}
            </h2>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 md:px-8 py-8" x-data="{ isSubmitting: false }" x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">
        
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-8 border-b border-admin-border bg-admin-bg/30">
                    <h3 class="text-lg font-bold text-admin-textPrimary">Veterinarian Details</h3>
                    <p class="text-sm text-admin-textSecondary mt-1">Enter the required information to register a new global veterinarian.</p>
                </div>

                <form method="POST" action="{{ route($routePrefix . 'store') }}" @submit="isSubmitting = true" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-admin-textPrimary mb-2">Full Name *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="e.g. Dr. Sarah Jenkins">
                            </div>
                            @error('name') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="specialization" class="block text-sm font-semibold text-admin-textPrimary mb-2">Specialization</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="stethoscope" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization') }}"
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="e.g. Feline Specialist, General Surgery">
                            </div>
                            @error('specialization') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-admin-textPrimary mb-2">Phone Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="+1 (555) 123-4567">
                            </div>
                            @error('phone') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-admin-textPrimary mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="sarah@example.com">
                            </div>
                            @error('email') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-admin-textPrimary mb-2">Clinic Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="map-pin" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="text" name="address" id="address" value="{{ old('address') }}"
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="123 Vet Street, City">
                            </div>
                            @error('address') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- License Number -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="license_number" class="block text-sm font-semibold text-admin-textPrimary mb-2">License Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="file-badge-2" class="h-5 w-5 text-admin-textSecondary/70"></i>
                                </div>
                                <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}"
                                    class="pl-10 block w-full rounded-xl border-admin-border bg-admin-surface text-admin-textPrimary focus:border-brand-orange focus:ring-brand-orange shadow-sm sm:text-sm"
                                    placeholder="e.g. VET-987654321">
                            </div>
                            @error('license_number') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="col-span-1 md:col-span-2 bg-admin-bg/50 p-4 rounded-xl border border-admin-border flex items-center justify-between">
                            <div>
                                <label for="status" class="block text-sm font-bold text-admin-textPrimary">Active Status</label>
                                <p class="text-xs text-admin-textSecondary mt-1">If active, this vet can be selected for health records.</p>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="hidden" name="status" value="inactive">
                                <input type="checkbox" name="status" id="status" value="active" class="peer sr-only" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                                <label for="status" class="block overflow-hidden h-6 rounded-full bg-slate-300 dark:bg-slate-600 cursor-pointer peer-checked:bg-green-500 transition-colors">
                                    <span class="block w-6 h-6 rounded-full bg-white dark:bg-slate-200 border border-slate-300 dark:border-slate-500 shadow transform transition-transform peer-checked:translate-x-6 peer-checked:border-green-500"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-admin-border flex justify-end gap-3">
                        <a href="{{ route($routePrefix . 'index') }}" class="px-5 py-2.5 text-sm font-semibold text-admin-textSecondary hover:bg-admin-bg border border-admin-border rounded-xl transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm"
                                :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }"
                                :disabled="isSubmitting">
                            <i data-lucide="save" class="w-4 h-4" x-show="!isSubmitting"></i>
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSubmitting" style="display: none;"></i>
                            <span x-text="isSubmitting ? 'Saving...' : 'Save Veterinarian'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
