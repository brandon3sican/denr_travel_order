@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
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
                <!-- Orders Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                    @php
                        $counter = ($travelOrders->currentPage() - 1) * $travelOrders->perPage() + 1;
                    @endphp
                    @forelse($travelOrders as $order)
                        @php
                            $statusBgClass = '';
                            if ($order->status_id == 1) $statusBgClass = 'bg-yellow-50';
                            elseif ($order->status_id == 2) $statusBgClass = 'bg-blue-50';
                            elseif ($order->status_id == 3) $statusBgClass = 'bg-green-50';
                            elseif ($order->status_id == 4) $statusBgClass = 'bg-red-50';
                            elseif ($order->status_id == 5) $statusBgClass = 'bg-gray-50';
                            elseif ($order->status_id == 6) $statusBgClass = 'bg-purple-50';
                        @endphp
                        <div class="{{ $statusBgClass }} rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300 relative pt-8 pl-5 pr-8"
                             data-status="{{ strtolower($order->status->name ?? '') }}">
                            <div class="absolute top-0 left-0 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-br-lg h-8 w-8 flex items-center justify-center text-sm font-bold shadow-lg">
                                <span class="drop-shadow-sm">{{ $counter++ }}</span>
                            </div>
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
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

                                <div class="mb-3">
                                    <h3 class="text-md font-semibold text-gray-700">
                                        <i class="far fa-user mr-1 text-gray-500"></i>
                                        {{ $order->employee->full_name }}
                                    </h3>
                                </div>

                                <div class="mb-3">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $order->destination }}</h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $order->purpose }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    <div>
                                        <span class="text-gray-500 block">Arrival</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Departure</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                                    <button onclick="showTravelOrder({{ $order->id }})"
                                        class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="far fa-eye mr-2"></i> View
                                    </button>

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
                            <p class="text-gray-500">No travel orders match your current filters.</p>
                        </div>
                    @endforelse
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
