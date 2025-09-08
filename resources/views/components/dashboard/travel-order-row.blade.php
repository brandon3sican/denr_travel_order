@props(['order', 'isAdmin' => false])

<tr>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
    </td>
    
    @if($isAdmin)
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $order->employee ? $order->employee->first_name . ' ' . $order->employee->last_name : 'N/A' }}
        </td>
    @endif
    
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        {{ $order->destination ?? 'N/A' }}
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $order->purpose ?? 'N/A' }}
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
        {{ \Carbon\Carbon::parse($order->departure_date)->format('M d, Y') }}
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
        {{ \Carbon\Carbon::parse($order->arrival_date)->format('M d, Y') }}
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap text-center">
        @php
            $statusName = $order->status->name ?? '';
            $statusClass = [
                'For Recommendation' => 'bg-yellow-100 text-yellow-800',
                'For Approval' => 'bg-blue-100 text-blue-800',
                'Approved' => 'bg-green-100 text-green-800',
                'Disapproved' => 'bg-red-100 text-red-800',
                'Cancelled' => 'bg-gray-100 text-gray-800',
                'Completed' => 'bg-purple-100 text-purple-800',
            ][$statusName] ?? 'bg-gray-100 text-gray-800';
        @endphp
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
            {{ $order->status->name ?? 'Unknown' }}
        </span>
    </td>
    
    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
        <button onclick="showTravelOrder({{ $order->id }})"
            class="text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-2 py-1 rounded mr-3 w-20">
            View
        </button>
    </td>
</tr>
