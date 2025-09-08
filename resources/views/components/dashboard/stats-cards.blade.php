@props(['totalTravelOrders', 'pendingRequests', 'completedRequests', 'cancelledRequests'])

<div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Travel Orders Card -->
    <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium opacity-90">Total Travel Orders</p>
                <p class="text-2xl font-bold mt-1">{{ $totalTravelOrders }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                <i class="fas fa-plus text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card -->
    <div class="bg-yellow-600 text-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium opacity-90">Pending Requests</p>
                <p class="text-2xl font-bold mt-1">{{ $pendingRequests }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Completed Requests Card -->
    <div class="bg-green-600 text-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium opacity-90">Completed Requests</p>
                <p class="text-2xl font-bold mt-1">{{ $completedRequests }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Cancelled Requests Card -->
    <div class="bg-gray-600 text-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium opacity-90">Cancelled Requests</p>
                <p class="text-2xl font-bold mt-1">{{ $cancelledRequests }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                <i class="fas fa-times-circle text-xl"></i>
            </div>
        </div>
    </div>
</div>
