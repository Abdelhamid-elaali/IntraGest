@extends('layouts.app')

@section('title', 'Edit Absence')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Absence Record
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('absences.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <form action="{{ route('absences.update', $absence) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Student Selection -->
                <div class="sm:col-span-3">
                    <label for="student_id" class="block text-sm font-medium text-gray-700">
                        Student
                    </label>
                    <div class="mt-1">
                        <select id="student_id" name="student_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (old('student_id', $absence->student_id) == $student->id) ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Type -->
                <div class="sm:col-span-3">
                    <label for="type" class="block text-sm font-medium text-gray-700">
                        Type
                    </label>
                    <div class="mt-1">
                        <select id="type" name="type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select type</option>
                            <option value="excused" {{ old('type', $absence->type) == 'excused' ? 'selected' : '' }}>Excused</option>
                            <option value="unexcused" {{ old('type', $absence->type) == 'unexcused' ? 'selected' : '' }}>Unexcused</option>
                            <option value="late" {{ old('type', $absence->type) == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="medical" {{ old('type', $absence->type) == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="family" {{ old('type', $absence->type) == 'family' ? 'selected' : '' }}>Family Emergency</option>
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Start Date -->
                <div class="sm:col-span-3">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">
                        Start Date
                    </label>
                    <div class="mt-1">
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $absence->start_date ? $absence->start_date->format('Y-m-d') : '') }}" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('start_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- End Date -->
                <div class="sm:col-span-3">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">
                        End Date
                    </label>
                    <div class="mt-1">
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $absence->end_date ? $absence->end_date->format('Y-m-d') : '') }}" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('end_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Duration (for late type) -->
                <div class="sm:col-span-3" id="duration-container" style="{{ $absence->type === 'late' ? 'display: block;' : 'display: none;' }}">
                    <label for="duration" class="block text-sm font-medium text-gray-700">
                        Duration (minutes)
                    </label>
                    <div class="mt-1">
                        <input type="number" name="duration" id="duration" min="1" value="{{ old('duration', $absence->duration) }}"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-3">
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <div class="mt-1">
                        <select id="status" name="status" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="pending" {{ old('status', $absence->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $absence->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $absence->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Supporting Documents -->
                @if($absence->supporting_documents && count($absence->supporting_documents) > 0)
                    <div class="sm:col-span-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Current Supporting Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            @foreach($absence->supporting_documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ basename($document) }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ asset('storage/' . $document) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                        <button type="button" onclick="confirmDocumentRemoval('{{ $document }}')" class="text-red-600 hover:text-red-800 text-sm">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="documents_to_remove" id="documents_to_remove" value="">
                    </div>
                @endif

                <!-- New Supporting Documents -->
                <div class="sm:col-span-6">
                    <label for="supporting_documents" class="block text-sm font-medium text-gray-700">
                        Add New Supporting Documents
                    </label>
                    <div class="mt-1">
                        <input type="file" name="supporting_documents[]" id="supporting_documents" multiple
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <p class="mt-1 text-sm text-gray-500">Upload any supporting documents (medical certificates, official letters, etc.)</p>
                        @error('supporting_documents')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Reason -->
                <div class="sm:col-span-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700">
                        Reason
                    </label>
                    <div class="mt-1">
                        <textarea id="reason" name="reason" rows="3" required
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('reason', $absence->reason) }}</textarea>
                        @error('reason')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes (for admins) -->
                <div class="sm:col-span-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        Admin Notes
                    </label>
                    <div class="mt-1">
                        <textarea id="notes" name="notes" rows="3"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('notes', $absence->notes) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Internal notes about this absence (not visible to the student)</p>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Absence
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Document Removal Confirmation Modal -->
<div id="documentRemovalModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Remove Document</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Are you sure you want to remove this document? This action cannot be undone.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="confirmRemoveBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Remove
            </button>
            <button type="button" onclick="hideDocumentRemovalModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeField = document.getElementById('type');
        const durationContainer = document.getElementById('duration-container');
        const durationField = document.getElementById('duration');
        const startDateField = document.getElementById('start_date');
        const endDateField = document.getElementById('end_date');
        const documentsToRemove = document.getElementById('documents_to_remove');
        let documentsToRemoveArray = [];

        // Handle type change
        typeField.addEventListener('change', function() {
            if (this.value === 'late') {
                durationContainer.style.display = 'block';
                durationField.required = true;
            } else {
                durationContainer.style.display = 'none';
                durationField.required = false;
            }
        });

        // Ensure end date is not before start date
        startDateField.addEventListener('change', function() {
            if (endDateField.value && new Date(endDateField.value) < new Date(this.value)) {
                endDateField.value = this.value;
            }
        });

        // Validate form before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const startDate = new Date(startDateField.value);
            const endDate = new Date(endDateField.value);

            if (endDate < startDate) {
                e.preventDefault();
                alert('End date cannot be before start date');
                return false;
            }

            // Update the hidden field with the list of documents to remove
            if (documentsToRemoveArray.length > 0) {
                documentsToRemove.value = JSON.stringify(documentsToRemoveArray);
            }
        });

        // Set up the document removal confirmation modal
        window.confirmDocumentRemoval = function(documentPath) {
            const modal = document.getElementById('documentRemovalModal');
            const confirmBtn = document.getElementById('confirmRemoveBtn');
            
            modal.classList.remove('hidden');
            
            confirmBtn.onclick = function() {
                documentsToRemoveArray.push(documentPath);
                hideDocumentRemovalModal();
                
                // Update UI to show document will be removed
                const documentElements = document.querySelectorAll('.flex.items-center.justify-between');
                documentElements.forEach(element => {
                    if (element.querySelector('span').textContent === basename(documentPath)) {
                        element.classList.add('opacity-50');
                        element.querySelector('button').textContent = 'Marked for removal';
                        element.querySelector('button').disabled = true;
                    }
                });
            };
        };

        window.hideDocumentRemovalModal = function() {
            document.getElementById('documentRemovalModal').classList.add('hidden');
        };

        // Helper function to get the basename from a path
        function basename(path) {
            return path.split('/').pop();
        }
    });
</script>
@endsection
