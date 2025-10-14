// Function to verify password
async function verifyPassword(password) {
    try {
        const response = await fetch('/verify-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        });

        const data = await response.json();
        return data.valid;
    } catch (error) {
        console.error('Error verifying password:', error);
        return false;
    }
}

// Function to handle recommendation of a travel order
function recommend(orderId) {
    return new Promise((resolve, reject) => {
        // Store the order ID in the modal
        const modal = document.getElementById('recommendModal');
        modal.dataset.orderId = orderId;
        
        // Reset the modal state
        document.getElementById('recommendPassword').value = '';
        document.getElementById('recommendPasswordError').classList.add('hidden');
        
        // Show the modal
        modal.classList.remove('hidden');
        
        // Set up the confirm button handler
        document.getElementById('confirmRecommendBtn').onclick = async function() {
            const password = document.getElementById('recommendPassword').value.trim();
            if (!password) {
                document.getElementById('recommendPasswordError').textContent = 'Password is required';
                document.getElementById('recommendPasswordError').classList.remove('hidden');
                return;
            }

            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const client_meta = getClientMeta();
                const location = await getLocation();
                
                // Submit the recommendation
                const response = await fetch(`/travel-order/${orderId}/recommend`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        password: password,
                        client_meta: client_meta,
                        location: location
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeRecommendModal();
                    await Swal.fire({
                        title: 'Recommended!',
                        text: 'The travel order has been recommended for approval.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    window.location.reload();
                    resolve(true);
                } else {
                    throw new Error(data.message || 'Failed to recommend travel order');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('recommendPasswordError').textContent = error.message || 'An error occurred. Please try again.';
                document.getElementById('recommendPasswordError').classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText;
                reject(error);
            }
        };
    });
}

// Function to handle approval of a travel order
function approve(orderId) {
    return new Promise((resolve, reject) => {
        // Store the order ID in the modal
        const modal = document.getElementById('approveModal');
        modal.dataset.orderId = orderId;
        
        // Reset the modal state
        document.getElementById('approvePassword').value = '';
        document.getElementById('approvePasswordError').classList.add('hidden');
        
        // Show the modal
        modal.classList.remove('hidden');
        
        // Get the confirm button and ensure it's enabled
        const confirmBtn = document.getElementById('confirmApproveBtn');
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Confirm Approval';
        
        // Remove any existing click handlers to prevent duplicates
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
        
        // Set up the confirm button handler
        newConfirmBtn.onclick = async function() {
            const password = document.getElementById('approvePassword').value.trim();
            if (!password) {
                document.getElementById('approvePasswordError').textContent = 'Password is required';
                document.getElementById('approvePasswordError').classList.remove('hidden');
                return;
            }

            // Show loading state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const client_meta = getClientMeta();
                const location = await getLocation();
                
                // Submit the approval
                const response = await fetch(`/travel-order/${orderId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        password: password,
                        client_meta: client_meta,
                        location: location
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeApproveModal();
                    await Swal.fire({
                        title: 'Approved!',
                        text: 'The travel order has been approved successfully.' + (data.travel_order_number ? ` Travel Order #: ${data.travel_order_number}` : ''),
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                    window.location.reload();
                    resolve(true);
                } else {
                    throw new Error(data.message || 'Failed to approve travel order');
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('approvePasswordError').textContent = error.message || 'An error occurred. Please try again.';
                document.getElementById('approvePasswordError').classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText;
                reject(error);
            }
        };
    });
}

// Function to show approve modal
function showApproveModal(orderId) {
    const modal = document.getElementById('approveModal');
    modal.dataset.orderId = orderId;
    document.getElementById('approvePassword').value = '';
    document.getElementById('approvePasswordError').classList.add('hidden');
    
    // Update modal title and button text
    modal.querySelector('h3').textContent = 'Approve Travel Order';
    modal.querySelector('svg').classList.remove('text-red-600', 'bg-red-100');
    modal.querySelector('svg').classList.add('text-green-600', 'bg-green-100');
    modal.querySelector('path').setAttribute('d', 'M5 13l4 4L19 7');
    modal.querySelector('p').textContent = 'Please enter your password to confirm approval.';
    
    // Update button styling and text
    const confirmBtn = document.getElementById('confirmApproveBtn');
    confirmBtn.textContent = 'Confirm Approval';
    confirmBtn.disabled = false; // Ensure button is enabled
    confirmBtn.innerHTML = 'Confirm Approval';
    confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700', 'focus:ring-red-300');
    confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700', 'focus:ring-green-300');
    
    // Show the modal
    modal.classList.remove('hidden');
}

// Function to show recommend modal
function showRecommendModal(orderId) {
    const modal = document.getElementById('recommendModal');
    modal.dataset.orderId = orderId;
    document.getElementById('recommendPassword').value = '';
    document.getElementById('recommendPasswordError').classList.add('hidden');
    modal.classList.remove('hidden');
}

// Add helper functions to close modals
function closeRecommendModal() {
    document.getElementById('recommendModal').classList.add('hidden');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.add('hidden');
    
    // Reset the confirm button state when closing the modal
    const confirmBtn = document.getElementById('confirmApproveBtn');
    if (confirmBtn) {
        confirmBtn.disabled = false;
        confirmBtn.innerHTML = 'Confirm Approval';
    }
}

// Helpers to gather client metadata and location
function getClientMeta() {
    const ua = navigator.userAgent || '';
    // Very light parsing; backend also stores full user_agent
    let device = 'Desktop';
    if (/Mobi|Android/i.test(ua)) device = 'Mobile';
    else if (/iPad|Tablet/i.test(ua)) device = 'Tablet';

    // Simple browser detection
    let browser = 'Unknown';
    if (/Chrome\//i.test(ua) && !/Edge\//i.test(ua) && !/OPR\//i.test(ua)) browser = 'Chrome';
    else if (/Edg\//i.test(ua)) browser = 'Edge';
    else if (/Firefox\//i.test(ua)) browser = 'Firefox';
    else if (/Safari\//i.test(ua) && !/Chrome\//i.test(ua)) browser = 'Safari';
    else if (/OPR\//i.test(ua)) browser = 'Opera';

    return {
        device,
        browser,
        platform: navigator.platform || null,
        screen: { w: window.screen?.width || null, h: window.screen?.height || null },
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || null,
        language: navigator.language || null,
        user_agent: ua
    };
}

function getLocation() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) return resolve(null);
        navigator.geolocation.getCurrentPosition(
            (pos) => resolve({
                lat: pos.coords.latitude,
                lng: pos.coords.longitude,
                accuracy: pos.coords.accuracy
            }),
            () => resolve(null),
            { enableHighAccuracy: true, timeout: 8000, maximumAge: 300000 }
        );
    });
}

// Function to handle rejection of a travel order
async function reject(orderId) {
    const { value: reason } = await Swal.fire({
        title: 'Reject Travel Order',
        input: 'textarea',
        inputLabel: 'Reason for rejection',
        inputPlaceholder: 'Please provide a detailed reason for rejection...',
        inputAttributes: {
            'aria-label': 'Type your rejection reason here',
            required: 'true'
        },
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Reject',
        cancelButtonText: 'Cancel',
        inputValidator: (value) => {
            if (!value) {
                return 'Please provide a reason for rejection';
            }
            if (value.trim().length < 10) {
                return 'Please provide a more detailed reason (at least 10 characters)';
            }
        }
    });

    if (reason) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will reject the travel order and notify the employee.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reject it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const client_meta = getClientMeta();
                const location = await getLocation();
                
                try {
                    const response = await fetch(`/travel-order/${orderId}/reject`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            reason: reason.trim(),
                            _token: csrfToken,
                            client_meta,
                            location
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire({
                            title: 'Rejected!',
                            text: 'The travel order has been rejected successfully.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to reject travel order');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    await Swal.fire({
                        title: 'Error!',
                        text: error.message || 'An error occurred while rejecting the travel order. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }
}

// Function to update travel order status via AJAX
async function updateTravelOrderStatus(orderId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const client_meta = getClientMeta();
    const location = await getLocation();
    
    try {
        const response = await fetch(`/travel-order/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                _token: csrfToken,
                client_meta,
                location
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (data.success) {
            // Show success message with SweetAlert2
            await Swal.fire({
                title: 'Success!',
                text: `Travel order has been ${status === 'for approval' ? 'recommended for approval' : 'updated'} successfully.`,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    window.location.reload();
                }
            });
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    } catch (error) {
        console.error('Error:', error);
        await Swal.fire({
            title: 'Error!',
            text: error.message || 'An error occurred while updating the travel order status. Please try again.',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }
}
