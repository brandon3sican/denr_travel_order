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
                    <h2 class="text-xl font-semibold text-gray-800">Reports</h2>
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
            <!-- Metrics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Average Approval Time -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Avg. Approval Time</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ number_format($metrics['avg_approval_time'], 1) }} hours
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending Approvals</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ number_format($metrics['pending_approvals']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Approvers with Pending -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Approvers with Pending</p>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ count($metrics['approval_workload']) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approver Workload -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Approver Workload</h3>
                    <p class="text-sm text-gray-500">Number of pending approvals per approver</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approver</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Approvals</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($metrics['approval_workload'] as $approver)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-600 font-medium">{{ substr($approver->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $approver->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $approver->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ $approver->pending_approval_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        {{ $approver->last_login_at ? \Carbon\Carbon::parse($approver->last_login_at)->diffForHumans() : 'Never' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        No approvers with pending approvals found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approval Time Distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Time Distribution</h3>
                    <div class="h-64">
                        <canvas id="approvalTimeChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Status</h3>
                    <div class="h-64">
                        <canvas id="approvalStatusChart"></canvas>
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
        // Approval Time Distribution Chart
        const timeCtx = document.getElementById('approvalTimeChart').getContext('2d');
        new Chart(timeCtx, {
            type: 'bar',
            data: {
                labels: ['<1h', '1-4h', '4-8h', '8-24h', '1-3d', '>3d'],
                datasets: [{
                    label: 'Number of Approvals',
                    data: [12, 19, 8, 15, 10, 5],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
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

        // Approval Status Chart
        const statusCtx = document.getElementById('approvalStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Returned'],
                datasets: [{
                    data: [65, 15, 10, 10],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(153, 102, 255, 1)'
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
@endsection
