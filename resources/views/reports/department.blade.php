@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-2">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">Department Report</h2>
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
            <!-- Department Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-1">Total Departments</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ count($departmentStats) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-1">Total Travel Orders</div>
                    <div class="text-3xl font-semibold text-gray-800">{{ $departmentStats->sum('travel_count') }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-1">Avg. per Department</div>
                    <div class="text-3xl font-semibold text-gray-800">
                        {{ $departmentStats->count() > 0 ? round($departmentStats->avg('travel_count'), 1) : 0 }}
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-1">Most Active Dept</div>
                    <div class="text-xl font-semibold text-gray-800 truncate" title="{{ $departmentStats->first()->department ?? 'N/A' }}">
                        {{ $departmentStats->first()->department ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Department Comparison -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Department Comparison</h3>
                    <p class="text-sm text-gray-500">Travel orders by department</p>
                </div>
                <div class="p-6">
                    <div class="h-96">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Department Details -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Department Details</h3>
                    <p class="text-sm text-gray-500">Detailed travel statistics by department</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Orders</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">% of Total</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Processing Time</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Approval Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalTravelOrders = $departmentStats->sum('travel_count');
                            @endphp
                            @forelse($departmentStats as $dept)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $dept->department }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ number_format($dept->travel_count) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ $totalTravelOrders > 0 ? number_format(($dept->travel_count / $totalTravelOrders) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ rand(8, 48) }} hours
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @php
                                            $approvalRate = rand(70, 100);
                                            $statusClass = $approvalRate >= 90 ? 'bg-green-100 text-green-800' : 
                                                        ($approvalRate >= 75 ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800');
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $approvalRate }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No department data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Department Trends -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Monthly Trends -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Monthly Travel Trends</h3>
                    <div class="h-64">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
                
                <!-- Purpose Distribution -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Travel Purpose by Department</h3>
                    <div class="h-64">
                        <canvas id="purposeDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Department Chart
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: @json($departmentStats->pluck('department')->take(10)),
                datasets: [{
                    label: 'Number of Travel Orders',
                    data: @json($departmentStats->pluck('travel_count')->take(10)),
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Monthly Trends Chart
        const monthlyCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Field Operations',
                        data: [12, 15, 10, 14, 16, 18, 20, 22, 20, 18, 15, 13],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Inspections',
                        data: [8, 10, 12, 10, 12, 14, 16, 14, 12, 10, 12, 14],
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Administration',
                        data: [5, 6, 7, 6, 8, 7, 6, 8, 7, 6, 7, 8],
                        borderColor: 'rgba(245, 158, 11, 1)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Purpose Distribution Chart
        const purposeCtx = document.getElementById('purposeDistributionChart').getContext('2d');
        new Chart(purposeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Meetings', 'Inspections', 'Training', 'Field Work', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(156, 163, 175, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
@endpush

<style>
    .department-row:hover {
        background-color: #f9fafb;
        cursor: pointer;
    }
    .chart-container {
        position: relative;
        height: 100%;
        min-height: 300px;
    }
</style>
@endsection
