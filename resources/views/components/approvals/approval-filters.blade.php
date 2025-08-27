@push('scripts')
    <script src="{{ asset('js/approval-filters.js') }}"></script>
    <script>
        let sortStates = {}; // Track sort states for each column
        
        function sortTable(columnIndex) {
            const table = document.querySelector('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
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
                    '<i class="fas fa-sort"></i>';
            }
            
            if (sortStates[columnIndex] === 0) {
                // Reset to original order
                const originalRows = Array.from(tbody.querySelectorAll('tr[data-original-order]'));
                originalRows.sort((a, b) => {
                    return parseInt(a.getAttribute('data-original-order')) - 
                           parseInt(b.getAttribute('data-original-order'));
                });
                
                // Re-append rows in original order
                originalRows.forEach(row => tbody.appendChild(row));
                return;
            }
            
            // Sort rows by column value
            rows.sort((a, b) => {
                let valueA, valueB;
                
                // For date columns (index 0 = created date, 4 = departure date)
                if (columnIndex === 0 || columnIndex === 4) {
                    valueA = new Date(a.cells[columnIndex].getAttribute('data-sort-value') || a.cells[columnIndex].textContent.trim());
                    valueB = new Date(b.cells[columnIndex].getAttribute('data-sort-value') || b.cells[columnIndex].textContent.trim());
                    return sortStates[columnIndex] === 1 ? valueA - valueB : valueB - valueA;
                } 
                // For text columns (add more conditions as needed)
                else {
                    valueA = a.cells[columnIndex].textContent.trim().toLowerCase();
                    valueB = b.cells[columnIndex].textContent.trim().toLowerCase();
                    return sortStates[columnIndex] === 1 ? 
                        valueA.localeCompare(valueB) : 
                        valueB.localeCompare(valueA);
                }
            });
            
            // Re-append rows in sorted order
            rows.forEach(row => tbody.appendChild(row));
        }
        
        // Initialize original order and add data attributes for sorting
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.setAttribute('data-original-order', index);
                
                // Add data-sort-value to date cells if they don't have one
                const dateCells = [row.cells[0], row.cells[4]]; // Indexes for date columns
                dateCells.forEach(cell => {
                    if (cell && !cell.hasAttribute('data-sort-value')) {
                        cell.setAttribute('data-sort-value', cell.textContent.trim());
                    }
                });
            });
            
            // Add data-column attribute to headers for better tracking
            document.querySelectorAll('th').forEach((th, index) => {
                th.setAttribute('data-column', index);
                if (th.onclick && th.onclick.toString().includes('sortTable')) {
                    if (!th.querySelector('.sort-icon')) {
                        const sortIcon = document.createElement('span');
                        sortIcon.className = 'sort-icon ml-1';
                        sortIcon.innerHTML = '<i class="fas fa-sort"></i>';
                        th.appendChild(sortIcon);
                    }
                }
            });
        });
    </script>
@endpush