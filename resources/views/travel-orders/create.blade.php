@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">Create Travel Order</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
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
                            <div class="flex items-center justify-between">
                                <div class="items-center space-x-4 mb-4">
                                    <h3 class="flex items-center text-2xl font-semibold">Travel Order Request Form</h3>
                                    <p class="text-sm text-gray-600">Fill up the form below to create a new travel order.
                                    </p>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('travel-orders.index') }}"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                            <h3 class="text-lg font-medium text-white bg-gray-800 px-4 py-2 rounded-md mb-4">
                                <i class="fas fa-info-circle mr-2"></i>Basic Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose of
                                        Travel</label>
                                    <input type="text" id="purpose" name="purpose" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Purpose 1; Purpose 2; Purpose 3">
                                    <p class="mt-1 text-xs text-gray-500">
                                        You can add multiple purposes by separating them with a semicolon (;).
                                    </p>
                                </div>
                                <div class="md:col-span-1">
                                    <label for="destination"
                                        class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                                    <input type="text" id="destination" name="destination" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Destination">
                                </div>
                                <div class="md:col-span-1">
                                    <label for="employee_salary"
                                        class="block text-sm font-medium text-gray-700 mb-1">Employee Salary</label>
                                    <input type="number" id="employee_salary" name="employee_salary" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Employee Salary">
                                </div>
                                <div class="md:col-span-1">
                                    <label for="departure_date"
                                        class="block text-sm font-medium text-gray-700 mb-1">Departure Date</label>
                                    <input type="date" id="departure_date" name="departure_date" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div class="md:col-span-1">
                                    <label for="arrival_date" class="block text-sm font-medium text-gray-700 mb-1">Arrival
                                        Date</label>
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
                                    <label for="appropriation" class="block text-sm font-medium text-gray-700 mb-1">Source
                                        of Fund</label>
                                    <select id="appropriation" name="appropriation" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        onchange="toggleOtherAppropriation(this.value)">
                                        <option value="">Select source of fund</option>
                                        <option value="Regular Fund">Regular Fund</option>
                                        <option value="Others">Others</option>
                                    </select>
                                    <div id="otherAppropriationContainer" class="mt-2 hidden">
                                        <label for="otherAppropriation"
                                            class="block text-sm font-medium text-gray-700 mb-1">Please specify: </label>
                                        <input type="text" id="otherAppropriation" name="otherAppropriation"
                                            class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Enter source of fund">
                                    </div>
                                </div>

                                <!-- Per Diem -->
                                <div class="md:col-span-1">
                                    <label for="per_diem" class="block text-sm font-medium text-gray-700 mb-1">Per
                                        Diem</label>
                                    <input type="number" id="per_diem" name="per_diem" min="0" required
                                        class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Per Diem">
                                </div>

                                <!-- Number of Labor -->
                                <div class="md:col-span-1">
                                    <label for="laborer_assistant"
                                        class="block text-sm font-medium text-gray-700 mb-1">Number of
                                        Labor/Assistant</label>
                                    <input type="number" id="laborer_assistant" name="laborer_assistant" min="0"
                                        required
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
                                        <label for="recommender"
                                            class="block text-sm font-medium text-gray-700 mb-1">Immediate
                                            Supervisor</label>
                                        <div class="relative">
                                            <select id="recommender" name="recommender" required
                                                class="appearance-none block w-full bg-white border-2 border-gray-300 rounded-md shadow py-2 pl-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($recommenders as $recommender)
                                                    <option value="{{ $recommender->email }}">
                                                        {{ $recommender->first_name }} {{ $recommender->middle_name ?: '' }}
                                                        {{ $recommender->last_name }} {{ $recommender->suffix ?: '' }}
                                                        @if ($recommender->position)
                                                            ({{ $recommender->position }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">The person who will recommend your travel
                                            order for approval</p>
                                    </div>

                                    <!-- Approver Selection -->
                                    <div class="relative">
                                        <label for="approver"
                                            class="block text-sm font-medium text-gray-700 mb-1">Division Chief/Agency
                                            Head</label>
                                        <div class="relative">
                                            <select id="approver" name="approver" required
                                                class="appearance-none block w-full bg-white border-2 border-gray-300 rounded-md shadow py-2 pl-3 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($approvers as $approver)
                                                    <option value="{{ $approver->email }}">
                                                        {{ $approver->first_name }} {{ $approver->middle_name ?: '' }}
                                                        {{ $approver->last_name }} {{ $approver->suffix ?: '' }}
                                                        @if ($approver->position)
                                                            ({{ $approver->position }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">The person who will approve your travel order
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="p-2 border-t-2 border-gray-600 flex justify-end space-x-3">
                            <button type="button" id="previewBtn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Preview
                            </button>
                            <button type="submit" id="submitBtn"
                                class="hidden inline-flex justify-center py-2 px-4 border border-transparent shadow text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
