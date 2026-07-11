@if(auth()->check() && !isset($isTeamAccess))
    <x-layouts.admin>
        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('organization.rescues.index') }}" class="text-admin-textSecondary hover:text-admin-primary transition-colors">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <h2 class="font-extrabold text-2xl text-admin-textPrimary tracking-tight">
                        Case: {{ $report->case_code }}
                    </h2>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="{{ $report->urgency === 'critical' ? 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20' : ($report->urgency === 'high' ? 'bg-brand-orange/10 text-brand-orange border-brand-orange/20' : 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-500/20') }} px-3 py-1 text-sm font-semibold rounded-full capitalize border flex items-center gap-1.5 shadow-sm">
                        <span class="w-2 h-2 rounded-full {{ $report->urgency === 'critical' ? 'bg-red-500 animate-pulse' : ($report->urgency === 'high' ? 'bg-brand-orange animate-pulse' : 'bg-yellow-500') }}"></span>
                        {{ $report->urgency }} Urgency
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full capitalize border {{ 
                        $report->status === 'reported' ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : (
                        $report->status === 'assigned' ? 'bg-blue-500/10 text-blue-600 border-blue-500/20' : (
                        $report->status === 'in_progress' ? 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20' : 
                        'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'))
                    }}">
                        {{ str_replace('_', ' ', $report->status) }}
                    </span>
                </div>
            </div>
        </x-slot>

        <div class="px-4 sm:px-6 md:px-8 py-8">
            @include('organization.rescue.show-content')
        </div>
    </x-layouts.admin>
@else
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Rescue Case: {{ $report->case_code }} - ResQNet</title>
        
        <!-- Google Fonts for Modern Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- CSS / JS Bundles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js & Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
            .dark body, body.dark {
                background-color: #0F172A !important;
                color: #F8FAFC !important;
            }
            .dark .bg-admin-surface, body.dark .bg-admin-surface {
                background-color: #1E293B !important;
            }
            .dark .bg-admin-bg, body.dark .bg-admin-bg {
                background-color: #0F172A !important;
            }
            .dark .border-admin-border, body.dark .border-admin-border {
                border-color: #334155 !important;
            }
            .dark .border-admin-border\/60, body.dark .border-admin-border\/60 {
                border-color: rgba(51, 65, 85, 0.6) !important;
            }
            .dark .text-admin-textPrimary, body.dark .text-admin-textPrimary {
                color: #F8FAFC !important;
            }
            .dark .text-admin-textSecondary, body.dark .text-admin-textSecondary {
                color: #CBD5E1 !important;
            }
            .dark input, .dark select, .dark textarea,
            body.dark input, body.dark select, body.dark textarea {
                background-color: #0F172A !important;
                border-color: #334155 !important;
                color: #F8FAFC !important;
            }
            .dark .bg-brand-cream, body.dark .bg-brand-cream {
                background-color: #1E293B !important;
                border-color: #334155 !important;
            }
            .dark .bg-admin-bg, body.dark .bg-admin-bg {
                background-color: #0F172A !important;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full bg-slate-50 dark:bg-[#111c2a] text-slate-900 dark:text-slate-100 antialiased transition-colors duration-150"
          x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
          x-init="
            $watch('darkMode', val => {
                localStorage.setItem('darkMode', val);
                if (val) {
                    document.documentElement.classList.add('dark');
                    document.body.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    document.body.classList.remove('dark');
                }
            });
            if (darkMode) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
            }
          "
          :class="{ 'dark': darkMode }">
          <script>
              if (localStorage.getItem('darkMode') === 'true') {
                  document.documentElement.classList.add('dark');
                  document.body.classList.add('dark');
              }
          </script>

          <!-- Navigation -->
          <nav class="bg-white dark:bg-[#1a2333] border-b border-slate-200 dark:border-slate-800/80 sticky top-0 z-50 transition-colors duration-300">
              <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                  <div class="flex items-center gap-3">
                      <span class="w-8 h-8 rounded-xl bg-gradient-to-tr from-brand-orange to-amber-500 flex items-center justify-center text-white font-extrabold shadow-md">R</span>
                      <span class="font-extrabold text-lg text-slate-900 dark:text-white tracking-tight">ResQNet <span class="text-brand-orange text-xs font-semibold uppercase tracking-wider bg-brand-orange/10 px-2 py-0.5 rounded ml-1">Portal</span></span>
                  </div>
                  <div>
                      <!-- Dark/Light Theme Toggle -->
                      <button @click="darkMode = !darkMode" class="p-2 text-slate-500 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-slate-100 rounded-xl transition-all duration-300 relative group" aria-label="Toggle dark mode">
                          <i data-lucide="sun" x-show="!darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:rotate-45"></i>
                          <i data-lucide="moon" x-show="darkMode" class="w-5 h-5 transition-transform duration-300 group-hover:-rotate-12" style="display: none;"></i>
                      </button>
                  </div>
              </div>
          </nav>

          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
              <!-- Header Section -->
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 border-b border-slate-200 dark:border-slate-800 pb-5">
                  <div>
                      <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $report->title }}</h1>
                      <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Case: <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $report->case_code }}</span> • Reported {{ $report->created_at->diffForHumans() }}</p>
                  </div>
                  <div class="flex items-center gap-2.5">
                      <span class="{{ $report->urgency === 'critical' ? 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20' : ($report->urgency === 'high' ? 'bg-brand-orange/10 text-brand-orange border-brand-orange/20' : 'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-500/20') }} px-3 py-1 text-sm font-semibold rounded-full capitalize border flex items-center gap-1.5 shadow-sm">
                          <span class="w-2 h-2 rounded-full {{ $report->urgency === 'critical' ? 'bg-red-500 animate-pulse' : ($report->urgency === 'high' ? 'bg-brand-orange animate-pulse' : 'bg-yellow-500') }}"></span>
                          {{ $report->urgency }} Urgency
                      </span>
                      <span class="px-3 py-1 text-sm font-semibold rounded-full capitalize border {{ 
                          $report->status === 'reported' ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : (
                          $report->status === 'assigned' ? 'bg-blue-500/10 text-blue-600 border-blue-500/20' : (
                          $report->status === 'in_progress' ? 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20' : 
                          'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'))
                      }}">
                          {{ str_replace('_', ' ', $report->status) }}
                      </span>
                  </div>
              </div>

              @include('organization.rescue.show-content')
          </div>
    </body>
    </html>
@endif
