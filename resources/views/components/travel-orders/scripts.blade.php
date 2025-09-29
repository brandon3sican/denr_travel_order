@push('scripts')
<script>
    // Function to open the completion modal
    function openCompleteModal(orderId) {
        document.getElementById('completeModal' + orderId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Set up file input change handler
        const fileInput = document.getElementById('file-upload-' + orderId);
        const fileNames = document.getElementById('file-names-' + orderId);

        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const invalidFiles = files.filter(file => file.type !== 'application/pdf');

            if (invalidFiles.length > 0) {
                alert('Only PDF files are allowed.');
                e.target.value = '';
                fileNames.textContent = 'No files selected';
                return;
            }

            if (files.length === 0) {
                fileNames.textContent = 'No files selected';
            } else {
                fileNames.textContent = files.length + ' file' + (files.length > 1 ? 's' : '') + ' selected';
            }
        });

        // Enable drag and drop
        const dropZone = fileInput.closest('div[class*="border-dashed"]');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight() {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;

            // Trigger change event
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }

    // Function to close the completion modal
    function closeCompleteModal(orderId) {
        document.getElementById('completeModal' + orderId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        // Reset file input
        document.getElementById('file-upload-' + orderId).value = '';
        document.getElementById('file-names-' + orderId).textContent = 'No files selected';
    }

    // Function to submit the completion form
    async function submitCompletion(orderId) {
        const fileInput = document.getElementById('file-upload-' + orderId);
        const files = fileInput.files;

        if (files.length === 0) {
            alert('Please upload at least one PDF file.');
            return;
        }

        // Validate file types
        const invalidFiles = Array.from(files).filter(file => file.type !== 'application/pdf');
        if (invalidFiles.length > 0) {
            alert('Only PDF files are allowed.');
            return;
        }

        const formData = new FormData();
        Array.from(files).forEach(file => {
            formData.append('documents[]', file);
        });

        try {
            const response = await fetch(`/travel-orders/${orderId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showSuccessMessage('Travel order marked as completed successfully!');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to complete travel order');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while completing the travel order.');
        } finally {
            closeCompleteModal(orderId);
        }
    }

    // Function to confirm delete
    function confirmDelete(orderId) {
        const form = document.getElementById('deleteForm');
        form.action = `/travel-orders/${orderId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Function to close delete modal
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Function to handle delete form submission
    function handleDelete(event) {
        const confirmInput = document.getElementById('confirmDeleteInput');
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        const deleteText = document.getElementById('deleteButtonText');
        const deleteLoader = document.getElementById('deleteButtonLoader');

        if (confirmInput.value !== 'DELETE') {
            event.preventDefault();
            return false;
        }

        // Show loading state
        deleteBtn.disabled = true;
        deleteText.classList.add('hidden');
        deleteLoader.classList.remove('hidden');
    }

    // Show success message
    function showSuccessMessage(message) {
        const successMessage = document.getElementById('successMessage');
        const messageText = document.getElementById('successMessageText');
        messageText.textContent = message;
        successMessage.classList.remove('hidden');

        // Hide after 5 seconds
        setTimeout(() => {
            successMessage.classList.add('hidden');
        }, 5000);
    }

    // Toggle dropdown
    function toggleDropdown(orderId) {
        const dropdown = document.getElementById(`dropdown-${orderId}`);
        dropdown.classList.toggle('hidden');
        
        // Close other dropdowns
        document.querySelectorAll(`[id^="dropdown-"]`).forEach(drop => {
            if (drop.id !== `dropdown-${orderId}`) {
                drop.classList.add('hidden');
            }
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.matches('.dropdown button')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
        const deleteModal = document.getElementById('deleteModal');
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
        
        // Close all completion modals
        document.querySelectorAll('[id^="completeModal"]').forEach(modal => {
            if (event.target === modal) {
                const orderId = modal.id.replace('completeModal', '');
                closeCompleteModal(orderId);
            }
        });
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
            document.querySelectorAll('[id^="completeModal"]').forEach(modal => {
                const orderId = modal.id.replace('completeModal', '');
                closeCompleteModal(orderId);
            });
        }
    });

    // Enable/disable delete button based on confirmation input
    const confirmDeleteInput = document.getElementById('confirmDeleteInput');
    if (confirmDeleteInput) {
        confirmDeleteInput.addEventListener('input', function() {
            const deleteBtn = document.getElementById('confirmDeleteBtn');
            if (this.value === 'DELETE') {
                deleteBtn.disabled = false;
                deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                deleteBtn.disabled = true;
                deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    }
</script>
@endpush
