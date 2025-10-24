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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
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
                                    datasets: [{
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
                            const hasData = data.pieChart && data.pieChart.data && data.pieChart.data.length >
                                0 &&
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
                                noDataContent.className =
                                    'absolute inset-0 flex flex-col items-center justify-center text-center p-4';

                                const noDataText = document.createElement('p');
                                noDataText.className = 'text-gray-500 mb-4 text-sm';
                                noDataText.textContent = 'No travel order data available';

                                @if (!auth()->user()->is_admin)
                                    const createButton = document.createElement('a');
                                    createButton.href = '{{ route('travel-orders.create') }}';
                                    createButton.className =
                                        'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150';
                                    createButton.innerHTML = `
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Travel Order
                            `;
                                @endif

                                noDataContent.appendChild(noDataText);
                                @if (!auth()->user()->is_admin)
                                    noDataContent.appendChild(createButton);
                                @endif
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
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                enabled: false
                                            }
                                        },
                                        animation: {
                                            animateRotate: false,
                                            animateScale: false
                                        }
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
                                        backgroundColor: data.pieChart.backgroundColors || labels
                                            .map(label => statusColors[label]?.bg || '#cccccc'),
                                        borderColor: data.pieChart.borderColors || labels.map(
                                            label => statusColors[label]?.border || '#999999'),
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
                                                        const total = context.dataset.data.reduce((
                                                            a, b) => a + b, 0);
                                                        const percentage = total > 0 ? Math.round((
                                                            value / total) * 100) : 0;
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
                            padding: {
                                top: 10,
                                bottom: 15
                            }
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
                            grid: {
                                display: false
                            },
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

                switch (period) {
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
                    "July", "August", "September", "October", "November", "December"
                ];

                const month = parseInt(monthSelect.value) - 1; // Convert to 0-indexed month
                const year = yearSelect.value;

                switch (period) {
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
                    "July", "August", "September", "October", "November", "December"
                ];

                switch (period) {
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
