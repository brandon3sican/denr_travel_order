@props(['order', 'counter'])

@php
    // Define status classes for different statuses
    $statusConfig = [
        1 => [
            'bg' => 'bg-yellow-200',
            'text' => 'text-yellow-800',
            'border' => 'border-yellow-100',
            'badge' => 'bg-yellow-100 text-yellow-800',
            'name' => 'For Recommendation',
        ],
        2 => [
            'bg' => 'bg-blue-200',
            'text' => 'text-blue-800',
            'border' => 'border-blue-100',
            'badge' => 'bg-blue-100 text-blue-800',
            'name' => 'For Approval',
        ],
        3 => [
            'bg' => 'bg-green-200',
            'text' => 'text-green-800',
            'border' => 'border-green-100',
            'badge' => 'bg-green-100 text-green-800',
            'name' => 'Approved',
        ],
        4 => [
            'bg' => 'bg-red-200',
            'text' => 'text-red-800',
            'border' => 'border-red-100',
            'badge' => 'bg-red-100 text-red-800',
            'name' => 'Disapproved',
        ],
        5 => [
            'bg' => 'bg-gray-200',
            'text' => 'text-gray-800',
            'border' => 'border-gray-100',
            'badge' => 'bg-gray-100 text-gray-800',
            'name' => 'Cancelled',
        ],
        6 => [
            'bg' => 'bg-purple-200',
            'text' => 'text-purple-800',
            'border' => 'border-purple-100',
            'badge' => 'bg-purple-100 text-purple-800',
            'name' => 'Completed',
        ],
    ];

    // Get status configuration
    $status = $statusConfig[$order->status_id] ?? $statusConfig[5]; // Default to gray if status not found
    $statusName = $status['name'];
    $statusSlug = str_replace(' ', '-', strtolower($statusName));
@endphp

<div class="{{ $status['bg'] }} rounded-lg shadow-md overflow-hidden border {{ $status['border'] }} hover:shadow-lg transition-shadow duration-300 relative pt-8 pl-5 pr-8"
    data-status="{{ $statusSlug }}">

    <!-- Order counter badge -->
    <div
        class="absolute top-0 left-0 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-br-lg h-8 w-8 flex items-center justify-center text-sm font-bold shadow-lg">
        <span class="drop-shadow-sm">{{ $counter }}</span>
    </div>

    <div class="p-5">
        <!-- Header with creation date and status -->
        <div class="flex justify-between items-start mb-4">
            <div>
                <span class="text-xs text-gray-500">Created on</span>
                <p class="text-sm font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                </p>
            </div>
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $status['badge'] }}">
                {{ $statusName }}
            </span>
        </div>

        <!-- Destination and purpose -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $order->destination }}</h3>
            <p class="text-sm text-gray-600 line-clamp-2">{{ $order->purpose }}</p>
        </div>

        <!-- Travel dates -->
        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <span class="text-gray-500 block">Departure</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-gray-500 block">Return</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($order->return_date)->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Latest status update -->
        @if ($order->latestStatusUpdate)
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-medium text-gray-500">Latest Update</span>
                </div>
                <div class="flex items-start text-sm text-gray-700 mt-1">
                    <div class="flex-shrink-0 mt-0.5 mr-2">
                        <i class="fas {{ $status['icon'] ?? 'fa-info-circle' }} text-{{ $status['text'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800">Status updated to: <span
                                class="font-medium">{{ $status['name'] }}</span></p>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                            <div class="flex items-center bg-gray-50 px-2 py-1 rounded">
                                <i class="fas fa-user-edit mr-1 text-gray-400"></i>
                                <span>{{ $order->latestStatusUpdate->user->employee->first_name . ' ' . $order->latestStatusUpdate->user->employee->middle_name . ' ' . $order->latestStatusUpdate->user->employee->last_name . ' ' . $order->latestStatusUpdate->user->employee->suffix ?? 'System' }}</span>
                            </div>
                            <div class="flex items-center bg-gray-50 px-2 py-1 rounded">
                                <i class="far fa-clock mr-1 text-gray-400"></i>
                                <span>{{ \Carbon\Carbon::parse($order->latestStatusUpdate->created_at)->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="text-xs text-gray-400 italic">
                                {{ \Carbon\Carbon::parse($order->latestStatusUpdate->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
            <!-- View button -->
            <button onclick="showTravelOrder({{ $order->id }})"
                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="far fa-eye mr-2"></i> View
            </button>

            <!-- Complete button (only for approved orders) -->
            @if ($order->status_id == 3)
                <button onclick="openCompleteModal({{ $order->id }})"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check-circle mr-2"></i> Complete
                </button>
                <form action="{{ route('travel-orders.cancel', $order) }}" method="POST" class="flex-1">
                    @csrf
                    @method('POST')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to cancel this travel order?')"
                        class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-times-circle mr-2"></i> Cancel
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
