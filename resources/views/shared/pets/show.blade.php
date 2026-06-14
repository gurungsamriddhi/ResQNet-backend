<x-layouts.admin>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ $canEdit ? route('organization.pets.index') : route('admin.pets.index') }}" class="text-admin-textSecondary hover:text-admin-primary transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h2 class="font-extrabold text-2xl text-admin-textPrimary tracking-tight">
                        {{ __('Pet Profile: ') }} {{ $pet->name }}
                    </h2>
                </div>
            </div>
            <div class="flex gap-3">
                <span class="px-3 py-1 bg-brand-orange/10 text-brand-orange text-sm font-semibold rounded-full capitalize border border-brand-orange/20">
                    {{ $pet->status }}
                </span>
                <span class="px-3 py-1 bg-admin-primary/10 text-admin-primary text-sm font-semibold rounded-full capitalize border border-admin-primary/20">
                    {{ $pet->species }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 md:px-8 py-8" x-data="{ activeTab: 'overview' }" x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">
        
        <!-- Main Card Container -->
        <div class="bg-admin-surface border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
            
            <!-- Profile Header -->
            <div class="flex flex-col md:flex-row gap-6 p-8 border-b border-admin-border bg-admin-bg/30">
                <!-- Image -->
                <div class="w-full md:w-1/3">
                    @if($pet->primaryImage)
                        <img src="{{ Storage::url($pet->primaryImage->image_path) }}" alt="{{ $pet->name }}" class="rounded-xl object-cover w-full h-64 shadow-md border border-admin-border/50">
                    @else
                        <div class="w-full h-64 rounded-xl bg-admin-bg border border-admin-border/50 flex flex-col items-center justify-center text-admin-textSecondary/50">
                            <i data-lucide="camera-off" class="w-12 h-12 mb-3"></i>
                            <span class="font-medium text-sm">No Photo Available</span>
                        </div>
                    @endif
                </div>
                <!-- Core Info -->
                <div class="w-full md:w-2/3 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h1 class="text-3xl font-extrabold text-admin-textPrimary mb-2">{{ $pet->name }} <span class="text-admin-textSecondary/60 font-medium text-2xl">- {{ $pet->breed ?? 'Unknown Breed' }}</span></h1>
                            <span class="text-xs font-mono font-semibold text-admin-textSecondary/60 bg-admin-surface px-2.5 py-1 rounded-lg border border-admin-border/50">
                                <i data-lucide="qr-code" class="w-3 h-3 inline mr-1"></i> {{ $pet->qr_code_id }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-admin-textSecondary mt-4 bg-admin-surface p-4 rounded-xl border border-admin-border/50">
                            <div><strong class="text-admin-textPrimary">Age:</strong> {{ $pet->age_months ? floor($pet->age_months / 12) . ' Years ' . ($pet->age_months % 12) . ' Months' : 'Unknown' }}</div>
                            <div><strong class="text-admin-textPrimary">Size:</strong> <span class="capitalize">{{ $pet->size ?? 'Unknown' }}</span></div>
                            <div><strong class="text-admin-textPrimary">Gender:</strong> <span class="capitalize">{{ $pet->gender ?? 'Unknown' }}</span></div>
                            <div><strong class="text-admin-textPrimary">Color:</strong> {{ $pet->color ?? 'Unknown' }}</div>
                        </div>
                    </div>
                    
                    @if($canEdit)
                    <div class="mt-6 flex gap-4">
                        <button class="bg-admin-primary hover:bg-admin-primaryPress text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                            <i data-lucide="pencil" class="w-4 h-4"></i> Edit Profile
                        </button>
                        <button class="bg-admin-surface border border-admin-border hover:bg-admin-bg text-admin-textPrimary font-semibold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                            <i data-lucide="share-2" class="w-4 h-4 text-admin-textSecondary"></i> Share
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-admin-border px-8 bg-admin-surface">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'overview'" 
                            :class="{ 'border-admin-primary text-admin-primary': activeTab === 'overview', 'border-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:border-admin-border': activeTab !== 'overview' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i> Overview
                    </button>

                    <button @click="activeTab = 'health'" 
                            :class="{ 'border-admin-primary text-admin-primary': activeTab === 'health', 'border-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:border-admin-border': activeTab !== 'health' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="heart-pulse" class="w-4 h-4"></i> Health Records
                    </button>

                    <button @click="activeTab = 'adoptions'" 
                            :class="{ 'border-admin-primary text-admin-primary': activeTab === 'adoptions', 'border-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:border-admin-border': activeTab !== 'adoptions' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="home" class="w-4 h-4"></i> Adoptions
                        <span class="bg-admin-primary/10 text-admin-primary py-0.5 px-2 rounded-full text-xs ml-1">{{ $pet->adoptionRequests ? $pet->adoptionRequests->count() : 0 }}</span>
                    </button>

                    <button @click="activeTab = 'rescue'" 
                            :class="{ 'border-admin-primary text-admin-primary': activeTab === 'rescue', 'border-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:border-admin-border': activeTab !== 'rescue' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i> Rescue Details
                    </button>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div class="p-8 bg-admin-bg/10 min-h-[400px]">
                
                <!-- 1. Overview Tab -->
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Quick Stats -->
                    <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                        <div class="bg-admin-surface p-4 rounded-xl border border-admin-border shadow-sm flex items-center gap-4">
                            <div class="bg-blue-500/10 p-3 rounded-lg text-blue-500">
                                <i data-lucide="calendar-plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-admin-textSecondary/70">Intake Date</div>
                                <div class="font-extrabold text-admin-textPrimary">{{ $pet->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="bg-admin-surface p-4 rounded-xl border border-admin-border shadow-sm flex items-center gap-4">
                            <div class="bg-brand-orange/10 p-3 rounded-lg text-brand-orange">
                                <i data-lucide="clock" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-admin-textSecondary/70">Days in Care</div>
                                <div class="font-extrabold text-admin-textPrimary">{{ $pet->created_at->diffInDays(now()) }} Days</div>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Info -->
                    <div class="bg-admin-surface p-5 rounded-xl border border-admin-border shadow-sm flex flex-col items-center text-center">
                         <div class="w-12 h-12 rounded-full bg-admin-primary/10 text-admin-primary flex items-center justify-center mb-3">
                             <i data-lucide="building-2" class="w-6 h-6"></i>
                         </div>
                         <h3 class="text-sm font-bold text-admin-textPrimary">{{ $pet->organization->name ?? 'System' }}</h3>
                         <p class="text-xs text-admin-textSecondary mt-1">Managing Organization</p>
                    </div>

                    <!-- Bio Box -->
                    <div class="lg:col-span-3 bg-admin-surface p-6 rounded-xl border border-admin-border shadow-sm">
                        <h3 class="text-lg font-extrabold text-admin-textPrimary mb-3 flex items-center gap-2">
                            <i data-lucide="align-left" class="w-5 h-5 text-admin-primary"></i> About {{ $pet->name }}
                        </h3>
                        <p class="text-admin-textSecondary leading-relaxed whitespace-pre-line">
                            {{ $pet->description ?: 'No description provided for this pet yet.' }}
                        </p>
                    </div>

                    @if($pet->images && $pet->images->count() > 0)
                    <!-- Photo Gallery -->
                    <div class="lg:col-span-3 bg-admin-surface p-6 rounded-xl border border-admin-border shadow-sm">
                        <h3 class="text-lg font-extrabold text-admin-textPrimary mb-4 flex items-center gap-2">
                            <i data-lucide="image" class="w-5 h-5 text-admin-primary"></i> Additional Photos
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($pet->images as $image)
                                <a href="{{ Storage::url($image->image_path) }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-admin-border hover:ring-2 hover:ring-admin-primary/50 transition-all">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $pet->name }} photo" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- 2. Health Records Tab -->
                <div x-cloak x-show="activeTab === 'health'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-extrabold text-admin-textPrimary">Medical History</h3>
                        @if($canEdit)
                        <button class="bg-admin-primary/10 text-admin-primary hover:bg-admin-primary/20 font-bold py-2 px-4 rounded-xl transition-colors text-sm border border-admin-primary/20 flex items-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Record
                        </button>
                        @endif
                    </div>
                    
                    <div class="bg-admin-surface rounded-xl border border-admin-border shadow-sm overflow-hidden">
                        <div class="p-8 text-center">
                            <i data-lucide="activity" class="w-12 h-12 text-admin-textSecondary/30 mx-auto mb-3"></i>
                            <h4 class="text-admin-textPrimary font-bold">No Medical Records</h4>
                            <p class="text-admin-textSecondary text-sm mt-1">Health records module integration pending.</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Adoptions Tab -->
                <div x-cloak x-show="activeTab === 'adoptions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-extrabold text-admin-textPrimary">Adoption Applications</h3>
                    </div>
                    
                    <div class="bg-admin-surface rounded-xl border border-admin-border shadow-sm overflow-hidden">
                        <div class="p-8 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-admin-textSecondary/30 mx-auto mb-3"></i>
                            <h4 class="text-admin-textPrimary font-bold">No Applications</h4>
                            <p class="text-admin-textSecondary text-sm mt-1">No one has applied to adopt {{ $pet->name }} yet.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Rescue Details Tab -->
                <div x-cloak x-show="activeTab === 'rescue'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-admin-surface p-6 rounded-xl border border-admin-border shadow-sm">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-admin-primary/10 flex items-center justify-center text-admin-primary">
                                <i data-lucide="shield-alert" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-admin-textPrimary">Rescue Context</h3>
                                <p class="text-xs text-admin-textSecondary">Initial intake details</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1">Intake Condition</div>
                                    <div class="font-medium text-admin-textPrimary">{{ $pet->health_status ?: 'No initial condition noted.' }}</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1">Link to Rescue Report</div>
                                    <div class="font-medium text-admin-textSecondary italic">No active rescue report linked.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.admin>
