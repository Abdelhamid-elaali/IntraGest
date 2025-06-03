@extends('layouts.app')

@section('title', 'Candidate Documents')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Documents for {{ $candidate->first_name }} {{ $candidate->last_name }}</h2>
                    <a href="{{ route('candidates.show', $candidate) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Candidate
                    </a>
                </div>

                @if (session('success'))
                    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
                        {{ session('success') }}
                    </x-alert>
                @endif
                
                @if (session('error'))
                    <x-alert type="error" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
                        {{ session('error') }}
                    </x-alert>
                @endif

                <div class="mt-6">
                    <p class="text-sm text-gray-500 mb-4">
                        Since ZIP file creation is not available, you can download each document individually:
                    </p>
                    
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($documents as $document)
                            <div class="relative bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm p-4">
                                <div class="flex items-start space-x-3">
                                    <!-- Document icon based on file type -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $fileExtension = pathinfo($document->original_filename, PATHINFO_EXTENSION);
                                            $iconColor = 'text-gray-400';
                                            
                                            switch(strtolower($fileExtension)) {
                                                case 'pdf':
                                                    $iconColor = 'text-red-500';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $iconColor = 'text-blue-500';
                                                    break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $iconColor = 'text-green-500';
                                                    break;
                                                case 'jpg':
                                                case 'jpeg':
                                                case 'png':
                                                case 'gif':
                                                    $iconColor = 'text-purple-500';
                                                    break;
                                                case 'zip':
                                                case 'rar':
                                                    $iconColor = 'text-yellow-500';
                                                    break;
                                            }
                                        @endphp
                                        
                                        <svg class="h-10 w-10 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $document->original_filename }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Uploaded: {{ $document->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('documents.download', $document) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full justify-center"
                                       onclick="showDocumentDownloadAlert('{{ $document->original_filename }}'); return true;">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showDocumentDownloadAlert(filename) {
        // Create a container for the x-alert if it doesn't exist
        let alertContainer = document.getElementById('dynamic-alerts-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.id = 'dynamic-alerts-container';
            alertContainer.className = 'fixed bottom-4 right-4 z-50 max-w-md';
            document.body.appendChild(alertContainer);
        }
        
        // Generate a unique ID for this alert
        const alertId = 'alert-' + Date.now();
        
        // Create the x-alert element using the component structure
        const alertElement = document.createElement('div');
        alertElement.id = alertId;
        alertElement.className = 'mb-4';
        alertElement.innerHTML = `
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-lg flex items-start" role="alert">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium">Document Downloaded</p>
                    <p class="text-sm">The document "${filename}" is being downloaded.</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg p-1.5 hover:bg-blue-200 inline-flex h-6 w-6 items-center justify-center" onclick="dismissAlert('${alertId}')">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add the alert to the container
        alertContainer.appendChild(alertElement);
        
        // Auto-dismiss after 4 seconds
        setTimeout(() => {
            dismissAlert(alertId);
        }, 4000);
    }
    
    function dismissAlert(alertId) {
        const alertElement = document.getElementById(alertId);
        if (alertElement) {
            alertElement.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                alertElement.remove();
            }, 500);
        }
    }
</script>
@endsection
