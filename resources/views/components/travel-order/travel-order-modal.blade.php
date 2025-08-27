<!-- Travel Order Modal Component -->
<div id="orderModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4 pt-10 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left align-middle shadow-xl transition-all sm:my-8">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="flex items-center justify-between border-b pb-4">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900">Travel Order Details</h3>
                            <button onclick="closeOrderModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <div class="mt-4 max-h-[70vh] overflow-y-auto" id="orderDetails">
                            <!-- Order details will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button type="button" id="printButton" class="hidden inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <button type="button" onclick="closeOrderModal()" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to show travel order details in modal
    async function showTravelOrder(orderId) {
        try {
            console.log('Opening travel order:', orderId);
            
            // Show loading state
            const modal = document.getElementById('orderModal');
            const orderDetails = document.getElementById('orderDetails');
            orderDetails.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-700">Loading travel order details...</span>
                </div>
            `;
            
            // Show the modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Fetch order details
            console.log('Fetching order details...');
            const response = await fetch(`/travel-orders/${orderId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                },
                credentials: 'same-origin'
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Error response:', errorData);
                throw new Error(errorData.message || 'Failed to fetch order details');
            }
            
            const order = await response.json();
            console.log('Order data:', order);
            
            // Format dates
            const formatDate = (dateString) => {
                if (!dateString) return 'N/A';
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('en-US', options);
            };

            // Get status class and text
            const statusInfo = {
                1: { class: 'bg-yellow-100 text-yellow-800', text: 'Pending' },
                2: { class: 'bg-blue-100 text-blue-800', text: 'Approved' },
                3: { class: 'bg-green-100 text-green-800', text: 'Completed' },
                4: { class: 'bg-red-100 text-red-800', text: 'Rejected' },
                5: { class: 'bg-gray-100 text-gray-800', text: 'For Recommendation' }
            }[order.status_id] || { class: 'bg-gray-100 text-gray-800', text: 'Unknown' };

            // Format currency
            const formatCurrency = (amount) => {
                if (!amount) return '₱0.00';
                return '₱' + parseFloat(amount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            };

            // Create HTML for order details
            const detailsHtml = `
            <div style="margin: 90px;">
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt;">
                    <tbody>
                        <tr style="height:2.9pt;">
                            <td rowspan="4" style="width:82.7pt; padding:0pt 5.4pt;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;">
                                    <img src="{{ asset('images/denr-logo.png') }}" width="70" height="68" alt="denr-logo">
                                </p>
                            </td>
                            <td style="width:273.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;">
                                    <span style="font-family:'Times New Roman';">Republic of the Philippines</span>
                                </p>
                            </td>
                            <td rowspan="4" style="width:82.7pt; padding:0pt 5.4pt;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;">
                                    <img src="{{ asset('images/bp-logo.png') }}" width="90" height="90" alt="bagong-pilipinas-logo">
                                </p>
                            </td>
                        </tr>
                        <tr style="height:2.9pt;">
                            <td style="width:273.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;">
                                    <strong><span style="font-family:'Times New Roman'; color:#006600;">
                                        Department of Environment and Natural Resources
                                    </span></strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:2.9pt;">
                            <td style="width:273.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;">
                                    <strong><span style="font-family:'Times New Roman'; color:#0070c0;">
                                        Cordillera Administrative Region
                                    </span></strong>
                                </p>
                            </td>
                        </tr>
                        <tr style="height:3.6pt;">
                            <td style="width:273.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;">
                                    <span style="font-family:'Times New Roman';">DENR-CAR Compound, Baguio City</span>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; line-height:108%; font-size:10pt;"><span style="height:0pt; display:block; position:absolute; z-index:-2;"></span><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:90.2pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:right; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:75.2pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:right; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">Date:</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.created_at ? formatDate  (order.created_at) : 'N/A'}</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td colspan="3" style="width:269.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><strong><span style="font-family:'Times New Roman';">TRAVEL ORDER</span></strong></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">No. TO-${new Date(order.created_at).getFullYear()}-${String(order.travel_order_id).padStart(4, '0')}</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:82.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:89.9pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">NAME:</span></p>
                            </td>
                            <td style="width:27.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:97.4pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.employee?.first_name || ''} ${order.employee?.middle_name || ''} ${order.employee?.last_name || ''}</span></p>
                            </td>
                            <td style="width:62.55pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">SALARY:</span></p>
                            </td>
                            <td style="width:23.65pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:101.45pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.employee_salary ? formatCurrency(order.employee_salary) : 'N/A'}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:89.9pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:27.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:97.4pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:62.55pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:23.65pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:101.45pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:89.9pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">POSITION:</span></p>
                            </td>
                            <td style="width:27.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:97.4pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.employee?.position_name || 'N/A'}</span></p>
                            </td>
                            <td colspan="2" style="width:97pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">OFFICIAL STATION:</span></p>
                            </td>
                            <td style="width:101.45pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.employee?.assignment_name || 'N/A'}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:89.9pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:27.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:97.4pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td colspan="2" style="width:97pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:101.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width:128.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">DEPARTURE DATE:</span></p>
                            </td>
                            <td style="width:97.4pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.departure_date ? formatDate  (order.departure_date) : 'N/A'}</span></p>
                            </td>
                            <td colspan="2" style="width:97pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">ARRIVAL DATE:</span></p>
                            </td>
                            <td style="width:101.45pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.arrival_date ? formatDate(order.arrival_date) : 'N/A'}</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width:128.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:97.4pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td colspan="2" style="width:97pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:101.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width:128.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">DESTINATION OF TRAVEL:</span></p>
                            </td>
                            <td style="width:97.4pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.destination || 'N/A'}</span></p>
                            </td>
                            <td style="width:62.55pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:23.65pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:101.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:452.5pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">PURPOSES OF TRAVEL:</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:452.5pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">- ${order.purpose || 'N/A'}</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr style="height:12.6pt;">
                            <td style="width:173.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">Per diems&rsquo; expenses allowed:</span></p>
                            </td>
                            <td colspan="2" style="width:146.7pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.per_diem || 'N/A'}</span></p>
                            </td>
                            <td style="width:20.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:29.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:38.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:13pt;">
                            <td style="width:173.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">Assistants or Laborers Allowed:</span></p>
                            </td>
                            <td colspan="2" style="width:146.7pt; border-top:0.75pt solid #000000; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.laborer_assistant || 'N/A'}</span></p>
                            </td>
                            <td style="width:20.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:29.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:38.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:12.6pt;">
                            <td colspan="2" style="width:209.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">Appropriations to which travel should be charged:</span></p>
                            </td>
                            <td colspan="2" style="width:142.2pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.appropriation || 'N/A'}</span></p>
                            </td>
                            <td style="width:29.7pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:38.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:12.6pt;">
                            <td style="width:173.45pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">Remarks or special instruction:</span></p>
                            </td>
                            <td colspan="4" style="width:218.95pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.remarks || 'N/A'}</span></p>
                            </td>
                            <td style="width:38.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:0pt;">
                            <td style="width:184.25pt;"><br></td>
                            <td style="width:36.25pt;"><br></td>
                            <td style="width:121.25pt;"><br></td>
                            <td style="width:31.75pt;"><br></td>
                            <td style="width:40.5pt;"><br></td>
                            <td style="width:49.5pt;"><br></td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr style="height:12.6pt;">
                            <td style="width:454.4pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><strong><u><span style="font-family:'Times New Roman';">CERTIFICATIONS</span></u></strong></p>
                            </td>
                        </tr>
                        <tr style="height:12.6pt;">
                            <td style="width:454.4pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span style="font-family:'Times New Roman';">This is to certify that the travel is necessary and is connected with the functions of the official/employee of this division/section.</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr style="height:15.75pt;">
                            <td style="width:11.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:173.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">RECOMMENDING APPROVAL:</span></p>
                            </td>
                            <td style="width:26.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:163.15pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">APPROVED:</span></p>
                            </td>
                            <td style="width:12.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:15.75pt;">
                            <td style="width:11.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td rowspan="2" style="width:173.7pt; padding:0pt 5.4pt; vertical-align:middle;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:26.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td rowspan="2" style="width:163.15pt; padding:0pt 5.4pt; vertical-align:middle;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:12.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:15.75pt;">
                            <td style="width:11.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:26.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:12.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:15.75pt;">
                            <td style="width:11.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:173.7pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <div style="text-align: center;">
                                    ${order.recommender_employee?.signature?.signature_url ? 
                                        `<img src="${order.recommender_employee.signature.signature_url}" alt="Signature" style="max-width: 150px; max-height: 60px; display: inline-block;" />` : 
                                        `<!-- No signature found for ${order.recommender_employee?.first_name} ${order.recommender_employee?.last_name} -->
                                        <p>signature</p>`
                                    }
                                    <p style="margin-top: 5px; line-height:normal; font-size:10pt;">
                                        <strong><span style="font-family:'Times New Roman';">
                                            ${order.recommender_employee?.first_name || ''} ${order.recommender_employee?.last_name || ''}
                                        </span></strong>
                                    </p>
                                </div>
                            </td>
                            <td style="width:26.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:163.15pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <div style="text-align: center;">
                                    ${order.approver_employee?.signature?.signature_url ? 
                                        `<img src="${order.approver_employee.signature.signature_url}" alt="Signature" style="max-width: 150px; max-height: 60px; display: inline-block;" />` : 
                                        `<!-- No signature found for ${order.approver_employee?.first_name} ${order.approver_employee?.last_name} -->
                                        <p>signature</p>`
                                    }
                                    <p style="margin-top: 5px; line-height:normal; font-size:10pt;">
                                        <strong><span style="font-family:'Times New Roman';">
                                            ${order.approver_employee?.first_name || ''} ${order.approver_employee?.last_name || ''}
                                        </span></strong>
                                    </p>
                                </div>
                            </td>
                            <td style="width:12.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr style="height:15.75pt;">
                            <td style="width:11.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:173.7pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.recommender_employee?.position_name || 'Not assigned'}</span></p>
                            </td>
                            <td style="width:26.75pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:163.15pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.approver_employee?.position_name || 'Not assigned'}</span></p>
                            </td>
                            <td style="width:12.95pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <p style="margin-bottom:0pt; text-align:center; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            
                            <td style="width:362.7pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:right; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">DATE OF APPROVAL:</span></p>
                            </td>
                            
                            <td style="width:66.9pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">mm/dd/yyyy</span></p>
                            </td>
                            
                        </tr>
                        <tr>
                            <td style="width:362.7pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:66.9pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; line-height:108%; font-size:10pt;"><strong><span style="font-family:'Times New Roman';">&nbsp;</span></strong></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr style="height:10.75pt;">
                            <td style="width:435.65pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><strong><u><span style="font-family:'Times New Roman';">AUTHORIZATION</span></u></strong></p>
                            </td>
                        </tr>
                        <tr style="height:42.95pt;">
                            <td style="width:435.65pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><strong><span style="font-family:'Times New Roman';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></strong><span style="font-family:'Times New Roman';">I hereby authorize the Accountant to deduct the corresponding amount of the unliquidated cash advance from my succeeding salary for my failure to liquidate this travel within the prescribed twenty (20) days period upon return to my permanent official station pursuant to COA Circular 2012-004 dated November 28, 2012.</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style="margin-bottom:0pt; text-align:justify; line-height:108%; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                <table style="margin-right: auto; margin-left: auto; margin-bottom: 0pt; padding: 0pt; border-collapse: collapse; width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width:145pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td rowspan="2" style="width:145.05pt; padding:0pt 5.4pt; vertical-align:middle;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:145.05pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:145pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:145.05pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:145pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:145.05pt; border-bottom:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <div style="text-align: center;">
                                    ${order.employee?.signature?.signature_url ? 
                                        `<img src="${order.employee.signature.signature_url}" alt="Signature" style="max-width: 150px; max-height: 60px; display: inline-block;" />` : 
                                        `<!-- No signature found for ${order.employee?.first_name} ${order.employee?.last_name} -->
                                        <p>signature</p>`
                                    }
                                    <p style="margin-top: 0px; line-height:normal; font-size:10pt;">
                                        <strong><span style="font-family:'Times New Roman';">
                                            ${order.employee?.first_name || ''} ${order.employee?.last_name || ''}
                                        </span></strong>
                                    </p>
                                </div>
                            </td>
                            <td style="width:145.05pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:145pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                            <td style="width:145.05pt; border-top:0.75pt solid #000000; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:center; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">${order.employee?.position_name || 'N/A'}</span></p>
                            </td>
                            <td style="width:145.05pt; padding:0pt 5.4pt; vertical-align:top;">
                                <p style="margin-bottom:0pt; text-align:justify; line-height:normal; font-size:10pt;"><span style="font-family:'Times New Roman';">&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `;
            
            // Update modal content
            orderDetails.innerHTML = detailsHtml;
            
            // Show print button for approved or completed orders
            const printButton = document.getElementById('printButton');
            if ([3, 6].includes(order.status_id)) {
                printButton.classList.remove('hidden');
                printButton.onclick = () => window.print();
            } else {
                printButton.classList.add('hidden');
            }
            
        } catch (error) {
            console.error('Error loading travel order:', error);
            let errorMessage = 'Failed to load travel order details. Please try again.';
            
            if (error.message.includes('Failed to fetch')) {
                errorMessage = 'Unable to connect to the server. Please check your internet connection.';
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            orderDetails.innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Error Loading Travel Order</p>
                            <p class="text-sm text-red-700">${errorMessage}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeOrderModal()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Close
                    </button>
                    <button type="button" onclick="showTravelOrder(${orderId})" class="ml-3 px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-sync-alt mr-1"></i> Retry
                    </button>
                </div>
            `;
        }
    }
    
    // Close modal function
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('orderModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeOrderModal();
                }
            });
        }
        
        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOrderModal();
            }
        });
    });
</script>
@endpush
