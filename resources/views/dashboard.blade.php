@extends('layout.app')

@php
    // Define status options for the filter
    $statusOptions = [
        'For Recommendation' => 'For Recommendation',
        'For Approval' => 'For Approval',
        'Approved' => 'Approved',
        'Disapproved' => 'Disapproved',
        'Cancelled' => 'Cancelled',
        'Completed' => 'Completed',
    ];
@endphp

@section('content')
    <x-dashboard.layout :showSignatureAlert="isset($showSignatureAlert) && $showSignatureAlert">
        <!-- Dashboard Content -->
        <div id="dashboardContent" class="page-content space-y-6">
            <!-- Overview Header -->
            <div class="border-b border-gray-200 pb-5">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Overview</h3>
                <p class="mt-2 max-w-4xl text-sm text-gray-500">Quickly view and manage your travel orders and requests.</p>
            </div>
            
            <!-- Stats Cards -->
            <x-dashboard.stats-cards 
                :totalTravelOrders="$totalTravelOrders" 
                :pendingRequests="$pendingRequests" 
                :completedRequests="$completedRequests" 
                :cancelledRequests="$cancelledRequests" 
            />

            <!-- Recent Travel Orders -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-white rounded-t-lg">
                    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="sm:mb-0">
                            <h3 class="text-base sm:text-lg md:text-lg font-bold text-gray-800">
                                Recent Travel Orders
                            </h3>
                            <p class="hidden md:block text-xs sm:text-sm text-gray-600 mt-0.5">
                                Track and manage all travel order requests
                            </p>
                        </div>

                        <div class="w-full sm:w-auto">
                            <x-dashboard.search-filter 
                                :statuses="$statusOptions" 
                                :currentStatus="request('status')" 
                                :searchQuery="request('search')" 
                            />
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto -mx-2 sm:mx-0 shadow-sm">
                    <div class="inline-block min-w-full align-middle">
                        <div class="bg-white border border-gray-200 overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <x-dashboard.table-header :isAdmin="auth()->user()->is_admin" />
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($travelOrders as $order)
                                        <tr class="relative group hover:bg-gray-50 transition-colors duration-150 ease-in-out border-b border-gray-100 last:border-0">
                                            <x-dashboard.travel-order-row 
                                                :order="$order" 
                                                :isAdmin="auth()->user()->is_admin" 
                                            />
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->is_admin ? '8' : '7' }}" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-4">
                                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" 
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                        </path>
                                                    </svg>
                                                    <div class="space-y-1">
                                                        <p class="text-base font-medium text-gray-700">
                                                            No travel orders found
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            Get started by creating a new travel order
                                                        </p>
                                                    </div>
                                                    @if (auth()->user()->can('create', App\Models\TravelOrder::class))
                                                        <a href="{{ route('travel-orders.create') }}"
                                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                            New Travel Order
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <x-dashboard.pagination :paginator="$travelOrders" />
            </div>
        </div>

        <!-- Other pages will be loaded here -->
        <div id="otherPages" class="page-content hidden"></div>

        <!-- Include Travel Order Modal Component -->
        @include('components.travel-order.travel-order-modal')
    </x-dashboard.layout>
@endsection
