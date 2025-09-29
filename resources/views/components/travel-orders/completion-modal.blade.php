@props(['order'])

<div id="completeModal{{ $order->id }}"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-file-signature text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-3">Complete Travel Order</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Please upload the signed documents for this travel order. The following documents are
                    required:
                </p>
                <ul class="text-sm text-left text-gray-600 mb-6 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Signed Certification of Travel Completed (PDF)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Signed Itinerary of Travel (PDF)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Signed Certificate of Appearance (PDF)</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span>Signed Travel Report (PDF)</span>
                    </li>
                </ul>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="signedDocuments">
                        Upload Signed Documents (PDF only)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload-{{ $order->id }}"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload files</span>
                                    <input id="file-upload-{{ $order->id }}" name="file-upload-{{ $order->id }}" type="file"
                                        class="sr-only" multiple accept=".pdf">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF files only, up to 10MB each
                            </p>
                        </div>
                    </div>
                    <p id="file-names-{{ $order->id }}" class="mt-1 text-sm text-gray-500">No files selected</p>
                </div>
            </div>
            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                    onclick="submitCompletion({{ $order->id }})">
                    Submit
                </button>
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    onclick="closeCompleteModal({{ $order->id }})">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
