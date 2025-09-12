@props(['statuses' => [], 'currentStatus' => null, 'searchQuery' => ''])

<div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 space-y-2 sm:space-y-0 w-full">
    @if (!auth()->user()->is_admin)
        <div class="w-full sm:w-auto">
            <a href="{{ route('travel-orders.create') }}"
                class="w-full sm:w-auto flex justify-center items-center px-3 sm:px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-1 sm:mr-2"></i>
                <span class="sm:inline">New</span>
            </a>
        </div>
    @endif
    <div class="w-full">
        <form id="filterForm" method="GET" class="w-full">
            <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:space-x-2 w-full">
                <div class="flex-1 min-w-0">
                    <select name="status" id="statusFilter" onchange="this.form.submit()"
                        class="w-full pl-3 pr-10 py-2 text-xs sm:text-sm border border-gray-300 bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                        <option value="" {{ $currentStatus === '' ? 'selected' : '' }}>All Status</option>
                        @foreach ($statuses as $status => $label)
                            <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($currentStatus || $searchQuery)
                    <div class="flex-shrink-0 w-full sm:w-auto">
                        <a href="{{ route('dashboard') }}"
                            class="w-full sm:w-auto flex items-center justify-center px-3 py-2 border border-transparent text-xs sm:text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-times mr-1 sm:mr-2"></i>
                            <span>Clear</span>
                        </a>
                    </div>
                @endif
                <input type="hidden" name="search" value="{{ $searchQuery }}">
            </div>
        </form>
    </div>
    <div class="relative">
        <div class="relative">
            <input type="text" name="search" id="searchInput" value="{{ $searchQuery }}" placeholder="Search..."
                class="block w-full pl-4 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                onkeypress="if(event.key === 'Enter') { document.getElementById('filterForm').submit(); }">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="submit" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <input type="hidden" name="status" value="{{ $currentStatus }}">
    </div>
</div>
