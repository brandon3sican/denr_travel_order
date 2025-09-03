<!-- Sidebar -->
<div id="sidebar" class="w-64 bg-gray-800 text-white">
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center">
            <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="h-8 w-8">
            <h1 class="text-xl font-bold ml-3">DENR:TOIS</h1>
        </div>
    </div>
    <nav class="mt-4">
        <!-- Main Section -->
        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Main</div>
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i>
            <span>Dashboard</span>
        </a>

        @if (!auth()->user()->is_admin)
            <!-- Travel Orders Section -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Travel Orders</div>
            <a href="{{ route('my-orders') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('my-orders') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-suitcase w-5 text-center mr-3"></i>
                <span>My Travel Orders</span>
            </a>
            <a href="{{ route('travel-orders.create') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('travel-orders.create') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-plus-circle w-5 text-center mr-3"></i>
                <span>New Travel Order</span>
            </a>
        @endif

        @php
            $user = auth()->user();
            $hasApprovalRole = $user->travelOrderRoles->whereIn('id', [3, 4, 5])->isNotEmpty();
        @endphp

        @if ($hasApprovalRole && ($user->employee && $user->employee->signature))
            <!-- Approvals Section -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Approvals</div>
            @if ($user->travelOrderRoles->whereIn('id', [3, 5])->isNotEmpty())
                <a href="{{ route('for-recommendation') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('for-recommendation') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clipboard-check w-5 text-center mr-3"></i>
                    <span>For Recommendation</span>
                </a>
            @endif
            @if ($user->travelOrderRoles->whereIn('id', [4, 5])->isNotEmpty())
                <a href="{{ route('for-approval') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('for-approval') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clipboard-list w-5 text-center mr-3"></i>
                    <span>For Approval</span>
                </a>
            @endif
        @endif

        @if (auth()->user()->is_admin)
            <!-- Admin Section -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Management</div>
            <a href="{{ route('travel-orders.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('travel-orders.index') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-clipboard-list w-5 text-center mr-3"></i>
                <span>Travel Orders</span>
            </a>
            <a href="{{ route('role-management.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('role-management*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-user-shield w-5 text-center mr-3"></i>
                <span>Role Management</span>
            </a>
            <a href="{{ route('status-management.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('status-management.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-tasks w-5 text-center mr-3"></i>
                <span>Status Management</span>
            </a>

            <!-- Reports Section -->
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Reports</div>
            <a href="{{ route('reports.approval-metrics') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('reports.approval-metrics') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-clipboard-check w-5 text-center mr-3"></i>
                <span>Approval Metrics</span>
            </a>
            <a href="{{ route('reports.travel-volume') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('reports.travel-volume') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-chart-line w-5 text-center mr-3"></i>
                <span>Travel Volume</span>
            </a>
            <a href="{{ route('reports.employee-travel') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('reports.employee-travel') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-users w-5 text-center mr-3"></i>
                <span>Employee Travel</span>
            </a>
            <a href="{{ route('reports.department') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('reports.department') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-building w-5 text-center mr-3"></i>
                <span>Department Reports</span>
            </a>
        @endif

        <!-- User Section -->
        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">User</div>
        <a href="#" id="profileLink" class="flex items-center px-4 py-3 text-gray-300">
            <i class="fas fa-user-cog w-5 text-center mr-3"></i>
            <span>My Profile</span>
        </a>
        @if (!auth()->user()->is_admin)
            <a href="{{ route('signature.index') }}" class="flex items-center px-4 py-3 text-gray-300 {{ request()->routeIs('signature.index') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-signature w-5 text-center mr-3"></i>
                <span>Signature</span>
            </a>
        @endif

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
                        <button onclick="closeModal()"
                            class="text-gray-400 hover:bg-gray-100 p-2 rounded-full transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Profile Content -->
                    <div class="p-6 bg-gray-800 rounded-b-lg">
                        @php
                            $user = Auth::user();
                            $employee = $user->employee; // Get the employee relationship
                            $firstName = $employee->first_name ?? ($user->first_name ?? '');
                            $middleName = $employee->middle_name ?? '';
                            $lastName = $employee->last_name ?? ($user->last_name ?? '');
                            $suffix = $employee->suffix ?? '';
                            $email = $employee->email ?? ($user->email ?? '');
                            $position = $employee->position_name ?? ($user->position_name ?? 'N/A');
                            $assignment = $employee->assignment_name ?? ($user->assignment_name ?? 'N/A');
                            $divSecUnit = $employee->div_sec_unit ?? ($user->div_sec_unit ?? 'N/A');
                            $fullName = trim(
                                "$firstName " .
                                    ($middleName ? $middleName . ' ' : '') .
                                    "$lastName" .
                                    ($suffix ? ' ' . $suffix : ''),
                            );
                        @endphp

                        <!-- Profile Header -->
                        <div class="text-center mb-6">
                            <div
                                class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-3xl text-blue-600 font-bold mb-3 border-4 border-white shadow-lg">
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
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">
                                            Assignment</p>
                                        <p class="text-sm text-gray-800">{{ $assignment }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">
                                            Division/Section/Unit</p>
                                        <p class="text-sm text-gray-800">{{ $divSecUnit }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-2">Contact
                                    Information</p>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <i class="fas fa-envelope text-blue-400 mt-0.5 mr-2"></i>
                                        <span class="text-sm text-gray-700">{{ $email }}</span>
                                    </div>
                                    @if ($employee && $employee->contact_number)
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
