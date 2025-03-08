@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create Academic Term</h1>
            <a href="{{ route('terms.index') }}" class="text-blue-600 hover:text-blue-800">Back to Terms</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('terms.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Term Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Term Type</label>
                    <select name="type" id="type" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <option value="">Select Type</option>
                        <option value="semester" {{ old('type') == 'semester' ? 'selected' : '' }}>Semester</option>
                        <option value="trimester" {{ old('type') == 'trimester' ? 'selected' : '' }}>Trimester</option>
                        <option value="quarter" {{ old('type') == 'quarter' ? 'selected' : '' }}>Quarter</option>
                    </select>
                </div>

                <div>
                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year') }}" required
                        placeholder="2024-2025"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-1">Registration Deadline</label>
                    <input type="date" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="drop_deadline" class="block text-sm font-medium text-gray-700 mb-1">Drop Deadline</label>
                    <input type="date" name="drop_deadline" id="drop_deadline" value="{{ old('drop_deadline') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label for="grading_deadline" class="block text-sm font-medium text-gray-700 mb-1">Grading Deadline</label>
                    <input type="date" name="grading_deadline" id="grading_deadline" value="{{ old('grading_deadline') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_current" id="is_current" value="1" {{ old('is_current') ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_current" class="ml-2 block text-sm text-gray-900">Set as Current Term</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Term
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const registrationDeadlineInput = document.getElementById('registration_deadline');
        const dropDeadlineInput = document.getElementById('drop_deadline');
        const gradingDeadlineInput = document.getElementById('grading_deadline');

        // Set min dates
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            dropDeadlineInput.min = this.value;
        });

        endDateInput.addEventListener('change', function() {
            registrationDeadlineInput.max = this.value;
            dropDeadlineInput.max = this.value;
            gradingDeadlineInput.min = this.value;
        });
    });
</script>
@endpush
@endsection
