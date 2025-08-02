<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENR Travel Order System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
         <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-gray-800 text-white transition-all duration-300 ease-in-out">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="h-8 w-8">
                    <h1 class="text-xl font-bold">DENR:TOIS</h1>
                </div>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('create-travel-order') }}" class="nav-item {{ Request::routeIs('create-travel-order') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Create Travel Order</span>
                </a>
                <a href="{{ route('my-travel-orders') }}" class="nav-item {{ Request::routeIs('my-travel-orders') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>My Travel Orders</span>
                </a>
                <a href="{{ route('user-management') }}" class="nav-item {{ Request::routeIs('user-management') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">U</div>
                        <div>
                            <p class="text-sm font-medium">User Name</p>
                            <p class="text-xs text-gray-400">user@denr.gov.ph</p>
                        </div>
                    </div>
                    <button id="logoutBtn" class="text-gray-400 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        @yield('content')

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/table-filters.js') }}"></script>
</body>
</html>

    