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
                        <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Dashboard Content -->
                <div id="dashboardContent" class="page-content">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Total Travel Orders</p>
                                    <p class="text-2xl font-bold mt-1">{{ $totalTravelOrders }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-plus text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Pending Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $pendingRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Completed Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $completedRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Cancelled Requests</p>
                                    <p class="text-2xl font-bold mt-1">{{ $cancelledRequests }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-times-circle text-xl"></i>
                                </div>
                            </div>
                        </div>                 
                    </div>

                    <!-- Recent Travel Orders -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="text-xl font-bold text-gray-800">Recent Travel Orders</h3>
                                    <p class="text-sm text-gray-600 mt-1">Track and manage all travel order requests</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <form id="filterForm" method="GET" class="flex space-x-2">
                                            <select name="status" id="statusFilter" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                                <option value="For Recommendation" {{ request('status') == 'For Recommendation' ? 'selected' : '' }}>For Recommendation</option>
                                                <option value="For Approval" {{ request('status') == 'For Approval' ? 'selected' : '' }}>For Approval</option>
                                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Disapproved" {{ request('status') == 'Disapproved' ? 'selected' : '' }}>Disapproved</option>
                                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            @if(request('status') || request('search'))
                                                <a href="{{ route('dashboard') }}" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                    Clear Filters
                                                </a>
                                            @endif
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        </form>
                                    </div>
                                    <div class="relative">
                                        <div class="relative">
                                            <input type="text" 
                                                name="search" 
                                                id="searchInput" 
                                                value="{{ request('search') }}" 
                                                placeholder="Search..." 
                                                class="block w-full pl-4 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                onkeypress="if(event.key === 'Enter') { document.getElementById('filterForm').submit(); }">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button type="submit" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Travel Order ID</th>
                                        @if (auth()->user()->is_admin)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Employee</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Destination</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Arrival Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Departure Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-white font-bold uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $index => $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TO-2025-{{ $order->id }}</td>
                                        @if($isAdmin)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->employee ? $order->employee->first_name . ' ' . $order->employee->last_name : 'N/A' }}
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $order->destination ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->purpose ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Get the status name from the relationship
                                                $statusName = $order->status->name ?? '';
                                                $statusClass = [
                                                    'For Recommendation' => 'bg-yellow-100 text-yellow-800',
                                                    'For Approval' => 'bg-blue-100 text-blue-800',
                                                    'Approved' => 'bg-green-100 text-green-800',
                                                    'Disapproved' => 'bg-red-100 text-red-800',
                                                    'Cancelled' => 'bg-gray-100 text-gray-800',
                                                    'Completed' => 'bg-purple-100 text-purple-800'
                                                ][$statusName] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $order->status->name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button onclick="showTravelOrder({{ $order->id }})" 
                                               class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? '6' : '5' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No travel orders found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                                <p class="text-sm text-gray-600">
                                    @if($travelOrders->count() > 0)
                                        Showing <span class="font-medium">{{ $travelOrders->firstItem() }}</span> to 
                                        <span class="font-medium">{{ $travelOrders->lastItem() }}</span> of 
                                        <span class="font-medium">{{ $travelOrders->total() }}</span> results
                                    @else
                                        No results found
                                    @endif
                                </p>
                                @if($travelOrders->hasPages())
                                    <div class="flex items-center space-x-1">
                                        {{-- Previous Page Link --}}
                                        @if ($travelOrders->onFirstPage())
                                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                                                <i class="fas fa-chevron-left"></i> Previous
                                            </span>
                                        @else
                                            <a href="{{ $travelOrders->previousPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-chevron-left"></i> Previous
                                            </a>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($travelOrders->getUrlRange(1, $travelOrders->lastPage()) as $page => $url)
                                            @if ($page == $travelOrders->currentPage())
                                                <span class="px-3 py-1 border rounded-md text-sm font-medium bg-blue-600 text-white border-blue-600">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $url }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($travelOrders->hasMorePages())
                                            <a href="{{ $travelOrders->nextPageUrl() }}" class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                Next <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                                                Next <i class="fas fa-chevron-right"></i>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other pages will be loaded here -->
                <div id="otherPages" class="page-content hidden"></div>
            </main>
        </div>
    </div>

    <!-- Include Travel Order Modal Component -->
    @include('components.travel-order-modal')

@endsection
    