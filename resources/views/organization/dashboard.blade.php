<x-layouts.admin>
    <div x-data="dashboardRealtime()" x-init="$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); })">

    <!-- Toast Notification -->
    <div x-show="toastVisible" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-5 right-5 z-[10000] max-w-sm bg-admin-surface border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
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
    <div x-show="assignModalOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4">
        <div @click="assignModalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white dark:bg-[#111c2a] rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden mx-auto" @click.outside="assignModalOpen = false">
            <div class="bg-brand-navy px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-base">Assign Rescue Team</h3>
                        <p class="text-white/60 text-xs" x-text="'Assigning team to ' + (assignTarget?.case_code || 'case')"></p>
                    </div>
                </div>
                <button @click="assignModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="px-6 py-6 space-y-4">
                <p class="text-xs text-admin-textSecondary">Select team members to dispatch:</p>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    <template x-for="(member, idx) in teamMembers" :key="member.id">
                        <label :for="'dash-member-' + idx" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all"
                                :class="selectedMembers.some(m => m.team_member_id === member.id) ? 'border-brand-orange bg-brand-orange/5' : 'border-admin-border hover:border-admin-border/80 bg-white'">
                            <input type="checkbox" :id="'dash-member-' + idx" :value="member.id"
                                   @change="toggleMember(member.id)"
                                   class="w-4 h-4 text-brand-orange accent-brand-orange flex-shrink-0 rounded">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-brand-navy flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    <span x-text="member.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-admin-textPrimary" x-text="member.name"></p>
                                    <p class="text-[10px] text-admin-textSecondary" x-text="member.email"></p>
                                </div>
                            </div>
                        </label>
                    </template>
                    <template x-if="teamMembers.length === 0">
                        <div class="text-center py-6 text-admin-textSecondary text-xs">
                            <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                            <p>No team members available. Add staff first.</p>
                        </div>
                    </template>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button @click="confirmAssign()" :disabled="selectedMembers.length === 0"
                            class="flex-1 py-2.5 rounded-xl bg-brand-orange text-white text-xs font-bold hover:bg-[#E25338] transition-colors disabled:opacity-40 disabled:cursor-not-allowed active:scale-[0.98]">
                        Assign &amp; Dispatch (<span x-text="selectedMembers.length"></span>)
                    </button>
                    <button @click="assignModalOpen = false" class="px-5 py-2.5 rounded-xl border border-admin-border text-admin-textSecondary hover:bg-admin-bg text-xs font-bold transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-admin-textPrimary font-bold text-2xl md:text-3xl tracking-tight flex items-center gap-2">
                Welcome back, {{ auth()->user()->name }} 🐾
            </h1>
            <p class="text-admin-textSecondary text-sm mt-1">
                Here's a real-time summary of your shelter's activities, rescues, and adoptions today.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('organization.pets.create') }}" class="flex items-center gap-2 bg-brand-orange hover:bg-brand-orange/90 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all duration-200">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Add New Pet</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">Shelter Capacity</span>
                    <div class="flex items-baseline gap-1.5 mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight" x-text="capacityOccupied">{{ $shelterCapacity['occupied'] }}</h3>
                        <span class="text-lg font-bold text-admin-textSecondary">/ {{ $shelterCapacity['capacity'] }}</span>
                        <span class="text-xs font-semibold text-admin-textSecondary ml-1">Occupied</span>
                    </div>
                </div>
                @php
                    $capacityPercent = $shelterCapacity['capacity'] > 0 ? round(($shelterCapacity['occupied'] / $shelterCapacity['capacity']) * 100) : 0;
                @endphp
                <div class="mt-4">
                    <div class="w-full bg-admin-bg rounded-full h-1.5 overflow-hidden border border-admin-border/50">
                        <div class="h-full rounded-full transition-all duration-500 {{ $capacityPercent > 80 ? 'bg-admin-btnRed' : ($capacityPercent > 50 ? 'bg-[#F59E0B]' : 'bg-secondary-500') }}" style="width: {{ $capacityPercent }}%"></div>
                    </div>
                    <span class="text-[10px] text-admin-textSecondary mt-1 block">{{ $capacityPercent }}% capacity utilized</span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-primary/10 text-admin-primary group-hover:bg-admin-primary group-hover:text-admin-textOnPrimary transition-all duration-300">
                <i data-lucide="building-2" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">Active Rescues</span>
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200" x-text="activeRescues">{{ $stats['active_rescues'] }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-admin-errorBg text-admin-errorText shadow-sm">
                        <i data-lucide="siren" class="w-3 h-3"></i>
                        <span>{{ $stats['pending_rescues'] }} pending</span>
                    </span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-btnRed/10 text-admin-btnRed group-hover:bg-admin-btnRed group-hover:text-white transition-all duration-300">
                <i data-lucide="shield-alert" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">Pets In Care</span>
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200" x-text="petsInCare">{{ $stats['pets_in_care'] }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="heart" class="w-3 h-3"></i>
                        <span>Sheltered</span>
                    </span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-[#F59E0B]/10 text-[#D97706] group-hover:bg-[#F59E0B] group-hover:text-white transition-all duration-300">
                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">Avg. Response Velocity</span>
                    <div class="flex items-baseline gap-1.5 mt-3 group-hover:scale-105 origin-left transition-transform duration-200">
                        <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight" x-text="avgResponse">{{ $avgResponseMinutes }}</h3>
                        <span class="text-sm font-bold text-admin-textSecondary">min</span>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="timer" class="w-3 h-3"></i>
                        <span>Reported → In Progress</span>
                    </span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-btnGreen/10 text-admin-btnGreen group-hover:bg-admin-btnGreen group-hover:text-white transition-all duration-300">
                <i data-lucide="gauge" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <span class="text-xs font-bold tracking-wider text-admin-textSecondary uppercase block">Pending Adoptions</span>
                    <h3 class="text-admin-textPrimary text-3xl font-extrabold tracking-tight mt-3 group-hover:scale-105 origin-left transition-transform duration-200" x-text="pendingAdoptions">{{ $stats['pending_adoptions'] }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-secondary-50 text-secondary-500 shadow-sm">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Awaiting review</span>
                    </span>
                </div>
            </div>
            <div class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center bg-admin-btnGreen/10 text-admin-btnGreen group-hover:bg-admin-btnGreen group-hover:text-white transition-all duration-300">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="bg-brand-cream border border-brand-orange/20 rounded-2xl p-6 lg:col-span-2 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-brand-orange/10">
                    <div>
                        <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight">Intakes vs Adoptions</h3>
                        <p class="text-admin-textSecondary text-xs mt-1">Daily animal admissions and finalized adoptions.</p>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-admin-textSecondary">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#EA7235]"></span>Intakes
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-admin-textSecondary">
                            <span class="w-2.5 h-2.5 rounded-full bg-secondary-500"></span>Adoptions
                        </span>

                        <div class="relative" id="orgDateDropdown">
                            <button id="orgDateDropdownToggle"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-admin-border bg-admin-surface text-xs font-semibold text-admin-textPrimary hover:border-brand-orange/40 transition-colors focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                                <svg class="w-3.5 h-3.5 text-admin-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span id="orgDateRangeLabel">Last 14 days</span>
                                <svg class="w-3.5 h-3.5 text-admin-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>
                            <div id="orgDateDropdownMenu" class="hidden absolute right-0 mt-1 w-52 bg-admin-surface border border-admin-border rounded-lg shadow-lg z-50 py-1">
                                <button type="button" data-range="1" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Last 24 hours</button>
                                <button type="button" data-range="7" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Last 7 days</button>
                                <button type="button" data-range="14" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Last 14 days</button>
                                <button type="button" data-range="28" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Last 28 days</button>
                                <button type="button" data-range="90" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Last 3 months</button>
                                <hr class="my-1 border-admin-border">
                                <button type="button" data-range="custom" class="org-range-option w-full px-4 py-2 text-left text-xs font-medium text-admin-textPrimary hover:bg-brand-orange/5 transition-colors">Custom Range...</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="orgCustomRangeContainer" class="flex items-center gap-2 mt-4" style="display:none">
                    <input type="date" id="orgCustomStartDate"
                           class="text-xs font-semibold bg-admin-surface border border-admin-border rounded-lg px-3 py-2 text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                    <span class="text-xs text-admin-textSecondary font-medium">to</span>
                    <input type="date" id="orgCustomEndDate"
                           class="text-xs font-semibold bg-admin-surface border border-admin-border rounded-lg px-3 py-2 text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-brand-orange/30">
                </div>

                <div class="flex gap-4 mt-6">
                    <div class="flex flex-col justify-between text-[10px] font-semibold text-admin-textSecondary/60 h-48 py-1 select-none text-right w-16" id="chartYLabels">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span>0</span>
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div class="relative h-48 w-full">
                            <canvas id="intakeAdoptionChart"></canvas>
                        </div>
                        <div class="flex justify-between text-[10px] font-semibold text-admin-textSecondary/80 pt-3 border-t border-brand-orange/10 mt-3 select-none overflow-hidden" id="chartXLabels">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm flex flex-col">
            <div class="border-b border-admin-border/60 pb-4 mb-5">
                <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">Quick Actions</h3>
                <p class="text-admin-textSecondary text-xs mt-0.5">Common tasks at a glance.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 flex-1">
                <a href="{{ route('organization.pets.create') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-brand-orange/40 hover:bg-brand-orange/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-brand-orange/10 flex items-center justify-center group-hover:bg-brand-orange group-hover:text-white transition-all duration-200">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-brand-orange group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">Add New Pet</span>
                </a>
                <a href="{{ route('organization.rescues.index') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-admin-btnRed/40 hover:bg-admin-btnRed/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-admin-btnRed/10 flex items-center justify-center group-hover:bg-admin-btnRed group-hover:text-white transition-all duration-200">
                        <i data-lucide="siren" class="w-5 h-5 text-admin-btnRed group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">Rescue Cases</span>
                </a>
                <a href="{{ route('organization.pets.index') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-secondary-500/40 hover:bg-secondary-500/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-secondary-500/10 flex items-center justify-center group-hover:bg-secondary-500 group-hover:text-white transition-all duration-200">
                        <i data-lucide="paw-print" class="w-5 h-5 text-secondary-500 group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">All Pets</span>
                </a>
                <a href="{{ route('organization.adoptions.index') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-admin-primary/40 hover:bg-admin-primary/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-admin-primary/10 flex items-center justify-center group-hover:bg-admin-primary group-hover:text-white transition-all duration-200">
                        <i data-lucide="heart-handshake" class="w-5 h-5 text-admin-primary group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">Adoptions</span>
                </a>
                <a href="{{ route('organization.orgteams.index') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-admin-navActive/40 hover:bg-admin-navActive/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-admin-navActive/10 flex items-center justify-center group-hover:bg-admin-navActive group-hover:text-white transition-all duration-200">
                        <i data-lucide="users" class="w-5 h-5 text-admin-navActive group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">Team</span>
                </a>
                <a href="{{ route('organization.settings.index') }}" class="flex flex-col items-center gap-2.5 p-4 rounded-xl border border-admin-border hover:border-admin-textSecondary/40 hover:bg-admin-textSecondary/5 transition-all duration-200 group">
                    <div class="w-10 h-10 rounded-full bg-admin-textSecondary/10 flex items-center justify-center group-hover:bg-admin-textSecondary group-hover:text-white transition-all duration-200">
                        <i data-lucide="settings" class="w-5 h-5 text-admin-textSecondary group-hover:text-white"></i>
                    </div>
                    <span class="text-[11px] font-bold text-admin-textPrimary text-center leading-tight">Settings</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 lg:col-span-2 shadow-sm flex flex-col">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-admin-border/60">
                <div>
                    <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">High-priority rescues</h3>
                    <p class="text-admin-textSecondary text-xs mt-0.5">Help these animals now.</p>
                </div>
                <a href="{{ route('organization.rescues.index') }}" class="flex items-center gap-1 text-xs font-bold text-admin-primary hover:text-admin-primaryPress transition-colors">
                    <span>View all</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="space-y-4 flex-1">
                @forelse($priorityRescues as $rescue)
                    @php
                        $urgency = $rescue->urgency ?? 'medium';
                        $isOwnOrg = $rescue->organization_id === $orgId;
                        $timeDiff = \Carbon\Carbon::parse($rescue->created_at)->diffForHumans();
                        $statusColors = [
                            'reported' => 'bg-red-50 text-red-600 border-red-100',
                            'assigned' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'in_progress' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        ];
                        $statusColor = $statusColors[$rescue->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                    @endphp
                    <div class="bg-admin-surface border border-admin-border rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-200 hover:shadow-md hover:border-admin-primary/20">
                        <div class="flex items-start gap-3 sm:gap-4 min-w-0 flex-1">
                            @if($urgency === 'critical')
                                <div class="flex-shrink-0 mt-0.5 relative">
                                    <span class="absolute inset-0 rounded-full bg-red-500 opacity-25 @if($rescue->status === 'reported') animate-ping @endif"></span>
                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-red-100 text-red-600 border border-red-200 flex items-center justify-center shadow-sm @if($rescue->status === 'reported') animate-pulse @endif">
                                        <i data-lucide="bell-ring" class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 fill-red-200"></i>
                                    </div>
                                </div>
                            @elseif($urgency === 'high')
                                <div class="flex-shrink-0 mt-0.5 relative">
                                    <span class="absolute inset-0 rounded-full bg-orange-500 opacity-25 @if($rescue->status === 'reported') animate-ping @endif"></span>
                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 text-brand-orange border border-orange-200 flex items-center justify-center shadow-sm @if($rescue->status === 'reported') animate-pulse @endif">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 sm:w-5 sm:h-5 text-brand-orange"></i>
                                    </div>
                                </div>
                            @else
                                <div class="flex-shrink-0 mt-0.5 relative">
                                    <span class="absolute inset-0 rounded-full bg-yellow-500 opacity-25 @if($rescue->status === 'reported') animate-ping @endif"></span>
                                    <div class="relative w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-yellow-100 text-yellow-600 border border-yellow-200 flex items-center justify-center shadow-sm @if($rescue->status === 'reported') animate-pulse @endif">
                                        <i data-lucide="bell" class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-500"></i>
                                    </div>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-admin-textPrimary font-bold text-sm sm:text-base tracking-tight truncate">{{ $rescue->title }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase border {{ $statusColor }}">{{ str_replace('_', ' ', $rescue->status) }}</span>
                                </div>
                                <p class="text-xs text-admin-textSecondary mt-1 leading-relaxed line-clamp-2">{{ $rescue->description }}</p>
                                <div class="flex items-center gap-2 sm:gap-3.5 mt-2 text-xs text-admin-textSecondary flex-wrap">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                        <span>{{ $rescue->address ?: 'Location not specified' }}</span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-admin-textSecondary/50"></i>
                                        <span>{{ $timeDiff }}</span>
                                    </span>
                                    <span class="text-[10px] font-semibold text-admin-textSecondary/70 uppercase">{{ $rescue->case_code }}</span>
                                </div>
                                @if($rescue->assignments->count() > 0)
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-[10px] font-bold text-blue-700">
                                            <i data-lucide="users" class="w-3 h-3"></i>
                                            <span>{{ $rescue->assignments->pluck('teamMember.name')->filter()->implode(', ') ?: 'Team assigned' }}</span>
                                        </span>
                                        <span class="text-[10px] text-admin-textSecondary">dispatched</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            @if($rescue->status === 'reported' && !$isOwnOrg)
                                <a href="{{ route('organization.rescues.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-brand-orange hover:bg-[#E25338] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                    Help Now
                                </a>
                            @elseif($rescue->status === 'assigned' && $isOwnOrg)
                                <button @click="openAssignModal({{ json_encode(['id' => $rescue->id, 'case_code' => $rescue->case_code, 'title' => $rescue->title]) }})" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                    Assign Team
                                </button>
                            @elseif($rescue->status === 'in_progress' && $isOwnOrg)
                                <a href="{{ route('organization.rescues.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-[#5BA44C] hover:bg-[#4A8A3E] text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all duration-200 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    View Details
                                </a>
                            @else
                                <a href="{{ route('organization.rescues.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-admin-bg text-admin-textSecondary border border-admin-border text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200">
                                    View Details
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-admin-surface border border-dashed border-admin-border rounded-2xl p-12 text-center">
                        <i data-lucide="shield-alert" class="w-12 h-12 mx-auto text-slate-300 mb-3"></i>
                        <h3 class="text-base font-bold text-admin-textPrimary">No active rescues</h3>
                        <p class="text-xs text-admin-textSecondary mt-1">No rescue reports at the moment. Check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="border-b border-admin-border/60 pb-4 mb-5">
                    <h3 class="text-admin-textPrimary font-bold text-lg tracking-tight flex items-center gap-2">
                        <i data-lucide="stethoscope" class="w-5 h-5 text-admin-primary"></i>
                        <span>Medical Wing Diagnostics</span>
                    </h3>
                    <p class="text-admin-textSecondary text-xs mt-1">Treatment distribution across sheltered animals.</p>
                </div>

                <div class="space-y-5">
                    @foreach($medicalWing as $item)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <div class="flex items-center gap-2 text-admin-textPrimary font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-{{ $item['color'] }}"></span>
                                    <span>{{ $item['label'] }}</span>
                                </div>
                                <span class="font-bold text-admin-textPrimary">{{ $item['percent'] }}%</span>
                            </div>
                            <div class="w-full bg-admin-bg rounded-full h-2 overflow-hidden border border-admin-border/50">
                                <div class="bg-{{ $item['color'] }} h-full rounded-full transition-all duration-700" style="width: {{ $item['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-admin-bg border border-admin-border/40 text-[11px] text-admin-textSecondary flex items-start gap-2.5">
                <i data-lucide="activity" class="w-4 h-4 text-admin-primary flex-shrink-0 mt-0.5"></i>
                <p>Distribution computed from active medical records, vaccination logs, and checkup entries for your shelter.</p>
            </div>
        </div>
    </div>

    <!-- Real-time Toast Notifications -->
    <template x-for="(notification, index) in notifications" :key="notification.id">
        <div x-show="index < 3"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="fixed top-4 right-4 z-[100] w-80 bg-white border border-admin-border rounded-xl shadow-2xl p-4"
             style="margin-top: calc(0.5rem + index * 5.5rem);">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center"
                     :class="notification.type === 'adoption' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                    <i :data-lucide="notification.type === 'adoption' ? 'paw-print' : 'shield-alert'" class="w-5 h-5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-admin-textPrimary" x-text="notification.title"></p>
                    <p class="text-xs text-admin-textSecondary mt-0.5" x-text="notification.message"></p>
                    <p class="text-[10px] text-admin-textSecondary/60 mt-1 font-semibold" x-text="notification.time"></p>
                </div>
                <button @click="removeNotification(notification.id)" class="flex-shrink-0 text-admin-textSecondary/40 hover:text-admin-textSecondary transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </template>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardRealtime', () => ({
                notifications: [],
                activePets: {{ $stats['active_pets'] }},
                capacityOccupied: {{ $shelterCapacity['occupied'] }},
                activeRescues: {{ $stats['active_rescues'] }},
                pendingAdoptions: {{ $stats['pending_adoptions'] }},
                petsInCare: {{ $stats['pets_in_care'] }},
                avgResponse: {{ $avgResponseMinutes }},

                assignModalOpen: false,
                assignTarget: null,
                selectedMembers: [],
                teamMembers: @json($teamMembers),
                toastVisible: false,
                toastMessage: '',

                showToast(msg) {
                    this.toastMessage = msg;
                    this.toastVisible = true;
                    setTimeout(() => { this.toastVisible = false; }, 4000);
                    this.$nextTick(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); });
                },

                openAssignModal(card) {
                    this.assignTarget = card;
                    this.selectedMembers = [];
                    this.assignModalOpen = true;
                },

                toggleMember(memberId) {
                    const idx = this.selectedMembers.findIndex(m => m.team_member_id === memberId);
                    if (idx > -1) {
                        this.selectedMembers.splice(idx, 1);
                    } else {
                        this.selectedMembers.push({ team_member_id: memberId, role: 'rescuer' });
                    }
                },

                async confirmAssign() {
                    if (this.selectedMembers.length === 0 || !this.assignTarget) return;
                    const targetId = this.assignTarget.id;
                    try {
                        const response = await fetch('{{ route("organization.rescues.assign-team", ["report" => "__ID__"], false) }}'.replace('__ID__', targetId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ team_members: this.selectedMembers }),
                        });
                        const data = await response.json().catch(() => ({}));
                        if (response.ok) {
                            this.assignModalOpen = false;
                            this.assignTarget = null;
                            this.showToast(data.message || 'Team assigned successfully.');
                        } else {
                            this.showToast('Error: ' + (data.message || 'Failed to assign team'));
                        }
                    } catch (e) {
                        this.showToast('Network error. Please try again.');
                    }
                },

                init() {
                    const adoptionHandler = (e) => {
                        this.pendingAdoptions++;
                        this.activePets++;
                        this.capacityOccupied++;
                        this.addNotification('adoption', 'New Adoption Request', `${e.detail.user_name} applied to adopt ${e.detail.pet_name}`);
                    };

                    const rescueHandler = (e) => {
                        this.activeRescues++;
                        this.addNotification('rescue', 'New Rescue Report', `${e.detail.title} (${e.detail.urgency})`);
                    };

                    const petHandler = (e) => {
                        this.capacityOccupied++;
                        this.activePets++;
                    };

                    window.addEventListener('new-adoption-request', adoptionHandler);
                    window.addEventListener('new-rescue-report', rescueHandler);
                    window.addEventListener('new-pet-admitted', petHandler);

                    this.$nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                        initIntakeAdoptionChart();
                    });
                },

                addNotification(type, title, message) {
                    this.notifications.unshift({
                        id: Date.now(),
                        type,
                        title,
                        message,
                        time: 'Just now',
                    });
                    if (this.notifications.length > 5) this.notifications.pop();
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },
            }));
        });

        var orgIntakeChart = null;
        var orgActiveRange = 14;
        var orgChartLabels = @json($chartData['labels']);

        function initIntakeAdoptionChart() {
            const labels = @json($chartData['labels']);
            const intakes = @json($chartData['intakes']);
            const adoptions = @json($chartData['adoptions']);

            const shortLabels = labels.map(l => l.split(' ')[1]);

            document.getElementById('chartXLabels').innerHTML = labels.map((l, i) => {
                const step = Math.ceil(labels.length / 7);
                if (i % step === 0 || i === labels.length - 1) {
                    return '<span class="flex-1 text-center">' + l.split(' ')[1] + '</span>';
                }
                return '';
            }).join('');

            const maxVal = Math.max(...intakes, ...adoptions, 1);
            const ySteps = 4;
            document.getElementById('chartYLabels').innerHTML = Array.from({length: ySteps + 1}, (_, i) => {
                const val = Math.round(maxVal * (1 - i / ySteps));
                return '<span>' + val + '</span>';
            }).join('');

            const ctx = document.getElementById('intakeAdoptionChart');
            if (!ctx) return;

            orgIntakeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: shortLabels,
                    datasets: [
                        {
                            label: 'Intakes',
                            data: intakes,
                            borderColor: '#EA7235',
                            backgroundColor: 'rgba(234, 114, 53, 0.08)',
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#EA7235',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1.5,
                            pointHoverRadius: 5,
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Adoptions',
                            data: adoptions,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.08)',
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1.5,
                            pointHoverRadius: 5,
                            tension: 0.35,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            cornerRadius: 10,
                            padding: 12,
                            titleFont: { weight: 'bold', size: 12 },
                            bodyFont: { size: 12 },
                            displayColors: true,
                            boxWidth: 8,
                            boxHeight: 8,
                            boxPadding: 4,
                            callbacks: {
                                title: function(items) {
                                    const idx = items[0].dataIndex;
                                    return labels[idx];
                                },
                                label: function(context) {
                                    const val = context.parsed.y;
                                    return context.dataset.label + ': ' + val;
                                },
                                afterBody: function(items) {
                                    const idx = items[0].dataIndex;
                                    return 'Adoptions: ' + adoptions[idx] + ' | Intakes: ' + intakes[idx];
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            display: false,
                            grid: { display: false },
                        },
                        y: {
                            display: false,
                            beginAtZero: true,
                            suggestedMax: maxVal + 1,
                            grid: {
                                color: 'rgba(0,0,0,0.04)',
                                drawBorder: false,
                            },
                        },
                    },
                },
            });
        }

        function updateOrgChart() {
            var url;
            if (orgActiveRange !== null) {
                url = '{{ route("organization.dashboard.chart-stats") }}?range=' + orgActiveRange;
            } else {
                var sd = document.getElementById('orgCustomStartDate');
                var ed = document.getElementById('orgCustomEndDate');
                if (sd && ed && sd.value && ed.value) {
                    url = '{{ route("organization.dashboard.chart-stats") }}?start_date=' + sd.value + '&end_date=' + ed.value;
                } else {
                    return;
                }
            }

            fetch(url)
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!orgIntakeChart) return;
                    orgChartLabels = data.labels;
                    orgIntakeChart.data.labels = data.labels.map(function (l) { return l.split(' ')[1]; });
                    orgIntakeChart.data.datasets[0].data = data.intakes;
                    orgIntakeChart.data.datasets[1].data = data.adoptions;
                    orgIntakeChart.update();

                    var maxVal = Math.max(...data.intakes, ...data.adoptions, 1);
                    document.getElementById('chartYLabels').innerHTML = Array.from({length: 5}, function (_, i) {
                        return '<span>' + Math.round(maxVal * (1 - i / 4)) + '</span>';
                    }).join('');

                    document.getElementById('chartXLabels').innerHTML = data.labels.map(function (l, i) {
                        var step = Math.ceil(data.labels.length / 7);
                        if (i % step === 0 || i === data.labels.length - 1) {
                            return '<span class="flex-1 text-center">' + l.split(' ')[1] + '</span>';
                        }
                        return '';
                    }).join('');
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.getElementById('orgDateDropdownToggle');
            var dropdownMenu = document.getElementById('orgDateDropdownMenu');
            var dateDropdown = document.getElementById('orgDateDropdown');

            if (toggleBtn && dropdownMenu) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function (e) {
                    if (dateDropdown && !dateDropdown.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }

            document.querySelectorAll('.org-range-option').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var range = this.getAttribute('data-range');
                    var label = document.getElementById('orgDateRangeLabel');
                    var customContainer = document.getElementById('orgCustomRangeContainer');

                    if (range === 'custom') {
                        orgActiveRange = null;
                        if (customContainer) customContainer.style.display = 'flex';
                        if (label) label.textContent = 'Custom Range';
                    } else {
                        orgActiveRange = parseInt(range);
                        if (customContainer) customContainer.style.display = 'none';
                        var names = {1: 'Last 24 hours', 7: 'Last 7 days', 14: 'Last 14 days', 28: 'Last 28 days', 90: 'Last 3 months'};
                        if (label) label.textContent = names[orgActiveRange] || 'Last ' + orgActiveRange + ' days';
                    }

                    if (dropdownMenu) dropdownMenu.classList.add('hidden');
                    updateOrgChart();
                });
            });

            var cs = document.getElementById('orgCustomStartDate');
            var ce = document.getElementById('orgCustomEndDate');
            if (cs) cs.addEventListener('change', function () {
                if (orgActiveRange === null) updateOrgChart();
            });
            if (ce) ce.addEventListener('change', function () {
                if (orgActiveRange === null) updateOrgChart();
            });

            var today = new Date().toISOString().split('T')[0];
            if (cs) cs.setAttribute('max', today);
            if (ce) ce.setAttribute('max', today);
        });

        if (window.subscribeOrganization && {{ $orgId ?? 'null' }}) {
            window.subscribeOrganization({{ $orgId }});
        }
    </script>
    @endpush
</x-layouts.admin>
