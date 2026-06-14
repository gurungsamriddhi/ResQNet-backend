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
            @if($pet->primaryImage)
                <img src="{{ $pet->primaryImage->image_url }}" alt="{{ $pet->name }}" class="w-full h-72 object-cover">
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

                <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
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
                        <p class="mt-1 font-bold">{{ $pet->color ?? 'Unknown' }}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl bg-orange-50 border border-orange-100 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-orange-700">Managed By</p>
                    <p class="mt-1 font-bold">{{ $pet->organization->name ?? 'ResQNet Partner Organization' }}</p>
                    @if($pet->organization?->address)
                        <p class="mt-1 text-sm text-slate-600">{{ $pet->organization->address }}</p>
                    @endif
                </div>

                <div class="mt-6">
                    <h2 class="font-extrabold">About {{ $pet->name }}</h2>
                    <p class="mt-2 text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $pet->description ?: 'No public description has been added for this pet yet.' }}
                    </p>
                </div>

                @if($pet->status === 'adopted')
                    <div class="mt-6 rounded-xl bg-blue-50 border border-blue-100 p-4 text-blue-800">
                        <p class="font-bold">This pet has been adopted.</p>
                        <p class="mt-1 text-sm">Adopters can use the ResQNet mobile app to access the detailed pet record linked to this QR ID.</p>
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
