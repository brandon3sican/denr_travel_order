@push('scripts')
    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('statusId').value = '';
            document.getElementById('modalTitle').textContent = 'Add New Status';
            document.getElementById('statusForm').reset();
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function editStatus(id, name) {
            document.getElementById('statusId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('modalTitle').textContent = 'Edit Status';
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('nameError').classList.add('hidden');
        }

        // Delete modal functions
        let statusToDelete = null;

        function confirmDelete(id) {
            statusToDelete = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            statusToDelete = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Form submission
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                _token: '{{ csrf_token() }}',
                name: document.getElementById('name').value
            };

            const statusId = document.getElementById('statusId').value;
            const url = statusId ?
                '/status-management/' + statusId :
                '/status-management';
            const method = statusId ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else if (data.errors) {
                        // Show validation errors
                        const errorField = document.getElementById('nameError');
                        errorField.textContent = data.errors.name ? data.errors.name[0] : '';
                        errorField.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Delete status
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!statusToDelete) return;

            fetch(`/status-management/${statusToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error deleting status');
                        closeDeleteModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the status');
                    closeDeleteModal();
                });
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const statusModal = document.getElementById('statusModal');
            const deleteModal = document.getElementById('deleteModal');

            if (event.target === statusModal) {
                closeModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Close with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeDeleteModal();
            }
        });
    </script>
@endpush
