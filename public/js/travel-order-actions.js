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
            // Create and show toast notification
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-green-800 bg-green-100 rounded-lg shadow-lg border border-green-200 transition-opacity duration-300';
            toast.role = 'alert';
            
            const icon = document.createElement('i');
            icon.className = 'fas fa-check-circle text-green-500 text-xl mr-3';
            
            const message = document.createElement('div');
            message.className = 'text-sm font-medium';
            message.textContent = `Travel order has been ${status === 'for approval' ? 'recommended for approval' : 'rejected'} successfully.`;
            
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'ml-4 text-green-600 hover:text-green-800';
            closeButton.innerHTML = '&times;';
            closeButton.onclick = () => toast.remove();
            
            toast.appendChild(icon);
            toast.appendChild(message);
            toast.appendChild(closeButton);
            document.body.appendChild(toast);
            
            // Auto-remove toast after 15 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 15000);
            
            // Reload the page after a short delay to show the toast
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the travel order status. Please try again.');
    });
}
