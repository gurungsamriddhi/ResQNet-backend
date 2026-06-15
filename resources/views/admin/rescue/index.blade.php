<x-layouts.admin>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6"
         x-data="rescuesApp()"
         x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">

        <!-- Toast Notification -->
        <div x-show="toastVisible" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-4 right-4 sm:top-6 sm:right-6 z-[70] max-w-[calc(100vw-2rem)] sm:max-w-sm bg-white border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-[#5BA44C]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="check-circle" class="w-4 h-4 text-[#5BA44C]"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-bold text-admin-textPrimary">Action Completed</p>
                <p class="text-xs text-admin-textSecondary mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-admin-textSecondary"></i>
            </button>
        </div>

        <!-- Assign Team Modal -->
        <div x-show="assignModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4">
            <div @click="assignModalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden mx-auto" @click.outside="assignModalOpen = false">
                <div class="bg-brand-navy px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                            <i data-lucide="users" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base">Assign Rescue Team</h3>
                            <p class="text-white/60 text-xs" x-text="'Assigning team to ' + assignTarget?.code"></p>
                        </div>
                    </div>
                    <button @click="assignModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="space-y-3">
                        <template x-for="(team, idx) in rescueTeams" :key="idx">
                            <label :for="'team-' + idx" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="selectedTeam === team.name ? 'border-brand-orange bg-brand-orange/5' : 'border-admin-border hover:border-admin-border/80 bg-white'">
                                <input type="radio" :id="'team-' + idx" :value="team.name" x-model="selectedTeam" class="w-4 h-4 text-brand-orange accent-brand-orange flex-shrink-0">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" :style="'background-color: ' + team.color">
                                        <span x-text="team.initials"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-admin-textPrimary" x-text="team.name"></p>
                                        <p class="text-[10px] text-admin-textSecondary" x-text="team.members + ' members · ' + team.status"></p>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button @click="confirmAssign()" :disabled="!selectedTeam" class="flex-1 py-2.5 rounded-xl bg-brand-orange text-white text-xs font-bold hover:bg-[#E25338] transition-colors disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                            Assign Team
                        </button>
                        <button @click="assignModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
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

        <!-- Map -->
        <div class="bg-brand-cream border border-brand-orange/20 rounded-2xl p-4 sm:p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-brand-orange/10">
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
            <div id="rescue-map" class="w-full h-[200px] sm:h-[300px] md:h-[350px] rounded-xl overflow-hidden shadow-inner"></div>
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
                'images' => ['/placeholder/dog.jpg'],
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
                'images' => [],
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
                'images' => [],
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
                'images' => [],
            ],
        ];
        @endphp

        <!-- Main Content: Rescue Cards + Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 sm:p-6 lg:col-span-2 shadow-sm">
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

                <div class="flex flex-col gap-4 w-full">
                    @foreach($rescueCases as $rescue)
                    <div x-data="rescueCard(@js($rescue))"
                         :style="currentStatus === 'CANCELLED' ? 'order: 9999;' : ''"
                         :class="{ 'bg-gray-50/80': currentStatus === 'CANCELLED' }"
                         class="bg-admin-surface border border-admin-border rounded-2xl transition-all duration-300 hover:shadow-md hover:border-admin-primary/20 overflow-hidden">

                        <!-- Main card body -->
                        <div :class="{ 'opacity-60': currentStatus === 'RESOLVED', 'opacity-65': currentStatus === 'CANCELLED' }">
                            <div class="p-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <!-- Left: Icon + Content -->
                                <div class="flex items-start gap-3 sm:gap-4 min-w-0 flex-1">
                                    <!-- Status Icon -->
                                    <div class="flex-shrink-0 mt-0.5">
                                        <template x-if="currentStatus === 'CRITICAL'">
                                            <div class="relative">
                                                <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 animate-ping"></span>
                                                <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-100 text-red-600 border border-red-200 flex items-center justify-center shadow-sm animate-pulse">
                                                    <i data-lucide="bell-ring" class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 fill-red-200"></i>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'URGENT'">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-50 text-brand-orange border border-orange-200 flex items-center justify-center shadow-sm">
                                                <i data-lucide="alert-triangle" class="w-4 h-4 sm:w-5 sm:h-5 text-brand-orange"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'MODERATE'">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-yellow-50 text-yellow-600 border border-yellow-200 flex items-center justify-center shadow-sm">
                                                <i data-lucide="bell" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'ASSIGNED'">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shadow-sm">
                                                <i data-lucide="truck" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'RESOLVED'">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shadow-sm">
                                                <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500"></i>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus === 'CANCELLED'">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-50 text-slate-400 border border-slate-200 flex items-center justify-center shadow-sm">
                                                <i data-lucide="slash" class="w-4 h-4 sm:w-5 sm:h-5 text-slate-400"></i>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Title & meta -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-admin-textPrimary font-bold text-sm sm:text-base tracking-tight truncate">
                                                {{ $rescue['title'] }}
                                            </h4>
                                            <span :class="badgeClasses" x-text="currentStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase">{{ $rescue['status'] }}</span>
                                        </div>
                                        <p class="text-xs text-admin-textSecondary mt-1.5 leading-relaxed line-clamp-2">{{ $rescue['description'] }}</p>
                                        <div class="flex items-center gap-2 sm:gap-3.5 mt-2 text-xs text-admin-textSecondary flex-wrap">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                                <span>{{ $rescue['location'] }}</span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                                <span>{{ $rescue['time'] }}</span>
                                            </span>
                                            <span class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase">{{ $rescue['code'] }}</span>
                                        </div>
                                        <!-- Assigned team badge (visible when assigned) -->
                                        <template x-if="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED'">
                                            <div class="mt-2 flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-[10px] font-bold text-blue-700">
                                                    <i data-lucide="users" class="w-3 h-3"></i>
                                                    <span x-text="assignedTeam || 'Alpha Team'"></span>
                                                </span>
                                                <span class="text-[10px] text-admin-textSecondary">dispatched</span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Right: Action buttons -->
                                <div class="flex-shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                                    <template x-if="currentStatus === 'CRITICAL' || currentStatus === 'URGENT' || currentStatus === 'MODERATE'">
                                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                            <button @click="dispatchHelp()" class="flex-1 sm:flex-none bg-brand-orange hover:bg-[#E25338] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <i data-lucide="zap" class="w-3.5 h-3.5 shrink-0"></i>
                                                Help Now
                                            </button>
                                            <button @click="openAssign()" class="flex-1 sm:flex-none bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <i data-lucide="users" class="w-3.5 h-3.5 shrink-0"></i>
                                                Assign Team
                                            </button>
                                            <button @click="markComplete()" class="flex-1 sm:flex-none border-2 border-[#5BA44C] text-[#5BA44C] hover:bg-[#5BA44C] hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5 shrink-0"></i>
                                                Mark Complete
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="currentStatus === 'ASSIGNED'">
                                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                            <button @click="markComplete()" class="flex-1 sm:flex-none bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95 inline-flex items-center justify-center gap-1.5">
                                                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                                Mark Complete
                                            </button>
                                            <button @click="cancelCase()" class="flex-1 sm:flex-none border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 active:scale-95">
                                                Cancel
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="currentStatus === 'CANCELLED'">
                                        <span class="w-full sm:w-auto border border-slate-200 text-slate-400 font-medium px-4 py-2 rounded-lg text-xs inline-flex items-center justify-center gap-1.5 cursor-default">
                                            <i data-lucide="slash" class="w-3.5 h-3.5 shrink-0"></i>
                                            Passed / Cancelled
                                        </span>
                                    </template>

                                    <template x-if="currentStatus === 'RESOLVED'">
                                        <span class="w-full sm:w-auto bg-[#5BA44C] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm inline-flex items-center justify-center gap-1.5 cursor-default">
                                            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                            Resolved
                                        </span>
                                    </template>

                                    <button @click="expanded = !expanded" class="text-xs text-slate-400 hover:text-brand-orange transition-colors cursor-pointer flex items-center justify-center gap-1 whitespace-nowrap flex-shrink-0 py-2 sm:py-0">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span x-text="expanded ? 'Hide Info' : 'View Info'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Expandable Detail Panel -->
                        <div x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="border-t border-admin-border/60 bg-gray-50/50">
                            <div class="p-4 sm:p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                                <!-- Column 1: Case Details -->
                                <div class="bg-white border border-admin-border rounded-xl p-4 space-y-4">
                                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        Case Details
                                    </h4>
                                    <div>
                                        <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Description</p>
                                        <p class="text-sm text-admin-textPrimary mt-0.5 leading-relaxed">{{ $rescue['description'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Location</p>
                                        <p class="text-sm font-medium text-admin-textPrimary mt-0.5 inline-flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/60"></i>
                                            {{ $rescue['location'] }}
                                        </p>
                                        <a href="#" class="block mt-1 text-[10px] font-bold text-brand-orange hover:underline">
                                            <i data-lucide="navigation" class="w-3 h-3 inline"></i> Get directions
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Image Evidence</p>
                                        <div class="mt-1.5 w-full h-20 sm:h-28 rounded-lg bg-slate-100 border border-dashed border-slate-200 flex items-center justify-center text-slate-400">
                                            <div class="text-center">
                                                <i data-lucide="image" class="w-6 h-6 mx-auto mb-1"></i>
                                                <span class="text-[10px]">No image attached</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Case Code</p>
                                            <p class="text-sm font-mono font-bold text-admin-textPrimary mt-0.5">{{ $rescue['code'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase tracking-wide">Reported</p>
                                            <p class="text-sm font-medium text-admin-textPrimary mt-0.5">{{ $rescue['time'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Column 2: Reporter + Assigned Team -->
                                <div class="bg-white border border-admin-border rounded-xl p-4 space-y-4">
                                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        Reporter Information
                                    </h4>
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
                                        <a href="#" class="block mt-1 text-[10px] font-bold text-brand-orange hover:underline">
                                            <i data-lucide="message-circle" class="w-3 h-3 inline"></i> Send SMS update
                                        </a>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="border-admin-border/60">

                                    <!-- Assigned Team -->
                                    <div>
                                        <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                                            <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                                            Assigned Team
                                        </h4>
                                        <template x-if="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED'">
                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center gap-3 p-2.5 rounded-lg bg-blue-50 border border-blue-100">
                                                    <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                                        <span x-text="(assignedTeam || 'Alpha Team').split(' ').map(w => w[0]).join('').substring(0, 2)"></span>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-admin-textPrimary" x-text="assignedTeam || 'Alpha Team'"></p>
                                                        <p class="text-[10px] text-admin-textSecondary">4 members · En route</p>
                                                    </div>
                                                </div>
                                                <div class="flex -space-x-2">
                                                    <template x-for="i in 4" :key="i">
                                                        <div class="w-7 h-7 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[9px] font-bold text-slate-500" x-text="['RP','SK','AD','MT'][i-1]"></div>
                                                    </template>
                                                    <span class="text-[9px] text-admin-textSecondary ml-1 self-center">+ en route</span>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="currentStatus !== 'ASSIGNED' && currentStatus !== 'RESOLVED'">
                                            <div class="mt-3 p-3 rounded-lg bg-slate-50 border border-dashed border-slate-200 text-center">
                                                <i data-lucide="users" class="w-5 h-5 mx-auto text-slate-300 mb-1"></i>
                                                <p class="text-[10px] text-slate-400">No team assigned yet</p>
                                                <p class="text-[9px] text-slate-300 mt-0.5">Use "Assign Team" button above</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Column 3: Status Timeline -->
                                <div class="bg-white border border-admin-border rounded-xl p-4">
                                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5 mb-4">
                                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                        Status Timeline
                                    </h4>
                                    <div class="space-y-0">
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div class="w-3 h-3 rounded-full bg-secondary-500 border-2 border-white ring-2 ring-secondary-500 z-10 flex-shrink-0"></div>
                                                <div class="w-0.5 bg-secondary-200 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p class="text-xs font-bold text-admin-textPrimary">Report Submitted</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5">Case registered & queued for review</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                                <div :class="currentStatus === 'RESOLVED' || currentStatus === 'CANCELLED' ? 'bg-secondary-200' : 'bg-gray-100'" class="w-0.5 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Help Dispatched</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="currentStatus !== 'CRITICAL' && currentStatus !== 'URGENT' && currentStatus !== 'MODERATE' ? 'Assistance on the way to location' : 'Pending dispatch'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative pb-7">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'bg-blue-500 ring-blue-500' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-secondary-200' : 'bg-gray-100'" class="w-0.5 absolute top-3 bottom-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Rescue Team Assigned</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="currentStatus === 'ASSIGNED' || currentStatus === 'RESOLVED' ? 'Team dispatched to location' : 'Awaiting team assignment'"></p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 relative">
                                            <div class="flex flex-col items-center">
                                                <div :class="currentStatus === 'RESOLVED' ? 'bg-[#5BA44C] ring-[#5BA44C]' : 'bg-gray-200 ring-gray-200'" class="w-3 h-3 rounded-full border-2 border-white ring-2 z-10 flex-shrink-0"></div>
                                            </div>
                                            <div class="pt-0.5">
                                                <p :class="currentStatus === 'RESOLVED' ? 'text-admin-textPrimary' : 'text-admin-textSecondary/70'" class="text-xs font-bold">Rescue Completed</p>
                                                <p class="text-[10px] text-admin-textSecondary mt-0.5" x-text="currentStatus === 'RESOLVED' ? 'Case resolved & closed' : 'Not yet completed'"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Timestamp -->
                                    <template x-if="currentStatus === 'RESOLVED'">
                                        <div class="mt-4 pt-3 border-t border-admin-border/50 flex items-center gap-2 text-[10px] text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            <span class="font-semibold">Case resolved at <span x-text="new Date().toLocaleTimeString()"></span></span>
                                        </div>
                                    </template>
                                    <template x-if="currentStatus === 'CANCELLED'">
                                        <div class="mt-4 pt-3 border-t border-admin-border/50 flex items-center gap-2 text-[10px] text-slate-500 bg-slate-50 rounded-lg px-3 py-2">
                                            <i data-lucide="slash" class="w-3.5 h-3.5"></i>
                                            <span class="font-semibold">Case passed / cancelled</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Sidebar: Rescue Summary -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 sm:p-6 shadow-sm">
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

                <div class="mt-6 pt-5 border-t border-admin-border/60">
                    <h4 class="text-[11px] font-bold text-admin-textSecondary uppercase tracking-wider mb-3">Quick Stats</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Teams available</span>
                            <span class="font-bold text-admin-textPrimary">4 standby</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Avg response time</span>
                            <span class="font-bold text-admin-textPrimary">24.5 min</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-admin-textSecondary">Today's cases</span>
                            <span class="font-bold text-admin-textPrimary">7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .leaflet-marker-critical .pulse-ring {
            position: absolute;
            top: 50%; left: 50%;
            width: 44px; height: 44px;
            margin: -22px 0 0 -22px;
            border-radius: 50%;
            background: rgba(220, 38, 38, 0.3);
            animation: leaflet-pulse 1.5s ease-in-out infinite;
        }
        @keyframes leaflet-pulse {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>

    <script>
        function rescuesApp() {
            return {
                rescues: @json($rescueCases),
                assignModalOpen: false,
                assignTarget: null,
                selectedTeam: '',
                rescueTeams: [
                    { name: 'Alpha Team', initials: 'AT', color: '#2563EB', members: 4, status: 'Available' },
                    { name: 'Bravo Team', initials: 'BT', color: '#7C3AED', members: 3, status: 'Available' },
                    { name: 'Charlie Team', initials: 'CT', color: '#DC2626', members: 5, status: 'On another call' },
                    { name: 'Delta Team', initials: 'DT', color: '#5BA44C', members: 2, status: 'Available' },
                ],
                toastMessage: '',
                toastVisible: false,
                toastTimeout: null,
                showToast(message) {
                    this.toastMessage = message;
                    this.toastVisible = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 3000);
                },
                openAssignModal(card) {
                    this.assignTarget = card;
                    this.selectedTeam = '';
                    this.assignModalOpen = true;
                },
                confirmAssign(card) {
                    if (!this.selectedTeam) return;
                    if (this.assignTarget) {
                        this.assignTarget.assignedTeam = this.selectedTeam;
                        if (this.assignTarget.currentStatus !== 'RESOLVED' && this.assignTarget.currentStatus !== 'CANCELLED') {
                            this.assignTarget.currentStatus = 'ASSIGNED';
                        }
                    }
                    this.assignModalOpen = false;
                    this.showToast('Rescue team ' + this.selectedTeam + ' has been assigned and notified.');
                }
            }
        }

        function rescueCard(rescue) {
            return {
                originalStatus: rescue.status,
                currentStatus: rescue.status,
                expanded: false,
                assignedTeam: null,
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
                dispatchHelp() {
                    if (this.currentStatus === 'CRITICAL' || this.currentStatus === 'URGENT' || this.currentStatus === 'MODERATE') {
                        this.currentStatus = 'ASSIGNED';
                        this.assignedTeam = this.assignedTeam || 'Alpha Team';
                    }
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Help dispatched! Rescue team is en route to the location.' }
                    }));
                },
                openAssign() {
                    const app = document.querySelector('[x-data="rescuesApp()"]')?.__x.$data;
                    if (app) app.openAssignModal(this);
                },
                markComplete() {
                    if (this.currentStatus === 'CANCELLED') return;
                    this.currentStatus = 'RESOLVED';
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { message: 'Case marked as resolved. All tasks completed.' }
                    }));
                },
                cancelCase() {
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

            const map = L.map(mapElement, {
                center: [28.2096, 83.9856],
                zoom: 14,
                zoomControl: false,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);

            rescueCoordinates.forEach(coord => {
                let icon;
                let popupContent;

                if (coord.status === 'Critical') {
                    icon = L.divIcon({
                        html: '<div class="leaflet-marker-critical" style="width:28px;height:28px;position:relative"><div class="pulse-ring"></div><div style="position:absolute;inset:3px;border-radius:50%;background:#DC2626;border:3px solid #991B1B"></div></div>',
                        className: '',
                        iconSize: [28, 28],
                        iconAnchor: [14, 14],
                    });
                    popupContent = `
                        <div style="background:#0F1A2E;padding:12px 16px;border-radius:8px;min-width:200px;max-width:260px;">
                            <p style="color:#fff;font-size:14px;font-weight:700;margin:0;letter-spacing:0.3px;">&#9888;&#65039; CRITICAL CASE DETECTED</p>
                            <p style="color:#94A3B8;font-size:12px;margin:6px 0 0;font-weight:500;">${coord.title}</p>
                        </div>
                    `;
                } else if (coord.status === 'Urgent') {
                    icon = L.divIcon({
                        html: '<div style="width:20px;height:20px;border-radius:50%;background:#EA7235;border:3px solid #C2410C"></div>',
                        className: '',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10],
                    });
                    popupContent = `
                        <div style="padding:8px 12px;min-width:150px;">
                            <p style="font-size:13px;font-weight:600;margin:0;">${coord.title}</p>
                            <p style="font-size:11px;color:#EA7235;margin:4px 0 0;font-weight:700;letter-spacing:0.5px;">URGENT</p>
                        </div>
                    `;
                } else if (coord.status === 'Moderate') {
                    icon = L.divIcon({
                        html: '<div style="width:18px;height:18px;border-radius:50%;background:#FCD34D;border:3px solid #B45309"></div>',
                        className: '',
                        iconSize: [18, 18],
                        iconAnchor: [9, 9],
                    });
                    popupContent = `
                        <div style="padding:8px 12px;min-width:150px;">
                            <p style="font-size:13px;font-weight:600;margin:0;">${coord.title}</p>
                            <p style="font-size:11px;color:#B45309;margin:4px 0 0;font-weight:700;letter-spacing:0.5px;">MODERATE</p>
                        </div>
                    `;
                }

                const marker = L.marker([coord.lat, coord.lng], { icon });
                marker.bindPopup(popupContent);
                marker.addTo(map);
            });

            setTimeout(() => map.invalidateSize(), 200);
        }
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>document.addEventListener('DOMContentLoaded', initMap)</script>
</x-layouts.admin>
