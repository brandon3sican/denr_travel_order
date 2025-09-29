@props(['showSignatureAlert' => false])

<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <x-dashboard.header :title="$title ?? 'Dashboard'" />

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-6">
        <!-- Signature Required Alert -->
        @if($showSignatureAlert)
            <x-alerts.signature-required :show="true" />
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Department of Environment and Natural
                    Resources. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>

<!-- Modals -->
@if($showSignatureAlert)
    <x-modals.user-agreement :show="true" />
    <x-modals.signature-required :show="true" />
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js"></script>
@endpush
