@extends('layout.app')

@section('content')
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">Role Management</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" id="searchUsers" placeholder="Search users..." class="pl-8 pr-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Assignment/Division Filter -->
                        <div class="relative">
                            <select id="assignmentFilter" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Assignments</option>
                                @php
                                    $assignments = \App\Models\Employee::select('assignment_name')
                                        ->distinct()
                                        ->whereNotNull('assignment_name')
                                        ->orderBy('assignment_name')
                                        ->pluck('assignment_name');
                                @endphp
                                @foreach($assignments as $assignment)
                                    <option value="{{ $assignment }}">{{ $assignment }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Users Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-white text-xs font-medium uppercase tracking-wider">Employee Name</th>
                                    <th scope="col" class="px-4 py-2 text-left text-white text-xs font-medium uppercase tracking-wider">Position</th>
                                    <th scope="col" class="px-4 py-2 text-left text-white text-xs font-medium uppercase tracking-wider">Assignment</th>
                                    <th scope="col" class="px-4 py-2 text-left text-white text-xs font-medium uppercase tracking-wider">Division/Section/Unit</th>
                                    <th scope="col" class="px-4 py-2 text-left text-white text-xs font-medium uppercase tracking-wider">Current Role</th>
                                    <th scope="col" class="px-4 py-2 text-right text-white text-xs font-medium uppercase tracking-wider">Change Role</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-500">
                                                    <span class="text-xs font-medium leading-none text-white">{{ substr($user->first_name, 0, 1) }}</span>
                                                </span>
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-xs font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $user->employee->position_name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $user->employee->assignment_name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $user->employee->div_sec_unit ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        @if($user->travelOrderRoles->isNotEmpty())
                                            @foreach($user->travelOrderRoles as $role)
                                                @php
                                                    $roleColors = [
                                                        'admin' => 'bg-blue-600 text-white',
                                                        'recommender' => 'bg-yellow-500 text-gray-900',
                                                        'approver' => 'bg-green-600 text-white',
                                                        'user' => 'bg-gray-300 text-black',
                                                    ];
                                                    $roleName = strtolower($role->name);
                                                    $roleClass = $roleColors[$roleName] ?? 'bg-gray-500 text-white';
                                                @endphp
                                                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md shadow-sm {{ $roleClass }} mr-1.5 mb-1.5 border border-gray-200">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="px-1.5 py-0.5 inline-flex text-2xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                No Role
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="relative w-full max-w-[180px] ml-auto">
                                            <form action="{{ route('role-management.update-role', $user->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <div class="relative">
                                                    <select 
                                                        name="role_id" 
                                                        onchange="this.form.submit()"
                                                        onfocus="this.classList.add('bg-blue-50')"
                                                        onblur="this.classList.remove('bg-blue-50')"
                                                        class="appearance-none w-full pl-3 pr-8 py-1.5 text-xs border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white cursor-pointer transition-all duration-150 hover:border-blue-400"
                                                        style="background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e\"); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.5em 1.5em;"
                                                    >
                                                        <option value="" class="text-gray-400">Select Role</option>
                                                        @foreach($roles as $role)
                                                            <option 
                                                                value="{{ $role->id }}" 
                                                                {{ $user->travelOrderRoles->contains('id', $role->id) ? 'selected' : '' }}
                                                                class="py-1"
                                                            >
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
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
                                <span class="relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-300 bg-white">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="ml-2 relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @else
                                <span class="ml-2 relative inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-300 bg-white">
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
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($users->onFirstPage())
                                        <span class="relative inline-flex items-center px-2 py-1.5 rounded-l-md border border-gray-300 bg-white text-xs text-gray-300">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-4 w-4"></i>
                                        </span>
                                    @else
                                        <a href="{{ $users->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-1.5 rounded-l-md border border-gray-300 bg-white text-xs text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-chevron-left h-4 w-4"></i>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                        @if ($page == $users->currentPage())
                                            <span aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-3 py-1.5 border text-xs font-medium">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-3 py-1.5 border text-xs font-medium">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($users->hasMorePages())
                                        <a href="{{ $users->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-1.5 rounded-r-md border border-gray-300 bg-white text-xs text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-chevron-right h-4 w-4"></i>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center px-2 py-1.5 rounded-r-md border border-gray-300 bg-white text-xs text-gray-300">
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
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assignmentFilter = document.getElementById('assignmentFilter');
        const searchInput = document.getElementById('searchUsers');
        let currentUrl = new URL(window.location.href);
        
        // Set initial filter value from URL
        const urlParams = new URLSearchParams(window.location.search);
        const assignmentParam = urlParams.get('assignment');
        if (assignmentParam) {
            assignmentFilter.value = assignmentParam;
        }
        
        // Handle assignment filter change
        assignmentFilter.addEventListener('change', function() {
            const selectedAssignment = this.value;
            const url = new URL(window.location.href);
            
            if (selectedAssignment) {
                url.searchParams.set('assignment', selectedAssignment);
            } else {
                url.searchParams.delete('assignment');
            }
            
            // Reset to first page when changing filters
            url.searchParams.set('page', '1');
            
            window.location.href = url.toString();
        });
        
        // Handle search input with debounce
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.trim();
                const url = new URL(window.location.href);
                
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                
                // Reset to first page when searching
                url.searchParams.set('page', '1');
                
                window.location.href = url.toString();
            }, 500);
        });
        
        // Set initial search value from URL
        const searchParam = urlParams.get('search');
        if (searchParam) {
            searchInput.value = searchParam;
        }
    });
</script>
@endpush

@endsection