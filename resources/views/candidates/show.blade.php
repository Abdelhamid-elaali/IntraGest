@extends('layouts.app')

@section('title', 'Candidate Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800">Candidate Details</h2>
        <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <!-- Candidate Profile Section -->
                <div class="md:w-1/3 flex flex-col items-center p-6 border-b md:border-b-0 md:border-r border-gray-200">
                    <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-blue-600 font-bold text-4xl">{{ substr($candidate->first_name, 0, 1) }}</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $candidate->first_name }} {{ $candidate->last_name }}</h3>
                    
                    <span class="px-3 py-1 mt-2 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($candidate->status == 'pending')
                            bg-yellow-100 text-yellow-800
                        @elseif($candidate->status == 'accepted')
                            bg-green-100 text-green-800
                        @elseif($candidate->status == 'rejected')
                            bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($candidate->status) }}
                    </span>
                    
                    <div class="mt-6 flex space-x-2">
                        <a href="{{ route('candidates.edit', $candidate) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this candidate?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                    
                    @if($candidate->status == 'pending')
                    <form action="{{ route('candidates.accept', $candidate) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Accept Candidate
                        </button>
                    </form>
                    @endif
                </div>
                
                <!-- Candidate Details Section -->
                <div class="md:w-2/3 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Contact Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Email Address</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->email }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Phone Number</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->phone }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">Address</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->address }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500">City</h4>
                                <div class="mt-1 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-gray-800">{{ $candidate->city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">Application Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Application Date</h4>
                            <div class="mt-1 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-800">{{ date('d/m/Y', strtotime($candidate->application_date)) }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-500">Birth Date</h4>
                            <div class="mt-1 flex items-center">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-800">{{ $candidate->birth_date ? date('d/m/Y', strtotime($candidate->birth_date)) : 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($candidate->notes)
                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-2 border-b border-gray-200">Notes</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-gray-700">{{ $candidate->notes }}</p>
                    </div>
                    @endif
                    
                    <!-- Supporting Documents Section -->
                    <div class="flex justify-between items-center mt-6 mb-4 pb-2 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Supporting Documents</h3>
                        @if($candidate->documents->count() > 0)
                        <a href="{{ route('candidates.download-documents', $candidate) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download All
                        </a>
                        @endif
                    </div>
                    
                    @if($candidate->documents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($candidate->documents as $document)
                                <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
                                    @php
                                        $fileType = strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION));
                                        $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    
                                    <!-- Image Preview (if applicable) -->
                                    @if($isImage)
                                        <div class="mb-3">
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="block">
                                                <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->original_filename }}" class="w-full h-32 object-cover rounded-md border border-gray-200 hover:opacity-90 transition-opacity duration-200" />
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Document icon based on file type -->
                                        <div class="flex-shrink-0">
                                            @php
                                                $iconClass = 'text-gray-400';
                                                
                                                if (in_array($fileType, ['pdf'])) {
                                                    $iconClass = 'text-red-500';
                                                } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                    $iconClass = 'text-blue-500';
                                                } elseif (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
                                                    $iconClass = 'text-green-500';
                                                } elseif (in_array($fileType, ['xls', 'xlsx', 'csv'])) {
                                                    $iconClass = 'text-emerald-500';
                                                }
                                            @endphp
                                            
                                            <svg class="h-10 w-10 {{ $iconClass }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Document details -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $document->original_filename }}">
                                                {{ $document->original_filename }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }} • {{ number_format($document->file_size / 1024, 0) }} KB
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Added {{ $document->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Document actions -->
                                    <div class="mt-3 flex justify-end space-x-2">
                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <form action="{{ route('candidates.delete-document', $document) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 text-center">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-600">No supporting documents have been uploaded for this candidate.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Handle document download with alert
     */
    function handleDocumentDownload(event, filename, documentId) {
        // Prevent default link behavior
        event.preventDefault();
        
        // Show the download alert
        showDocumentDownloadAlert(filename);
        
        // Construct the download URL
        const downloadUrl = '/documents/' + documentId + '/download';
        
        // For direct download, open in a new window that will be automatically closed
        const downloadWindow = window.open(downloadUrl, '_blank');
        
        // If popup is blocked, fallback to direct navigation
        if (!downloadWindow) {
            window.location.href = downloadUrl;
        } else {
            // Close the window after download is initiated (for browsers that allow it)
            setTimeout(() => {
                try {
                    if (downloadWindow) downloadWindow.close();
                } catch (e) {
                    // Ignore errors from cross-origin restrictions
                }
            }, 1000);
        }
    }

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
                    <p class="text-sm">The document "${filename}" is being downloaded. Some file types may not be viewable in the browser and will need to be opened with appropriate software.</p>
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
        
        // Auto-dismiss after 6 seconds
        setTimeout(() => {
            dismissAlert(alertId);
        }, 6000);
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
