@extends('layout.app')

@section('content')
    <!-- Complete Travel Order Modal -->
    @foreach ($travelOrders as $order)
        @if ($order->status_id == 3)
            <div id="completeModal{{ $order->id }}"
                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                            <i class="fas fa-file-signature text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Complete Travel Order</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500 mb-4">
                                Please upload the signed documents for this travel order. The following documents are
                                required:
                            </p>
                            <ul class="text-sm text-left text-gray-600 mb-6 space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Signed Certification of Travel Completed (PDF)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Signed Itinerary of Travel (PDF)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Signed Certificate of Appearance (PDF)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Signed Travel Report (PDF)</span>
                                </li>
                            </ul>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="signedDocuments">
                                    Upload Signed Documents (PDF only)
                                </label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload-{{ $order->id }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload files</span>
                                                <input id="file-upload-{{ $order->id }}" name="file-upload"
                                                    type="file" class="sr-only" accept=".pdf" multiple>
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PDF up to 10MB
                                        </p>
                                    </div>
                                </div>
                                <p id="file-names-{{ $order->id }}" class="mt-2 text-sm text-gray-500">No files selected
                                </p>
                            </div>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="complete-btn-{{ $order->id }}" onclick="submitCompletion({{ $order->id }})"
                                class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-300">
                                Submit
                            </button>
                            <button onclick="closeCompleteModal({{ $order->id }})"
                                class="ml-3 px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">My Travel Orders</h2>
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
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-xl font-bold text-gray-800">My Travel Orders</h3>
                                <p class="text-sm text-gray-600 mt-1">Track and manage my travel order requests</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <a href="{{ route('travel-orders.create') }}"
                                        class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-plus mr-2"></i> New Travel Order
                                    </a>
                                    <select id="statusFilter"
                                        class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="all">All Status</option>
                                        @foreach (\App\Models\TravelOrderStatus::all() as $status)
                                            <option value="{{ strtolower($status->name) }}">{{ ucfirst($status->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Orders Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @php
                        $counter = ($travelOrders->currentPage() - 1) * $travelOrders->perPage() + 1;
                    @endphp
                    @forelse($travelOrders as $order)
                        @php
                            $statusBgClass = '';
                            if ($order->status_id == 1) {
                                $statusBgClass = 'bg-yellow-50';
                            } elseif ($order->status_id == 2) {
                                $statusBgClass = 'bg-blue-50';
                            } elseif ($order->status_id == 3) {
                                $statusBgClass = 'bg-green-50';
                            } elseif ($order->status_id == 4) {
                                $statusBgClass = 'bg-red-50';
                            } elseif ($order->status_id == 5) {
                                $statusBgClass = 'bg-gray-50';
                            } elseif ($order->status_id == 6) {
                                $statusBgClass = 'bg-purple-50';
                            }
                        @endphp
                        <div class="{{ $statusBgClass }} rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 relative pt-8 pl-5 pr-8"
                            data-status="{{ strtolower($order->status->name ?? '') }}">
                            <div
                                class="absolute top-0 left-0 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-br-lg h-8 w-8 flex items-center justify-center text-sm font-bold shadow-lg">
                                <span class="drop-shadow-sm">{{ $counter++ }}</span>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <span class="text-xs text-gray-500">Created on</span>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if ($order->status_id == 1) bg-yellow-100 text-yellow-800 @endif
                                        @if ($order->status_id == 2) bg-blue-100 text-blue-800 @endif
                                        @if ($order->status_id == 3) bg-green-100 text-green-800 @endif
                                        @if ($order->status_id == 4) bg-red-100 text-red-800 @endif
                                        @if ($order->status_id == 5) bg-gray-100 text-gray-800 @endif
                                        @if ($order->status_id == 6) bg-purple-100 text-purple-800 @endif">
                                        {{ $order->status->name ?? 'N/A' }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $order->destination }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $order->purpose }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    <div>
                                        <span class="text-gray-500 block">Departure</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Arrival</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                @if ($order->latestStatusUpdate)
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <div class="text-xs text-gray-500 mb-1">Latest Update</div>
                                        <div class="flex items-center text-xs text-gray-700">
                                            <div class="flex-shrink-0 mr-2">
                                                <i class="fas fa-clock text-gray-400"></i>
                                            </div>
                                            <div>
                                                <div>{{ $order->latestStatusUpdate->created_at->format('M d, Y h:i A') }}
                                                </div>
                                                <div class="text-xs text-gray-600 font-bold">
                                                    {{ ucfirst($order->latestStatusUpdate->action) }}
                                                    @if ($order->latestStatusUpdate->user)
                                                        @php
                                                            $employee = \App\Models\Employee::where(
                                                                'email',
                                                                $order->latestStatusUpdate->user->email,
                                                            )->first();
                                                        @endphp
                                                        @if ($employee)
                                                            by {{ $employee->first_name }}
                                                            {{ $employee->middle_name ? ' ' . $employee->middle_name . ' ' : ' ' }}
                                                            {{ $employee->last_name }}
                                                        @endif
                                                    @endif
                                                </div>
                                                @if ($order->latestStatusUpdate->from_status && $order->latestStatusUpdate->to_status)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Status changed to
                                                        <span
                                                            class="font-medium font-bold">{{ ucfirst($order->latestStatusUpdate->to_status) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                                    <button onclick="showTravelOrder({{ $order->id }})"
                                        class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="far fa-eye mr-2"></i> View
                                    </button>

                                    @if ($order->status_id == 3)
                                        <button onclick="openCompleteModal({{ $order->id }}); return false;"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-check mr-2"></i> Complete
                                        </button>
                                    @endif

                                    @if ($order->status_id == 1)
                                        <button onclick="editTravelOrder({{ $order->id }})"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-yellow-600 text-sm font-medium rounded-md text-yellow-600 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                            <i class="far fa-edit mr-2"></i> Edit
                                        </button>

                                        <button type="button" onclick="confirmDelete({{ $order->id }})"
                                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="far fa-trash-alt mr-2"></i> Delete
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-inbox text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No travel orders found</h3>
                            <p class="text-gray-500">Get started by creating a new travel order.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($travelOrders->hasPages())
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($travelOrders->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-300 bg-white">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $travelOrders->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($travelOrders->hasMorePages())
                                <a href="{{ $travelOrders->nextPageUrl() }}"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-300 bg-white">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $travelOrders->firstItem() }}</span>
                                    to <span class="font-medium">{{ $travelOrders->lastItem() }}</span>
                                    of <span class="font-medium">{{ $travelOrders->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($travelOrders->onFirstPage())
                                        <span
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </span>
                                    @else
                                        <a href="{{ $travelOrders->previousPageUrl() }}"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-5 w-5"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($travelOrders->getUrlRange(1, $travelOrders->lastPage()) as $page => $url)
                                        @if ($page == $travelOrders->currentPage())
                                            <span aria-current="page"
                                                class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($travelOrders->hasMorePages())
                                        <a href="{{ $travelOrders->nextPageUrl() }}"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </a>
                                    @else
                                        <span
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-5 w-5"></i>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-4">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                        Resources. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @include('components.travel-order.travel-order-modal')
    @include('components.travel-order.edit-travel-order-modal')

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b bg-red-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-red-700">Delete Travel Order</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-red-700 font-medium">Warning: This action cannot be undone!</p>
                    <p class="text-gray-700 mt-2">You are about to permanently delete this travel order. This will:</p>
                    <ul class="list-disc list-inside text-gray-600 mt-2 space-y-1">
                        <li>Permanently remove all associated data</li>
                        <li>Remove the record from all reports</li>
                        <li>Be irreversible</li>
                    </ul>
                    <p class="text-gray-700 mt-3 font-medium">Are you absolutely sure you want to continue?</p>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Type <span
                            class="font-mono bg-gray-100 px-2 py-1 rounded mt-2">DELETE</span> to confirm</p>
                    <input type="text" id="confirmDeleteInput"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Type DELETE to confirm">
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <form id="deleteForm" method="POST" class="inline" onsubmit="handleDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="confirmDeleteBtn" disabled
                            class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium opacity-50 cursor-not-allowed focus:outline-none">
                            <span id="deleteButtonText">Delete</span>
                            <span id="deleteButtonLoader" class="hidden">
                                <i class="fas fa-spinner fa-spin"></i> Deleting...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message Container -->
    <div id="successMessage" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-1-9V8a1 1 0 1 1 2 0v3a1 1 0 0 1-2 0zm0 4a1 1 0 1 1 2 0v1a1 1 0 0 1-2 0v-1z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Success</p>
                    <p id="successMessageText" class="text-sm"></p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Function to open the completion modal
            function openCompleteModal(orderId) {
                document.getElementById('completeModal' + orderId).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                // Set up file input change handler
                const fileInput = document.getElementById('file-upload-' + orderId);
                const fileNames = document.getElementById('file-names-' + orderId);

                fileInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    const invalidFiles = files.filter(file => file.type !== 'application/pdf');

                    if (invalidFiles.length > 0) {
                        alert('Only PDF files are allowed.');
                        e.target.value = '';
                        fileNames.textContent = 'No files selected';
                        return;
                    }

                    if (files.length === 0) {
                        fileNames.textContent = 'No files selected';
                    } else {
                        fileNames.textContent = files.length + ' file' + (files.length > 1 ? 's' : '') + ' selected';
                    }
                });

                // Enable drag and drop
                const dropZone = fileInput.closest('div[class*="border-dashed"]');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropZone.classList.add('border-blue-400', 'bg-blue-50');
                }

                function unhighlight() {
                    dropZone.classList.remove('border-blue-400', 'bg-blue-50');
                }

                dropZone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInput.files = files;

                    // Trigger change event
                    const event = new Event('change');
                    fileInput.dispatchEvent(event);
                }
            }

            // Function to close the completion modal
            function closeCompleteModal(orderId) {
                document.getElementById('completeModal' + orderId).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                // Reset file input
                document.getElementById('file-upload-' + orderId).value = '';
                document.getElementById('file-names-' + orderId).textContent = 'No files selected';
            }

            // Function to submit the completion form
            async function submitCompletion(orderId) {
                const fileInput = document.getElementById('file-upload-' + orderId);
                const files = fileInput.files;

                if (files.length === 0) {
                    alert('Please upload at least one PDF file.');
                    return;
                }

                // Validate file types
                const invalidFiles = Array.from(files).filter(file => file.type !== 'application/pdf');
                if (invalidFiles.length > 0) {
                    alert('Only PDF files are allowed.');
                    return;
                }

                // Disable submit button and show loading state
                const submitBtn = document.getElementById('complete-btn-' + orderId);
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';

                try {
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    // Add all files to form data
                    Array.from(files).forEach((file, index) => {
                        formData.append(`documents[${index}]`, file);
                    });

                    const response = await fetch(`/travel-orders/${orderId}/complete`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Show success message
                        alert('Travel order marked as completed successfully!');
                        // Close modal and refresh the page
                        closeCompleteModal(orderId);
                        window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to complete travel order');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred: ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
            // Handle delete confirmation input
            document.addEventListener('DOMContentLoaded', function() {
                const confirmInput = document.getElementById('confirmDeleteInput');
                const deleteButton = document.getElementById('confirmDeleteBtn');

                if (confirmInput && deleteButton) {
                    confirmInput.addEventListener('input', function() {
                        if (this.value.trim().toUpperCase() === 'DELETE') {
                            deleteButton.disabled = false;
                            deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
                            deleteButton.classList.add('hover:bg-red-700');
                        } else {
                            deleteButton.disabled = true;
                            deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                            deleteButton.classList.remove('hover:bg-red-700');
                        }
                    });
                }
            });

            // Reset modal state when opened
            function confirmDelete(orderId) {
                const form = document.getElementById('deleteForm');
                const confirmInput = document.getElementById('confirmDeleteInput');
                const deleteButton = document.getElementById('confirmDeleteBtn');

                form.action = `/travel-orders/${orderId}`;
                if (confirmInput) confirmInput.value = '';
                if (deleteButton) {
                    deleteButton.disabled = true;
                    deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                    deleteButton.classList.remove('hover:bg-red-700');
                }
                document.getElementById('deleteModal').classList.remove('hidden');
            }
            // Show success message function
            function showSuccessMessage(message) {
                const successMessage = document.getElementById('successMessage');
                const messageText = document.getElementById('successMessageText');

                messageText.textContent = message;
                successMessage.classList.remove('hidden');

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 5000);
            }
            // Handle delete form submission
            async function handleDelete(event) {
                event.preventDefault();

                const form = event.target;
                const submitButton = form.querySelector('button[type="submit"]');
                const buttonText = document.getElementById('deleteButtonText');
                const buttonLoader = document.getElementById('deleteButtonLoader');

                try {
                    // Show loading state
                    buttonText.classList.add('hidden');
                    buttonLoader.classList.remove('hidden');
                    submitButton.disabled = true;

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-HTTP-Method-Override': 'DELETE'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Show success message
                        showSuccessMessage(result.message);
                        // Close the modal
                        closeDeleteModal();
                        // Reload the page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(result.message || 'Failed to delete travel order');
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    alert(error.message || 'An error occurred while deleting the travel order');
                    // Reset button state
                    buttonText.classList.remove('hidden');
                    buttonLoader.classList.add('hidden');
                    submitButton.disabled = false;
                }
            }
            // Delete modal functions
            function confirmDelete(orderId) {
                const form = document.getElementById('deleteForm');
                form.action = `/travel-orders/${orderId}`;
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            // Show success message
            function showSuccessMessage(message) {
                const successMessage = document.getElementById('successMessage');
                const messageText = document.getElementById('successMessageText');
                messageText.textContent = message;
                successMessage.classList.remove('hidden');

                // Hide after 5 seconds
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 5000);
            }

            // Close modals when clicking outside
            window.onclick = function(event) {
                const deleteModal = document.getElementById('deleteModal');
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        </script>
    @endpush
@endsection
