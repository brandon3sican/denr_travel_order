<!-- Mobile Menu Button (Hamburger) -->
<div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-gray-800 p-6 flex justify-between items-center">
    <div class="flex items-center">
        <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="h-8 w-8">
        <h1 class="text-xl font-bold ml-3 text-white">DENR:TOIS</h1>
    </div>
    <button id="mobile-menu-button" type="button" class="text-gray-300 hover:text-white focus:outline-none"
        aria-label="Toggle menu">
        <svg id="menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
</div>

<!-- Mobile Menu Modal -->
<div id="mobile-menu-modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 bg-black bg-opacity-75"></div>

    <!-- Menu Content -->
    <div
        class="fixed inset-y-0 right-0 w-4/5 max-w-sm bg-gray-800 text-white shadow-lg transform transition-transform duration-300 ease-in-out translate-x-full">
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
            <h2 class="text-xl font-bold">Menu</h2>
            <button id="close-menu" class="text-gray-300 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto h-[calc(100%-60px)]">
            <!-- Sidebar content will be moved here by JavaScript -->
        </div>
    </div>
</div>

<!-- Sidebar (Desktop) -->
<div id="sidebar"
    class="hidden lg:block lg:static lg:translate-x-0 w-80 bg-gray-800 text-white z-40 h-screen overflow-y-auto transition-all duration-200 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center justify-center">
            <img src="{{ asset('images/denr-logo.png') }}" alt="DENR Logo" class="h-6 w-6">
            <h1 class="text-base font-bold ml-1">DENR:TOIS</h1>
        </div>
    </div>
    <nav class="py-0.5">
        <!-- Main Section -->
        <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Main</div>
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-1.5 text-sm text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            <i class="fas fa-tachometer-alt w-4 text-center text-gray-400 text-xs"></i>
            <span class="ml-2 text-lg">Dashboard</span>
        </a>

        @if (!auth()->user()->is_admin)
            <!-- Travel Orders Section -->
            <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Travel</div>
            <a href="{{ route('my-orders') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('my-orders') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-suitcase w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">My Travel Orders</span>
            </a>
            <a href="{{ route('travel-orders.create') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('travel-orders.create') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-plus-circle w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">New Travel Order</span>
            </a>
        @endif

        @php
            $user = auth()->user();
            $hasApprovalRole = $user->travelOrderRoles->whereIn('id', [3, 4, 5])->isNotEmpty();
        @endphp

        @if ($hasApprovalRole && ($user->employee && $user->employee->signature))
            <!-- Approvals Section -->
            <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Approvals</div>
            @if ($user->travelOrderRoles->whereIn('id', [3, 5])->isNotEmpty())
                <a href="{{ route('for-recommendation') }}"
                    class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('for-recommendation') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clipboard-check w-4 text-center text-gray-400 text-xs"></i>
                    <span class="ml-2 text-lg">For Recommendation</span>
                </a>
            @endif
            @if ($user->travelOrderRoles->whereIn('id', [4, 5])->isNotEmpty())
                <a href="{{ route('for-approval') }}"
                    class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('for-approval') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-clipboard-list w-4 text-center text-gray-400 text-xs"></i>
                    <span class="ml-2 text-lg">For Approval</span>
                </a>
            @endif
            <a href="{{ route('history') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('travel-orders.approval-history') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-clipboard-list w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">Approval History</span>
            </a>
        @endif

        @if (auth()->user()->is_admin)
            <!-- Admin Section -->
            <!-- Travel Order -->
            <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Travel Orders
            </div>
            <a href="{{ route('travel-orders.index') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('travel-orders.index') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-clipboard-list w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">All Travel Orders</span>
            </a>
            <a href="{{ route('history') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('history') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-clipboard-list w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">All Approvals History</span>
            </a>

            <!-- Management -->
            <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Management</div>
            <a href="{{ route('role-management.index') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('role-management*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-user-shield w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">Roles Management</span>
            </a>
            <a href="{{ route('status-management.index') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('status-management.*') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-tasks w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">Status Management</span>
            </a>

            <a href="{{ route('signature-management.index') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('signature-management.index') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-signature w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">Signature Management</span>
            </a>

            <!-- Reports Section -->
            {{-- <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Reports</div>
            <a href="{{ route('reports.approval-metrics') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('reports.approval-metrics') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-chart-pie w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2">Metrics</span>
            </a>
            <a href="{{ route('reports.travel-volume') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('reports.travel-volume') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-chart-line w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2">Volume</span>
            </a>
            <a href="{{ route('reports.employee-travel') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('reports.employee-travel') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-users w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2">Employees</span>
            </a>
            <a href="{{ route('reports.department') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('reports.department') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-building w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2">Departments</span>
            </a> --}}
        @endif

        <!-- User Section -->
        <div class="px-3 py-1 mt-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Account</div>
        <a href="#" id="profileLink"
            class="js-open-profile flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5">
            <i class="fas fa-user-cog w-4 text-center text-gray-400 text-xs"></i>
            <span class="ml-2 text-lg">My Profile</span>
        </a>
        @if (!auth()->user()->is_admin)
            <a href="{{ route('signature.index') }}"
                class="flex items-center px-3 py-1.5 text-xs text-gray-300 hover:bg-gray-700/60 rounded mx-1.5 mb-0.5 {{ request()->routeIs('signature.index') ? 'bg-gray-700' : '' }}">
                <i class="fas fa-signature w-4 text-center text-gray-400 text-xs"></i>
                <span class="ml-2 text-lg">Signature</span>
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
                                class="h-20 w-20 sm:h-24 sm:w-24 mx-auto rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-2xl sm:text-3xl text-blue-600 font-bold mb-3 border-4 border-white shadow-lg">
                                {{ $firstName ? strtoupper(substr($firstName, 0, 1)) : 'U' }}
                            </div>
                            <h4 class="text-lg sm:text-xl font-semibold text-white">{{ $fullName }}</h4>
                            <p class="text-sm text-blue-300 font-medium mt-1">{{ $position }}</p>
                            <p class="text-xs text-gray-100 mt-1 break-words">{{ $email }}</p>
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
                const closeButton = document.querySelector('[onclick="closeModal()"]');
                // Mobile modal elements
                const mobileProfileModal = document.getElementById('profileModalMobile');
                const mobileModalContent = document.getElementById('modalContentMobile');

                // Show modal with animation
                function showModal() {
                    modal.classList.remove('hidden');
                    // Force reflow to enable transition
                    void modal.offsetWidth;
                    modalContent.classList.remove('opacity-0', 'scale-95');
                    modalContent.classList.add('opacity-100', 'scale-100');
                }

                // Show mobile modal with slide-up animation
                function showMobileProfileModal() {
                    if (!mobileProfileModal || !mobileModalContent) return;
                    mobileProfileModal.classList.remove('hidden');
                    // Force reflow
                    void mobileModalContent.offsetWidth;
                    mobileModalContent.classList.remove('translate-y-4', 'opacity-0');
                    mobileModalContent.classList.add('translate-y-0', 'opacity-100');
                    mobileProfileModal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                }

                // Close modal with animation
                function closeModal() {
                    modalContent.classList.remove('opacity-100', 'scale-100');
                    modalContent.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 200);
                }

                function closeMobileProfileModal() {
                    if (!mobileProfileModal || !mobileModalContent) return;
                    mobileModalContent.classList.remove('translate-y-0', 'opacity-100');
                    mobileModalContent.classList.add('translate-y-4', 'opacity-0');
                    setTimeout(() => {
                        mobileProfileModal.classList.add('hidden');
                        mobileProfileModal.setAttribute('aria-hidden', 'true');
                        document.body.classList.remove('overflow-hidden');
                    }, 200);
                }

                // Event Listeners (delegated) for profile open, works in sidebar and mobile menu
                document.addEventListener('click', function(e) {
                    const trigger = e.target.closest('.js-open-profile');
                    if (trigger) {
                        e.preventDefault();
                        // If coming from mobile menu, close it first if present
                        const mobileMenuModal = document.getElementById('mobile-menu-modal');
                        if (mobileMenuModal && !mobileMenuModal.classList.contains('hidden')) {
                            // Reuse toggleMobileMenu if available
                            const panel = mobileMenuModal.querySelector('.transform');
                            if (panel) {
                                panel.classList.add('translate-x-full');
                                setTimeout(() => {
                                    mobileMenuModal.classList.add('hidden');
                                }, 300);
                                document.body.classList.remove('overflow-hidden');
                            }
                            // Delay opening profile modal until drawer is fully closed
                            setTimeout(() => {
                                if (window.innerWidth < 1024) {
                                    showMobileProfileModal();
                                } else {
                                    showModal();
                                }
                            }, 320);
                        } else {
                            // Open mobile or desktop modal depending on viewport
                            if (window.innerWidth < 1024) { // lg breakpoint
                                showMobileProfileModal();
                            } else {
                                showModal();
                            }
                        }
                    }
                });

                if (closeButton) {
                    closeButton.addEventListener('click', closeModal);
                }

                // Close when clicking outside modal content
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });

                // Close mobile profile modal when clicking on backdrop
                if (mobileProfileModal) {
                    mobileProfileModal.addEventListener('click', function(e) {
                        if (e.target === mobileProfileModal) {
                            closeMobileProfileModal();
                        }
                    });
                }

                // Close with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (modal && !modal.classList.contains('hidden')) {
                            closeModal();
                        }
                        if (mobileProfileModal && !mobileProfileModal.classList.contains('hidden')) {
                            closeMobileProfileModal();
                        }
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

            // Expose close for mobile modal
            window.closeMobileProfileModal = function() {
                const mobileProfileModal = document.getElementById('profileModalMobile');
                const mobileModalContent = document.getElementById('modalContentMobile');
                if (mobileProfileModal && mobileModalContent) {
                    mobileModalContent.classList.remove('translate-y-0', 'opacity-100');
                    mobileModalContent.classList.add('translate-y-4', 'opacity-0');
                    setTimeout(() => {
                        mobileProfileModal.classList.add('hidden');
                    }, 200);
                }
            };
        </script>

    </nav>
    @include('components.header')
</div>

<!-- Mobile Profile Modal (separate instance for small screens) placed OUTSIDE hidden sidebar -->
<div id="profileModalMobile" class="fixed inset-0 z-[9999] hidden lg:hidden" aria-hidden="true"
    style="z-index: 99999;">
    <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900 bg-opacity-70"
        style="z-index: 99999;">
        <div class="bg-white w-full sm:w-[90%] max-w-md rounded-t-2xl sm:rounded-2xl shadow-2xl transform transition-all duration-300 translate-y-4 opacity-0"
            style="z-index: 100000;" id="modalContentMobile">
            <!-- Header -->
            <div class="px-5 pt-5 pb-3 flex justify-between items-center border-b bg-gray-800 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-semibold text-white">Employee Profile</h3>
                    <p class="text-xs text-gray-400">View your profile details.</p>
                </div>
                <button onclick="closeMobileProfileModal()" class="text-gray-400 hover:bg-gray-100 p-2 rounded-full">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>
            <!-- Content -->
            <div class="p-5 bg-gray-800 rounded-b-2xl">
                @php
                    $user = Auth::user();
                    $employee = $user->employee;
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

                <!-- Header -->
                <div class="text-center mb-5">
                    <div
                        class="h-20 w-20 mx-auto rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-2xl text-blue-600 font-bold mb-3 border-4 border-white shadow-lg">
                        {{ $firstName ? strtoupper(substr($firstName, 0, 1)) : 'U' }}
                    </div>
                    <h4 class="text-lg font-semibold text-white">{{ $fullName }}</h4>
                    <p class="text-xs text-blue-300 mt-0.5">{{ $position }}</p>
                    <p class="text-xs text-gray-100 mt-1 break-words">{{ $email }}</p>
                </div>

                <!-- Details -->
                <div class="space-y-3">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Assignment</p>
                        <p class="text-sm text-gray-800">{{ $assignment }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">
                            Division/Section/Unit</p>
                        <p class="text-sm text-gray-800">{{ $divSecUnit }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMenuButton = document.getElementById('close-menu');
        const mobileMenuModal = document.getElementById('mobile-menu-modal');
        const mobileMenuContent = mobileMenuModal?.querySelector('.overflow-y-auto');
        const sidebar = document.getElementById('sidebar');

        // Move sidebar content to mobile menu
        if (mobileMenuContent && sidebar) {
            const sidebarContent = sidebar.innerHTML;
            mobileMenuContent.innerHTML = sidebarContent;
            // Remove any cloned profile modal to avoid duplicate IDs
            const clonedProfileModal = mobileMenuContent.querySelector('#profileModal');
            if (clonedProfileModal) {
                clonedProfileModal.remove();
            }
            // Also remove any cloned mobile profile modal to avoid duplicate IDs
            const clonedMobileProfileModal = mobileMenuContent.querySelector('#profileModalMobile');
            if (clonedMobileProfileModal) {
                clonedMobileProfileModal.remove();
            }
        }

        function toggleMobileMenu(show) {
            const menuPanel = mobileMenuModal?.querySelector('.transform');
            if (!menuPanel) return;

            if (show) {
                mobileMenuModal.classList.remove('hidden');
                setTimeout(() => {
                    menuPanel.classList.remove('translate-x-full');
                }, 10);
                document.body.classList.add('overflow-hidden');
            } else {
                menuPanel.classList.add('translate-x-full');
                setTimeout(() => {
                    mobileMenuModal.classList.add('hidden');
                }, 300);
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Open mobile menu
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => toggleMobileMenu(true));
        }

        // Close mobile menu
        if (closeMenuButton) {
            closeMenuButton.addEventListener('click', () => toggleMobileMenu(false));
        }

        // Close when clicking on backdrop
        const backdrop = document.getElementById('mobile-menu-backdrop');
        if (backdrop) {
            backdrop.addEventListener('click', () => toggleMobileMenu(false));
        }

        // Close menu when clicking on a navigation link
        document.addEventListener('click', (e) => {
            if (e.target.closest('#mobile-menu-modal a')) {
                toggleMobileMenu(false);
            }
        });

        // Handle window resize
        function handleResize() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                toggleMobileMenu(false);
            }
        }

        window.addEventListener('resize', handleResize);
    });
</script>
