<div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            @php
                $user = Auth::user();
                $employee = $user->employee;
                $userInitial = $employee && $employee->first_name 
                    ? strtoupper(substr($employee->first_name, 0, 1))
                    : ($user->first_name ? strtoupper(substr($user->first_name, 0, 1)) : 'U');
            @endphp
            <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">{{ $userInitial }}</div>
            <div>
                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-500 hover:text-white focus:outline-none">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</div>