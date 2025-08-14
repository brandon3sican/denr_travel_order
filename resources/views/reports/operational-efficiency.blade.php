@extends('layout.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Operational Efficiency</h1>
        <p class="text-gray-600">Analyze system efficiency and processing times</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Average Processing Time -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-stopwatch text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Avg. Processing Time</p>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ number_format($processingTimes['submission_to_approval'], 1) }} hours
                    </p>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Completion Rate</p>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ 100 - number_format($cancellationRates, 1) }}%
                    </p>
                </div>
            </div>
        </div>

        <!-- Cancellation Rate -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Cancellation Rate</p>
                    <p class="text-2xl font-semibold text-gray-800">
                        {{ number_format($cancellationRates, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Processing Time Trends -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Processing Time Trends</h3>
            <div class="h-64">
                <canvas id="processingTimeChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Distribution</h3>
            <div class="h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Efficiency Metrics -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Efficiency Metrics</h3>
            <p class="text-sm text-gray-500">Key performance indicators for travel order processing</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metric</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Average Approval Time</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($processingTimes['submission_to_approval'], 1) }} hours</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">< 24 hours</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                On Target
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Completion Rate</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ 100 - number_format($cancellationRates, 1) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">> 90%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ (100 - $cancellationRates) >= 90 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ (100 - $cancellationRates) >= 90 ? 'On Target' : 'Needs Improvement' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Cancellation Rate</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($cancellationRates, 1) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">< 10%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cancellationRates <= 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $cancellationRates <= 10 ? 'On Target' : 'Needs Attention' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Average Completion Time</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($processingTimes['approval_to_completion'], 1) }} hours</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">< 72 hours</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $processingTimes['approval_to_completion'] <= 72 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $processingTimes['approval_to_completion'] <= 72 ? 'On Target' : 'Needs Attention' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Process Bottlenecks -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Process Bottlenecks</h3>
            <p class="text-sm text-gray-500">Areas where delays commonly occur</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900">Approval Delay</span>
                        <span class="text-gray-500">45% of delays</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900">Documentation</span>
                        <span class="text-gray-500">30% of delays</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900">Budget Approval</span>
                        <span class="text-gray-500">15% of delays</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-500 h-2.5 rounded-full" style="width: 15%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900">Other</span>
                        <span class="text-gray-500">10% of delays</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-gray-500 h-2.5 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Processing Time Chart
        const timeCtx = document.getElementById('processingTimeChart').getContext('2d');
        new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Avg. Processing Time (hours)',
                    data: [24, 22, 20, 18, 16, 15, 14, 13, 12, 11, 10, 9],
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Hours'
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Cancelled', 'Completed'],
                datasets: [{
                    data: [40, 20, 5, 10, 25],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                        'rgba(59, 130, 246, 0.8)'
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
    .efficiency-metric {
        transition: all 0.3s ease;
    }
    .efficiency-metric:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
@endsection
