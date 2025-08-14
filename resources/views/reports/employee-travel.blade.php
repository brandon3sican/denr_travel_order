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
                    <h2 class="text-xl font-semibold text-gray-800">Employee Travel Report</h2>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Top Travelers -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Top Travelers</h3>
                        <p class="text-sm text-gray-500">Employees with the most travel orders</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Orders</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Last Trip</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topTravelers as $traveler)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium">{{ substr($traveler->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $traveler->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $traveler->position_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{ $traveler->travel_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ $traveler->last_trip ? \Carbon\Carbon::parse($traveler->last_trip)->format('M d, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No travel data available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Frequent Destinations -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Frequent Destinations</h3>
                        <p class="text-sm text-gray-500">Most common travel destinations</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($frequentDestinations as $destination)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-900">{{ $destination->destination }}</span>
                                        <span class="text-gray-500">{{ $destination->total }} trips</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                            style="width: {{ ($destination->total / max($frequentDestinations->max('total'), 1)) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No destination data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Patterns Over Time -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Travel Patterns Over Time</h3>
                    <p class="text-sm text-gray-500">Monthly travel order volume by employee</p>
                </div>
                <div class="p-6">
                    <div class="h-96">
                        <canvas id="travelPatternsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Travel Purpose Analysis -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Travel Purpose Analysis</h3>
                    <p class="text-sm text-gray-500">Breakdown of travel purposes</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">By Purpose Category</h4>
                            <div class="h-64">
                                <canvas id="purposeCategoryChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-4">By Department</h4>
                            <div class="h-64">
                                <canvas id="departmentPurposeChart"></canvas>
                            </div>
                        </div>
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
        // Travel Patterns Chart
        const travelCtx = document.getElementById('travelPatternsChart').getContext('2d');
        new Chart(travelCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'John Doe',
                        data: [3, 5, 4, 7, 6, 8, 5, 4, 6, 7, 5, 4],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Jane Smith',
                        data: [2, 3, 2, 4, 3, 5, 6, 4, 3, 4, 5, 6],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Mike Johnson',
                        data: [1, 2, 1, 3, 2, 4, 3, 2, 3, 2, 4, 5],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
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

        // Purpose Category Chart
        const purposeCtx = document.getElementById('purposeCategoryChart').getContext('2d');
        new Chart(purposeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Meetings', 'Training', 'Field Work', 'Conferences', 'Inspections', 'Other'],
                datasets: [{
                    data: [30, 25, 20, 15, 5, 5],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
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

        // Department Purpose Chart
        const deptCtx = document.getElementById('departmentPurposeChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: ['Field Operations', 'Inspections', 'Administration', 'Planning', 'Enforcement'],
                datasets: [{
                    label: 'Average Trips per Month',
                    data: [8, 6, 4, 3, 2],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    });
</script>
@endpush

<style>
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endsection
