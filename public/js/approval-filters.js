// Use window object to prevent duplicate declarations
window.sortStates = window.sortStates || {}; // Track sort states for each column

function sortTable(columnIndex) {
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr:not([style*="display: none"])'));
    const header = table.querySelectorAll('th')[columnIndex];
    
    // Initialize sort state for this column if it doesn't exist
    if (sortStates[columnIndex] === undefined) {
        sortStates[columnIndex] = 0; // 0 = none, 1 = asc, 2 = desc
    }
    
    // Toggle sort order for this column
    sortStates[columnIndex] = (sortStates[columnIndex] + 1) % 3;
    
    // Reset other columns' sort states
    Object.keys(sortStates).forEach(col => {
        if (parseInt(col) !== columnIndex) {
            sortStates[col] = 0;
            // Reset other columns' sort icons
            const otherHeader = table.querySelector(`th[data-column="${col}"]`);
            if (otherHeader) {
                const icon = otherHeader.querySelector('.sort-icon');
                if (icon) icon.innerHTML = '<i class="fas fa-sort"></i>';
            }
        }
    });
    
    // Update sort icon for current column
    const sortIcon = header.querySelector('.sort-icon');
    if (sortIcon) {
        sortIcon.innerHTML = sortStates[columnIndex] === 1 ? 
            '<i class="fas fa-sort-up"></i>' : 
            sortStates[columnIndex] === 2 ? 
            '<i class="fas fa-sort-down"></i>' : 
            '<i class="fas fa-sort">';
    }
    
    if (sortStates[columnIndex] === 0) {
        // Reset to original order but maintain filtered rows
        const originalRows = Array.from(tbody.querySelectorAll('tr'));
        originalRows.sort((a, b) => {
            return parseInt(a.getAttribute('data-original-order') || 0) - 
                   parseInt(b.getAttribute('data-original-order') || 0);
        });
        
        // Re-append rows in original order
        originalRows.forEach(row => tbody.appendChild(row));
        return;
    }
    
    // Sort rows by column value
    rows.sort((a, b) => {
        let valueA, valueB;
        const cellA = a.cells[columnIndex];
        const cellB = b.cells[columnIndex];
        
        if (!cellA || !cellB) return 0;
        
        // For date columns (index 0 = created date, 4 = departure date)
        if (columnIndex === 0 || columnIndex === 4) {
            const dateA = cellA.getAttribute('data-sort-value');
            const dateB = cellB.getAttribute('data-sort-value');
            valueA = dateA ? new Date(dateA) : new Date(0);
            valueB = dateB ? new Date(dateB) : new Date(0);
            return sortStates[columnIndex] === 1 ? valueA - valueB : valueB - valueA;
        } 
        // For text columns
        else {
            valueA = cellA.textContent.trim().toLowerCase();
            valueB = cellB.textContent.trim().toLowerCase();
            return sortStates[columnIndex] === 1 ? 
                valueA.localeCompare(valueB) : 
                valueB.localeCompare(valueA);
        }
    });
    
    // Re-append rows in sorted order
    rows.forEach(row => tbody.appendChild(row));
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize search and filter functionality
    const searchInput = document.getElementById('search');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const tableRows = document.querySelectorAll('tbody tr');
    
    // Initialize original order and add data attributes for sorting
    tableRows.forEach((row, index) => {
        row.setAttribute('data-original-order', index);
    });
    
    // Add data-column attribute to headers for better tracking
    document.querySelectorAll('th').forEach((th, index) => {
        th.setAttribute('data-column', index);
    });
    
    // Initialize the search functionality
    function initSearch() {
        if (!searchInput) return;
        
        // Apply filters when search button is clicked
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', filterTable);
        }
        
        // Also filter when pressing Enter in search input
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filterTable();
            }
        });
        
        // Clear filters and reset table
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterTable();
            });
        }
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
    if (urlParams.has('search') && searchInput) {
        searchInput.value = urlParams.get('search');
        filterTable();
    }
});
