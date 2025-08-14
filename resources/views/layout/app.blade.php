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
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-black' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                @if (!auth()->user()->is_admin)
                <!-- Regular User Menu -->
                <a href="{{ route('my-travel-orders') }}" class="nav-item {{ request()->routeIs('my-travel-orders') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-passport"></i>
                    <span>My Travel Orders</span>
                </a>
                <a href="{{ route('travel-orders.create') }}" class="nav-item {{ request()->routeIs('travel-orders.create') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
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
                <a href="{{ route('all-travel-orders') }}" class="nav-item {{ request()->routeIs('all-travel-orders') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>All Travel Orders</span>
                </a>

                <!-- Role & Permission Management -->
                <a href="{{ route('role-management') }}" class="nav-item {{ request()->routeIs('role-management') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Role Management</span>
                </a>

                <!-- Status Management -->
                <a href="{{ route('status-management.index') }}" class="nav-item {{ request()->routeIs('status-management.*') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Status Management</span>
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'bg-gray-700 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports & Analytics</span>
                </a>
                @endif
                
                <!-- User Profile -->
                <a href="" id="profileLink" class="nav-item">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>

                <!-- Profile Modal -->
                <div id="profileModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 transition-opacity">
                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" 
                             id="modalContent">
                            <!-- Header -->
                            <div class="px-6 pt-6 pb-2 flex justify-between items-center border-b bg-gray-800">
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Employee Profile</h3>
                                </div>
                                <button onclick="closeModal()" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            
                            <!-- Profile Content -->
                            <div class="p-6">
                                @php
                                    $user = Auth::user();
                                    $employee = $user->employee; // Get the employee relationship
                                    $firstName = $employee->first_name ?? $user->first_name ?? '';
                                    $middleName = $employee->middle_name ?? '';
                                    $lastName = $employee->last_name ?? $user->last_name ?? '';
                                    $suffix = $employee->suffix ?? '';
                                    $email = $employee->email ?? $user->email ?? '';
                                    $position = $employee->position_name ?? $user->position_name ?? 'N/A';
                                    $assignment = $employee->assignment_name ?? $user->assignment_name ?? 'N/A';
                                    $divSecUnit = $employee->div_sec_unit ?? $user->div_sec_unit ?? 'N/A';
                                    $fullName = trim("$firstName " . ($middleName ? $middleName . ' ' : '') . "$lastName" . ($suffix ? ' ' . $suffix : ''));
                                @endphp
                                
                                <!-- Profile Header -->
                                <div class="text-center mb-6">
                                    <div class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-3xl text-blue-600 font-bold mb-3 border-4 border-white shadow-lg">
                                        {{ $firstName ? strtoupper(substr($firstName, 0, 1)) : 'U' }}
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $fullName }}</h4>
                                    <p class="text-sm text-blue-600 font-medium mt-1">{{ $position }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $email }}</p>
                                </div>

                                <!-- Profile Details -->
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Assignment</p>
                                                <p class="text-sm text-gray-800">{{ $assignment }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Division/Section/Unit</p>
                                                <p class="text-sm text-gray-800">{{ $divSecUnit }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Contact Information</p>
                                        <div class="space-y-2">
                                            <div class="flex items-start">
                                                <i class="fas fa-envelope text-blue-400 mt-0.5 mr-2"></i>
                                                <span class="text-sm text-gray-700">{{ $email }}</span>
                                            </div>
                                            @if($employee && $employee->contact_number)
                                            <div class="flex items-start">
                                                <i class="fas fa-phone-alt text-blue-400 mt-0.5 mr-2"></i>
                                                <span class="text-sm text-gray-700">{{ $employee->contact_number }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Wait for the DOM to be fully loaded
                    document.addEventListener('DOMContentLoaded', function() {
                        // Get modal elements
                        const modal = document.getElementById('profileModal');
                        const modalContent = document.getElementById('modalContent');
                        const profileLink = document.getElementById('profileLink');
                        const closeButton = document.querySelector('[onclick="closeModal()"]');

                        // Show modal with animation
                        function showModal() {
                            modal.classList.remove('hidden');
                            // Force reflow to enable transition
                            void modal.offsetWidth;
                            modalContent.classList.remove('opacity-0', 'scale-95');
                            modalContent.classList.add('opacity-100', 'scale-100');
                        }

                        // Close modal with animation
                        function closeModal() {
                            modalContent.classList.remove('opacity-100', 'scale-100');
                            modalContent.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => {
                                modal.classList.add('hidden');
                            }, 200);
                        }

                        // Event Listeners
                        if (profileLink) {
                            profileLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                showModal();
                            });
                        }

                        if (closeButton) {
                            closeButton.addEventListener('click', closeModal);
                        }

                        // Close when clicking outside modal content
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                closeModal();
                            }
                        });

                        // Close with Escape key
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                                closeModal();
                            }
                        });
                    });

                    // Make closeModal available globally for inline onclick handlers
                    window.closeModal = function() {
                        const modal = document.getElementById('profileModal');
                        const modalContent = document.getElementById('modalContent');
                        if (modal && modalContent) {
                            modalContent.classList.remove('opacity-100', 'scale-100');
                            modalContent.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => {
                                modal.classList.add('hidden');
                            }, 200);
                        }
                    };
                </script>

            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @php
                            $user = Auth::user();
                            $employee = $user->employee;
                            $userInitial = $employee && $employee->first_name 
                                ? strtoupper(substr($employee->first_name, 0, 1))
                                : ($user->first_name ? strtoupper(substr($user->first_name, 0, 1)) : 'U');
                        @endphp
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">{{ $userInitial }}</div>
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
    <script>
        // Close modal when clicking outside
        document.getElementById('profileModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('profileModal').classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>