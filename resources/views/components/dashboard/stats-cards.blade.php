@props(['totalTravelOrders', 'pendingRequests', 'completedRequests', 'cancelledRequests'])

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">
    <!-- Total Travel Orders Card -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-90 tracking-wide">TOTAL TRAVEL ORDERS</p>
                <p class="text-xl font-bold mt-0.5">{{ $totalTravelOrders }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-white bg-opacity-20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-suitcase text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card -->
    <div class="bg-gradient-to-br from-yellow-600 to-yellow-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-90 tracking-wide">PENDING TRAVEL ORDERS</p>
                <p class="text-xl font-bold mt-0.5">{{ $pendingRequests }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-white bg-opacity-20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Completed Requests Card -->
    <div class="bg-gradient-to-br from-green-600 to-green-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-90 tracking-wide">COMPLETED TRAVEL ORDERS</p>
                <p class="text-xl font-bold mt-0.5">{{ $completedRequests }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-white bg-opacity-20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Cancelled Requests Card -->
    <div class="bg-gradient-to-br from-gray-600 to-gray-500 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-medium opacity-90 tracking-wide">CANCELLED TRAVEL ORDERS</p>
                <p class="text-xl font-bold mt-0.5">{{ $cancelledRequests }}</p>
            </div>
            <div class="w-9 h-9 rounded-full bg-white bg-opacity-20 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-sm"></i>
            </div>
        </div>
    </div>
</div>
