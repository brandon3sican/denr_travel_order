<div class="flex flex-col md:flex-row gap-4 mb-6">
    <!-- Search Input -->
    <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </div>
        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by destination or purpose..."
            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>

    <!-- Status Filter -->
    <div class="relative w-48">
        <select name="status" id="status" onchange="this.form.submit()"
            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option value="">All Status</option>
            @foreach($statuses as $id => $name)
                <option value="{{ $id }}" {{ request('status') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Date Range Picker -->
    <div class="relative w-72">
        <input type="text" name="date_range" id="date-range" value="{{ request('date_range') }}" 
            class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Select date range">
    </div>

    <!-- Reset Button -->
    @if(request()->has('search') || request()->has('status') || request()->has('date_range'))
        <a href="{{ route('travel-orders.index') }}" 
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Reset Filters
        </a>
    @endif
</div>
