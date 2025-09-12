// DOM Elements
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const logoutBtn = document.getElementById('logoutBtn');
const ordersTableBody = document.getElementById('ordersTableBody');
const currentPageSpan = document.getElementById('currentPage');
const totalPagesSpan = document.getElementById('totalPages');
const prevPageBtn = document.getElementById('prevPage');
const nextPageBtn = document.getElementById('nextPage');
const orderModal = document.getElementById('orderModal');
const closeModalBtn = document.getElementById('closeModal');
const orderDetails = document.getElementById('orderDetails');

// Pagination variables
let currentPage = 1;
const itemsPerPage = 5;
const travelOrders = window.travelOrders || [];
let totalPages = 1;

// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    // Initialize with any existing travel orders
    if (window.travelOrders) {
        totalPages = Math.ceil(window.travelOrders.length / itemsPerPage);
    }
    
    // Set up event listeners
    setupEventListeners();
    
    // Only render dashboard if we have travel orders
    if (travelOrders.length > 0) {
        renderDashboard();
        updatePaginationInfo();
    }
});

// Set up event listeners
function setupEventListeners() {
    // Toggle sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    
    // Logout button
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }
    
    // Navigation items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', handleNavigation);
    });
    
    // Pagination buttons
    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', () => changePage(-1));
    }
    
    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', () => changePage(1));
    }
    
    // Modal close button
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === orderModal) {
            closeModal();
        }
    });
}

// Toggle sidebar
function toggleSidebar() {
    sidebar.classList.toggle('collapsed');
}

// Handle logout
function handleLogout() {
    // In a real app, you would make an API call to log out the user
    console.log('User logged out');
    // Redirect to login page
    window.location.href = 'login.html';
}

// Handle navigation
function handleNavigation(e) {
    e.preventDefault();
    
    // Get the target URL from the clicked link
    const targetUrl = this.getAttribute('href');
    
    // If the target URL is the same as current page, do nothing
    if (window.location.pathname.endsWith(targetUrl)) {
        return;
    }
    
    // Navigate to the target URL
    window.location.href = targetUrl;
}

// Render dashboard with travel orders
function renderDashboard() {
    if (!ordersTableBody) return;
    
    // Clear existing rows
    ordersTableBody.innerHTML = '';
    
    // Check if we have travel orders
    if (!travelOrders || travelOrders.length === 0) {
        ordersTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No travel orders found.
                </td>
            </tr>
        `;
        return;
    }
    
    // Calculate start and end index for current page
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentOrders = travelOrders.slice(startIndex, endIndex);
    
    // Add rows to table
    currentOrders.forEach(order => {
        const row = document.createElement('tr');
        
        // Format dates
        const departureDate = new Date(order.departureDate).toLocaleDateString();
        const arrivalDate = new Date(order.arrivalDate).toLocaleDateString();
        
        // Determine status class
        let statusClass = '';
        switch (order.status) {
            case 'approved':
                statusClass = 'approved';
                break;
            case 'pending':
                statusClass = 'pending';
                break;
            case 'rejected':
                statusClass = 'rejected';
                break;
            default:
                statusClass = 'pending';
        }
        
        // Set data-status attribute for filtering
        row.setAttribute('data-status', order.status);
        
        // Create row HTML
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${order.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-600">${order.employeeName.charAt(0)}</span>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${order.employeeName}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${order.destination}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">${departureDate} - ${arrivalDate}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${statusClass}">${order.status}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="view-order-btn text-blue-600 hover:text-blue-900 mr-3" data-id="${order.id}">View</button>
            </td>
        `;
        
        ordersTableBody.appendChild(row);
    });
    
    // Add event listeners to view buttons
    document.querySelectorAll('.view-order-btn').forEach(btn => {
        btn.addEventListener('click', () => viewOrderDetails(btn.dataset.id));
    });
}

// View order details
function viewOrderDetails(orderId) {
    const order = travelOrders.find(o => o.id === orderId);
    if (!order) return;
    
    // Format dates
    const departureDate = new Date(order.departureDate).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
    });
    
    const arrivalDate = new Date(order.arrivalDate).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
    });
    
    // Determine status class and text
    let statusClass = '';
    let statusText = '';
    
    switch (order.status) {
        case 'approved':
            statusClass = 'approved';
            statusText = 'Approved';
            break;
        case 'pending':
            statusClass = 'pending';
            statusText = 'Pending Approval';
            break;
        case 'rejected':
            statusClass = 'rejected';
            statusText = 'Rejected';
            break;
        default:
            statusClass = 'pending';
            statusText = 'Pending';
    }
    
    // Create order details HTML
    orderDetails.innerHTML = `
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Travel Order ID</h4>
                    <p class="mt-1 text-sm text-gray-900">${order.id}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Status</h4>
                    <p class="mt-1">
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Employee Name</h4>
                    <p class="mt-1 text-sm text-gray-900">${order.employeeName}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Destination</h4>
                    <p class="mt-1 text-sm text-gray-900">${order.destination}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Departure Date</h4>
                    <p class="mt-1 text-sm text-gray-900">${departureDate}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Arrival Date</h4>
                    <p class="mt-1 text-sm text-gray-900">${arrivalDate}</p>
                </div>
            </div>
            
            <div>
                <h4 class="text-sm font-medium text-gray-500">Purpose</h4>
                <p class="mt-1 text-sm text-gray-900">${order.purpose}</p>
            </div>
            
            <div class="pt-5 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" class="close-modal-btn bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
                ${order.status === 'pending' ? `
                    <button type="button" class="approve-btn bg-green-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve
                    </button>
                    <button type="button" class="reject-btn bg-red-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-3">
                        Reject
                    </button>
                ` : ''}
            </div>
        </div>
    `;
    
    // Add event listeners to modal buttons
    const closeModalBtn = orderDetails.querySelector('.close-modal-btn');
    const approveBtn = orderDetails.querySelector('.approve-btn');
    const rejectBtn = orderDetails.querySelector('.reject-btn');
    
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    if (approveBtn) {
        approveBtn.addEventListener('click', () => updateOrderStatus(orderId, 'approved'));
    }
    
    if (rejectBtn) {
        rejectBtn.addEventListener('click', () => updateOrderStatus(orderId, 'rejected'));
    }
    
    // Show the modal
    openModal();
}

// Update order status
function updateOrderStatus(orderId, newStatus) {
    const orderIndex = travelOrders.findIndex(o => o.id === orderId);
    if (orderIndex === -1) return;
    
    // In a real app, you would make an API call to update the status
    travelOrders[orderIndex].status = newStatus;
    
    // Show success message
    alert(`Order ${orderId} has been ${newStatus}`);
    
    // Close the modal and refresh the view
    closeModal();
    renderDashboard();
}

// Open modal
function openModal() {
    if (orderModal) {
        orderModal.classList.remove('hidden');
        orderModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

// Close modal
function closeModal() {
    if (orderModal) {
        orderModal.classList.add('hidden');
        orderModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Change page
function changePage(direction) {
    const newPage = currentPage + direction;
    
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        renderDashboard();
        updatePaginationInfo();
    }
}

// Update pagination info
function updatePaginationInfo() {
    if (currentPageSpan) currentPageSpan.textContent = currentPage;
    if (totalPagesSpan) totalPagesSpan.textContent = totalPages;
    
    // Disable/enable pagination buttons
    if (prevPageBtn) {
        prevPageBtn.disabled = currentPage === 1;
    }
    
    if (nextPageBtn) {
        nextPageBtn.disabled = currentPage === totalPages;
    }
}

// Make functions available globally for testing
window.toggleSidebar = toggleSidebar;
window.handleLogout = handleLogout;
