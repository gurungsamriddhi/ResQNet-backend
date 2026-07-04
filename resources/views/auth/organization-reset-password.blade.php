<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | Organization | ResQNet</title>
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
<body class="font-sans antialiased bg-gradient-to-br from-orange-50 via-white to-green-50 min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">

        <!-- Logo & Heading -->
        <div class="text-center mb-8">
            <a href="/" class="inline-block">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    ResQ<span class="text-primary-500">Net</span>
                </h1>
            </a>
            <p class="mt-2 text-gray-500 text-sm">Organization & Shelter Portal</p>
            <div class="mt-3 inline-flex items-center gap-2 bg-secondary-50 border border-secondary-500/20 text-secondary-600 text-xs font-semibold px-3 py-1.5 rounded-full">
                <span>🐾</span>
                PARTNER LOGIN
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white/80 backdrop-blur-xl border border-gray-200/50 rounded-2xl p-8 shadow-xl shadow-gray-200/50">

            <h2 class="text-xl font-bold text-gray-950 mb-2">Reset Password</h2>
            <p class="text-gray-500 text-sm mb-6">Choose a strong new password for your organization account.</p>

            <!-- Status messages -->
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 p-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('organization.password.store') }}">
                @csrf

                <!-- New Password -->
                <x-auth.password-field
                    id="password"
                    name="password"
                    label="New Password"
                    :autofocus="true"
                />

                <!-- Confirm Password -->
                <x-auth.password-field
                    id="password_confirmation"
                    name="password_confirmation"
                    label="Confirm New Password"
                    class="mb-6"
                />

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5">
                    Update Password
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('organization.login') }}" class="text-sm text-gray-500 hover:text-gray-950 font-medium transition">
                    &larr; Back to Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-8">&copy; {{ date('Y') }} ResQNet Nepal. All rights reserved.</p>
    </div>
</body>
</html>
