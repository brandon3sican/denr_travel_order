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
                        <h2 class="text-xl font-semibold text-gray-800">Create Travel Order</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('my-travel-orders') }}" class="text-white hover:text-red-300 flex items-center space-x-3 font-semibold bg-red-600 px-4 py-2 rounded-md">
                            Cancel
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6">
                        <form id="travelOrderForm" action="{{ route('travel-orders.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="status_id" value="1"> <!-- 1 = For Recommendation -->
                            <input type="hidden" name="employee_email" value="{{ auth()->user()->email }}">
                            <input type="hidden" id="preview_data" name="preview_data" value="">
                            
                            <!-- Basic Information -->
                            <div class="">
                                <div class="mb-4">
                                    <h3 class="text-2xl font-semibold">Travel Order Request Form</h3>
                                    <p class="text-sm text-gray-600">Fill up the form below to create a new travel order.</p>
                                </div>
                                <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>Basic Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose of Travel</label>
                                        <input type="text" id="purpose" name="purpose" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Purpose of travel">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                                        <input type="text" id="destination" name="destination" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Destination">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="employee_salary" class="block text-sm font-medium text-gray-700 mb-1">Employee Salary</label>
                                        <input type="number" id="employee_salary" name="employee_salary" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Employee Salary">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">Departure Date</label>
                                        <input type="date" id="departure_date" name="departure_date" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="arrival_date" class="block text-sm font-medium text-gray-700 mb-1">Arrival Date</label>
                                        <input type="date" id="arrival_date" name="arrival_date" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Details -->
                            <div class="pt-6 border-t-2 border-gray-600">
                                <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">
                                    <i class="fas fa-plane mr-2"></i>Travel Details
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Fund Source -->
                                    <div class="md:col-span-1">
                                        <label for="appropriation" class="block text-sm font-medium text-gray-700 mb-1">Source of Fund</label>
                                        <select id="appropriation" name="appropriation" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            onchange="toggleOtherAppropriation(this.value)">
                                            <option value="">Select source of fund</option>
                                            <option value="Regular Fund">Regular Fund</option>
                                            <option value="Others">Others</option>
                                        </select>
                                        <div id="otherAppropriationContainer" class="mt-2 hidden">
                                            <label for="otherAppropriation" class="block text-sm font-medium text-gray-700 mb-1">Please specify: </label>
                                            <input type="text" id="otherAppropriation" name="otherAppropriation"
                                                class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="Enter source of fund">
                                        </div>
                                    </div>

                                    <!-- Per Diem -->
                                    <div class="md:col-span-1">
                                        <label for="per_diem" class="block text-sm font-medium text-gray-700 mb-1">Per Diem</label>
                                        <input type="number" id="per_diem" name="per_diem" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Per Diem">
                                    </div>

                                    <!-- Number of Labor -->
                                    <div class="md:col-span-1">
                                        <label for="laborer_assistant" class="block text-sm font-medium text-gray-700 mb-1">Number of Labor/Assistant</label>
                                        <input type="number" id="laborer_assistant" name="laborer_assistant" required
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Number of Labor/Assistant">
                                    </div>
                                </div>

                                <script>
                                    function toggleOtherAppropriation(value) {
                                        const otherContainer = document.getElementById('otherAppropriationContainer');
                                        const otherInput = document.getElementById('otherAppropriation');
                                        if (value === 'Others') {
                                            otherContainer.classList.remove('hidden');
                                            otherInput.setAttribute('required', 'required');
                                        } else {
                                            otherContainer.classList.add('hidden');
                                            otherInput.removeAttribute('required');
                                            otherInput.value = '';
                                        }
                                    }
                                    
                                    // Handle form submission to combine values if 'Others' is selected
                                    document.querySelector('form').addEventListener('submit', function(e) {
                                        const fundSource = document.getElementById('fundSource');
                                        const otherInput = document.getElementById('otherAppropriation');
                                        
                                        if (fundSource.value === 'Others' && otherInput.value.trim() !== '') {
                                            // Create a hidden input with the combined value
                                            const hiddenInput = document.createElement('input');
                                            hiddenInput.type = 'hidden';
                                            hiddenInput.name = 'fundSource';
                                            hiddenInput.value = 'Others: ' + otherInput.value.trim();
                                            this.appendChild(hiddenInput);
                                        }
                                    });
                                </script>
                                <div class="md:col-span-2">
                                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                                    <textarea id="remarks" name="remarks" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Remarks" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- Approvals -->
                            <div class="pt-6 border-t-2 border-gray-600">
                                <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">
                                    <i class="fas fa-check mr-2"></i>Approval Details
                                </h3>
                                <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Recommender Selection -->
                                        <div class="relative">
                                            <label for="recommender" class="block text-sm font-medium text-gray-700 mb-1">Recommender</label>
                                            <div class="relative">
                                                <select id="recommender" name="recommender" required
                                                    class="appearance-none block w-full bg-white border-2 border-gray-300 rounded-md shadow py-2 pl-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    <option value="" disabled selected>Select recommender</option>
                                                    @foreach($recommenders as $recommender)
                                                        <option value="{{ $recommender->email }}">
                                                            {{ $recommender->first_name }} {{ $recommender->last_name }}
                                                            @if($recommender->position)
                                                                ({{ $recommender->position }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">The person who will recommend your travel order for approval</p>
                                        </div>

                                        <!-- Approver Selection -->
                                        <div class="relative">
                                            <label for="approver" class="block text-sm font-medium text-gray-700 mb-1">Approver</label>
                                            <div class="relative">
                                                <select id="approver" name="approver" required
                                                    class="appearance-none block w-full bg-white border-2 border-gray-300 rounded-md shadow py-2 pl-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    <option value="" disabled selected>Select approver</option>
                                                    @foreach($approvers as $approver)
                                                        <option value="{{ $approver->email }}">
                                                            {{ $approver->first_name }} {{ $approver->last_name }}
                                                            @if($approver->position)
                                                                ({{ $approver->position }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">The person who will approve your travel order</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @push('scripts')
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Tab switching
                                    const tabButtons = document.querySelectorAll('.tab-button');
                                    const tabContents = document.querySelectorAll('.tab-content');

                                    tabButtons.forEach(button => {
                                        button.addEventListener('click', () => {
                                            const tabId = button.getAttribute('data-tab');
                                            
                                            // Update active tab
                                            tabButtons.forEach(btn => {
                                                btn.classList.remove('border-blue-500', 'text-blue-600');
                                                btn.classList.add('border-transparent', 'text-gray-500');
                                            });
                                            button.classList.remove('border-transparent', 'text-gray-500');
                                            button.classList.add('border-blue-500', 'text-blue-600');
                                            
                                            // Show selected tab content
                                            tabContents.forEach(content => {
                                                content.classList.add('hidden');
                                            });
                                            document.getElementById(tabId).classList.remove('hidden');
                                        });
                                    });

                                    // Signature Canvas Setup
                                    const canvas = document.getElementById('signatureCanvas');
                                    if (canvas) {
                                        const ctx = canvas.getContext('2d');
                                        let isDrawing = false;
                                        let lastX = 0;
                                        let lastY = 0;

                                        // Initialize canvas
                                        function initCanvas() {
                                            // Set canvas size
                                            canvas.width = 400;
                                            canvas.height = 150;
                                            
                                            // Set drawing style
                                            ctx.strokeStyle = '#1e40af'; // Darker blue for better visibility
                                            ctx.lineWidth = 2.5;
                                            ctx.lineCap = 'round';
                                            ctx.lineJoin = 'round';
                                            
                                            // Clear canvas with a subtle pattern
                                            ctx.fillStyle = '#ffffff';
                                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                                            
                                            // Add a subtle grid pattern
                                            ctx.strokeStyle = '#f0f4ff';
                                            ctx.lineWidth = 1;
                                            const gridSize = 20;
                                            
                                            // Draw vertical lines
                                            for (let x = 0; x <= canvas.width; x += gridSize) {
                                                ctx.beginPath();
                                                ctx.moveTo(x, 0);
                                                ctx.lineTo(x, canvas.height);
                                                ctx.stroke();
                                            }
                                            
                                            // Draw horizontal lines
                                            for (let y = 0; y <= canvas.height; y += gridSize) {
                                                ctx.beginPath();
                                                ctx.moveTo(0, y);
                                                ctx.lineTo(canvas.width, y);
                                                ctx.stroke();
                                            }
                                            
                                            // Reset stroke style for drawing
                                            ctx.strokeStyle = '#1e40af';
                                            ctx.lineWidth = 2.5;
                                            
                                            // Hide the guide when drawing starts
                                            const guide = document.getElementById('signatureGuide');
                                            if (guide) {
                                                guide.style.display = 'none';
                                            }
                                        }

                                        // Initialize canvas on load
                                        initCanvas();

                                        // Get canvas position
                                        function getCanvasCoordinates(e) {
                                            const rect = canvas.getBoundingClientRect();
                                            return {
                                                x: e.clientX - rect.left,
                                                y: e.clientY - rect.top
                                            };
                                        }

                                        // Drawing functions
                                        function startDrawing(e) {
                                            isDrawing = true;
                                            const pos = getCanvasCoordinates(e);
                                            [lastX, lastY] = [pos.x, pos.y];
                                            
                                            // Hide the guide when drawing starts
                                            const guide = document.getElementById('signatureGuide');
                                            if (guide) {
                                                guide.style.display = 'none';
                                            }
                                            
                                            // Start a new path for this stroke
                                            ctx.beginPath();
                                            ctx.moveTo(pos.x, pos.y);
                                        }

                                        function draw(e) {
                                            if (!isDrawing) return;
                                            
                                            const pos = getCanvasCoordinates(e);
                                            
                                            // Only draw if the mouse has moved a minimum distance
                                            const minDistance = 2;
                                            const dx = pos.x - lastX;
                                            const dy = pos.y - lastY;
                                            const distance = Math.sqrt(dx * dx + dy * dy);
                                            
                                            if (distance > minDistance) {
                                                // Draw a line from the last position to the current position
                                                ctx.beginPath();
                                                ctx.moveTo(lastX, lastY);
                                                ctx.lineTo(pos.x, pos.y);
                                                ctx.stroke();
                                                
                                                [lastX, lastY] = [pos.x, pos.y];
                                                updateSignatureData();
                                            }
                                        }

                                        function stopDrawing() {
                                            isDrawing = false;
                                        }

                                        // Canvas event listeners
                                        canvas.addEventListener('mousedown', startDrawing);
                                        canvas.addEventListener('mousemove', draw);
                                        canvas.addEventListener('mouseup', stopDrawing);
                                        canvas.addEventListener('mouseout', stopDrawing);

                                        // Touch support for mobile
                                        canvas.addEventListener('touchstart', (e) => {
                                            e.preventDefault();
                                            const touch = e.touches[0];
                                            const mouseEvent = new MouseEvent('mousedown', {
                                                clientX: touch.clientX,
                                                clientY: touch.clientY
                                            });
                                            canvas.dispatchEvent(mouseEvent);
                                        }, { passive: false });

                                        canvas.addEventListener('touchmove', (e) => {
                                            e.preventDefault();
                                            const touch = e.touches[0];
                                            const mouseEvent = new MouseEvent('mousemove', {
                                                clientX: touch.clientX,
                                                clientY: touch.clientY
                                            });
                                            canvas.dispatchEvent(mouseEvent);
                                        }, { passive: false });

                                        canvas.addEventListener('touchend', (e) => {
                                            e.preventDefault();
                                            const mouseEvent = new MouseEvent('mouseup', {});
                                            canvas.dispatchEvent(mouseEvent);
                                        }, { passive: false });

                                        // Prevent scrolling when touching the canvas
                                        document.body.addEventListener('touchstart', function(e) {
                                            if (e.target === canvas) {
                                                e.preventDefault();
                                            }
                                        }, { passive: false });
                                        document.body.addEventListener('touchend', function(e) {
                                            if (e.target === canvas) {
                                                e.preventDefault();
                                            }
                                        }, { passive: false });
                                        document.body.addEventListener('touchmove', function(e) {
                                            if (e.target === canvas) {
                                                e.preventDefault();
                                            }
                                        }, { passive: false });

                                        // Clear canvas
                                        const clearCanvasBtn = document.getElementById('clearCanvas');
                                        if (clearCanvasBtn) {
                                            clearCanvasBtn.addEventListener('click', () => {
                                                // Show a confirmation dialog
                                                if (confirm('Are you sure you want to clear your signature?')) {
                                                    // Clear the canvas
                                                    ctx.fillStyle = '#ffffff';
                                                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                                                    document.getElementById('signatureData').value = '';
                                                    document.getElementById('signatureError').classList.add('hidden');
                                                    
                                                    // Show the guide again when clearing
                                                    const guide = document.getElementById('signatureGuide');
                                                    if (guide) {
                                                        guide.style.display = 'flex';
                                                    }
                                                    
                                                    // Re-initialize the canvas to restore the grid
                                                    initCanvas();
                                                    
                                                    // Show a brief confirmation message
                                                    const originalText = clearCanvasBtn.innerHTML;
                                                    clearCanvasBtn.innerHTML = '<i class="fas fa-check mr-1"></i>Cleared!';
                                                    setTimeout(() => {
                                                        clearCanvasBtn.innerHTML = originalText;
                                                    }, 1500);
                                                }
                                            });
                                        }

                                        // Handle file upload
                                        const fileInput = document.getElementById('signatureUpload');
                                        const fileNameSpan = document.getElementById('fileName');
                                        const fileInfoDiv = document.getElementById('fileInfo');
                                        const clearFileBtn = document.getElementById('clearFile');

                                        if (fileInput && fileNameSpan && fileInfoDiv && clearFileBtn) {
                                            fileInput.addEventListener('change', function(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    if (file.size > 1024 * 1024) { // 1MB limit
                                                        alert('File size must be less than 1MB');
                                                        return;
                                                    }
                                                    
                                                    if (file.type !== 'image/png') {
                                                        alert('Please upload a PNG file');
                                                        return;
                                                    }

                                                    const reader = new FileReader();
                                                    reader.onload = function(event) {
                                                        const img = new Image();
                                                        img.onload = function() {
                                                            // Clear canvas and draw the uploaded image
                                                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                                                            
                                                            // Calculate dimensions to maintain aspect ratio
                                                            const ratio = Math.min(canvas.width / img.width, canvas.height / img.height);
                                                            const x = (canvas.width - img.width * ratio) / 2;
                                                            const y = (canvas.height - img.height * ratio) / 2;
                                                            
                                                            ctx.drawImage(img, x, y, img.width * ratio, img.height * ratio);
                                                            updateSignatureData();
                                                        };
                                                        img.src = event.target.result;
                                                    };
                                                    reader.readAsDataURL(file);
                                                    
                                                    fileNameSpan.textContent = file.name;
                                                    fileInfoDiv.classList.remove('hidden');
                                                }
                                            });

                                            clearFileBtn.addEventListener('click', function() {
                                                fileInput.value = '';
                                                fileInfoDiv.classList.add('hidden');
                                                document.getElementById('signatureData').value = '';
                                            });
                                        }

                                        // Update hidden input with signature data
                                        function updateSignatureData() {
                                            document.getElementById('signatureData').value = canvas.toDataURL('image/png');
                                            document.getElementById('signatureError').classList.add('hidden');
                                        }

                                        // Form validation
                                        const form = document.querySelector('form');
                                        if (form) {
                                            form.addEventListener('submit', function(e) {
                                                if (!document.getElementById('signatureData').value) {
                                                    e.preventDefault();
                                                    document.getElementById('signatureError').classList.remove('hidden');
                                                    document.getElementById('signatureError').scrollIntoView({ behavior: 'smooth', block: 'center' });
                                                }
                                            });
                                        }
                                    }
                                });
                            </script>
                            @endpush
                            
                            <!-- Form Actions -->
                            <div class="p-2 border-t-2 border-gray-600 flex justify-end space-x-3">
                                <button type="button" id="previewBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Preview
                                </button>
                                <button type="submit" id="submitBtn" class="hidden inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

@include('components.travel-order.create-preview-modal')
    
@endsection
