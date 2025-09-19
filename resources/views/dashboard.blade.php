@extends('layout.app')

@php
    // Define status options for the filter
    $statusOptions = [
        'For Recommendation' => 'For Recommendation',
        'For Approval' => 'For Approval',
        'Approved' => 'Approved',
        'Disapproved' => 'Disapproved',
        'Cancelled' => 'Cancelled',
        'Completed' => 'Completed',
    ];
@endphp

@section('content')
    @if (isset($showSignatureAlert) && $showSignatureAlert)
        <!-- Signature Required Modal -->
        <div id="signatureRequiredModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-signature text-blue-500 text-2xl mr-3"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">Signature Required</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <p class="text-gray-700 mb-4">Before you can proceed, you need to upload your digital signature.
                            This signature will be used to sign your travel orders.</p>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Important:</strong> Your signature must be your official handwritten
                                        signature. Please sign on a white paper and upload a clear image in .PNG format with
                                        transparent background or draw your signature using a digital signature tool.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden logout form -->
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        <!-- User Agreement Modal -->
                        <div id="userAgreementModal"
                            class="fixed inset-0 bg-black bg-opacity-60 items-center justify-center z-60 hidden"
                            role="dialog" aria-modal="true" aria-labelledby="agreementTitle">
                            <div class="bg-white rounded-lg shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
                                <!-- Alert Header (severity emphasis) -->
                                <div class="px-6 py-4 border-b bg-red-50 sticky top-0 z-10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                                            <h3 id="agreementTitle" class="text-xl font-semibold text-gray-900">Important:
                                                Digital Signature User Agreement</h3>
                                        </div>
                                        <button type="button" id="closeAgreementModal"
                                            class="text-gray-500 hover:text-gray-700" aria-label="Close">
                                            <i class="fas fa-times text-xl"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Scrollable Content -->
                                <div id="agreementContent" class="p-6 overflow-y-auto"
                                    style="max-height: calc(90vh - 128px);">
                                    <div class="prose prose-sm max-w-none">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Purpose and Scope</h4>
                                        <p class="text-gray-700 mb-4">
                                            Your digital signature is a legal representation of your identity and will be
                                            used to authorize travel orders. <strong>This e‑signature is strictly and
                                                exclusively for use within the DENR Travel Order Information System (TOIS)
                                                and will not be used for any other application or purpose.</strong>
                                        </p>

                                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                                            <p class="text-yellow-800 text-sm">
                                                Please read the following carefully. Proceeding indicates your
                                                acknowledgment and agreement.
                                            </p>
                                        </div>

                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Terms and Conditions</h4>
                                        <ol class="list-decimal pl-5 space-y-3 text-sm text-gray-800">
                                            <li>
                                                <strong>Legal Binding:</strong> Your digital signature has the same legal
                                                effect as a handwritten signature.
                                            </li>
                                            <li>
                                                <strong>Authorized Use (Exclusivity):</strong> This signature will be used
                                                <strong>only</strong> within the DENR TOIS to sign travel orders and
                                                directly related TOIS documents. It will <strong>not</strong> be shared
                                                with, exported to, or utilized by any other system or for any other purpose.
                                            </li>
                                            <li>
                                                <strong>Security:</strong> Your signature is stored securely and used solely
                                                for its intended purpose.
                                            </li>
                                            <li>
                                                <strong>Accuracy:</strong> You confirm the signature you provide is your
                                                official handwritten signature.
                                            </li>
                                            <li>
                                                <strong>Prohibited Use:</strong> Do not provide another person’s signature
                                                or authorize use outside TOIS. Suspected misuse may result in access
                                                restrictions and administrative action consistent with DENR policies.
                                            </li>
                                            <li>
                                                <strong>Record Keeping:</strong> Actions performed with your e‑signature may
                                                be logged for audit and compliance.
                                            </li>
                                        </ol>

                                        <div class="mt-6 flex items-start bg-blue-50 border border-blue-100 rounded-md p-3">
                                            <input id="agreeExclusive" type="checkbox"
                                                class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                                            <label for="agreeExclusive" class="ml-3 text-sm text-blue-900">
                                                I acknowledge and agree that my e‑signature will be used
                                                <strong>exclusively</strong> within the DENR Travel Order Information System
                                                (TOIS) for travel orders and directly related TOIS documents.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sticky Footer Actions -->
                                <div class="px-6 py-4 bg-gray-50 border-t sticky bottom-0">
                                    <div class="flex items-center justify-end space-x-3">
                                        <button type="button" id="cancelBtn"
                                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                            Cancel
                                        </button>
                                        <button type="button" id="acceptBtn"
                                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 opacity-50 cursor-not-allowed"
                                            disabled>
                                            I Understand and Agree
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-500">You can also upload your signature later from the signature
                                menu.</p>
                        </div>

                        <!-- Secondary Confirmation Modal -->
                        <div id="confirmAgreementModal"
                            class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-[61] hidden"
                            role="dialog" aria-modal="true" aria-labelledby="confirmAgreementTitle">
                            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
                                <div class="px-5 py-4 border-b bg-yellow-50">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-yellow-600 text-xl mr-2"></i>
                                        <h4 id="confirmAgreementTitle" class="text-lg font-semibold text-gray-900">Confirm
                                            Acceptance</h4>
                                    </div>
                                </div>
                                <div class="px-5 py-4 text-sm text-gray-700">
                                    <p>Please confirm that you understand your e‑signature will be used
                                        <strong>exclusively</strong> within DENR TOIS for travel orders and you agree to the
                                        terms stated.
                                    </p>
                                </div>
                                <div class="px-5 py-4 bg-gray-50 border-t flex items-center justify-end space-x-2">
                                    <button type="button" id="confirmNo"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">No,
                                        Go Back</button>
                                    <button type="button" id="confirmYes"
                                        class="px-4 py-2 border border-transparent rounded-md text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Yes,
                                        I Agree</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button type="button" id="openAgreementModal"
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-upload mr-2"></i> Upload/Draw Signature Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('userAgreementModal');
                const openModalBtn = document.getElementById('openAgreementModal');
                const closeModalBtn = document.getElementById('closeAgreementModal');
                const cancelBtn = document.getElementById('cancelBtn');
                const acceptBtn = document.getElementById('acceptBtn');
                const agreeExclusive = document.getElementById('agreeExclusive');
                const content = document.getElementById('agreementContent');
                const confirmModal = document.getElementById('confirmAgreementModal');
                const confirmYes = document.getElementById('confirmYes');
                const confirmNo = document.getElementById('confirmNo');

                function openModal() {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                    // Reset button state each time it opens
                    acceptBtn.disabled = true;
                    acceptBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    if (agreeExclusive) agreeExclusive.checked = false;
                    content.scrollTop = 0;
                }

                function logoutUser() {
                    const form = document.getElementById('logoutForm');
                    if (form) form.submit();
                }

                function closeModal() {
                    // Any attempt to close the agreement forces logout
                    logoutUser();
                }

                function openConfirm() {
                    confirmModal.classList.remove('hidden');
                    confirmModal.classList.add('flex');
                }

                function closeConfirm() {
                    confirmModal.classList.add('hidden');
                    confirmModal.classList.remove('flex');
                }

                function isScrolledToBottom(el) {
                    const threshold = 5; // px tolerance
                    return el.scrollTop + el.clientHeight >= el.scrollHeight - threshold;
                }

                function updateAcceptState() {
                    const scrolled = isScrolledToBottom(content);
                    const checked = agreeExclusive ? agreeExclusive.checked : true;
                    if (scrolled && checked) {
                        acceptBtn.disabled = false;
                        acceptBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        acceptBtn.disabled = true;
                        acceptBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }

                openModalBtn.addEventListener('click', openModal);
                closeModalBtn.addEventListener('click', closeModal);
                cancelBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });

                content.addEventListener('scroll', updateAcceptState);
                if (agreeExclusive) agreeExclusive.addEventListener('change', updateAcceptState);

                acceptBtn.addEventListener('click', function() {
                    if (!acceptBtn.disabled) {
                        openConfirm();
                    }
                });

                // 'No, Go Back' should only close the confirmation modal
                confirmNo.addEventListener('click', closeConfirm);
                confirmYes.addEventListener('click', function() {
                    // proceed to signature page
                    window.location.href = '{{ route('signature.index') }}';
                });

                // Force logout if user presses Escape to dismiss
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        logoutUser();
                    }
                });
            });
        </script>
    @endpush

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">Dashboard</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @if (isset($showSignatureAlert) && $showSignatureAlert)
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                You need to upload your signature before you can submit travel orders.
                                <a href="{{ route('signature.index') }}"
                                    class="font-medium underline text-red-700 hover:text-red-600">
                                    Click here to upload your signature now.
                                </a>
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button"
                                    class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none';">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dashboard Content -->
            <div id="dashboardContent" class="page-content">
                <!-- Stats Cards -->
                <x-dashboard.stats-cards :totalTravelOrders="$totalTravelOrders" :pendingRequests="$pendingRequests" :completedRequests="$completedRequests" :cancelledRequests="$cancelledRequests" />


                <!-- Recent Travel Orders -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                            <div class="sm:mb-0">
                                <h3 class="text-base sm:text-lg md:text-lg font-bold text-gray-800">Recent Travel Orders
                                </h3>
                                <p class="hidden md:block text-xs sm:text-sm text-gray-600 mt-0.5">Track and manage all
                                    travel order requests</p>
                            </div>

                            <div class="w-full sm:w-auto">
                                <x-dashboard.search-filter :statuses="$statusOptions" :currentStatus="request('status')" :searchQuery="request('search')" />
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto -mx-2 sm:mx-0 shadow-sm">
                        <div class="inline-block min-w-full align-middle">
                            <div class="bg-white border border-gray-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <x-dashboard.table-header :isAdmin="auth()->user()->is_admin" />
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse($travelOrders as $order)
                                            <tr
                                                class="relative group hover:bg-gray-50 transition-colors duration-150 ease-in-out border-b border-gray-100 last:border-0">
                                                <x-dashboard.travel-order-row :order="$order" :isAdmin="auth()->user()->is_admin" />
                                            @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}"
                                                    class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center space-y-4">
                                                        <svg class="w-16 h-16 text-gray-300" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.5"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                            </path>
                                                        </svg>
                                                        <div class="space-y-1">
                                                            <p class="text-base font-medium text-gray-700">No travel orders
                                                                found</p>
                                                            <p class="text-sm text-gray-500">Get started by creating a new
                                                                travel order</p>
                                                        </div>
                                                        @if (auth()->user()->can('create', App\Models\TravelOrder::class))
                                                            <a href="{{ route('travel-orders.create') }}"
                                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                                New Travel Order
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <x-dashboard.pagination :paginator="$travelOrders" />
                </div>
            </div>

            <!-- Other pages will be loaded here -->
            <div id="otherPages" class="page-content hidden"></div>

        </main>

        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                        Resources. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    </div>

    <!-- Include Travel Order Modal Component -->
    @include('components.travel-order.travel-order-modal')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js"></script>
    @endpush
@endsection
