document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const tableRows = document.querySelectorAll('tbody tr');
    
    // Initialize the search functionality
    function initSearch() {
        if (!searchInput) return;
        
        // Apply filters when search button is clicked
        applyFiltersBtn.addEventListener('click', filterTable);
        
        // Also filter when pressing Enter in search input
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterTable();
            }
        });
        
        // Clear filters and reset table
        clearFiltersBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterTable();
        });
    }
    
    // Filter table rows based on search input
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const isVisible = rowText.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
        });
        
        // Update URL with search parameters (for sharing/bookmarking)
        updateUrlParams({
            search: searchTerm || null
        });
    }
    
    // Update URL parameters without page reload
    function updateUrlParams(params) {
        const url = new URL(window.location);
        
        Object.entries(params).forEach(([key, value]) => {
            if (value === null || value === '') {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, value);
            }
        });
        
        window.history.pushState({}, '', url);
    }
    
    // Initialize on page load
    initSearch();
    
    // If there are URL parameters, apply them
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
        filterTable();
    }
});
