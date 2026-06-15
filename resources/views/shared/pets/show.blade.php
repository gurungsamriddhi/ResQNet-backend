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

    <div class="px-4 sm:px-6 md:px-8 py-8" x-data="{ activeTab: 'overview', deleteModalOpen: false, openEditModal: false, addHealthModalOpen: false }" x-effect="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons())">
        
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
                            <button @click="openEditModal = true" class="bg-admin-primary hover:bg-admin-primaryPress text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                                <i data-lucide="pencil" class="w-4 h-4"></i> Edit Profile
                            </button>
                            <button @click="deleteModalOpen = true" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Delete
                            </button>
                            <!--<button class="bg-admin-surface border border-admin-border hover:bg-admin-bg text-admin-textPrimary font-semibold py-2.5 px-6 rounded-xl transition-all shadow-sm flex items-center gap-2">
                                <i data-lucide="share-2" class="w-4 h-4 text-admin-textSecondary"></i> Share
                            </button>-->
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
                        <button @click="addHealthModalOpen = true" class="bg-admin-primary/10 text-admin-primary hover:bg-admin-primary/20 font-bold py-2 px-4 rounded-xl transition-colors text-sm border border-admin-primary/20 flex items-center gap-2">
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

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" @keydown.escape.window="deleteModalOpen = false">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-admin-bg rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full border border-admin-border">
                    <form method="POST" action="{{ route('organization.pets.destroy', $pet) }}">
                        @csrf
                        @method('DELETE')
                        <div class="bg-white dark:bg-admin-bg px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600 dark:text-red-400"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg font-extrabold text-admin-textPrimary">Delete {{ $pet->name }}?</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-admin-textSecondary">This action cannot be undone. The pet profile and all associated records will be permanently removed.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-admin-surface px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-admin-border">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                            <button type="button" @click="deleteModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-admin-border shadow-sm px-4 py-2 bg-white dark:bg-admin-bg text-base font-medium text-admin-textPrimary hover:bg-gray-50 dark:hover:bg-admin-surface focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;" @keydown.escape.window="openEditModal = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="openEditModal = false"></div>

            <div x-show="openEditModal" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="openEditModal = false">

                <form method="POST" action="{{ route('organization.pets.update', $pet) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="relative bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-10 pb-8 px-6">
                        <div class="absolute inset-0 opacity-10 rounded-t-2xl pointer-events-none">
                            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-admin-primary blur-3xl"></div>
                            <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full bg-admin-navActive blur-3xl"></div>
                        </div>
                        <div class="absolute top-4 right-4 z-10">
                            <button type="button" @click="openEditModal = false"
                                    class="p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200 shadow-sm">
                                <i data-lucide="x" class="w-4.5 h-4.5"></i>
                            </button>
                        </div>
                        <div class="flex flex-col items-center text-center relative z-[5]">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-xl border-4 border-admin-surface ring-2 ring-admin-primary/20 bg-admin-primary/20 text-admin-primary">
                                <i data-lucide="pencil" class="w-8 h-8 stroke-[2.5]"></i>
                            </div>
                            <h2 class="text-xl font-bold text-admin-textPrimary mt-4">Edit {{ $pet->name }}</h2>
                            <p class="text-sm text-admin-textSecondary/70 mt-0.5">Update the pet's profile information.</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Name</label>
                                <input type="text" name="name" value="{{ old('name', $pet->name) }}"
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Breed</label>
                                <input type="text" name="breed" value="{{ old('breed', $pet->breed) }}"
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Species</label>
                                <select name="species"
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                    <option value="dog" {{ $pet->species == 'dog' ? 'selected' : '' }}>Dog</option>
                                    <option value="cat" {{ $pet->species == 'cat' ? 'selected' : '' }}>Cat</option>
                                    <option value="bird" {{ $pet->species == 'bird' ? 'selected' : '' }}>Bird</option>
                                    <option value="rabbit" {{ $pet->species == 'rabbit' ? 'selected' : '' }}>Rabbit</option>
                                    <option value="other" {{ $pet->species == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Gender</label>
                                <select name="gender"
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                    <option value="male" {{ $pet->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $pet->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="unknown" {{ $pet->gender == 'unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Age (months)</label>
                                <input type="number" name="age_months" value="{{ old('age_months', $pet->age_months) }}" min="0"
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Size</label>
                                <select name="size"
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                    <option value="small" {{ $pet->size == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ $pet->size == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ $pet->size == 'large' ? 'selected' : '' }}>Large</option>
                                    <option value="xl" {{ $pet->size == 'xl' ? 'selected' : '' }}>Extra Large</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Color</label>
                                <input type="text" name="color" value="{{ old('color', $pet->color) }}"
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Status</label>
                                <select name="status"
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                    <option value="available" {{ $pet->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="adopted" {{ $pet->status == 'adopted' ? 'selected' : '' }}>Adopted</option>
                                    <option value="unavailable" {{ $pet->status == 'unavailable' ? 'selected' : '' }}>In Care</option>
                                    <option value="inactive" {{ $pet->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Description</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">{{ old('description', $pet->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Health Status</label>
                            <textarea name="health_status" rows="2"
                                      class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">{{ old('health_status', $pet->health_status) }}</textarea>
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="is_vaccinated" value="1" {{ $pet->is_vaccinated ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                                <span class="text-sm font-semibold text-admin-textPrimary">Vaccinated</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="is_neutered" value="1" {{ $pet->is_neutered ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-admin-border bg-admin-bg text-admin-primary focus:ring-admin-primary/30">
                                <span class="text-sm font-semibold text-admin-textPrimary">Neutered / Spayed</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-2">Profile Photo</label>
                            <div class="flex items-center gap-4">
                                @if($pet->primaryImage)
                                    <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-admin-border/40 flex-shrink-0">
                                        <img src="{{ Storage::url($pet->primaryImage->image_path) }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-xl bg-admin-bg border-2 border-dashed border-admin-border/50 flex items-center justify-center text-admin-textSecondary/40 flex-shrink-0">
                                        <i data-lucide="camera" class="w-6 h-6"></i>
                                    </div>
                                @endif
                                <div>
                                    <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                                        <i data-lucide="upload" class="w-4 h-4"></i>
                                        <span>Change Image</span>
                                        <input type="file" name="primary_image" accept="image/*" class="hidden">
                                    </label>
                                    <p class="text-[11px] text-admin-textSecondary/50 mt-1.5">JPG, PNG or GIF. 1:1 ratio recommended.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60">Additional Photos</label>
                                <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-admin-surface border border-admin-border text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-bg font-semibold text-xs transition-all duration-200">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Add Photos</span>
                                    <input type="file" name="additional_images[]" accept="image/*" multiple class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-5 border-t border-admin-border bg-admin-bg/20 flex items-center justify-between rounded-b-2xl">
                        <button type="button" @click="openEditModal = false"
                                class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1.5"></i> Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all duration-200 shadow-sm">
                            <i data-lucide="check" class="w-4 h-4 inline mr-1.5"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Health Record Modal -->
        <div x-show="addHealthModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
             style="display: none;" @keydown.escape.window="addHealthModalOpen = false">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm" @click="addHealthModalOpen = false"></div>

            <div x-show="addHealthModalOpen" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-admin-surface border border-admin-border rounded-2xl shadow-2xl"
                 @click.away="addHealthModalOpen = false"
                 x-data="healthRecordForm({{ $pet->id }})">

                <form @submit.prevent="submitRecord">
                    @csrf
                    <input type="hidden" name="pet_id" value="{{ $pet->id }}">

                    <div class="relative bg-gradient-to-br from-admin-primary/20 via-admin-primary/5 to-admin-bg rounded-t-2xl pt-10 pb-8 px-6">
                        <div class="absolute inset-0 opacity-10 rounded-t-2xl pointer-events-none">
                            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full bg-admin-primary blur-3xl"></div>
                            <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full bg-admin-navActive blur-3xl"></div>
                        </div>
                        <div class="absolute top-4 right-4 z-10">
                            <button type="button" @click="addHealthModalOpen = false"
                                    class="p-2 bg-admin-surface/80 backdrop-blur-sm border border-admin-border rounded-xl text-admin-textSecondary hover:text-admin-textPrimary hover:bg-admin-surface transition-all duration-200 shadow-sm">
                                <i data-lucide="x" class="w-4.5 h-4.5"></i>
                            </button>
                        </div>
                        <div class="flex flex-col items-center text-center relative z-[5]">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-xl border-4 border-admin-surface ring-2 ring-admin-primary/20 bg-admin-primary/20 text-admin-primary">
                                <i data-lucide="heart-pulse" class="w-8 h-8 stroke-[2.5]"></i>
                            </div>
                            <h2 class="text-xl font-bold text-admin-textPrimary mt-4">Add Medical Record</h2>
                            <p class="text-sm text-admin-textSecondary/70 mt-0.5">Record a health event for {{ $pet->name }}.</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" x-model="title" required
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Type <span class="text-red-500">*</span></label>
                                <select name="type" x-model="type" required
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all appearance-none">
                                    <option value="checkup">Checkup</option>
                                    <option value="treatment">Treatment</option>
                                    <option value="surgery">Surgery</option>
                                    <option value="injury">Injury</option>
                                    <option value="observation">Observation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Record Date <span class="text-red-500">*</span></label>
                                <input type="date" name="record_date" x-model="recordDate" required
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Next Visit Date</label>
                                <input type="date" name="next_visit_date" x-model="nextVisitDate"
                                       class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Description</label>
                            <textarea name="description" x-model="description" rows="3"
                                      class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/60 mb-1.5">Notes</label>
                            <textarea name="notes" x-model="notes" rows="2"
                                      class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                        </div>
                    </div>

                    <div class="px-8 py-5 border-t border-admin-border bg-admin-bg/20 flex items-center justify-between rounded-b-2xl">
                        <button type="button" @click="addHealthModalOpen = false"
                                class="px-5 py-2.5 rounded-xl bg-admin-surface border border-admin-border text-admin-textPrimary hover:bg-admin-bg font-semibold text-sm transition-all duration-200">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1.5"></i> Cancel
                        </button>
                        <button type="submit" :disabled="saving"
                                class="px-5 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress font-bold text-sm transition-all duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            <i data-lucide="plus" class="w-4 h-4 inline mr-1.5"></i>
                            <span x-text="saving ? 'Saving...' : 'Add Record'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('healthRecordForm', (petId) => ({
            petId: petId,
            title: '',
            type: 'checkup',
            recordDate: new Date().toISOString().split('T')[0],
            nextVisitDate: '',
            description: '',
            notes: '',
            saving: false,

            async submitRecord() {
                if (!this.title.trim()) return;
                this.saving = true;
                try {
                    const res = await fetch('{{ route("organization.health.medical-records.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pet_id: this.petId,
                            title: this.title,
                            type: this.type,
                            record_date: this.recordDate,
                            next_visit_date: this.nextVisitDate || null,
                            description: this.description || null,
                            notes: this.notes || null,
                        }),
                    });
                    if (!res.ok) {
                        const err = await res.json();
                        alert(err.message || 'Failed to save record.');
                        return;
                    }
                    window.location.reload();
                } catch (e) {
                    console.error('Save failed', e);
                    alert('An error occurred while saving the record.');
                } finally {
                    this.saving = false;
                }
            },
        }));
    });
</script>
</x-layouts.admin>
