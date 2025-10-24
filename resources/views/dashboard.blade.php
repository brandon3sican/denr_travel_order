@extends('layout.app')

@php
    // Define status options for the filter
    $statusOptions = [
        'For Recommendation' => 'For Recommendation',
        'For Approval' => 'For Approval',
        'Approved' => 'Approved',
        'Disapproved' => 'Disapproved',
        'Cancelled' => 'Cancelled',
        'Completed' => 'Completed',
    ];
@endphp

@section('content')
    <x-dashboard.layout :showSignatureAlert="isset($showSignatureAlert) && $showSignatureAlert">
        <!-- Dashboard Content -->
        <div id="dashboardContent" class="page-content space-y-6">
            <!-- Overview Header -->
            <div class="border-b border-gray-200 pb-5">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Overview</h3>
                <p class="mt-2 max-w-4xl text-sm text-gray-500">Quickly view and manage your travel orders and requests.</p>
            </div>

            @if (auth()->user()->pendingApprovals()->exists() || auth()->user()->pendingRecommendations()->exists())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">
                                @if (auth()->user()->pendingApprovals()->exists() && auth()->user()->pendingRecommendations()->exists())
                                    You have pending travel orders to <a
                                        href="{{ route('for-recommendation', ['status' => 'For Recommendation']) }}"
                                        class="underline font-semibold">recommend</a> and <a
                                        href="{{ route('for-approval', ['status' => 'For Approval']) }}"
                                        class="underline font-semibold">approve</a>.
                                @elseif(auth()->user()->pendingRecommendations()->exists())
                                    You have travel orders waiting for your <a
                                        href="{{ route('for-recommendation', ['status' => 'For Recommendation']) }}"
                                        class="underline font-semibold">recommendation</a>.
                                @else
                                    You have travel orders waiting for your <a
                                        href="{{ route('for-approval', ['status' => 'For Approval']) }}"
                                        class="underline font-semibold">approval</a>.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pending Actions Modal -->
                <div id="pendingActionsModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    <i class="fas fa-bell text-yellow-500 mr-2"></i>
                                    Action Required
                                </h3>
                                <button onclick="closePendingActionsModal()" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="mt-4">
                                @if (auth()->user()->pendingApprovals()->exists() && auth()->user()->pendingRecommendations()->exists())
                                    <p class="text-gray-700 mb-4">You have pending travel orders that require your
                                        attention:</p>
                                    <ul class="list-disc pl-5 space-y-2 mb-6">
                                        <li><a href="{{ route('for-recommendation', ['status' => 'For Recommendation']) }}"
                                                class="text-blue-600 hover:underline">{{ auth()->user()->pendingRecommendations()->count() }}
                                                travel orders need your recommendation</a></li>
                                        <li><a href="{{ route('for-approval', ['status' => 'For Approval']) }}"
                                                class="text-blue-600 hover:underline">{{ auth()->user()->pendingApprovals()->count() }}
                                                travel orders need your approval</a></li>
                                    </ul>
                                @elseif(auth()->user()->pendingRecommendations()->exists())
                                    <p class="text-gray-700 mb-4">You have <span
                                            class="font-semibold">{{ auth()->user()->pendingRecommendations()->count() }}
                                            travel orders</span> waiting for your recommendation.</p>
                                @else
                                    <p class="text-gray-700 mb-4">You have <span
                                            class="font-semibold">{{ auth()->user()->pendingApprovals()->count() }} travel
                                            orders</span> waiting for your approval.</p>
                                @endif
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" onclick="closePendingActionsModal()"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Remind Me Later
                                </button>
                                @if (auth()->user()->pendingRecommendations()->exists())
                                    <a href="{{ route('for-recommendation', ['status' => 'For Recommendation']) }}"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        View Recommendations
                                    </a>
                                @endif
                                @if (auth()->user()->pendingApprovals()->exists())
                                    <a href="{{ route('for-approval', ['status' => 'For Approval']) }}"
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        View Approvals
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Show modal on page load if there are pending actions
                    document.addEventListener('DOMContentLoaded', function() {
                        @if (auth()->user()->pendingApprovals()->exists() || auth()->user()->pendingRecommendations()->exists())
                            // Always show the modal when there are pending actions
                            document.getElementById('pendingActionsModal').classList.remove('hidden');
                        @endif
                    });

                    function closePendingActionsModal() {
                        document.getElementById('pendingActionsModal').classList.add('hidden');
                    }
                </script>
                <style>
                    #pendingActionsModal {
                        transition: opacity 0.3s ease-in-out;
                    }

                    #pendingActionsModal.hidden {
                        opacity: 0;
                        pointer-events: none;
                    }
                </style>
            @endif

            <!-- Stats Cards -->
            <x-dashboard.stats-cards :totalTravelOrders="$totalTravelOrders" :pendingRequests="$pendingRequests" :completedRequests="$completedRequests" :cancelledRequests="$cancelledRequests" />

            <!-- Charts Section -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 sm:mb-0">Travel Order Analytics</h3>
                    <div class="w-full grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-1">
                            <label for="timePeriod" class="text-xs font-medium text-gray-700">View by</label>
                            <select id="timePeriod"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div id="monthSelector" class="flex flex-col space-y-1">
                            <label for="selectedMonth" class="text-xs font-medium text-gray-700">Month</label>
                            <select id="selectedMonth"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div id="yearSelector" class="flex flex-col space-y-1">
                            <label for="selectedYear" class="text-xs font-medium text-gray-700">Year</label>
                            <select id="selectedYear"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                @for ($year = date('Y') - 5; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Line Chart -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Travel Orders Trend</h4>
                        <div class="h-80">
                            <canvas id="travelOrdersLineChart"></canvas>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100">
                        <h4 class="text-md font-medium text-gray-700 mb-3">Status Distribution</h4>
                        <div class="h-80">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Travel Orders -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="sm:mb-0">
                            <h3 class="text-base sm:text-lg md:text-lg font-bold text-gray-800">
                                Recent Travel Orders
                            </h3>
                            <p class="hidden md:block text-xs sm:text-sm text-gray-600 mt-0.5">
                                Track and manage all travel order requests
                            </p>
                        </div>

                        <div class="w-full sm:w-auto">
                            <x-dashboard.search-filter :statuses="$statusOptions" :currentStatus="request('status')" :searchQuery="request('search')" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-2 sm:mx-0 shadow-sm">
                    <div class="inline-block min-w-full align-middle">
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <x-dashboard.table-header :isAdmin="auth()->user()->is_admin" />
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($travelOrders as $order)
                                        <tr
                                            class="relative group hover:bg-gray-50 transition-colors duration-150 ease-in-out border-b border-gray-100 last:border-0">
                                            <x-dashboard.travel-order-row :order="$order" :isAdmin="auth()->user()->is_admin" />
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}"
                                                class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-4">
                                                    <svg class="w-16 h-16 text-gray-300" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-1">
                                                        <p class="text-base font-medium text-gray-700">
                                                            No travel orders found
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            Get started by creating a new travel order
                                                        </p>
                                                    </div>
                                                    @if (auth()->user()->can('create', App\Models\TravelOrder::class))
                                                        <a href="{{ route('travel-orders.create') }}"
                                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                            New Travel Order
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <x-dashboard.pagination :paginator="$travelOrders" />
            </div>
        </div>

        <!-- Other pages will be loaded here -->
        <div id="otherPages" class="page-content hidden"></div>

        <!-- Include Travel Order Modal Component -->
        @include('components.travel-order.travel-order-modal')

    </x-dashboard.layout>

    @include('components.dashboard.scripts')
@endsection
