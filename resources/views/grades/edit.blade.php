@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Grade</h1>
            <a href="{{ route('grades.index') }}" class="text-blue-500 hover:text-blue-600">
                Back to Grades
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('grades.update', $grade) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <select name="student_id" id="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $grade->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject_id" id="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $grade->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="academic_term_id" class="block text-sm font-medium text-gray-700 mb-2">Academic Term</label>
                    <select name="academic_term_id" id="academic_term_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Select Term</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ old('academic_term_id', $grade->academic_term_id) == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_term_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="assessment_type" class="block text-sm font-medium text-gray-700 mb-2">Assessment Type</label>
                    <select name="assessment_type" id="assessment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                        <option value="">Select Type</option>
                        @foreach(['Assignment', 'Quiz', 'Midterm', 'Final', 'Project', 'Participation'] as $type)
                            <option value="{{ $type }}" {{ old('assessment_type', $grade->assessment_type) == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                    @error('assessment_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Score (0-100)</label>
                    <input type="number" name="score" id="score" min="0" max="100" step="0.01" 
                           value="{{ old('score', $grade->score) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                           required>
                    @error('score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (%)</label>
                    <input type="number" name="weight" id="weight" min="0" max="100" step="0.01" 
                           value="{{ old('weight', $grade->weight) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" 
                           required>
                    @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                    <textarea name="comments" id="comments" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('comments', $grade->comments) }}</textarea>
                    @error('comments')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('grades.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Update Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const assessmentTypes = {
        'Assignment': { defaultWeight: 15 },
        'Quiz': { defaultWeight: 10 },
        'Midterm': { defaultWeight: 25 },
        'Final': { defaultWeight: 35 },
        'Project': { defaultWeight: 20 },
        'Participation': { defaultWeight: 5 }
    };

    const assessmentSelect = document.getElementById('assessment_type');
    const weightInput = document.getElementById('weight');

    assessmentSelect.addEventListener('change', function() {
        const selectedType = this.value;
        if (selectedType in assessmentTypes) {
            weightInput.value = assessmentTypes[selectedType].defaultWeight;
        }
    });
});
</script>
@endpush
@endsection
