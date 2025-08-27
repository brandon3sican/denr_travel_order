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
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="text-xl font-bold text-gray-800">Recent Travel Orders</h3>
                                    <p class="text-sm text-gray-600 mt-1">Track and manage all travel order requests</p>
                                </div>
                                <div class="flex items-center space-x-3">
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(0)">Date Created</th>
                                    @if (auth()->user()->is_admin)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(1)">Employee</th>
                                    @endif
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(2)">Destination</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(3)">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(4)">Arrival Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(5)">Departure Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(6)">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider cursor-pointer hover:text-gray-700" onclick="sortTable(7)">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $order)
                                <tr data-status="{{ strtolower($order->status->name ?? '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                    </td>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <button onclick="showTravelOrder({{ $order->id }})" 
                                            class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3">
                                             View
                                         </button>
                                        @if($order->status_id == 1) {{-- Only show edit for pending orders --}}
                                        <button onclick="editTravelOrder({{ $order->id }})" 
                                           class="text-yellow-600 hover:text-yellow-900 border border-yellow-600 px-2 py-1 rounded mr-3">
                                            Edit
                                        </button>
                                       
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded mr-3"
                                                onclick="confirmDelete({{ $order->id }})">
                                            Delete
                                        </button>
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
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-300 bg-white">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $travelOrders->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($travelOrders->hasMorePages())
                                <a href="{{ $travelOrders->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-300 bg-white">
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

    @include('components.travel-order.travel-order-modal')
    @include('components.travel-order.edit-travel-order-modal')
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b bg-red-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-red-700">Delete Travel Order</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-red-700 font-medium">Warning: This action cannot be undone!</p>
                    <p class="text-gray-700 mt-2">You are about to permanently delete this travel order. This will:</p>
                    <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                        <li>Permanently remove all associated data</li>
                        <li>Remove the record from all reports</li>
                        <li>Be irreversible</li>
                    </ul>
                    <p class="text-gray-700 mt-3 font-medium">Are you absolutely sure you want to continue?</p>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Type <span class="font-mono bg-gray-100 px-2 py-1 rounded mt-2">DELETE</span> to confirm</p>
                    <input type="text" id="confirmDeleteInput" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="Type DELETE to confirm">
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="inline" onsubmit="handleDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteBtn" disabled
                            class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium opacity-50 cursor-not-allowed focus:outline-none">
                            <span id="deleteButtonText">Delete</span>
                            <span id="deleteButtonLoader" class="hidden">
                                <i class="fas fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Message Container -->
    <div id="successMessage" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-1-9V8a1 1 0 1 1 2 0v3a1 1 0 0 1-2 0zm0 4a1 1 0 1 1 2 0v1a1 1 0 0 1-2 0v-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Success</p>
                    <p id="successMessageText" class="text-sm"></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Handle delete confirmation input
        document.addEventListener('DOMContentLoaded', function() {
            const confirmInput = document.getElementById('confirmDeleteInput');
            const deleteButton = document.getElementById('confirmDeleteBtn');
            
            if (confirmInput && deleteButton) {
                confirmInput.addEventListener('input', function() {
                    if (this.value.trim().toUpperCase() === 'DELETE') {
                        deleteButton.disabled = false;
                        deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        deleteButton.classList.add('hover:bg-red-700');
                    } else {
                        deleteButton.disabled = true;
                        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                        deleteButton.classList.remove('hover:bg-red-700');
                    }
                });
            }
        });
        
        // Reset modal state when opened
        function confirmDelete(orderId) {
            const form = document.getElementById('deleteForm');
            const confirmInput = document.getElementById('confirmDeleteInput');
            const deleteButton = document.getElementById('confirmDeleteBtn');
            
            form.action = `/travel-orders/${orderId}`;
            if (confirmInput) confirmInput.value = '';
            if (deleteButton) {
                deleteButton.disabled = true;
                deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                deleteButton.classList.remove('hover:bg-red-700');
            }
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        // Show success message function
        function showSuccessMessage(message) {
            const successMessage = document.getElementById('successMessage');
            const messageText = document.getElementById('successMessageText');
            
            messageText.textContent = message;
            successMessage.classList.remove('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 5000);
        }
        // Handle delete form submission
        async function handleDelete(event) {
            event.preventDefault();
            
            const form = event.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const buttonText = document.getElementById('deleteButtonText');
            const buttonLoader = document.getElementById('deleteButtonLoader');
            
            try {
                // Show loading state
                buttonText.classList.add('hidden');
                buttonLoader.classList.remove('hidden');
                submitButton.disabled = true;
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-HTTP-Method-Override': 'DELETE'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    showSuccessMessage(result.message);
                    // Close the modal
                    closeDeleteModal();
                    // Reload the page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(result.message || 'Failed to delete travel order');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert(error.message || 'An error occurred while deleting the travel order');
                // Reset button state
                buttonText.classList.remove('hidden');
                buttonLoader.classList.add('hidden');
                submitButton.disabled = false;
            }
        }
        // Delete modal functions
        function confirmDelete(orderId) {
            const form = document.getElementById('deleteForm');
            form.action = `/travel-orders/${orderId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
        
        // Show success message
        function showSuccessMessage(message) {
            const successMessage = document.getElementById('successMessage');
            const messageText = document.getElementById('successMessageText');
            messageText.textContent = message;
            successMessage.classList.remove('hidden');
            
            // Hide after 5 seconds
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 5000);
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
    @endpush

@endsection
