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

                        <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
                            <a href="{{ route('signature.index') }}"
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-upload mr-2"></i> Upload/Draw Signature Now
                            </a>
                            <button type="button"
                                onclick="document.getElementById('signatureRequiredModal').classList.add('hidden')"
                                class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                I'll do it later
                            </button>
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
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-xl font-bold text-gray-800">Recent Travel Orders</h3>
                                <p class="text-sm text-gray-600 mt-1">Track and manage all travel order requests</p>
                            </div>

                            <x-dashboard.search-filter :statuses="$statusOptions" :currentStatus="request('status')" :searchQuery="request('search')" />
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <x-dashboard.table-header :isAdmin="auth()->user()->is_admin" />
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $order)
                                    <x-dashboard.travel-order-row :order="$order" :isAdmin="auth()->user()->is_admin" />
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}"
                                            class="px-6 py-4 text-center text-sm text-gray-500">
                                            No travel orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
