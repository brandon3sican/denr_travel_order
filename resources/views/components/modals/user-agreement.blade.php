@props(['show' => false])

@if($show)
<div id="userAgreementModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center z-50 p-4" role="dialog" aria-modal="true" aria-labelledby="uaTitle">
    <div class="bg-white rounded-xl shadow-2xl w-11/12 md:w-5/6 lg:w-4/5 xl:w-3/4 2xl:w-2/3 max-w-none ring-2 ring-red-600/60" style="max-height:90vh;">
        <div class="px-6 py-4 border-b bg-red-600 text-white rounded-t-xl sticky top-0 z-10">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-2xl mr-3"></i>
                </div>
                <div>
                    <h3 id="uaTitle" class="text-xl font-bold">Important Notice: User Agreement Required</h3>
                    <p class="text-xs text-red-100">Please review this carefully before continuing.</p>
                </div>
            </div>
        </div>
        <div class="p-6 flex flex-col" style="max-height:calc(90vh - 72px);">
            <!-- Top Alert Banner -->
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-red-500 mt-0.5 mr-3"></i>
                    <p class="text-sm text-red-800"><span class="font-semibold">This is a mandatory notice.</span> Your e‑signature and audit details are required to proceed and will be used on printable travel orders and throughout the approval process.</p>
                </div>
            </div>
            <!-- Single scrollable content -->
            <div id="uaContent" class="space-y-5 flex-1 overflow-y-auto pr-2">
                <!-- Purpose -->
                <section>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Purpose</h4>
                    <p class="text-gray-700">To continue using this system, you are required to <span class="font-semibold">upload or draw your digital signature</span>. Your signature will be applied to your travel order documents, used in the approval workflow, and will appear on the <span class="font-semibold">printable travel order</span> as the signature of the <span class="font-semibold">Requester</span>, <span class="font-semibold">Recommending</span>, and <span class="font-semibold">Approving</span> personnel as applicable.</p>
                </section>

                <!-- Data Collected -->
                <section class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <h5 class="text-sm font-semibold text-yellow-800 mb-1">Data Collected</h5>
                    <ul class="list-disc list-inside text-sm text-yellow-800 space-y-1">
                        <li><span class="font-semibold">Signature Image</span>: Your official handwritten signature (clear .PNG with transparent background recommended) or a drawn signature using the provided tool.</li>
                        <li><span class="font-semibold">Audit Metadata</span>: Timestamp of upload, device/browser information, and signature-related actions.</li>
                    </ul>
                </section>

                <!-- Use and Approval -->
                <section class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <h5 class="text-sm font-semibold text-blue-800 mb-1">Use and Approval</h5>
                    <p class="text-sm text-blue-800">Your signature and audit metadata will be used <span class="font-semibold">only within this system</span> to: (a) display your signature on <span class="font-semibold">generated and printable travel order documents</span> (including fields for <span class="font-semibold">Requester</span>, <span class="font-semibold">Recommending</span>, and <span class="font-semibold">Approving</span> personnel), (b) support the <span class="font-semibold">approval process</span> by verifying actions taken, and (c) maintain audit trails for integrity and compliance with records management policies.</p>
                </section>

                <!-- Retention and Consent -->
                <section>
                    <h5 class="text-sm font-semibold text-gray-900 mb-1">Retention and Consent</h5>
                    <p class="text-sm text-gray-700">Data is retained in accordance with internal policies and applicable regulations. By proceeding, you affirm that the signature you provide is your official signature and you consent to its use, together with audit metadata, as described above.</p>
                </section>

                <section>
                    <div class="flex items-start">
                        <input id="uaAgreeCheckbox" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1" />
                        <label for="uaAgreeCheckbox" class="ml-2 text-sm text-gray-700">I have read and understand this User Agreement, and I consent to the collection and use of my signature and audit metadata within this system for travel order signing, audit trails, and the approval process.</label>
                    </div>
                </section>
            </div>
            <p id="uaScrollHint" class="mt-3 text-xs text-red-700 font-medium flex items-center"><i class="fas fa-arrow-down mr-2"></i>Scroll to the bottom and check the acknowledgment to enable "I Agree and Proceed".</p>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-end sticky bottom-0 bg-white pt-4 border-t">
                <div class="space-x-2">
                    <button id="uaCancelBtn" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded">
                        Cancel
                    </button>
                    <a id="uaProceedBtn" href="{{ route('signature.index') }}" class="px-4 py-2 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded shadow disabled:opacity-50 opacity-50 pointer-events-none">
                        I Understand, Agree, and Proceed
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden logout form to force logout on cancel -->
<form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
    @csrf
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const uaModal = document.getElementById('userAgreementModal');
    if (!uaModal) return;

    // Elements
    const content = document.getElementById('uaContent');
    const cancelBtn = document.getElementById('uaCancelBtn');
    const proceedBtn = document.getElementById('uaProceedBtn');
    const agreeCbx = document.getElementById('uaAgreeCheckbox');
    const scrollHint = document.getElementById('uaScrollHint');

    // Gate proceed on scroll-to-bottom and checkbox
    let scrolledToBottom = false;
    
    function updateProceedState() {
        const canProceed = scrolledToBottom && agreeCbx?.checked;
        if (canProceed) {
            proceedBtn.classList.remove('opacity-50', 'pointer-events-none');
            if (scrollHint) scrollHint.textContent = 'Acknowledgment checked. You can proceed.';
        } else {
            proceedBtn.classList.add('opacity-50', 'pointer-events-none');
            if (scrollHint) scrollHint.textContent = 'Scroll to the bottom and check the acknowledgment to enable "I Agree and Proceed".';
        }
    }

    function computeInitialScrollState() {
        if (!content) {
            scrolledToBottom = true; // no content container, allow
            return;
        }
        // If content does not overflow, treat as already at bottom
        const overflows = content.scrollHeight > content.clientHeight + 1;
        if (!overflows) {
            scrolledToBottom = true;
        } else {
            // If already at bottom on load
            const atBottom = content.scrollTop + content.clientHeight >= content.scrollHeight - 10;
            scrolledToBottom = atBottom;
        }
    }

    content?.addEventListener('scroll', function () {
        const nearBottom = content.scrollTop + content.clientHeight >= content.scrollHeight - 10; // 10px threshold
        if (nearBottom) {
            scrolledToBottom = true;
            updateProceedState();
        }
    });

    // Recompute on resize (content size may change)
    window.addEventListener('resize', function () {
        computeInitialScrollState();
        updateProceedState();
    });

    cancelBtn?.addEventListener('click', function () {
        // Force logout
        const form = document.getElementById('logoutForm');
        if (form) form.submit();
    });

    agreeCbx?.addEventListener('change', function () {
        updateProceedState();
    });

    // Initialize state
    computeInitialScrollState();
    updateProceedState();
});
</script>
@endpush
@endif
