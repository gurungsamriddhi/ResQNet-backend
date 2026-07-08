<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donate — ResQNet</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|outfit:600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F4F6F8] text-brand-navy antialiased">

    {{-- ============================================================ --}}
    {{-- NAVBAR (matches welcome page structure, brand colors)        --}}
    {{-- ============================================================ --}}
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <svg class="h-8 w-8 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="font-bold text-2xl tracking-tight text-brand-navy">ResQ<span class="text-brand-orange">Net</span></span>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-600 hover:text-brand-orange font-medium transition">Home</a>
                    <a href="/#about" class="text-gray-600 hover:text-brand-orange font-medium transition">About</a>
                    <a href="/#features" class="text-gray-600 hover:text-brand-orange font-medium transition">Features</a>
                    <a href="/#how-it-works" class="text-gray-600 hover:text-brand-orange font-medium transition">How It Works</a>
                    <a href="/#ngos" class="text-gray-600 hover:text-brand-orange font-medium transition">For NGOs</a>
                    <a href="{{ route('donate') }}" class="text-brand-orange font-bold transition">Donation</a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('register.organization') }}" class="bg-brand-orange hover:bg-[#e5660f] text-white px-5 py-2.5 rounded-full font-medium transition shadow-md shadow-brand-orange/30">
                        Partner With Us
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Success/Error Messages --}}
    <div class="pt-24">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16 space-y-20">

        {{-- ============================================================ --}}
        {{-- 1. HERO: Image (left) + Donation Form (right)               --}}
        {{-- ============================================================ --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-start">

            {{-- Left: Brand Story --}}
            <div class="order-1 lg:order-none lg:sticky lg:top-32">
                <div class="flex items-center gap-3 mb-5">
                    <span class="h-px w-8 bg-brand-orange/40"></span>
                    <span class="text-xs font-extrabold uppercase tracking-[0.2em] text-brand-orange">Support Our Mission</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-brand-navy leading-[1.1] mb-6">
                    Help Us Keep<br>Helping Others
                </h1>
                <p class="text-base sm:text-lg text-slate-600 leading-relaxed mb-12 max-w-lg">
                    Your donation helps us maintain the platform, improve features, and ensure help reaches those who need it.
                </p>

                {{-- Feature Rows --}}
                <div class="space-y-5">
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-brand-navy/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-11 h-11 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shrink-0">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-brand-navy">Reliable Platform</p>
                            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">24/7 uptime and secure infrastructure</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-brand-navy/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-11 h-11 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shrink-0">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-brand-navy">Better Features</p>
                            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">Continuous improvements</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-brand-navy/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-11 h-11 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shrink-0">
                            <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                        <div>
                            <p class="text-base font-bold text-brand-navy">More Impact</p>
                            <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">Helping more lives every day</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Donation Form Card --}}
            <div class="order-2 lg:order-none"
                 x-data="donationForm(@js($presetAmounts), @js(old('amount', 500)), @js(old('frequency', 'one_time')), @js(old('payment_method', 'khalti')), @js(old('is_anonymous', false)))">

                <div class="bg-white rounded-3xl border border-brand-navy/15 shadow-xl shadow-brand-navy/5 p-6 sm:p-8 lg:p-10">
                    <div class="mb-6">
                        <p class="text-xs font-extrabold uppercase tracking-widest text-brand-orange mb-1">Support Our Platform</p>
                        <h2 class="text-2xl font-extrabold text-brand-navy">Donate to Support the Platform</h2>
                    </div>

                    <form action="{{ route('donate.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="amount" :value="resolvedAmount">
                        <input type="hidden" name="frequency" :value="frequency">
                        <input type="hidden" name="is_anonymous" :value="isAnonymous ? 1 : 0">

                        {{-- Amount Grid --}}
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-3">Choose Amount</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($presetAmounts as $amount)
                                    <button type="button"
                                            @click="selectPreset({{ $amount }})"
                                            :class="selectedPreset === {{ $amount }} && !customMode
                                                ? 'border-brand-orange bg-brand-orange/5 text-brand-orange ring-2 ring-brand-orange/20'
                                                : 'border-brand-navy/10 bg-slate-50 text-brand-navy hover:border-brand-orange/40'"
                                            class="py-3 rounded-xl border text-sm font-bold transition-all">
                                        Rs. {{ number_format($amount) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Custom Amount --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Custom Amount (Rs.)</label>
                            <input type="number" min="1" step="1" x-model="customAmount" @input="customMode = true; selectedPreset = null"
                                   placeholder="Enter custom amount"
                                   class="w-full px-4 py-2.5 rounded-xl border border-brand-navy/10 bg-slate-50 text-brand-navy text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                        </div>

                        {{-- Frequency Toggle --}}
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-3">Frequency</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" @click="frequency = 'one_time'"
                                        :class="frequency === 'one_time' ? 'border-brand-orange bg-brand-orange/5 text-brand-orange ring-2 ring-brand-orange/20' : 'border-brand-navy/10 bg-slate-50 text-brand-navy'"
                                        class="py-3 rounded-xl border text-sm font-bold transition-all">
                                    One Time
                                </button>
                                <button type="button" @click="frequency = 'monthly'"
                                        :class="frequency === 'monthly' ? 'border-brand-orange bg-brand-orange/5 text-brand-orange ring-2 ring-brand-orange/20' : 'border-brand-navy/10 bg-slate-50 text-brand-navy'"
                                        class="py-3 rounded-xl border text-sm font-bold transition-all">
                                    Monthly
                                </button>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <input type="hidden" name="payment_method" value="khalti">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-3">Payment Method</label>
                            <label class="flex items-center gap-3 px-4 py-3 rounded-xl border border-brand-orange/20 bg-brand-orange/5 cursor-pointer">
                                <div class="w-5 h-5 rounded-md border-2 border-brand-orange bg-brand-orange flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm font-bold text-brand-navy">Khalti</span>
                            </label>
                        </div>

                        {{-- Donor Fields (hidden when anonymous) --}}
                        <div class="space-y-4 pt-2 border-t border-brand-navy/10" x-show="!isAnonymous" x-cloak>
                            <div>
                                <label for="donor_name" class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-1.5">Full Name</label>
                                <input type="text" id="donor_name" name="donor_name" value="{{ old('donor_name') }}"
                                       class="w-full px-4 py-2.5 rounded-xl border border-brand-navy/10 bg-slate-50 text-brand-navy text-sm focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="donor_phone" class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-1.5">Phone</label>
                                    <input type="tel" id="donor_phone" name="donor_phone" value="{{ old('donor_phone') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-brand-navy/10 bg-slate-50 text-brand-navy text-sm focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                                </div>
                                <div>
                                    <label for="donor_email" class="block text-xs font-extrabold uppercase tracking-widest text-brand-navy mb-1.5">Email</label>
                                    <input type="email" id="donor_email" name="donor_email" value="{{ old('donor_email') }}"
                                           class="w-full px-4 py-2.5 rounded-xl border border-brand-navy/10 bg-slate-50 text-brand-navy text-sm focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Anonymous Toggle --}}
                        <label class="flex items-center justify-between p-4 rounded-xl border border-brand-navy/10 bg-slate-50 cursor-pointer">
                            <div>
                                <p class="text-sm font-bold text-brand-navy">Donate Anonymously</p>
                                <p class="text-xs text-slate-500 mt-0.5">Your name will not be displayed publicly</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" class="sr-only" x-model="isAnonymous">
                                <div class="w-11 h-6 rounded-full transition-colors"
                                     :class="isAnonymous ? 'bg-brand-orange' : 'bg-slate-300'"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform"
                                     :class="isAnonymous ? 'translate-x-5' : 'translate-x-0'"></div>
                            </div>
                        </label>

                        {{-- CTA --}}
                        <button type="submit"
                                class="w-full py-3.5 rounded-xl bg-brand-orange hover:bg-[#e5660f] text-white font-extrabold text-sm transition-all shadow-lg shadow-brand-orange/25 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            Donate Securely
                        </button>

                        <p class="text-center text-xs text-slate-500 font-medium flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 text-brand-orange" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            100% Secure &amp; Trusted
                        </p>
                    </form>
                </div>
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- 2. Our Donors — Public Transparency Ledger                   --}}
        {{-- ============================================================ --}}
        @if($recentDonors->isNotEmpty())
            <section>
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-extrabold text-brand-navy">Our Donors</h2>
                    <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">Every contribution brings us closer to our mission. Thank you for your generosity.</p>
                </div>

                <div class="bg-white rounded-3xl border border-brand-navy/10 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-brand-navy/10 bg-slate-50/80">
                                    <th class="text-left px-6 py-4 text-xs font-extrabold uppercase tracking-widest text-brand-navy">Name</th>
                                    <th class="text-left px-6 py-4 text-xs font-extrabold uppercase tracking-widest text-brand-navy">Donate Date</th>
                                    <th class="text-right px-6 py-4 text-xs font-extrabold uppercase tracking-widest text-brand-navy">Donate Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-brand-navy/5">
                                @foreach($recentDonors as $donor)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-brand-navy">
                                            {{ $donor->is_anonymous || blank($donor->donor_name) ? 'Anonymous' : $donor->donor_name }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $donor->created_at->format('F j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-brand-navy">
                                            Rs. {{ number_format($donor->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @php $tableTotal = $recentDonors->sum('amount'); @endphp
                            <tfoot>
                                <tr class="bg-brand-navy/5 border-t-2 border-brand-navy/15">
                                    <td colspan="2" class="px-6 py-4 text-xs font-extrabold uppercase tracking-widest text-brand-navy">Total</td>
                                    <td class="px-6 py-4 text-right font-extrabold text-brand-navy">Rs. {{ number_format($tableTotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
        @endif

        {{-- ============================================================ --}}
        {{-- 3. Where Your Donation Goes                                   --}}
        {{-- ============================================================ --}}
        <section>
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-brand-navy">Where Your Donation Goes</h2>
                <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">Every contribution is allocated transparently to keep ResQNet running and growing.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($allocationBreakdown as $index => $item)
                    <div class="bg-white rounded-2xl border border-brand-navy/10 p-5 text-center shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3
                            @switch($index)
                                @case(0) bg-blue-50 text-blue-500 @break
                                @case(1) bg-violet-50 text-violet-500 @break
                                @case(2) bg-amber-50 text-amber-500 @break
                                @case(3) bg-rose-50 text-rose-500 @break
                                @case(4) bg-slate-100 text-slate-500 @break
                            @endswitch">
                            @switch($index)
                                @case(0)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                @break
                                @case(1)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                @break
                                @case(2)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                @break
                                @case(3)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                @break
                                @case(4)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                @break
                            @endswitch
                        </div>
                        <p class="text-2xl font-extrabold text-brand-orange">{{ $item['percent'] }}</p>
                        <p class="text-sm font-bold text-brand-navy mt-1">{{ $item['title'] }}</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $item['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- 4. Impact Stats                                               --}}
        {{-- ============================================================ --}}
        <section>
            <div class="text-center mb-10">
                <h2 class="text-3xl font-extrabold text-brand-navy">Your Support Makes a Difference</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($impactStats as $index => $stat)
                    <div class="bg-white rounded-2xl border border-brand-navy/10 p-6 text-center shadow-sm">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4
                            @switch($index)
                                @case(0) bg-blue-50 text-blue-500 @break
                                @case(1) bg-amber-50 text-amber-500 @break
                                @case(2) bg-red-50 text-red-500 @break
                                @case(3) bg-emerald-50 text-emerald-500 @break
                            @endswitch">
                            @switch($index)
                                @case(0)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                @break
                                @case(1)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                @break
                                @case(2)
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                @break
                                @case(3)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                @break
                            @endswitch
                        </div>
                        <p class="text-3xl font-extrabold text-brand-navy">{{ $stat['value'] }}</p>
                        <p class="text-sm font-bold text-brand-navy mt-1">{{ $stat['label'] }}</p>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $stat['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ============================================================ --}}
        {{-- 5. Footer Appreciation Banner                                 --}}
        {{-- ============================================================ --}}
        <section class="bg-white rounded-3xl border border-brand-navy/10 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-orange/10 text-brand-orange flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed max-w-2xl">
                    This platform exists because of people like you. Your support helps us continue this mission and build a better, safer community for everyone.
                </p>
            </div>
            <a href="#"
               onclick="window.scrollTo({ top: 0, behavior: 'smooth' }); return false;"
               class="shrink-0 px-6 py-3 rounded-xl bg-brand-orange hover:bg-[#e5660f] text-white font-extrabold text-sm transition-all shadow-lg shadow-brand-orange/25">
                Thank You! ❤️
            </a>
        </section>
    </main>

    {{-- ============================================================ --}}
    {{-- FOOTER (matches welcome page structure, brand colors)         --}}
    {{-- ============================================================ --}}
    <footer class="bg-brand-navy text-white pt-16 pb-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="h-8 w-8 text-brand-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="font-bold text-2xl tracking-tight text-white">ResQ<span class="text-brand-orange">Net</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Empowering animal welfare in Nepal through technology, community, and compassion.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/#how-it-works" class="hover:text-brand-orange transition">How it Works</a></li>
                        <li><a href="/#features" class="hover:text-brand-orange transition">Features</a></li>
                        <li><a href="/#download" class="hover:text-brand-orange transition">Mobile App</a></li>
                        <li><a href="#" class="hover:text-brand-orange transition">Adopt a Pet</a></li>
                        <li><a href="#" class="hover:text-brand-orange transition">Report Rescue</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Partners</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/#ngos" class="hover:text-brand-orange transition">For NGOs</a></li>
                        <li><a href="{{ route('register.organization') }}" class="hover:text-brand-orange transition">Register Organization</a></li>
                        <li><a href="#" class="hover:text-brand-orange transition">Partner Directory</a></li>
                        <li><a href="#" class="hover:text-brand-orange transition">Sponsorships</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Pokhara, Nepal</li>
                        <li>info@resqnet.org.np</li>
                        <li>+977 1-461268</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} ResQNet. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0 text-sm text-gray-500">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('donationForm', (presets, oldAmount, oldFrequency, oldPayment, oldAnonymous) => ({
                selectedPreset: presets.includes(Number(oldAmount)) ? Number(oldAmount) : 500,
                customAmount: presets.includes(Number(oldAmount)) ? '' : oldAmount,
                customMode: !presets.includes(Number(oldAmount)) && oldAmount,
                frequency: oldFrequency || 'one_time',
                paymentMethod: oldPayment || 'khalti',
                isAnonymous: Boolean(oldAnonymous),

                selectPreset(amount) {
                    this.selectedPreset = amount;
                    this.customMode = false;
                    this.customAmount = '';
                },

                get resolvedAmount() {
                    if (this.customMode && this.customAmount) {
                        return Number(this.customAmount);
                    }
                    return this.selectedPreset || 500;
                },
            }));
        });
    </script>
</body>
</html>
