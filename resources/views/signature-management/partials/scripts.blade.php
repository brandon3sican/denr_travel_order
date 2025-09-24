@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assignmentFilter = document.getElementById('assignmentFilter');
        const searchInput = document.getElementById('searchUsers');

        // Initialize from URL
        const url = new URL(window.location.href);
        const urlParams = url.searchParams;
        const initAssignment = urlParams.get('assignment');
        const initSearch = urlParams.get('search');
        if (initAssignment && assignmentFilter) assignmentFilter.value = initAssignment;
        if (initSearch && searchInput) searchInput.value = initSearch;

        // Update URL on filter change
        assignmentFilter?.addEventListener('change', function () {
            const u = new URL(window.location.href);
            if (this.value) {
                u.searchParams.set('assignment', this.value);
            } else {
                u.searchParams.delete('assignment');
            }
            u.searchParams.set('page', '1');
            window.location.href = u.toString();
        });

        // Debounced search
        let t;
        searchInput?.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(() => {
                const u = new URL(window.location.href);
                const term = this.value.trim();
                if (term) {
                    u.searchParams.set('search', term);
                } else {
                    u.searchParams.delete('search');
                }
                u.searchParams.set('page', '1');
                window.location.href = u.toString();
            }, 450);
        });

        // Reset modal helpers
        window.openResetModal = function (btn) {
            const modal = document.getElementById('resetSigModal');
            const nameEl = document.getElementById('resetEmpName');
            const emailEl = document.getElementById('resetEmpEmail');
            const form = document.getElementById('resetSigForm');
            const confirmEmailInput = document.getElementById('confirmEmailInput');
            const ack = document.getElementById('ackReset');

            const empId = btn.getAttribute('data-employee-id');
            const empName = btn.getAttribute('data-employee-name');
            const empEmail = btn.getAttribute('data-employee-email');

            nameEl.textContent = empName;
            emailEl.textContent = empEmail;
            confirmEmailInput.value = '';
            ack.checked = false;

            // Set form action
            const base = window.location.origin;
            form.action = base + '/signature-management/' + encodeURIComponent(empId) + '/reset';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeResetModal = function () {
            const modal = document.getElementById('resetSigModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        // Close when clicking outside content
        document.getElementById('resetSigModal')?.addEventListener('click', function (e) {
            if (e.target === this) closeResetModal();
        });
    });
</script>
@endpush
