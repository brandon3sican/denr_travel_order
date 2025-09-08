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
                    <h2 class="text-xl font-semibold text-gray-800">All Travel Orders</h2>
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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 items-end">
                        <!-- Search -->
                        <div>
                            <label for="search" class="sr-only">Search</label>
                            <div>
                                <input type="text" id="search" placeholder="Search..." value="{{ request('search') }}"
                                    class="block w-full pl-7 pr-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status-filter" class="sr-only">Status</label>
                            <div>
                                <select id="status-filter"
                                    class="block w-full pl-2 pr-6 py-1.5 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Statuses</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ strtolower($status->name) }}"
                                            {{ request('status') == strtolower($status->name) ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range Picker -->
                        <div>
                            <label for="date-range-button" class="sr-only">Date Range</label>
                            <button type="button" id="date-range-button"
                                class="w-full flex items-center justify-between px-2 py-1.5 border border-gray-300 rounded bg-white text-left text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <span class="flex items-center">
                                    <i class="far fa-calendar-alt text-gray-400 mr-1.5 text-xs"></i>
                                    <span id="date-range-text" class="truncate text-xs">
                                        @if (request('date_range'))
                                            {{ request('date_range') }}
                                        @else
                                            Date Range
                                        @endif
                                    </span>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400 text-2xs ml-1"></i>
                            </button>
                            <input type="hidden" id="date-range" name="date_range" value="{{ request('date_range') }}">
                            <!-- Dropdown menu -->
                            <div id="date-range-dropdown"
                                class="hidden absolute z-10 w-56 mt-1 bg-white shadow-lg rounded-md py-1 border border-gray-200 text-sm">
                                <div class="space-y-0.5 p-1">
                                    <a href="#" data-range="today"
                                        class="flex items-center px-2 py-1.5 text-gray-700 rounded hover:bg-gray-100">
                                        <i class="far fa-sun text-yellow-400 mr-2 w-4 text-center"></i> Today
                                    </a>
                                    <a href="#" data-range="this-week"
                                        class="flex items-center px-2 py-1.5 text-gray-700 rounded hover:bg-gray-100">
                                        <i class="far fa-calendar-week text-blue-400 mr-2 w-4 text-center"></i> This Week
                                    </a>
                                    <a href="#" data-range="next-week"
                                        class="flex items-center px-2 py-1.5 text-gray-700 rounded hover:bg-gray-100">
                                        <i class="far fa-calendar-plus text-purple-400 mr-2 w-4 text-center"></i> Next Week
                                    </a>
                                    <a href="#" data-range="this-month"
                                        class="flex items-center px-2 py-1.5 text-gray-700 rounded hover:bg-gray-100">
                                        <i class="far fa-calendar text-green-400 mr-2 w-4 text-center"></i> This Month
                                    </a>
                                    <a href="#" data-range="next-month"
                                        class="flex items-center px-2 py-1.5 text-gray-700 rounded hover:bg-gray-100">
                                        <i class="fas fa-calendar-plus text-indigo-400 mr-2 w-4 text-center"></i> Next Month
                                    </a>
                                </div>
                                <div class="border-t border-gray-200 my-1"></div>
                                <div class="p-1">
                                    <div id="date-range-picker" class="w-full text-xs"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <button type="button" id="clear-filters"
                                class="flex-1 flex items-center justify-center px-2 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <i class="fas fa-times text-gray-500 mr-1"></i> Clear
                            </button>
                            <button type="button" id="apply-filters"
                                class="flex-1 flex items-center justify-center px-2 py-1.5 border border-transparent rounded text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <i class="fas fa-filter text-blue-100 mr-1"></i> Apply
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
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">Date
                                        Created</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Employee</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Purpose</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Destination</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Travel Date</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-white font-bold uppercase">
                                        Status</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-white font-bold uppercase">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="text-medium text-gray-500">
                                                {{ $order->created_at->format('M d, Y') }}</div>
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
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div>{{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                            </div>
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
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ $order->status->name }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <button onclick="showTravelOrder({{ $order->id }})"
                                                class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3 w-20">
                                                View
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
    @include('components.travel-order.travel-order-admin')

@endsection
