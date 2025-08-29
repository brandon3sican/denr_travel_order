@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const assignmentFilter = document.getElementById('assignmentFilter');
            const searchInput = document.getElementById('searchUsers');
            let currentUrl = new URL(window.location.href);

            // Set initial filter value from URL
            const urlParams = new URLSearchParams(window.location.search);
            const assignmentParam = urlParams.get('assignment');
            if (assignmentParam) {
                assignmentFilter.value = assignmentParam;
            }

            // Handle assignment filter change
            assignmentFilter.addEventListener('change', function() {
                const selectedAssignment = this.value;
                const url = new URL(window.location.href);

                if (selectedAssignment) {
                    url.searchParams.set('assignment', selectedAssignment);
                } else {
                    url.searchParams.delete('assignment');
                }

                // Reset to first page when changing filters
                url.searchParams.set('page', '1');

                window.location.href = url.toString();
            });

            // Handle search input with debounce
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.trim();
                    const url = new URL(window.location.href);

                    if (searchTerm) {
                        url.searchParams.set('search', searchTerm);
                    } else {
                        url.searchParams.delete('search');
                    }

                    // Reset to first page when searching
                    url.searchParams.set('page', '1');

                    window.location.href = url.toString();
                }, 500);
            });

            // Set initial search value from URL
            const searchParam = urlParams.get('search');
            if (searchParam) {
                searchInput.value = searchParam;
            }
        });
    </script>
@endpush
