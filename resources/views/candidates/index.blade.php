@extends('layouts.app')

@section('content')
<div class="container">
    <div id="dynamic-alert-container" class="mb-4"></div> {{-- Container for dynamic alerts --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Candidates List</h1>
        <div class="text-sm text-gray-500">
            Showing {{ $candidates->firstItem() ?? 0 }} - {{ $candidates->lastItem() ?? 0 }} of {{ $candidates->total() }} candidates
        </div>
    </div>
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form action="{{ route('candidates.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search candidates...">
                    </div>
                </div>
                
                <!-- Status Filter -->
                <div class="w-full md:w-48">
                    <select name="status" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <!-- Add New Candidate Button -->
                <div class="flex-shrink-0">
                    <a href="{{ route('candidates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New
                    </a>
                </div>
            </div>
        </form>
    </div>
    <div class="flex justify-between items-center mb-6">
        <div class="text-sm text-gray-500"></div>
        <div class="flex space-x-2 bulk-actions opacity-0 transform transition-all duration-300 ease-in-out overflow-hidden translate-y-[-20px]" style="height: 0; max-height: 0;">
                <!-- Export Buttons -->
                <div class="flex space-x-2">
                    <button type="button" onclick="exportCandidates('pdf')" class="inline-flex items-center px-3 py-1.5 bg-blue-100 border border-blue-300 rounded-md text-xs font-medium text-blue-800 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all duration-150 shadow-sm" title="Export as PDF">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path>
                        </svg>
                        PDF
                    </button>
                    <button type="button" 
                            onclick="exportCandidates('excel')" 
                            class="inline-flex items-center px-3 py-1.5 bg-green-200 border border-green-400 rounded-md text-xs font-medium text-green-900 hover:bg-green-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all duration-150 shadow-sm" 
                            title="Export as Excel">
                        <svg class="w-4 h-4 mr-1.5 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path>
                        </svg>
                        Excel
                    </button>
                    <button type="button" onclick="exportCandidates('csv')" class="inline-flex items-center px-3 py-1.5 bg-yellow-100 border border-yellow-300 rounded-md text-xs font-medium text-yellow-800 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600 transition-all duration-150 shadow-sm" title="Export as CSV">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path>
                        </svg>
                        CSV
                    </button>
                </div>
                
                <button type="button" id="bulk-accept" class="inline-flex items-center px-3 py-1.5 bg-emerald-100 border border-emerald-300 rounded-md text-xs font-medium text-emerald-800 hover:bg-emerald-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition-all duration-150 shadow-sm" title="Accept selected candidates">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Accept Selected
                </button>
                <button type="button" id="bulk-delete" class="inline-flex items-center px-3 py-1.5 bg-red-100 border border-red-300 rounded-md text-xs font-medium text-red-800 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all duration-150 shadow-sm" title="Delete selected candidates">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
        {{ session('success') }}
    </x-alert>
    @endif

    <form id="bulk-action-form" method="POST" action="{{ route('candidates.index') }}" class="bg-white rounded-lg shadow overflow-hidden">
        @csrf

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4" title="Select all candidates">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($candidates as $candidate)
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap text-center">
                        <input type="checkbox" name="selected[]" value="{{ $candidate->id }}" class="candidate-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $candidate->getAttribute('first_name') }} {{ $candidate->getAttribute('last_name') }}</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->specialization ?: 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->phone ?: 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->city ?: 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->academic_year ?: 'First Year' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($candidate->status === 'accepted')
                                bg-green-100 text-green-800
                            @elseif($candidate->status === 'pending')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($candidate->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('candidates.show', $candidate) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100" title="View candidate details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('candidates.edit', $candidate) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-full hover:bg-indigo-100" title="Edit candidate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            @if($candidate->status !== 'accepted')
                            <a href="#" onclick="acceptCandidate(event, '{{ $candidate->id }}')" class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-100" title="Accept candidate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </a>
                            @else
                            <span class="p-1 rounded-full bg-green-100 flex items-center justify-center" title="Accepted">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            @endif
                            <button type="button" onclick="deleteCandidateRow(event, '{{ $candidate->id }}')" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" title="Delete candidate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No candidates found. <a href="{{ route('candidates.create') }}" class="text-blue-600 hover:text-blue-900">Add one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if ($candidates->hasPages())
        <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                <!-- Pagination Info -->
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $candidates->firstItem() }}</span> to <span class="font-medium">{{ $candidates->lastItem() }}</span> of <span class="font-medium">{{ $candidates->total() }}</span> results
                </div>
                
                <!-- Pagination Links -->
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Previous Page Link -->
                    @if ($candidates->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $candidates->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    <!-- Pagination Elements -->
                    @foreach ($candidates->getUrlRange(1, $candidates->lastPage()) as $page => $url)
                        @if ($page == $candidates->currentPage())
                            <span aria-current="page" class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Next Page Link -->
                    @if ($candidates->hasMorePages())
                        <a href="{{ $candidates->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </nav>
            </div>
        </div>
        @endif
    </form>
    
    <script>
    // Function to handle export operations
    async function exportCandidates(format) {
        // Show a loading message
        const loadingMessage = `Preparing ${format.toUpperCase()} export...`;
        
        // Create a temporary alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = 'fixed top-4 right-4 z-50 px-6 py-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 flex items-center';
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-800 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div>${loadingMessage}</div>
        `;
        
        // Add the alert to the page
        document.body.appendChild(alertDiv);
        
        // Determine the export URL and filename based on the format
        let exportUrl = '';
        let fileName = '';
        let contentType = '';
        
        switch (format) {
            case 'pdf':
                exportUrl = '{{ route('candidates.export.pdf') }}';
                fileName = 'candidates-export-' + new Date().toISOString().split('T')[0] + '.pdf';
                contentType = 'application/pdf';
                break;
            case 'excel':
                exportUrl = '{{ route('candidates.export.excel') }}';
                fileName = 'candidates-export-' + new Date().toISOString().split('T')[0] + '.xlsx';
                contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'csv':
                exportUrl = '{{ route('candidates.export.csv') }}';
                fileName = 'candidates-export-' + new Date().toISOString().split('T')[0] + '.csv';
                contentType = 'text/csv';
                break;
            default:
                console.error('Invalid export format:', format);
                return;
        }
        
        try {
            // Add timestamp to prevent caching
            const url = new URL(exportUrl);
            url.searchParams.append('_', new Date().getTime());
            
            // Fetch the file
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get the blob from the response
            const blob = await response.blob();
            
            // Create a download link and trigger the download
            const downloadUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            
            // Clean up
            document.body.removeChild(link);
            window.URL.revokeObjectURL(downloadUrl);
            
            // Show success message
            const successMessage = `${format.toUpperCase()} export completed successfully!`;
            alertDiv.innerHTML = `
                <svg class="flex-shrink-0 inline w-5 h-5 mr-3 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <div>${successMessage}</div>
            `;
            alertDiv.className = 'fixed top-4 right-4 z-50 px-6 py-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-gray-800 dark:text-green-400 flex items-center';
            
        } catch (error) {
            console.error('Export error:', error);
            
            // Show error message
            alertDiv.innerHTML = `
                <svg class="flex-shrink-0 inline w-5 h-5 mr-3 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                </svg>
                <div>Error: Failed to generate export. Please try again.</div>
            `;
            alertDiv.className = 'fixed top-4 right-4 z-50 px-6 py-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-gray-800 dark:text-red-400 flex items-center';
        }
        
        // Remove the alert after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
        const bulkAcceptButton = document.getElementById('bulk-accept');
        const bulkDeleteButton = document.getElementById('bulk-delete');
        const bulkActionForm = document.getElementById('bulk-action-form');
        const bulkActionsContainer = document.querySelector('.bulk-actions');
        
        // Select all functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            candidateCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActionButtons();
        });
        
        // Individual checkbox change
        candidateCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActionButtons();
                
                // Update select all checkbox
                const allChecked = Array.from(candidateCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(candidateCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
        
        // Update bulk action buttons state with smooth top-to-bottom transitions
        function updateBulkActionButtons() {
            const hasSelection = Array.from(candidateCheckboxes).some(cb => cb.checked);
            
            // Show/hide bulk action buttons based on selection with smooth transitions
            if (hasSelection) {
                // First make sure the container is flex
                bulkActionsContainer.classList.remove('hidden');
                bulkActionsContainer.classList.add('flex');
                
                // Use setTimeout to ensure the display change has taken effect before animating
                setTimeout(() => {
                    // Animate from top to bottom
                    bulkActionsContainer.classList.remove('opacity-0', 'translate-y-[-20px]');
                    bulkActionsContainer.classList.add('opacity-100', 'translate-y-0');
                    bulkActionsContainer.style.height = '40px'; // Set appropriate height
                    bulkActionsContainer.style.maxHeight = '40px';
                    bulkActionsContainer.style.marginBottom = '0.5rem';
                }, 10);
            } else {
                // Animate out (back to top)
                bulkActionsContainer.classList.remove('opacity-100', 'translate-y-0');
                bulkActionsContainer.classList.add('opacity-0', 'translate-y-[-20px]');
                bulkActionsContainer.style.height = '0';
                bulkActionsContainer.style.maxHeight = '0';
                bulkActionsContainer.style.marginBottom = '0';
                
                // After animation completes, hide the container
                setTimeout(() => {
                    if (!Array.from(candidateCheckboxes).some(cb => cb.checked)) {
                        bulkActionsContainer.classList.remove('flex');
                        bulkActionsContainer.classList.add('hidden');
                    }
                }, 300); // Match this with the CSS transition duration
            }
        }
        
        // Bulk accept action
        bulkAcceptButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to accept the selected candidates?')) {
                bulkActionForm.action = '{{ route("candidates.bulk-accept") }}';
                bulkActionForm.submit();
            }
        });
        
        // Bulk delete action
        bulkDeleteButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete the selected candidates? This action cannot be undone.')) {
                // Create a hidden input to store the candidates IDs with the correct name
                const selectedCheckboxes = document.querySelectorAll('input[name="selected[]"]:checked');
                
                // Remove any existing candidates input to avoid duplicates
                const existingInputs = bulkActionForm.querySelectorAll('input[name="candidates[]"]');
                existingInputs.forEach(input => input.remove());
                
                // Create new inputs with the correct name expected by the controller
                selectedCheckboxes.forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'candidates[]';
                    hiddenInput.value = checkbox.value;
                    bulkActionForm.appendChild(hiddenInput);
                });
                
                bulkActionForm.action = '{{ route("candidates.bulk-destroy") }}';
                bulkActionForm.submit();
            }
        });
        
        // Set initial state classes
        bulkActionsContainer.classList.add('hidden');
        bulkActionsContainer.classList.remove('flex');
        
        // Initial button state
        updateBulkActionButtons();
        
        // Function to accept candidate
        window.acceptCandidate = function(event, candidateId) {
            event.preventDefault();
            
            // Get the clicked element
            const clickedElement = event.currentTarget;
            const originalContent = clickedElement.innerHTML;
            
            // Show loading state
            clickedElement.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            clickedElement.style.pointerEvents = 'none';
            
            // Create form data with CSRF token
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Submit using fetch
            fetch(`/candidates/${candidateId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Success - reload the page
                    window.location.reload();
                } else {
                    throw new Error('Failed to accept candidate');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                clickedElement.innerHTML = originalContent;
                clickedElement.style.pointerEvents = 'auto';
                alert('Failed to accept candidate. Please try again.');
            });
        };

        // Function to delete candidate using form submission via JS
        window.deleteCandidateRow = function(event, candidateId) {
            event.preventDefault();

            if (confirm('Are you sure you want to delete this candidate? This action cannot be undone.')) {
                // Create a form dynamically
                const form = document.createElement('form');
                form.setAttribute('method', 'POST');
                form.setAttribute('action', `{{ url('candidates') }}/${candidateId}`); // Use url() helper for full URL

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.setAttribute('type', 'hidden');
                csrfInput.setAttribute('name', '_token');
                csrfInput.setAttribute('value', csrfToken);
                form.appendChild(csrfInput);

                // Add method override for DELETE
                const methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'DELETE');
                form.appendChild(methodInput);

                // Append the form to the body and submit
                document.body.appendChild(form);
                form.submit();

                // Optional: Remove the form after submission
                // document.body.removeChild(form);
            }
        };

        // Helper function to display dynamic alerts
        function displayAlert(type, message) {
            const container = document.getElementById('dynamic-alert-container');
            // Clear existing alerts
            container.innerHTML = '';

            const alertDiv = document.createElement('div');
            alertDiv.classList.add(
                'alert',
                `alert-${type}`,
                'px-4',
                'py-3',
                'rounded',
                'relative',
                'mb-4'
            );
            // Add specific Tailwind classes based on type
            if (type === 'success') {
                alertDiv.classList.add('bg-green-100', 'border', 'border-green-400', 'text-green-700');
            } else if (type === 'danger') {
                 alertDiv.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700');
            }

            alertDiv.innerHTML = `
                <strong class="font-bold">${type === 'success' ? 'Success!' : 'Error!'}</strong>
                <span class="block sm:inline"> ${message}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-${type === 'success' ? 'green' : 'red'}-500" role="button" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.remove()">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15L6.342 6.342a1.2 1.2 0 0 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.15 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            `;

            container.appendChild(alertDiv);

            // Optional: Auto-dismiss after a few seconds
            // setTimeout(() => {
            //     alertDiv.remove();
            // }, 5000); // 5 seconds
        }
    });
    </script>
</div>
@endsection
