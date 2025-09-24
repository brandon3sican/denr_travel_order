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
        <!-- User Agreement Modal (shown first) -->
        <div id="userAgreementModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="uaTitle">
            <div class="bg-white rounded-xl shadow-2xl w-11/12 md:w-5/6 lg:w-4/5 xl:w-3/4 2xl:w-2/3 max-w-none ring-2 ring-red-600/60" style="max-height:90vh;">
                <div class="px-6 py-4 border-b bg-red-600 text-white rounded-t-xl sticky top-0 z-10">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-white text-2xl mr-3"></i>
                        </div>
                        <div>
                            <h3 id="uaTitle" class="text-xl font-bold">Important Notice: User Agreement Required</h3>
                            <p class="text-xs text-red-100">Please review this carefully before continuing.</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex flex-col" style="max-height:calc(90vh - 72px);"> 
                    <!-- Top Alert Banner -->
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-red-500 mt-0.5 mr-3"></i>
                            <p class="text-sm text-red-800"><span class="font-semibold">This is a mandatory notice.</span> Your e‑signature and audit details are required to proceed and will be used on printable travel orders and throughout the approval process.</p>
                        </div>
                    </div>
                    <!-- Single scrollable content -->
                    <div id="uaContent" class="space-y-5 flex-1 overflow-y-auto pr-2">
                        <!-- Purpose -->
                        <section>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Purpose</h4>
                            <p class="text-gray-700">To continue using this system, you are required to <span class="font-semibold">upload or draw your digital signature</span>. Your signature will be applied to your travel order documents, used in the approval workflow, and will appear on the <span class="font-semibold">printable travel order</span> as the signature of the <span class="font-semibold">Requester</span>, <span class="font-semibold">Recommending</span>, and <span class="font-semibold">Approving</span> personnel as applicable.</p>
                        </section>

                        <!-- Data Collected -->
                        <section class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <h5 class="text-sm font-semibold text-yellow-800 mb-1">Data Collected</h5>
                            <ul class="list-disc list-inside text-sm text-yellow-800 space-y-1">
                                <li><span class="font-semibold">Signature Image</span>: Your official handwritten signature (clear .PNG with transparent background recommended) or a drawn signature using the provided tool.</li>
                                <li><span class="font-semibold">Audit Metadata</span>: Timestamp of upload, device/browser information, and signature-related actions.</li>
                            </ul>
                        </section>

                        <!-- Use and Approval -->
                        <section class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <h5 class="text-sm font-semibold text-blue-800 mb-1">Use and Approval</h5>
                            <p class="text-sm text-blue-800">Your signature and audit metadata will be used <span class="font-semibold">only within this system</span> to: (a) display your signature on <span class="font-semibold">generated and printable travel order documents</span> (including fields for <span class="font-semibold">Requester</span>, <span class="font-semibold">Recommending</span>, and <span class="font-semibold">Approving</span> personnel), (b) support the <span class="font-semibold">approval process</span> by verifying actions taken, and (c) maintain audit trails for integrity and compliance with records management policies.</p>
                        </section>

                        <!-- Retention and Consent -->
                        <section>
                            <h5 class="text-sm font-semibold text-gray-900 mb-1">Retention and Consent</h5>
                            <p class="text-sm text-gray-700">Data is retained in accordance with internal policies and applicable regulations. By proceeding, you affirm that the signature you provide is your official signature and you consent to its use, together with audit metadata, as described above.</p>
                        </section>

                        <section>
                            <div class="flex items-start">
                                <input id="uaAgreeCheckbox" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" />
                                <label for="uaAgreeCheckbox" class="ml-2 text-sm text-gray-700">I have read and understand this User Agreement, and I consent to the collection and use of my signature and audit metadata within this system for travel order signing, audit trails, and the approval process.</label>
                            </div>
                        </section>
                    </div>
                    <p id="uaScrollHint" class="mt-3 text-xs text-red-700 font-medium flex items-center"><i class="fas fa-arrow-down mr-2"></i>Scroll to the bottom and check the acknowledgment to enable "I Agree and Proceed".</p>

                    <!-- Actions -->
                    <div class="mt-6 flex items-center justify-end sticky bottom-0 bg-white pt-4 border-t">
                        <div class="space-x-2">
                            <button id="uaCancelBtn" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded">
                                Cancel
                            </button>
                            <a id="uaProceedBtn" href="{{ route('signature.index') }}" class="px-4 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded shadow disabled:opacity-50 opacity-50 pointer-events-none">
                                I Understand, Agree, and Proceed
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hidden logout form to force logout on cancel -->
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
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

                        <div class="mt-6 text-center">
                            <a href="{{ route('signature.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-upload mr-2"></i> Upload/Draw Signature Now
                            </a>
                        </div>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-500">You can also upload your signature later from the signature
                                menu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const uaModal = document.getElementById('userAgreementModal');
                const sigModal = document.getElementById('signatureRequiredModal');
                if (!uaModal) return;

                // Hide Signature Required while Agreement is shown
                if (sigModal) {
                    sigModal.style.display = 'none';
                }
                // Elements
                const content = document.getElementById('uaContent');
                const cancelBtn = document.getElementById('uaCancelBtn');
                const proceedBtn = document.getElementById('uaProceedBtn');
                const agreeCbx = document.getElementById('uaAgreeCheckbox');
                const scrollHint = document.getElementById('uaScrollHint');

                // Gate proceed on scroll-to-bottom and checkbox
                let scrolledToBottom = false;
                function updateProceedState() {
                    const canProceed = scrolledToBottom && agreeCbx?.checked;
                    if (canProceed) {
                        proceedBtn.classList.remove('opacity-50', 'pointer-events-none');
                        if (scrollHint) scrollHint.textContent = 'Acknowledgment checked. You can proceed.';
                    } else {
                        proceedBtn.classList.add('opacity-50', 'pointer-events-none');
                        if (scrollHint) scrollHint.textContent = 'Scroll to the bottom and check the acknowledgment to enable "I Agree and Proceed".';
                    }
                }

                function computeInitialScrollState() {
                    if (!content) {
                        scrolledToBottom = true; // no content container, allow
                        return;
                    }
                    // If content does not overflow, treat as already at bottom
                    const overflows = content.scrollHeight > content.clientHeight + 1;
                    if (!overflows) {
                        scrolledToBottom = true;
                    } else {
                        // If already at bottom on load
                        const atBottom = content.scrollTop + content.clientHeight >= content.scrollHeight - 10;
                        scrolledToBottom = atBottom;
                    }
                }

                content?.addEventListener('scroll', function () {
                    const nearBottom = content.scrollTop + content.clientHeight >= content.scrollHeight - 10; // 10px threshold
                    if (nearBottom) {
                        scrolledToBottom = true;
                        updateProceedState();
                    }
                });

                // Recompute on resize (content size may change)
                window.addEventListener('resize', function () {
                    computeInitialScrollState();
                    updateProceedState();
                });

                cancelBtn?.addEventListener('click', function () {
                    // Force logout
                    const form = document.getElementById('logoutForm');
                    if (form) form.submit();
                });

                agreeCbx?.addEventListener('change', function () {
                    updateProceedState();
                });

                // Initialize state
                computeInitialScrollState();
                updateProceedState();
            });
        </script>
    @endpush
@endsection
