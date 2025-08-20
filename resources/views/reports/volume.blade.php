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
                <h2 class="text-xl font-semibold text-gray-800">Travel Order Volume Report</h2>
            </div>
            <div class="flex space-x-2">
                <div class="relative">
                    <select id="timeframe" class="appearance-none bg-white border border-gray-300 py-2 rounded-md pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly" selected>Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <div class="relative">
                    <select id="statusFilter" class="appearance-none bg-white border border-gray-300 py-2 rounded-md pl-3 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\TravelOrderStatus::all() as $status)
                            <option value="{{ $status->name }}" {{ request('status') == $status->name ? 'selected' : '' }}>
                                {{ ucfirst($status->name) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <button class="relative p-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 overflow-y-auto p-6">

        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
                <div class="h-80">
                    <canvas id="volumeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Detailed Data</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Travel Orders</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $total = $data->sum('total');
                        @endphp
                        @forelse($data as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->period)->format('M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    {{ number_format($item->total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    {{ $total > 0 ? number_format(($item->total / $total) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    No data available for the selected filters
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($data->isNotEmpty())
                        <tfoot class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Total</th>
                                <th class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($total) }}</th>
                                <th class="px-6 py-3 text-right text-sm font-medium text-gray-900">100%</th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        const ctx = document.getElementById('volumeChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($data->pluck('period')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M Y'))),
                datasets: [{
                    label: 'Number of Travel Orders',
                    data: @json($data->pluck('total')),
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 0.8)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(59, 130, 246, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14 },
                        bodyFont: { size: 14 },
                        padding: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Handle filter changes
        document.getElementById('timeframe').addEventListener('change', updateFilters);
        document.getElementById('statusFilter').addEventListener('change', updateFilters);

        function updateFilters() {
            const timeframe = document.getElementById('timeframe').value;
            const status = document.getElementById('statusFilter').value;
            
            let url = '{{ route("reports.travel-volume") }}';
            const params = new URLSearchParams();
            
            if (timeframe) params.append('timeframe', timeframe);
            if (status) params.append('status', status);
            
            window.location.href = url + '?' + params.toString();
        }
    });
</script>
@endpush

<style>
    .chart-container {
        position: relative;
        height: 400px;
    }
</style>
@endsection
