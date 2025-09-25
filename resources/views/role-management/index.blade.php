@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Role Management</h2>
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

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-3">
                    <h3 class="text-base font-semibold text-gray-800">User Role Update</h3>
                    <p class="text-xs text-gray-500">Search a user and update their role.</p>
                </div>
                <!-- Search and Filter Section -->
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="relative flex-1 max-w-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchUsers" placeholder="Search by name, email, or position..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150">
                        </div>

                        <!-- Assignment Filter -->
                        <div class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                            </div>
                            <select id="assignmentFilter"
                                class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150">
                                <option value="">All Assignments</option>
                                @php
                                    $assignments = \App\Models\Employee::select('assignment_name')
                                        ->distinct()
                                        ->whereNotNull('assignment_name')
                                        ->orderBy('assignment_name')
                                        ->pluck('assignment_name');
                                @endphp
                                @foreach ($assignments as $assignment)
                                    <option value="{{ $assignment }}">{{ $assignment }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Employee Details
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase w-1/3">Role
                                    Management</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-start">
                                            @php
                                                $firstLetter = strtoupper(substr($user->first_name, 0, 1));
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
                                                    {{ $user->first_name }}{{ $user->middle_name ? ' ' . $user->middle_name : '' }}
                                                    {{ $user->last_name }}{{ $user->suffix ? ' ' . $user->suffix : '' }}
                                                </div>
                                                <div class="text-gray-500 text-xs mb-1">{{ $user->email }}</div>
                                                <div class="space-y-0.5">
                                                    <div class="text-gray-700"><span class="font-medium">Position:</span>
                                                        {{ $user->employee->position_name ?? 'N/A' }}</div>
                                                    <div class="text-gray-700"><span class="font-medium">Assignment:</span>
                                                        {{ $user->employee->assignment_name ?? 'N/A' }}</div>
                                                    <div class="text-gray-700"><span class="font-medium">Division:</span>
                                                        {{ $user->employee->division_name ?? 'N/A' }}</div>
                                                </div>
                                                @if ($user->travelOrderRoles->isNotEmpty())
                                                    <div class="mt-1.5 flex flex-wrap gap-1">
                                                        @foreach ($user->travelOrderRoles as $role)
                                                            @php
                                                                $roleColors = [
                                                                    'admin' => 'bg-blue-600 text-white',
                                                                    'recommender' => 'bg-yellow-500 text-gray-900',
                                                                    'approver' => 'bg-green-600 text-white',
                                                                    'user' => 'bg-gray-300 text-black',
                                                                ];
                                                                $roleClass =
                                                                    $roleColors[strtolower($role->name)] ??
                                                                    'bg-gray-500 text-white';
                                                            @endphp
                                                            <span
                                                                class="px-1.5 py-0.5 text-xs rounded {{ $roleClass }}">{{ $role->name }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-800 mt-1.5">No
                                                        Role Assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('role-management.update-role', $user) }}" method="POST"
                                            class="space-y-2">
                                            @csrf
                                            <div class="relative">
                                                <select name="role_id" onchange="this.form.submit()"
                                                    class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer transition duration-150">
                                                    <option value="" class="text-gray-400">Change Role</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ $user->travelOrderRoles->contains('id', $role->id) ? 'selected' : '' }}
                                                            class="py-2 px-3 hover:bg-blue-50">
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                        @if ($user->travelOrderRoles->isNotEmpty())
                                            <div class="mt-2">
                                                <div class="text-xs font-medium text-gray-500 mb-1">Current Role</div>
                                                <div class="flex flex-wrap gap-1.5">
                                                    @foreach ($user->travelOrderRoles as $role)
                                                        @php
                                                            $roleColors = [
                                                                'admin' =>
                                                                    'bg-blue-100 text-blue-800 border border-blue-200',
                                                                'recommender' =>
                                                                    'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                                                'approver' =>
                                                                    'bg-green-100 text-green-800 border border-green-200',
                                                                'user' =>
                                                                    'bg-gray-100 text-gray-800 border border-gray-200',
                                                            ];
                                                            $roleClass =
                                                                $roleColors[strtolower($role->name)] ??
                                                                'bg-gray-100 text-gray-800 border border-gray-200';
                                                        @endphp
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleClass }}">
                                                            <i class="fas fa-user-tag text-xs mr-1.5"></i>
                                                            {{ ucfirst($role->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="bg-white px-3 py-2 flex items-center justify-between border-t border-gray-200">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($users->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-300 bg-white">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}"
                                    class="ml-2 relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span
                                    class="ml-2 relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-300 bg-white">
                                    Next
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs text-gray-600">
                                    Showing <span class="font-medium">{{ $users->firstItem() }}</span>
                                    to <span class="font-medium">{{ $users->lastItem() }}</span>
                                    of <span class="font-medium">{{ $users->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <span
                                            class="relative inline-flex items-center px-2 py-1.5 rounded-l-md border border-gray-300 bg-white text-xs text-gray-300">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-4 w-4"></i>
                                        </span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}"
                                            class="relative inline-flex items-center px-2 py-1.5 rounded-l-md border border-gray-300 bg-white text-xs text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-4 w-4"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        @if ($page == $users->currentPage())
                                            <span aria-current="page"
                                                class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-3 py-1.5 border text-xs font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-3 py-1.5 border text-xs font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}"
                                            class="relative inline-flex items-center px-2 py-1.5 rounded-r-md border border-gray-300 bg-white text-xs text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-4 w-4"></i>
                                        </a>
                                    @else
                                        <span
                                            class="relative inline-flex items-center px-2 py-1.5 rounded-r-md border border-gray-300 bg-white text-xs text-gray-300">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-4 w-4"></i>
                                        </span>
                                    @endif
                                </nav>
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

    @include('role-management.partials.scripts')

@endsection
