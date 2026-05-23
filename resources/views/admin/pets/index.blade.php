<x-layouts.admin>
    <!-- PHP Mock Data Array -->
    @php
    $mockPets = [
        [
            'name' => 'Max',
            'initial' => 'M',
            'avatar_color' => 'bg-emerald-500 text-white',
            'breed' => 'Golden Retriever',
            'age' => '2 years',
            'organization' => 'Green Valley Shelter',
            'status' => 'Available'
        ],
        [
            'name' => 'Luna',
            'initial' => 'L',
            'avatar_color' => 'bg-blue-500 text-white',
            'breed' => 'Siamese Cat',
            'age' => '1 year',
            'organization' => 'Urban Animal Rescue',
            'status' => 'Adopted'
        ],
        [
            'name' => 'Charlie',
            'initial' => 'C',
            'avatar_color' => 'bg-teal-600 text-white',
            'breed' => 'Labrador',
            'age' => '3 years',
            'organization' => 'Happy Paws',
            'status' => 'Pending'
        ],
        [
            'name' => 'Bella',
            'initial' => 'B',
            'avatar_color' => 'bg-indigo-500 text-white',
            'breed' => 'German Shepherd',
            'age' => '2.5 years',
            'organization' => 'Green Valley Shelter',
            'status' => 'Available'
        ],
        [
            'name' => 'Buddy',
            'initial' => 'B',
            'avatar_color' => 'bg-violet-500 text-white',
            'breed' => 'Mixed Breed',
            'age' => '4 years',
            'organization' => 'Street Rescue Initiative',
            'status' => 'Available'
        ],
        [
            'name' => 'Daisy',
            'initial' => 'D',
            'avatar_color' => 'bg-pink-500 text-white',
            'breed' => 'Blue-headed Parrot',
            'age' => null,
            'organization' => 'Wild Wing Rescue',
            'status' => 'In Care'
        ]
    ];
    @endphp

    <div class="px-4 sm:px-6 md:px-8 py-8 space-y-6">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-admin-textPrimary font-extrabold text-3xl tracking-tight">
                    Pets Management
                </h1>
                <p class="text-admin-textSecondary text-sm mt-1">
                    Manage and monitor shelter animals, breed classifications, and adoption statuses.
                </p>
            </div>
            <!-- Dynamic Micro-Interactions -->
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-admin-border bg-admin-surface text-admin-textPrimary hover:bg-admin-bg hover:text-admin-primary transition-all duration-300 font-semibold text-sm shadow-sm hover:shadow active:scale-95 group">
                    <i data-lucide="filter" class="w-4 h-4 text-admin-textSecondary group-hover:text-admin-primary transition-colors"></i>
                    <span>Advanced Filter</span>
                </button>
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-admin-primary text-white hover:bg-admin-primaryPress transition-all duration-300 font-bold text-sm shadow-md hover:shadow-lg active:scale-95 shadow-admin-primary/10">
                    <i data-lucide="plus" class="w-4.5 h-4.5 stroke-[2.5]"></i>
                    <span>Add Pet</span>
                </button>
            </div>
        </div>

        <!-- Search and Filters Control Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search bar taking up 3/4 space on desktop -->
            <div class="relative md:col-span-3">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="search" class="w-4.5 h-4.5"></i>
                </div>
                <input type="text" 
                       placeholder="Search pets by name, breed, or organization..." 
                       class="w-full pl-10 pr-4 py-3 bg-admin-surface border border-admin-border rounded-xl text-sm text-admin-textPrimary placeholder-admin-textSecondary/50 focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary transition-all duration-200 shadow-sm">
            </div>

            <!-- Styled Breed Select Dropdown taking up remaining 1/4 -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                </div>
                <select class="w-full pl-10 pr-10 py-3 bg-admin-surface border border-admin-border rounded-xl text-sm text-admin-textPrimary focus:outline-none focus:ring-2 focus:ring-admin-primary/20 focus:border-admin-primary appearance-none transition-all duration-200 shadow-sm font-medium">
                    <option value="">All Animals</option>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="chicken">Chicken</option>
                    <option value="birds">Birds</option>
                    <option value="rabbit">Rabbit</option>
                    <option value="cow">Cow</option>
                    <option value="donkey">Donkey</option>
                    <option value="duck">Duck</option>
                    <option value="pig">Pig</option>
                    <option value="rat">Rat</option>
                    <option value="others">Others</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-admin-textSecondary/60">
                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <!-- Modern Pet Directory Data Table Card -->
        <div class="bg-admin-surface border border-admin-border rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-admin-bg/50 border-b border-admin-border text-[11px] font-extrabold uppercase tracking-widest text-admin-textSecondary/80">
                            <th class="py-4 px-6 select-none">Pet Name</th>
                            <th class="py-4 px-6 select-none">Breed</th>
                            <th class="py-4 px-6 select-none">Age</th>
                            <th class="py-4 px-6 select-none">Organization</th>
                            <th class="py-4 px-6 select-none">Status</th>
                            <th class="py-4 px-6 select-none text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border/60">
                        @foreach ($mockPets as $pet)
                            <tr class="hover:bg-admin-bg/30 transition-colors duration-200 group">
                                <!-- Name & Avatar -->
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <!-- Letter Initials Avatar -->
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-extrabold text-sm shadow-sm transition-transform duration-300 group-hover:scale-105 {{ $pet['avatar_color'] }}">
                                            {{ $pet['initial'] }}
                                        </div>
                                        <span class="font-bold text-admin-textPrimary text-sm group-hover:text-admin-primary transition-colors">
                                            {{ $pet['name'] }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Breed -->
                                <td class="py-6 px-6">
                                    <span class="text-sm font-semibold text-admin-textSecondary">
                                        {{ $pet['breed'] }}
                                    </span>
                                </td>

                                <!-- Age -->
                                <td class="py-6 px-6">
                                    <span class="text-sm font-semibold text-admin-textSecondary">
                                        {{ $pet['age'] ?? '-' }}
                                    </span>
                                </td>

                                <!-- Organization -->
                                <td class="py-6 px-6">
                                    <span class="text-sm font-medium text-admin-textSecondary">
                                        {{ $pet['organization'] }}
                                    </span>
                                </td>

                                <!-- Status Pill -->
                                <td class="py-6 px-6">
                                    @if ($pet['status'] === 'Available')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-admin-successBg text-admin-successText shadow-sm border border-admin-successText/10">
                                            <span class="w-1.5 h-1.5 rounded-full bg-admin-btnGreen animate-pulse"></span>
                                            <span>Available</span>
                                        </span>
                                    @elseif ($pet['status'] === 'Pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FFFBEB] text-[#D97706] shadow-sm border border-[#FCD34D]/30">
                                            <i data-lucide="hourglass" class="w-3.5 h-3.5 animate-spin-slow"></i>
                                            <span>Pending</span>
                                        </span>
                                    @elseif ($pet['status'] === 'Adopted')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#EFF6FF] text-[#2563EB] shadow-sm border border-[#BFDBFE]/30">
                                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                            <span>Adopted</span>
                                        </span>
                                    @else
                                        <!-- In Care -->
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F5F3FF] text-[#7C3AED] shadow-sm border border-[#DDD6FE]/30">
                                            <i data-lucide="heart" class="w-3.5 h-3.5 text-[#7C3AED] fill-[#7C3AED]/20 animate-pulse"></i>
                                            <span>In Care</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-6 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2.5">
                                        <!-- View button -->
                                        <button class="p-2 text-admin-textSecondary hover:text-admin-primary hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105" title="View details">
                                            <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                                        </button>
                                        <!-- Edit button -->
                                        <button class="p-2 text-admin-textSecondary hover:text-admin-btnGreen hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105" title="Edit pet">
                                            <i data-lucide="pencil" class="w-4.5 h-4.5"></i>
                                        </button>
                                        <!-- Delete button -->
                                        <button class="p-2 text-admin-textSecondary hover:text-admin-btnRed hover:bg-admin-bg rounded-xl transition-all duration-300 hover:scale-105" title="Delete pet">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5 text-admin-btnRed"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Crisp Table Pagination Footer -->
            <div class="px-6 py-4 bg-admin-bg/20 border-t border-admin-border flex items-center justify-between flex-wrap gap-4 text-xs font-semibold text-admin-textSecondary">
                <span>Showing 1-6 of 2,847 pets</span>
                
                <div class="flex items-center gap-2">
                    <button class="px-3.5 py-1.5 rounded-xl border border-admin-border bg-admin-surface hover:bg-admin-bg text-admin-textPrimary transition-all duration-200 active:scale-95 disabled:opacity-50" disabled>
                        Previous
                    </button>
                    <button class="w-8 h-8 rounded-xl bg-admin-primary text-white flex items-center justify-center font-bold shadow-sm shadow-admin-primary/10">
                        1
                    </button>
                    <button class="w-8 h-8 rounded-xl border border-admin-border bg-admin-surface hover:bg-admin-bg text-admin-textPrimary flex items-center justify-center transition-all duration-200">
                        2
                    </button>
                    <button class="px-3.5 py-1.5 rounded-xl border border-admin-border bg-admin-surface hover:bg-admin-bg text-admin-textPrimary transition-all duration-200 active:scale-95">
                        Next
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-layouts.admin>
