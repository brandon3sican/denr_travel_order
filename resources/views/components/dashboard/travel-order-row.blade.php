@props(['order', 'isAdmin' => false])

@php
    $statusName = $order->status->name ?? '';
    $statusClass =
        [
            'For Recommendation' => 'bg-yellow-100 text-yellow-800',
            'For Approval' => 'bg-blue-100 text-blue-800',
            'Approved' => 'bg-green-100 text-green-800',
            'Disapproved' => 'bg-red-100 text-red-800',
            'Cancelled' => 'bg-gray-100 text-gray-800',
            'Completed' => 'bg-purple-100 text-purple-800',
        ][$statusName] ?? 'bg-gray-100 text-gray-800';

    $departure = \Carbon\Carbon::parse($order->departure_date);
    $arrival = \Carbon\Carbon::parse($order->arrival_date);
    $days = $departure->diffInDays($arrival) + 1;
@endphp

<tr class="hover:bg-gray-50">
    <!-- Travel Order Details Column -->
    <td class="px-6 py-4">
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <div class="font-medium text-gray-900">
                    <i class="fas fa-user w-4 text-center text-gray-400 text-xs"></i>
                    {{ $order->employee ? $order->employee->first_name . ' ' . $order->employee->middle_name . ' ' . $order->employee->last_name : 'N/A' }}
                    @if ($order->employee?->position_name)
                        <span class="text-gray-500 text-sm">({{ $order->employee->position_name }})</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <i class="fas fa-briefcase w-4 text-center text-gray-400 text-xs"></i>
                    <span class="font-medium">Purpose:</span> {{ $order->purpose ?? 'N/A' }}
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <i class="fas fa-map-marker-alt w-4 text-center text-gray-400 text-xs"></i>
                    <span class="font-medium">Destination:</span> {{ $order->destination ?? 'N/A' }}
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-sm">
                    <i class="fas fa-calendar w-4 text-center text-gray-400 text-xs"></i>
                    <span class="font-medium">Travel Dates:</span>
                    {{ $departure->format('M d, Y') }} to {{ $arrival->format('M d, Y') }}
                    <span class="text-gray-500">({{ $days }} {{ Str::plural('day', $days) }})</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="text-xs">
                    <i class="fas fa-calendar w-4 text-center text-gray-400 text-xs"></i>
                    <span class="text-gray-500">Created:
                        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                    </span>
                </div>
            </div>
            <div>
                <span class="text-xs text-gray-500"><i class="fas fa-circle w-4 text-center text-gray-400 text-xs"></i>
                    Status:</span>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                    {{ $order->status->name }}
                </span>
            </div>
        </div>
    </td>

    <!-- Action Column -->
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
        <button onclick="showTravelOrder({{ $order->id }})"
            class="text-blue-600 hover:text-blue-900 border border-blue-600 px-2 py-1 rounded text-xs sm:text-sm w-16 sm:w-20">
            <i class="fas fa-eye sm:hidden"></i>
            <span class="hidden sm:inline">View</span>
        </button>
    </td>
</tr>
