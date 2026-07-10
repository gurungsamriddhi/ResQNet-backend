<x-layouts.admin>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route($routePrefix . 'index') }}" class="p-2 bg-admin-surface hover:bg-admin-bg text-admin-textSecondary hover:text-brand-orange border border-admin-border rounded-xl transition-colors shadow-sm">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-bold text-2xl text-admin-textPrimary tracking-tight">
                {{ __('Veterinarian Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 md:px-8 py-8" x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        @if(session('success'))
            <div class="max-w-3xl mx-auto mb-6">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="max-w-3xl mx-auto space-y-6">
            {{-- Profile Header --}}
            <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-8 bg-gradient-to-r from-brand-navy to-brand-navy/80">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl font-extrabold flex-shrink-0">
                            {{ strtoupper(substr($vet->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-white font-extrabold text-xl tracking-tight">{{ $vet->name }}</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide uppercase {{ $vet->status === 'active' ? 'bg-green-500/20 text-green-300 border border-green-400/30' : 'bg-slate-500/20 text-slate-300 border border-slate-400/30' }}">
                                    {{ $vet->status === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                                @if($vet->is_global)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold tracking-wide uppercase bg-brand-orange/20 text-orange-300 border border-orange-400/30">
                                        Global
                                    </span>
                                @endif
                            </div>
                            @if($vet->specialization)
                                <p class="text-white/60 text-sm mt-1">{{ $vet->specialization }}</p>
                            @endif
                        </div>
                        <a href="{{ route($routePrefix . 'edit', $vet) }}" class="flex-shrink-0 inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl p-5 text-center">
                    <div class="w-10 h-10 rounded-full bg-admin-primary/10 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="file-text" class="w-5 h-5 text-admin-primary"></i>
                    </div>
                    <p class="text-2xl font-extrabold text-admin-textPrimary">{{ $vet->medicalRecords()->count() }}</p>
                    <p class="text-xs text-admin-textSecondary mt-1 font-medium">Medical Records</p>
                </div>
                <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl p-5 text-center">
                    <div class="w-10 h-10 rounded-full bg-secondary-500/10 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="syringe" class="w-5 h-5 text-secondary-500"></i>
                    </div>
                    <p class="text-2xl font-extrabold text-admin-textPrimary">{{ $vet->vaccinations()->count() }}</p>
                    <p class="text-xs text-admin-textSecondary mt-1 font-medium">Vaccinations</p>
                </div>
                <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl p-5 text-center">
                    <div class="w-10 h-10 rounded-full bg-brand-orange/10 flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="pill" class="w-5 h-5 text-brand-orange"></i>
                    </div>
                    <p class="text-2xl font-extrabold text-admin-textPrimary">{{ $vet->medications()->count() }}</p>
                    <p class="text-xs text-admin-textSecondary mt-1 font-medium">Medications</p>
                </div>
            </div>

            {{-- Details --}}
            <div class="bg-white dark:bg-[#1a2333] border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-admin-border bg-admin-bg/30">
                    <h3 class="text-base font-bold text-admin-textPrimary">Contact & Details</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <p class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider">Phone</p>
                            <p class="text-sm text-admin-textPrimary mt-1 font-medium">{{ $vet->phone ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider">Email</p>
                            <p class="text-sm text-admin-textPrimary mt-1 font-medium">{{ $vet->email ?: '—' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider">Address</p>
                            <p class="text-sm text-admin-textPrimary mt-1 font-medium">{{ $vet->address ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider">License Number</p>
                            <p class="text-sm text-admin-textPrimary mt-1 font-medium">{{ $vet->license_number ?: '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider">Registered</p>
                            <p class="text-sm text-admin-textPrimary mt-1 font-medium">{{ $vet->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                <form action="{{ route($routePrefix . 'destroy', $vet) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this veterinarian?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 text-xs font-bold text-red-500 hover:text-red-700 transition-colors px-3 py-2 rounded-xl hover:bg-red-50">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete Veterinarian
                    </button>
                </form>
                <a href="{{ route($routePrefix . 'index') }}" class="text-xs font-bold text-admin-textSecondary hover:text-admin-textPrimary transition-colors">
                    Back to list
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>
