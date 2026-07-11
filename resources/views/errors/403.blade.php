<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied - ResQNet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8 text-center">
        <div class="flex justify-center mb-6">
            <svg class="h-20 w-20 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Access Denied</h1>
        
        <p class="text-gray-600 mb-8">
            @auth
                @php
                    $roleName = auth()->user()->roles->first()?->name ?? 'User';
                @endphp
                You are currently logged in as <strong>{{ ucfirst(str_replace('_', ' ', $roleName)) }}</strong>. 
                You do not have the required privileges to view this page.
            @else
                You do not have permission to access this resource.
            @endauth
        </p>

        @auth
            @php
                $roleName = auth()->user()->roles->first()?->name ?? 'user';
                $logoutRoute = $roleName === 'super_admin' ? route('admin.logout') : route('organization.logout');
            @endphp
            <div class="flex flex-col sm:flex-row gap-4 justify-center w-full">
                <a href="javascript:history.back()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors w-full">
                    Go Back
                </a>
                
                <form method="POST" action="{{ $logoutRoute }}" class="w-full m-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors w-full h-full">
                        Log Out & Switch
                    </button>
                </form>
            </div>
        @else
            <a href="/" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors w-full">
                Return Home
            </a>
        @endauth
    </div>
</body>
</html>
