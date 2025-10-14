@props(['title' => 'Dashboard'])

<header class="bg-white shadow-sm z-10">
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center">
            <button id="sidebarToggle" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 id="pageTitle" class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
        </div>
    </div>
</header>
