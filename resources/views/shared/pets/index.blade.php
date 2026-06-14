<x-layouts.admin>
    @php
        $petsJson = isset($pets) ? $pets->toJson() : '[]';
        $petsBaseUrl = request()->is('admin/*') ? '/admin/pets' : '/organization/pets';
        $canEdit = $canEdit ?? false;
    @endphp

    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6" x-data="petManager({{ $petsJson }}, '{{ $petsBaseUrl }}', {{ $canEdit ? 'true' : 'false' }})"
        x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-admin-textPrimary font-extrabold text-3xl tracking-tight">
                    Pets Management
                </h1>
                <p class="text-admin-textSecondary text-sm mt-1">
                    Manage and monitor shelter animals, breed classifications, and adoption statuses.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-admin-border bg-admin-surface text-admin-textPrimary hover:bg-admin-bg hover:text-admin-primary transition-all duration-300 font-semibold text-sm shadow-sm hover:shadow active:scale-95 group">
                    <i data-lucide="filter"
                        class="w-4 h-4 text-admin-textSecondary group-hover:text-admin-primary transition-colors"></i>
                    <span>Advanced Filter</span>
                </button>
                <button x-show="canEdit" @click="openAddModal()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-orange text-white hover:bg-brand-orange/90 transition-all duration-300 font-bold text-sm shadow-md hover:shadow-lg active:scale-95 shadow-brand-orange/10">
                    <i data-lucide="plus" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span>Add Pet</span>
                </button>
            </div>
        </div>

        <!-- Search and Filters Control Row -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="relative md:col-span-2">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="search" class="w-4.5 h-4.5"></i>
                </div>
                <input type="text" x-model="search" placeholder="Search pets by name, breed, or organization..."
                    class="w-full pl-10 pr-4 py-3 bg-admin-surface border border-admin-border rounded-xl text-sm text-admin-textPrimary placeholder-admin-textSecondary/50 focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all duration-200 shadow-sm">
                <button x-show="search.length" @click="search = ''"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-admin-textSecondary/60 hover:text-admin-textPrimary transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select x-model="speciesFilter"
                    class="w-full pl-10 pr-10 py-3 bg-admin-surface border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary appearance-none transition-all duration-200 shadow-sm font-medium">
                    <option value="">All Animals</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="bird">Bird</option>
                    <option value="rabbit">Rabbit</option>
                    <option value="other">Other</option>
                </select>
                <div
                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                </div>
                <select x-model="statusFilter"
                    class="w-full pl-10 pr-10 py-3 bg-admin-surface border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary appearance-none transition-all duration-200 shadow-sm font-medium">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="adopted">Adopted</option>
                    <option value="unavailable">In Care</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div
                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="resetFilters()" x-show="search || speciesFilter || statusFilter"
                    class="px-4 py-3 rounded-xl border border-admin-border bg-admin-surface text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-bg transition-all text-sm font-semibold">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-1"></i> Reset
                </button>
            </div>
        </div>

        <!-- Active Filter Tags -->
        <div x-show="search || speciesFilter || statusFilter" class="flex flex-wrap items-center gap-2 -mt-2">
            <span class="text-[11px] font-semibold text-admin-textSecondary/60 uppercase tracking-wider">Active
                filters:</span>
            <template x-if="search">
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-brand-orange/10 text-brand-orange text-xs font-semibold border border-brand-orange/20">
                    <i data-lucide="search" class="w-3 h-3"></i>
                    "<span x-text="search"></span>"
                    <button @click="search = ''" class="hover:text-brand-orange/80 ml-0.5"><i data-lucide="x"
                            class="w-3 h-3"></i></button>
                </span>
            </template>
            <template x-if="speciesFilter">
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-brand-orange/10 text-brand-orange text-xs font-semibold border border-brand-orange/20 capitalize">
                    <i data-lucide="tag" class="w-3 h-3"></i> <span x-text="speciesFilter"></span>
                    <button @click="speciesFilter = ''" class="hover:text-brand-orange/80 ml-0.5"><i data-lucide="x"
                            class="w-3 h-3"></i></button>
                </span>
            </template>
            <template x-if="statusFilter">
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-brand-orange/10 text-brand-orange text-xs font-semibold border border-brand-orange/20">
                    <i data-lucide="filter" class="w-3 h-3"></i> <span x-text="statusLabel(statusFilter)"></span>
                    <button @click="statusFilter = ''" class="hover:text-brand-orange/80 ml-0.5"><i data-lucide="x"
                            class="w-3 h-3"></i></button>
                </span>
            </template>
        </div>

        <!-- Results count bar -->
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold text-admin-textSecondary/70">
                <span x-text="filteredPets.length"></span> pet<span x-show="filteredPets.length !== 1">s</span> found
            </p>
        </div>

        <!-- Modern Pet Directory Data Table Card -->
        <div
            class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-admin-bg/50 border-b border-admin-border text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/80">
                            <th class="py-4 px-6 select-none">Pet Name</th>
                            <th class="py-4 px-6 select-none">Breed</th>
                            <th class="py-4 px-6 select-none">Gender</th>
                            <th class="py-4 px-6 select-none">Age</th>
                            <th class="py-4 px-6 select-none">Status</th>
                            <th class="py-4 px-6 select-none text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border/60">
                        <template x-for="pet in filteredPets" :key="pet.qr_code_id">
                            <tr class="hover:bg-admin-bg/30 transition-colors duration-200 group">
                                <!-- Name + Avatar -->
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <template x-if="pet.primary_image">
                                            <img :src="pet.primary_image" alt=""
                                                class="w-10 h-10 rounded-xl object-cover shadow-sm transition-transform duration-300 group-hover:scale-105 border border-admin-border/40">
                                        </template>
                                        <template x-if="!pet.primary_image">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-extrabold text-sm shadow-sm transition-transform duration-300 group-hover:scale-105"
                                                :class="speciesAvatarClass(pet.species)">
                                                <span x-text="pet.name.charAt(0).toUpperCase()"></span>
                                            </div>
                                        </template>
                                        <span class="font-bold text-admin-textPrimary text-sm"
                                            x-text="pet.name"></span>
                                    </div>
                                </td>
                                <!-- Breed -->
                                <td class="py-6 px-6">
                                    <span class="text-sm font-semibold text-admin-textSecondary"
                                        x-text="pet.breed"></span>
                                </td>
                                <!-- Gender -->
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-2">
                                        <span x-show="pet.gender === 'male'"><i data-lucide="mars"
                                                class="w-4 h-4 text-blue-400"></i></span>
                                        <span x-show="pet.gender === 'female'"><i data-lucide="venus"
                                                class="w-4 h-4 text-pink-400"></i></span>
                                        <span x-show="pet.gender === 'unknown'"><i data-lucide="help-circle"
                                                class="w-4 h-4 text-admin-textSecondary/50"></i></span>
                                        <span class="text-sm font-semibold text-admin-textSecondary capitalize"
                                            x-text="pet.gender"></span>
                                    </div>
                                </td>
                                <!-- Age -->
                                <td class="py-6 px-6">
                                    <span class="text-sm font-semibold text-admin-textSecondary"
                                        x-text="formatAge(pet.age_months)"></span>
                                </td>
                                <!-- Status -->
                                <td class="py-6 px-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-sm border"
                                        :class="statusClass(pet.status)">
                                        <template x-if="pet.status === 'available'"><span
                                                class="w-1.5 h-1.5 rounded-full bg-admin-btnGreen animate-pulse"></span></template>
                                        <template x-if="pet.status !== 'available'"><i
                                                :data-lucide="statusIcon(pet.status)"
                                                class="w-3.5 h-3.5"></i></template>
                                        <span x-text="statusLabel(pet.status)"></span>
                                    </span>
                                </td>
                                <!-- Actions -->
                                <td class="py-6 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <a :href="petsBase + '/' + pet.id"
                                            class="inline-block p-2 text-admin-textSecondary hover:text-admin-primary hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105"
                                            title="View details">
                                            <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                                        </a>
                                        <button x-show="canEdit" @click="openEditModal(pet)"
                                            class="p-2 text-admin-textSecondary hover:text-admin-btnGreen hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105"
                                            title="Edit pet">
                                            <i data-lucide="pencil" class="w-4.5 h-4.5"></i>
                                        </button>
                                        <button x-show="canEdit" @click="confirmDelete(pet)"
                                            class="p-2 text-admin-textSecondary hover:text-admin-btnRed hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105"
                                            title="Delete pet">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5 text-admin-btnRed"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div x-show="filteredPets.length === 0" class="flex flex-col items-center justify-center py-16 px-6">
                <div
                    class="w-20 h-20 rounded-2xl bg-admin-bg/50 flex items-center justify-center mb-5 border border-admin-border/60">
                    <i data-lucide="search-x" class="w-10 h-10 text-admin-textSecondary/30"
                        style="stroke-width: 1.5;"></i>
                </div>
                <h3 class="text-lg font-extrabold text-admin-textPrimary mb-1">No pets match your filters</h3>
                <p class="text-sm text-admin-textSecondary/70 mb-5">Try adjusting your search or filter criteria.</p>
                <button @click="resetFilters()"
                    class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all shadow-sm">
                    <i data-lucide="rotate-ccw" class="w-4 h-4 inline mr-1.5"></i> Clear All Filters
                </button>
            </div>

            <!-- Pagination Footer -->
            <div x-show="filteredPets.length > 0"
                class="px-6 py-4 bg-admin-bg/20 border-t border-admin-border flex items-center justify-between flex-wrap gap-4 text-xs font-semibold text-admin-textSecondary">
                <span
                    x-text="'Showing all ' + filteredPets.length + ' pet' + (filteredPets.length !== 1 ? 's' : '')"></span>
                <div class="flex items-center gap-2">
                    <button
                        class="px-3.5 py-1.5 rounded-xl border border-admin-border bg-admin-surface text-admin-textSecondary transition-all duration-200 opacity-50"
                        disabled>Previous</button>
                    <button
                        class="w-8 h-8 rounded-xl bg-admin-primary text-white flex items-center justify-center font-bold shadow-sm shadow-admin-primary/10">1</button>
                    <button
                        class="px-3.5 py-1.5 rounded-xl border border-admin-border bg-admin-surface text-admin-textSecondary transition-all duration-200 opacity-50"
                        disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- ===== DETAILS MODAL ===== -->
        <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="display: none;" @keydown.escape.window="isModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="isModalOpen = false"></div>

            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                @click.away="isModalOpen = false">

                <!-- Modal Header -->
                <div
                    class="relative bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-10 pb-8 px-6">
                    <div class="absolute inset-0 opacity-10 rounded-t-2xl pointer-events-none">
                        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-admin-primary blur-3xl"></div>
                        <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full bg-admin-navActive blur-3xl">
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <button @click="isModalOpen = false"
                            class="p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200 shadow-sm">
                            <i data-lucide="x" class="w-4.5 h-4.5"></i>
                        </button>
                    </div>

                    <!-- Avatar -->
                    <div class="flex flex-col items-center text-center relative z-[5]">
                        <template x-if="selectedPet.primary_image">
                            <img :src="selectedPet.primary_image" alt=""
                                class="w-20 h-20 rounded-2xl object-cover shadow-xl border-4 border-admin-surface ring-2 ring-admin-primary/20">
                        </template>
                        <template x-if="!selectedPet.primary_image">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-xl border-4 border-admin-surface ring-2 ring-admin-primary/20"
                                :class="speciesAvatarClass(selectedPet.species)">
                                <span
                                    x-text="selectedPet.name ? selectedPet.name.charAt(0).toUpperCase() : '?'"></span>
                            </div>
                        </template>

                        <!-- Badges -->
                        <div class="flex items-center gap-2.5 mt-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-sm border"
                                :class="statusClass(selectedPet.status)">
                                <template x-if="selectedPet.status === 'available'">
                                    <span class="w-1.5 h-1.5 rounded-full bg-admin-btnGreen animate-pulse"></span>
                                </template>
                                <template x-if="selectedPet.status !== 'available'">
                                    <i :data-lucide="statusIcon(selectedPet.status)" class="w-3.5 h-3.5"></i>
                                </template>
                                <span x-text="statusLabel(selectedPet.status)"></span>
                            </span>
                            <span
                                class="text-[11px] font-mono font-semibold text-admin-textSecondary/60 bg-admin-surface/60 backdrop-blur-sm px-2.5 py-1 rounded-lg border border-admin-border/50">
                                <i data-lucide="qr-code" class="w-3 h-3 inline mr-1"></i>
                                <span x-text="selectedPet.qr_code_id"></span>
                            </span>
                        </div>

                        <!-- Name + Org -->
                        <h2 class="text-xl font-bold text-admin-textPrimary mt-4" x-text="selectedPet.name"></h2>
                        <p class="text-sm text-admin-textSecondary/70 mt-0.5 flex items-center gap-1.5">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 flex-shrink-0"></i>
                            <span x-text="selectedPet.organization"></span>
                        </p>
                    </div>
                </div>

                <!-- Modal Body: Detail View -->
                <div x-show="!isEditing" class="p-8 space-y-7">

                    <!-- Classification Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1.5">
                                <i data-lucide="paw-print" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Species</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary capitalize"
                                x-text="selectedPet.species"></p>
                        </div>
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <i data-lucide="scan" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Breed</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary" x-text="selectedPet.breed ?? '-'"></p>
                        </div>
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <span x-show="selectedPet.gender === 'male'" class="text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="7" />
                                        <path d="M12 5V2" />
                                        <path d="M10 2h4" />
                                        <path d="M15 9l4-4" />
                                    </svg>
                                </span>
                                <span x-show="selectedPet.gender === 'female'" class="text-pink-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="7" />
                                        <path d="M12 19v6" />
                                        <path d="M9 22h6" />
                                    </svg>
                                </span>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Gender</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary capitalize"
                                x-text="selectedPet.gender"></p>
                        </div>
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <i data-lucide="maximize" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Size</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary capitalize" x-text="selectedPet.size">
                            </p>
                        </div>
                    </div>

                    <!-- Age, Color, Added -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Age</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary">
                                <span x-show="selectedPet.age_months"
                                    x-text="formatAge(selectedPet.age_months)"></span>
                                <span x-show="!selectedPet.age_months">-</span>
                            </p>
                        </div>
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <i data-lucide="palette" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Color</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary" x-text="selectedPet.color ?? '-'"></p>
                        </div>
                        <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                            <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Date Added</h3>
                            </div>
                            <p class="text-sm font-bold text-admin-textPrimary"
                                x-text="selectedPet.created_at ? selectedPet.created_at.split(' ')[0] : '-'"></p>
                        </div>
                    </div>

                    <!-- Health & Medical -->
                    <div>
                        <div class="flex items-center gap-2.5 mb-4">
                            <div class="h-px flex-1 bg-admin-border/40"></div>
                            <span
                                class="text-[10px] font-extrabold uppercase tracking-widest text-admin-textSecondary/50 px-2">Health
                                & Medical</span>
                            <div class="h-px flex-1 bg-admin-border/40"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                                <h3
                                    class="text-[10px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">
                                    Vaccinated</h3>
                                <span
                                    :class="selectedPet.is_vaccinated ? 'bg-admin-successBg text-admin-successText' :
                                        'bg-admin-btnRed/10 text-admin-btnRed'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold">
                                    <i :data-lucide="selectedPet.is_vaccinated ? 'check-circle' : 'x-circle'"
                                        class="w-3.5 h-3.5"></i>
                                    <span x-text="selectedPet.is_vaccinated ? 'Yes' : 'No'"></span>
                                </span>
                            </div>
                            <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                                <h3
                                    class="text-[10px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">
                                    Neutered / Spayed</h3>
                                <span
                                    :class="selectedPet.is_neutered ? 'bg-admin-successBg text-admin-successText' :
                                        'bg-admin-btnRed/10 text-admin-btnRed'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold">
                                    <i :data-lucide="selectedPet.is_neutered ? 'check-circle' : 'x-circle'"
                                        class="w-3.5 h-3.5"></i>
                                    <span x-text="selectedPet.is_neutered ? 'Yes' : 'No'"></span>
                                </span>
                            </div>
                            <div class="bg-admin-bg/30 rounded-xl px-4 py-3.5 border border-admin-border/40">
                                <h3
                                    class="text-[10px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">
                                    Last Updated</h3>
                                <p class="text-sm font-bold text-admin-textPrimary"
                                    x-text="selectedPet.updated_at ? selectedPet.updated_at.split(' ')[0] : '-'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div x-show="selectedPet.description"
                        class="bg-admin-bg/30 rounded-xl px-5 py-4 border border-admin-border/40">
                        <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-2.5">
                            <i data-lucide="align-left" class="w-4 h-4"></i>
                            <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Description</h3>
                        </div>
                        <p class="text-sm text-admin-textPrimary leading-relaxed" x-text="selectedPet.description">
                        </p>
                    </div>

                    <!-- Health Status Details -->
                    <div x-show="selectedPet.health_status"
                        class="bg-admin-bg/30 rounded-xl px-5 py-4 border border-admin-border/40">
                        <div class="flex items-center gap-2 text-admin-textSecondary/60 mb-2.5">
                            <i data-lucide="heart-pulse" class="w-4 h-4"></i>
                            <h3 class="text-[10px] font-extrabold uppercase tracking-widest">Health Status Notes</h3>
                        </div>
                        <p class="text-sm text-admin-textPrimary leading-relaxed" x-text="selectedPet.health_status">
                        </p>
                    </div>

                    <!-- Image Gallery -->
                    <div x-show="selectedPet.images && selectedPet.images.length > 0">
                        <div class="flex items-center gap-2.5 mb-4">
                            <div class="h-px flex-1 bg-admin-border/40"></div>
                            <span
                                class="text-[10px] font-extrabold uppercase tracking-widest text-admin-textSecondary/50 px-2">Photos</span>
                            <div class="h-px flex-1 bg-admin-border/40"></div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <template x-for="(img, idx) in selectedPet.images" :key="idx">
                                <div
                                    class="relative group aspect-square rounded-xl overflow-hidden border border-admin-border/40 bg-admin-bg/30">
                                    <img :src="img.url || img" :alt="'Photo ' + (idx + 1)"
                                        class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="grid grid-cols-2 gap-5 pt-2">
                        <div class="flex items-center gap-3 text-admin-textSecondary/50">
                            <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                            <div>
                                <span class="text-[10px] font-semibold">Created</span>
                                <p class="text-xs font-bold text-admin-textSecondary/80"
                                    x-text="selectedPet.created_at"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-admin-textSecondary/50">
                            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
                            <div>
                                <span class="text-[10px] font-semibold">Updated</span>
                                <p class="text-xs font-bold text-admin-textSecondary/80"
                                    x-text="selectedPet.updated_at"></p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Body: Edit Form -->
                <div x-show="isEditing" class="p-8 space-y-5">
                    <div class="flex items-center gap-2.5 mb-2">
                        <i data-lucide="pencil" class="w-4 h-4 text-admin-primary"></i>
                        <h3 class="text-sm font-extrabold text-admin-textPrimary">Editing <span
                                x-text="selectedPet.name"></span></h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Name</label>
                            <input type="text" x-model="editForm.name"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Breed</label>
                            <input type="text" x-model="editForm.breed"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Species</label>
                            <select x-model="editForm.species"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="bird">Bird</option>
                                <option value="rabbit">Rabbit</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Gender</label>
                            <select x-model="editForm.gender"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unknown">Unknown</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Age
                                (months)</label>
                            <input type="number" x-model="editForm.age_months" min="0"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Size</label>
                            <select x-model="editForm.size"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                                <option value="xl">Extra Large</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Color</label>
                            <input type="text" x-model="editForm.color"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Status</label>
                            <select x-model="editForm.status"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="available">Available</option>
                                <option value="adopted">Adopted</option>
                                <option value="unavailable">In Care</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Description</label>
                        <textarea x-model="editForm.description" rows="3"
                            class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Health
                            Status</label>
                        <textarea x-model="editForm.health_status" rows="2"
                            class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" x-model="editForm.is_vaccinated"
                                class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                            <span class="text-sm font-semibold text-admin-textPrimary">Vaccinated</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" x-model="editForm.is_neutered"
                                class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                            <span class="text-sm font-semibold text-admin-textPrimary">Neutered / Spayed</span>
                        </label>
                    </div>

                    <!-- Profile Image Upload -->
                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">Profile
                            Photo</label>
                        <div class="flex items-center gap-4">
                            <template x-if="editForm.primary_image">
                                <div
                                    class="relative w-20 h-20 rounded-xl overflow-hidden border border-admin-border/40 flex-shrink-0">
                                    <img :src="editForm.primary_image" alt=""
                                        class="w-full h-full object-cover">
                                    <button @click="editForm.primary_image = null"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-admin-btnRed/80 text-white flex items-center justify-center hover:bg-admin-btnRed transition-colors">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </template>
                            <template x-if="!editForm.primary_image">
                                <div
                                    class="w-20 h-20 rounded-xl bg-admin-bg border-2 border-dashed border-admin-border/50 flex items-center justify-center text-admin-textSecondary/40 flex-shrink-0">
                                    <i data-lucide="camera" class="w-6 h-6"></i>
                                </div>
                            </template>
                            <div>
                                <label
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                    <span>Change Image</span>
                                    <input type="file" accept="image/*" @change="handleEditPrimaryImage"
                                        class="hidden">
                                </label>
                                <p class="text-[11px] text-admin-textSecondary/50 mt-1.5">JPG, PNG or GIF. 1:1 ratio
                                    recommended.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Images Upload -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Additional
                                Photos</label>
                            <label
                                class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-admin-surface border border-admin-border text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-bg font-semibold text-xs transition-all duration-200">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Add Photos</span>
                                <input type="file" accept="image/*" multiple @change="handleEditImages"
                                    class="hidden">
                            </label>
                        </div>
                        <div x-show="editForm.images.length === 0"
                            class="bg-admin-bg/30 border-2 border-dashed border-admin-border/30 rounded-xl px-5 py-6 text-center">
                            <i data-lucide="image" class="w-8 h-8 text-admin-textSecondary/20 mx-auto mb-2"></i>
                            <p class="text-xs text-admin-textSecondary/40 font-semibold">No additional photos added
                                yet.</p>
                        </div>
                        <div x-show="editForm.images.length > 0"
                            class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            <template x-for="(img, idx) in editForm.images" :key="idx">
                                <div
                                    class="relative group aspect-square rounded-xl overflow-hidden border border-admin-border/40 bg-admin-bg/30">
                                    <img :src="img.url || img" alt="" class="w-full h-full object-cover">
                                    <button @click="removeEditImage(idx)"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-admin-btnRed/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-admin-btnRed transition-all">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="px-8 py-5 border-t border-admin-border bg-admin-bg/20 flex items-center justify-between rounded-b-2xl">
                    <template x-if="!isEditing">
                        <button @click="isModalOpen = false"
                            class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                            <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1.5"></i> Back
                        </button>
                    </template>
                    <template x-if="isEditing">
                        <button @click="cancelEdit()"
                            class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1.5"></i> Cancel
                        </button>
                    </template>
                    <div class="flex items-center gap-3">
                        <template x-if="!isEditing && canEdit">
                            <button @click="enableEdit()"
                                class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all duration-200 shadow-sm">
                                <i data-lucide="pencil" class="w-4 h-4 inline mr-1.5"></i> Edit Pet
                            </button>
                        </template>
                        <template x-if="isEditing">
                            <button @click="saveEdit()"
                                class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all duration-200 shadow-sm">
                                <i data-lucide="check" class="w-4 h-4 inline mr-1.5"></i> Save Changes
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== ADD PET MODAL ===== -->
        <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
            style="display: none;" @keydown.escape.window="closeAddModal()">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="closeAddModal()"></div>

            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                @click.away="closeAddModal()">

                <!-- Header -->
                <div
                    class="relative bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-10 pb-8 px-6">
                    <div class="absolute inset-0 opacity-10 rounded-t-2xl pointer-events-none">
                        <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-admin-primary blur-3xl"></div>
                        <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full bg-admin-navActive blur-3xl">
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <button @click="closeAddModal()"
                            class="p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200 shadow-sm">
                            <i data-lucide="x" class="w-4.5 h-4.5"></i>
                        </button>
                    </div>
                    <div class="flex flex-col items-center text-center relative z-[5]">
                        <div
                            class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-xl border-4 border-admin-surface ring-2 ring-admin-primary/20 bg-admin-primary/20 text-admin-primary">
                            <i data-lucide="plus" class="w-8 h-8 stroke-[2.5]"></i>
                        </div>
                        <h2 class="text-xl font-bold text-admin-textPrimary mt-4">Add New Pet</h2>
                        <p class="text-sm text-admin-textSecondary/70 mt-0.5">Fill in the details to register a new
                            animal.</p>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-8 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Name
                                <span class="text-admin-btnRed">*</span></label>
                            <input type="text" x-model="addForm.name"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Breed</label>
                            <input type="text" x-model="addForm.breed"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Species</label>
                            <select x-model="addForm.species"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="bird">Bird</option>
                                <option value="rabbit">Rabbit</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Gender</label>
                            <select x-model="addForm.gender"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="unknown">Unknown</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Age
                                (months)</label>
                            <input type="number" x-model="addForm.age_months" min="0"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Size</label>
                            <select x-model="addForm.size"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                                <option value="xl">Extra Large</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Color</label>
                            <input type="text" x-model="addForm.color"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Status</label>
                            <select x-model="addForm.status"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                <option value="available">Available</option>
                                <option value="adopted">Adopted</option>
                                <option value="unavailable">In Care</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Description</label>
                        <textarea x-model="addForm.description" rows="3"
                            class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Health
                            Status</label>
                        <textarea x-model="addForm.health_status" rows="2"
                            class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>

                    <!-- Profile Image Upload -->
                    <div>
                        <label
                            class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">Profile
                            Photo</label>
                        <div class="flex items-center gap-4">
                            <template x-if="addForm.primary_image">
                                <div
                                    class="relative w-20 h-20 rounded-xl overflow-hidden border border-admin-border/40 flex-shrink-0">
                                    <img :src="addForm.primary_image" alt=""
                                        class="w-full h-full object-cover">
                                    <button @click="addForm.primary_image = null"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-admin-btnRed/80 text-white flex items-center justify-center hover:bg-admin-btnRed transition-colors">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </template>
                            <template x-if="!addForm.primary_image">
                                <div
                                    class="w-20 h-20 rounded-xl bg-admin-bg border-2 border-dashed border-admin-border/50 flex items-center justify-center text-admin-textSecondary/40 flex-shrink-0">
                                    <i data-lucide="camera" class="w-6 h-6"></i>
                                </div>
                            </template>
                            <div>
                                <label
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                    <span>Choose Image</span>
                                    <input type="file" accept="image/*" @change="handleAddPrimaryImage"
                                        class="hidden">
                                </label>
                                <p class="text-[11px] text-admin-textSecondary/50 mt-1.5">JPG, PNG or GIF. 1:1 ratio
                                    recommended.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Images Upload -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label
                                class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Additional
                                Photos</label>
                            <label
                                class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-admin-surface border border-admin-border text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-bg font-semibold text-xs transition-all duration-200">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Add Photos</span>
                                <input type="file" accept="image/*" multiple @change="handleAddImages"
                                    class="hidden">
                            </label>
                        </div>
                        <div x-show="addForm.images.length === 0"
                            class="bg-admin-bg/30 border-2 border-dashed border-admin-border/30 rounded-xl px-5 py-6 text-center">
                            <i data-lucide="image" class="w-8 h-8 text-admin-textSecondary/20 mx-auto mb-2"></i>
                            <p class="text-xs text-admin-textSecondary/40 font-semibold">No additional photos added
                                yet.</p>
                        </div>
                        <div x-show="addForm.images.length > 0"
                            class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            <template x-for="(img, idx) in addForm.images" :key="idx">
                                <div
                                    class="relative group aspect-square rounded-xl overflow-hidden border border-admin-border/40 bg-admin-bg/30">
                                    <img :src="img.url || img" alt="" class="w-full h-full object-cover">
                                    <button @click="removeAddImage(idx)"
                                        class="absolute top-1 right-1 w-5 h-5 rounded-full bg-admin-btnRed/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-admin-btnRed transition-all">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" x-model="addForm.is_vaccinated"
                                class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                            <span class="text-sm font-semibold text-admin-textPrimary">Vaccinated</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" x-model="addForm.is_neutered"
                                class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                            <span class="text-sm font-semibold text-admin-textPrimary">Neutered / Spayed</span>
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="px-8 py-5 border-t border-admin-border bg-admin-bg/20 flex items-center justify-between rounded-b-2xl">
                    <button @click="closeAddModal()"
                        class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                        Cancel
                    </button>
                    <button @click="saveNewPet()"
                        class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all duration-200 shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-1.5"></i> Add Pet
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== DELETE CONFIRMATION POPUP ===== -->
        <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[60] flex items-center justify-center p-4"
            style="display: none;" @keydown.escape.window="cancelDelete()">

            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="cancelDelete()"></div>

            <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm bg-admin-surface border border-admin-border rounded-2xl shadow-2xl p-6 text-center">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-admin-btnRed/10 flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-admin-btnRed"></i>
                </div>

                <h3 class="text-lg font-extrabold text-admin-textPrimary mb-1">Delete Pet</h3>
                <p class="text-sm text-admin-textSecondary/70 mb-6">
                    Are you sure you want to delete <span class="font-bold text-admin-textPrimary"
                        x-text="petToDelete?.name"></span>? This action cannot be undone.
                </p>

                <div class="flex items-center gap-3 justify-center">
                    <button @click="cancelDelete()"
                        class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                        Cancel
                    </button>
                    <button @click="executeDelete()"
                        class="px-5 py-2.5 rounded-xl bg-admin-btnRed text-white hover:bg-red-600 font-bold text-sm transition-all duration-200 shadow-sm">
                        <i data-lucide="trash-2" class="w-4 h-4 inline mr-1.5"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('petManager', (pets, petsBase, canEdit = false) => ({
                pets: pets,
                canEdit: canEdit,
                search: '',
                speciesFilter: '',
                statusFilter: '',
                isModalOpen: false,
                isEditing: false,
                selectedPet: {},
                editForm: { images: [] },
                showDeleteConfirm: false,
                petToDelete: null,
                showAddModal: false,
                addForm: {
                    name: '',
                    species: 'dog',
                    breed: '',
                    gender: 'male',
                    age_months: '',
                    size: 'medium',
                    color: '',
                    status: 'available',
                    description: '',
                    health_status: '',
                    is_vaccinated: false,
                    is_neutered: false,
                    primary_image: null,
                    images: [],
                    primary_image_file: null,
                    image_files: [],
                },
                csrfToken: document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute(
                    'content') ?? '',
                petsBase: petsBase,

                get filteredPets() {
                    return this.pets.filter(pet => {
                        const q = this.search.toLowerCase();
                        const matchesSearch = !q ||
                            pet.name.toLowerCase().includes(q) ||
                            pet.breed.toLowerCase().includes(q) ||
                            (pet.organization && pet.organization.toLowerCase().includes(
                            q));
                        const matchesSpecies = !this.speciesFilter || pet.species === this
                            .speciesFilter;
                        const matchesStatus = !this.statusFilter || pet.status === this
                            .statusFilter;
                        return matchesSearch && matchesSpecies && matchesStatus;
                    });
                },

                openModal(pet) {
                    this.selectedPet = pet;
                    this.isEditing = false;
                    this.isModalOpen = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                openEditModal(pet) {
                    this.selectedPet = pet;
                    const copy = JSON.parse(JSON.stringify(pet));
                    copy.primary_image_file = null;
                    copy.image_files = [];
                    copy.remove_image_ids = [];
                    this.editForm = copy;
                    this.isEditing = true;
                    this.isModalOpen = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                enableEdit() {
                    const copy = JSON.parse(JSON.stringify(this.selectedPet));
                    copy.primary_image_file = null;
                    copy.image_files = [];
                    copy.remove_image_ids = [];
                    this.editForm = copy;
                    this.isEditing = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                async saveEdit() {
                    if (!this.editForm.name?.trim()) return;
                    const fd = new FormData();
                    const fields = ['name', 'species', 'breed', 'gender', 'size', 'color',
                        'description', 'health_status', 'status', 'age_months'
                    ];
                    fields.forEach(f => {
                        if (this.editForm[f] !== undefined && this.editForm[f] !== '') fd
                            .append(f, this.editForm[f]);
                    });
                    fd.append('is_vaccinated', this.editForm.is_vaccinated ? '1' : '0');
                    fd.append('is_neutered', this.editForm.is_neutered ? '1' : '0');
                    fd.append('_method', 'PUT');
                    if (this.editForm.primary_image_file) fd.append('primary_image', this.editForm
                        .primary_image_file);
                    this.editForm.image_files.forEach(f => fd.append('images[]', f));
                    if (this.editForm.remove_image_ids?.length) {
                        this.editForm.remove_image_ids.forEach(id => fd.append('remove_image_ids[]',
                            id));
                    }
                    try {
                        const res = await fetch(this.petsBase + '/' + this.editForm.id, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: fd,
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            alert(err.message || 'Failed to update pet.');
                            return;
                        }
                        const json = await res.json();
                        if (json.pet) {
                            const idx = this.pets.findIndex(p => p.id === json.pet.id);
                            if (idx !== -1) this.pets[idx] = json.pet;
                        }
                        this.isEditing = false;
                        this.isModalOpen = false;
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    } catch (e) {
                        console.error('Save failed', e);
                        alert('An error occurred while updating the pet.');
                    }
                },

                cancelEdit() {
                    this.isEditing = false;
                    this.editForm = {};
                },

                confirmDelete(pet) {
                    this.petToDelete = pet;
                    this.showDeleteConfirm = true;
                },

                cancelDelete() {
                    this.showDeleteConfirm = false;
                    this.petToDelete = null;
                },

                async executeDelete() {
                    if (!this.petToDelete) return;
                    try {
                        const res = await fetch(this.petsBase + '/' + this.petToDelete.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            alert(err.message || 'Failed to delete pet.');
                            return;
                        }
                        this.pets = this.pets.filter(p => p.id !== this.petToDelete.id);
                        if (this.selectedPet?.id === this.petToDelete.id) {
                            this.isModalOpen = false;
                            this.selectedPet = {};
                        }
                    } catch (e) {
                        console.error('Delete failed', e);
                        alert('An error occurred while deleting the pet.');
                    }
                    this.cancelDelete();
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                openAddModal() {
                    this.addForm = {
                        name: '',
                        species: 'dog',
                        breed: '',
                        gender: 'male',
                        age_months: '',
                        size: 'medium',
                        color: '',
                        status: 'available',
                        description: '',
                        health_status: '',
                        is_vaccinated: false,
                        is_neutered: false,
                        primary_image: null,
                        images: [],
                        primary_image_file: null,
                        image_files: [],
                    };
                    this.showAddModal = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                closeAddModal() {
                    this.showAddModal = false;
                },

                async saveNewPet() {
                    if (!this.addForm.name.trim()) return;
                    const fd = new FormData();
                    const fields = ['name', 'species', 'breed', 'gender', 'size', 'color',
                        'description', 'health_status', 'status', 'age_months'
                    ];
                    fields.forEach(f => {
                        if (this.addForm[f] !== undefined && this.addForm[f] !== '') fd
                            .append(f, this.addForm[f]);
                    });
                    fd.append('is_vaccinated', this.addForm.is_vaccinated ? '1' : '0');
                    fd.append('is_neutered', this.addForm.is_neutered ? '1' : '0');
                    if (this.addForm.primary_image_file) fd.append('primary_image', this.addForm
                        .primary_image_file);
                    this.addForm.image_files.forEach(f => fd.append('images[]', f));
                    try {
                        const res = await fetch(this.petsBase, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: fd,
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            alert(err.message || 'Failed to save pet. Check all required fields.');
                            return;
                        }
                        const json = await res.json();
                        if (json.pet) this.pets.unshift(json.pet);
                        this.closeAddModal();
                    } catch (e) {
                        console.error('Save failed', e);
                        alert('An error occurred while saving the pet.');
                    }
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                resetFilters() {
                    this.search = '';
                    this.speciesFilter = '';
                    this.statusFilter = '';
                },

                readFileAsDataURL(file) {
                    return new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = (e) => resolve(e.target.result);
                        reader.readAsDataURL(file);
                    });
                },

                async handleAddPrimaryImage(e) {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    this.addForm.primary_image_file = file;
                    this.addForm.primary_image = await this.readFileAsDataURL(file);
                    e.target.value = '';
                },

                async handleAddImages(e) {
                    const files = Array.from(e.target.files || []);
                    for (const file of files) {
                        this.addForm.image_files.push(file);
                        const url = await this.readFileAsDataURL(file);
                        this.addForm.images.push({
                            id: null,
                            url
                        });
                    }
                    e.target.value = '';
                },

                removeAddImage(idx) {
                    this.addForm.images.splice(idx, 1);
                    this.addForm.image_files.splice(idx, 1);
                },

                async handleEditPrimaryImage(e) {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    this.editForm.primary_image_file = file;
                    this.editForm.primary_image = await this.readFileAsDataURL(file);
                    e.target.value = '';
                },

                async handleEditImages(e) {
                    const files = Array.from(e.target.files || []);
                    for (const file of files) {
                        this.editForm.image_files.push(file);
                        const url = await this.readFileAsDataURL(file);
                        this.editForm.images.push({
                            id: null,
                            url
                        });
                    }
                    e.target.value = '';
                },

                removeEditImage(idx) {
                    const img = this.editForm.images[idx];
                    if (img && img.id) {
                        if (!this.editForm.remove_image_ids) this.editForm.remove_image_ids = [];
                        this.editForm.remove_image_ids.push(img.id);
                    }
                    this.editForm.images.splice(idx, 1);
                    this.editForm.image_files.splice(idx, 1);
                },

                speciesAvatarClass(species) {
                    const map = {
                        dog: 'bg-emerald-500 text-white',
                        cat: 'bg-blue-500 text-white',
                        bird: 'bg-pink-500 text-white',
                        rabbit: 'bg-violet-500 text-white'
                    };
                    return map[species] || 'bg-teal-500 text-white';
                },

                statusLabel(status) {
                    const map = {
                        available: 'Available',
                        adopted: 'Adopted',
                        unavailable: 'In Care',
                        inactive: 'Pending'
                    };
                    return map[status] || status;
                },

                statusClass(status) {
                    const map = {
                        available: 'bg-admin-successBg text-admin-successText border-admin-successText/10',
                        adopted: 'bg-[#EFF6FF] text-[#2563EB] border-[#BFDBFE]/30',
                        unavailable: 'bg-[#F5F3FF] text-[#7C3AED] border-[#DDD6FE]/30',
                        inactive: 'bg-[#FFFBEB] text-[#D97706] border-[#FCD34D]/30',
                    };
                    return map[status] || 'bg-gray-100 text-gray-600 border-gray-200';
                },

                statusIcon(status) {
                    const map = {
                        adopted: 'check',
                        unavailable: 'heart',
                        inactive: 'hourglass'
                    };
                    return map[status] || 'circle';
                },

                formatAge(months) {
                    if (!months && months !== 0) return '-';
                    const y = Math.floor(months / 12);
                    const m = months % 12;
                    if (y === 0) return m + 'm';
                    if (m === 0) return y + 'y';
                    return y + 'y ' + m + 'm';
                },
            }));
        });
    </script>
</x-layouts.admin>
