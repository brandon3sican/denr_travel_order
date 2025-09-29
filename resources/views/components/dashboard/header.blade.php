@props(['title' => 'Dashboard'])

<header class="bg-white shadow-sm z-10">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center">
            <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <button class="relative p-2 text-gray-600 hover:text-gray-900">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-0 right-0 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>
        </div>
    </div>
</header>
