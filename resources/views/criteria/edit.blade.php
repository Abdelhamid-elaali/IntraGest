@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Edit Criterion</h1>
        <p class="text-gray-600">Update the acceptance criterion for candidate evaluation.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form id="editCriterionForm" action="{{ route('criteria.update', $criteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="_method" value="PUT">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Criterion Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $criteria->name) }}" 
                           placeholder="Enter criterion name"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Select Category</option>
                        <option value="geographical" {{ old('category', $criteria->category) == 'geographical' ? 'selected' : '' }}>Geographical</option>
                        <option value="social" {{ old('category', $criteria->category) == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="academic" {{ old('category', $criteria->category) == 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="physical" {{ old('category', $criteria->category) == 'physical' ? 'selected' : '' }}>Physical</option>
                        <option value="family" {{ old('category', $criteria->category) == 'family' ? 'selected' : '' }}>Family</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="score" class="block text-sm font-medium text-gray-700 mb-1">Score Points</label>
                    <input type="number" 
                           name="score" 
                           id="score" 
                           value="{{ old('score', $criteria->score) }}" 
                           min="1" 
                           max="100" 
                           placeholder="Enter score points (1-100)"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                           required>
                    <p class="text-xs text-gray-500 mt-1">The score points awarded for this criterion in the candidate selection process.</p>
                    @error('score')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              placeholder="Enter a description for this criterion (optional)" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $criteria->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('criteria.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" id="submitButton" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                    <span class="submit-text">Update Criterion</span>
                    <div id="loadingSpinner" class="hidden ml-2 spinner-border spinner-border-sm text-white" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editCriterionForm');
    const submitButton = document.getElementById('submitButton');
    const submitText = submitButton.querySelector('.submit-text');
    const loadingSpinner = document.getElementById('loadingSpinner');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitButton.disabled = true;
        submitText.textContent = 'Updating...';
        loadingSpinner.classList.remove('hidden');
        
        // Submit the form via fetch API
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(new FormData(form)).toString()
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            // Redirect to index page with success message
            if (data.redirect) {
                // Add success message to the session before redirecting
                sessionStorage.setItem('showSuccessMessage', data.message || 'Criterion updated successfully.');
                window.location.href = data.redirect;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the criterion. Please try again.');
            submitButton.disabled = false;
            submitText.textContent = 'Update Criterion';
            loadingSpinner.classList.add('hidden');
        });
    });
});
</script>
@endpush

    <!-- Criteria Information -->
    <div class="mt-8 mb-6">
        <x-criteria-info />
    </div>
</div>
@endsection
