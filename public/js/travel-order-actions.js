// Function to handle recommendation of a travel order
function recommend(orderId) {
    Swal.fire({
        title: 'Confirm Recommendation',
        text: 'Are you sure you want to recommend this travel order for approval?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, recommend it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showRecommendModal(orderId);
        }
    });
}

// Function to handle approval of a travel order
function approve(orderId) {
    Swal.fire({
        title: 'Confirm Approval',
        text: 'Are you sure you want to approve this travel order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showApproveModal(orderId);
        }
    });
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
