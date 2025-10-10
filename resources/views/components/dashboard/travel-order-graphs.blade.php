@props(['chartData', 'statusData'])

<div class="mt-8">
    <!-- Charts Container -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-md font-medium text-gray-900">Travel Orders Trend</h4>
            <p class="text-sm text-gray-600 mb-4">Click on the chart to view the details of each status.</p>
            <div class="h-80">
                <!-- Filter Section -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-3 sm:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900">Travel Order Analytics</h3>
                    <div class="flex space-x-2">
                        <select id="timeRange"
                            class="block w-40 text-center rounded-md border-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="all" selected disabled>-Select-</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                        <button id="applyFilter"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply
                        </button>
                    </div>
                </div>
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-md font-medium text-gray-900">Status Distribution</h4>
            <p class="text-sm text-gray-600 mb-4">Click on the chart to view the details of each status.</p>
            <div class="h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize line chart
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Total Travel Orders',
                        data: @json($chartData['datasets']['total']),
                        borderColor: 'rgb(59, 130, 246)', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }, {
                        label: 'Pending Travel Orders',
                        data: @json($chartData['datasets']['pending']),
                        borderColor: 'rgb(234, 179, 8)', // yellow-500
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }, {
                        label: 'Completed Travel Orders',
                        data: @json($chartData['datasets']['completed']),
                        borderColor: 'rgb(16, 185, 129)', // green-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }, {
                        label: 'Cancelled Travel Orders',
                        data: @json($chartData['datasets']['cancelled']),
                        borderColor: 'rgb(239, 68, 68)', // red-500
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Function to get color for a specific status
            function getStatusColor(status) {
                const colorMap = {
                    'pending': {
                        bg: 'rgba(234, 179, 8, 0.7)',
                        border: 'rgba(234, 179, 8, 1)'
                    },
                    'for approval': {
                        bg: 'rgba(249, 115, 22, 0.7)',
                        border: 'rgba(249, 115, 22, 1)'
                    },
                    'recommendation': {
                        bg: 'rgba(245, 158, 11, 0.7)',
                        border: 'rgba(245, 158, 11, 1)'
                    },
                    'approved': {
                        bg: 'rgba(16, 185, 129, 0.7)',
                        border: 'rgba(16, 185, 129, 1)'
                    },
                    'completed': {
                        bg: 'rgba(5, 150, 105, 0.7)',
                        border: 'rgba(5, 150, 105, 1)'
                    },
                    'cancelled': {
                        bg: 'rgba(239, 68, 68, 0.7)',
                        border: 'rgba(239, 68, 68, 1)'
                    },
                    'disapproved': {
                        bg: 'rgba(185, 28, 28, 0.7)',
                        border: 'rgba(185, 28, 28, 1)'
                    },
                    'default': {
                        bg: 'rgba(156, 163, 175, 0.7)',
                        border: 'rgba(156, 163, 175, 1)'
                    }
                };

                const lowerStatus = status.toLowerCase();
                for (const [key, colors] of Object.entries(colorMap)) {
                    if (lowerStatus.includes(key)) {
                        return colors;
                    }
                }
                return colorMap.default;
            }

            // Get initial colors
            const initialColors = @json($statusData['labels']).map(label => getStatusColor(label));

            // Initialize pie chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: @json($statusData['labels']),
                    datasets: [{
                        data: @json($statusData['data']),
                        backgroundColor: initialColors.map(c => c.bg),
                        borderColor: initialColors.map(c => c.border),
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
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
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    layout: {
                        padding: 10
                    }
                }
            });

            // Filter functionality
            document.getElementById('applyFilter').addEventListener('click', function() {
                const timeRange = document.getElementById('timeRange').value;

                // Show loading state
                const button = this;
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';

                // Make AJAX request to get filtered data
                fetch(`/dashboard/analytics?range=${timeRange}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Received data:', data); // Debug log

                        // Update line chart
                        if (data.chartData && data.chartData.labels && data.chartData.datasets) {
                            lineChart.data.labels = data.chartData.labels;
                            lineChart.data.datasets[0].data = data.chartData.datasets.total;
                            lineChart.data.datasets[1].data = data.chartData.datasets.pending;
                            lineChart.data.datasets[2].data = data.chartData.datasets.completed;
                            lineChart.data.datasets[3].data = data.chartData.datasets.cancelled;
                            lineChart.update();
                        } else {
                            console.error('Invalid chart data structure:', data);
                        }

                        // Update pie chart with filtered data
                        if (data.statusData && data.statusData.labels && data.statusData.data) {
                            const colors = data.statusData.labels.map(label => getStatusColor(label));

                            pieChart.data.labels = data.statusData.labels;
                            pieChart.data.datasets[0].data = data.statusData.data;
                            pieChart.data.datasets[0].backgroundColor = colors.map(c => c.bg);
                            pieChart.data.datasets[0].borderColor = colors.map(c => c.border);

                            pieChart.update();
                        } else {
                            console.error('Invalid status data structure:', data.statusData);
                        }

                        button.disabled = false;
                        button.innerHTML = originalText;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Error';
                        setTimeout(() => {
                            button.innerHTML = originalText;
                        }, 2000);
                    });
            });
        });
    </script>
@endpush
