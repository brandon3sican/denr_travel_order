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
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">View by:</span>
                            <select id="timePeriod" class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div id="monthSelector" class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Month:</span>
                            <select id="selectedMonth" class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div id="yearSelector" class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Year:</span>
                            <select id="selectedYear" class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @for($year = date('Y') - 5; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart instances
            let lineChart, pieChart;
            
            // DOM Elements
            const timePeriodSelect = document.getElementById('timePeriod');
            const monthSelect = document.getElementById('selectedMonth');
            const yearSelect = document.getElementById('selectedYear');
            const monthSelector = document.getElementById('monthSelector');
            const yearSelector = document.getElementById('yearSelector');
            
            // Toggle visibility of selectors based on period
            function updateSelectorVisibility() {
                const period = timePeriodSelect.value;
                
                if (period === 'monthly') {
                    monthSelector.style.display = 'flex';
                    yearSelector.style.display = 'flex';
                } else if (period === 'yearly') {
                    monthSelector.style.display = 'none';
                    yearSelector.style.display = 'flex';
                } else {
                    monthSelector.style.display = 'flex';
                    yearSelector.style.display = 'flex';
                }
            }
            
            // Initialize charts
            function initCharts() {
                const lineCtx = document.getElementById('travelOrdersLineChart')?.getContext('2d');
                const pieCtx = document.getElementById('statusPieChart')?.getContext('2d');
                
                if (!lineCtx || !pieCtx) return;
                
                // Destroy existing charts if they exist
                if (lineChart) lineChart.destroy();
                if (pieChart) pieChart.destroy();
                
                // Get filter values
                const timePeriod = timePeriodSelect.value;
                const month = monthSelect.value;
                const year = yearSelect.value;
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                
                // Show loading state
                $('.chart-container').addClass('opacity-50');
                
                // Build query parameters
                const params = new URLSearchParams({
                    period: timePeriod,
                    timezone: timezone,
                    month: timePeriod === 'monthly' ? month : '',
                    year: year
                });
                
                // Fetch data based on selected filters
                fetch(`/api/travel-orders/analytics?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response:', data); // Debug log
                    
                    // Ensure we have data
                    if (!data || !data.lineChart || !data.pieChart) {
                        throw new Error('Invalid data format received from server');
                    }
                    
                    // Update line chart
                    if (document.getElementById('travelOrdersLineChart')) {
                        if (lineChart) lineChart.destroy();
                        
                        const lineCtx = document.getElementById('travelOrdersLineChart').getContext('2d');
                        lineChart = new Chart(lineCtx, {
                            type: 'line',
                            data: {
                                labels: data.lineChart.labels || [],
                                datasets: [
                                    {
                                        label: 'Pending',
                                        data: data.lineChart.pendingData || [],
                                        borderColor: 'rgb(249, 168, 37)',
                                        backgroundColor: 'rgba(249, 168, 37, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: false
                                    },
                                    {
                                        label: 'Completed',
                                        data: data.lineChart.completedData || [],
                                        borderColor: 'rgb(16, 185, 129)',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: false
                                    },
                                    {
                                        label: 'Cancelled',
                                        data: data.lineChart.cancelledData || [],
                                        borderColor: 'rgb(107, 114, 128)',
                                        backgroundColor: 'rgba(107, 114, 128, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: false
                                    },
                                    {
                                        label: 'Disapproved',
                                        data: data.lineChart.disapprovedData || [],
                                        borderColor: 'rgb(239, 68, 68)',
                                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: false
                                    }
                                ]
                            },
                            options: getLineChartOptions(timePeriod)
                        });
                    }
                    
                    // Update pie chart
                    if (document.getElementById('statusPieChart')) {
                        if (pieChart) pieChart.destroy();
                        
                        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
                        
                        // Check if there's any data to display
                        const hasData = data.pieChart && data.pieChart.data && data.pieChart.data.length > 0 && 
                                     data.pieChart.data.some(value => value > 0);
                        
                        if (!hasData) {
                            // Display "No Data" message
                            // Get the container element
                            const chartContainer = document.getElementById('statusPieChart').parentNode;
                            
                            // Create a wrapper div for the chart and button
                            const wrapper = document.createElement('div');
                            wrapper.className = 'relative w-full h-full flex items-center justify-center';
                            
                            // Create the chart canvas
                            const canvas = document.createElement('canvas');
                            canvas.id = 'statusPieChart';
                            wrapper.appendChild(canvas);
                            
                            // Create the no-data content
                            const noDataContent = document.createElement('div');
                            noDataContent.className = 'absolute inset-0 flex flex-col items-center justify-center text-center p-4';
                            
                            const noDataText = document.createElement('p');
                            noDataText.className = 'text-gray-500 mb-4 text-sm';
                            noDataText.textContent = 'No travel order data available';
                            
                            const createButton = document.createElement('a');
                            createButton.href = '{{ route("travel-orders.create") }}';
                            createButton.className = 'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150';
                            createButton.innerHTML = `
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Travel Order
                            `;
                            
                            noDataContent.appendChild(noDataText);
                            noDataContent.appendChild(createButton);
                            wrapper.appendChild(noDataContent);
                            
                            // Replace the existing canvas with our wrapper
                            document.getElementById('statusPieChart').replaceWith(wrapper);
                            
                            // Initialize the chart with minimal data
                            pieChart = new Chart(canvas, {
                                type: 'doughnut',
                                data: {
                                    datasets: [{
                                        data: [1],
                                        backgroundColor: ['rgba(200, 200, 200, 0.2)'],
                                        borderWidth: 0
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '60%',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: { enabled: false }
                                    },
                                    animation: { animateRotate: false, animateScale: false }
                                }
                            });
                        } else {
                            // Define colors for all statuses
                            const statusColors = {
                                'For Recommendation': {
                                    bg: 'rgba(249, 168, 37, 0.7)',
                                    border: 'rgba(249, 168, 37, 1)'
                                },
                                'For Approval': {
                                    bg: 'rgba(59, 130, 246, 0.7)',
                                    border: 'rgba(59, 130, 246, 1)'
                                },
                                'Approved': {
                                    bg: 'rgba(16, 185, 129, 0.7)',
                                    border: 'rgba(16, 185, 129, 1)'
                                },
                                'Completed': {
                                    bg: 'rgba(139, 92, 246, 0.7)',
                                    border: 'rgba(139, 92, 246, 1)'
                                },
                                'Cancelled': {
                                    bg: 'rgba(107, 114, 128, 0.7)',
                                    border: 'rgba(107, 114, 128, 1)'
                                },
                                'Disapproved': {
                                    bg: 'rgba(239, 68, 68, 0.7)',
                                    border: 'rgba(239, 68, 68, 1)'
                                }
                            };

                            // Prepare data for the chart
                            const labels = data.pieChart.labels || [];
                            const chartData = {
                                labels: labels,
                                datasets: [{
                                    data: data.pieChart.data || [],
                                    backgroundColor: data.pieChart.backgroundColors || labels.map(label => statusColors[label]?.bg || '#cccccc'),
                                    borderColor: data.pieChart.borderColors || labels.map(label => statusColors[label]?.border || '#999999'),
                                    borderWidth: 1
                                }]
                            };

                            pieChart = new Chart(pieCtx, {
                                type: 'doughnut',
                                data: chartData,
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'right',
                                            labels: {
                                                usePointStyle: true,
                                                padding: 15,
                                                font: {
                                                    size: 12
                                                }
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    const label = context.label || '';
                                                    const value = context.raw || 0;
                                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                                    return `${label}: ${value} (${percentage}%)`;
                                                }
                                            }
                                        }
                                    },
                                    cutout: '60%',
                                    animation: {
                                        animateScale: true,
                                        animateRotate: true
                                    }
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching analytics data:', error);
                    // Show error message to user
                    alert('Failed to load analytics data. Please try again later.');
                })
                .finally(() => {
                    // Always remove loading state
                    $('.chart-container').removeClass('opacity-50');
                });
            }
            
            // Get appropriate chart options based on time period
            function getLineChartOptions(period) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'top',
                            labels: {
                                boxWidth: 12
                            }
                        },
                        tooltip: { 
                            mode: 'index', 
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: getChartTitle(period),
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: { top: 10, bottom: 15 }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { 
                                display: true, 
                                color: 'rgba(0, 0, 0, 0.05)' 
                            },
                            title: { 
                                display: true, 
                                text: 'Number of Orders',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        x: { 
                            grid: { display: false },
                            title: {
                                display: true,
                                text: getXAxisTitle(period),
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                };
            }
            
            // Get X-axis title based on period
            function getXAxisTitle(period) {
                const month = monthSelect.options[monthSelect.selectedIndex].text;
                const year = yearSelect.value;
                
                switch(period) {
                    case 'daily':
                        return `Days of ${month} ${year}`;
                    case 'weekly':
                        return `Weeks of ${month} ${year}`;
                    case 'monthly':
                        return `Months of ${year}`;
                    case 'yearly':
                        return 'Years';
                    default:
                        return 'Timeline';
                }
            }
            
            // Update chart title based on period and selected date
            function getChartTitle(period) {
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];
                
                const month = parseInt(monthSelect.value) - 1; // Convert to 0-indexed month
                const year = yearSelect.value;
                
                switch(period) {
                    case 'daily':
                        return `Daily Travel Orders - ${monthNames[month]} ${year}`;
                    case 'weekly':
                        return `Weekly Travel Orders - ${monthNames[month]} ${year}`;
                    case 'monthly':
                        return `Monthly Travel Orders - ${year}`;
                    case 'yearly':
                        return `Yearly Travel Orders - ${year}`;
                    default:
                        return 'Travel Orders Overview';
                }
            }
            
            // Initialize selectors visibility
            updateSelectorVisibility();
            
            // Initialize charts on page load
            initCharts();
            
            // Add event listeners
            timePeriodSelect.addEventListener('change', function() {
                updateSelectorVisibility();
                initCharts();
            });
            
            monthSelect.addEventListener('change', function() {
                if (timePeriodSelect.value === 'monthly') {
                    initCharts();
                }
            });
            
            yearSelect.addEventListener('change', function() {
                initCharts();
            });
            
            // Update chart title based on period
            function getChartTitle(period, month = null, year = null) {
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];
                
                switch(period) {
                    case 'daily':
                        return `Daily Travel Orders` + (year ? ` ${year}` : '');
                    case 'weekly':
                        return `Weekly Travel Orders` + (year ? ` ${year}` : '');
                    case 'monthly':
                        if (month && year) {
                            return `Monthly Travel Orders - ${monthNames[month-1]} ${year}`;
                        }
                        return 'Monthly Travel Orders';
                    case 'yearly':
                        return `Yearly Travel Orders` + (year ? ` (${year})` : '');
                    default:
                        return 'Travel Orders Overview';
                }
            }
        });
    </script>
    <style>
        .chart-container {
            transition: opacity 0.3s ease-in-out;
        }
        .chart-container.loading {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
    @endpush
@endsection
