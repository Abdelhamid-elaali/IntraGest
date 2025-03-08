@extends('layouts.app')

@section('title', 'New Enrollment')

@section('header', 'Create New Enrollment')

@section('content')
    <x-card>
        <form action="{{ route('enrollments.store') }}" method="POST">
            @csrf

            <!-- Student Information -->
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Student Information</h2>
                    <p class="mt-1 text-sm text-gray-500">Select the student and provide enrollment details.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-select
                            name="student_id"
                            label="Select Student"
                            :error="$errors->first('student_id')"
                            required
                        >
                            <option value="">Select a student</option>
                            <option value="1">John Smith</option>
                            <option value="2">Sarah Johnson</option>
                            <option value="3">Michael Brown</option>
                        </x-select>
                    </div>

                    <div>
                        <x-select
                            name="academic_term_id"
                            label="Academic Term"
                            :error="$errors->first('academic_term_id')"
                            required
                        >
                            <option value="">Select term</option>
                            <option value="1">Fall 2025</option>
                            <option value="2">Spring 2026</option>
                            <option value="3">Summer 2026</option>
                        </x-select>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Subject Selection</h2>
                    
                    <div class="bg-blue-50 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Select the subjects for enrollment. Make sure to check prerequisites and credit limits.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Mathematics -->
                        <div class="flex items-start p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="subjects[]" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-900">Mathematics</label>
                                <p class="text-sm text-gray-500">Advanced Mathematics - 4 Credits</p>
                                <div class="mt-1">
                                    <x-badge variant="success" size="sm">No Prerequisites</x-badge>
                                </div>
                            </div>
                        </div>

                        <!-- Physics -->
                        <div class="flex items-start p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="subjects[]" value="2" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-900">Physics</label>
                                <p class="text-sm text-gray-500">General Physics - 3 Credits</p>
                                <div class="mt-1">
                                    <x-badge variant="warning" size="sm">Requires Mathematics</x-badge>
                                </div>
                            </div>
                        </div>

                        <!-- Literature -->
                        <div class="flex items-start p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="subjects[]" value="3" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3">
                                <label class="text-sm font-medium text-gray-900">Literature</label>
                                <p class="text-sm text-gray-500">World Literature - 3 Credits</p>
                                <div class="mt-1">
                                    <x-badge variant="success" size="sm">No Prerequisites</x-badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <div class="mt-1">
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Add any additional notes or requirements..."
                        ></textarea>
                    </div>
                </div>

                <!-- Credit Summary -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900">Credit Summary</h3>
                    <dl class="mt-2 grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm text-gray-500">Selected Credits</dt>
                            <dd class="text-sm font-medium text-gray-900">10</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Maximum Credits Allowed</dt>
                            <dd class="text-sm font-medium text-gray-900">18</dd>
                        </div>
                    </dl>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <x-button variant="outline" type="button" onclick="history.back()">
                        Cancel
                    </x-button>
                    <x-button variant="primary" type="submit">
                        Create Enrollment
                    </x-button>
                </div>
            </div>
        </form>
    </x-card>

    <!-- Warning Messages -->
    <div class="mt-6 space-y-4">
        <x-alert type="warning" :dismissible="true">
            <strong class="font-medium">Important:</strong>
            Enrollment will be pending until approved by an administrator.
        </x-alert>

        <x-alert type="info" :dismissible="true">
            <strong class="font-medium">Note:</strong>
            Make sure all prerequisites are met before submitting the enrollment.
        </x-alert>
    </div>
@endsection
