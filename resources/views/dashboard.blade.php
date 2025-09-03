@extends('layout.app')

@section('content')
    @if(isset($showSignatureAlert) && $showSignatureAlert)
    <!-- Signature Required Modal -->
    <div id="signatureRequiredModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="px-6 py-4 border-b bg-blue-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-signature text-blue-500 text-2xl mr-3"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Signature Required</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">Before you can proceed, you need to upload your digital signature. This signature will be used to sign your travel orders.</p>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Important:</strong> Your signature must be your official handwritten signature. Please sign on a white paper and upload a clear image in .PNG format with transparent background or draw your signature using a digital signature tool.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('signature.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-upload mr-2"></i> Upload/Draw Signature Now
                        </a>
                        <button type="button" onclick="document.getElementById('signatureRequiredModal').classList.add('hidden')" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            I'll do it later
                        </button>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">You can also upload your signature later from your profile settings.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
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
                @if(isset($showSignatureAlert) && $showSignatureAlert)
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                You need to upload your signature before you can submit travel orders. 
                                <a href="{{ route('signature.index') }}" class="font-medium underline text-red-700 hover:text-red-600">
                                    Click here to upload your signature now.
                                </a>
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50" onclick="this.parentElement.parentElement.parentElement.parentElement.style.display='none';">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
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
                                    <div>
                                        <a href="{{ route('travel-orders.create') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus mr-2"></i> New Travel Order
                                        </a>
                                    </div>
                                    <div class="relative">
                                        <form id="filterForm" method="GET" class="flex space-x-2 border border-gray-500 rounded-md">
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
                                                <a href="{{ route('dashboard') }}" class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">
                                                    Clear
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Date Created</th>
                                        @if (auth()->user()->is_admin)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Employee</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Destination</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">Arrival Date</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">Departure Date</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $index => $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <button onclick="showTravelOrder({{ $order->id }})" 
                                               class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3 w-20">
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
    @include('components.travel-order.travel-order-modal')

@endsection
    