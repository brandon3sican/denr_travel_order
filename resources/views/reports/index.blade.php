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
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Travel Volume Report Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Travel Volume</h2>
                        </div>
                        <p class="text-gray-600 mb-4">View travel order volumes over time, filtered by status and time period.</p>
                        <a href="{{ route('reports.travel-volume') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            View Report <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Approval Metrics Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Approval Process</h2>
                        </div>
                        <p class="text-gray-600 mb-4">Analyze approval times, workloads, and bottlenecks in the approval process.</p>
                        <a href="{{ route('reports.approval-metrics') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            View Report <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Employee Travel Patterns Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Employee Travel</h2>
                        </div>
                        <p class="text-gray-600 mb-4">View travel patterns, frequent travelers, and common destinations.</p>
                        <a href="{{ route('reports.employee-travel') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            View Report <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Operational Efficiency Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                <i class="fas fa-tachometer-alt text-xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Operational Efficiency</h2>
                        </div>
                        <p class="text-gray-600 mb-4">Analyze processing times, cancellation rates, and overall system efficiency.</p>
                        <a href="{{ route('reports.operational-efficiency') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            View Report <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Department Reports Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-800">Department Reports</h2>
                        </div>
                        <p class="text-gray-600 mb-4">View travel statistics and metrics by department or division.</p>
                        <a href="{{ route('reports.department') }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                            View Report <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .report-card {
        transition: all 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
@endsection
