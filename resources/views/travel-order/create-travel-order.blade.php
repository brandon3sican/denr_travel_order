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
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                        <form id="travelOrderForm" class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose of Travel</label>
                                        <input type="text" id="purpose" name="purpose" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Purpose of travel">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                                        <input type="text" id="destination" name="destination" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Destination">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary</label>
                                        <input type="number" id="salary" name="salary" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Salary">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="departureDate" class="block text-sm font-medium text-gray-700 mb-1">Departure Date</label>
                                        <input type="date" id="departureDate" name="departureDate" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="arrivalDate" class="block text-sm font-medium text-gray-700 mb-1">Arrival Date</label>
                                        <input type="date" id="arrivalDate" name="arrivalDate" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Travel Details -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Travel Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-1">
                                        <label for="fundSource" class="block text-sm font-medium text-gray-700 mb-1">Source of Fund</label>
                                        <input type="text" id="fundSource" name="fundSource" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Source of fund">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="perDiem" class="block text-sm font-medium text-gray-700 mb-1">Per Diem</label>
                                        <input type="number" id="perDiem" name="perDiem" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Per Diem">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="noOfLabor" class="block text-sm font-medium text-gray-700 mb-1">Number of Labor or Assistant</label>
                                        <input type="number" id="noOfLabor" name="noOfLabor" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Number of Labor or Assistant">
                                    </div>
                                    <div class="md:col-span-1">
                                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                                        <input type="text" id="remarks" name="remarks" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Remarks">
                                    </div>
                                </div>
                            </div>

                            <!-- Approvals -->
                            <div class="pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Details</h3>
                                <div class="space-y-6">
                                    <!-- Recommender Selection -->
                                    <div class="relative">
                                        <label for="recommender" class="block text-sm font-medium text-gray-700 mb-1">Recommender</label>
                                        <div class="relative">
                                            <select id="recommender" name="recommender" required
                                                class="appearance-none block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="" disabled selected>Select recommender</option>
                                                <option value="maria.santos">Maria Santos (Supervising EMS)</option>
                                                <option value="carlos.reyes">Carlos Reyes (Chief, Planning Division)</option>
                                                <option value="luz.cruz">Luz Cruz (OIC, Admin Division)</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">The person who will recommend your travel order</p>
                                    </div>

                                    <!-- Approver Selection -->
                                    <div class="relative">
                                        <label for="approver" class="block text-sm font-medium text-gray-700 mb-1">Approver</label>
                                        <div class="relative">
                                            <select id="approver" name="approver" required
                                                class="appearance-none block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 pl-3 pr-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="" disabled selected>Select approver</option>
                                                <option value="juan.delacruz">Juan Dela Cruz (Regional Director)</option>
                                                <option value="maria.gonzales">Maria Gonzales (Assistant Regional Director)</option>
                                                <option value="robert.lim">Robert Lim (Chief, Finance Division)</option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">The person who will approve your travel order</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </button>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

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
