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
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold bg-gray-700 text-white mb-3 pb-2 border-b text-center py-2 rounded-t-md rounded-b-md">EMPLOYEE INFORMATION</h4>
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

                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold bg-gray-700 text-white mb-3 pb-2 border-b text-center py-2 rounded-t-md rounded-b-md">TRAVEL DETAILS</h4>
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

                    <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold bg-gray-700 text-white mb-3 pb-2 border-b text-center py-2 rounded-t-md rounded-b-md">APPROVAL DETAILS</h4>
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