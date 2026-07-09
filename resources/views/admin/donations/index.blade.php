<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="donationManager()">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-admin-border pb-5">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Donations</h1>
                <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">Monitor and manage all incoming donations</p>
            </div>
            <div>
                <button type="button" @click="exportModalOpen = true" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl transition-all shadow-sm">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Export to Excel
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 dark:bg-emerald-500/10 dark:border-emerald-500/20 px-4 py-3 text-sm font-semibold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-500/10 dark:border-red-500/20 px-4 py-3 text-sm text-red-800 dark:text-red-400">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
                <div class="flex flex-col justify-between h-full">
                    <div>
                        <span class="text-xs font-bold tracking-wider text-slate-500 dark:text-slate-300 uppercase block">Total Donations Collected</span>
                        <h3 class="text-slate-900 dark:text-slate-100 text-3xl font-extrabold tracking-tight mt-3">Rs. {{ number_format($totalCollected, 2) }}</h3>
                    </div>
                </div>
                <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-emerald-600/10 text-emerald-600">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-slate-700/40 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-[#111c2a] border-b border-admin-border text-slate-800 dark:text-slate-200 font-semibold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Phone Number</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border/60">
                        @forelse($donations as $donation)
                            @php
                                $displayName = $donation->is_anonymous || blank($donation->donor_name)
                                    ? 'Valued Supporter'
                                    : $donation->donor_name;
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    @if($donation->is_anonymous)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold tracking-wide uppercase bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-600/50 shadow-sm">Anonymous</span>
                                    @else
                                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ $donation->donor_name ?? '—' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-300">
                                    @if($donation->is_anonymous)
                                        <span class="text-slate-300 dark:text-slate-600">—</span>
                                    @else
                                        {{ $donation->donor_phone ?? '—' }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-300">{{ $donation->donor_email ?? '—' }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-300">{{ $donation->created_at->format('M j, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button"
                                            data-id="{{ $donation->id }}"
                                            data-email="{{ $donation->donor_email ?? '' }}"
                                            data-name="{{ $displayName }}"
                                            data-date="{{ $donation->created_at->format('F j, Y') }}"
                                            data-amount="{{ $donation->amount }}"
                                            @click="openPreview($el)"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-brand-orange/10 text-brand-orange hover:bg-brand-orange hover:text-white text-xs font-bold transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                                        <i data-lucide="mail-heart" class="w-4 h-4"></i>
                                        Send Appreciation
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">No completed donations yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($donations->hasPages())
                <div class="px-6 py-4 border-t border-admin-border/60">{{ $donations->links() }}</div>
            @endif
        </div>

        <!-- Email Preview Modal -->
        <div x-show="previewOpen"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="previewOpen = false"
                 class="bg-white dark:bg-[#111c2a] rounded-2xl max-w-xl w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700/40">
                <div class="p-6 pb-4 border-b border-slate-200 dark:border-slate-700/40">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-orange/10 flex items-center justify-center text-brand-orange">
                                <i data-lucide="mail-heart" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Send Appreciation Email</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Review the message before sending</p>
                            </div>
                        </div>
                        <button type="button" @click="previewOpen = false"
                                class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-400 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
                <div class="px-6 py-4 space-y-3 bg-slate-50/50 dark:bg-slate-900/30 border-b border-slate-200 dark:border-slate-700/40">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="font-bold text-slate-600 dark:text-slate-400 w-16">To:</span>
                        <span class="text-slate-800 dark:text-slate-200 font-medium" x-text="previewEmail"></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="font-bold text-slate-600 dark:text-slate-400 w-16">Subject:</span>
                        <span class="text-slate-800 dark:text-slate-200 font-medium">Thank You for Your Generous Donation — ResQNet</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="bg-white dark:bg-[#0f1729] border border-slate-200 dark:border-slate-700/50 rounded-xl p-6 shadow-inner">
                        <div class="text-center mb-5">
                            <div class="w-12 h-12 rounded-full bg-brand-orange/10 flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="heart" class="w-6 h-6 text-brand-orange"></i>
                            </div>
                            <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Thank You, <span x-text="previewName"></span>!</h2>
                        </div>
                        <div class="space-y-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            <p>Your generosity makes our mission possible. On behalf of the entire ResQNet community, we want to express our sincere gratitude for your heartfelt contribution.</p>
                            <div class="bg-brand-orange/5 dark:bg-brand-orange/10 border border-brand-orange/10 dark:border-brand-orange/20 rounded-xl p-4 my-4">
                                <div class="grid grid-cols-2 gap-4 text-center">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Donation Amount</p>
                                        <p class="text-lg font-extrabold text-brand-orange mt-1">Rs. <span x-text="previewAmount"></span></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</p>
                                        <p class="text-lg font-extrabold text-slate-900 dark:text-slate-100 mt-1" x-text="previewDate"></p>
                                    </div>
                                </div>
                            </div>
                            <p>Your support helps us maintain the platform, improve features, and ensure help reaches those who need it most. Every contribution brings us one step closer to a better, safer community for everyone.</p>
                            <div class="pt-2">
                                <a href="{{ url('/') }}" class="inline-block px-5 py-2.5 rounded-xl bg-brand-orange hover:bg-brand-orange/90 text-white text-xs font-extrabold transition-all shadow-md">Visit ResQNet →</a>
                            </div>
                            <div class="pt-3 border-t border-slate-200 dark:border-slate-700/40">
                                <p class="text-xs text-slate-500 dark:text-slate-400">With gratitude,</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">The ResQNet Team</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-[#0b0f19] p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700/40">
                    <button type="button" @click="previewOpen = false"
                            class="px-4 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">Cancel</button>
                    <form method="POST" x-bind:action="previewAction" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-brand-orange hover:bg-brand-orange/90 rounded-xl transition-all shadow-md shadow-brand-orange/20 active:scale-95">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Send Email
                        </button>
                    </form>
                </div>
            </div>
        <!-- Export Modal -->
        <div x-show="exportModalOpen"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
             style="display: none;">
            <div @click.away="exportModalOpen = false"
                 class="bg-white dark:bg-[#111c2a] rounded-2xl max-w-md w-full mx-4 shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700/40">
                <form action="{{ route('admin.donations.export') }}" method="GET" @submit="exportModalOpen = false">
                    <div class="p-6 pb-4 border-b border-slate-200 dark:border-slate-700/40">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-600/10 flex items-center justify-center text-emerald-600">
                                    <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">Export Donations</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Download data as an Excel file</p>
                                </div>
                            </div>
                            <button type="button" @click="exportModalOpen = false"
                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-400 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Date Range</label>
                            <select x-model="exportRange" class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-2.5 text-sm text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                <option value="all">All Time</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>

                        <div x-show="exportRange === 'custom'" x-collapse class="space-y-4 mt-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Start Date</label>
                                <input type="date" name="start_date" x-model="exportStartDate" :required="exportRange === 'custom'"
                                       class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-2.5 text-sm text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">End Date</label>
                                <input type="date" name="end_date" x-model="exportEndDate" :required="exportRange === 'custom'"
                                       class="w-full bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700/50 rounded-xl p-2.5 text-sm text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-[#0b0f19] p-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-700/40">
                        <button type="button" @click="exportModalOpen = false"
                                class="px-4 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">Cancel</button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-all shadow-md shadow-emerald-600/20 active:scale-95">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('donationManager', () => ({
                previewOpen: false,
                previewEmail: '',
                previewName: '',
                previewDate: '',
                previewAmount: '',
                previewAction: '',

                exportModalOpen: false,
                exportRange: 'all',
                exportStartDate: '',
                exportEndDate: '',

                openPreview(btn) {
                    this.previewEmail = btn.dataset.email;
                    this.previewName = btn.dataset.name;
                    this.previewDate = btn.dataset.date;
                    this.previewAmount = btn.dataset.amount;
                    this.previewAction = '{{ url('admin/donation') }}/' + btn.dataset.id + '/appreciation';
                    this.previewOpen = true;
                },
            }));
        });
    </script>
</x-layouts.admin>
