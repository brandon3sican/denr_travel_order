<!-- Edit Travel Order Modal Component -->
<div id="editOrderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 pt-10 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all sm:my-8">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="flex items-center justify-between border-b pb-4">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900">Edit Travel Order</h3>
                            <button onclick="closeEditOrderModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4 max-h-[70vh] overflow-y-auto" id="editOrderForm">
                            <!-- Edit form will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" id="updateOrderButton" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
                <button type="button" onclick="closeEditOrderModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to show edit travel order form in modal
    async function editTravelOrder(orderId) {
        try {
            console.log('Editing travel order:', orderId);
            
            // Show loading state
            const modal = document.getElementById('editOrderModal');
            const editForm = document.getElementById('editOrderForm');
            editForm.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-700">Loading travel order details...</span>
                </div>
            `;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Fetch travel order data
            const response = await fetch(`/travel-orders/${orderId}/edit`);
            if (!response.ok) {
                throw new Error('Failed to fetch travel order');
            }
            
            const data = await response.json();
            
            // Generate form HTML
            const formHtml = `
                <input type="hidden" name="status_id" value="1"> <!-- 1 = For Recommendation -->
                <input type="hidden" name="employee_email" value="{{ auth()->user()->email }}">
                
                <form id="updateTravelOrderForm" action="/travel-orders/${orderId}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-medium bg-gray-500 text-white mb-4 p-2 rounded-md text-center"> <i class="fas fa-info-circle"></i> Basic Details</h3>
                                <div>
                                    <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
                                    <textarea name="purpose" id="purpose" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">${data.purpose || ''}</textarea>
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="destination" class="block text-sm font-medium text-gray-700">Destination</label>
                                            <input type="text" name="destination" id="destination" value="${data.destination || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                        </div>
                                        <div>
                                            <label for="salary" class="block text-sm font-medium text-gray-700">Salary</label>
                                            <input type="number" name="salary" id="salary" value="${data.employee_salary || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                        </div>
                                    </div>
                                
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" value="${data.departure_date || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="end_date" id="end_date" value="${data.arrival_date || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                    </div>
                                </div>
                            </div>                        
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 shadow-sm">
                                <h3 class="text-lg font-medium bg-gray-500 text-white mb-4 p-2 rounded-md text-center"><i class="fas fa-info-circle"></i> Travel Details</h3>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                    <div>
                                        <label for="appropriation" class="block text-sm font-medium text-gray-700">Appropriation</label>
                                        <input type="text" name="appropriation" id="appropriation" value="${data.appropriation || ''}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="per_diem" class="block text-sm font-medium text-gray-700">Per Diem</label>
                                        <input type="number" name="per_diem" id="per_diem" value="${data.per_diem || '0'}" min="0" step="1" oninput="this.value = Math.max(0, Math.floor(this.value)) || 0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                    </div>
                                    <div>
                                        <label for="laborer_assistant" class="block text-sm font-medium text-gray-700">Laborer/Assistant</label>
                                        <input type="number" name="laborer_assistant" id="laborer_assistant" value="${data.laborer_assistant || '0'}" min="0" step="1" oninput="this.value = Math.max(0, Math.floor(this.value)) || 0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
                                    </div>
                                </div>
                                <div>
                                    <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                    <textarea name="remarks" id="remarks" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">${data.remarks || ''}</textarea>
                                </div>
                            </div>
                    </div>
                </form>
            `;
            
            // Update modal content
            editForm.innerHTML = formHtml;
            
            // Add form submission handler
            document.getElementById('updateOrderButton').onclick = async (e) => {
                e.preventDefault();
                const form = document.getElementById('updateTravelOrderForm');
                const submitButton = document.getElementById('updateOrderButton');
                const originalButtonText = submitButton.innerHTML;
                
                try {
                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
                    
                    // Prepare form data with all required fields
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('employee_email', '{{ auth()->user()->email }}');
                    formData.append('employee_salary', document.getElementById('salary').value || '0');
                    formData.append('destination', document.getElementById('destination').value || '');
                    formData.append('purpose', document.getElementById('purpose').value || '');
                    formData.append('departure_date', document.getElementById('start_date').value);
                    formData.append('arrival_date', document.getElementById('end_date').value);
                    formData.append('appropriation', document.getElementById('appropriation').value || '');
                    formData.append('per_diem', document.getElementById('per_diem').value || '0');
                    formData.append('laborer_assistant', document.getElementById('laborer_assistant').value || '0');
                    formData.append('remarks', document.getElementById('remarks').value || '');
                    formData.append('status_id', '1');
                    
                    // Log the form data for debugging
                    const formDataObj = {};
                    formData.forEach((value, key) => formDataObj[key] = value);
                    console.log('Form data being sent:', formDataObj);
                    
                    console.log('Sending update request to:', form.action);
                    console.log('Form data:', Object.fromEntries(formData.entries()));
                    
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(formData).toString()
                    });
                    
                    console.log('Response status:', response.status);
                    const result = await response.json().catch(() => ({}));
                    console.log('Response data:', result);
                    
                    if (!response.ok) {
                        const errorMessage = result.message || 
                                         (result.errors ? Object.values(result.errors).flat().join(' ') : '') ||
                                         'Failed to update travel order';
                        console.error('Update failed:', errorMessage);
                        throw new Error(errorMessage);
                    }
                    
                    // Show success modal
                    const successModal = `
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 shadow-xl">
                                <div class="flex items-center justify-center">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                        <i class="fas fa-check text-green-600 text-xl"></i>
                                    </div>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Success!</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Travel order has been updated successfully.</p>
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6">
                                    <button type="button" onclick="this.closest('.fixed').remove()" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add modal to the body
                    document.body.insertAdjacentHTML('beforeend', successModal);
                    
                    // Reload the page after 3 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } catch (error) {
                    console.error('Error updating travel order:', error);
                    const errorMessage = error.message || 'Failed to update travel order. Please try again.';
                    alert(errorMessage);
                }
            };
            
        } catch (error) {
            console.error('Error loading travel order:', error);
            editForm.innerHTML = `
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="h-5 w-5 text-red-400 fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error loading travel order details</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>${error.message || 'Please try again later.'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    // Close modal function
    function closeEditOrderModal() {
        document.getElementById('editOrderModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('editOrderModal');
        if (event.target === modal) {
            closeEditOrderModal();
        }
    });
</script>
@endpush