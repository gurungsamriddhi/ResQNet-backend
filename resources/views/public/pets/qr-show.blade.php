<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pet->name }} - ResQNet Pet ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: Inter, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="max-w-3xl mx-auto px-4 py-6 sm:py-10">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            @if($pet->getFirstMedia('images'))
                <img src="{{ $pet->getFirstMediaUrl('images') }}" alt="{{ $pet->name }}" class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 bg-orange-50 flex items-center justify-center text-orange-600">
                    <span class="text-lg font-bold">ResQNet Pet ID</span>
                </div>
            @endif

            <section class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-widest text-orange-600">{{ $pet->qr_code_id }}</p>
                        <h1 class="mt-1 text-3xl font-extrabold tracking-tight">{{ $pet->name }}</h1>
                        <p class="mt-1 text-slate-500">
                            {{ ucfirst($pet->species) }}{{ $pet->breed ? ' - ' . $pet->breed : '' }}
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-full px-3 py-1 text-sm font-bold capitalize
                        {{ $pet->status === 'available' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        {{ str_replace('_', ' ', $pet->status) }}
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Age</p>
                        <p class="mt-1 font-bold">
                            {{ $pet->age_months ? floor($pet->age_months / 12) . ' yrs ' . ($pet->age_months % 12) . ' mos' : 'Unknown' }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Gender</p>
                        <p class="mt-1 font-bold capitalize">{{ $pet->gender ?? 'Unknown' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Size</p>
                        <p class="mt-1 font-bold capitalize">{{ $pet->size ?? 'Unknown' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Color</p>
                        <p class="mt-1 font-bold capitalize">{{ $pet->color ?? 'Unknown' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Vaccinated</p>
                        <p class="mt-1 font-bold">{{ $pet->is_vaccinated ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Neutered / Spayed</p>
                        <p class="mt-1 font-bold">{{ $pet->is_neutered ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 col-span-2 sm:col-span-3">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Health Status</p>
                        <div class="mt-1 flex items-center">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold capitalize border
                                {{ strtolower($pet->health_status) === 'healthy' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (strtolower($pet->health_status) === 'critical' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                {{ str_replace('_', ' ', $pet->health_status ?? 'healthy') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-xl bg-orange-50 border border-orange-100 p-5">
                    <p class="text-xs font-bold uppercase tracking-wide text-orange-700">Managed By</p>
                    <p class="mt-1.5 text-lg font-extrabold text-slate-900">{{ $pet->organization->name ?? 'ResQNet Partner Organization' }}</p>
                    
                    @if($pet->organization)
                        <div class="mt-4 space-y-2.5 text-sm text-slate-700">
                            @if($pet->organization->address)
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-orange-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $pet->organization->address }}</span>
                                </div>
                            @endif

                            @if($pet->organization->user?->name)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span><span class="text-slate-500 font-medium">Contact:</span> {{ $pet->organization->user->name }}</span>
                                </div>
                            @endif

                            @if($pet->organization->email)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <a href="mailto:{{ $pet->organization->email }}" class="text-orange-700 hover:underline font-semibold">{{ $pet->organization->email }}</a>
                                </div>
                            @endif

                            @if($pet->organization->phone)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <a href="tel:{{ $pet->organization->phone }}" class="text-orange-700 hover:underline font-semibold">{{ $pet->organization->phone }}</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <h2 class="font-extrabold text-lg text-slate-900">About {{ $pet->name }}</h2>
                    <p class="mt-2 text-slate-600 leading-relaxed whitespace-pre-line text-sm">
                        {{ $pet->description ?: 'No public description has been added for this pet yet.' }}
                    </p>
                </div>

                <!-- 1. Medical Timeline Section -->
                <div class="mt-8 border-t border-slate-200 pt-6">
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Medical History
                    </h2>
                    @if($pet->medicalRecords->isEmpty())
                        <p class="mt-2 text-sm text-slate-500 italic">No medical records on file for this pet.</p>
                    @else
                        <div class="mt-4 relative pl-6 border-l-2 border-slate-200 space-y-6">
                            @foreach($pet->medicalRecords as $record)
                                <div class="relative">
                                    <span class="absolute -left-[31px] top-1 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-white border-2 border-orange-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-600"></span>
                                    </span>
                                    <div>
                                        <span class="inline-block text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}
                                        </span>
                                        <span class="ml-2 inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-orange-50 text-orange-700 border border-orange-100">
                                            {{ str_replace('_', ' ', $record->type) }}
                                        </span>
                                        <h4 class="text-sm font-extrabold text-slate-900 mt-1.5">{{ $record->title }}</h4>
                                        @if($record->description)
                                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $record->description }}</p>
                                        @endif
                                        @if($record->notes)
                                            <p class="text-xs text-slate-500 mt-1 italic">Notes: {{ $record->notes }}</p>
                                        @endif
                                        @if($record->vet)
                                            <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Dr. {{ $record->vet->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 2. Vaccinations Section -->
                <div class="mt-8 border-t border-slate-200 pt-6">
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Vaccinations
                    </h2>
                    @if($pet->vaccinations->isEmpty())
                        <p class="mt-2 text-sm text-slate-500 italic">No vaccination records on file.</p>
                    @else
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($pet->vaccinations as $vaccination)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start gap-2">
                                            <h4 class="text-sm font-bold text-slate-900">{{ $vaccination->vaccine_name }}</h4>
                                            @if($vaccination->batch_number)
                                                <span class="text-[9px] font-mono text-slate-400 bg-slate-200/60 px-1.5 py-0.5 rounded whitespace-nowrap">
                                                    Batch: {{ $vaccination->batch_number }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-600 mt-2">
                                            Administered: <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($vaccination->date_administered)->format('M d, Y') }}</span>
                                        </p>
                                        @if($vaccination->next_due_date)
                                            <p class="text-xs text-slate-600 mt-1">
                                                Next Due: <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($vaccination->next_due_date)->format('M d, Y') }}</span>
                                            </p>
                                        @endif
                                        @if($vaccination->notes)
                                            <p class="text-xs text-slate-500 mt-2 italic bg-white p-2 rounded border border-slate-100">{{ $vaccination->notes }}</p>
                                        @endif
                                    </div>
                                    @if($vaccination->vet)
                                        <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center gap-1.5 text-[11px] text-slate-400">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Dr. {{ $vaccination->vet->name }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 3. Medications Section -->
                <div class="mt-8 border-t border-slate-200 pt-6">
                    <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        Prescribed Medications
                    </h2>
                    @if($pet->medications->isEmpty())
                        <p class="mt-2 text-sm text-slate-500 italic">No medications prescribed.</p>
                    @else
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($pet->medications as $medication)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start gap-2">
                                            <h4 class="text-sm font-bold text-slate-900">{{ $medication->medicine_name }}</h4>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold capitalize border whitespace-nowrap
                                                {{ !$medication->end_date || \Carbon\Carbon::parse($medication->end_date)->isFuture() ? 'bg-green-50 text-green-700 border-green-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                                {{ !$medication->end_date || \Carbon\Carbon::parse($medication->end_date)->isFuture() ? 'Active' : 'Completed' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-xs text-slate-600">
                                                Dosage: <span class="font-semibold text-slate-800">{{ $medication->dosage }}</span>
                                            </p>
                                            @if($medication->frequency)
                                                <p class="text-xs text-slate-600">
                                                    Frequency: <span class="font-semibold text-slate-800">{{ $medication->frequency }}</span>
                                                </p>
                                            @endif
                                            <p class="text-xs text-slate-500">
                                                Duration: <span class="font-medium text-slate-700">
                                                    {{ \Carbon\Carbon::parse($medication->start_date)->format('M d, Y') }}
                                                    to
                                                    {{ $medication->end_date ? \Carbon\Carbon::parse($medication->end_date)->format('M d, Y') : 'Ongoing' }}
                                                </span>
                                            </p>
                                        </div>
                                        @if($medication->notes)
                                            <p class="text-xs text-slate-500 mt-2 italic bg-white p-2 rounded border border-slate-100">{{ $medication->notes }}</p>
                                        @endif
                                    </div>
                                    @if($medication->vet)
                                        <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center gap-1.5 text-[11px] text-slate-400">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Prescribed by: Dr. {{ $medication->vet->name }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if($pet->status === 'adopted')
                    <div class="mt-6 rounded-xl bg-blue-50 border border-blue-100 p-4 text-blue-800">
                        <p class="font-bold text-sm">This pet has been adopted.</p>
                        <p class="mt-1 text-xs">Adopters can use the ResQNet mobile app to access the detailed pet record linked to this QR ID.</p>
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ $appDeepLink }}" class="inline-flex items-center justify-center rounded-xl bg-orange-600 px-4 py-3 font-bold text-white hover:bg-orange-700 transition">
                        Open in ResQNet App
                    </a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
