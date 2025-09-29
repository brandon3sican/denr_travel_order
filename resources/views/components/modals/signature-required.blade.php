@props(['show' => false])

@if ($show)
    <div id="signatureRequiredModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="px-6 py-4 border-b bg-blue-50">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-signature text-blue-500 text-2xl mr-3"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Signature Required</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-700 mb-4">Before you can proceed, you need to upload your digital signature.
                        This signature will be used to sign your travel orders.</p>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Important:</strong> Your signature must be your official handwritten
                                    signature. Please sign on a white paper and upload a clear image in .PNG format with
                                    transparent background or draw your signature using a digital signature tool.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('signature.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                            <i class="fas fa-upload mr-2"></i> Upload/Draw Signature Now
                        </a>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">You can also upload your signature later from the signature
                            menu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
