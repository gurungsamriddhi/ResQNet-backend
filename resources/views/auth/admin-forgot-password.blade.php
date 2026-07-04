<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Admin | ResQNet</title>
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

            <h2 class="text-xl font-bold text-white mb-2">Forgot Password?</h2>
            <p class="text-gray-400 text-sm mb-6">Enter your email address and we'll send you a 6-digit OTP code to verify and reset your password.</p>

            <!-- Status messages -->
            @if (session('status'))
                <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-400 p-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-500/10 border border-red-500/30 p-3 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-gray-900/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                        placeholder="admin@resqnet.org.np">
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5">
                    Send Verification OTP
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('admin.login') }}" class="text-sm text-gray-400 hover:text-white transition">
                    &larr; Back to Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-xs mt-8">&copy; {{ date('Y') }} ResQNet. All rights reserved.</p>
    </div>
</body>
</html>
