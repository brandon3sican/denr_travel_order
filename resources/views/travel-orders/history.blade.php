@extends('layout.app')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Travel Orders History</h2>
                </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @if (auth()->user()->is_admin)
                <!-- All Travel History (from travel_order_status_histories) -->
                <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                    <div class="p-3 border-b bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">All Approval History</h3>
                                <p class="text-xs text-gray-500">All approval history of travel orders.</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                <!-- Search -->
                                <div class="relative flex-1">
                                    <input type="text" id="searchInput" placeholder="Search..."
                                        class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <!-- Date Range Picker -->
                                <div class="relative" id="dateRangePicker">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Date Range</span>
                                    </button>
                                    <div
                                        class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 z-10 p-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">From</label>
                                                <input type="date" id="dateFrom"
                                                    class="w-full text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">To</label>
                                                <input type="date" id="dateTo"
                                                    class="w-full text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                        <div class="mt-2 flex justify-end gap-2">
                                            <button type="button" id="clearDates"
                                                class="text-xs text-gray-600 hover:text-gray-800">Clear</button>
                                            <button type="button" id="applyDates"
                                                class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">When</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Action</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Travel Order
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Status Change
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Actor</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Location</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Where (IP)</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">How
                                        (Device/Browser)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(($allHistory ?? []) as $h)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            {{ optional($h->created_at)->format('M d, Y h:i A') ?? '—' }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">{{ ucfirst(str_replace('_', ' ', $h->action)) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="space-y-1">
                                                <div class="font-medium text-gray-900">
                                                    {{ $h->travelOrder->employee->first_name }}
                                                    {{ $h->travelOrder->employee->middle_name ?: '' }}
                                                    {{ $h->travelOrder->employee->last_name }}
                                                </div>
                                            </div>
                                            @if (optional($h->travelOrder)->destination)
                                                <div class="text-xs text-gray-500">Destination:
                                                    {{ $h->travelOrder->destination ?? '—' }}</div>
                                                <div class="text-xs text-gray-500">Purpose:
                                                    {{ $h->travelOrder->purpose ?? '—' }}</div>
                                                <div class="text-xs text-gray-500">Date:
                                                    {{ \Carbon\Carbon::parse($h->travelOrder->departure_date)->format('M d, Y') }}
                                                    to
                                                    {{ \Carbon\Carbon::parse($h->travelOrder->arrival_date)->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <div class="pt-2">
                                                        <button onclick="showTravelOrder({{ $h->travelOrder->id }})"
                                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            <i class="fas fa-eye mr-1"></i> View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-700">{{ $h->from_status ?? '—' }} →
                                            {{ $h->to_status ?? '—' }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-700">
                                            {{ optional($h->user)->name ?? (optional($h->user)->email ?? '—') }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-700">
                                            @php
                                                $travelOrder = $h->travelOrder;
                                                $location = [];

                                                if ($travelOrder) {
                                                    // Add street/address if available
                                                    if (!empty($travelOrder->street_address)) {
                                                        $location[] = $travelOrder->street_address;
                                                    } elseif (!empty($travelOrder->address)) {
                                                        $location[] = $travelOrder->address;
                                                    }

                                                    // Add location/destination if different from address
                                                    if (
                                                        !empty($travelOrder->location) &&
                                                        (!isset($location[0]) || $travelOrder->location != $location[0])
                                                    ) {
                                                        $location[] = $travelOrder->location;
                                                    } elseif (
                                                        !empty($travelOrder->destination) &&
                                                        (!isset($location[0]) ||
                                                            $travelOrder->destination != $location[0])
                                                    ) {
                                                        $location[] = $travelOrder->destination;
                                                    }

                                                    // Always add coordinates if available
                                                    if ($travelOrder->lat && $travelOrder->lng) {
                                                        $coordinates =
                                                            number_format($travelOrder->lat, 6) .
                                                            ', ' .
                                                            number_format($travelOrder->lng, 6);
                                                        // Only add if not already in location array
                                                        if (!in_array($coordinates, $location)) {
                                                            $location[] = $coordinates;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if (!empty($location))
                                                <div class="space-y-1">
                                                    @foreach ($location as $loc)
                                                        <div>{{ $loc }}</div>
                                                    @endforeach
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600">{{ $h->ip_address ?? '—' }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            {{ trim(($h->device ?? '') . ' ' . ($h->browser ? '(' . $h->browser . ')' : '')) ?: '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No history
                                            recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (($allHistory ?? null) && $allHistory->hasPages())
                        <div class="bg-white px-3 py-2 flex items-center justify-between border-t border-gray-200">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs text-gray-600">
                                        Showing <span class="font-medium">{{ $allHistory->firstItem() }}</span>
                                        to <span class="font-medium">{{ $allHistory->lastItem() }}</span>
                                        of <span class="font-medium">{{ $allHistory->total() }}</span> results
                                    </p>
                                </div>
                                <div>
                                    {{ $allHistory->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <!-- Travel History (from travel_order_status_histories) -->
                <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                    <div class="p-3 border-b bg-gray-50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Approvals History</h3>
                                <p class="text-xs text-gray-500">Approvals history of travel orders.</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                <!-- Search -->
                                <div class="relative flex-1">
                                    <input type="text" id="searchInput" placeholder="Search..."
                                        class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <svg class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <!-- Date Range Picker -->
                                <div class="relative" id="dateRangePicker">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Date Range</span>
                                    </button>
                                    <div
                                        class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 z-10 p-3">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">From</label>
                                                <input type="date" id="dateFrom"
                                                    class="w-full text-sm border-gray-300 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">To</label>
                                                <input type="date" id="dateTo"
                                                    class="w-full text-sm border-gray-300 rounded-md">
                                            </div>
                                        </div>
                                        <div class="mt-2 flex justify-end gap-2">
                                            <button type="button" id="clearDates"
                                                class="text-xs text-gray-600 hover:text-gray-800">Clear</button>
                                            <button type="button" id="applyDates"
                                                class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Apply</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Travel Order
                                        Details
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Status Change
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Date & Time
                                    </th>

                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse(($allHistory ?? []) as $h)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="space-y-1">
                                                <div class="font-medium text-gray-900">
                                                    {{ $h->travelOrder->employee->first_name }}
                                                    {{ $h->travelOrder->employee->middle_name ?: '' }}
                                                    {{ $h->travelOrder->employee->last_name }}
                                                </div>
                                            </div>
                                            @if (optional($h->travelOrder)->destination)
                                                <div class="text-xs text-gray-500">Destination:
                                                    {{ $h->travelOrder->destination ?? '—' }}</div>
                                                <div class="text-xs text-gray-500">Purpose:
                                                    {{ $h->travelOrder->purpose ?? '—' }}</div>
                                                <div class="text-xs text-gray-500">Date:
                                                    {{ \Carbon\Carbon::parse($h->travelOrder->departure_date)->format('M d, Y') }}
                                                    to
                                                    {{ \Carbon\Carbon::parse($h->travelOrder->arrival_date)->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <div class="pt-2">
                                                        <button onclick="showTravelOrder({{ $h->travelOrder->id }})"
                                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            <i class="fas fa-eye mr-1"></i> View Details
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-700">{{ $h->from_status ?? '—' }} →
                                            {{ $h->to_status ?? '—' }}</td>
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            {{ optional($h->created_at)->format('M d, Y h:i A') ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No history
                                            recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if (($allHistory ?? null) && $allHistory->hasPages())
                        <div class="bg-white px-3 py-2 flex items-center justify-between border-t border-gray-200">
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs text-gray-600">
                                        Showing <span class="font-medium">{{ $allHistory->firstItem() }}</span>
                                        to <span class="font-medium">{{ $allHistory->lastItem() }}</span>
                                        of <span class="font-medium">{{ $allHistory->total() }}</span> results
                                    </p>
                                </div>
                                <div>
                                    {{ $allHistory->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </main>

        <footer class="bg-white border-t border-gray-200 mt-4">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                    Resources. All rights reserved.</p>
            </div>
        </footer>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle date picker
                const dateRangeBtn = document.getElementById('dateRangePicker');
                if (dateRangeBtn) {
                    const dateRangeDropdown = dateRangeBtn.querySelector('div[class*="hidden"]');
                    const dateFromInput = document.getElementById('dateFrom');
                    const dateToInput = document.getElementById('dateTo');
                    const applyDatesBtn = document.getElementById('applyDates');
                    const clearDatesBtn = document.getElementById('clearDates');
                    const searchInput = document.getElementById('searchInput');
                    const table = document.querySelector('table');
                    const tableRows = table ? table.querySelectorAll('tbody tr:not(.no-results)') : [];

                    // Function to parse date from table cell
                    function parseDateFromCell(dateString) {
                        if (!dateString) return null;
                        // Try to parse the date string
                        const date = new Date(dateString);
                        if (!isNaN(date.getTime())) {
                            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
                        }
                        return null;
                    }

                    // Toggle date picker dropdown
                    dateRangeBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        dateRangeDropdown.classList.toggle('hidden');
                    });

                    // Close date picker when clicking outside
                    document.addEventListener('click', function() {
                        if (!dateRangeDropdown.classList.contains('hidden')) {
                            dateRangeDropdown.classList.add('hidden');
                        }
                    });

                    // Prevent dropdown from closing when clicking inside it
                    dateRangeDropdown.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });

                    // Apply filters based on date range and search
                    function applyFilters() {
                        const fromDate = dateFromInput.value ? new Date(dateFromInput.value) : null;
                        const toDate = dateToInput.value ? new Date(dateToInput.value) : null;
                        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

                        // Set time to start of day for fromDate and end of day for toDate
                        if (fromDate) fromDate.setHours(0, 0, 0, 0);
                        if (toDate) toDate.setHours(23, 59, 59, 999);

                        let hasVisibleRows = false;

                        tableRows.forEach(row => {
                            const dateCell = row.querySelector('td:first-child');
                            if (!dateCell) return;

                            const rowDate = parseDateFromCell(dateCell.textContent.trim());
                            if (!rowDate) return;

                            let isVisible = true;

                            // Apply date range filter
                            if (fromDate && rowDate < fromDate) {
                                isVisible = false;
                            }
                            if (toDate && rowDate > toDate) {
                                isVisible = false;
                            }

                            // Apply search filter if there's a search term
                            if (isVisible && searchTerm) {
                                const rowText = row.textContent.toLowerCase();
                                isVisible = rowText.includes(searchTerm);
                            }

                            row.style.display = isVisible ? '' : 'none';
                            if (isVisible) hasVisibleRows = true;
                        });

                        // Show/hide no results message only when searching
                        const tbody = table ? table.querySelector('tbody') : null;
                        let noResultsRow = tbody ? tbody.querySelector('tr.no-results') : null;

                        // Only show no results message if there's an active search
                        if (searchTerm && !hasVisibleRows) {
                            if (!noResultsRow && tbody) {
                                noResultsRow = document.createElement('tr');
                                noResultsRow.className = 'no-results';
                                noResultsRow.innerHTML = `
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No matching records found
                                    </td>
                                `;
                                tbody.appendChild(noResultsRow);
                            }
                        } else if (noResultsRow) {
                            noResultsRow.remove();
                        }
                    }

                    // Event listeners
                    if (applyDatesBtn) {
                        applyDatesBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            dateRangeDropdown.classList.add('hidden');
                            applyFilters();
                        });
                    }

                    if (clearDatesBtn) {
                        clearDatesBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            dateFromInput.value = '';
                            dateToInput.value = '';
                            dateRangeDropdown.classList.add('hidden');
                            applyFilters();
                        });
                    }

                    // Search functionality
                    if (searchInput) {
                        searchInput.addEventListener('input', applyFilters);
                    }

                    // Apply filters when both dates are selected
                    [dateFromInput, dateToInput].forEach(input => {
                        if (input) {
                            input.addEventListener('change', function() {
                                if (dateFromInput.value && dateToInput.value) {
                                    applyFilters();
                                }
                            });
                        }
                    });

                    // Initial filter application
                    applyFilters();
                }
            });
        </script>
    @endpush

    @include('components.travel-order.travel-order-modal')
@endsection
