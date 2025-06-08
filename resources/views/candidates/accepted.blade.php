@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Candidates Selection</h1>
        <div class="flex flex-col items-end space-y-2">
        <form action="{{ route('candidates.index') }}" method="GET">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to All Candidates
            </button>
        </form>
            <div class="flex space-x-2 bulk-actions opacity-0 transform transition-all duration-300 ease-in-out overflow-hidden translate-y-[-20px]" style="height: 0; max-height: 0;">
                <button type="button" id="bulk-convert" class="inline-flex items-center px-3 py-1.5 bg-green-100 border border-green-300 rounded-md text-xs font-medium text-green-800 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all duration-150 shadow-sm" title="Convert selected candidates to trainees">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Convert to Trainee
                </button>
                <button type="button" id="bulk-reject" class="inline-flex items-center px-3 py-1.5 bg-red-100 border border-red-300 rounded-md text-xs font-medium text-red-800 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all duration-150 shadow-sm" title="Reject selected candidates">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Selected
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4">
        {{ session('success') }}
    </x-alert>
    @endif  

    <form id="bulk-action-form" method="POST" class="bg-white rounded-lg shadow overflow-hidden">
        @csrf
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4" title="Select all candidates">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance (km)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Score</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acceptance Date</th>
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
                        <div class="text-sm font-medium text-gray-900">{{ $candidate->first_name }} {{ $candidate->last_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->nationality ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->distance ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->academic_year ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-green-600">{{ $candidate->score ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->updated_at->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('candidates.show', $candidate) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100" title="View candidate details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <button onclick="convertCandidate(event, '{{ $candidate->id }}')" class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-100" title="Convert to trainee">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </button>
                            <button onclick="rejectCandidate(event, '{{ $candidate->id }}')" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" title="Reject candidate">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No accepted candidates found. <a href="{{ route('candidates.index') }}" class="text-blue-600 hover:text-blue-900">View all candidates</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50">
            {{ $candidates->links() }}
        </div>
    </form>
</div>

<script>
// Show success message if URL has converted=true parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('converted') === 'true') {
        // Show success message using the application's alert system
        const alertEvent = new CustomEvent('show-alert', {
            detail: {
                type: 'success',
                message: 'Candidate successfully converted to trainee.',
                autoDismiss: true,
                dismissAfter: 4000
            }
        });
        window.dispatchEvent(alertEvent);
        
        // Remove the converted parameter from URL without reloading
        const url = new URL(window.location);
        url.searchParams.delete('converted');
        window.history.replaceState({}, '', url);
    }
    const selectAllCheckbox = document.getElementById('select-all');
    const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
    const bulkConvertButton = document.getElementById('bulk-convert');
    const bulkRejectButton = document.getElementById('bulk-reject');
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
    
    // Update bulk action buttons state with smooth transitions
    function updateBulkActionButtons() {
        const hasSelection = Array.from(candidateCheckboxes).some(cb => cb.checked);
        
        if (hasSelection) {
            bulkActionsContainer.classList.remove('hidden');
            bulkActionsContainer.classList.add('flex');
            
            setTimeout(() => {
                bulkActionsContainer.classList.remove('opacity-0', 'translate-y-[-20px]');
                bulkActionsContainer.classList.add('opacity-100', 'translate-y-0');
                bulkActionsContainer.style.height = '40px';
                bulkActionsContainer.style.maxHeight = '40px';
                bulkActionsContainer.style.marginBottom = '0.5rem';
            }, 10);
        } else {
            bulkActionsContainer.classList.remove('opacity-100', 'translate-y-0');
            bulkActionsContainer.classList.add('opacity-0', 'translate-y-[-20px]');
            bulkActionsContainer.style.height = '0';
            bulkActionsContainer.style.maxHeight = '0';
            bulkActionsContainer.style.marginBottom = '0';
            
            setTimeout(() => {
                if (!Array.from(candidateCheckboxes).some(cb => cb.checked)) {
                    bulkActionsContainer.classList.remove('flex');
                    bulkActionsContainer.classList.add('hidden');
                }
            }, 300);
        }
    }
    
    // Bulk convert to trainee action
    bulkConvertButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to convert the selected candidates to trainees?')) {
            bulkActionForm.action = '{{ route("candidates.bulk-convert") }}';
            console.log('Bulk convert form action:', bulkActionForm.action);
            bulkActionForm.submit();
        }
    });
    
    // Bulk reject action
    bulkRejectButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to reject the selected candidates? This action cannot be undone.')) {
            // Set the form action to the bulk reject route
            bulkActionForm.action = '{{ route("candidates.bulk-reject") }}';
            console.log('Bulk reject form action:', bulkActionForm.action);

            // The selected candidate IDs are already collected by checkboxes with name="selected[]"
            // and the bulkReject controller method expects input named 'selected'.
            // Remove any lingering inputs from a potential previous bulk action (like delete) if they exist.
            const existingCandidatesInputs = bulkActionForm.querySelectorAll('input[name="candidates[]"]');
            existingCandidatesInputs.forEach(input => input.remove());

            // Submit the form
            bulkActionForm.submit();
        }
    });
    
    // Function to convert candidate to trainee
    window.convertCandidate = function(event, candidateId) {
        event.preventDefault();
        
        if (!confirm('Are you sure you want to convert this candidate to a trainee?')) {
            return;
        }
        
        // Show loading state
        const clickedButton = event.currentTarget;
        const originalContent = clickedButton.innerHTML;
        clickedButton.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        clickedButton.disabled = true;
        
        // First check if candidate is already a trainee
        fetch(`/candidates/${candidateId}/check-trainee`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Show warning message using the application's alert system
                    const alertEvent = new CustomEvent('show-alert', {
                        detail: {
                            type: 'warning',
                            title: 'Already a Trainee',
                            message: `This candidate is already in the trainee list as ${data.student_name} (ID: ${data.student_id})`,
                            autoDismiss: true,
                            dismissAfter: 5000
                        }
                    });
                    window.dispatchEvent(alertEvent);
                    
                    // Restore button state
                    clickedButton.innerHTML = originalContent;
                    clickedButton.disabled = false;
                    return;
                }
                
                // If not a trainee, proceed with conversion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/candidates/${candidateId}/convert`;
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);
                
                // Add method spoofing for PUT/PATCH/DELETE
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'POST';
                form.appendChild(method);
                
                // Add form to the page and submit it
                document.body.appendChild(form);
                form.submit();
            })
            .catch(error => {
                console.error('Error checking trainee status:', error);
                // Restore button state
                clickedButton.innerHTML = originalContent;
                clickedButton.disabled = false;
                
                // Show error message
                const alertEvent = new CustomEvent('show-alert', {
                    detail: {
                        type: 'error',
                        title: 'Error',
                        message: 'An error occurred while checking trainee status. Please try again.',
                        autoDismiss: true,
                        dismissAfter: 5000
                    }
                });
                window.dispatchEvent(alertEvent);
            });
    };
    
    // Function to reject candidate
    window.rejectCandidate = function(event, candidateId) {
        event.preventDefault();
        
        if (!confirm('Are you sure you want to reject this candidate?')) {
            return;
        }

        // Get the clicked element for potential loading state feedback
        const clickedElement = event.currentTarget;
        const originalContent = clickedElement.innerHTML;
        
        // Show loading state (optional)
        // clickedElement.innerHTML = '<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        // clickedElement.style.pointerEvents = 'none';

        // Create a form dynamically to submit a POST request
        const form = document.createElement('form');
        form.setAttribute('method', 'POST');
        form.setAttribute('action', `/candidates/${candidateId}/reject`); // Correct route

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.setAttribute('type', 'hidden');
        csrfInput.setAttribute('name', '_token');
        csrfInput.setAttribute('value', csrfToken);
        form.appendChild(csrfInput);

        console.log('Submitting individual reject form to:', form.action); // Temporary log

        // Append the form to the body and submit
        document.body.appendChild(form);
        form.submit();

        // Optional: Handle response via fetch if not using full form submission and page reload
        /*
        fetch(`/candidates/${candidateId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            // body: JSON.stringify({ _token: csrfToken }) // If sending JSON
        })
        .then(response => {
            if (response.ok) {
                // Success - reload the page or remove the row
                window.location.reload(); 
                // Or find the row and remove it:
                // const candidateRow = clickedElement.closest('tr');
                // if (candidateRow) { candidateRow.remove(); }
            } else {
                // Handle errors (e.g., show error message)
                response.text().then(text => console.error('Reject failed:', text));
                alert('Failed to reject candidate. Please try again.');
                 // Restore button state
                 // clickedElement.innerHTML = originalContent;
                 // clickedElement.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Network error during reject:', error);
            alert('An error occurred. Please try again.');
             // Restore button state
             // clickedElement.innerHTML = originalContent;
             // clickedElement.style.pointerEvents = 'auto';
        });
        */
    };

    // Set initial state
    bulkActionsContainer.classList.add('hidden');
    bulkActionsContainer.classList.remove('flex');
    updateBulkActionButtons();
});

// JavaScript to explicitly submit individual reject forms
document.addEventListener('DOMContentLoaded', function() {
    const individualRejectForms = document.querySelectorAll('.individual-reject-form');

    individualRejectForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // If the default was prevented (e.g., by the confirm dialog), explicitly submit
            if (event.defaultPrevented) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
