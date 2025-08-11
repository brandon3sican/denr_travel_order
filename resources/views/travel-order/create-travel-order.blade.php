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
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gray-200 shadow-xl rounded-lg p-6 mb-6">
                        <form id="travelOrderForm" action="{{ route('travel-orders.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="status_id" value="1"> <!-- 1 = For Recommendation -->
                            <input type="hidden" name="employee_email" value="{{ auth()->user()->email }}">
                            <input type="hidden" id="preview_data" name="preview_data" value="">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">Basic Information</h3>
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
                                <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">Travel Details</h3>
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
                                    <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">Approval Details</h3>
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

                                <!-- Form Actions -->
                                <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                                    <button type="button" class="bg-white py-2 px-4 border-2 border-gray-300 rounded-md shadow text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Cancel
                                    </button>
                                    <button type="button" id="previewBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Preview
                                    </button>
                                    <button type="submit" id="submitBtn" class="hidden inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Submit Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 p-4">
        <div class="flex items-center justify-center min-h-full">
            <div class="bg-white rounded-lg p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Preview Travel Order</h3>
                <button type="button" id="closePreviewModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="space-y-6">
                <!-- Preview Content Will Be Inserted Here -->
                <div id="previewContent"></div>
                
                <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" id="cancelPreviewBtn" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to Edit
                    </button>
                    <button type="button" id="confirmSubmitBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const recommenderSelect = document.getElementById('recommender');
        const approverSelect = document.getElementById('approver');
        
        // Store the original options
        const originalRecommenderOptions = Array.from(recommenderSelect.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            hidden: opt.hidden
        }));
        
        const originalApproverOptions = Array.from(approverSelect.options).map(opt => ({
            value: opt.value,
            text: opt.text,
            hidden: opt.hidden
        }));
        
        function updateDropdowns() {
            const selectedRecommender = recommenderSelect.value;
            const selectedApprover = approverSelect.value;
            
            // Reset both dropdowns to original state
            recommenderSelect.innerHTML = '';
            approverSelect.innerHTML = '';
            
            // Add default options
            const recommenderDefault = document.createElement('option');
            recommenderDefault.value = '';
            recommenderDefault.textContent = 'Select recommender';
            recommenderDefault.disabled = true;
            recommenderDefault.selected = !selectedRecommender;
            recommenderSelect.appendChild(recommenderDefault);
            
            const approverDefault = document.createElement('option');
            approverDefault.value = '';
            approverDefault.textContent = 'Select approver';
            approverDefault.disabled = true;
            approverDefault.selected = !selectedApprover;
            approverSelect.appendChild(approverDefault);
            
            // Repopulate recommenders
            originalRecommenderOptions.forEach(optData => {
                if (optData.value && optData.value !== selectedApprover) {
                    const option = new Option(optData.text, optData.value);
                    option.selected = optData.value === selectedRecommender;
                    recommenderSelect.add(option);
                }
            });
            
            // Repopulate approvers
            originalApproverOptions.forEach(optData => {
                if (optData.value && optData.value !== selectedRecommender) {
                    const option = new Option(optData.text, optData.value);
                    option.selected = optData.value === selectedApprover;
                    approverSelect.add(option);
                }
            });
        }
        
        // Add event listeners
        recommenderSelect.addEventListener('change', updateDropdowns);
        approverSelect.addEventListener('change', updateDropdowns);
        
        // Initial update
        updateDropdowns();

        // Preview Modal Functionality
        const previewModal = document.getElementById('previewModal');
        const previewBtn = document.getElementById('previewBtn');
        const closePreviewModal = document.getElementById('closePreviewModal');
        const cancelPreviewBtn = document.getElementById('cancelPreviewBtn');
        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('travelOrderForm');
        const previewContent = document.getElementById('previewContent');

        // Format date for display
        function formatDate(dateString) {
            if (!dateString) return '';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Format currency for display
        function formatCurrency(amount) {
            if (!amount) return '₱0.00';
            return '₱' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Get selected text from select element
        function getSelectedText(elementId) {
            const element = document.getElementById(elementId);
            return element.options[element.selectedIndex]?.text || '';
        }

        // Show preview modal
        previewBtn.addEventListener('click', function() {
            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Get form data
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            // Handle fund source field
            if (data.fund_source === 'Others') {
                data.fund_source = data.other_fund_source || 'Others';
            }

            // Store form data in hidden field for submission
            document.getElementById('preview_data').value = JSON.stringify(data);

            // Build preview HTML
            let previewHTML = `
                <div class="space-y-4 text-sm">
                    <div class="bg-white p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">EMPLOYEE INFORMATION</h4>
                        <div class="grid grid-cols-5 gap-1">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Name</p>
                                <p class="font-medium text-gray-800">{{ auth()->user()->employee->first_name ?? '' }} {{ auth()->user()->employee->middle_name ?? '' }} {{ auth()->user()->employee->last_name ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Position</p>
                                <p class="font-medium text-gray-800">{{ auth()->user()->employee->position_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Div/Sec/Unit</p>
                                <p class="font-medium text-gray-800">{{ auth()->user()->employee->div_sec_unit ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Official Station</p>
                                <p class="font-medium text-gray-800">{{ auth()->user()->employee->assignment_name ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Salary</p>
                                <p class="font-medium text-gray-800">${data.employee_salary ? `₱${parseFloat(data.employee_salary).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">TRAVEL DETAILS</h4>
                        <div class="grid grid-cols-5 gap-2">
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Purpose</p>
                                <p class="font-medium text-gray-800">${data.purpose || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Destination</p>
                                <p class="font-medium text-gray-800">${data.destination || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Duration</p>
                                <p class="font-medium text-gray-800">
                                    ${formatDate(data.departure_date)} to ${formatDate(data.arrival_date)}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Source of Fund</p>
                                <p class="font-medium text-gray-800">${data.fund_source === 'Others' ? (data.other_fund_source || 'N/A') : (data.fund_source || 'N/A')}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-0.5">Per Diem</p>
                                <p class="font-medium text-gray-800">${data.per_diem ? `₱${parseFloat(data.per_diem).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'N/A'}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-500 mb-0.5">Remarks</p>
                                <p class="font-medium text-gray-800">${data.remarks || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">APPROVAL DETAILS</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="border-r pr-4">
                                <p class="text-xs text-gray-500 mb-1">Recommending Approval</p>
                                <p class="text-sm font-medium text-gray-800">${getSelectedText('recommender')}</p>
                            </div>
                            <div class="pl-2">
                                <p class="text-xs text-gray-500 mb-1">Approved By</p>
                                <p class="text-sm font-medium text-gray-800">${getSelectedText('approver')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Update preview content
            previewContent.innerHTML = previewHTML;

            // Show preview modal
            previewModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        // Close preview modal
        function closeModal() {
            previewModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Handle form submission when confirm button is clicked
        document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
            // Submit the form
            document.getElementById('travelOrderForm').submit();
        });

        // Event listeners for closing modal
        closePreviewModal.addEventListener('click', closeModal);
        cancelPreviewBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        previewModal.addEventListener('click', function(e) {
            if (e.target === previewModal) {
                closeModal();
            }
        });

        // Confirm submission
        confirmSubmitBtn.addEventListener('click', function() {
            closeModal();
            // Hide preview button and show submit button
            previewBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            // Scroll to submit button
            submitBtn.scrollIntoView({ behavior: 'smooth' });
        });
    });
    </script>
    @endpush

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <h3 class="mt-3 text-lg font-medium text-gray-900">Success!</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <p>Your travel order has been submitted successfully.</p>
                    <p class="mt-1">Reference No: <span id="referenceNo" class="font-medium">TO-2023-00123</span></p>
                </div>
                <div class="mt-4">
                    <button type="button" id="closeSuccessModal" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
    
@endsection
