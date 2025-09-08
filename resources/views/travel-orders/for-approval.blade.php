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
                    <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
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
            <div class="bg-white rounded-lg shadow overflow-hidden">
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


            <div class="bg-white rounded-lg shadow overflow-hidden mt-2">
                <!-- Travel Orders Table -->
                <div class="bg-white rounded shadow overflow-hidden mt-2">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase cursor-pointer hover:bg-gray-700"
                                        onclick="sortTable(0)">
                                        <div class="flex items-center">
                                            Date Created
                                            <span class="sort-icon ml-1">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </div>
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Employee</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Purpose</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Destination</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase cursor-pointer hover:bg-gray-700"
                                        onclick="sortTable(3)">
                                        <div class="flex items-center">
                                            Travel Date
                                            <span class="sort-icon ml-1">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </div>
                                    </th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-white font-bold uppercase">
                                        Status</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-white font-bold uppercase">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap"
                                            data-sort-value="{{ \Carbon\Carbon::parse($order->created_at)->toIso8601String() }}">
                                            <div class="text-medium text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="font-medium">{{ $order->employee->first_name }}
                                                {{ $order->employee->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $order->employee->position_name }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="font-medium text-gray-500">{{ $order->purpose }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="font-medium text-gray-500">{{ $order->destination }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap"
                                            data-sort-value="{{ \Carbon\Carbon::parse($order->departure_date)->toIso8601String() }}">
                                            <div class="font-medium">
                                                {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">to
                                                {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-center">
                                            @php
                                                $statusColors = [
                                                    'for recommendation' => 'bg-yellow-100 text-yellow-800',
                                                    'for approval' => 'bg-blue-100 text-blue-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'disapproved' => 'bg-red-100 text-red-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                    'completed' => 'bg-purple-100 text-purple-800',
                                                ];
                                                $statusColor =
                                                    $statusColors[strtolower($order->status->name)] ??
                                                    'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ $order->status->name }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-center">
                                            <button onclick="showTravelOrder({{ $order->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3 w-20">
                                                View
                                            </button>
                                            <button onclick="approve({{ $order->id }})"
                                                class="text-green-600 hover:text-green-900 border border-green-600 px-2 py-1 rounded mr-3 w-20 {{ $order->status->name !== 'For Approval' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $order->status->name !== 'For Approval' ? 'disabled' : '' }}>
                                                Approve
                                            </button>
                                            <button onclick="reject({{ $order->id }})"
                                                class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded mr-3 w-20 {{ $order->status->name !== 'For Approval' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $order->status->name !== 'For Approval' ? 'disabled' : '' }}>
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-4 text-center text-gray-500 text-sm">
                                            No travel orders found.
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
