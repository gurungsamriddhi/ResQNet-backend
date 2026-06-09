<x-layouts.admin>
    <div class="px-4 sm:px-6 md:px-8 py-8 bg-slate-50 min-h-screen"
         x-data="organizationManager()"
         x-init="if (typeof lucide !== 'undefined') $nextTick(() => lucide.createIcons()); initToasts();"
         x-effect="if (typeof lucide !== 'undefined') lucide.createIcons()">

        <!-- ===== PAGE HEADER ===== -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Partner organizations</h1>
                <p class="text-slate-600 font-medium text-sm mt-1">Verify and onboard rescue groups so they can save more lives.</p>
            </div>
            <button @click="inviteOrganization()"
                    class="border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 text-xs font-medium px-4 py-2 rounded-xl shadow-sm transition-all flex items-center gap-2 h-fit">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                <span>Invite organization</span>
            </button>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: "{{ session('success') }}" } }));
                    }, 500);
                });
            </script>
        @endif
        
        @if($errors->any())
            <script>
                document.addEventListener('alpine:init', () => {
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: "An error occurred. Please try again." } }));
                    }, 500);
                });
            </script>
        @endif

        <!-- ===== AWAITING VERIFICATION ===== -->
        @if($pendingOrganizations->count() > 0)
        <div>
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm mt-8 mb-6">
                <!-- Container Header -->
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-slate-900 font-bold text-lg">Awaiting verification</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $pendingOrganizations->count() }} organizations are ready for review.</p>
                    </div>
                    <span class="bg-amber-100 text-amber-800 font-bold text-[10px] tracking-wide uppercase px-2.5 py-1 rounded-md whitespace-nowrap">
                        Action needed
                    </span>
                </div>

                <!-- Pending Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
                    @foreach($pendingOrganizations as $org)
                        <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">

                            <!-- Header Meta Line -->
                            <div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                        @if($org->logo)
                                            <img src="{{ Storage::url($org->logo) }}" class="w-full h-full rounded-xl object-cover" alt="Logo">
                                        @else
                                            <i data-lucide="building" class="w-5 h-5 text-slate-500"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-slate-800 font-bold text-base block leading-snug">{{ $org->name }}</span>
                                        <div class="flex flex-wrap items-center gap-x-2.5 gap-y-0.5 mt-1">
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                <span>{{ $org->address ?: 'No address' }}</span>
                                            </span>
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="mail" class="w-3 h-3"></i>
                                                <span>{{ $org->email }}</span>
                                            </span>
                                            <span class="text-slate-500 font-medium text-xs flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <span>Submitted {{ $org->created_at->diffForHumans() }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Access Row -->
                            <button @click="reviewDocs({{ $org->id }}, '{{ addslashes($org->name) }}', '{{ $org->getFirstMediaUrl('registration_documents') }}')"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-semibold py-2 px-3 rounded-lg flex items-center justify-center gap-2 mt-4 transition-colors cursor-pointer">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                <span>Review docs (Reg No: {{ $org->registration_number ?? 'N/A' }})</span>
                            </button>

                            <!-- Action Forms Group -->
                            <div class="flex items-center justify-end gap-2 mt-4 pt-3 border-t border-slate-100">
                                <button @click="openRejectModal({{ $org->id }}, '{{ addslashes($org->name) }}')"
                                        class="border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    <span>Reject</span>
                                </button>
                                
                                <form action="{{ route('admin.organizations.approve', $org) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-[#5BA44C] hover:bg-emerald-600 text-white text-xs font-semibold px-4 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        <span>Approve</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- ===== VERIFIED PARTNERS ===== -->
        @if($verifiedOrganizations->count() > 0)
        <div>
            <h2 class="text-slate-900 font-bold text-xl mb-4 mt-8">Verified partners</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($verifiedOrganizations as $org)
                    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm flex flex-col justify-between transition-all duration-300">

                        <!-- Top: Avatar + Name + City -->
                        <div>
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-full bg-teal-700 border-2 border-teal-600/20 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                                    @if($org->logo)
                                        <img src="{{ Storage::url($org->logo) }}" class="w-full h-full object-cover" alt="Logo">
                                    @else
                                        <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-slate-800 font-bold text-base block leading-snug">{{ $org->name }}</span>
                                    <span class="text-slate-500 font-medium text-xs flex items-center gap-1 mt-0.5">
                                        <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                                        <span>{{ $org->address ?: 'No address' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="mt-3 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Established</span>
                                    <span class="font-semibold text-slate-700">{{ $org->created_at->format('Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">Reg No.</span>
                                    <span class="font-semibold text-slate-700">{{ $org->registration_number ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Controls -->
                        <div class="flex items-center justify-between gap-2 mt-4 pt-3 border-t border-slate-100">
                            <span class="bg-emerald-50 text-emerald-700 font-bold text-[11px] tracking-wide px-3 py-1 rounded-full flex items-center justify-center shadow-inner border border-emerald-200/50">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5 mr-1"></i>
                                Verified
                            </span>
                            <button @click="openSuspendModal({{ $org->id }}, '{{ addslashes($org->name) }}')"
                                    class="text-xs text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 px-2.5 py-1 rounded-md transition-colors flex items-center gap-1.5">
                                <i data-lucide="shield-off" class="w-3 h-3"></i>
                                Suspend
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ===== SUSPENDED ORGS ===== -->
        @if($suspendedOrganizations->count() > 0)
        <div>
            <h2 class="text-slate-900 font-bold text-xl mb-4 mt-8">Suspended partners</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($suspendedOrganizations as $org)
                    <div class="bg-white border border-slate-100 rounded-xl p-5 shadow-sm flex flex-col justify-between transition-all duration-300 opacity-75">

                        <div>
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-full bg-slate-200 border-2 border-slate-300 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                                    <i data-lucide="building" class="w-5 h-5 text-slate-500"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-slate-800 font-bold text-base block leading-snug">{{ $org->name }}</span>
                                    <span class="text-slate-500 font-medium text-xs flex items-center gap-1 mt-0.5">
                                        <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0"></i>
                                        <span>{{ $org->address ?: 'No address' }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3 p-2 bg-rose-50 rounded border border-rose-100 text-xs text-rose-800">
                                <strong>Reason:</strong><br/>
                                {!! nl2br(e($org->suspension_reason)) !!}
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 mt-4 pt-3 border-t border-slate-100">
                            <span class="bg-rose-100 text-rose-700 font-bold text-[11px] tracking-wide px-3 py-1 rounded-full flex items-center justify-center shadow-inner border border-rose-200">
                                <i data-lucide="ban" class="w-3.5 h-3.5 mr-1"></i>
                                Suspended
                            </span>
                            <form action="{{ route('admin.organizations.approve', $org) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-xs text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 border border-slate-200 px-2.5 py-1 rounded-md transition-colors flex items-center gap-1.5">
                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                    Reactivate
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- ===== REJECTED ORGS ===== -->
        @if($rejectedOrganizations->count() > 0)
        <div>
            <h2 class="text-slate-900 font-bold text-xl mb-4 mt-8">Rejected Applications</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rejectedOrganizations as $org)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm flex flex-col justify-between transition-all duration-300">
                        <div>
                            <div class="flex items-start gap-3.5">
                                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                                    <i data-lucide="building" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-slate-600 font-bold text-base block leading-snug">{{ $org->name }}</span>
                                    <span class="text-slate-500 font-medium text-xs flex items-center gap-1 mt-0.5">
                                        <i data-lucide="calendar" class="w-3 h-3 flex-shrink-0"></i>
                                        <span>Rejected on {{ $org->updated_at->format('M d, Y') }}</span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3 text-xs text-slate-500">
                                <strong>Reason:</strong><br/>
                                {!! nl2br(e($org->rejection_reason)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ===== EMPTY STATE ===== -->
        @if($pendingOrganizations->count() === 0 && $verifiedOrganizations->count() === 0 && $suspendedOrganizations->count() === 0 && $rejectedOrganizations->count() === 0)
        <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-14 text-center mt-8">
            <div class="w-14 h-14 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="building-2" class="w-7 h-7 text-slate-300"></i>
            </div>
            <p class="text-base font-semibold text-slate-600">No partner organizations yet</p>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Invite rescue groups to join the network and start collaborating to save more animals.</p>
            <button @click="inviteOrganization()"
                    class="mt-5 inline-flex items-center gap-2 bg-[#5BA44C] text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-emerald-600 active:scale-[0.97] shadow-sm transition-all">
                <i data-lucide="mail-plus" class="w-4 h-4"></i>
                <span>Send your first invitation</span>
            </button>
        </div>
        @endif

        <!-- Reject Modal -->
        <div x-show="rejectModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="rejectModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900 opacity-75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="rejectModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form :action="rejectUrl" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i data-lucide="alert-triangle" class="h-6 w-6 text-rose-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                                        Reject Organization
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 mb-4">
                                            Select the reasons for rejecting <span class="font-semibold text-slate-700" x-text="targetOrgName"></span>. This will be sent to the organization via email.
                                        </p>
                                        
                                        <!-- Reasons list -->
                                        <div class="space-y-3">
                                            <template x-for="(reason, index) in rejectReasonsList" :key="index">
                                                <label class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" name="reasons[]" :value="reason" class="focus:ring-admin-primary h-4 w-4 text-admin-primary border-slate-300 rounded" x-model="selectedRejectReasons">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <span class="font-medium text-slate-700" x-text="reason"></span>
                                                    </div>
                                                </label>
                                            </template>
                                            
                                            <!-- Other Option -->
                                            <label class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="reasons[]" value="Other" class="focus:ring-admin-primary h-4 w-4 text-admin-primary border-slate-300 rounded" x-model="selectedRejectReasons">
                                                </div>
                                                <div class="ml-3 text-sm w-full">
                                                    <span class="font-medium text-slate-700">Other</span>
                                                    <div x-show="selectedRejectReasons.includes('Other')" class="mt-2" x-transition>
                                                        <textarea name="other_reason" rows="2" class="shadow-sm focus:ring-admin-primary focus:border-admin-primary block w-full sm:text-sm border-slate-300 rounded-md p-2 border" placeholder="Please specify..."></textarea>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                            <button type="submit" :disabled="selectedRejectReasons.length === 0" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Reject Application
                            </button>
                            <button type="button" @click="rejectModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Suspend Modal -->
        <div x-show="suspendModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="suspendModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900 opacity-75 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="suspendModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form :action="suspendUrl" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i data-lucide="shield-off" class="h-6 w-6 text-orange-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                                        Suspend Organization
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 mb-4">
                                            Select the reasons for suspending <span class="font-semibold text-slate-700" x-text="targetOrgName"></span>. This will immediately disable their access to the platform.
                                        </p>
                                        
                                        <!-- Reasons list -->
                                        <div class="space-y-3">
                                            <template x-for="(reason, index) in suspendReasonsList" :key="index">
                                                <label class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" name="reasons[]" :value="reason" class="focus:ring-admin-primary h-4 w-4 text-admin-primary border-slate-300 rounded" x-model="selectedSuspendReasons">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <span class="font-medium text-slate-700" x-text="reason"></span>
                                                    </div>
                                                </label>
                                            </template>
                                            
                                            <!-- Other Option -->
                                            <label class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="reasons[]" value="Other" class="focus:ring-admin-primary h-4 w-4 text-admin-primary border-slate-300 rounded" x-model="selectedSuspendReasons">
                                                </div>
                                                <div class="ml-3 text-sm w-full">
                                                    <span class="font-medium text-slate-700">Other</span>
                                                    <div x-show="selectedSuspendReasons.includes('Other')" class="mt-2" x-transition>
                                                        <textarea name="other_reason" rows="2" class="shadow-sm focus:ring-admin-primary focus:border-admin-primary block w-full sm:text-sm border-slate-300 rounded-md p-2 border" placeholder="Please specify..."></textarea>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                            <button type="submit" :disabled="selectedSuspendReasons.length === 0" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Suspend Account
                            </button>
                            <button type="button" @click="suspendModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Document Viewer Modal -->
        <div x-show="docModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="docModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="docModalOpen = false">
                    <div class="absolute inset-0 bg-slate-900 opacity-80 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="docModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                    
                    <div class="bg-white px-4 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5 text-admin-primary"></i>
                            <span x-text="`Registration Document: ${currentDocName}`"></span>
                        </h3>
                        <button @click="docModalOpen = false" class="text-slate-400 hover:text-slate-500 hover:bg-slate-100 rounded-full p-1 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <div class="bg-slate-100 p-0 relative" style="height: 70vh;">
                        <template x-if="currentDocUrl">
                            <iframe :src="currentDocUrl" class="w-full h-full border-0" title="Document Viewer"></iframe>
                        </template>
                        <template x-if="!currentDocUrl">
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-500">
                                <i data-lucide="file-warning" class="w-12 h-12 mb-3 text-slate-300"></i>
                                <p class="text-lg font-medium">No document available</p>
                                <p class="text-sm mt-1">This organization has not uploaded a registration document.</p>
                            </div>
                        </template>
                    </div>
                    
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                        <template x-if="currentDocUrl">
                            <a :href="currentDocUrl" target="_blank" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-admin-primary text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:ml-3 sm:w-auto sm:text-sm items-center gap-2">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                                Open in new tab
                            </a>
                        </template>
                        <button type="button" @click="docModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-admin-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Toast Notification (Alpine Global Event Listener) -->
        <div x-show="toastVisible" x-cloak 
             @show-toast.window="showToast($event.detail.message)"
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-x-4" 
             x-transition:enter-end="opacity-100 translate-x-0" 
             x-transition:leave="transition ease-in duration-300" 
             x-transition:leave-start="opacity-100 translate-x-0" 
             x-transition:leave-end="opacity-0 translate-x-4" 
             class="fixed top-6 right-6 z-[70] max-w-sm bg-white border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center border border-emerald-200 mt-0.5">
                <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-admin-textPrimary">Success</p>
                <p class="text-xs text-admin-textSecondary mt-0.5 leading-relaxed" x-text="toastMessage"></p>
            </div>
            <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 flex items-center justify-center flex-shrink-0 transition-colors">
                <i data-lucide="x" class="w-3.5 h-3.5 text-admin-textSecondary"></i>
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('organizationManager', () => ({
                rejectModalOpen: false,
                suspendModalOpen: false,
                docModalOpen: false,
                targetOrgId: null,
                targetOrgName: '',
                rejectUrl: '',
                suspendUrl: '',
                currentDocUrl: '',
                currentDocName: '',
                
                selectedRejectReasons: [],
                rejectReasonsList: [
                    'Incomplete documentation',
                    'Invalid registration number',
                    'Organization does not meet eligibility criteria',
                    'Duplicate registration',
                    'Suspicious or fraudulent information'
                ],
                
                selectedSuspendReasons: [],
                suspendReasonsList: [
                    'Violation of platform terms of service',
                    'Reports of animal abuse or neglect',
                    'Fraudulent activity detected',
                    'Failure to maintain required standards',
                    'Non-compliance with regulatory requirements'
                ],

                // Toast state
                toastMessage: '',
                toastVisible: false,
                toastTimeout: null,
                
                initToasts() {
                    // Handled via x-on:show-toast.window on the element
                },

                showToast(message) {
                    this.toastMessage = message;
                    this.toastVisible = true;
                    if (this.toastTimeout) clearTimeout(this.toastTimeout);
                    this.toastTimeout = setTimeout(() => { this.toastVisible = false; }, 4000);
                },

                openRejectModal(id, name) {
                    this.targetOrgId = id;
                    this.targetOrgName = name;
                    this.selectedRejectReasons = [];
                    // Generate URL dynamically. This requires standard resource route or manually parsing
                    this.rejectUrl = `/admin/organizations/${id}/reject`;
                    this.rejectModalOpen = true;
                },
                
                openSuspendModal(id, name) {
                    this.targetOrgId = id;
                    this.targetOrgName = name;
                    this.selectedSuspendReasons = [];
                    this.suspendUrl = `/admin/organizations/${id}/suspend`;
                    this.suspendModalOpen = true;
                },

                reviewDocs(id, name, url) {
                    this.currentDocName = name;
                    this.currentDocUrl = url;
                    this.docModalOpen = true;
                },

                inviteOrganization() {
                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message: 'An invitation link will be sent to the organization admin\'s email address.' } }));
                }
            }));
        });
    </script>
</x-layouts.admin>
