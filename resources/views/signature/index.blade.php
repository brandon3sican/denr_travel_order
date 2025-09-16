@extends('layout.app')

@section('content')
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm z-10">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Digital Signature</h2>
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
        <main class="flex-1 overflow-y-auto p-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="p-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                            </path>
                        </svg>
                        Create Digital Signature
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Draw Signature Card -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Draw Signature</h3>
                            </div>

                            <p class="text-sm text-gray-500 mb-4">Draw your signature using your mouse or touch screen</p>

                            <div class="border-2 border-dashed border-gray-300 rounded-lg overflow-hidden mb-4 bg-white">
                                <div id="signature-pad" class="w-full h-48">
                                    <canvas id="signature-canvas" class="w-full h-full"></canvas>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <button type="button" id="clear"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Clear
                                </button>
                                <button type="button" id="save"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Save Signature
                                </button>
                            </div>
                        </div>

                        <!-- Upload Signature Card -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center mb-4">
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Upload Signature</h3>
                            </div>

                            <p class="text-sm text-gray-500 mb-4">Upload a PNG image of your signature</p>

                            <div id="upload-container"
                                class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg bg-white">
                                <div class="space-y-1 text-center">
                                    <!-- Upload Prompt -->
                                    <div id="upload-prompt">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4 flex text-sm text-gray-600">
                                            <label for="signature-upload"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input id="signature-upload" name="signature-upload" type="file"
                                                    class="sr-only" accept=".png,image/png">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            PNG (Max. 5MB)
                                        </p>
                                    </div>

                                    <!-- Upload Preview -->
                                    <div id="upload-preview" class="hidden">
                                        <div class="flex flex-col items-center">
                                            <img id="signature-preview" src="#" alt="Signature preview"
                                                class="max-h-48 max-w-full rounded">
                                            <button type="button" id="change-upload"
                                                class="mt-3 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Change file
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (isset($signature) && $signature)
                        <div class="bg-white rounded-lg shadow overflow-hidden text-center mt-4 border-2">
                            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Your Current Signature
                                </h3>
                            </div>
                            <div class="px-6 py-5">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="flex-1">
                                        <div class="inline-block bg-white p-4 border border-gray-500 rounded-lg shadow-sm">
                                            <img src="{{ $signature->signature_data }}" alt="Your Signature"
                                                class="h-20" />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Last updated: {{ $signature->updated_at->format('M d, Y \a\t h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


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

    @include('signature.partials.styles')
    @include('signature.partials.scripts')
@endsection
