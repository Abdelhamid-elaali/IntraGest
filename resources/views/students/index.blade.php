@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Trainee List</h1>
        <div class="flex flex-col items-end space-y-2">
            <form action="{{ route('students.create') }}" method="GET">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Trainee
                </button>
            </form>
            <div class="flex space-x-2 bulk-actions opacity-0 transform transition-all duration-300 ease-in-out overflow-hidden translate-y-[-20px]" style="height: 0; max-height: 0;">
                <button type="button" id="bulk-delete" class="inline-flex items-center px-3 py-2 bg-red-100 border border-red-300 rounded-md text-xs font-medium text-red-800 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all duration-150 shadow-sm" title="Delete selected trainees">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <x-alert type="success" :auto-dismiss="true" :dismiss-after="4000" class="mb-4" title="Success!">
        {{ session('success') }}
    </x-alert>
    @endif

    <form id="bulk-action-form" method="POST" action="#" class="bg-white rounded-lg shadow overflow-hidden">
        @csrf
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4" title="Select all trainees">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialization</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nationality</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap text-center">
                        <input type="checkbox" name="selected[]" value="{{ $student->id }}" class="student-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-4 w-4">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $student->first_name }} {{ $student->last_name }}
                            @if($student->name && $student->name !== trim($student->first_name . ' ' . $student->last_name))
                                <span class="text-xs text-gray-500">({{ $student->name }})</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->academic_year }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->specialization }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->nationality }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->phone ?: 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-100" title="View trainee details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 p-1 rounded-full hover:bg-indigo-100" title="Edit trainee">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button onclick="deleteStudent({{ $student->id }})" class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100" title="Delete trainee">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No trainees found. <a href="{{ route('students.create') }}" class="text-blue-600 hover:text-blue-900">Add one now</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 bg-gray-50">
            {{ $students->links() }}
        </div>
    </form>
</div>

@if(session('trainee_added'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger custom event when a trainee is added
        window.dispatchEvent(new CustomEvent('trainee-added'));
    });
</script>
@elseif(session('trainee_removed'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger custom event when a trainee is removed
        window.dispatchEvent(new CustomEvent('trainee-removed'));
    });
</script>
@endif

<script type="module">
import TraineeCounter from '/js/trainee-counter.js';

// Initialize with the current count
TraineeCounter.init({{ $students->total() }});

// Update the counter when the page loads
TraineeCounter.subscribe((count) => {
    console.log('Trainee count updated in students list:', count);
    // You can update any UI elements here if needed
});

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const bulkActionsContainer = document.querySelector('.bulk-actions');
    const bulkDeleteBtn = document.getElementById('bulk-delete');
    const bulkActionForm = document.getElementById('bulk-action-form');
    
    // Toggle bulk actions visibility based on checkboxes
    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
        const someChecked = checkedBoxes.length > 0;
        
        if (someChecked) {
            bulkActionsContainer.style.height = 'auto';
            bulkActionsContainer.style.maxHeight = '100px';
            bulkActionsContainer.style.opacity = '1';
            bulkActionsContainer.style.transform = 'translateY(0)';
        } else {
            bulkActionsContainer.style.height = '0';
            bulkActionsContainer.style.maxHeight = '0';
            bulkActionsContainer.style.opacity = '0';
            bulkActionsContainer.style.transform = 'translateY(-20px)';
        }
    }
    
    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        toggleBulkActions();
    });
    
    // Individual checkbox change
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(studentCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
            toggleBulkActions();
        });
    });
    
    // Bulk delete functionality
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.student-checkbox:checked'))
                .map(checkbox => checkbox.value);
                
            if (selectedIds.length === 0) {
                const alertEvent = new CustomEvent('show-alert', {
                    detail: {
                        type: 'warning',
                        message: 'Please select at least one trainee to delete.',
                        timeout: 4000
                    }
                });
                window.dispatchEvent(alertEvent);
                return;
            }
            
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected trainee(s)?`)) {
                return;
            }
            
            // Set the form action and submit
            bulkActionForm.action = '{{ route("students.bulk-destroy") }}';
            bulkActionForm.submit();
        });
    }
});

async function deleteStudent(studentId) {
    if (!confirm('Are you sure you want to delete this trainee?')) {
        return;
    }
    
    try {
        const response = await fetch(`/students/${studentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Update the counter
            TraineeCounter.updateCount(data.count);
            
            // Remove the row from the table
            const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
            if (row) row.remove();
            
            // Show success message
            alert('Trainee deleted successfully');
        } else {
            throw new Error(data.message || 'Failed to delete trainee');
        }
    } catch (error) {
        console.error('Error deleting trainee:', error);
        alert('Error deleting trainee: ' + error.message);
    }

    // Get the CSRF token from the meta tag
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Create the fetch request
    fetch(`/students/${studentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        redirect: 'follow',
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.redirected) {
            // If the response is a redirect, follow it to show the flash message
            window.location.href = response.url;
        } else if (response.ok) {
            // If it's a JSON response (API)
            return response.json().then(data => {
                // Show success message using the application's alert system
                const alertEvent = new CustomEvent('show-alert', {
                    detail: {
                        type: 'success',
                        message: data.message || 'Trainee deleted successfully.',
                        timeout: 5000
                    }
                });
                window.dispatchEvent(alertEvent);
                
                // Reload the page after a short delay to update the list
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            });
        } else {
            // Handle error case
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to delete trainee');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        const alertEvent = new CustomEvent('show-alert', {
            detail: {
                type: 'error',
                message: error.message || 'An error occurred while deleting the trainee.',
                timeout: 5000
            }
        });
        window.dispatchEvent(alertEvent);
    });
}
</script>
@endsection
