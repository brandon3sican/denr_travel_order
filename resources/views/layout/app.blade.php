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
                <!-- Admin Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Main
                </div>

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-tachometer-alt text-blue-400 mr-3 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                @if (!auth()->user()->is_admin)
                <!-- Regular User Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Travel Orders
                </div>

                <!-- My Travel Orders -->
                <a href="{{ route('my-travel-orders') }}" class="nav-item {{ request()->routeIs('my-travel-orders') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-suitcase text-green-400 mr-3 w-5 text-center"></i>
                    <span>My Travel Orders</span>
                </a>

                <!-- New Travel Order -->
                <a href="{{ route('travel-orders.create') }}" class="nav-item {{ request()->routeIs('travel-orders.create') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-plus-circle text-yellow-400 mr-3 w-5 text-center"></i>
                    <span>New Travel Order</span>
                </a>
                @endif

                @php
                    $user = auth()->user();
                    $hasApprovalRole = $user->travelOrderRoles->whereIn('id', [3, 4, 5])->isNotEmpty();
                @endphp
                
                @if ($hasApprovalRole)
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Approvals
                </div>
                @if ($user->travelOrderRoles->whereIn('id', [3, 5])->isNotEmpty())
                <a href="" class="nav-item {{ request()->routeIs('approvals.recommendation') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-clipboard-check text-yellow-400 mr-3 w-5 text-center"></i>
                    <span>For Recommendation</span>
                </a>
                @endif
                @if ($user->travelOrderRoles->whereIn('id', [4, 5])->isNotEmpty())
                <a href="" class="nav-item {{ request()->routeIs('approvals.approval') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-clipboard-list text-green-400 mr-3 w-5 text-center"></i>
                    <span>For Approval</span>
                </a>
                @endif
                @endif

                @if (auth()->user()->is_admin)
                <!-- Admin Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Travel Orders
                </div>

                <!-- Travel Order Management -->
                <a href="{{ route('all-travel-orders') }}" class="nav-item {{ request()->routeIs('all-travel-orders') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-clipboard-list text-indigo-400 mr-3 w-5 text-center"></i>
                    <span>All Travel Orders</span>
                </a>

                <!-- Role & Permission Management -->
                <a href="{{ route('role-management') }}" class="nav-item {{ request()->routeIs('role-management') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-user-shield text-purple-400 mr-3 w-5 text-center"></i>
                    <span>Role Management</span>
                </a>

                <!-- Status Management -->
                <a href="{{ route('status-management.index') }}" class="nav-item {{ request()->routeIs('status-management.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-tasks text-cyan-400 mr-3 w-5 text-center"></i>
                    <span>Status Management</span>
                </a>

                <!-- Reports Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Reports
                </div>

                <a href="{{ route('reports.approval-metrics') }}" class="nav-item {{ request()->routeIs('reports.approval-metrics') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-clipboard-check text-green-400 mr-3 w-5 text-center"></i>
                    <span>Approval Metrics</span>
                </a>

                <a href="{{ route('reports.travel-volume') }}" class="nav-item {{ request()->routeIs('reports.travel-volume') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-chart-line text-blue-400 mr-3 w-5 text-center"></i>
                    <span>Travel Volume</span>
                </a>

                <a href="{{ route('reports.employee-travel') }}" class="nav-item {{ request()->routeIs('reports.employee-travel') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-users text-purple-400 mr-3 w-5 text-center"></i>
                    <span>Employee Travel</span>
                </a>

                <a href="{{ route('reports.department') }}" class="nav-item {{ request()->routeIs('reports.department') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-building text-amber-400 mr-3 w-5 text-center"></i>
                    <span>Department Reports</span>
                </a>
                @endif

                <!-- User Menu -->
                <div class="px-4 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    User Profile
                </div>
                <!-- User Profile -->
                <a href="" id="profileLink" class="nav-item text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-user-cog text-pink-400 mr-3 w-5 text-center"></i>
                    <span>My Profile</span>
                </a>
                
                <!-- Signature -->
                <a href="{{ route('signature.index') }}" class="nav-item text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="fas fa-signature text-indigo-400 mr-3 w-5 text-center"></i>
                    <span>Signature</span>
                </a>

                <!-- Profile Modal -->
                <div id="profileModal" class="fixed inset-0 z-50 hidden overflow-y-auto rounded-xl">
                    <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 transition-opacity">
                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" 
                             id="modalContent">
                            <!-- Header -->
                            <div class="px-6 pt-6 pb-2 flex justify-between items-center border-b bg-gray-800 rounded-t-lg">
                                <div>
                                    <h3 class="text-xl font-semibold text-white">Employee Profile</h3>
                                    <p class="text-sm text-gray-400">View and manage your profile information.</p>
                                </div>
                                <button onclick="closeModal()" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition-colors">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                            
                            <!-- Profile Content -->
                            <div class="p-6 bg-gray-800 rounded-b-lg">
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
                                    <h4 class="text-xl font-semibold text-white">{{ $fullName }}</h4>
                                    <p class="text-sm text-blue-600 font-medium mt-1">{{ $position }}</p>
                                    <p class="text-xs text-gray-100 mt-1">{{ $email }}</p>
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
            @include('components.header')
        </div>
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/table-filters.js') }}"></script>

    @include('components.profile-modal')
    @stack('scripts')
</body>
</html>