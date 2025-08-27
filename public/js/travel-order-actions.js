// Function to handle recommendation of a travel order
function recommend(orderId) {
    if (confirm('Are you sure you want to recommend this travel order for approval?')) {
        updateTravelOrderStatus(orderId, 'for approval');
    }
}

// Function to handle rejection of a travel order
function reject(orderId) {
    if (confirm('Are you sure you want to reject this travel order?')) {
        updateTravelOrderStatus(orderId, 'disapproved');
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
