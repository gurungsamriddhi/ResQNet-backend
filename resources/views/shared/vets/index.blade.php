<x-layouts.admin>

    <div class="bg-[#f8fafc] dark:bg-slate-900 min-h-screen px-4 sm:px-6 md:px-8 py-8"
         x-data="vetManager()"
         x-init="init(); $nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">
                {{ __('Veterinarians Management') }}
            </h2>
            <button type="button" @click="openCreateModal()" class="inline-flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add New Veterinarian
            </button>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: "{{ session('success') }}", type: 'success' } }));
                    }, 500);
                });
            </script>
        @endif

        <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 shadow-sm sm:rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Specialization</th>
                            <th class="px-6 py-4">Contact</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        @forelse($vets as $vet)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors" id="vet-row-{{ $vet->id }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-slate-100" id="vet-name-{{ $vet->id }}">{{ $vet->name }}</div>
                                    @if($vet->license_number)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1" id="vet-license-{{ $vet->id }}">License: {{ $vet->license_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400" id="vet-spec-{{ $vet->id }}">
                                    {{ $vet->specialization ?? 'General Practice' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-500 dark:text-slate-400 space-y-1">
                                        @if($vet->phone)
                                            <div class="flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3"></i> <span id="vet-phone-{{ $vet->id }}">{{ $vet->phone }}</span></div>
                                        @endif
                                        @if($vet->email)
                                            <div class="flex items-center gap-2"><i data-lucide="mail" class="w-3 h-3"></i> <span id="vet-email-{{ $vet->id }}">{{ $vet->email }}</span></div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4" id="vet-status-{{ $vet->id }}">
                                    @if($vet->status === 'active')
                                        <span class="px-2.5 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 text-xs font-bold rounded-full">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button"
                                                @click="fetchShowDetails('{{ route($routePrefix . 'show', $vet) }}')"
                                                class="p-2 text-slate-500 dark:text-slate-400 hover:text-blue-500 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors"
                                                title="View Details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button"
                                                @click="fetchEditDetails('{{ route($routePrefix . 'edit', $vet) }}', '{{ route($routePrefix . 'update', $vet) }}')"
                                                class="p-2 text-slate-500 dark:text-slate-400 hover:text-brand-orange bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors"
                                                title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button"
                                                @click="deleteModalOpen = true; vetToDelete = '{{ addslashes($vet->name) }}'; deleteUrl = '{{ route($routePrefix . 'destroy', $vet) }}'; deleteId = {{ $vet->id }};"
                                                class="p-2 text-slate-500 dark:text-slate-400 hover:text-red-500 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg transition-colors"
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                                        <div class="w-16 h-16 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="stethoscope" class="w-8 h-8 text-slate-400 dark:text-slate-500"></i>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-1">No Veterinarians Found</p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">There are currently no veterinarians registered.</p>
                                        <button type="button" @click="openCreateModal()" class="mt-4 text-brand-orange hover:text-brand-orange/80 font-bold text-sm">Add the first vet &rarr;</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vets->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                    {{ $vets->links() }}
                </div>
            @endif
        </div>

        @if(isset($globalVets) && $globalVets->isNotEmpty())
        <div class="mt-8 mb-4">
            <h3 class="font-bold text-xl text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <i data-lucide="globe" class="w-5 h-5 text-blue-500"></i>
                Platform-Wide Veterinarians
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">These veterinarians are verified by the platform and are available to all organizations. You can view their details but cannot edit or delete them.</p>
        </div>

        <div class="bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 shadow-sm sm:rounded-2xl overflow-hidden opacity-90">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Contact</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50">
                        @foreach($globalVets as $gVet)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-950/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-lg flex-shrink-0">
                                            {{ substr($gVet->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                                {{ $gVet->name }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $gVet->specialization ?? 'General Practice' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                    @if($gVet->email)
                                        <div class="flex items-center gap-2 mb-1">
                                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                            <a href="mailto:{{ $gVet->email }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $gVet->email }}</a>
                                        </div>
                                    @endif
                                    @if($gVet->phone)
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                            <a href="tel:{{ $gVet->phone }}" class="hover:text-blue-600 dark:hover:text-blue-400">{{ $gVet->phone }}</a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($gVet->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-800/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 border border-slate-200 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" @click="fetchShowDetails('{{ route($routePrefix . 'show', $gVet->id) }}')" class="p-2 text-slate-500 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/20 rounded-lg transition-colors" title="View Details">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

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
                 :class="toastType === 'success' ? 'bg-emerald-100 border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800/30' : 'bg-red-100 border-red-200 dark:bg-red-950/30 dark:border-red-800/30'">
                <i data-lucide="check" class="w-4 h-4 text-emerald-600 dark:text-emerald-450" x-show="toastType === 'success'"></i>
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 dark:text-red-450" x-show="toastType === 'error'"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="toastType === 'success' ? 'Success' : 'Error'"></p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400"></i>
            </button>
        </div>

        <!-- Create Modal -->
        <template x-teleport="body">
            <div x-show="createModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="createModalOpen = false">
                <div @click.away="createModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-2xl w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 max-h-[90vh] flex flex-col">
                    <div class="px-8 pt-8 pb-0 flex-shrink-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-md">
                                    <i data-lucide="stethoscope" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-slate-100">Add New Veterinarian</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Fill in the details below to register a new veterinarian.</p>
                                </div>
                            </div>
                            <button @click="createModalOpen = false" class="p-2 rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-350 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <div class="h-px bg-slate-200 dark:bg-slate-700 mt-6"></div>
                    </div>

                    <div class="px-8 py-6 overflow-y-auto flex-1">
                        <form @submit.prevent="submitCreateForm" class="space-y-6">
                            <div x-show="createErrors.general" class="p-3 bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 rounded-lg text-sm border border-red-200 dark:border-red-800/30 flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
                                <span x-text="createErrors.general"></span>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Personal Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="createForm.name" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm px-3.5 py-2.5 transition-all" required placeholder="Dr. Jane Doe">
                                        <p x-show="createErrors.name" x-text="createErrors.name?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Specialization</label>
                                        <input type="text" x-model="createForm.specialization" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm px-3.5 py-2.5 transition-all" placeholder="e.g. Feline Specialist">
                                        <p x-show="createErrors.specialization" x-text="createErrors.specialization?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">License Number</label>
                                        <input type="text" x-model="createForm.license_number" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm px-3.5 py-2.5 transition-all" placeholder="e.g. VET-123456">
                                        <p x-show="createErrors.license_number" x-text="createErrors.license_number?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100 dark:bg-slate-700"></div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Contact Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Phone Number</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                                <i data-lucide="phone" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" x-model="createForm.phone" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm pl-10 pr-3.5 py-2.5 transition-all" placeholder="+1 234 567 8900">
                                        </div>
                                        <p x-show="createErrors.phone" x-text="createErrors.phone?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                                <i data-lucide="mail" class="w-4 h-4"></i>
                                            </div>
                                            <input type="email" x-model="createForm.email" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm pl-10 pr-3.5 py-2.5 transition-all" placeholder="jane@example.com">
                                        </div>
                                        <p x-show="createErrors.email" x-text="createErrors.email?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Clinic Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                            </div>
                                            <input type="text" x-model="createForm.address" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-2 sm:text-sm pl-10 pr-3.5 py-2.5 transition-all" placeholder="123 Vet Street, City">
                                        </div>
                                        <p x-show="createErrors.address" x-text="createErrors.address?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-slate-100 dark:bg-slate-700"></div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3">Status</h4>
                                <div class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-xl border border-emerald-200 dark:border-emerald-800/30">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                            <i data-lucide="shield-check" class="w-4.5 h-4.5"></i>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">Active Status</label>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Toggle to activate or deactivate this veterinarian</p>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" :checked="createForm.status === 'active'" @change="createForm.status = $event.target.checked ? 'active' : 'inactive'" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3 flex-shrink-0">
                        <button type="button" @click="createModalOpen = false" class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750 rounded-lg transition-colors">Cancel</button>
                        <button type="button" @click="submitCreateForm" :disabled="isSavingCreate" class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSavingCreate" style="display: none;"></i>
                            <span x-text="isSavingCreate ? 'Creating...' : 'Create Veterinarian'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Show Details Modal -->
        <template x-teleport="body">
            <div x-show="showModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="showModalOpen = false">
                <div @click.away="showModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-lg w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-950/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i data-lucide="info" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Veterinarian Details</h3>
                        </div>
                        <button @click="showModalOpen = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4" x-show="!isLoadingDetails">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Full Name</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.name"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="vetDetails.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-950/30 dark:text-green-400' : 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-400'"
                                          x-text="vetDetails.status === 'active' ? 'Active' : 'Inactive'">
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Specialization</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.specialization || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">License Number</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.license_number || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Email</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.email || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Phone</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.phone || 'N/A'"></p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase tracking-wider">Address</p>
                                    <p class="font-medium text-slate-900 dark:text-slate-100" x-text="vetDetails.address || 'N/A'"></p>
                                </div>
                            </div>

                            <hr class="border-slate-200 dark:border-slate-700">

                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div class="bg-slate-50 dark:bg-slate-900/30 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100" x-text="vetDetails.medical_records_count"></p>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Records</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/30 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100" x-text="vetDetails.vaccinations_count"></p>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Vaccines</p>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-900/30 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <p class="text-xl font-bold text-slate-900 dark:text-slate-100" x-text="vetDetails.medications_count"></p>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Meds</p>
                                </div>
                            </div>

                            <div class="text-xs text-slate-500 dark:text-slate-400 text-center mt-4">
                                Registered on <span x-text="vetDetails.created_at"></span>
                            </div>
                        </div>
                        <div x-show="isLoadingDetails" class="py-8 flex justify-center">
                            <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-brand-orange"></i>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="showModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">Close</button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Edit Modal -->
        <template x-teleport="body">
            <div x-show="editModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="editModalOpen = false">
                <div @click.away="editModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-xl w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 max-h-[90vh] flex flex-col">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-950/20 flex items-center justify-center text-brand-orange">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Edit Veterinarian</h3>
                        </div>
                        <button @click="editModalOpen = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto flex-1">
                        <div x-show="isLoadingEdit" class="py-12 flex justify-center">
                            <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-brand-orange"></i>
                        </div>

                        <form x-show="!isLoadingEdit" @submit.prevent="submitEditForm" class="space-y-4">
                            <div x-show="editErrors.general" class="p-3 bg-red-50 dark:bg-red-950/20 text-red-700 dark:text-red-400 rounded-lg text-sm border border-red-200 dark:border-red-800/30 flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5"></i>
                                <span x-text="editErrors.general"></span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name *</label>
                                    <input type="text" x-model="editForm.name" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm" required>
                                    <p x-show="editErrors.name" x-text="editErrors.name?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Specialization</label>
                                    <input type="text" x-model="editForm.specialization" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm">
                                    <p x-show="editErrors.specialization" x-text="editErrors.specialization?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                                    <input type="text" x-model="editForm.phone" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm">
                                    <p x-show="editErrors.phone" x-text="editErrors.phone?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                    <input type="email" x-model="editForm.email" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm">
                                    <p x-show="editErrors.email" x-text="editErrors.email?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Clinic Address</label>
                                    <input type="text" x-model="editForm.address" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm">
                                    <p x-show="editErrors.address" x-text="editErrors.address?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">License Number</label>
                                    <input type="text" x-model="editForm.license_number" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:border-brand-orange focus:ring-brand-orange sm:text-sm">
                                    <p x-show="editErrors.license_number" x-text="editErrors.license_number?.[0]" class="mt-1 text-xs text-red-600 dark:text-red-400"></p>
                                </div>

                                <div class="col-span-1 md:col-span-2 flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/30 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-900 dark:text-slate-100">Active Status</label>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Enable or disable this veterinarian</p>
                                    </div>
                                    <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" :checked="editForm.status === 'active'" @change="editForm.status = $event.target.checked ? 'active' : 'inactive'" id="edit_is_active" class="peer sr-only">
                                        <label for="edit_is_active" class="block overflow-hidden h-6 rounded-full bg-slate-300 dark:bg-slate-750 cursor-pointer peer-checked:bg-green-500 transition-colors">
                                            <span class="block w-6 h-6 rounded-full bg-white border border-slate-300 dark:border-slate-600 shadow transform transition-transform peer-checked:translate-x-6 peer-checked:border-green-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700 flex-shrink-0">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                        <button type="button" @click="submitEditForm" :disabled="isSavingEdit" class="px-4 py-2 text-sm font-semibold text-white bg-brand-orange hover:bg-brand-orange/90 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSavingEdit" style="display: none;"></i>
                            <span x-text="isSavingEdit ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div x-show="deleteModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="deleteModalOpen = false">
                <div @click.away="deleteModalOpen = false" class="bg-white dark:bg-slate-800 rounded-xl max-w-md w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center flex-shrink-0 text-red-600 dark:text-red-400">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Delete Veterinarian</h3>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Are you sure you want to delete <strong class="text-slate-700 dark:text-slate-350" x-text="vetToDelete"></strong>? This action cannot be undone.
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">Cancel</button>
                        <button type="button" @click="submitDelete" :disabled="isDeleting" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm inline-flex items-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isDeleting" style="display: none;"></i>
                            <span x-text="isDeleting ? 'Deleting...' : 'Delete Vet'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <script>
            function vetManager() {
                return {
                    toastVisible: false,
                    toastMessage: '',
                    toastType: 'success',
                    toastTimeout: null,

                    createModalOpen: false,
                    isSavingCreate: false,
                    createUrl: '{{ route($routePrefix . "store") }}',
                    createForm: {
                        name: '',
                        specialization: '',
                        phone: '',
                        email: '',
                        address: '',
                        license_number: '',
                        status: 'active'
                    },
                    createErrors: {},

                    showModalOpen: false,
                    isLoadingDetails: false,
                    vetDetails: {},

                    editModalOpen: false,
                    isLoadingEdit: false,
                    isSavingEdit: false,
                    editUpdateUrl: '',
                    editForm: {
                        id: null,
                        name: '',
                        specialization: '',
                        phone: '',
                        email: '',
                        address: '',
                        license_number: '',
                        status: 'active'
                    },
                    editErrors: {},

                    deleteModalOpen: false,
                    vetToDelete: '',
                    deleteUrl: '',
                    deleteId: null,
                    isDeleting: false,

                    init() {},

                    showToast(message, type = 'success') {
                        this.toastMessage = message;
                        this.toastType = type;
                        this.toastVisible = true;
                        if (this.toastTimeout) clearTimeout(this.toastTimeout);
                        this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 4000);
                        if (typeof lucide !== 'undefined') {
                            setTimeout(() => lucide.createIcons(), 50);
                        }
                    },

                    openCreateModal() {
                        this.createForm = { name: '', specialization: '', phone: '', email: '', address: '', license_number: '', status: 'active' };
                        this.createErrors = {};
                        this.createModalOpen = true;
                    },

                    async submitCreateForm() {
                        this.isSavingCreate = true;
                        this.createErrors = {};
                        try {
                            const response = await fetch(this.createUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(this.createForm)
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                if (response.status === 422) {
                                    this.createErrors = data.errors;
                                    this.showToast('Please check the form for errors.', 'error');
                                } else {
                                    const msg = data.message || data.error || 'An error occurred while saving.';
                                    this.createErrors = { general: msg };
                                    this.showToast(msg, 'error');
                                }
                                throw new Error('Request failed');
                            }
                            this.createModalOpen = false;
                            this.showToast(data.message || 'Veterinarian created successfully.', 'success');
                            setTimeout(() => { window.location.reload(); }, 1000);
                        } catch (error) {
                            if (error.message !== 'Request failed') {
                                this.showToast(error.message || 'An unexpected error occurred.', 'error');
                            }
                        } finally {
                            this.isSavingCreate = false;
                        }
                    },

                    async fetchShowDetails(url) {
                        this.showModalOpen = true;
                        this.isLoadingDetails = true;
                        try {
                            const response = await fetch(url, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            });
                            if (!response.ok) throw new Error('Failed to fetch details');
                            this.vetDetails = await response.json();
                        } catch (error) {
                            this.showModalOpen = false;
                            this.showToast('Unable to load veterinarian details.', 'error');
                        } finally {
                            this.isLoadingDetails = false;
                            if (typeof lucide !== 'undefined') setTimeout(() => lucide.createIcons(), 50);
                        }
                    },

                    async fetchEditDetails(editUrl, updateUrl) {
                        this.editModalOpen = true;
                        this.isLoadingEdit = true;
                        this.editUpdateUrl = updateUrl;
                        this.editErrors = {};
                        try {
                            const response = await fetch(editUrl, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                            });
                            if (!response.ok) throw new Error('Failed to fetch details');
                            const data = await response.json();
                            this.editForm = {
                                id: data.id, name: data.name || '', specialization: data.specialization || '',
                                phone: data.phone || '', email: data.email || '', address: data.address || '',
                                license_number: data.license_number || '', status: data.status || 'active'
                            };
                        } catch (error) {
                            this.editModalOpen = false;
                            this.showToast('Unable to load data for editing.', 'error');
                        } finally {
                            this.isLoadingEdit = false;
                        }
                    },

                    async submitEditForm() {
                        this.isSavingEdit = true;
                        this.editErrors = {};
                        try {
                            const payload = { ...this.editForm, _method: 'PUT' };
                            const response = await fetch(this.editUpdateUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload)
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                if (response.status === 422) {
                                    this.editErrors = data.errors;
                                    this.showToast('Please check the form for errors.', 'error');
                                } else {
                                    const msg = data.message || data.error || 'An error occurred while saving.';
                                    this.editErrors = { general: msg };
                                    this.showToast(msg, 'error');
                                }
                                throw new Error('Request failed');
                            }
                            this.editModalOpen = false;
                            this.showToast(data.message || 'Veterinarian updated successfully.', 'success');
                            this.updateRowInTable(data.vet);
                        } catch (error) {
                            if (error.message !== 'Request failed') {
                                this.showToast(error.message || 'An unexpected error occurred.', 'error');
                            }
                        } finally {
                            this.isSavingEdit = false;
                        }
                    },

                    async submitDelete() {
                        this.isDeleting = true;
                        try {
                            const response = await fetch(this.deleteUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ _method: 'DELETE' })
                            });
                            const data = await response.json();
                            if (!response.ok) {
                                const msg = data.message || data.error || 'Failed to delete';
                                this.showToast(msg, 'error');
                                throw new Error('Request failed');
                            }
                            this.deleteModalOpen = false;
                            this.showToast(data.message || 'Veterinarian deleted successfully.', 'success');
                            const row = document.getElementById('vet-row-' + this.deleteId);
                            if (row) row.remove();
                        } catch (error) {
                            this.deleteModalOpen = false;
                            if (error.message !== 'Request failed') {
                                this.showToast(error.message || 'An error occurred while deleting.', 'error');
                            }
                        } finally {
                            this.isDeleting = false;
                        }
                    },

                    updateRowInTable(vet) {
                        const nameEl = document.getElementById('vet-name-' + vet.id);
                        if (nameEl) nameEl.textContent = vet.name;
                        const licenseEl = document.getElementById('vet-license-' + vet.id);
                        if (licenseEl && vet.license_number) licenseEl.textContent = 'License: ' + vet.license_number;
                        const specEl = document.getElementById('vet-spec-' + vet.id);
                        if (specEl) specEl.textContent = vet.specialization || 'General Practice';
                        const phoneEl = document.getElementById('vet-phone-' + vet.id);
                        if (phoneEl) phoneEl.textContent = vet.phone || '';
                        const emailEl = document.getElementById('vet-email-' + vet.id);
                        if (emailEl) emailEl.textContent = vet.email || '';
                        const statusEl = document.getElementById('vet-status-' + vet.id);
                        if (statusEl) {
                            if (vet.status === 'active') {
                                statusEl.innerHTML = '<span class="px-2.5 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 text-xs font-bold rounded-full">Active</span>';
                            } else {
                                statusEl.innerHTML = '<span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-full">Inactive</span>';
                            }
                        }
                        setTimeout(() => { window.location.reload(); }, 1000);
                    }
                }
            }
        </script>
    </div>
</x-layouts.admin>