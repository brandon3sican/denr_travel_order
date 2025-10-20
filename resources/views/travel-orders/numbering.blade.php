@extends('layout.app')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Approved Travel Orders</h2>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-gray-800">Travel Order Numbering</h3>
                            <p class="text-sm text-gray-600 mt-1">View and confirm approved travel orders</p>
                        </div>
                    </div>
                </div>

                <!-- Orders Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @forelse($approvedTravelOrders as $index => $order)
                        <div
                            class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="text-xs text-gray-500">Created on</span>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $order->status->name ?? 'Approved' }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <h3 class="text-md font-semibold text-gray-700">
                                        <i class="far fa-user mr-1 text-gray-500"></i>
                                        {{ $order->employee->full_name }}
                                    </h3>
                                </div>

                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $order->destination }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $order->purpose }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    <div>
                                        <span class="text-gray-500 block">Departure</span>
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Arrival</span>
                                        <span class="font-medium">
                                            {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">
                                        TO #{{ $order->id }}
                                    </span>
                                    <div class="space-x-2">
                                        <button onclick="showTravelOrder({{ $order->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        @if (!$order->travelOrderNumber?->is_confirmed)
                                            <button type="button"
                                                onclick="editTravelOrderNumber({{ $order->id }}, '{{ $order->travelOrderNumber?->travel_order_number ?? '' }}')"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <i class="fas fa-check mr-1"></i> Confirm
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-gray-400 cursor-not-allowed">
                                                <i class="fas fa-check-circle mr-1"></i> Confirmed
                                            </span>
                                        @endif
                                        @if ($order->travelOrderNumber?->is_confirmed)
                                            @if (auth()->user()->is_admin)
                                                <button type="button"
                                                    onclick="resetTravelOrderConfirmation({{ $order->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <i class="fas fa-undo mr-1"></i> Reset
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">No approved travel orders</h3>
                                <p class="mt-1 text-sm text-gray-500">There are no travel orders awaiting confirmation.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if (isset($approvedTravelOrders) && $approvedTravelOrders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $approvedTravelOrders->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>

    @include('components.travel-order.travel-order-modal')

    <!-- Edit Travel Order Number Modal -->
    <div id="editNumberModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal Container -->
            <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
                <!-- Modal Content -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="text-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Edit Travel Order
                            Number</h3>
                        <div class="mt-2">
                            <input type="hidden" id="travelOrderId">
                            <label for="travelOrderNumber"
                                class="block text-sm font-medium text-gray-700 text-left mb-1">Travel Order Number</label>
                            <input type="text" id="travelOrderNumber" name="travel_order_number"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                    <button type="button" onclick="saveTravelOrderNumber()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">
                        Save
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="mt-0 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <!-- Modal Container -->
            <div class="relative w-full max-w-md transform overflow-hidden rounded-lg bg-white shadow-xl transition-all">
                <!-- Modal Content -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <h3 class="mt-3 text-lg font-medium text-gray-900">Confirm Travel Order</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to confirm this travel order number?</p>
                            <p class="text-sm font-medium text-gray-700 mt-2">Travel Order #<span
                                    id="confirmOrderNumber"></span></p>
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                    <button type="button" onclick="confirmTravelOrder()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 sm:ml-3 sm:w-auto">
                        Confirm
                    </button>
                    <button type="button" onclick="closeConfirmationModal()"
                        class="mt-0 w-full inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentOrderId = null;
            let currentOrderNumber = '';
            
            function resetTravelOrderConfirmation(orderId) {
                console.log('Reset confirmation clicked for order ID:', orderId);
                
                if (!confirm('Are you sure you want to reset the confirmation status of this travel order?')) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    alert('Security error: CSRF token missing');
                    return;
                }

                const url = `/travel-order/${orderId}/reset-confirmation`;
                console.log('Making request to:', url);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        _token: csrfToken
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Server returned an error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Travel order confirmation has been reset successfully');
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to reset confirmation');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + (error.message || 'An error occurred while resetting the confirmation'));
                });
            }

            function editTravelOrderNumber(orderId, orderNumber) {
                currentOrderId = orderId;
                currentOrderNumber = orderNumber;
                document.getElementById('travelOrderId').value = orderId;
                document.getElementById('travelOrderNumber').value = orderNumber;
                document.getElementById('editNumberModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editNumberModal').classList.add('hidden');
            }

            function showConfirmation(orderId, orderNumber) {
                currentOrderId = orderId;
                document.getElementById('confirmOrderNumber').textContent = orderNumber;
                document.getElementById('confirmationModal').classList.remove('hidden');
            }

            function closeConfirmationModal() {
                document.getElementById('confirmationModal').classList.add('hidden');
            }

            function saveTravelOrderNumber() {
                const newNumber = document.getElementById('travelOrderNumber').value;
                if (!newNumber) {
                    alert('Please enter a travel order number');
                    return;
                }

                // Show confirmation after saving the number
                closeEditModal();
                showConfirmation(currentOrderId, newNumber);
            }

            function confirmTravelOrder() {
                const orderNumber = document.getElementById('travelOrderNumber').value;

                // Create form data
                const formData = new FormData();
                formData.append('travel_order_number', orderNumber);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                fetch(`/travel-order/${currentOrderId}/confirm`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                console.error('Server error:', err);
                                throw new Error(err.message || 'Failed to confirm travel order');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Server response:', data); // Debug log
                        if (data.success) {
                            // Close the confirmation modal
                            closeConfirmationModal();

                            // Find the confirm button that was clicked
                            const confirmButton = document.querySelector(
                                `button[onclick*="editTravelOrderNumber(${currentOrderId}"]`);
                            if (confirmButton) {
                                // Update the button text and style
                                confirmButton.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Confirmed';
                                confirmButton.classList.remove('bg-green-600', 'hover:bg-green-700',
                                    'focus:ring-green-500');
                                confirmButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                                confirmButton.disabled = true;

                                // Show success message
                                alert('Travel order confirmed successfully!');

                                // Optional: Update the travel order number display if it's shown elsewhere
                                const orderNumberDisplay = document.querySelector(`#order-${currentOrderId}-number`);
                                if (orderNumberDisplay) {
                                    orderNumberDisplay.textContent = orderNumber;
                                }
                            } else {
                                // Fallback to page reload if we can't find the button
                                window.location.reload();
                            }
                        } else {
                            alert('Error: ' + (data.message || 'Failed to confirm travel order'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while confirming the travel order');
                    })
                    .finally(() => {
                        closeConfirmationModal();
                    });
            }
        </script>
    @endpush
@endsection
