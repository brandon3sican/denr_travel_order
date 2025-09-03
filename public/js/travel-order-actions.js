// Function to handle recommendation of a travel order
function recommend(orderId) {
    showRecommendModal(orderId);
}

// Function to handle approval of a travel order
function approve(orderId) {
    showApproveModal(orderId);
}

// Function to handle rejection of a travel order
function reject(orderId) {
    const reason = prompt('Please provide a reason for rejection (required):');
    if (reason === null) return; // User cancelled
    if (!reason.trim()) {
        alert('Rejection reason is required.');
        return;
    }

    if (confirm('Are you sure you want to reject this travel order? This action cannot be undone.')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/travel-order/${orderId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                reason: reason,
                _token: csrfToken
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Travel order has been rejected successfully.');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to reject travel order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred while rejecting the travel order. Please try again.');
        });
    }
}

// Function to update travel order status via AJAX
function updateTravelOrderStatus(orderId, status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/travel-order/${orderId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: status,
            _token: csrfToken
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert(`Travel order has been ${status === 'for approval' ? 'recommended for approval' : 'rejected'} successfully.`);
            // Reload the page to reflect changes
            window.location.reload();
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the travel order status. Please try again.');
    });
}
