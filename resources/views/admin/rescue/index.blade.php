<x-layouts.admin>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-admin-textPrimary font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                Rescue Reports
            </h1>
            <p class="text-admin-textSecondary text-sm mt-1">
                Real-time active rescue cases across the region.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-secondary-50 border border-secondary-500/20 text-xs font-semibold text-secondary-500 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-secondary-500 animate-pulse"></span>
                Live Updates
            </span>
        </div>
    </div>

    <div class="bg-brand-cream border border-brand-orange/20 rounded-2xl p-6 mt-8 shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-brand-orange/10">
            <div>
                <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight">
                    Active rescue reports
                </h3>
                <p class="text-admin-textSecondary text-xs mt-1">
                    Live geolocation of incoming rescue requests.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-xs font-bold text-red-600 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                3 Active
            </span>
        </div>
        <div id="rescue-map" class="w-full h-[350px] rounded-xl overflow-hidden shadow-inner"></div>
    </div>

    @php
    $rescueCases = [
        [
            'id' => 1,
            'title' => 'Injured stray dog',
            'description' => 'A stray dog was hit by a vehicle near the main chowk. Visible leg injury, bleeding heavily. Requires immediate veterinary attention and rescue.',
            'status' => 'CRITICAL',
            'location' => 'Nayabazar-9, Pokhara',
            'time' => '8 min ago',
            'code' => 'RQ-2418',
            'reporter_name' => 'Ram Bahadur Thapa',
            'reporter_phone' => '+977-9841234567',
        ],
        [
            'id' => 2,
            'title' => 'Kitten stuck in drain',
            'description' => 'A small kitten has been trapped in a narrow drainage pipe for over two hours. Can hear crying but cannot climb out on its own.',
            'status' => 'URGENT',
            'location' => 'Chipledhunga, Pokhara near trademall',
            'time' => '26 min ago',
            'code' => 'RQ-2417',
            'reporter_name' => 'Sita Devi Sharma',
            'reporter_phone' => '+977-9859876543',
        ],
        [
            'id' => 3,
            'title' => 'Abandoned puppies (4)',
            'description' => 'Four newborn puppies found abandoned in a cardboard box near the temple. No mother in sight. Need immediate shelter and feeding.',
            'status' => 'URGENT',
            'location' => 'Chorepatan, Devis Fall',
            'time' => '1 h ago',
            'code' => 'RQ-2415',
            'reporter_name' => 'Hari Prasad Adhikari',
            'reporter_phone' => '+977-9801237890',
        ],
        [
            'id' => 4,
            'title' => 'Cat stuck on roof',
            'description' => 'An adult cat has been stuck on a two-story residential roof since morning. Unable to climb down. Homeowner is concerned.',
            'status' => 'MODERATE',
            'location' => 'Lakeside, Pokhara',
            'time' => '3 h ago',
            'code' => 'RQ-2412',
            'reporter_name' => 'Maya Devi Gurung',
            'reporter_phone' => '+977-9865432109',
        ],
    ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 lg:col-span-2 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-admin-border/60">
                <div>
                    <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                        All rescue requests
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-0.5">
                        Prioritized by severity.
                    </p>
                </div>
                <a href="#" class="flex items-center gap-1 text-xs font-bold text-admin-primary hover:text-admin-primaryPress transition-colors">
                    <span>View all</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div x-data="rescuesApp()" @show-toast.window="showToast($event.detail.message)" class="flex flex-col gap-4 w-full">
                @foreach($rescueCases as $rescue)
                    <div x-data="rescueCard(@js($rescue))" :style="currentStatus === 'CANCELLED' ? 'order: 9999;' : ''" :class="{ 'bg-gray-50/80': currentStatus === 'CANCELLED' }" class="bg-admin-surface border border-admin-border rounded-2xl transition-all duration-300 hover:shadow-md hover:border-admin-primary/20">
                        <div :class="{ 'opacity-60': currentStatus === 'RESOLVED', 'opacity-65': currentStatus === 'CANCELLED' }" class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @if($rescue['status'] === 'CRITICAL')
                                    <div class="relative flex-shrink-0">
                                        <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 animate-ping"></span>
                                        <div class="relative w-12 h-12 rounded-full bg-red-100 text-red-600 border border-red-200 flex items-center justify-center shadow-sm animate-pulse">
                                            <i data-lucide="bell-ring" class="w-5 h-5 text-red-600 fill-red-200"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-400 border border-red-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <i data-lucide="bell" class="w-5 h-5 text-red-400"></i>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-admin-textPrimary font-bold text-sm sm:text-base tracking-tight truncate">
                                            {{ $rescue['title'] }}
                                        </h4>

                                        <span :class="badgeClasses" x-text="currentStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase">{{ $rescue['status'] }}</span>
                                    </div>

                                    <div class="flex items-center gap-3.5 mt-2 text-xs text-admin-textSecondary flex-wrap">
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                            <span>{{ $rescue['location'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                            <span>{{ $rescue['time'] }}</span>
                                        </div>
                                        <span class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase">{{ $rescue['code'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-shrink-0 flex items-center justify-end gap-4">
                                <template x-if="currentStatus === 'CRITICAL' || currentStatus === 'URGENT' || currentStatus === 'MODERATE'">
                                    <div class="flex items-center gap-2">
                                        <button @click="advanceStage" class="bg-brand-orange hover:bg-[#E25338] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                            <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                                            Help now
                                        </button>
                                        <button @click="cancel" class="border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95">
                                            Cancel
                                        </button>
                                    </div>
                                </template>

                                <template x-if="currentStatus === 'ASSIGNED'">
                                    <button @click="advanceStage" class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center gap-1.5">
                                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                        Assign Rescue Team
                                    </button>
                                </template>

                                <template x-if="currentStatus === 'CANCELLED'">
                                    <span class="border border-slate-200 text-slate-400 font-medium px-4 py-2 rounded-lg text-xs inline-flex items-center gap-1.5 cursor-default">
                                        <i data-lucide="slash" class="w-3.5 h-3.5"></i>
                                        Passed / Cancelled
                                    </span>
                                </template>

                                <template x-if="currentStatus === 'RESOLVED'">
                                    <span class="bg-[#5BA44C] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm inline-flex items-center gap-1.5 cursor-default">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        Marked as Complete
                                    </span>
                                </template>

                                <span @click="expanded = !expanded" class="text-xs text-slate-400 hover:text-brand-orange transition-colors cursor-pointer flex items-center gap-1 whitespace-nowrap">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span x-text="expanded ? 'Hide Info' : 'View Info'"></span>
                                </span>
                            </div>
                        </div>

                        <div x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border-t border-admin-border/60 bg-gray-50/50">
                            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white border border-admin-border rounded-xl p-4">
                                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        Reporter Information
                                    </h4>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Full Name</p>
                                            <p class="text-sm font-bold text-admin-textPrimary mt-0.5">{{ $rescue['reporter_name'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Contact</p>
                                            <a href="tel:{{ $rescue['reporter_phone'] }}" class="text-sm font-semibold text-brand-orange hover:text-brand-orange/80 transition-colors inline-flex items-center gap-1 mt-0.5">
                                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                                {{ $rescue['reporter_phone'] }}
                                            </a>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Location</p>
                                            <p class="text-sm font-medium text-admin-textPrimary mt-0.5 inline-flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                                {{ $rescue['location'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Submitted</p>
                                            <p class="text-sm font-medium text-admin-textPrimary mt-0.5">{{ $rescue['time'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white border border-admin-border rounded-xl p-4">
                                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        Status Timeline
                                    </h4>
                                    <div class="space-y-0">
                                        <div class="flex items-start gap-3 relative pb-6">
                                            <div class="flex flex-col items-center">
                                                <div class="w-3 h-3 rounded-full bg-secondary-500 border-2 border-white ring-2 ring-secondary-500 z-10 flex-shrink-0"></div>
                                                <div class="w-0.5 bg-secondary-200 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p class="text-xs font-bold text-admin-textPrimary">Report Submitted</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5">Case registered &amp; queued</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative pb-6">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-secondary-200' : 'bg-gray-100'" class="w-0.5 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'text-admin-textPrimary' : 'text-admin-textSecondary'" class="text-xs font-bold">Rescue Team Assigned</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5">Team dispatched to location</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-[#5BA44C] ring-[#5BA44C]' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'RESOLVED' ? 'text-admin-textPrimary' : 'text-admin-textSecondary'" class="text-xs font-bold">Rescue Completed</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5">Case resolved &amp; closed</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm">
            <div class="border-b border-admin-border/60 pb-4 mb-6">
                <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">
                    Rescue Summary
                </h3>
                <p class="text-admin-textSecondary text-xs mt-0.5">
                    Case breakdown by priority.
                </p>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-xs font-bold text-red-700">Critical</span>
                    </div>
                    <span class="text-xs font-extrabold text-red-700">1</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-orange-50 border border-orange-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-brand-orange"></span>
                        <span class="text-xs font-bold text-orange-700">Urgent</span>
                    </div>
                    <span class="text-xs font-extrabold text-orange-700">2</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-xl bg-yellow-50 border border-yellow-100">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                        <span class="text-xs font-bold text-yellow-700">Moderate</span>
                    </div>
                    <span class="text-xs font-extrabold text-yellow-700">1</span>
                </div>
            </div>
        </div>
    </div>

    <div x-show="toastVisible" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-6 right-6 z-[60] max-w-sm bg-white border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-[#5BA44C]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <i data-lucide="check-circle" class="w-4 h-4 text-[#5BA44C]"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-bold text-admin-textPrimary">Case Passed</p>
            <p class="text-xs text-admin-textSecondary mt-0.5 leading-relaxed" x-text="toastMessage"></p>
        </div>
        <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 flex items-center justify-center flex-shrink-0 transition-colors">
            <i data-lucide="x" class="w-3.5 h-3.5 text-admin-textSecondary"></i>
        </button>
    </div>

    <div x-show="isUserModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="closeReporterModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden" @click.outside="closeReporterModal">
            <div class="bg-brand-navy px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">Reporter Details</h3>
                        <p class="text-white/60 text-xs">Contact information & incident info</p>
                    </div>
                </div>
                <button @click="closeReporterModal" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="px-6 py-6 space-y-5">
                <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                    <div class="w-12 h-12 rounded-full bg-brand-orange/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user" class="w-6 h-6 text-brand-orange"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-admin-textPrimary" x-text="selectedReporter.reporter_name || 'N/A'"></p>
                        <p class="text-xs text-admin-textSecondary mt-0.5">Reported by</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-start gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-brand-orange mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] font-semibold text-admin-textSecondary uppercase tracking-wider">Contact Number</p>
                            <a :href="'tel:' + (selectedReporter.reporter_phone || '')" class="text-sm font-bold text-brand-navy hover:text-brand-orange transition-colors" x-text="selectedReporter.reporter_phone || 'N/A'"></a>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="shield-alert" class="w-4 h-4 text-brand-orange mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] font-semibold text-admin-textSecondary uppercase tracking-wider">Incident</p>
                            <p class="text-sm font-semibold text-admin-textPrimary" x-text="selectedReporter.title || 'N/A'"></p>
                            <p class="text-xs text-admin-textSecondary mt-1 leading-relaxed" x-text="selectedReporter.description || 'No additional details.'"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-brand-orange mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] font-semibold text-admin-textSecondary uppercase tracking-wider">Location</p>
                            <p class="text-sm font-medium text-admin-textPrimary" x-text="selectedReporter.location || 'N/A'"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="clock" class="w-4 h-4 text-brand-orange mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] font-semibold text-admin-textSecondary uppercase tracking-wider">Submitted</p>
                            <p class="text-sm font-medium text-admin-textPrimary" x-text="selectedReporter.time || 'N/A'"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-lucide="tag" class="w-4 h-4 text-brand-orange mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[11px] font-semibold text-admin-textSecondary uppercase tracking-wider">Case Code</p>
                            <p class="text-sm font-mono font-bold text-admin-textPrimary" x-text="selectedReporter.code || 'N/A'"></p>
                        </div>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <button @click="closeReporterModal" class="w-full py-2.5 rounded-xl bg-brand-navy text-white text-xs font-bold hover:bg-brand-navy/90 transition-colors active:scale-[0.98]">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        function rescuesApp() {
            return {
                rescues: @json($rescueCases),
                isUserModalOpen: false,
                selectedReporter: {},
                toastMessage: '',
                toastVisible: false,
                toastTimeout: null,
                openReporterModal(rescueId) {
                    this.selectedReporter = this.rescues.find(r => r.id === rescueId) || {};
                    this.isUserModalOpen = true;
                },
                closeReporterModal() {
                    this.isUserModalOpen = false;
                    this.selectedReporter = {};
                },
                showToast(message) {
                    this.toastMessage = message;
                    this.toastVisible = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 3000);
                }
            }
        }

        function rescueCard(rescue) {
            return {
                originalStatus: rescue.status,
                currentStatus: rescue.status,
                expanded: false,
                get badgeClasses() {
                    const s = this.currentStatus;
                    if (s === 'CRITICAL') return 'bg-admin-btnRed text-white';
                    if (s === 'URGENT') return 'bg-brand-orange/20 text-brand-orange border border-brand-orange/30';
                    if (s === 'MODERATE') return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
                    if (s === 'ASSIGNED') return 'bg-[#2563EB] text-white';
                    if (s === 'RESOLVED') return 'bg-[#5BA44C] text-white';
                    if (s === 'CANCELLED') return 'bg-slate-100 text-slate-500';
                    return 'bg-gray-100 text-gray-600';
                },
                advanceStage() {
                    if (this.currentStatus === 'CRITICAL' || this.currentStatus === 'URGENT' || this.currentStatus === 'MODERATE') {
                        this.currentStatus = 'ASSIGNED';
                    } else if (this.currentStatus === 'ASSIGNED') {
                        this.currentStatus = 'RESOLVED';
                    }
                },
                cancel() {
                    this.currentStatus = 'CANCELLED';
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Case passed back to the global network. Other organizations can now accept this report.' }
                    }));
                }
            }
        }

        const rescueCoordinates = [
            { id: 1, lat: 28.2096, lng: 83.9856, title: 'Injured stray dog', status: 'Critical' },
            { id: 2, lat: 28.2130, lng: 83.9870, title: 'Kitten stuck in drain', status: 'Urgent' },
            { id: 3, lat: 28.2060, lng: 83.9780, title: 'Abandoned puppies (4)', status: 'Urgent' },
            { id: 4, lat: 28.2150, lng: 83.9920, title: 'Cat stuck on roof', status: 'Moderate' },
        ];

        function initMap() {
            const mapElement = document.getElementById('rescue-map');
            if (!mapElement) return;

            const map = new google.maps.Map(mapElement, {
                center: { lat: 28.2096, lng: 83.9856 },
                zoom: 14,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                styles: [
                    { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
                    { featureType: 'transit', elementType: 'labels', stylers: [{ visibility: 'off' }] },
                ],
            });

            rescueCoordinates.forEach(coord => {
                let markerIcon;
                let infoContent;

                if (coord.status === 'Critical') {
                    markerIcon = {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 14,
                        fillColor: '#DC2626',
                        fillOpacity: 1,
                        strokeColor: '#991B1B',
                        strokeWeight: 3,
                    };
                    const marker = new google.maps.Marker({
                        position: { lat: coord.lat, lng: coord.lng },
                        map: map,
                        icon: markerIcon,
                        title: coord.title,
                        animation: google.maps.Animation.DROP,
                    });
                    let pulseState = false;
                    setInterval(() => {
                        pulseState = !pulseState;
                        marker.setIcon({
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: pulseState ? 16 : 14,
                            fillColor: pulseState ? '#EF4444' : '#DC2626',
                            fillOpacity: pulseState ? 0.7 : 1,
                            strokeColor: '#991B1B',
                            strokeWeight: pulseState ? 2 : 3,
                        });
                    }, 600);
                    infoContent = `
                        <div style="background: #0F1A2E; padding: 12px 16px; border-radius: 8px; min-width: 200px; max-width: 260px;">
                            <p style="color: #fff; font-size: 14px; font-weight: 700; margin: 0; letter-spacing: 0.3px;">⚠️ CRITICAL CASE DETECTED</p>
                            <p style="color: #94A3B8; font-size: 12px; margin: 6px 0 0; font-weight: 500;">${coord.title}</p>
                        </div>
                    `;
                    const infoWindow = new google.maps.InfoWindow({
                        content: infoContent,
                        pixelOffset: new google.maps.Size(0, -4),
                    });
                    marker.addListener('click', () => infoWindow.open(map, marker));
                } else if (coord.status === 'Urgent') {
                    markerIcon = {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#EA7235',
                        fillOpacity: 1,
                        strokeColor: '#C2410C',
                        strokeWeight: 2,
                    };
                    const marker = new google.maps.Marker({
                        position: { lat: coord.lat, lng: coord.lng },
                        map: map,
                        icon: markerIcon,
                        title: coord.title,
                    });
                    infoContent = `
                        <div style="padding: 8px 12px; min-width: 150px;">
                            <p style="font-size: 13px; font-weight: 600; margin: 0;">${coord.title}</p>
                            <p style="font-size: 11px; color: #EA7235; margin: 4px 0 0; font-weight: 700; letter-spacing: 0.5px;">URGENT</p>
                        </div>
                    `;
                    const infoWindow = new google.maps.InfoWindow({ content: infoContent });
                    marker.addListener('click', () => infoWindow.open(map, marker));
                } else if (coord.status === 'Moderate') {
                    markerIcon = {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 9,
                        fillColor: '#FCD34D',
                        fillOpacity: 1,
                        strokeColor: '#B45309',
                        strokeWeight: 2,
                    };
                    const marker = new google.maps.Marker({
                        position: { lat: coord.lat, lng: coord.lng },
                        map: map,
                        icon: markerIcon,
                        title: coord.title,
                    });
                    infoContent = `
                        <div style="padding: 8px 12px; min-width: 150px;">
                            <p style="font-size: 13px; font-weight: 600; margin: 0;">${coord.title}</p>
                            <p style="font-size: 11px; color: #B45309; margin: 4px 0 0; font-weight: 700; letter-spacing: 0.5px;">MODERATE</p>
                        </div>
                    `;
                    const infoWindow = new google.maps.InfoWindow({ content: infoContent });
                    marker.addListener('click', () => infoWindow.open(map, marker));
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_FREE_MAPS_API_KEY_PLACEHOLDER&callback=initMap" async defer></script>
</x-layouts.admin>
