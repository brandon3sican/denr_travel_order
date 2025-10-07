@extends('layout.app')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Signature Management</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span
                            class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-3 border-b bg-gray-50">
                    <h3 class="text-base font-semibold text-gray-800">Signature Management</h3>
                    <p class="text-xs text-gray-500">Search a user and reset their signature.</p>
                    <!-- Filters -->
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" id="searchUsers" placeholder="Search by name, email, or position..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150">
                            </div>

                            <div class="relative w-full sm:w-64">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                                </div>
                                <select id="assignmentFilter"
                                    class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150">
                                    <option value="">All Assignments</option>
                                    @foreach ($assignments as $assignment)
                                        <option value="{{ $assignment }}">{{ $assignment }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase w-2/3">Employee</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-start">
                                            @php
                                                $firstLetter = strtoupper(substr($row->first_name ?? 'U', 0, 1));
                                                $colors = [
                                                    'bg-blue-500',
                                                    'bg-green-500',
                                                    'bg-purple-500',
                                                    'bg-pink-500',
                                                    'bg-indigo-500',
                                                ];
                                                $bgColor = $colors[ord($firstLetter) % count($colors)];
                                            @endphp
                                            <div
                                                class="h-10 w-10 rounded-full {{ $bgColor }} flex-shrink-0 flex items-center justify-center text-white font-bold">
                                                {{ $firstLetter }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium text-gray-900">
                                                    {{ $row->first_name }}{{ $row->middle_name ? ' ' . $row->middle_name : '' }}
                                                    {{ $row->last_name }}{{ $row->suffix ? ' ' . $row->suffix : '' }}
                                                </div>
                                                <div class="text-gray-500 text-xs mb-1">{{ $row->user_email }}</div>
                                                <div class="space-y-0.5">
                                                    <div class="text-gray-700"><span class="font-medium">Position:</span>
                                                        {{ $row->position_name ?? 'N/A' }}</div>
                                                    <div class="text-gray-700"><span class="font-medium">Assignment:</span>
                                                        {{ $row->assignment_name ?? 'N/A' }}</div>
                                                    @if ($row->signature_id)
                                                        <div class="text-green-700 text-xs">Has signature • Updated
                                                            {{ optional($row->signature_updated_at)->format('M d, Y h:i A') }}
                                                        </div>
                                                    @else
                                                        <div class="text-yellow-700 text-xs">No signature on file</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none"
                                            data-employee-id="{{ $row->employee_id }}"
                                            data-employee-name="{{ $row->first_name }} {{ $row->last_name }}"
                                            data-employee-email="{{ $row->user_email }}" onclick="openResetModal(this)">
                                            <i class="fas fa-undo mr-1"></i>
                                            Reset Signature
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No employees
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="bg-white px-3 py-2 flex items-center justify-between border-t border-gray-200">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs text-gray-600">
                                    Showing <span class="font-medium">{{ $users->firstItem() }}</span>
                                    to <span class="font-medium">{{ $users->lastItem() }}</span>
                                    of <span class="font-medium">{{ $users->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
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

    <!-- Reset Modal -->
    <div id="resetSigModal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 border-b bg-red-600 text-white rounded-t-xl">
                <h3 class="text-lg font-semibold">Reset Signature</h3>
                <p class="text-xs text-red-100">Please confirm the correct employee before proceeding.</p>
            </div>
            <form id="resetSigForm" method="POST">
                @csrf
                <div class="p-6 space-y-3">
                    <div class="text-sm text-gray-700">
                        <div class="font-medium">Employee</div>
                        <div id="resetEmpName" class="text-gray-900"></div>
                        <div id="resetEmpEmail" class="text-gray-600 text-xs"></div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm text-gray-700">Type the employee's email to confirm</label>
                        <input type="email" name="confirm_email" id="confirmEmailInput"
                            class="w-full border rounded px-3 py-2 text-sm" required placeholder="employee@example.com">
                    </div>
                    <label class="flex items-start space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="ack_reset" id="ackReset"
                            class="h-4 w-4 text-red-600 border-gray-300 rounded" required>
                        <span>I understand this will remove the existing signature and the user will need to provide a new
                            one.</span>
                    </label>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded"
                            onclick="closeResetModal()">Cancel</button>
                        <button type="submit" id="confirmResetBtn"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded">Confirm
                            Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('signature-management.partials.scripts')
@endsection
