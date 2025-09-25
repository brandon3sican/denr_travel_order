@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1 class="text-2xl font-bold">Travel Order Details</h1>
            <p class="text-sm opacity-75">ID: {{ $travelOrder->id }}</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Basic Information</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Purpose:</span> {{ $travelOrder->purpose }}</p>
                        <p><span class="font-medium">Destination:</span> {{ $travelOrder->destination }}</p>
                        <p><span class="font-medium">Start Date:</span> {{ \Carbon\Carbon::parse($travelOrder->start_date)->format('M d, Y') }}</p>
                        <p><span class="font-medium">End Date:</span> {{ \Carbon\Carbon::parse($travelOrder->end_date)->format('M d, Y') }}</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $travelOrder->status->name === 'Approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $travelOrder->status->name === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $travelOrder->status->name === 'Rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $travelOrder->status->name === 'Completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ $travelOrder->status->name }}
                            </span>
                        </p>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Personnel</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Employee:</span> {{ $travelOrder->employee->first_name }} {{ $travelOrder->employee->last_name }}</p>
                        @if($travelOrder->recommender)
                            <p><span class="font-medium">Recommender:</span> {{ $travelOrder->recommender_employee->first_name ?? '' }} {{ $travelOrder->recommender_employee->last_name ?? $travelOrder->recommender }}</p>
                        @endif
                        @if($travelOrder->approver)
                            <p><span class="font-medium">Approver:</span> {{ $travelOrder->approver_employee->first_name ?? '' }} {{ $travelOrder->approver_employee->last_name ?? $travelOrder->approver }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($travelOrder->remarks)
                <div class="mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Remarks</h2>
                    <p class="text-gray-700">{{ $travelOrder->remarks }}</p>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
                
                @can('update', $travelOrder)
                    <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Status History -->
    @if($travelOrder->statusHistories->isNotEmpty())
        <div class="mt-8 max-w-4xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Status History</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($travelOrder->statusHistories as $history)
                        <li class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas {{ $history->action === 'approve' ? 'fa-check text-green-500' : ($history->action === 'reject' ? 'fa-times text-red-500' : 'fa-edit text-blue-500') }}"></i>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $history->user->name ?? 'System' }}
                                        <span class="text-sm text-gray-500">
                                            {{ $history->created_at->diffForHumans() }}
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        @if($history->action === 'update_approvers')
                                            @php $metadata = json_decode($history->metadata, true) @endphp
                                            @if(isset($metadata['new_recommender']) || isset($metadata['new_approver']))
                                                Updated approvers:
                                                @if(isset($metadata['new_recommender']))
                                                    <span class="font-medium">Recommender</span> to {{ $metadata['new_recommender'] }}
                                                @endif
                                                @if(isset($metadata['new_approver']))
                                                    <span class="font-medium">Approver</span> to {{ $metadata['new_approver'] }}
                                                @endif
                                            @endif
                                        @else
                                            {{ ucfirst($history->action) }}
                                            @if($history->from_status !== $history->to_status)
                                                from <span class="font-medium">{{ $history->from_status }}</span> to <span class="font-medium">{{ $history->to_status }}</span>
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
