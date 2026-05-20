<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Submitted | ResQNet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50:'#fff7ed', 100:'#ffedd5', 500:'#f97316', 600:'#ea580c' },
                        secondary: { 50:'#f0fdf4', 100:'#dcfce7', 500:'#22c55e', 600:'#16a34a' },
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .float-animation { animation: float 3s ease-in-out infinite; }
        @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(1.4); opacity: 0; } }
        .pulse-ring { animation: pulse-ring 2s ease-out infinite; }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-orange-50 via-white to-green-50 min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-6 text-center">

        <!-- Animated Icon -->
        <div class="mb-8 relative inline-block">
            <div class="absolute inset-0 bg-primary-500/20 rounded-full pulse-ring"></div>
            <div class="relative w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center shadow-xl shadow-primary-500/30 float-animation mx-auto">
                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Logo -->
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">
            ResQ<span class="text-primary-500">Net</span>
        </h1>

        <!-- Main Message -->
        <div class="bg-white/80 backdrop-blur-xl border border-gray-200/50 rounded-2xl p-8 shadow-xl shadow-gray-200/50 mt-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-3">Registration Submitted Successfully! 🎉</h2>
            <p class="text-gray-600 mb-6 leading-relaxed">
                Thank you for partnering with ResQNet. Your application has been received and our team is now reviewing your documents.
            </p>

            <!-- Timeline -->
            <div class="bg-primary-50 border border-primary-100 rounded-xl p-5 mb-6 text-left">
                <h3 class="font-semibold text-primary-700 text-sm mb-3">What happens next?</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-secondary-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <p class="text-sm text-gray-700"><strong>Step 1:</strong> Your registration documents are being verified.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </div>
                        <p class="text-sm text-gray-700"><strong>Step 2:</strong> Our admin team will approve your account within <strong class="text-primary-600">1–2 working days</strong>.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-white text-xs font-bold">3</span>
                        </div>
                        <p class="text-sm text-gray-500">You will receive an email notification once your account is activated.</p>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-left">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <p class="text-sm text-blue-700">
                        Once approved, you can log in using the <strong>email and password</strong> you just created at <strong>resqnet.org.np/organization/login</strong>
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="/"
                    class="flex-1 bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 text-center">
                    ← Back to Home
                </a>
                <a href="mailto:info@resqnet.org.np"
                    class="flex-1 bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-xl border border-gray-200 transition text-center">
                    Contact Support
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-gray-400 text-xs mt-8">&copy; {{ date('Y') }} ResQNet Nepal. All rights reserved.</p>
    </div>
</body>
</html>
