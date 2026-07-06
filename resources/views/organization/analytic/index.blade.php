<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data=""
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Organization Analytics</h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Performance metrics for {{ $org->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Pets</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center">
                        <i data-lucide="paw-print" class="w-4 h-4"></i>
                    </div>
                </div>
                <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalPets }}</span>
                <div class="mt-2 flex gap-2 text-[10px]">
                    <span class="text-emerald-600 font-semibold">{{ $availablePets }} available</span>
                    <span class="text-slate-400">|</span>
                    <span class="text-blue-600 font-semibold">{{ $totalAdopted }} adopted</span>
                </div>
            </div>

            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Adoption Requests</span>
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-indigo-500 flex items-center justify-center">
                        <i data-lucide="heart-handshake" class="w-4 h-4"></i>
                    </div>
                </div>
                <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalAdoptionRequests }}</span>
                <div class="mt-2 flex gap-2 text-[10px]">
                    <span class="text-emerald-600 font-semibold">{{ $adoptionSuccessRate }}% success rate</span>
                </div>
            </div>

            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Rescue Reports</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/20 text-amber-500 flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i>
                    </div>
                </div>
                <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">{{ $totalRescueReports }}</span>
                <div class="mt-2 flex gap-2 text-[10px]">
                    <span class="text-emerald-600 font-semibold">{{ $rescueResolutionRate }}% resolved</span>
                </div>
            </div>

            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Total Donations</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/20 text-amber-500 flex items-center justify-center">
                        <i data-lucide="hand-coins" class="w-4 h-4"></i>
                    </div>
                </div>
                <span class="text-3xl font-extrabold text-slate-800 dark:text-slate-100">NPR {{ number_format($totalDonations) }}</span>
                <div class="mt-2 flex gap-2 text-[10px]">
                    <span class="text-slate-500 font-semibold">{{ $donationCount }} donations</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 block mb-4">Adoption Requests by Status</span>
                @php $colors = ['pending' => 'bg-amber-400', 'under_review' => 'bg-blue-400', 'approved' => 'bg-indigo-400', 'rejected' => 'bg-red-400', 'completed' => 'bg-emerald-400']; @endphp
                <div class="space-y-3">
                    @forelse($adoptionRequestsByStatus as $status => $count)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-semibold text-slate-700 dark:text-slate-300 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $count }}</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 dark:bg-[#111c2a] rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $colors[$status] ?? 'bg-slate-400' }}" style="width: {{ $totalAdoptionRequests > 0 ? ($count / $totalAdoptionRequests) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No adoption requests yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 block mb-4">Rescue Reports by Status</span>
                @php $rColors = ['reported' => 'bg-red-400', 'assigned' => 'bg-blue-400', 'in_progress' => 'bg-amber-400', 'resolved' => 'bg-emerald-400']; @endphp
                <div class="space-y-3">
                    @forelse($rescueReportsByStatus as $status => $count)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-semibold text-slate-700 dark:text-slate-300 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $count }}</span>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 dark:bg-[#111c2a] rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $rColors[$status] ?? 'bg-slate-400' }}" style="width: {{ $totalRescueReports > 0 ? ($count / $totalRescueReports) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No rescue reports yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 block mb-4">Recent Donations</span>
                <div class="space-y-2">
                    @forelse($recentDonations as $d)
                        <div class="flex items-center justify-between py-2 border-b border-admin-border/40 last:border-0">
                            <div>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $d->user?->name ?? 'Anonymous' }}</span>
                                <span class="block text-[10px] text-slate-400">{{ $d->payment_method }} · {{ $d->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600">NPR {{ number_format($d->amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No donations yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm p-5">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 block mb-4">Recent Adoption Activity</span>
                <div class="space-y-2">
                    @forelse($recentAdoptions as $a)
                        <div class="flex items-center justify-between py-2 border-b border-admin-border/40 last:border-0">
                            <div>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $a->pet?->name ?? 'Unknown pet' }}</span>
                                <span class="block text-[10px] text-slate-400 capitalize">{{ $a->status }} · {{ $a->user?->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-[10px] font-medium text-slate-500">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">No adoption activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
