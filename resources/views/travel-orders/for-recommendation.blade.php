@extends('layout.app')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Travel Orders For Recommendation</h2>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Travel Orders For Recommendation</h3>

                    <!-- Travel Orders Table -->
                    <div class="shadow rounded overflow-hidden mt-2">
                        <div class="overflow-x-auto">
                            <!-- Desktop Table -->
                            <div class="hidden md:block">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase">
                                                Travel Order Details
                                            </th>
                                            <th
                                                class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase w-1/6">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($travelOrders as $order)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4">
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium text-gray-900">
                                                                <i
                                                                    class="fas fa-user w-4 text-center text-gray-400 text-xs"></i>
                                                                {{ $order->employee->first_name }}
                                                                {{ $order->employee->middle_name ?? '' }}
                                                                {{ $order->employee->last_name }}
                                                                {{ $order->employee->suffix_name ?? '' }}
                                                            </div>
                                                        </div>
                                                        <div class="text-sm">
                                                            <i
                                                                class="fas fa-briefcase w-4 text-center text-gray-400 text-xs"></i>
                                                            <span class="font-medium">Purpose:</span> {{ $order->purpose }}
                                                        </div>
                                                        <div class="text-sm">
                                                            <i
                                                                class="fas fa-map-marker-alt w-4 text-center text-gray-400 text-xs"></i>
                                                            <span class="font-medium">Destination:</span>
                                                            {{ $order->destination }}
                                                        </div>
                                                        <div class="flex items-center space-x-4 text-sm">
                                                            <div>
                                                                <span class="font-medium">Travel Dates:</span>
                                                                {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
                                                                to
                                                                {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            Created: {{ $order->created_at->format('M d, Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-2">
                                                            <button onclick="showTravelOrder({{ $order->id }})"
                                                                class="text-blue-600 hover:text-blue-900">
                                                                <i class="fas fa-eye"></i>
                                                                <span class="hidden sm:inline">View Details</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                                    <button onclick="recommend({{ $order->id }})"
                                                        class="text-green-600 hover:text-green-900 border border-green-600 px-2 py-1 rounded mr-3 lg:w-28 md:w-15 sm:w-15"
                                                        {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'disabled' : '' }}>
                                                        <i class="fas fa-thumbs-up sm:hidden"></i>
                                                        <span class="hidden sm:inline">Recommend</span>
                                                    </button>
                                                    <button onclick="reject({{ $order->id }})"
                                                        class="text-red-600 hover:text-red-900 border border-red-600 px-2 py-1 rounded mr-3 lg:w-28 md:w-15 sm:w-15"
                                                        {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'disabled' : '' }}>
                                                        <i class="fas fa-thumbs-down sm:hidden"></i>
                                                        <span class="hidden sm:inline">Reject</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="px-3 py-8 text-center">
                                                    <div class="flex flex-col items-center justify-center space-y-2">
                                                        <i class="fas fa-search text-gray-400 text-4xl"></i>
                                                        <p class="text-gray-500 text-sm font-medium">
                                                            @if (request()->has('search') && !empty(request('search')))
                                                                No match found for "{{ request('search') }}"
                                                            @else
                                                                No travel orders found.
                                                            @endif
                                                        </p>
                                                        @if (request()->has('search') && !empty(request('search')))
                                                            <button
                                                                onclick="document.getElementById('search').value = ''; document.querySelector('form').submit();"
                                                                class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                                                <i class="fas fa-undo-alt mr-1"></i> Clear search
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards -->
                            <div class="md:hidden space-y-4">
                                @forelse($travelOrders as $order)
                                    <div
                                        class="bg-gray-50 rounded-lg shadow-lg shadow-gray-200 overflow-hidden border border-gray-200">
                                        <div class="p-4">
                                            <div class="space-y-2">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="font-medium text-gray-900">
                                                        <i class="fas fa-user mr-1"></i>
                                                        {{ $order->employee->first_name }}
                                                        {{ $order->employee->middle_name ?? '' }}
                                                        {{ $order->employee->last_name }}
                                                        {{ $order->employee->suffix_name ?? '' }}
                                                    </h3>
                                                    <span
                                                        class="text-xs px-2 py-1 rounded-full font-bold {{ $order->status->name === 'For Recommendation' ? 'bg-yellow-100 text-yellow-800' : ($order->status->name === 'Recommended' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $order->status->name }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Position:</span>
                                                    {{ $order->employee->position_name }}
                                                </p>
                                                <p class="text-sm">
                                                    <span class="font-medium">Purpose:</span> {{ $order->purpose }}
                                                </p>
                                                <p class="text-sm">
                                                    <span class="font-medium">Destination:</span> {{ $order->destination }}
                                                </p>
                                                <p class="text-sm">
                                                    <span class="font-medium">Dates:</span>
                                                    {{ \Carbon\Carbon::parse($order->departure_date)->format('M d') }} -
                                                    {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    Created: {{ $order->created_at->format('M d, Y') }}
                                                </p>
                                                <!-- Action Buttons -->
                                                <div class="mt-4 pt-3 border-t border-gray-100">
                                                    <div class="flex flex-col space-y-3">

                                                        <!-- Action Buttons -->
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <button onclick="recommend({{ $order->id }})"
                                                                class="w-full flex items-center justify-center px-3 py-2 border border-green-600 rounded-md text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'disabled' : '' }}>
                                                                <i class="fas fa-thumbs-up mr-1"></i>
                                                                <span>Recommend</span>
                                                            </button>
                                                            <button onclick="reject({{ $order->id }})"
                                                                class="w-full flex items-center justify-center px-3 py-2 border border-red-600 rounded-md text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                {{ $order->status->name === 'For Approval' || $order->status->name === 'Disapproved' ? 'disabled' : '' }}>
                                                                <i class="fas fa-thumbs-down mr-1"></i>
                                                                <span>Reject</span>
                                                            </button>
                                                        </div>

                                                        <!-- View Details Button -->
                                                        <button onclick="showTravelOrder({{ $order->id }})"
                                                            class="w-full mt-2 flex items-center justify-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            <i class="fas fa-eye mr-2"></i> View Full Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <i class="fas fa-search text-gray-400 text-4xl mb-2"></i>
                                        <p class="text-sm">
                                            @if (request()->has('search') && !empty(request('search')))
                                                No match found for "{{ request('search') }}"
                                            @else
                                                No travel orders found.
                                            @endif
                                        </p>
                                        @if (request()->has('search') && !empty(request('search')))
                                            <button
                                                onclick="document.getElementById('search').value = ''; document.querySelector('form').submit();"
                                                class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center mx-auto">
                                                <i class="fas fa-undo-alt mr-1"></i> Clear search
                                            </button>
                                        @endif
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if ($travelOrders->hasPages())
                            <div class="px-3 py-2 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-700">
                                        Showing {{ $travelOrders->firstItem() }}-{{ $travelOrders->lastItem() }} of
                                        {{ $travelOrders->total() }}
                                    </div>
                                    <div class="flex space-x-1">
                                        @if ($travelOrders->onFirstPage())
                                            <span class="px-2 py-1 text-xs text-gray-400 border rounded">Previous</span>
                                        @else
                                            <a href="{{ $travelOrders->previousPageUrl() }}"
                                                class="px-2 py-1 text-xs text-gray-700 border rounded hover:bg-gray-50">Previous</a>
                                        @endif

                                        @if ($travelOrders->hasMorePages())
                                            <a href="{{ $travelOrders->nextPageUrl() }}"
                                                class="px-2 py-1 text-xs text-gray-700 border rounded hover:bg-gray-50">Next</a>
                                        @else
                                            <span class="px-2 py-1 text-xs text-gray-400 border rounded">Next</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
        </main>

        <footer class="bg-white border-t border-gray-200 mt-4">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                        Resources. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Include Travel Order Modal Component -->
    @include('components.travel-order.travel-order-modal')

@endsection
