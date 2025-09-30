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
                    <h2 class="text-xl font-semibold text-gray-800">Status Management</h2>
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
        <main class="flex-1 overflow-y-auto p-4">

            <!-- Travel Orders Reset -->
            <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-3 border-b bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-800">Travel Orders Status Reset</h3>
                    <p class="text-xs text-gray-500">Search a travel order and reset its status to For Recommendation or For
                        Approval.</p>
                    <div class="mt-3">
                        <form method="GET" action="{{ route('status-management.index') }}" class="max-w-md">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by employee, destination, purpose, or status..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Travel Order
                                    Details</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @isset($travelOrders)
                                @forelse($travelOrders as $to)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900">Travel Dates: {{ $to->departure_date }} to
                                                {{ $to->arrival_date }}</div>
                                            <div class="text-xs text-gray-600">Destination: {{ $to->destination }}</div>
                                            <div class="text-xs text-gray-600">Purpose: {{ $to->purpose }}</div>
                                            <div class="text-xs text-gray-600">Employee:
                                                {{ optional($to->employee)->first_name }}
                                                {{ optional($to->employee)->last_name }} ({{ optional($to->employee)->email }})
                                            </div>
                                            <div class="text-xs text-gray-600">Position:
                                                {{ optional($to->employee)->position_name }}
                                            </div>
                                            <div class="text-xs text-gray-600">Department:
                                                {{ optional($to->employee)->assignment_name }}
                                            </div>
                                            <div class="text-xs text-gray-600">Division:
                                                {{ optional($to->employee)->div_sec_unit }}
                                            </div>
                                            <div class="text-xs text-gray-600 mt-2"><button
                                                    onclick="showTravelOrder({{ $to->id }})"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="hidden sm:inline">View Details</span>
                                                </button>
                                            </div>

                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-2">
                                                @php $currentStatus = strtolower(optional($to->status)->name ?? ''); @endphp
                                                @if ($currentStatus !== 'for recommendation')
                                                    <form method="POST"
                                                        action="{{ route('status-management.reset', ['id' => $to->id]) }}"
                                                        onsubmit="return confirm('Reset TO #{{ $to->id }} to For Recommendation?');">
                                                        @csrf
                                                        <input type="hidden" name="target" value="for recommendation">
                                                        <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200"><i
                                                                class="fas fa-redo mr-1"></i>For
                                                            Recommendation</button>
                                                    </form>
                                                @endif
                                                @if ($currentStatus !== 'for approval' && $currentStatus !== 'for recommendation')
                                                    <form method="POST"
                                                        action="{{ route('status-management.reset', ['id' => $to->id]) }}"
                                                        onsubmit="return confirm('Reset TO #{{ $to->id }} to For Approval?');">
                                                        @csrf
                                                        <input type="hidden" name="target" value="for approval">
                                                        <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-medium rounded text-green-700 bg-green-50 hover:bg-green-100 border border-green-200"><i
                                                                class="fas fa-redo mr-1"></i>For
                                                            Approval</button>
                                                    </form>
                                                @endif
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-gray-500 mt-2">Current
                                                Status: {{ optional($to->status)->name }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No travel orders
                                            found.</td>
                                    </tr>
                                @endforelse
                            @endisset
                        </tbody>
                    </table>
                </div>

                @if (isset($travelOrders) && $travelOrders->hasPages())
                    <div class="bg-white px-3 py-2 flex items-center justify-between border-t border-gray-200">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs text-gray-600">
                                    Showing <span class="font-medium">{{ $travelOrders->firstItem() }}</span>
                                    to <span class="font-medium">{{ $travelOrders->lastItem() }}</span>
                                    of <span class="font-medium">{{ $travelOrders->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                {{ $travelOrders->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Add/Edit Modal -->
        <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Add New Status</h3>
                </div>
                <form id="statusForm" class="p-6">
                    @csrf
                    <input type="hidden" id="statusId" name="id">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Status Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter status name">
                        <p id="nameError" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Deletion</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6">Are you sure you want to delete this status? This action cannot be
                        undone.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Cancel
                        </button>
                        <button type="button" id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-white border-t border-gray-200 mt-4">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                        Resources. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>


    @include('status-management.partials.scripts')
    @include('components.travel-order.travel-order-modal')
@endsection
