@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Signature Pad
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Handle window resize with debounce
            let resizeTimeout;

            function resizeCanvas() {
                const container = canvas.parentElement;
                const ratio = Math.max(window.devicePixelRatio || 1, 1);

                // Set canvas dimensions to match container
                canvas.width = container.offsetWidth * ratio;
                canvas.height = container.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);

                // Redraw signature if it exists
                if (!signaturePad.isEmpty()) {
                    const data = signaturePad.toData();
                    signaturePad.clear();
                    signaturePad.fromData(data);
                }
            }

            function debounceResize() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(resizeCanvas, 250);
            }

            window.addEventListener('resize', debounceResize);

            // Initial resize
            resizeCanvas();

            // Add touch support for mobile devices
            if ('ontouchstart' in window) {
                document.body.style.touchAction = 'none';
            }

            // Clear button
            document.getElementById('clear').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear your signature?')) {
                    signaturePad.clear();
                }
            });

            // Save signature
            document.getElementById('save').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    showAlert('Please provide a signature first.', 'error');
                    return;
                }

                // Show loading state
                const saveBtn = document.getElementById('save');
                const originalText = saveBtn.innerHTML;
                saveBtn.disabled = true;
                saveBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        `;

                const signatureData = signaturePad.toDataURL('image/png');

                saveSignature(signatureData)
                    .then(() => {
                        saveBtn.innerHTML = originalText;
                        saveBtn.disabled = false;
                        showAlert('Signature saved successfully!', 'success');
                        // Reload the page to show the updated signature
                        setTimeout(() => window.location.reload(), 1000);
                    })
                    .catch(error => {
                        console.error('Error saving signature:', error);
                        saveBtn.innerHTML = originalText;
                        saveBtn.disabled = false;
                        showAlert('Failed to save signature. Please try again.', 'error');
                    });
            });

            // File upload handling
            const uploadInput = document.getElementById('signature-upload');
            const uploadPrompt = document.getElementById('upload-prompt');
            const uploadPreview = document.getElementById('upload-preview');
            const signaturePreview = document.getElementById('signature-preview');
            const changeUploadBtn = document.getElementById('change-upload');
            const uploadContainer = document.getElementById('upload-container');

            // Show alert message
            function showAlert(message, type = 'info') {
                // Remove any existing alerts
                const existingAlert = document.querySelector('.alert-message');
                if (existingAlert) {
                    existingAlert.remove();
                }

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert-message fixed top-4 right-4 px-6 py-4 rounded-md shadow-lg ${
            type === 'error' ? 'bg-red-100 border-l-4 border-red-500 text-red-700' : 
            type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' :
            'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
        }`;

                alertDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'error' ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' : 'M5 13l4 4L19 7'}"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;

                document.body.appendChild(alertDiv);

                // Auto-remove after 5 seconds
                setTimeout(() => {
                    alertDiv.style.opacity = '0';
                    setTimeout(() => alertDiv.remove(), 300);
                }, 5000);
            }

            // Handle drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadContainer.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                uploadContainer.classList.add('drag-over');
            }

            function unhighlight() {
                uploadContainer.classList.remove('drag-over');
            }

            uploadContainer.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 1) {
                    showAlert('Please upload only one file at a time.', 'error');
                    return;
                }

                uploadInput.files = files;
                const event = new Event('change');
                uploadInput.dispatchEvent(event);
            }

            // Handle file selection
            uploadInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                if (!file.type.match('image.*')) {
                    showAlert('Please select an image file (PNG, JPG, GIF, BMP, SVG)', 'error');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    showAlert('File size should be less than 5MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    signaturePreview.src = e.target.result;
                    uploadPrompt.classList.add('hidden');
                    uploadPreview.classList.remove('hidden');

                    // Show loading state
                    const uploadBtn = document.querySelector('button[type="button"]');
                    const originalText = uploadBtn ? uploadBtn.innerHTML : '';

                    if (uploadBtn) {
                        uploadBtn.disabled = true;
                        uploadBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Uploading...
                `;
                    }

                    // Auto-save the uploaded signature
                    saveSignature(e.target.result)
                        .then(() => {
                            showAlert('Signature uploaded successfully!', 'success');
                            // Reload the page to show the updated signature
                            setTimeout(() => window.location.reload(), 1000);
                        })
                        .catch(error => {
                            console.error('Error uploading signature:', error);
                            showAlert('Failed to upload signature. Please try again.', 'error');

                            if (uploadBtn) {
                                uploadBtn.innerHTML = originalText;
                                uploadBtn.disabled = false;
                            }
                        });
                };
                reader.readAsDataURL(file);
            });

            // Change upload button
            changeUploadBtn.addEventListener('click', function() {
                uploadPrompt.classList.remove('hidden');
                uploadPreview.classList.add('hidden');
                uploadInput.value = '';
            });

            // Handle clear signature button in the current signature section
            const clearSignatureBtn = document.querySelector('#clear-signature');
            if (clearSignatureBtn) {
                clearSignatureBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (confirm(
                            'Are you sure you want to remove your signature? This action cannot be undone.'
                            )) {
                        // Show loading state
                        clearSignatureBtn.disabled = true;
                        clearSignatureBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Removing...
                `;

                        fetch('{{ route('signature.clear') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showAlert('Signature removed successfully!', 'success');
                                    setTimeout(() => window.location.reload(), 1000);
                                } else {
                                    throw new Error(data.message || 'Failed to remove signature');
                                }
                            })
                            .catch(error => {
                                console.error('Error removing signature:', error);
                                showAlert('Failed to remove signature. Please try again.', 'error');
                                clearSignatureBtn.disabled = false;
                                clearSignatureBtn.innerHTML = 'Remove Signature';
                            });
                    }
                });
            }

            // Save signature function
            function saveSignature(signatureData) {
                return new Promise((resolve, reject) => {
                    fetch('{{ route('signature.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                signature: signatureData,
                                source: signaturePad.isEmpty() ? 'upload' : 'draw'
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw new Error(err.message ||
                                        'Network response was not ok');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                resolve(data);
                            } else {
                                throw new Error(data.message || 'Failed to save signature');
                            }
                        })
                        .catch(error => {
                            console.error('Error saving signature:', error);
                            showAlert(error.message || 'Failed to save signature. Please try again.',
                                'error');
                            reject(error);
                        });
                });
            }
        });
    </script>
@endpush
