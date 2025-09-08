@props(['statuses' => [], 'currentStatus' => null, 'searchQuery' => ''])

<div class="flex items-center space-x-3">
    @if (!auth()->user()->is_admin)
        <div>
            <a href="{{ route('travel-orders.create') }}"
                class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i> New Travel Order
            </a>
        </div>
    @endif
    <div class="relative">
        <form id="filterForm" method="GET" class="flex space-x-2 border border-gray-500 rounded-md">
            <select name="status" id="statusFilter" onchange="this.form.submit()"
                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="" {{ $currentStatus === '' ? 'selected' : '' }}>All Status</option>
                @foreach ($statuses as $status => $label)
                    <option value="{{ $status }}" {{ $currentStatus === $status ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @if ($currentStatus || $searchQuery)
                <a href="{{ route('dashboard') }}"
                    class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">
                    Clear
                </a>
            @endif
            <input type="hidden" name="search" value="{{ $searchQuery }}">
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
