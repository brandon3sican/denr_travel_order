@extends('layout.app')

@section('content')
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Dashboard Content -->
                <div id="dashboardContent" class="page-content">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Total Travel Orders</p>
                                    <p class="text-2xl font-bold mt-1">{{ $totalTravelOrders }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-plus text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Pending Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $pendingRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Completed Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $completedRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Cancelled Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $cancelledRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-times-circle text-xl"></i>
                                </div>
                            </div>
                        </div>                 
                    </div>

                    <!-- Recent Travel Orders -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="text-xl font-bold text-gray-800">Recent Travel Orders</h3>
                                    <p class="text-sm text-gray-600 mt-1">Track and manage all travel order requests</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <form id="filterForm" method="GET" class="flex space-x-2">
                                            <select name="status" id="statusFilter" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                                <option value="For Recommendation" {{ request('status') == 'For Recommendation' ? 'selected' : '' }}>For Recommendation</option>
                                                <option value="For Approval" {{ request('status') == 'For Approval' ? 'selected' : '' }}>For Approval</option>
                                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Disapproved" {{ request('status') == 'Disapproved' ? 'selected' : '' }}>Disapproved</option>
                                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            @if(request('status') || request('search'))
                                                <a href="{{ route('dashboard') }}" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                    Clear Filters
                                                </a>
                                            @endif
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        </form>
                                    </div>
                                    <div class="relative">
                                        <div class="relative">
                                            <input type="text" 
                                                name="search" 
                                                id="searchInput" 
                                                value="{{ request('search') }}" 
                                                placeholder="Search..." 
                                                class="block w-full pl-4 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                onkeypress="if(event.key === 'Enter') { document.getElementById('filterForm').submit(); }">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button type="submit" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Travel Order ID</th>
                                        @if (auth()->user()->is_admin)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Employee</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Destination</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Arrival Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Departure Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-black uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $index => $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TO-2025-{{ $order->id }}</td>
                                        @if($isAdmin)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->employee ? $order->employee->first_name . ' ' . $order->employee->last_name : 'N/A' }}
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->destination ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->purpose ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Get the status name from the relationship
                                                $statusName = $order->status->name ?? '';
                                                $statusClass = [
                                                    'For Recommendation' => 'bg-yellow-100 text-yellow-800',
                                                    'For Approval' => 'bg-blue-100 text-blue-800',
                                                    'Approved' => 'bg-green-100 text-green-800',
                                                    'Disapproved' => 'bg-red-100 text-red-800',
                                                    'Cancelled' => 'bg-gray-100 text-gray-800',
                                                    'Completed' => 'bg-purple-100 text-purple-800'
                                                ][$statusName] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $order->status->name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="showTravelOrder({{ $order->id }})" 
                                               class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? '6' : '5' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No travel orders found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                <p class="text-sm text-gray-600">
                                    @if($travelOrders->count() > 0)
                                        Showing <span class="font-medium">{{ $travelOrders->firstItem() }}</span> to 
                                        <span class="font-medium">{{ $travelOrders->lastItem() }}</span> of 
                                        <span class="font-medium">{{ $travelOrders->total() }}</span> results
                                    @else
                                        No results found
                                    @endif
                                </p>
                                @if($travelOrders->hasPages())
                                    <div class="flex items-center space-x-1">
                                        {{-- Previous Page Link --}}
                                        @if ($travelOrders->onFirstPage())
                                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                                                <i class="fas fa-chevron-left"></i> Previous
                                            </span>
                                        @else
                                            <a href="{{ $travelOrders->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-chevron-left"></i> Previous
                                            </a>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($travelOrders->getUrlRange(1, $travelOrders->lastPage()) as $page => $url)
                                            @if ($page == $travelOrders->currentPage())
                                                <span class="px-3 py-1 border rounded-md text-sm font-medium bg-blue-600 text-white border-blue-600">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $url }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($travelOrders->hasMorePages())
                                            <a href="{{ $travelOrders->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                Next <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                                                Next <i class="fas fa-chevron-right"></i>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other pages will be loaded here -->
                <div id="otherPages" class="page-content hidden"></div>
            </main>
        </div>
    </div>

    <!-- View Order Modal -->
    <div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 pt-10 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all sm:my-8">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <div class="flex items-center justify-between border-b pb-4">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900">Travel Order Details</h3>
                                <button onclick="closeOrderModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div class="mt-4 max-h-[70vh] overflow-y-auto" id="orderDetails">
                                <!-- Order details will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" id="printButton" class="hidden inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <button type="button" onclick="closeOrderModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Function to show travel order details in modal
    async function showTravelOrder(orderId) {
        try {
            console.log('Opening travel order:', orderId);
            
            // Show loading state
            const modal = document.getElementById('orderModal');
            const orderDetails = document.getElementById('orderDetails');
            orderDetails.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-700">Loading travel order details...</span>
                </div>
            `;
            
            // Show the modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Fetch order details
            console.log('Fetching order details...');
            const response = await fetch(`/travel-orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                credentials: 'same-origin'
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Error response:', errorData);
                throw new Error(errorData.message || 'Failed to fetch order details');
            }
            
            const order = await response.json();
            console.log('Order data:', order);
            
            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            };

            // Get status class and text
            const statusInfo = {
                1: { class: 'bg-yellow-100 text-yellow-800', text: 'Pending' },
                2: { class: 'bg-blue-100 text-blue-800', text: 'Approved' },
                3: { class: 'bg-green-100 text-green-800', text: 'Completed' },
                4: { class: 'bg-red-100 text-red-800', text: 'Rejected' },
                5: { class: 'bg-gray-100 text-gray-800', text: 'For Recommendation' }
            }[order.status_id] || { class: 'bg-gray-100 text-gray-800', text: 'Unknown' };

            // Format currency
            const formatCurrency = (amount) => {
                if (!amount) return '₱0.00';
                return '₱' + parseFloat(amount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            };

            // Create HTML for order details
            const detailsHtml = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-200 p-6 rounded-lg">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Travel Order No.</h4>
                        <p class="mt-1 text-sm text-gray-900">TO-${new Date(order.created_at).getFullYear()}-${String(order.id).padStart(4, '0')}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusInfo.class}">
                            ${statusInfo.text}
                        </span>
                    </div>
                    @if (auth()->user()->is_admin)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Employee</h4>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ auth()->user()->employee->first_name ?? '' }} {{ auth()->user()->employee->middle_name ?? '' }} {{ auth()->user()->employee->last_name ?? '' }}
                        </p>
                    </div>
                    @endif
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Destination</h4>
                        <p class="mt-1 text-sm text-gray-900">${order.destination || 'N/A'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Travel Period</h4>
                        <p class="mt-1 text-sm text-gray-900">
                            ${formatDate(order.departure_date)} to ${formatDate(order.arrival_date)}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Purpose</h4>
                        <p class="mt-1 text-sm text-gray-900">${order.purpose || 'N/A'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Source of Fund</h4>
                        <p class="mt-1 text-sm text-gray-900">${order.appropriation || 'N/A'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Per Diem</h4>
                        <p class="mt-1 text-sm text-gray-900">${formatCurrency(order.per_diem)}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Labor/Assistant</h4>
                        <p class="mt-1 text-sm text-gray-900">${order.laborer_assistant || '0'}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Remarks</h4>
                        <p class="mt-1 text-sm text-gray-900">${order.remarks || 'N/A'}</p>
                    </div>
                    <div class="col-span-2 bg-gray-400 p-6 rounded-lg">
                        <h4 class="text-sm font-medium text-black mb-2">Personnel Involved</h4>
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <h5 class="text-xs font-medium text-gray-500">Employee</h5>
                                <p class="text-sm text-gray-900">
                                    {{ auth()->user()->employee->first_name ?? '' }} {{ auth()->user()->employee->middle_name ?? '' }} {{ auth()->user()->employee->last_name ?? '' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <h5 class="text-xs font-medium text-gray-500">Recommender</h5>
                                <p class="text-sm text-gray-900">
                                    ${order.recommender_employee ? `${order.recommender_employee.first_name || ''} ${order.recommender_employee.last_name || ''}`.trim() : 'Not assigned'}
                                    ${order.recommender_employee?.position_name ? `<span class="block text-xs text-gray-500">${order.recommender_employee.position_name}</span>` : ''}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <h5 class="text-xs font-medium text-gray-500">Approver</h5>
                                <p class="text-sm text-gray-900">
                                    ${order.approver_employee ? `${order.approver_employee.first_name || ''} ${order.approver_employee.last_name || ''}`.trim() : 'Not assigned'}
                                    ${order.approver_employee?.position_name ? `<span class="block text-xs text-gray-500">${order.approver_employee.position_name}</span>` : ''}
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Update modal content
            orderDetails.innerHTML = detailsHtml;
            
            // Show print button for approved or completed orders
            const printButton = document.getElementById('printButton');
            if ([2, 3].includes(order.status_id)) {
                printButton.classList.remove('hidden');
                printButton.onclick = () => window.print();
            } else {
                printButton.classList.add('hidden');
            }
            
        } catch (error) {
            console.error('Error loading travel order:', error);
            let errorMessage = 'Failed to load travel order details. Please try again.';
            
            if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Unable to connect to the server. Please check your internet connection.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            orderDetails.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Error Loading Travel Order</p>
                            <p class="text-sm text-red-700">${errorMessage}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeOrderModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Close
                    </button>
                    <button type="button" onclick="showTravelOrder(${orderId})" class="ml-3 px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sync-alt mr-1"></i> Retry
                    </button>
                </div>
            `;
        }
    }
    
    // Close modal function
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    document.getElementById('orderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeOrderModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeOrderModal();
        }
    });
    
    // Close button handler (using event delegation since the close button is now in the modal template)
    document.addEventListener('click', function(e) {
        if (e.target.closest('[onclick*="closeOrderModal"]')) {
            closeOrderModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeOrderModal();
        }
    });
</script>
@endpush

@endsection
    