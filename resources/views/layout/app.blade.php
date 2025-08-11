<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DENR Travel Order System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/denr-logo.png') }}">
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
                    &nbsp;
                    <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="h-8 w-8">
                    <h1 class="text-xl font-bold">&nbsp;&nbsp;&nbsp;DENR:TOIS</h1>
                </div>
            </div>
            <nav class="mt-4">
                <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                @if (!auth()->user()->is_admin)
                <a href="{{ route('my-travel-orders') }}" class="nav-item {{ Request::routeIs('my-travel-orders') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>My Travel Orders</span>
                </a>
                @endif
                @if (auth()->user()->is_admin)
                <a href="{{ route('role-management') }}" class="nav-item {{ Request::routeIs('role-management') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Role Management</span>
                </a>
                @endif
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">U</div>
                        <div>
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-white focus:outline-none">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/table-filters.js') }}"></script>
    @stack('scripts')
</body>
</html>