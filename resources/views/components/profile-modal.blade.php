<script>
    // Close modal when clicking outside
    document.getElementById('profileModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('profileModal').classList.add('hidden');
        }
    });
</script>