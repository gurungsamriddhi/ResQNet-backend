<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Your Organization | ResQNet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#fff7ed', 100:'#ffedd5', 200:'#fed7aa', 300:'#fdba74', 400:'#fb923c', 500:'#f97316', 600:'#ea580c', 700:'#c2410c' },
                        secondary: { 50:'#f0fdf4', 100:'#dcfce7', 500:'#22c55e', 600:'#16a34a' },
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-orange-50 via-white to-green-50 min-h-screen relative overflow-x-hidden">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">

            <!-- Logo & Heading -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        ResQ<span class="text-primary-500">Net</span>
                    </h1>
                </a>
                <h2 class="mt-3 text-2xl font-bold text-gray-900">Partner With ResQNet</h2>
                <p class="mt-2 text-gray-500 text-sm max-w-md mx-auto">
                    Register your animal shelter, rescue center, or NGO to start managing rescues, adoptions, and donations through our platform.
                </p>
            </div>

            <!-- Card -->
            <div class="bg-white/80 backdrop-blur-xl border border-gray-200/50 rounded-2xl p-8 shadow-xl shadow-gray-200/50">

                <!-- Validation errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="font-semibold text-red-700 text-sm">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6 bg-amber-50/70 border-l-4 border-amber-500 p-4 rounded-r-xl shadow-sm backdrop-blur-sm">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900 tracking-wide">📢 Important Login Note</h3>
                            <p class="text-xs text-amber-800 mt-1 leading-relaxed font-medium">
                                Please take careful note of the <span class="underline font-bold"> Email Address</span> and <span class="underline font-bold">Password</span> you provide in Section 1. After the administration verification audit passes, you must log in using those exact details, not your public operational company email.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('register.organization.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Admin User -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-sm">1</div>
                            <h3 class="text-lg font-bold text-gray-900">Admin Account Details</h3>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">This account will be used to log in and manage your organization on ResQNet.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                <input id="user_name" type="text" name="user_name" value="{{ old('user_name') }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                    placeholder="Ram Bahadur Thapa">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="user_phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                                <input id="user_phone" type="text" name="user_phone" value="{{ old('user_phone') }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                    placeholder="+977 98XXXXXXXX">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <input id="user_email" type="email" name="user_email" value="{{ old('user_email') }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                placeholder="you@example.com">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Password -->
                            <x-auth.password-field
                                id="password"
                                name="password"
                                label="Password"
                                placeholder="Min 8 characters"
                                size="sm"
                                :show-required="true"
                            />

                            <!-- Confirm Password -->
                            <x-auth.password-field
                                id="password_confirmation"
                                name="password_confirmation"
                                label="Confirm Password"
                                placeholder="Re-enter password"
                                size="sm"
                                :show-required="true"
                            />
                        </div>
                    </div>

                    <!-- Section 2: Organization -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 rounded-full bg-secondary-100 flex items-center justify-center text-secondary-600 font-bold text-sm">2</div>
                            <h3 class="text-lg font-bold text-gray-900">Organization Details</h3>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Provide your organization's official information for verification.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Org Name -->
                            <div>
                                <label for="org_name" class="block text-sm font-medium text-gray-700 mb-1.5">Organization Name <span class="text-red-500">*</span></label>
                                <input id="org_name" type="text" name="org_name" value="{{ old('org_name') }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                    placeholder="Nepal Animal Welfare Society">
                            </div>

                            <!-- Org Email -->
                            <div>
                                <label for="org_email" class="block text-sm font-medium text-gray-700 mb-1.5">Public Contact Email <span class="text-red-500">*</span></label>
                                <input id="org_email" type="email" name="org_email" value="{{ old('org_email') }}" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                    placeholder="info@yourorg.org">
                            </div>
                        </div>

                        <!-- Registration Number -->
                        <div class="mt-4">
                            <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1.5">Registration Number (NGO/Gov) <span class="text-red-500">*</span></label>
                            <input id="registration_number" type="text" name="registration_number" value="{{ old('registration_number') }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm"
                                placeholder="e.g., SWC-12345/078">
                        </div>

                        <!-- Document Upload -->
                        <div class="mt-4">
                            <label for="document" class="block text-sm font-medium text-gray-700 mb-1.5">Registration Document <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input id="document" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100 cursor-pointer border border-gray-200 rounded-xl bg-gray-50 transition">
                            </div>
                            <p class="mt-1.5 text-xs text-gray-400">Upload your government registration certificate. PDF, JPG, or PNG — max 5MB.</p>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5 text-base">
                        🐾 Submit Registration Request
                    </button>

                    <p class="mt-4 text-center text-xs text-gray-400">
                        By registering, you agree to ResQNet's Terms of Service and Privacy Policy.
                    </p>
                </form>

                <!-- Login link -->
                <div class="mt-6 text-center border-t border-gray-200 pt-5">
                    <p class="text-sm text-gray-500">
                        Already have an account?
                        <a href="{{ route('organization.login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Sign In</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-400 text-xs mt-8">&copy; {{ date('Y') }} ResQNet Nepal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>