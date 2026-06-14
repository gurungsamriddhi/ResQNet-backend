<x-layouts.admin>
    @php
        $qrScanUrl = rtrim(config('app.url'), '/') . route('public.pets.qr.show', $pet->qr_code_id, false);
        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(180)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($qrScanUrl);
    @endphp

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
        
        @if($pet->isSuspended())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-700 dark:text-red-400 p-4 rounded-xl flex items-start gap-3">
                <i data-lucide="alert-triangle" class="w-6 h-6 shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="font-bold text-lg">This Pet Profile is Suspended</h3>
                    <p class="text-sm mt-1 opacity-90"><span class="font-semibold">Reason:</span> {{ $pet->suspension_reason }}</p>
                </div>
            </div>
        @endif

        <style>
            .pet-qr-svg svg {
                width: 100%;
                height: auto;
                display: block;
            }

            .qr-print-label {
                display: none;
            }

            @media print {
                body * {
                    visibility: hidden !important;
                }

                .qr-print-label,
                .qr-print-label * {
                    visibility: visible !important;
                }

                .qr-print-label {
                    display: flex !important;
                    position: fixed;
                    inset: 0;
                    align-items: center;
                    justify-content: center;
                    background: #ffffff;
                    color: #111827;
                    font-family: Arial, sans-serif;
                }

                .qr-print-card {
                    width: 78mm;
                    min-height: 96mm;
                    border: 1px solid #111827;
                    border-radius: 8px;
                    padding: 10mm;
                    text-align: center;
                    box-sizing: border-box;
                }

                .qr-print-card svg {
                    width: 52mm;
                    height: 52mm;
                    margin: 5mm auto;
                }
            }
        </style>

        <div class="qr-print-label" aria-hidden="true">
            <div class="qr-print-card">
                <div style="font-size: 18px; font-weight: 800;">ResQNet Pet ID</div>
                <div style="font-size: 24px; font-weight: 900; margin-top: 4mm;">{{ $pet->name }}</div>
                <div>{!! $qrCodeSvg !!}</div>
                <div style="font-size: 16px; font-weight: 800;">{{ $pet->qr_code_id }}</div>
                <div style="font-size: 10px; margin-top: 4mm; overflow-wrap: anywhere;">{{ $qrScanUrl }}</div>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-admin-surface border border-admin-border shadow-sm sm:rounded-2xl overflow-hidden">
            
            <!-- Profile Header -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-8 border-b border-admin-border bg-admin-bg/30">
                <!-- Image -->
                <div class="lg:col-span-4">
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
                <div class="lg:col-span-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h1 class="text-3xl font-extrabold text-admin-textPrimary mb-2">{{ $pet->name }} <span class="text-admin-textSecondary/60 font-medium text-2xl">- {{ $pet->breed ?? 'Unknown Breed' }}</span></h1>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-mono font-semibold text-admin-textSecondary/60 bg-admin-surface px-2.5 py-1 rounded-lg border border-admin-border/50">
                                    <i data-lucide="qr-code" class="w-3 h-3 inline mr-1"></i> {{ $pet->qr_code_id }}
                                </span>
                                <button onclick="window.print()" class="bg-admin-surface border border-admin-border hover:bg-admin-bg text-admin-textPrimary p-1.5 rounded-lg transition-all shadow-sm" title="Print QR Code">
                                    <i data-lucide="printer" class="w-4 h-4 text-admin-textSecondary hover:text-admin-primary"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-admin-textSecondary mt-4 bg-admin-surface p-4 rounded-xl border border-admin-border/50">
                            <div><strong class="text-admin-textPrimary">Age:</strong> {{ $pet->age_months ? floor($pet->age_months / 12) . ' Years ' . ($pet->age_months % 12) . ' Months' : 'Unknown' }}</div>
                            <div><strong class="text-admin-textPrimary">Size:</strong> <span class="capitalize">{{ $pet->size ?? 'Unknown' }}</span></div>
                            <div><strong class="text-admin-textPrimary">Gender:</strong> <span class="capitalize">{{ $pet->gender ?? 'Unknown' }}</span></div>
                            <div><strong class="text-admin-textPrimary">Color:</strong> {{ $pet->color ?? 'Unknown' }}</div>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-4 items-center justify-between">
                        <div class="flex gap-4">
                            @if($canEdit)
                            <button class="bg-admin-primary hover:bg-admin-primaryPress text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                                <i data-lucide="pencil" class="w-4 h-4"></i> Edit Profile
                            </button>
                            <button class="bg-admin-surface border border-admin-border hover:bg-admin-bg text-admin-textPrimary font-semibold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                                <i data-lucide="share-2" class="w-4 h-4 text-admin-textSecondary"></i> Share
                            </button>
                            @endif
                        </div>
                        
                        @if($isSuperAdmin ?? false)
                            <div>
                                @if($pet->isSuspended())
                                    <form action="{{ route('admin.pets.reactivate', $pet) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500/10 text-green-600 hover:bg-green-500/20 font-bold py-2.5 px-6 rounded-xl transition-all border border-green-500/20 flex items-center gap-2">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i> Reactivate Profile
                                        </button>
                                    </form>
                                @else
                                    <button onclick="document.getElementById('suspendModal').classList.remove('hidden')" class="bg-red-500/10 text-red-600 hover:bg-red-500/20 font-bold py-2.5 px-6 rounded-xl transition-all border border-red-500/20 flex items-center gap-2">
                                        <i data-lucide="ban" class="w-4 h-4"></i> Suspend Profile
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- QR Code -->
                <aside class="lg:col-span-3 bg-white text-slate-900 rounded-xl border border-admin-border shadow-sm p-5 flex flex-col items-center justify-between">
                    <div class="text-center">
                        <div class="text-xs font-extrabold uppercase tracking-widest text-slate-500">Scan Pet ID</div>
                        <div class="text-lg font-extrabold mt-1">{{ $pet->qr_code_id }}</div>
                    </div>

                    <div class="pet-qr-svg w-44 h-44 my-4">
                        {!! $qrCodeSvg !!}
                    </div>

                    <div class="w-full space-y-3">
                        <p class="text-xs text-slate-500 text-center leading-relaxed break-words">
                            Opens this pet record through the public QR endpoint.
                        </p>
                        <button type="button"
                                onclick="window.print()"
                                class="w-full inline-flex items-center justify-center gap-2 bg-admin-primary hover:bg-admin-primaryPress text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-sm">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            Print QR Label
                        </button>
                    </div>
                </aside>
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
                    
                    @if($isSuperAdmin ?? false)
                    <button @click="activeTab = 'analytics'" 
                            :class="{ 'border-admin-primary text-admin-primary': activeTab === 'analytics', 'border-transparent text-admin-textSecondary hover:text-admin-textPrimary hover:border-admin-border': activeTab !== 'analytics' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4"></i> Analytics
                    </button>
                    @endif
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
                    
                    @if($pet->medicalRecords && $pet->medicalRecords->count() > 0)
                        <div class="space-y-4">
                            @foreach($pet->medicalRecords as $record)
                                <div class="bg-admin-surface rounded-xl border border-admin-border p-5 shadow-sm">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-bold text-admin-textPrimary">{{ $record->title }}</h4>
                                            <p class="text-xs text-admin-textSecondary flex items-center gap-2 mt-1">
                                                <i data-lucide="calendar" class="w-3 h-3"></i> {{ $record->record_date?->format('M d, Y') ?? 'N/A' }}
                                                <span class="px-2 py-0.5 bg-admin-primary/10 text-admin-primary rounded uppercase text-[10px] font-bold">{{ $record->type }}</span>
                                            </p>
                                        </div>
                                        @if($record->vet)
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-admin-textPrimary">{{ $record->vet->name }}</p>
                                                <p class="text-xs text-admin-textSecondary">Veterinarian</p>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm text-admin-textSecondary mt-2">{{ $record->description }}</p>
                                    @if($record->notes)
                                        <div class="mt-3 bg-admin-bg p-3 rounded-lg text-sm text-admin-textSecondary border border-admin-border/50">
                                            <strong class="text-admin-textPrimary">Notes:</strong> {{ $record->notes }}
                                        </div>
                                    @endif
                                    @if($record->next_visit_date)
                                        <div class="mt-4 flex items-center gap-2 text-brand-orange text-sm font-semibold">
                                            <i data-lucide="calendar-clock" class="w-4 h-4"></i> Next Visit: {{ $record->next_visit_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-admin-surface rounded-xl border border-admin-border shadow-sm overflow-hidden">
                            <div class="p-8 text-center">
                                <i data-lucide="activity" class="w-12 h-12 text-admin-textSecondary/30 mx-auto mb-3"></i>
                                <h4 class="text-admin-textPrimary font-bold">No Medical Records</h4>
                                <p class="text-admin-textSecondary text-sm mt-1">No health records have been added for this pet.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 3. Adoptions Tab -->
                <div x-cloak x-show="activeTab === 'adoptions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-extrabold text-admin-textPrimary">Adoption Applications</h3>
                    </div>
                    
                    @if($pet->adoptionRequests && $pet->adoptionRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($pet->adoptionRequests as $request)
                                <div class="bg-admin-surface rounded-xl border border-admin-border p-5 shadow-sm flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-admin-primary/10 flex items-center justify-center text-admin-primary font-bold text-lg">
                                            {{ strtoupper(substr($request->user->first_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-admin-textPrimary">{{ $request->user->first_name ?? 'Unknown' }} {{ $request->user->last_name ?? '' }}</h4>
                                            <p class="text-xs text-admin-textSecondary flex items-center gap-2 mt-1">
                                                <i data-lucide="calendar" class="w-3 h-3"></i> Applied: {{ $request->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-500/10 text-yellow-600 border-yellow-500/20',
                                                'approved' => 'bg-green-500/10 text-green-600 border-green-500/20',
                                                'rejected' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                                'cancelled' => 'bg-gray-500/10 text-gray-600 border-gray-500/20',
                                            ];
                                            $colorClass = $statusColors[$request->status] ?? 'bg-admin-primary/10 text-admin-primary border-admin-primary/20';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $colorClass }}">
                                            {{ $request->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-admin-surface rounded-xl border border-admin-border shadow-sm overflow-hidden">
                            <div class="p-8 text-center">
                                <i data-lucide="inbox" class="w-12 h-12 text-admin-textSecondary/30 mx-auto mb-3"></i>
                                <h4 class="text-admin-textPrimary font-bold">No Applications</h4>
                                <p class="text-admin-textSecondary text-sm mt-1">No one has applied to adopt {{ $pet->name }} yet.</p>
                            </div>
                        </div>
                    @endif
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

            @if($isSuperAdmin ?? false)
            <!-- 5. Analytics Tab -->
            <div x-cloak x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-extrabold text-admin-textPrimary">Pet Analytics & Performance</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-admin-surface p-5 rounded-xl border border-admin-border shadow-sm">
                        <div class="text-xs font-bold uppercase tracking-widest text-admin-textSecondary/70 mb-1">Total Adop. Requests</div>
                        <div class="text-3xl font-extrabold text-admin-textPrimary">{{ $analytics['adoption_requests'] ?? 0 }}</div>
                    </div>
                    <div class="bg-admin-surface p-5 rounded-xl border border-admin-border shadow-sm">
                        <div class="text-xs font-bold uppercase tracking-widest text-admin-textSecondary/70 mb-1">Adoption Success Rate</div>
                        <div class="text-3xl font-extrabold text-admin-textPrimary">{{ $analytics['adoption_success_rate'] ?? 0 }}%</div>
                    </div>
                    <div class="bg-admin-surface p-5 rounded-xl border border-admin-border shadow-sm">
                        <div class="text-xs font-bold uppercase tracking-widest text-admin-textSecondary/70 mb-1">Medical Records</div>
                        <div class="text-3xl font-extrabold text-admin-textPrimary">{{ $analytics['total_medical_records'] ?? 0 }}</div>
                    </div>
                    <div class="bg-admin-surface p-5 rounded-xl border border-admin-border shadow-sm">
                        <div class="text-xs font-bold uppercase tracking-widest text-admin-textSecondary/70 mb-1">Days in Care</div>
                        <div class="text-3xl font-extrabold text-admin-textPrimary">{{ $analytics['days_in_care'] ?? 0 }}</div>
                    </div>
                </div>
                
                <div class="bg-admin-surface p-6 rounded-xl border border-admin-border shadow-sm">
                    <h4 class="font-bold text-admin-textPrimary mb-4">Engagement Summary</h4>
                    <p class="text-admin-textSecondary text-sm leading-relaxed">
                        This pet has been in care for {{ $analytics['days_in_care'] ?? 0 }} days, generating {{ $analytics['adoption_requests'] ?? 0 }} adoption requests resulting in a {{ $analytics['adoption_success_rate'] ?? 0 }}% success rate. There are {{ $analytics['total_medical_records'] ?? 0 }} medical records logged, with the last visit on {{ $analytics['last_medical_visit'] ?? 'N/A' }}.
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>

    @if($isSuperAdmin ?? false)
    <!-- Suspend Modal -->
    <div id="suspendModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('suspendModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-admin-bg rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-admin-border">
                <form action="{{ route('admin.pets.suspend', $pet) }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-admin-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-admin-textPrimary" id="modal-title">Suspend Pet Profile</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-admin-textSecondary mb-4">
                                        Are you sure you want to suspend {{ $pet->name }}? This will hide the profile from public view. Please provide a reason for suspension.
                                    </p>
                                    <div>
                                        <label for="suspension_reason" class="block text-sm font-medium text-admin-textPrimary mb-1">Reason for Suspension <span class="text-red-500">*</span></label>
                                        <textarea id="suspension_reason" name="suspension_reason" rows="3" required class="w-full bg-admin-surface border border-admin-border rounded-lg p-2.5 text-sm text-admin-textPrimary focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="E.g., Investigation pending, Policy violation..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-admin-surface px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-admin-border">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Suspend Profile
                        </button>
                        <button type="button" onclick="document.getElementById('suspendModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-admin-border shadow-sm px-4 py-2 bg-white dark:bg-admin-bg text-base font-medium text-admin-textPrimary hover:bg-gray-50 dark:hover:bg-admin-surface focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-layouts.admin>
