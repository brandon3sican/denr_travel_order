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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('layout.navigation')

        <!-- Main Content -->
        @yield('content')
        
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    <!-- Success Message Container -->
    <div id="successMessage" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg flex items-center"
            role="alert">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
            </div>
            <div>
                <p class="font-bold">Success</p>
                <p id="successMessageText" class="text-sm"></p>
            </div>
            <button type="button" onclick="document.getElementById('successMessage').classList.add('hidden')"
                class="ml-4 text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showSuccessMessage('{{ session('success') }}');
            });
        </script>
    @endif

    <script>
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
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/table-filters.js') }}"></script>
    <script src="{{ asset('js/travel-order-actions.js') }}"></script>

    @include('components.profile-modal')

    <!-- Recommend Confirmation Modal -->
    <div id="recommendModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b bg-blue-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-blue-500 text-xl mr-3"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Confirm Recommendation</h3>
                </div>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-700 mb-4">You are about to recommend this travel order for approval. This action
                    cannot be undone.</p>
                <p class="text-sm text-gray-600 mb-4">By recommending, you confirm that all details are accurate and
                    complete.</p>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Type <span
                            class="font-mono bg-gray-100 px-2 py-1 rounded">RECOMMEND</span> to confirm</p>
                    <input type="text" id="confirmRecommendInput"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Type RECOMMEND to confirm">
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeRecommendModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" id="confirmRecommendBtn" disabled
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium opacity-50 cursor-not-allowed focus:outline-none">
                        Confirm Recommendation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b bg-green-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Final Approval</h3>
                </div>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-700 mb-4">You are about to approve this travel order. This action is irreversible.
                </p>
                <p class="text-sm text-gray-600 mb-4">By approving, you confirm that all requirements are met and budget
                    is available.</p>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Type <span
                            class="font-mono bg-gray-100 px-2 py-1 rounded">APPROVE</span> to confirm</p>
                    <input type="text" id="confirmApproveInput"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500"
                        placeholder="Type APPROVE to confirm">
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" id="confirmApproveBtn" disabled
                        class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium opacity-50 cursor-not-allowed focus:outline-none">
                        Confirm Approval
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle recommend confirmation input
        document.addEventListener('DOMContentLoaded', function() {
            // Recommend modal
            const recommendInput = document.getElementById('confirmRecommendInput');
            const recommendButton = document.getElementById('confirmRecommendBtn');

            if (recommendInput && recommendButton) {
                recommendInput.addEventListener('input', function() {
                    if (this.value.trim().toUpperCase() === 'RECOMMEND') {
                        recommendButton.disabled = false;
                        recommendButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        recommendButton.classList.add('hover:bg-blue-700');
                    } else {
                        recommendButton.disabled = true;
                        recommendButton.classList.add('opacity-50', 'cursor-not-allowed');
                        recommendButton.classList.remove('hover:bg-blue-700');
                    }
                });
            }

            // Approve modal
            const approveInput = document.getElementById('confirmApproveInput');
            const approveButton = document.getElementById('confirmApproveBtn');

            if (approveInput && approveButton) {
                approveInput.addEventListener('input', function() {
                    if (this.value.trim().toUpperCase() === 'APPROVE') {
                        approveButton.disabled = false;
                        approveButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        approveButton.classList.add('hover:bg-green-700');
                    } else {
                        approveButton.disabled = true;
                        approveButton.classList.add('opacity-50', 'cursor-not-allowed');
                        approveButton.classList.remove('hover:bg-green-700');
                    }
                });
            }
        });

        // Global variables to store order ID
        let orderToRecommend = null;
        let orderToApprove = null;

        // Recommend modal functions
        function showRecommendModal(orderId) {
            orderToRecommend = orderId;
            const modal = document.getElementById('recommendModal');
            const input = document.getElementById('confirmRecommendInput');
            const button = document.getElementById('confirmRecommendBtn');

            if (input) input.value = '';
            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.classList.remove('hover:bg-blue-700');
            }
            modal.classList.remove('hidden');
        }

        function closeRecommendModal() {
            document.getElementById('recommendModal').classList.add('hidden');
            orderToRecommend = null;
        }

        // Approve modal functions
        function showApproveModal(orderId) {
            orderToApprove = orderId;
            const modal = document.getElementById('approveModal');
            const input = document.getElementById('confirmApproveInput');
            const button = document.getElementById('confirmApproveBtn');

            if (input) input.value = '';
            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.classList.remove('hover:bg-green-700');
            }
            modal.classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            orderToApprove = null;
        }

        // Handle confirm recommend button click
        document.getElementById('confirmRecommendBtn')?.addEventListener('click', function() {
            if (orderToRecommend) {
                updateTravelOrderStatus(orderToRecommend, 'for approval');
                closeRecommendModal();
            }
        });

        // Handle confirm approve button click
        document.getElementById('confirmApproveBtn')?.addEventListener('click', function() {
            if (orderToApprove) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                fetch(`/travel-order/${orderToApprove}/approve`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _token: csrfToken
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessMessage(
                                `Travel order has been approved successfully.\nTravel Order Number: ${data.travel_order_number}`
                                );
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Failed to approve travel order');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message ||
                            'An error occurred while approving the travel order. Please try again.');
                    });

                closeApproveModal();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
