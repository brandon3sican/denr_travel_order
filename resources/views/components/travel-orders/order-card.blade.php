@props(['order', 'counter'])

@php
    // Define status classes for different statuses
    $statusConfig = [
        1 => [
            'bg' => 'bg-yellow-50',
            'text' => 'text-yellow-800',
            'border' => 'border-yellow-100',
            'badge' => 'bg-yellow-100 text-yellow-800',
            'name' => 'For Recommendation'
        ],
        2 => [
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-800',
            'border' => 'border-blue-100',
            'badge' => 'bg-blue-100 text-blue-800',
            'name' => 'For Approval'
        ],
        3 => [
            'bg' => 'bg-green-50',
            'text' => 'text-green-800',
            'border' => 'border-green-100',
            'badge' => 'bg-green-100 text-green-800',
            'name' => 'Approved'
        ],
        4 => [
            'bg' => 'bg-red-50',
            'text' => 'text-red-800',
            'border' => 'border-red-100',
            'badge' => 'bg-red-100 text-red-800',
            'name' => 'Disapproved'
        ],
        5 => [
            'bg' => 'bg-gray-50',
            'text' => 'text-gray-800',
            'border' => 'border-gray-100',
            'badge' => 'bg-gray-100 text-gray-800',
            'name' => 'Cancelled'
        ],
        6 => [
            'bg' => 'bg-purple-50',
            'text' => 'text-purple-800',
            'border' => 'border-purple-100',
            'badge' => 'bg-purple-100 text-purple-800',
            'name' => 'Completed'
        ]
    ];

    // Get status configuration
    $status = $statusConfig[$order->status_id] ?? $statusConfig[5]; // Default to gray if status not found
    $statusName = $status['name'];
    $statusSlug = str_replace(' ', '-', strtolower($statusName));
@endphp

<div class="{{ $status['bg'] }} rounded-lg shadow-md overflow-hidden border {{ $status['border'] }} hover:shadow-lg transition-shadow duration-300 relative pt-8 pl-5 pr-8"
    data-status="{{ $statusSlug }}">
    
    <!-- Order counter badge -->
    <div class="absolute top-0 left-0 bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-br-lg h-8 w-8 flex items-center justify-center text-sm font-bold shadow-lg">
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
                <div class="text-xs text-gray-500 mb-1">Latest Update</div>
                <div class="flex items-center text-xs text-gray-700">
                    <div class="flex-shrink-0 mr-2">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        {{ $order->latestStatusUpdate->comments }}
                        <div class="text-xs text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($order->latestStatusUpdate->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
            <!-- View button -->
            <a href="{{ route('travel-orders.show', $order) }}"
                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="far fa-eye mr-2"></i> View
            </a>

            <!-- Complete button (only for approved orders) -->
            @if($order->status_id == 3)
                <button onclick="openCompleteModal({{ $order->id }})"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check-circle mr-2"></i> Complete
                </button>
            @endif

            <!-- Cancel button (only for pending and for approval) -->
            @if(in_array($order->status_id, [1, 2]))
                <a href="{{ route('travel-orders.cancel', $order) }}"
                    onclick="return confirm('Are you sure you want to cancel this travel order?')"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times-circle mr-2"></i> Cancel
                </a>
            @endif

            <!-- More actions dropdown -->
            <div class="relative inline-block text-left">
                <button type="button"
                    onclick="toggleDropdown('more-actions-{{ $order->id }}')"
                    class="inline-flex justify-center items-center p-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    id="menu-button-{{ $order->id }}" aria-expanded="true" aria-haspopup="true">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

                <div id="more-actions-{{ $order->id }}"
                    class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $order->id }}" tabindex="-1">
                    <div class="py-1" role="none">
                        <!-- Edit action -->
                        <a href="{{ route('travel-orders.edit', $order) }}"
                            class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                            role="menuitem" tabindex="-1" id="menu-item-0">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        
                        <!-- Delete action -->
                        <button type="button"
                            onclick="confirmDelete({{ $order->id }})"
                            class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100"
                            role="menuitem" tabindex="-1" id="menu-item-1">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
            </div>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
            <!-- View button -->
            <a href="{{ route('travel-orders.show', $order) }}"
                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-indigo-600 text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="far fa-eye mr-2"></i> View
            </a>

            <!-- Complete button (only for approved orders) -->
            @if($order->status_id == 3)
                <button onclick="openCompleteModal({{ $order->id }})"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-green-600 text-sm font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check-circle mr-2"></i> Complete
                </button>
            @endif

            <!-- Cancel button (only for pending and for approval) -->
            @if(in_array($order->status_id, [1, 2]))
                <a href="{{ route('travel-orders.cancel', $order) }}"
                    onclick="return confirm('Are you sure you want to cancel this travel order?')"
                    class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times-circle mr-2"></i> Cancel
                </a>
            @endif

            <!-- More actions dropdown -->
            <div class="relative inline-block text-left">
                <button type="button"
                    onclick="toggleDropdown('more-actions-{{ $order->id }}')"
                    class="inline-flex justify-center items-center p-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    id="menu-button-{{ $order->id }}" aria-expanded="true" aria-haspopup="true">
                    <i class="fas fa-ellipsis-v"></i>
                </button>

                <div id="more-actions-{{ $order->id }}"
                    class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $order->id }}" tabindex="-1">
                    <div class="py-1" role="none">
                        <!-- Edit action -->
                        <a href="{{ route('travel-orders.edit', $order) }}"
                            class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                            role="menuitem" tabindex="-1" id="menu-item-0">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        
                        <!-- Delete action -->
                        <button type="button"
                            onclick="confirmDelete({{ $order->id }})"
                            class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100"
                            role="menuitem" tabindex="-1" id="menu-item-1">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
