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
            <nav class="mt-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                @if (!auth()->user()->is_admin)
                <!-- Regular User Menu -->
                <a href="{{ route('my-travel-orders') }}" class="nav-item {{ Request::routeIs('my-travel-orders') ? 'active' : '' }}">
                    <i class="fas fa-passport"></i>
                    <span>My Travel Orders</span>
                </a>
                <a href="{{ route('travel-orders.create') }}" class="nav-item {{ Request::routeIs('travel-orders.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Travel Order</span>
                </a>
                @endif

                @if (auth()->user()->is_admin)
                <!-- Admin Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Administration
                </div>

                <!-- Travel Order Management -->
                <a href="{{ route('all-travel-orders') }}" class="nav-item">
                    <i class="fas fa-clipboard-list"></i>
                    <span>All Travel Orders</span>
                </a>

                <!-- Role & Permission Management -->
                <a href="" class="nav-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Role Management</span>
                </a>

                <!-- Status Management -->
                <a href="" class="nav-item">
                    <i class="fas fa-tasks"></i>
                    <span>Status Management</span>
                </a>

                <!-- Reports -->
                <a href="" class="nav-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports & Analytics</span>
                </a>
                @endif

                <!-- Common Menu Items -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Resources
                </div>
                
                <!-- User Profile -->
                <a href="{{ route('profile.edit') }}" class="nav-item {{ Request::routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>

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