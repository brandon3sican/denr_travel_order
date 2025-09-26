@extends('layout.app')

@php
    // Get all statuses for the filter
    $statuses = \App\Models\TravelOrderStatus::all()->pluck('name', 'id');
    
    // Get the current status filter from the request
    $currentStatus = request('status');
    $searchQuery = request('search');
@endphp

@section('content')
    <!-- Completion Modals -->
    @foreach ($travelOrders as $order)
        @if ($order->status_id == 3)
            <x-travel-orders.completion-modal :order="$order" />
        @endif
    @endforeach

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">Travel Orders</h2>
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
                                    
                                    <form action="{{ route('travel-orders.index') }}" method="GET" class="inline">
                                        <select name="status" onchange="this.form.submit()"
                                            class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">All Status</option>
                                            @foreach($statuses as $id => $name)
                                                <option value="{{ strtolower($name) }}" {{ $currentStatus == strtolower($name) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if(request('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        @endif
                                    </form>
                                    
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <form action="{{ route('travel-orders.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between">
                            <x-travel-orders.search-filter 
                                :statuses="$statuses" 
                                :currentStatus="$currentStatus"
                                :searchQuery="$searchQuery"
                            />
                        </form>
                    </div>

                    <!-- Orders Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                        @php
                            $counter = ($travelOrders->currentPage() - 1) * $travelOrders->perPage() + 1;
                        @endphp
                        
                        @forelse($travelOrders as $order)
                            <x-travel-orders.order-card :order="$order" :counter="$counter++" />
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900">No travel orders found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new travel order.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('travel-orders.create') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus mr-2"></i> New Travel Order
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <x-travel-orders.pagination :paginator="$travelOrders" />
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-travel-orders.delete-modal />

    <!-- Success Message -->
    <x-travel-orders.success-message />

    <!-- Include JavaScript -->
    <x-travel-orders.scripts />
@endsection
