// Table Filters and Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize based on the current page
    if (document.getElementById('searchOrders')) {
        initTravelOrdersFilters();
    }
    
    if (document.getElementById('searchUsers')) {
        initUserManagementFilters();
    }
});

// Initialize filters for Travel Orders page
function initTravelOrdersFilters() {
    const searchInput = document.getElementById('searchOrders');
    const statusFilter = document.getElementById('statusFilter');
    const tabButtons = document.querySelectorAll('.tab-button');
    const tableRows = document.querySelectorAll('#ordersTableBody tr');

    // Search functionality
    searchInput.addEventListener('input', filterTravelOrders);
    statusFilter.addEventListener('change', filterTravelOrders);
    
    // Tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            filterTravelOrders();
        });
    });

    function filterTravelOrders() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const activeTab = document.querySelector('.tab-button.active').dataset.tab;

        tableRows.forEach(row => {
            const status = row.getAttribute('data-status');
            const textContent = row.textContent.toLowerCase();
            
            // Check tab filter
            let tabMatch = true;
            if (activeTab !== 'all') {
                tabMatch = status === activeTab;
            }
            
            // Check status filter
            const statusMatch = statusValue === 'all' || status === statusValue;
            
            // Check search term
            const searchMatch = textContent.includes(searchTerm);
            
            // Show/hide row based on filters
            if (tabMatch && statusMatch && searchMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
}

// Initialize filters for User Management page
function initUserManagementFilters() {
    const searchInput = document.getElementById('searchUsers');
    const tabButtons = document.querySelectorAll('.tab-button');
    const tableRows = document.querySelectorAll('#usersTableBody tr');

    // Search functionality
    searchInput.addEventListener('input', filterUsers);
    
    // Tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            filterUsers();
        });
    });

    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const activeTab = document.querySelector('.tab-button.active').dataset.tab;

        tableRows.forEach(row => {
            const status = row.getAttribute('data-status');
            const textContent = row.textContent.toLowerCase();
            
            // Check tab filter
            let tabMatch = true;
            if (activeTab === 'active') {
                tabMatch = status === 'active';
            } else if (activeTab === 'inactive') {
                tabMatch = status === 'inactive';
            } else if (activeTab === 'pending') {
                tabMatch = status === 'pending';
            }
            
            // Check search term
            const searchMatch = textContent.includes(searchTerm);
            
            // Show/hide row based on filters
            row.style.display = (tabMatch && searchMatch) ? '' : 'none';
        });
    }
}

// Make functions available globally
window.initTravelOrdersFilters = initTravelOrdersFilters;
window.initUserManagementFilters = initUserManagementFilters;
