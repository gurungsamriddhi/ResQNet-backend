<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | Admin | ResQNet</title>
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
<body class="font-sans antialiased bg-gray-900 min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-primary-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-block">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">
                    ResQ<span class="text-primary-500">Net</span>
                </h1>
            </a>
            <p class="mt-2 text-gray-400 text-sm">System Administration Portal</p>
        </div>

        <!-- Card -->
        <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-8 shadow-2xl">

            <h2 class="text-xl font-bold text-white mb-2">Reset Password</h2>
            <p class="text-gray-400 text-sm mb-6">Choose a strong, secure new password for your administration account.</p>

            <!-- Validation errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/30 p-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.store') }}">
                @csrf

                <!-- New Password -->
                <x-auth.password-field
                    id="password"
                    name="password"
                    label="New Password"
                    theme="dark"
                    :autofocus="true"
                />

                <!-- Confirm Password -->
                <x-auth.password-field
                    id="password_confirmation"
                    name="password_confirmation"
                    label="Confirm New Password"
                    theme="dark"
                    class="mb-6"
                />

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5">
                    Update Password
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-xs mt-8">&copy; {{ date('Y') }} ResQNet. All rights reserved.</p>
    </div>
</body>
</html>
