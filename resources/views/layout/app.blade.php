<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Travel Order Information System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/denr-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS removed - Using Tailwind alerts instead -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b bg-green-50 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <h3 class="text-lg font-medium text-gray-900">Success</h3>
                </div>
                <button type="button" onclick="hideModal('successModal')"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <p id="successMessageText" class="text-gray-700"></p>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                <button type="button" onclick="hideModal('successModal')"
                    class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    OK
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b bg-red-50 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <h3 class="text-lg font-medium text-gray-900">Error</h3>
                </div>
                <button type="button" onclick="hideModal('errorModal')"
                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <p id="errorMessageText" class="text-gray-700"></p>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                <button type="button" onclick="hideModal('errorModal')"
                    class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    OK
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('success', '{{ session('success') }}');
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showModal('error', '{{ session('error') }}');
            });
        </script>
    @endif

    <script>
        // Show modal function
        function showModal(type, message) {
            const modal = document.getElementById(`${type}Modal`);
            const messageElement = document.getElementById(`${type}MessageText`);

            if (modal && messageElement) {
                messageElement.textContent = message;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Auto-hide after 5 seconds for success messages
                if (type === 'success') {
                    setTimeout(() => {
                        hideModal(`${type}Modal`);
                    }, 5000);
                }
            }
        }

        // Hide modal function
        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('[id$="Modal"]');
            modals.forEach(modal => {
                if (event.target === modal) {
                    hideModal(modal.id);
                }
            });
        };
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
                <p class="text-gray-700 mb-4">You are about to recommend this travel order for approval. Please enter
                    your password to confirm.</p>
                <div class="mt-2">
                    <label for="recommendPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="recommendPassword"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter your password">
                    <p id="recommendPasswordError" class="mt-1 text-sm text-red-600 hidden">Incorrect password. Please
                        try again.</p>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeRecommendModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" id="confirmRecommendBtn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none">
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
                <p class="text-gray-700 mb-4">You are about to approve this travel order. Please enter your password to
                    confirm.</p>
                <div class="mt-2">
                    <label for="approvePassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" 
                           id="approvePassword"
                           name="approvePassword"
                           autocomplete="new-password"
                           autocomplete="off"
                           data-lpignore="true"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="Enter your password">
                    <p id="approvePasswordError" class="mt-1 text-sm text-red-600 hidden">Incorrect password. Please try
                        again.</p>
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeApproveModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" id="confirmApproveBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium focus:outline-none">
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
                            showModal('success',
                                `Travel order has been approved successfully.\nTravel Order Number: ${data.travel_order_number}`
                            );
                            setTimeout(() => window.location.reload(), 3000);
                        } else {
                            throw new Error(data.message || 'Failed to approve travel order');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showModal('error', error.message ||
                            'An error occurred while approving the travel order. Please try again.');
                    });

                closeApproveModal();
            }
        });
    </script>

    <!-- SweetAlert2 removed - Using custom Tailwind modals -->
    @if (auth()->check())
        <script>
            let timeout;
            const timeoutInMinutes = 4; // 1 minute before server-side timeout (10 minutes total)
            let isWarningShown = false;

            function startInactivityTimer() {
                // Reset timer on any activity
                window.onload = resetTimer;
                document.onmousemove = resetTimer;
                document.onkeypress = resetTimer;
                document.onclick = resetTimer;
                document.onscroll = resetTimer;
            }

            function showTimeoutWarning() {
                if (!isWarningShown) {
                    isWarningShown = true;
                    // Show a warning modal or notification
                    const modal = document.createElement('div');
                    modal.id = 'session-timeout-warning';
                    modal.style.position = 'fixed';
                    modal.style.top = '20px';
                    modal.style.right = '20px';
                    modal.style.backgroundColor = '#f8d7da';
                    modal.style.padding = '15px';
                    modal.style.borderRadius = '5px';
                    modal.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
                    modal.style.zIndex = '9999';
                    modal.innerHTML = `
                    <p>Your session will expire in 1 minute due to inactivity.</p>
                    <button onclick="extendSession()" style="margin-top: 10px; padding: 5px 10px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">
                        Stay Logged In
                    </button>
                `;
                    document.body.appendChild(modal);
                }
            }

            function resetTimer() {
                clearTimeout(timeout);
                timeout = setTimeout(showTimeoutWarning, timeoutInMinutes * 60 * 1000);
            }

            function extendSession() {
                // Remove the warning
                const warning = document.getElementById('session-timeout-warning');
                if (warning) {
                    warning.remove();
                }
                isWarningShown = false;

                // Ping the server to extend the session
                fetch('{{ route('session.keep-alive') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        resetTimer();
                    }
                });
            }

            // Start the timer when the page loads
            document.addEventListener('DOMContentLoaded', startInactivityTimer);
        </script>
    @endif
    @stack('scripts')
</body>

</html>
