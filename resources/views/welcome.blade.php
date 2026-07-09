<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ResQNet - Animal Rescue & Adoption Platform Nepal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS (Using CDN for standalone landing page simplicity, assuming Vite setup is standard) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#f97316', // Orange
                            600: '#ea580c',
                            700: '#c2410c',
                        },
                        secondary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#16a34a', // Green
                            600: '#15803d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom subtle animations */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <!-- Logo Icon -->
                    <svg class="h-8 w-8 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="font-bold text-2xl tracking-tight text-gray-900">ResQ<span class="text-primary-500">Net</span></span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-gray-600 hover:text-primary-600 font-medium transition">About</a>
                    <a href="#features" class="text-gray-600 hover:text-primary-600 font-medium transition">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-primary-600 font-medium transition">How It Works</a>
                    <a href="#ngos" class="text-gray-600 hover:text-primary-600 font-medium transition">For NGOs</a>
                    <a href="{{ route('donate') }}" class="text-primary-600 font-bold transition">Donation</a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('register.organization') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-5 py-2.5 rounded-full font-medium transition shadow-md shadow-primary-500/30">
                        Partner With Us
                    </a>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary-100 blur-3xl opacity-50"></div>
            <div class="absolute top-40 -left-40 w-96 h-96 rounded-full bg-secondary-100 blur-3xl opacity-50"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="fade-in-up">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-primary-50 text-primary-600 font-semibold text-sm mb-6 border border-primary-100">
                        🐾 Empowering Animal Welfare in Nepal
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6 tracking-tight">
                        Connecting Nepal to <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-primary-700">Animal Rescue</span> & Adoption
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-lg leading-relaxed">
                        A centralized platform bridging citizens, rescue centers, and animal lovers to report rescues, adopt pets, and support NGOs effortlessly.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#download" class="flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white px-8 py-4 rounded-full font-semibold text-lg transition shadow-lg shadow-primary-500/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Mobile App
                        </a>
                        <a href="#report" class="flex items-center justify-center bg-white hover:bg-gray-50 text-gray-800 border border-gray-200 px-8 py-4 rounded-full font-semibold text-lg transition shadow-sm hover:shadow">
                            Report Rescue
                        </a>
                    </div>
                    
                    <div class="mt-8 flex items-center gap-4 text-sm text-gray-500 font-medium">
                        <div class="flex -space-x-2">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1" alt="User">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2" alt="User">
                            <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=3" alt="User">
                        </div>
                        <p>Join 1,000+ community members</p>
                    </div>
                </div>
                
                <div class="relative fade-in-up delay-200 hidden lg:block">
                    <!-- Hero Placeholder Image (Using an abstract composition) -->
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-100 bg-white p-2 transform rotate-2 hover:rotate-0 transition duration-500">
                        <img src="https://images.unsplash.com/photo-1543852786-1cf6624b9987?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Rescued Animals" class="rounded-xl w-full h-[500px] object-cover" />
                        
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-xl border border-gray-100 flex items-center gap-4 animate-bounce" style="animation-duration: 3s;">
                            <div class="bg-secondary-100 p-3 rounded-full text-secondary-500">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Recently Adopted</p>
                                <p class="font-bold text-gray-900">Bella found a home!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. About ResQNet -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-secondary-500 font-semibold tracking-wide uppercase text-sm mb-2">About The Platform</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">A Centralized Ecosystem for Animal Welfare</h3>
                <p class="text-lg text-gray-600">ResQNet brings together modern technology and compassionate communities to streamline rescue operations, manage health records digitally, and facilitate loving adoptions.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl transition duration-300 group fade-in-up">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center text-primary-500 mb-6 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Rescue Reporting</h4>
                    <p class="text-gray-600 leading-relaxed">Report animals in distress instantly with GPS location and photo evidence from our mobile app.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl transition duration-300 group fade-in-up delay-100">
                    <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center text-secondary-500 mb-6 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Seamless Adoption</h4>
                    <p class="text-gray-600 leading-relaxed">Browse verified profiles of rescued animals and submit adoption requests directly to partner NGOs.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl transition duration-300 group fade-in-up delay-200">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Digital Health</h4>
                    <p class="text-gray-600 leading-relaxed">Comprehensive medical records, vaccination tracking, and vet histories for every registered animal.</p>
                </div>

                <!-- Card 4 -->
                <div class="bg-gray-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl transition duration-300 group fade-in-up delay-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-500 mb-6 group-hover:scale-110 transition">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">QR Identification</h4>
                    <p class="text-gray-600 leading-relaxed">Smart QR tags for shelter animals. Scan with any phone to instantly access the animal's profile and medical history.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. How It Works (Timeline) -->
    <section id="how-it-works" class="py-20 bg-primary-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-primary-600 font-semibold tracking-wide uppercase text-sm mb-2">The Journey</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">From Rescue to Forever Home</h3>
            </div>

            <div class="relative">
                <!-- Line connecting steps (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-primary-200 -translate-y-1/2 z-0"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-white border-4 border-primary-500 flex items-center justify-center text-2xl font-bold text-primary-500 mb-4 shadow-md">1</div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Report Rescue</h4>
                        <p class="text-gray-600 text-sm">Citizen spots an animal in need and submits a quick report with GPS via the mobile app.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-white border-4 border-primary-500 flex items-center justify-center text-2xl font-bold text-primary-500 mb-4 shadow-md">2</div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">NGO Response</h4>
                        <p class="text-gray-600 text-sm">Nearby partner organizations receive the alert, dispatch volunteers, and secure the animal.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-white border-4 border-primary-500 flex items-center justify-center text-2xl font-bold text-primary-500 mb-4 shadow-md">3</div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Treatment & Care</h4>
                        <p class="text-gray-600 text-sm">Animal receives vet care. Medical records and QR tags are generated in the ResQNet system.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-white border-4 border-primary-500 flex items-center justify-center text-2xl font-bold text-primary-500 mb-4 shadow-md">4</div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">Adoption</h4>
                        <p class="text-gray-600 text-sm">Once healthy, the animal is listed. Families can browse, apply, and provide a loving home.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Core Features -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-secondary-500 font-semibold tracking-wide uppercase text-sm mb-2">Powerful Features</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything needed for animal welfare</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Live Rescue Tracking</h4>
                        <p class="text-gray-600 text-sm">Track the status of your reported rescues in real-time until they are resolved.</p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Secure Donations</h4>
                        <p class="text-gray-600 text-sm">Integrated with Khalti and eSewa. Support your favorite shelters securely and transparently.</p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">NGO Admin Panel</h4>
                        <p class="text-gray-600 text-sm">A comprehensive dashboard for shelters to manage pets, adoptions, vets, and volunteers.</p>
                    </div>
                </div>
                 <!-- Feature 4 -->
                 <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Smart Search</h4>
                        <p class="text-gray-600 text-sm">Filter adoptable pets by species, breed, age, and location to find your perfect companion.</p>
                    </div>
                </div>
                <!-- Feature 5 -->
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Adoption Workflows</h4>
                        <p class="text-gray-600 text-sm">Streamlined application processes helping NGOs vet potential adopters efficiently.</p>
                    </div>
                </div>
                <!-- Feature 6 -->
                <div class="flex gap-4 p-6 rounded-2xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Medical Histories</h4>
                        <p class="text-gray-600 text-sm">Log vaccinations, treatments, and prescriptions to ensure animals stay healthy.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Mobile App Promotion Section -->
    <section id="download" class="py-24 bg-gray-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Phone Mockup -->
                <div class="relative mx-auto w-full max-w-sm lg:max-w-md fade-in-up">
                    <div class="relative rounded-[2.5rem] border-[14px] border-gray-800 bg-gray-800 shadow-2xl overflow-hidden aspect-[9/19.5]">
                        <!-- App Screen Placeholder -->
                        <div class="absolute inset-0 bg-gray-100 flex flex-col">
                            <!-- App Header -->
                            <div class="bg-primary-500 text-white p-6 pt-12 pb-4">
                                <h3 class="font-bold text-xl mb-1">Hi, Samriddhi! 👋</h3>
                                <p class="text-primary-100 text-sm">Ready to help some animals today?</p>
                            </div>
                            <!-- App Content -->
                            <div class="p-4 space-y-4">
                                <div class="bg-white p-4 rounded-xl shadow-sm flex items-center justify-between border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <span class="font-bold text-gray-800">Report Emergency</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                                <h4 class="font-bold text-gray-800 pt-2">Adoptable Pets near you</h4>
                                <div class="flex gap-4 overflow-x-auto pb-4">
                                    <div class="min-w-[140px] bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="h-24 bg-gray-200">
                                            <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-3">
                                            <p class="font-bold text-sm">Max</p>
                                            <p class="text-xs text-gray-500">2 months • Dog</p>
                                        </div>
                                    </div>
                                    <div class="min-w-[140px] bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="h-24 bg-gray-200">
                                            <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-3">
                                            <p class="font-bold text-sm">Luna</p>
                                            <p class="text-xs text-gray-500">1 year • Cat</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Bottom Nav -->
                            <div class="mt-auto bg-white border-t border-gray-200 p-4 flex justify-around text-gray-400">
                                <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center lg:text-left fade-in-up delay-200">
                    <h2 class="text-primary-500 font-semibold tracking-wide uppercase text-sm mb-2">ResQNet App</h2>
                    <h3 class="text-3xl md:text-4xl font-bold text-white mb-6">Rescue & Adopt from Your Pocket</h3>
                    <p class="text-lg text-gray-400 mb-8 leading-relaxed">
                        The ResQNet mobile app makes it incredibly easy to report animals in distress with pinpoint GPS accuracy. Browse adoptable pets, track your rescue reports, and make secure donations directly from your smartphone.
                    </p>
                    
                    <ul class="space-y-4 mb-10 text-gray-300">
                        <li class="flex items-center gap-3 justify-center lg:justify-start">
                            <svg class="w-5 h-5 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Instant GPS-based rescue reporting
                        </li>
                        <li class="flex items-center gap-3 justify-center lg:justify-start">
                            <svg class="w-5 h-5 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Live tracking of your submitted reports
                        </li>
                        <li class="flex items-center gap-3 justify-center lg:justify-start">
                            <svg class="w-5 h-5 text-secondary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Swipe through adoptable pets near you
                        </li>
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#" class="bg-white hover:bg-gray-100 text-gray-900 px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M16.365 1.488c-.808 0-2.316.486-3.155 1.428-.753.844-1.295 2.158-1.077 3.42 1.01.078 2.21-.497 3.013-1.42.75-.845 1.258-2.127 1.07-3.342-.03-.002-.572-.086-.851-.086zm-1.892 4.708c-1.396-.062-2.738.835-3.468.835-.73 0-1.815-.794-2.986-.773-1.52.023-2.923.882-3.712 2.25-1.597 2.766-.407 6.852 1.155 9.109.76 1.1 1.666 2.33 2.85 2.29 1.144-.041 1.583-.736 2.966-.736 1.383 0 1.782.736 2.986.713 1.224-.021 1.992-1.107 2.748-2.21 1.055-1.543 1.35-2.81 1.373-2.883-.031-.013-2.698-1.037-2.723-4.143-.021-2.597 2.125-3.837 2.219-3.888-1.218-1.78-3.111-2.022-3.408-2.064z"/></svg>
                            Download for iOS
                        </a>
                        <a href="#" class="bg-transparent border-2 border-gray-600 hover:border-gray-400 text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993s-.4482.9997-.9993.9997zm-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993s-.4482.9997-.9993.9997zm11.4045-6.02l1.9973-3.4592c.1158-.2006.047-.4572-.1536-.573-.2006-.1158-.4572-.047-.573.1536l-2.0369 3.5277c-1.4251-.6496-2.9842-1.0113-4.6153-1.0113-1.6312 0-3.1902.3617-4.6153 1.0113l-2.0369-3.5277c-.1158-.2006-.3724-.2694-.573-.1536-.2006.1158-.2694.3724-.1536.573l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3432-4.1021-2.6889-7.5743-7.1185-9.4396z"/></svg>
                            Download for Android
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Organization Partnership -->
    <section id="ngos" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-primary-50 rounded-3xl p-8 lg:p-16 border border-primary-100 flex flex-col lg:flex-row items-center justify-between gap-12 shadow-xl">
                <div class="lg:w-1/2">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Are you an Animal Shelter or NGO?</h2>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Join the ResQNet platform to digitize your shelter. Manage animal records, handle adoption requests efficiently, receive secure donations, and get alerted to rescue reports in your vicinity.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center gap-3 text-gray-700 font-medium">
                            <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">✓</span>
                            Free digital management dashboard
                        </li>
                        <li class="flex items-center gap-3 text-gray-700 font-medium">
                            <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">✓</span>
                            Generate QR codes for your animals
                        </li>
                        <li class="flex items-center gap-3 text-gray-700 font-medium">
                            <span class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">✓</span>
                            Reach a wider audience of adopters
                        </li>
                    </ul>
                    <a href="{{ route('register.organization') }}" class="inline-block bg-gray-900 hover:bg-gray-800 text-white px-8 py-4 rounded-full font-bold transition shadow-lg">
                        Request Organization Account
                    </a>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <!-- Dashboard Mockup Placeholder -->
                    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden transform rotate-1 hover:rotate-0 transition duration-300">
                        <div class="bg-gray-100 px-4 py-3 border-b border-gray-200 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="p-6">
                            <div class="h-8 w-1/3 bg-gray-200 rounded mb-6"></div>
                            <div class="grid grid-cols-3 gap-4 mb-6">
                                <div class="h-24 bg-primary-50 rounded-lg border border-primary-100 flex flex-col items-center justify-center">
                                    <div class="text-2xl font-bold text-primary-600">42</div>
                                    <div class="text-xs text-gray-500">Active Pets</div>
                                </div>
                                <div class="h-24 bg-secondary-50 rounded-lg border border-secondary-100 flex flex-col items-center justify-center">
                                    <div class="text-2xl font-bold text-secondary-600">12</div>
                                    <div class="text-xs text-gray-500">Adoptions</div>
                                </div>
                                <div class="h-24 bg-blue-50 rounded-lg border border-blue-100 flex flex-col items-center justify-center">
                                    <div class="text-2xl font-bold text-blue-600">5</div>
                                    <div class="text-xs text-gray-500">Rescues</div>
                                </div>
                            </div>
                            <div class="h-40 bg-gray-50 rounded-lg border border-gray-200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Statistics -->
    <section class="py-20 bg-primary-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-primary-400/50">
                <div class="fade-in-up">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2">1,245</div>
                    <div class="text-primary-100 font-medium">Animals Rescued</div>
                </div>
                <div class="fade-in-up delay-100">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2">890</div>
                    <div class="text-primary-100 font-medium">Successful Adoptions</div>
                </div>
                <div class="fade-in-up delay-200">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2">350+</div>
                    <div class="text-primary-100 font-medium">Active Volunteers</div>
                </div>
                <div class="fade-in-up delay-300">
                    <div class="text-4xl md:text-5xl font-extrabold mb-2">15</div>
                    <div class="text-primary-100 font-medium">Partner Organizations</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Testimonials -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
                <h2 class="text-secondary-500 font-semibold tracking-wide uppercase text-sm mb-2">Success Stories</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Happy Tails</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
                    <svg class="absolute top-6 left-6 w-10 h-10 text-gray-200" fill="currentColor" viewBox="0 0 32 32"><path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/></svg>
                    <p class="text-gray-600 italic relative z-10 pt-8 mb-6">
                        "Reporting the injured street dog was so simple. Within hours, a local shelter had picked him up. A month later, we adopted him through ResQNet!"
                    </p>
                    <div class="flex items-center gap-4">
                        <img class="w-12 h-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Sarah">
                        <div>
                            <h5 class="font-bold text-gray-900">Sarah Shrestha</h5>
                            <p class="text-sm text-gray-500">Adopter & Reporter</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
                    <svg class="absolute top-6 left-6 w-10 h-10 text-gray-200" fill="currentColor" viewBox="0 0 32 32"><path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/></svg>
                    <p class="text-gray-600 italic relative z-10 pt-8 mb-6">
                        "The NGO dashboard has revolutionized how we manage our shelter. The digital health records and QR codes save us hours of paperwork."
                    </p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-100 flex items-center justify-center text-secondary-600 font-bold text-xl">K</div>
                        <div>
                            <h5 class="font-bold text-gray-900">KAT Center</h5>
                            <p class="text-sm text-gray-500">Partner Organization</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative">
                    <svg class="absolute top-6 left-6 w-10 h-10 text-gray-200" fill="currentColor" viewBox="0 0 32 32"><path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"/></svg>
                    <p class="text-gray-600 italic relative z-10 pt-8 mb-6">
                        "I love how easy it is to donate to specific shelters via eSewa. It feels great knowing exactly where my contribution is going."
                    </p>
                    <div class="flex items-center gap-4">
                        <img class="w-12 h-12 rounded-full object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Rahul">
                        <div>
                            <h5 class="font-bold text-gray-900">Rahul Maharjan</h5>
                            <p class="text-sm text-gray-500">Regular Donor</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- 9. Volunteer CTA -->
    <section class="py-24 bg-white border-t border-gray-100 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Make a Difference Today</h2>
            <p class="text-xl text-gray-600 mb-10">Whether you want to foster, volunteer at a shelter, or spread awareness, your help is needed.</p>
            <a href="#" class="inline-block bg-secondary-500 hover:bg-secondary-600 text-white px-8 py-4 rounded-full font-bold text-lg transition shadow-lg shadow-secondary-500/30">
                Become a Volunteer
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="h-8 w-8 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="font-bold text-2xl tracking-tight text-white">ResQ<span class="text-primary-500">Net</span></span>
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
                        <li><a href="#how-it-works" class="hover:text-primary-500 transition">How it Works</a></li>
                        <li><a href="#features" class="hover:text-primary-500 transition">Features</a></li>
                        <li><a href="#download" class="hover:text-primary-500 transition">Mobile App</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Adopt a Pet</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Report Rescue</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Partners</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#ngos" class="hover:text-primary-500 transition">For NGOs</a></li>
                        <li><a href="{{ route('register.organization') }}" class="hover:text-primary-500 transition">Register Organization</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Partner Directory</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Sponsorships</a></li>
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
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">© {{ date('Y') }} ResQNet. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0 text-sm text-gray-500">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
