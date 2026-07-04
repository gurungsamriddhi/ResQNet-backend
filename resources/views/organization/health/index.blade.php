<x-layouts.admin>
    <div id="flash-container">
        @if(session('success'))
            <div class="mx-4 sm:mx-6 md:mx-8 mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="healthTable()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-admin-textPrimary font-extrabold text-3xl tracking-tight">
                    Health Records
                </h1>
                <p class="text-admin-textSecondary text-sm mt-1 font-medium">
                    All medical records and vaccinations across every pet.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="addMedicalOpen = true"
                        class="bg-brand-orange text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-brand-orange/90 transition-all flex items-center gap-2 shadow-md shadow-brand-orange/10">
                    <i data-lucide="plus" class="w-4 h-4 stroke-[2.5]"></i>
                    Add Medical Record
                </button>
                <button @click="addVaccineOpen = true"
                        class="bg-admin-primary text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-admin-primaryPress transition-all flex items-center gap-2 shadow-md">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Add Vaccination
                </button>
            </div>
        </div>

        <template x-if="groupedRecords.length === 0">
            <div class="flex flex-col items-center justify-center py-16 px-6 bg-admin-surface border border-admin-border rounded-2xl shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-admin-bg/50 flex items-center justify-center mb-4 border border-admin-border/60">
                    <i data-lucide="clipboard-list" class="w-8 h-8 text-admin-textSecondary/30" style="stroke-width: 1.5;"></i>
                </div>
                <h3 class="text-base font-extrabold text-admin-textPrimary mb-1">No health records found</h3>
                <p class="text-xs text-admin-textSecondary/70">Add a medical record or vaccination from any pet profile to see it here.</p>
            </div>
        </template>

        <template x-if="groupedRecords.length > 0">
            <div class="bg-admin-surface border border-admin-border rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-admin-border bg-admin-bg/30">
                                <th class="text-left px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Pet</th>
                                <th class="text-left px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Records</th>
                                <th class="text-left px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Latest Activity</th>
                                <th class="text-right px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-admin-border/50">
                            <template x-for="group in groupedRecords" :key="group.pet_id">
                                <tr class="hover:bg-admin-bg/20 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <template x-if="group.pet_image">
                                                <img :src="group.pet_image" :alt="group.pet_name"
                                                     class="w-9 h-9 rounded-lg object-cover shadow-sm border border-slate-100 dark:border-slate-700/50 shrink-0">
                                            </template>
                                            <template x-if="!group.pet_image">
                                                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-extrabold text-sm shadow-sm border border-slate-100 dark:border-slate-700/50 bg-admin-primary/10 text-admin-primary shrink-0"
                                                     x-text="group.pet_name.charAt(0).toUpperCase()">
                                                </div>
                                            </template>
                                            <div>
                                                <a :href="'{{ url('organization/pets') }}/' + group.pet_id" class="font-bold text-admin-textPrimary hover:text-admin-primary transition-colors" x-text="group.pet_name"></a>
                                                <p class="text-[11px] text-admin-textSecondary/70 font-medium" x-text="group.pet_breed || group.pet_species || ''"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <template x-if="group.medical_count > 0">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-admin-primary/10 text-admin-primary border border-admin-primary/20">
                                                    <i data-lucide="file-text" class="w-3 h-3"></i>
                                                    <span x-text="group.medical_count + ' Medical'"></span>
                                                </span>
                                            </template>
                                            <template x-if="group.vaccination_count > 0">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-brand-orange/10 text-brand-orange border border-brand-orange/20">
                                                    <i data-lucide="syringe" class="w-3 h-3"></i>
                                                    <span x-text="group.vaccination_count + ' Vaccination'"></span>
                                                </span>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="text-admin-textSecondary" x-text="group.latest_date || '—'"></span>
                                        <p class="text-[11px] text-admin-textSecondary/60 font-medium mt-0.5" x-text="group.total + ' total record' + (group.total !== 1 ? 's' : '')"></p>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                    @click="openViewModal(group.pet_id)"
                                                    class="text-admin-primary hover:text-opacity-80 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                                <i data-lucide="eye" class="w-3.5 h-3.5 inline mr-1"></i>
                                                View
                                            </button>
                                            <button type="button"
                                                    @click="openAddMedicalForPet(group.pet_id)"
                                                    class="text-brand-orange hover:text-opacity-80 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                                <i data-lucide="heart-pulse" class="w-3.5 h-3.5 inline mr-1"></i>
                                                Medical
                                            </button>
                                            <button type="button"
                                                    @click="openAddVaccineForPet(group.pet_id)"
                                                    class="text-admin-primary hover:text-opacity-80 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                                <i data-lucide="shield-check" class="w-3.5 h-3.5 inline mr-1"></i>
                                                Vaccine
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <div class="text-xs text-admin-textSecondary/60 font-medium">
            Showing <span x-text="groupedRecords.length"></span> pet(s) with records
        </div>

        {{-- Edit Health Record Modal (inside parent scope) --}}
        <div x-show="openEditHealthModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;" @keydown.escape.window="openEditHealthModal = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="openEditHealthModal = false"></div>

            <div x-show="openEditHealthModal" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="openEditHealthModal = false">

                <form @submit.prevent="submitEditRecord">
                    @csrf
                    @method('PUT')

                    <div class="relative bg-gradient-to-br from-brand-orange/20 via-brand-orange/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                        <button type="button" @click="openEditHealthModal = false"
                                class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary transition-all">
                            <i data-lucide="x" class="w-4.5 h-4.5"></i>
                        </button>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                                <i data-lucide="pencil" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-admin-textPrimary" x-text="'Edit ' + (selectedRecord.type_label || 'Record')"></h2>
                                <p class="text-xs text-admin-textSecondary" x-text="'Updating ' + (selectedRecord.pet_name ? selectedRecord.pet_name + '\'s ' : '') + 'record'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Pet name (read-only) --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Pet</label>
                            <p class="text-sm font-semibold text-admin-textPrimary" x-text="selectedRecord.pet_name"></p>
                        </div>

                        {{-- Title / Vaccine Name --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">
                                <span x-text="selectedRecord.model === 'vaccination' ? 'Vaccine Name' : 'Title'"></span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="selectedRecord.title" required
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>

                        {{-- Type (medical only) --}}
                        <div x-show="selectedRecord.model === 'medical'">
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Type <span class="text-red-500">*</span></label>
                            <select x-model="selectedRecord.type" required
                                    class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                                <option value="checkup">Checkup</option>
                                <option value="treatment">Treatment</option>
                                <option value="surgery">Surgery</option>
                                <option value="injury">Injury</option>
                                <option value="observation">Observation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">
                                <span x-text="selectedRecord.model === 'vaccination' ? 'Date Administered' : 'Record Date'"></span>
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="selectedRecord.date" required
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>

                        {{-- Next Due Date --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">
                                <span x-text="selectedRecord.model === 'vaccination' ? 'Next Due Date' : 'Next Visit Date'"></span>
                            </label>
                            <input type="date" x-model="selectedRecord.next_date"
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>

                        {{-- Status badge (read-only) --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Status</label>
                            <div>
                                <template x-if="selectedRecord.model === 'vaccination' && selectedRecord.next_date && new Date(selectedRecord.next_date) < new Date()">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Overdue
                                    </span>
                                </template>
                                <template x-if="selectedRecord.next_date && !(selectedRecord.model === 'vaccination' && new Date(selectedRecord.next_date) < new Date())">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Active
                                    </span>
                                </template>
                                <template x-if="!selectedRecord.next_date">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-[#111c2a] text-slate-500 dark:text-slate-300 border border-slate-200 dark:border-slate-700/40">
                                        Completed
                                    </span>
                                </template>
                            </div>
                        </div>

                        {{-- Assigned Veterinarian --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Assigned Veterinarian</label>
                            <select x-model="selectedRecord.vet_name"
                                    class="w-full bg-slate-50 dark:bg-[#111c2a] border border-slate-200 dark:border-slate-700/40 text-slate-800 dark:text-slate-200 text-sm p-2.5 rounded-xl focus:outline-none">
                                <option value="">Select Veterinarian (Optional)</option>
                                @foreach($vets as $vet)
                                    <option value="{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}">{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}{{ $vet->is_global ? ' ★' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Notes</label>
                            <textarea x-model="selectedRecord.notes" rows="2"
                                      class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button type="button" @click="openEditHealthModal = false"
                                class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textSecondary hover:bg-admin-bg font-semibold text-xs transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="savingEdit"
                                class="px-4 py-2 rounded-xl bg-brand-orange text-white font-semibold text-xs hover:bg-brand-orange/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="savingEdit ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

{{-- View Pet Health Modal --}}
    <div x-show="viewPetOpen" x-cloak class="fixed inset-0 z-50 flex items-start justify-center p-4 sm:p-6 pt-8 sm:pt-12"
         style="display: none;" @keydown.escape.window="viewPetOpen = false">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="viewPetOpen = false"></div>

        <div x-show="viewPetOpen" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl">

            {{-- Rich Pet Header --}}
            <div class="bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                <button type="button" @click="viewPetOpen = false"
                        class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary transition-all">
                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                </button>
                <div class="flex items-start gap-5">
                    <template x-if="getPetById(viewPetId)?.pet_image">
                        <img :src="getPetById(viewPetId).pet_image" :alt="getPetById(viewPetId)?.name || 'Pet'"
                             class="w-16 h-16 rounded-2xl object-cover shadow-md shrink-0">
                    </template>
                    <template x-if="!getPetById(viewPetId)?.pet_image">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-md bg-admin-primary/15 text-admin-primary shrink-0"
                             x-text="(getPetById(viewPetId)?.name || '?').charAt(0).toUpperCase()">
                        </div>
                    </template>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-extrabold text-admin-textPrimary truncate" x-text="getPetById(viewPetId)?.name || 'Pet Details'"></h2>
                        <p class="text-sm text-admin-textSecondary mt-0.5">
                            <span x-text="getPetById(viewPetId)?.breed || ''"></span>
                            <span x-show="getPetById(viewPetId)?.breed && getPetById(viewPetId)?.species"> · </span>
                            <span x-text="getPetById(viewPetId)?.species || ''"></span>
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-[#111c2a] border border-admin-border text-admin-textSecondary text-xs shadow-sm">
                                <i data-lucide="venus-and-mars" class="w-3.5 h-3.5 text-admin-textSecondary/70"></i>
                                <span x-text="getPetById(viewPetId)?.gender || 'Unknown'"></span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-[#111c2a] border border-admin-border text-admin-textSecondary text-xs shadow-sm">
                                <i data-lucide="palette" class="w-3.5 h-3.5 text-admin-textSecondary/70"></i>
                                <span x-text="getPetById(viewPetId)?.color || 'Unknown'"></span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-[#111c2a] border border-admin-border text-admin-textSecondary text-xs shadow-sm">
                                <i data-lucide="cake" class="w-3.5 h-3.5 text-admin-textSecondary/70"></i>
                                <span x-text="getPetById(viewPetId)?.date_of_birth ? getPetAge(getPetById(viewPetId).date_of_birth) : 'Unknown'"></span>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold shadow-sm">
                                <i data-lucide="clipboard-list" class="w-3.5 h-3.5"></i>
                                <span x-text="petRecords.length + ' record' + (petRecords.length !== 1 ? 's' : '')"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Records --}}
            <div class="p-6 space-y-6">

                {{-- Medical Reports --}}
                <template x-if="medicalRecords.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-admin-textPrimary mb-3 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-admin-primary"></i>
                            Medical Reports (<span x-text="medicalRecords.length"></span>)
                        </h3>
                        <div class="space-y-3">
                            <template x-for="rec in medicalRecords" :key="'med-' + rec.id">
                                <div class="bg-white dark:bg-[#0b0f19] border border-admin-border rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-admin-primary/10 text-admin-primary border border-admin-primary/20 capitalize" x-text="rec.type"></span>
                                                <template x-if="!rec.next_date">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                                        Completed
                                                    </span>
                                                </template>
                                                <template x-if="rec.next_date">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Active
                                                    </span>
                                                </template>
                                            </div>
                                            <h4 class="text-sm font-bold text-admin-textPrimary" x-text="rec.title"></h4>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-admin-textSecondary">
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    <span x-text="rec.date"></span>
                                                </span>
                                                <span class="flex items-center gap-1" x-show="rec.next_date">
                                                    <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    Next: <span class="font-semibold text-brand-orange" x-text="rec.next_date"></span>
                                                </span>
                                                <span class="flex items-center gap-1" x-show="rec.vet_name">
                                                    <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    <span x-text="rec.vet_name"></span>
                                                </span>
                                            </div>
                                            <p x-show="rec.description" class="mt-3 text-xs text-admin-textSecondary/90 bg-admin-bg/50 rounded-lg p-3 border border-admin-border/50 leading-relaxed" x-text="rec.description"></p>
                                            <p x-show="rec.notes" class="mt-2 text-xs text-admin-textSecondary/60 italic flex items-start gap-1">
                                                <i data-lucide="message-square-text" class="w-3 h-3 mt-0.5 shrink-0"></i>
                                                <span x-text="rec.notes"></span>
                                            </p>
                                        </div>
                                        <button type="button" @click="deleteRecord(rec)"
                                                class="shrink-0 w-7 h-7 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 hover:text-red-600 flex items-center justify-center transition-all"
                                                title="Delete record">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Vaccinations --}}
                <template x-if="vaccinationRecords.length > 0">
                    <div>
                        <h3 class="text-sm font-bold text-admin-textPrimary mb-3 flex items-center gap-2">
                            <i data-lucide="syringe" class="w-4 h-4 text-brand-orange"></i>
                            Vaccination Records (<span x-text="vaccinationRecords.length"></span>)
                        </h3>
                        <div class="space-y-3">
                            <template x-for="rec in vaccinationRecords" :key="'vac-' + rec.id">
                                <div class="bg-white dark:bg-[#0b0f19] border border-admin-border rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-orange/10 text-brand-orange border border-brand-orange/20">
                                                    <i data-lucide="syringe" class="w-3 h-3"></i>
                                                    Vaccination
                                                </span>
                                                <template x-if="rec.next_date && new Date(rec.next_date) < new Date()">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                        Overdue
                                                    </span>
                                                </template>
                                                <template x-if="rec.next_date && !(new Date(rec.next_date) < new Date())">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Active
                                                    </span>
                                                </template>
                                                <template x-if="!rec.next_date">
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                                        Completed
                                                    </span>
                                                </template>
                                            </div>
                                            <h4 class="text-sm font-bold text-admin-textPrimary" x-text="rec.title"></h4>
                                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-admin-textSecondary">
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    Administered: <span class="font-semibold" x-text="rec.date"></span>
                                                </span>
                                                <span class="flex items-center gap-1" x-show="rec.next_date">
                                                    <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    Due: <span class="font-semibold text-brand-orange" x-text="rec.next_date"></span>
                                                </span>
                                                <span class="flex items-center gap-1" x-show="rec.vet_name">
                                                    <i data-lucide="stethoscope" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                    <span x-text="rec.vet_name"></span>
                                                </span>
                                            </div>
                                            <p x-show="rec.notes" class="mt-3 text-xs text-admin-textSecondary/60 italic flex items-start gap-1 bg-admin-bg/30 rounded-lg p-3 border border-admin-border/30">
                                                <i data-lucide="message-square-text" class="w-3 h-3 mt-0.5 shrink-0"></i>
                                                <span x-text="rec.notes"></span>
                                            </p>
                                        </div>
                                        <button type="button" @click="deleteRecord(rec)"
                                                class="shrink-0 w-7 h-7 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 hover:text-red-600 flex items-center justify-center transition-all"
                                                title="Delete record">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Empty State --}}
                <template x-if="petRecords.length === 0">
                    <div class="flex flex-col items-center justify-center py-16 px-6 bg-admin-bg/30 rounded-2xl border border-dashed border-admin-border">
                        <div class="w-14 h-14 rounded-full bg-white dark:bg-[#111c2a] flex items-center justify-center mb-4 shadow-sm border border-admin-border">
                            <i data-lucide="clipboard-list" class="w-7 h-7 text-admin-textSecondary/40"></i>
                        </div>
                        <p class="text-base font-bold text-admin-textPrimary mb-1">No health records yet</p>
                        <p class="text-sm text-admin-textSecondary/70">Add a medical record or vaccination from the pet's profile to get started.</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Add Medical Record Modal --}}
    <div x-show="addMedicalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         style="display: none;" @keydown.escape.window="addMedicalOpen = false">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="addMedicalOpen = false"></div>

        <div x-show="addMedicalOpen" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
             @click.away="addMedicalOpen = false">

            <form @submit.prevent="med_submitRecord">
                @csrf
                <div class="relative bg-gradient-to-br from-brand-orange/20 via-brand-orange/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                    <button type="button" @click="addMedicalOpen = false"
                            class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary transition-all">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center">
                            <i data-lucide="heart-pulse" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-admin-textPrimary">Add Medical Record</h2>
                            <p class="text-xs text-admin-textSecondary">Log a health event for a pet.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Pet <span class="text-red-500">*</span></label>
                        <select x-model="med_pet_id" required
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            <option value="">Select a pet</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->breed ? ' — ' . $pet->breed : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Title</label>
                        <input type="text" x-model="med_title" placeholder="e.g. Routine Checkup"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Type <span class="text-red-500">*</span></label>
                            <select x-model="med_type" required
                                    class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                                <option value="checkup">Checkup</option>
                                <option value="treatment">Treatment</option>
                                <option value="surgery">Surgery</option>
                                <option value="injury">Injury</option>
                                <option value="observation">Observation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Record Date <span class="text-red-500">*</span></label>
                            <input type="date" x-model="med_record_date" required
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Next Visit</label>
                        <input type="date" x-model="med_next_visit_date"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Veterinarian</label>
                        <select x-model="med_vet_name"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            <option value="">Select Veterinarian (Optional)</option>
                            @foreach($vets as $vet)
                                <option value="{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}">{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}{{ $vet->is_global ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Description</label>
                        <textarea x-model="med_description" rows="2"
                                  class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Notes</label>
                        <textarea x-model="med_notes" rows="2"
                                  class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="addMedicalOpen = false"
                            class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textSecondary hover:bg-admin-bg font-semibold text-xs transition-all">
                        Cancel
                    </button>
                    <button type="submit" :disabled="med_saving"
                            class="px-4 py-2 rounded-xl bg-brand-orange text-white font-semibold text-xs hover:bg-brand-orange/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="med_saving ? 'Saving...' : 'Create Record'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Vaccination Modal --}}
    <div x-show="addVaccineOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         style="display: none;" @keydown.escape.window="addVaccineOpen = false">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="addVaccineOpen = false"></div>

        <div x-show="addVaccineOpen" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
             @click.away="addVaccineOpen = false">

            <form @submit.prevent="vac_submitVaccine">
                @csrf
                <div class="relative bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-8 pb-6 px-6 border-b border-admin-border/60">
                    <button type="button" @click="addVaccineOpen = false"
                            class="absolute top-4 right-4 p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary transition-all">
                        <i data-lucide="x" class="w-4.5 h-4.5"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-admin-primary/10 text-admin-primary flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-admin-textPrimary">Add Vaccination</h2>
                            <p class="text-xs text-admin-textSecondary">Record a vaccination for a pet.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Pet <span class="text-red-500">*</span></label>
                        <select x-model="vac_pet_id" required
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            <option value="">Select a pet</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->name }}{{ $pet->breed ? ' — ' . $pet->breed : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Vaccine Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="vac_vaccine_name" required placeholder="e.g. Rabies, DHPP"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Batch Number</label>
                            <input type="text" x-model="vac_batch_number" placeholder="e.g. B12345"
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Date Admin. <span class="text-red-500">*</span></label>
                            <input type="date" x-model="vac_date_administered" required
                                   class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Next Due Date</label>
                        <input type="date" x-model="vac_next_due_date"
                               class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Veterinarian</label>
                        <select x-model="vac_vet_name"
                                class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            <option value="">Select Veterinarian (Optional)</option>
                            @foreach($vets as $vet)
                                <option value="{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}">{{ $vet->name }}{{ $vet->specialization ? ' (' . $vet->specialization . ')' : '' }}{{ $vet->is_global ? ' ★' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary mb-1.5">Notes</label>
                        <textarea x-model="vac_notes" rows="2"
                                  class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/20 flex items-center justify-end gap-3 rounded-b-2xl">
                    <button type="button" @click="addVaccineOpen = false"
                            class="px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textSecondary hover:bg-admin-bg font-semibold text-xs transition-all">
                        Cancel
                    </button>
                    <button type="submit" :disabled="vac_saving"
                            class="px-4 py-2 rounded-xl bg-admin-primary text-white font-semibold text-xs hover:bg-admin-primaryPress transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="vac_saving ? 'Saving...' : 'Add Vaccination'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="confirmDeleteOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="display: none;" @keydown.escape.window="cancelDelete()">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="cancelDelete()"></div>

        <div x-show="confirmDeleteOpen" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-sm bg-admin-surface border border-admin-border rounded-2xl shadow-2xl p-6">

            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center mb-4">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-red-500"></i>
                </div>
                <h3 class="text-lg font-bold text-admin-textPrimary mb-2">Delete Record?</h3>
                <p class="text-sm text-admin-textSecondary/80 mb-1">
                    Do you really want to delete this
                    <span class="font-semibold text-admin-textPrimary" x-text="(confirmDeleteRecord?.type_label || 'record').toLowerCase()"></span>
                    for
                    <span class="font-semibold text-admin-textPrimary" x-text="confirmDeleteRecord?.pet_name || 'this pet'"></span>?
                </p>
                <p class="text-xs text-red-500/80 font-medium">This action cannot be undone.</p>
            </div>

            <div class="mt-6 flex items-center gap-3 justify-end">
                <button type="button" @click="cancelDelete()"
                        class="px-4 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textSecondary hover:bg-admin-bg font-semibold text-xs transition-all flex-1">
                    Cancel
                </button>
                <button type="button" @click="executeDelete()"
                        class="px-4 py-2.5 rounded-xl bg-red-500 text-white font-semibold text-xs hover:bg-red-600 transition-all flex-1">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('healthTable', () => ({
                addMedicalOpen: false,
                addVaccineOpen: false,
                openEditHealthModal: false,
                selectedRecord: {},
                savingEdit: false,
                viewPetOpen: false,
                viewPetId: null,
                allRecords: [],
                petsData: [],
                preselectedPetId: null,
                preselectedPetName: '',
                med_pet_id: '',
                med_title: '',
                med_type: 'checkup',
                med_record_date: new Date().toISOString().split('T')[0],
                med_next_visit_date: '',
                med_description: '',
                med_notes: '',
                med_vet_name: '',
                med_saving: false,
                vac_pet_id: '',
                vac_vaccine_name: '',
                vac_batch_number: '',
                vac_date_administered: new Date().toISOString().split('T')[0],
                vac_next_due_date: '',
                vac_notes: '',
                vac_vet_name: '',
                vac_saving: false,
                confirmDeleteOpen: false,
                confirmDeleteRecord: null,

                init() {
                    this.allRecords = window.healthRecordsList || [];
                    this.petsData = window.petsList || [];
                    window.addEventListener('close-add-medical', () => {
                        this.addMedicalOpen = false;
                        window.location.reload();
                    });
                    window.addEventListener('close-add-vaccine', () => {
                        this.addVaccineOpen = false;
                        window.location.reload();
                    });
                },

                get groupedRecords() {
                    const map = new Map();
                    this.allRecords.forEach(r => {
                        if (!map.has(r.pet_id)) {
                            map.set(r.pet_id, {
                                pet_id: r.pet_id,
                                pet_name: r.pet_name,
                                pet_breed: r.pet_breed,
                                pet_species: r.pet_species,
                                pet_image: r.pet_image || null,
                                medical_count: 0,
                                vaccination_count: 0,
                                total: 0,
                                latest_date: null,
                            });
                        }
                        const g = map.get(r.pet_id);
                        if (r.pet_image && !g.pet_image) g.pet_image = r.pet_image;
                        if (r.model === 'medical') g.medical_count++;
                        else g.vaccination_count++;
                        g.total++;
                        if (!g.latest_date || r.date > g.latest_date) g.latest_date = r.date;
                    });
                    return Array.from(map.values()).sort((a, b) => a.pet_name.localeCompare(b.pet_name));
                },

                getPetById(id) {
                    return this.petsData.find(p => p.id === id);
                },

                get petRecords() {
                    return this.allRecords.filter(r => r.pet_id === this.viewPetId);
                },

                get medicalRecords() {
                    return this.petRecords.filter(r => r.model === 'medical');
                },

                get vaccinationRecords() {
                    return this.petRecords.filter(r => r.model === 'vaccination');
                },

                getPetAge(dateOfBirth) {
                    if (!dateOfBirth) return '';
                    const dob = new Date(dateOfBirth);
                    const now = new Date();
                    const diff = now - dob;
                    const years = Math.floor(diff / (365.25 * 24 * 60 * 60 * 1000));
                    const months = Math.floor((diff % (365.25 * 24 * 60 * 60 * 1000)) / (30.44 * 24 * 60 * 60 * 1000));
                    if (years > 0) return `${years}y ${months}m`;
                    if (months > 0) return `${months}m`;
                    const days = Math.floor(diff / (24 * 60 * 60 * 1000));
                    return `${days}d`;
                },

                openAddMedicalForPet(petId) {
                    this.preselectedPetId = petId;
                    const pet = this.petsData.find(p => p.id === petId);
                    this.preselectedPetName = pet ? pet.name : '';
                    this.med_pet_id = petId;
                    this.med_title = '';
                    this.med_type = 'checkup';
                    this.med_record_date = new Date().toISOString().split('T')[0];
                    this.med_next_visit_date = '';
                    this.med_description = '';
                    this.med_notes = '';
                    this.med_vet_name = '';
                    this.med_saving = false;
                    this.addMedicalOpen = true;
                },

                openAddVaccineForPet(petId) {
                    this.preselectedPetId = petId;
                    const pet = this.petsData.find(p => p.id === petId);
                    this.preselectedPetName = pet ? pet.name : '';
                    this.vac_pet_id = petId;
                    this.vac_vaccine_name = '';
                    this.vac_batch_number = '';
                    this.vac_date_administered = new Date().toISOString().split('T')[0];
                    this.vac_next_due_date = '';
                    this.vac_notes = '';
                    this.vac_vet_name = '';
                    this.vac_saving = false;
                    this.addVaccineOpen = true;
                },

                openViewModal(petId) {
                    this.viewPetId = petId;
                    this.viewPetOpen = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                deleteRecord(rec) {
                    this.confirmDeleteRecord = rec;
                    this.confirmDeleteOpen = true;
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
                },

                cancelDelete() {
                    this.confirmDeleteOpen = false;
                    this.confirmDeleteRecord = null;
                },

                async executeDelete() {
                    const rec = this.confirmDeleteRecord;
                    if (!rec) return;
                    const label = rec.type_label || 'record';
                    try {
                        const url = rec.model === 'medical'
                            ? '{{ route("organization.health.medical-records.destroy", "") }}' + '/' + rec.id
                            : '{{ route("organization.health.vaccinations.destroy", "") }}' + '/' + rec.id;
                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                        });
                        if (!res.ok) {
                            let msg = 'Failed to delete record.';
                            try {
                                const err = await res.json();
                                msg = err.message || msg;
                            } catch (e) {}
                            alert(msg);
                            return;
                        }
                        this.allRecords = this.allRecords.filter(r => !(r.id === rec.id && r.model === rec.model));
                        this.confirmDeleteOpen = false;
                        this.confirmDeleteRecord = null;
                    } catch (e) {
                        alert('An error occurred while deleting the record.');
                    }
                },

                async submitEditRecord() {
                    if (!this.selectedRecord.title?.trim()) return;
                    this.savingEdit = true;
                    try {
                        const isMedical = this.selectedRecord.model === 'medical';
                        const recordId = this.selectedRecord.id;
                        const baseUrl = isMedical
                            ? '{{ route("organization.health.medical-records.update", "") }}'
                            : '{{ route("organization.health.vaccinations.update", "") }}';
                        const url = baseUrl + '/' + recordId;

                        const body = isMedical
                            ? {
                                title: this.selectedRecord.title,
                                type: this.selectedRecord.type,
                                record_date: this.selectedRecord.date,
                                next_visit_date: this.selectedRecord.next_date || null,
                                description: this.selectedRecord.description || null,
                                notes: this.selectedRecord.notes || null,
                                vet_name: this.selectedRecord.vet_name || null,
                            }
                            : {
                                vaccine_name: this.selectedRecord.title,
                                date_administered: this.selectedRecord.date,
                                next_due_date: this.selectedRecord.next_date || null,
                                notes: this.selectedRecord.notes || null,
                                vet_name: this.selectedRecord.vet_name || null,
                            };

                        const res = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(body),
                        });
                        if (!res.ok) {
                            let msg = 'Failed to update record.';
                            try {
                                const err = await res.json();
                                msg = err.message || msg;
                            } catch (e) {}
                            alert(msg);
                            return;
                        }
                        sessionStorage.setItem('flash_success', 'Record updated successfully.');
                        window.location.reload();
                    } catch (e) {
                        alert('An error occurred while updating the record.');
                    } finally {
                        this.savingEdit = false;
                    }
                },

                async med_submitRecord() {
                    const petId = this.preselectedPetId || this.med_pet_id;
                    if (!petId) return;
                    this.med_saving = true;
                    try {
                        const res = await fetch('{{ route("organization.health.medical-records.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                pet_id: petId,
                                title: this.med_title,
                                type: this.med_type,
                                record_date: this.med_record_date,
                                next_visit_date: this.med_next_visit_date || null,
                                description: this.med_description || null,
                                notes: this.med_notes || null,
                                vet_name: this.med_vet_name || null,
                            }),
                        });
                        if (!res.ok) {
                            let msg = 'Failed to save record.';
                            try {
                                const err = await res.json();
                                msg = err.message || msg;
                            } catch (e) {}
                            alert(msg);
                            return;
                        }
                        sessionStorage.setItem('flash_success', 'Medical record added successfully.');
                        window.dispatchEvent(new CustomEvent('close-add-medical'));
                    } catch (e) {
                        alert('An error occurred while saving the record.');
                    } finally {
                        this.med_saving = false;
                    }
                },

                async vac_submitVaccine() {
                    const petId = this.preselectedPetId || this.vac_pet_id;
                    if (!petId || !this.vac_vaccine_name.trim()) return;
                    this.vac_saving = true;
                    try {
                        const res = await fetch('{{ route("organization.health.vaccinations.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                pet_id: petId,
                                vaccine_name: this.vac_vaccine_name,
                                batch_number: this.vac_batch_number || null,
                                date_administered: this.vac_date_administered,
                                next_due_date: this.vac_next_due_date || null,
                                notes: this.vac_notes || null,
                                vet_name: this.vac_vet_name || null,
                            }),
                        });
                        if (!res.ok) {
                            let msg = 'Failed to save vaccination.';
                            try {
                                const err = await res.json();
                                msg = err.message || msg;
                            } catch (e) {}
                            alert(msg);
                            return;
                        }
                        sessionStorage.setItem('flash_success', 'Vaccination record added successfully.');
                        window.dispatchEvent(new CustomEvent('close-add-vaccine'));
                    } catch (e) {
                        alert('An error occurred while saving the vaccination.');
                    } finally {
                        this.vac_saving = false;
                    }
                },
            }));
        });
    </script>

    <script>
        window.healthRecordsList = @json($healthRecords->values());
        window.petsList = @json($pets);
    </script>

    <script>
        (function() {
            var msg = sessionStorage.getItem('flash_success');
            if (msg) {
                sessionStorage.removeItem('flash_success');
                var container = document.getElementById('flash-container');
                if (container) {
                    var div = document.createElement('div');
                    div.className = 'mx-4 sm:mx-6 md:mx-8 mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700';
                    div.textContent = msg;
                    container.prepend(div);
                }
            }
        })();
    </script>
</x-layouts.admin>
