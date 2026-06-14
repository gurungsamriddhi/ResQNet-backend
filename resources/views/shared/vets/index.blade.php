<x-layouts.admin>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-admin-textPrimary tracking-tight">
                {{ __('Veterinarians Management') }}
            </h2>
            <a href="{{ route('admin.vets.create') }}" class="inline-flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add New Vet
            </a>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 md:px-8 py-8" x-data="{ deleteModalOpen: false, vetToDelete: null, deleteUrl: '' }" x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">
        
        @if (session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-700 p-4 rounded-xl flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="text-sm font-bold">{{ session('success') }}</h4>
                </div>
            </div>
        @endif

        <div class="bg-white border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-admin-bg/50 text-admin-textSecondary font-semibold border-b border-admin-border">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Specialization</th>
                            <th class="px-6 py-4">Contact</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border">
                        @forelse($vets as $vet)
                            <tr class="hover:bg-admin-bg/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-admin-textPrimary">{{ $vet->name }}</div>
                                    @if($vet->license_number)
                                        <div class="text-xs text-admin-textSecondary mt-1">License: {{ $vet->license_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-admin-textSecondary">
                                    {{ $vet->specialization ?? 'General Practice' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-admin-textSecondary space-y-1">
                                        @if($vet->phone)
                                            <div class="flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3"></i> {{ $vet->phone }}</div>
                                        @endif
                                        @if($vet->email)
                                            <div class="flex items-center gap-2"><i data-lucide="mail" class="w-3 h-3"></i> {{ $vet->email }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($vet->is_active)
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-full">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.vets.edit', $vet) }}" class="p-2 text-admin-textSecondary hover:text-brand-orange bg-admin-surface border border-admin-border rounded-lg transition-colors" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <button type="button" 
                                                @click="deleteModalOpen = true; vetToDelete = '{{ addslashes($vet->name) }}'; deleteUrl = '{{ route('admin.vets.destroy', $vet) }}'"
                                                class="p-2 text-admin-textSecondary hover:text-red-500 bg-admin-surface border border-admin-border rounded-lg transition-colors" 
                                                title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-admin-textSecondary">
                                        <div class="w-16 h-16 bg-admin-surface border border-admin-border rounded-full flex items-center justify-center mb-4">
                                            <i data-lucide="stethoscope" class="w-8 h-8 text-admin-textSecondary/50"></i>
                                        </div>
                                        <p class="text-lg font-bold text-admin-textPrimary mb-1">No Veterinarians Found</p>
                                        <p class="text-sm">There are currently no global veterinarians registered.</p>
                                        <a href="{{ route('admin.vets.create') }}" class="mt-4 text-brand-orange hover:text-brand-orange/80 font-bold text-sm">Add the first vet &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($vets->hasPages())
                <div class="p-4 border-t border-admin-border bg-admin-surface">
                    {{ $vets->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div x-show="deleteModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="deleteModalOpen = false">
                <div @click.away="deleteModalOpen = false" class="bg-white rounded-xl max-w-md w-full mx-4 shadow-2xl overflow-hidden border border-slate-200">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900">Delete Veterinarian</h3>
                        </div>
                        <p class="text-sm text-slate-500">
                            Are you sure you want to delete <strong class="text-slate-700" x-text="vetToDelete"></strong>? This action cannot be undone.
                        </p>
                    </div>
                    <div class="bg-slate-50 p-4 flex justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                        <form method="POST" :action="deleteUrl">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Delete Vet</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.admin>
