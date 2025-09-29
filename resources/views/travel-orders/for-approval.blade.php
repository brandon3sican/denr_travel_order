@extends('layout.app')

@include('travel-orders.partials.approval-filters')

@push('scripts')
    <script src="{{ asset('js/approval-filters.js') }}"></script>
@endpush

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Travel Orders For Approval</h2>
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
            <div class="bg-white rounded-lg shadow overflow-hidden mt-2">
                <div class="px-6 py-4">
                    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="sm:mb-0">
                            <h3 class="text-base sm:text-lg md:text-lg font-bold text-gray-800">Travel Orders For
                                Recommendation
                            </h3>
                            <p class="hidden md:block text-xs sm:text-sm text-gray-600 mt-0.5">View and recommend travel
                                orders</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow overflow-hidden mt-3">
                        <!-- Search and Filter Section -->
                        <div class="bg-white rounded-lg border border-gray-200 p-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 items-end">
                                <!-- Enhanced Search with Icon -->
                                <div class="relative">
                                    <label for="search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text" id="search" placeholder="Search travel orders..."
                                            value="{{ request('search') }}"
                                            class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <button type="button" id="clear-filters"
                                        class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        <i class="fas fa-sync-alt text-gray-500 mr-2"></i>
                                        <span>Reset</span>
                                    </button>
                                    <button type="button" id="apply-filters"
                                        class="flex-1 flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        <i class="fas fa-search text-blue-100 mr-2"></i>
                                        <span>Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Travel Orders Table -->
                    <div class="bg-white rounded shadow overflow-hidden mt-2">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase">
                                            Travel Order Details
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase w-1/6">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <div class="font-medium text-gray-900">
                                                            {{ $order->employee->first_name }}
                                                            {{ $order->employee->last_name }}
                                                            <span
                                                                class="text-gray-500 text-sm">({{ $order->employee->position_name }})</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-sm">
                                                        <span class="font-medium">Purpose:</span> {{ $order->purpose }}
                                                    </div>
                                                    <div class="text-sm">
                                                        <span class="font-medium">Destination:</span>
                                                        {{ $order->destination }}
                                                    </div>
                                                    <div class="flex items-center space-x-4 text-sm">
                                                        <div>
                                                            <span class="font-medium">Travel Dates:</span>
                                                            {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                                            to
                                                            {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        Created: {{ $order->created_at->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-2">
                                                        <button onclick="showTravelOrder({{ $order->id }})"
                                                            class="text-blue-600 hover:text-blue-900">
                                                            <i class="fas fa-eye"></i>
                                                            <span class="hidden sm:inline">View Details</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-center">
                                                <button onclick="approve({{ $order->id }})"
                                                    class="text-green-600 hover:text-green-900 border border-green-600 px-2 py-1 rounded mr-3 w-25 {{ $order->status->name !== 'For Approval' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $order->status->name !== 'For Approval' ? 'disabled' : '' }}>
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button onclick="reject({{ $order->id }})"
                                                    class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded mr-3 w-20 {{ $order->status->name !== 'For Approval' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $order->status->name !== 'For Approval' ? 'disabled' : '' }}>
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-3 py-8 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <i class="fas fa-search text-gray-400 text-4xl"></i>
                                                    <p class="text-gray-500 text-sm font-medium">
                                                        @if (request()->has('search') && !empty(request('search')))
                                                            No match found for "{{ request('search') }}"
                                                        @else
                                                            No travel orders found.
                                                        @endif
                                                    </p>
                                                    @if (request()->has('search') && !empty(request('search')))
                                                        <button
                                                            onclick="document.getElementById('search').value = ''; document.querySelector('form').submit();"
                                                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                                            <i class="fas fa-undo-alt mr-1"></i> Clear search
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($travelOrders->hasPages())
                            <div class="px-3 py-2 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-700">
                                        Showing {{ $travelOrders->firstItem() }}-{{ $travelOrders->lastItem() }} of
                                        {{ $travelOrders->total() }}
                                    </div>
                                    <div class="flex space-x-1">
                                        @if ($travelOrders->onFirstPage())
                                            <span class="px-2 py-1 text-xs text-gray-400 border rounded">Previous</span>
                                        @else
                                            <a href="{{ $travelOrders->previousPageUrl() }}"
                                                class="px-2 py-1 text-xs text-gray-700 border rounded hover:bg-gray-50">Previous</a>
                                        @endif

                                        @if ($travelOrders->hasMorePages())
                                            <a href="{{ $travelOrders->nextPageUrl() }}"
                                                class="px-2 py-1 text-xs text-gray-700 border rounded hover:bg-gray-50">Next</a>
                                        @else
                                            <span class="px-2 py-1 text-xs text-gray-400 border rounded">Next</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                        Resources. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Include Travel Order Modal Component -->
    @include('components.travel-order.travel-order-modal')

@endsection
