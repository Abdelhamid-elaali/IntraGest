@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/criteria.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-blue-800">Add New Criteria</h1>
        <p class="text-gray-600">Create multiple acceptance criteria for candidate evaluation.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('criteria.store') }}" method="POST" id="criteria-form">
            @csrf

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Criteria Container -->
            <div id="criteria-container">
                <!-- Initial Criteria Row -->
                <div class="criteria-row border-b border-gray-200 pb-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Criterion Name</label>
                            <input type="text" 
                                   name="criteria[0][name]" 
                                   placeholder="Enter criterion name" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="criteria[0][category]" class="w-full category-select rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="">Select Category</option>
                                <option value="geographical">Geographical</option>
                                <option value="social">Social</option>
                                <option value="academic">Academic</option>
                                <option value="physical">Physical</option>
                                <option value="family">Family</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Score Points</label>
                                <div class="flex">
                                    <input type="number" 
                                           name="criteria[0][score]" 
                                           min="1" 
                                           max="100" 
                                           placeholder="Points (1-100)" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                        <textarea name="criteria[0][description]" 
                                  rows="2" 
                                  placeholder="Enter criterion description" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>
                </div>
            </div>

            <!-- Add More Button -->
            <div class="mt-2 mb-6">
                <button type="button" id="add-criteria" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Another Criterion
                </button>
            </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('criteria.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Save Criterion
                </button>
            </div>
            
        </form>
    </div>
    
    <!-- Criteria Information -->
    <div class="mt-8 mb-6">
        <x-criteria-info />
    </div>
</div>
@endsection
