@extends('layout.app')

@section('content')
<!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Top Navigation -->
    <header class="bg-white shadow-sm z-10">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center">
                <button id="sidebarToggle" class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800">Digital Signature</h2>
            </div>
            <div class="flex items-center space-x-4">
                <button class="relative p-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto p-6">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="p-4">
                <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                    <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Create Digital Signature
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Draw Signature Card -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Draw Signature</h3>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-4">Draw your signature using your mouse or touch screen</p>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg overflow-hidden mb-4 bg-white">
                            <div id="signature-pad" class="w-full h-48">
                                <canvas id="signature-canvas" class="w-full h-full"></canvas>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-4">
                            <button type="button" id="clear" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Clear
                            </button>
                            <button type="button" id="save" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Save Signature
                            </button>
                        </div>
                </div>
                
                    <!-- Upload Signature Card -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Upload Signature</h3>
                        </div>
                        
                        <p class="text-sm text-gray-500 mb-4">Upload an image of your signature (PNG, JPG, GIF, BMP, SVG)</p>
                        
                        <div id="upload-container" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg bg-white">
                            <div class="space-y-1 text-center">
                                <!-- Upload Prompt -->
                                <div id="upload-prompt">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4 flex text-sm text-gray-600">
                                        <label for="signature-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="signature-upload" name="signature-upload" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        PNG, JPG, GIF, BMP, or SVG (Max. 5MB)
                                    </p>
                                </div>
                                
                                <!-- Upload Preview -->
                                <div id="upload-preview" class="hidden">
                                    <div class="flex flex-col items-center">
                                        <img id="signature-preview" src="#" alt="Signature preview" class="max-h-48 max-w-full rounded">
                                        <button type="button" id="change-upload" class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                            </svg>
                                            Change file
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        @if(isset($signature) && $signature)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Your Current Signature
                </h3>
            </div>
            <div class="px-6 py-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1">
                        <div class="inline-block bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
                            <img src="{{ $signature->signature_data }}" alt="Your Signature" class="h-20" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Last updated: {{ $signature->updated_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <button type="button" id="clear" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Remove Signature
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Signature Canvas */
    #signature-canvas {
        width: 100%;
        height: 100%;
        touch-action: none;
        background-color: white;
    }
    
    /* Upload Container */
    #upload-container {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 200px;
    }
    
    #upload-container.drag-over {
        background-color: #f8fafc;
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1);
    }
    
    /* Signature Preview */
    #signature-preview {
        max-height: 180px;
        max-width: 100%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .grid-cols-1 {
            grid-template-columns: 1fr;
        }
        
        .md\:grid-cols-2 {
            grid-template-columns: 1fr;
        }
        
        .md\:flex-row {
            flex-direction: column;
        }
        
        .md\:ml-6 {
            margin-left: 0;
            margin-top: 1rem;
        }
    }
</style>
@endpush

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
            
            if (confirm('Are you sure you want to remove your signature? This action cannot be undone.')) {
                // Show loading state
                clearSignatureBtn.disabled = true;
                clearSignatureBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Removing...
                `;
                
                fetch('{{ route("signature.clear") }}', {
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
            fetch('{{ route("signature.store") }}', {
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
                        throw new Error(err.message || 'Network response was not ok');
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
                showAlert(error.message || 'Failed to save signature. Please try again.', 'error');
                reject(error);
            });
        });
    }
});
</script>
@endpush
@endsection
