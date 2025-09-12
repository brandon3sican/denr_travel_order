@props(['paginator'])

@if ($paginator->hasPages())
    <div class="m-4 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
        <p class="text-sm text-gray-600">
            @if ($paginator->count() > 0)
                Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to
                <span class="font-medium">{{ $paginator->lastItem() }}</span> of
                <span class="font-medium">{{ $paginator->total() }}</span> results
            @else
                No results found
            @endif
        </p>

        <div class="flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span
                        class="px-3 py-1 border rounded-md text-sm font-medium bg-blue-600 text-white border-blue-600">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                        class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-1 border rounded-md text-sm font-medium text-gray-400 bg-white cursor-not-allowed">
                    Next <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif
