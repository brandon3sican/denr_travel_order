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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-green-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Total Travel Orders</p>
                                    <p class="text-2xl font-bold mt-1">0</p>
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
                                    <p class="text-2xl font-bold mt-1">0</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium opacity-90">Completed Requests</p>
                                    <p class="text-2xl font-bold mt-1">0</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Travel Orders -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-4 sm:mb-0">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Travel Orders</h3>
                                <p class="text-sm text-gray-500">List of all recent travel orders and their status</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">
                                    Page <span id="currentPage">1</span> of <span id="totalPages">1</span>
                                </span>
                                <button id="prevPage" class="px-3 py-1 border rounded disabled:opacity-50" disabled>
                                    &larr; Prev
                                </button>
                                <button id="nextPage" class="px-3 py-1 border rounded disabled:opacity-50">
                                    Next &rarr;
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Order ID</th>
                                        @if (auth()->user()->is_admin)
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                        @endif
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody" class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                        @if (auth()->user()->is_admin)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Employee</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Destination</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Dates</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Status</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Actions</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Other pages will be loaded here -->
                <div id="otherPages" class="page-content hidden"></div>
            </main>
        </div>
    </div>

    <!-- View Order Modal -->
    <div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-medium text-gray-900">Travel Order Details</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div id="orderDetails" class="mt-6">
                    <!-- Order details will be inserted here -->
                </div>
            </div>
        </div>
    </div>

@endsection
    