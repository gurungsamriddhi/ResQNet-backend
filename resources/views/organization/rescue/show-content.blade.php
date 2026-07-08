<!-- Leaflet Maps CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@if(session('success'))
    <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 p-4 rounded-xl flex items-center justify-between gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
        <div class="flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0 text-emerald-500"></i>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
        <button type="button" @click="show = false" class="text-emerald-700 dark:text-emerald-400 hover:opacity-75 transition-opacity">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

<div x-data="rescueDetailApp({ reportId: {{ $report->id }}, csrfToken: '{{ csrf_token() }}' })" class="space-y-6">
    <!-- Toast Notification -->
    <div x-show="toastVisible" x-cloak 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-x-4" 
         x-transition:enter-end="opacity-100 translate-x-0" 
         x-transition:leave="transition ease-in duration-300" 
         x-transition:leave-start="opacity-100 translate-x-0" 
         x-transition:leave-end="opacity-0 translate-x-4" 
         class="fixed top-5 right-5 z-[10000] max-w-sm bg-white dark:bg-[#111c2a] border border-admin-border rounded-xl shadow-2xl p-4 flex items-start gap-3">
        <div class="w-8 h-8 rounded-full bg-[#5BA44C]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
            <i data-lucide="check-circle" class="w-4 h-4 text-[#5BA44C]"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-bold text-admin-textPrimary">Success</p>
            <p class="text-xs text-admin-textSecondary mt-0.5 leading-relaxed" x-text="toastMessage"></p>
        </div>
        <button @click="toastVisible = false" class="w-5 h-5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center justify-center flex-shrink-0 transition-colors">
            <i data-lucide="x" class="w-3.5 h-3.5 text-admin-textSecondary"></i>
        </button>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Detail & Action Panel -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Case Overview Card -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-admin-border/60 pb-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-brand-orange/10 flex items-center justify-center text-brand-orange">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">Rescue Incident Details</h3>
                        <p class="text-admin-textSecondary text-xs mt-0.5">Comprehensive report overview and metadata.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Image Display (if exists) -->
                    @if($report->image_path)
                        <div class="relative group rounded-xl overflow-hidden border border-admin-border/50 shadow-inner bg-admin-bg/30">
                            <img src="{{ asset('storage/' . $report->image_path) }}" 
                                 alt="Rescue Case Image" 
                                 class="w-full h-80 object-cover group-hover:scale-[1.02] transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <a href="{{ asset('storage/' . $report->image_path) }}" target="_blank" 
                                   class="text-white bg-black/60 backdrop-blur-sm px-3.5 py-2 rounded-lg text-xs font-bold flex items-center gap-1.5 hover:bg-black/80 transition-colors">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    Open original image in new tab
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-48 rounded-xl bg-admin-bg border border-admin-border/50 flex flex-col items-center justify-center text-admin-textSecondary/50">
                            <i data-lucide="camera-off" class="w-12 h-12 mb-3"></i>
                            <span class="font-medium text-sm">No Photo Uploaded by Reporter</span>
                        </div>
                    @endif

                    <!-- Incident Description -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-admin-textSecondary uppercase tracking-wider">Incident Description</h4>
                        <p class="text-sm text-admin-textPrimary leading-relaxed bg-admin-bg/30 border border-admin-border/50 rounded-xl p-4 whitespace-pre-line">
                            {{ $report->description }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action / Update Panel -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-admin-border/60 pb-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-admin-primary/10 flex items-center justify-center text-admin-primary">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-admin-textPrimary font-extrabold text-lg tracking-tight">Case Status Actions</h3>
                        <p class="text-admin-textSecondary text-xs mt-0.5">Manage assignments and resolve the case.</p>
                    </div>
                </div>

                @if(auth()->check() || session()->has('team_member_id'))
                    @if($report->status === 'resolved')
                        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-400 p-4 rounded-xl flex items-start gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="font-bold text-sm">Case Resolved &amp; Closed</h4>
                                <p class="text-xs mt-1 leading-relaxed">This rescue operation has been fully resolved. A corresponding pet profile was created in the system database for intake and post-rescue care.</p>
                                @if($report->pet_id && auth()->check())
                                    <a href="{{ auth()->user()->hasRole('super_admin') ? route('admin.pets.show', $report->pet_id) : route('organization.pets.show', $report->pet_id) }}" 
                                       class="inline-flex items-center gap-1 text-xs font-bold text-brand-orange hover:underline mt-2">
                                        <i data-lucide="paw-print" class="w-3.5 h-3.5"></i>
                                        View Generated Pet Profile
                                    </a>
                                @endif
                            </div>
                        </div>
                    @elseif(session()->has('team_member_id') && !auth()->check())
                        {{-- Magic Link Team Member View --}}
                        @if($report->status === 'assigned')
                            <div class="space-y-4">
                                <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-800 dark:text-blue-400 p-4 rounded-xl flex items-start gap-3">
                                    <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-sm">You have been dispatched to this rescue</h4>
                                        <p class="text-xs mt-1 leading-relaxed">When you arrive at the rescue location, tap the button below to notify the organization dashboard that you are on-site.</p>
                                    </div>
                                </div>

                                <form action="{{ URL::signedRoute('rescue.arrived', ['report' => $report->id, 'teamMember' => session('team_member_id')]) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full bg-[#5BA44C] hover:bg-[#4A8A3E] text-white font-bold py-4 px-6 rounded-xl shadow-lg transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2.5 text-base">
                                        <i data-lucide="map-pin-check" class="w-5 h-5"></i>
                                        I Have Arrived
                                    </button>
                                </form>
                            </div>
                        @elseif($report->status === 'in_progress')
                            <div class="bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-indigo-800 dark:text-indigo-400 p-4 rounded-xl flex items-start gap-3">
                                <i data-lucide="activity" class="w-5 h-5 shrink-0 mt-0.5"></i>
                                <div>
                                    <h4 class="font-bold text-sm">Rescue In Progress</h4>
                                    <p class="text-xs mt-1 leading-relaxed">Your arrival has been recorded. The rescue operation is actively underway. The organization admin will mark this case as resolved once the animal is secured.</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-800 dark:text-amber-400 p-4 rounded-xl flex items-center gap-3">
                                <i data-lucide="clock" class="w-5 h-5 shrink-0"></i>
                                <span class="text-xs font-medium">Case status: <strong class="capitalize">{{ str_replace('_', ' ', $report->status) }}</strong></span>
                            </div>
                        @endif
                    @else
                        {{-- Authenticated Admin Status Update Form --}}
                        <form @submit.prevent="submitStatus" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-admin-textSecondary uppercase tracking-wider mb-2">Update Status</label>
                                <select x-model="status" 
                                        class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all">
                                    @if($report->status === 'assigned')
                                        <option value="in_progress">Start Rescue (In Progress)</option>
                                        <option value="resolved">Resolve Case (Resolved)</option>
                                    @elseif($report->status === 'in_progress')
                                        <option value="resolved">Complete Rescue (Resolved)</option>
                                    @else
                                        <option value="{{ $report->status }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-admin-textSecondary uppercase tracking-wider mb-2">Case Update Notes</label>
                                <textarea x-model="notes" rows="3" 
                                          placeholder="Enter dispatch updates, findings, or resolution remarks..."
                                          class="w-full px-3.5 py-2.5 bg-admin-bg border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all"></textarea>
                                <p class="text-[10px] text-admin-textSecondary/70 mt-1">If marked as Resolved, a pet profile will be automatically created in the database with these details.</p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" :disabled="isSubmitting"
                                        class="px-5 py-2.5 rounded-xl bg-admin-primary text-white text-xs font-bold hover:bg-admin-primaryPress transition-colors disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5">
                                    <span x-show="!isSubmitting">Update Case Status</span>
                                    <span x-show="isSubmitting" class="flex items-center gap-1"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Updating...</span>
                                </button>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-800 dark:text-blue-400 p-4 rounded-xl flex items-center gap-3">
                        <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
                        <span class="text-xs font-medium">Please login as organization administrator to take actions on this case.</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Sidebar Maps, Contacts, Team, Timeline -->
        <div class="space-y-6">
            
            <!-- Location Map Card -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-4 h-4 text-brand-orange"></i>
                    Rescue Geolocation
                </h4>
                
                <div id="rescue-detail-map" class="w-full h-56 rounded-xl shadow-inner border border-admin-border/60 overflow-hidden"></div>
                
                <div class="space-y-2">
                    <p class="text-sm font-medium text-admin-textPrimary leading-relaxed">
                        {{ $report->address ?: 'Address not explicitly detailed.' }}
                    </p>
                    @if($report->latitude && $report->longitude)
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-1 text-xs font-bold text-brand-orange hover:underline">
                            <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                            Open in Google Maps
                        </a>
                    @endif
                </div>
            </div>

            <!-- Reporter Details Card -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="user" class="w-4 h-4 text-brand-orange"></i>
                    Reporter Information
                </h4>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand-navy text-white flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr($report->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-admin-textPrimary">{{ $report->user->name ?? 'Anonymous Citizen' }}</p>
                        @if($report->user?->phone)
                            <a href="tel:{{ $report->user->phone }}" 
                               class="text-xs font-semibold text-brand-orange hover:underline flex items-center gap-1 mt-0.5">
                                <i data-lucide="phone" class="w-3 h-3"></i>
                                {{ $report->user->phone }}
                            </a>
                        @else
                            <p class="text-xs text-admin-textSecondary">Phone number not provided</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assigned Team Card -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="users" class="w-4 h-4 text-brand-orange"></i>
                    Assigned Dispatch Team
                </h4>

                @if($report->assignments->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($report->assignments as $assignment)
                            <div class="flex items-center gap-3 p-2.5 rounded-lg bg-admin-bg border border-admin-border/50">
                                <div class="w-8 h-8 rounded-full bg-admin-primary/10 text-admin-primary flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($assignment->teamMember->name ?? 'R', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-admin-textPrimary">{{ $assignment->teamMember->name ?? 'Rescuer' }}</p>
                                    <p class="text-[10px] text-admin-textSecondary capitalize">{{ str_replace('_', ' ', $assignment->role) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-xl border border-dashed border-admin-border text-center">
                        <i data-lucide="users" class="w-6 h-6 mx-auto text-slate-300 mb-1.5"></i>
                        <p class="text-xs text-admin-textSecondary font-medium">No team assigned yet</p>
                    </div>
                @endif
            </div>

            <!-- Status Timeline Card -->
            <div class="bg-admin-surface border border-admin-border rounded-2xl p-5 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-admin-textSecondary uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="history" class="w-4 h-4 text-brand-orange"></i>
                    Case Timeline
                </h4>

                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($report->histories as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-admin-border/60" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center ring-4 ring-white dark:ring-slate-800">
                                                <i data-lucide="check-circle" class="w-4 h-4 text-indigo-500"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5">
                                            <p class="text-xs font-bold text-admin-textPrimary">
                                                Status: <span class="capitalize">{{ str_replace('_', ' ', $history->to_status) }}</span>
                                            </p>
                                            <p class="text-[10px] text-admin-textSecondary mt-0.5">
                                                {{ $history->created_at->format('M d, Y H:i') }}
                                            </p>
                                            @if($history->notes)
                                                <p class="text-[10px] text-admin-textSecondary mt-1.5 bg-admin-bg p-2 rounded-lg border border-admin-border/50 leading-relaxed italic">
                                                    "{{ $history->notes }}"
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function rescueDetailApp(config) {
        return {
            reportId: config.reportId,
            csrfToken: config.csrfToken,
            status: '{{ $report->status }}',
            notes: '',
            isSubmitting: false,
            toastVisible: false,
            toastMessage: '',
            
            init() {
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                    this.initMap();
                });
            },
            
            initMap() {
                const lat = {{ $report->latitude ?? 'null' }};
                const lng = {{ $report->longitude ?? 'null' }};
                const mapElement = document.getElementById('rescue-detail-map');
                if (lat && lng && mapElement) {
                    const map = L.map(mapElement, {
                        center: [lat, lng],
                        zoom: 14,
                        zoomControl: false
                    });
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap',
                        maxZoom: 19,
                    }).addTo(map);
                    
                    L.marker([lat, lng]).addTo(map)
                        .bindPopup("<b>Incident Spot</b><br>{{ $report->address }}")
                        .openPopup();
                }
            },
            
            async submitStatus() {
                this.isSubmitting = true;
                try {
                    const response = await fetch('{{ route("organization.rescues.update", $report->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: this.status,
                            notes: this.notes
                        })
                    });
                    const data = await response.json();
                    if (response.ok) {
                        this.toastMessage = data.message || 'Status updated successfully.';
                        this.toastVisible = true;
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        alert(data.message || 'Error updating status');
                    }
                } catch (err) {
                    alert('Network error. Please try again.');
                } finally {
                    this.isSubmitting = false;
                }
            }
        }
    }
</script>
