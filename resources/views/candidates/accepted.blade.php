@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Accepted Candidates</h1>
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

    <form id="bulk-action-form" method="POST" action="#" class="bg-white rounded-lg shadow overflow-hidden">
        @csrf
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4" title="Select all candidates">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distance (km)</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Income Level</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Training Level</th>
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
                        <div class="text-sm text-gray-500">{{ $candidate->distance ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->income_level ? ucfirst($candidate->income_level) : 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $candidate->training_level ? ucfirst($candidate->training_level) : 'N/A' }}</div>
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
                            <form action="{{ route('candidates.convert', $candidate) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-100" title="Convert to trainee">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('candidates.reject', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this candidate?');">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" title="Reject candidate">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const candidateCheckboxes = document.querySelectorAll('.candidate-checkbox');
    const bulkConvertButton = document.getElementById('bulk-convert');
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
            bulkActionForm.submit();
        }
    });
    
    // Bulk delete action
    bulkDeleteButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete the selected candidates? This action cannot be undone.')) {
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
    
    // Set initial state
    bulkActionsContainer.classList.add('hidden');
    bulkActionsContainer.classList.remove('flex');
    updateBulkActionButtons();
});
</script>
@endsection
