@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle date picker
            const dateRangeBtn = document.getElementById('dateRangePicker');
            if (dateRangeBtn) {
                const dateRangeDropdown = dateRangeBtn.querySelector('div[class*="hidden"]');
                const dateFromInput = document.getElementById('dateFrom');
                const dateToInput = document.getElementById('dateTo');
                const applyDatesBtn = document.getElementById('applyDates');
                const clearDatesBtn = document.getElementById('clearDates');
                const searchInput = document.getElementById('searchInput');
                const table = document.querySelector('table');
                const tableRows = table ? table.querySelectorAll('tbody tr:not(.no-results)') : [];

                // Function to parse date from table cell
                function parseDateFromCell(dateString) {
                    if (!dateString) return null;
                    // Try to parse the date string
                    const date = new Date(dateString);
                    if (!isNaN(date.getTime())) {
                        return new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    }
                    return null;
                }

                // Toggle date picker dropdown
                dateRangeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dateRangeDropdown.classList.toggle('hidden');
                });

                // Close date picker when clicking outside
                document.addEventListener('click', function() {
                    if (!dateRangeDropdown.classList.contains('hidden')) {
                        dateRangeDropdown.classList.add('hidden');
                    }
                });

                // Prevent dropdown from closing when clicking inside it
                dateRangeDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Apply filters based on date range and search
                function applyFilters() {
                    const fromDate = dateFromInput.value ? new Date(dateFromInput.value) : null;
                    const toDate = dateToInput.value ? new Date(dateToInput.value) : null;
                    const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';

                    // Set time to start of day for fromDate and end of day for toDate
                    if (fromDate) fromDate.setHours(0, 0, 0, 0);
                    if (toDate) toDate.setHours(23, 59, 59, 999);

                    let hasVisibleRows = false;
                    let hasActiveFilters = false;

                    // Check if any filters are active
                    if (fromDate || toDate || searchTerm) {
                        hasActiveFilters = true;
                    }

                    tableRows.forEach(row => {
                        // Skip the no-results row if it exists
                        if (row.classList.contains('no-results')) {
                            row.style.display = 'none';
                            return;
                        }

                        const dateCell = row.querySelector('td:first-child');
                        if (!dateCell) return;

                        const rowDate = parseDateFromCell(dateCell.textContent.trim());
                        if (!rowDate) return;

                        let isVisible = true;

                        // Apply date range filter
                        if (fromDate && rowDate < fromDate) {
                            isVisible = false;
                        }
                        if (toDate && rowDate > toDate) {
                            isVisible = false;
                        }

                        // Apply search filter if there's a search term
                        if (isVisible && searchTerm) {
                            const rowText = row.textContent.toLowerCase();
                            isVisible = rowText.includes(searchTerm);
                        }

                        row.style.display = isVisible ? '' : 'none';
                        if (isVisible) hasVisibleRows = true;
                    });

                    // Show/hide no results message
                    const tbody = table ? table.querySelector('tbody') : null;
                    let noResultsRow = tbody ? tbody.querySelector('tr.no-results') : null;

                    // Only show no results message if there are active filters and no rows are visible
                    if (!hasVisibleRows && hasActiveFilters) {
                        if (!noResultsRow && tbody) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.className = 'no-results';
                            noResultsRow.innerHTML = `
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No matching records found${searchTerm ? ' for "' + searchTerm + '"' : ''}
                                    </td>
                                `;
                            tbody.appendChild(noResultsRow);
                        } else if (noResultsRow) {
                            noResultsRow.style.display = '';
                        }
                    } else if (noResultsRow) {
                        noResultsRow.style.display = 'none';
                    }
                }

                // Event listeners
                if (applyDatesBtn) {
                    applyDatesBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dateRangeDropdown.classList.add('hidden');
                        applyFilters();
                    });
                }

                if (clearDatesBtn) {
                    clearDatesBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dateFromInput.value = '';
                        dateToInput.value = '';
                        dateRangeDropdown.classList.add('hidden');
                        applyFilters();
                    });
                }

                // Search functionality
                if (searchInput) {
                    searchInput.addEventListener('input', applyFilters);
                }

                // Apply filters when both dates are selected
                [dateFromInput, dateToInput].forEach(input => {
                    if (input) {
                        input.addEventListener('change', function() {
                            if (dateFromInput.value && dateToInput.value) {
                                applyFilters();
                            }
                        });
                    }
                });

                // Initial filter application
                applyFilters();
            }
        });
    </script>
@endpush
