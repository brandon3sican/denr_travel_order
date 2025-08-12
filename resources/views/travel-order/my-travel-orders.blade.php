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
                        <h2 class="text-xl font-semibold text-gray-800">My Travel Orders</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <a href="{{ route('travel-orders.create') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-2"></i> New Travel Order
                            </a>
                            <select id="statusFilter" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">All Status</option>
                                @foreach(\App\Models\TravelOrderStatus::all() as $status)
                                    <option value="{{ strtolower($status->name) }}">{{ ucfirst($status->name) }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">

                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(0)">Travel Order No.</th>
                                    @if (auth()->user()->is_admin)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(1)">Employee</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(2)">Destination</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(3)">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(4)">Arrival Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(5)">Departure Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(6)">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(7)">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $order)
                                <tr data-status="{{ strtolower($order->status->name ?? '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        TO-2025-{{ $order->id }}
                                    </td>
                                    @if (auth()->user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->employee->name }}
                                    </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->destination }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->purpose }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status_id == 1) bg-yellow-100 text-yellow-800 @endif
                                            @if($order->status_id == 2) bg-blue-100 text-blue-800 @endif
                                            @if($order->status_id == 3) bg-green-100 text-green-800 @endif
                                            @if($order->status_id == 4) bg-red-100 text-red-800 @endif
                                            @if($order->status_id == 5) bg-gray-100 text-gray-800 @endif
                                            @if($order->status_id == 6) bg-purple-100 text-purple-800 @endif
                                            "
                                            data-status="{{ strtolower($order->status->name ?? '') }}">
                                            {{ $order->status->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick='showOrderDetails(@json($order))' 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3 border border-indigo-600 px-2 py-1 rounded">
                                            View
                                        </button>
                                        @if($order->status_id == 1) {{-- Only show edit for pending orders --}}
                                        <a href="{{ route('travel-orders.edit', $order->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 border border-yellow-600 px-2 py-1 rounded mr-3">
                                            Edit
                                        </a>
                                        @endif
                                        @if($order->status_id == 1) {{-- Only show delete for pending orders --}}
                                        <a href="{{ route('travel-orders.destroy', $order->id) }}" 
                                           class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded mr-3">
                                            Delete
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No travel orders found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($travelOrders->hasPages())
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($travelOrders->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $travelOrders->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($travelOrders->hasMorePages())
                                <a href="{{ $travelOrders->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $travelOrders->firstItem() }}</span>
                                    to <span class="font-medium">{{ $travelOrders->lastItem() }}</span>
                                    of <span class="font-medium">{{ $travelOrders->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($travelOrders->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </span>
                                    @else
                                        <a href="{{ $travelOrders->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($travelOrders->getUrlRange(1, $travelOrders->lastPage()) as $page => $url)
                                        @if ($page == $travelOrders->currentPage())
                                            <span aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($travelOrders->hasMorePages())
                                        <a href="{{ $travelOrders->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
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
        // Status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr[data-status]');
            
            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Initialize the status filter based on URL parameter if present
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            
            if (statusParam) {
                const filterSelect = document.getElementById('statusFilter');
                const option = Array.from(filterSelect.options).find(
                    opt => opt.value.toLowerCase() === statusParam.toLowerCase()
                );
                
                if (option) {
                    filterSelect.value = option.value;
                    filterSelect.dispatchEvent(new Event('change'));
                }
            }
        });
        function showOrderDetails(orderData) {
            // Convert the order data to a proper JavaScript object if it's a string
            const order = typeof orderData === 'string' ? JSON.parse(orderData) : orderData;
            console.log('Order data:', order); // Debug log
            
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
            document.getElementById('orderDetails').innerHTML = detailsHtml;
            
            // Show print button for approved or completed orders
            const printButton = document.getElementById('printButton');
            if ([2, 3].includes(order.status_id)) {
                printButton.classList.remove('hidden');
                printButton.onclick = () => window.print();
            } else {
                printButton.classList.add('hidden');
            }

            // Show modal
            document.getElementById('orderModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside of it
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
    </script>
    @endpush

@endsection
