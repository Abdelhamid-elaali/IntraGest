@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Candidates List</h1>
        <div class="flex flex-col items-end space-y-2">
            <form action="{{ route('candidates.create') }}" method="GET">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Candidate
                </button>
            </form>
            <div class="flex space-x-2 bulk-actions opacity-0 transform transition-all duration-300 ease-in-out overflow-hidden translate-y-[-20px]" style="height: 0; max-height: 0;">
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Income Level</th>
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
                        <div class="text-sm text-gray-500">{{ $candidate->income_level ? ucfirst($candidate->income_level) : 'N/A' }}</div>
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
                            @endif
                        ">
                            {{ ucfirst($candidate->status) }}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
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
                            <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" title="Delete candidate">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No candidates found. <a href="{{ route('candidates.create') }}" class="text-blue-600 hover:text-blue-900">Add one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50">
            {{ $candidates->links() }}
        </div>
    </form>
    
    <script>
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
    });
    </script>
</div>
@endsection
