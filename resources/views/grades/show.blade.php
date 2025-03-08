@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Grade Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('grades.edit', $grade) }}" class="text-blue-500 hover:text-blue-600">
                    Edit Grade
                </a>
                <a href="{{ route('grades.index') }}" class="text-gray-500 hover:text-gray-600">
                    Back to Grades
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Student and Subject Info -->
            <div class="border-b border-gray-200">
                <div class="grid grid-cols-2 gap-4 p-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Student</h3>
                        <p class="mt-1">
                            <a href="{{ route('grades.student', $grade->student) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $grade->student->name }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Subject</h3>
                        <p class="mt-1">
                            <a href="{{ route('grades.subject', $grade->subject) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $grade->subject->name }} ({{ $grade->subject->code }})
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grade Details -->
            <div class="border-b border-gray-200">
                <div class="grid grid-cols-3 gap-4 p-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Score</h3>
                        <p class="mt-1 text-2xl font-semibold {{ $grade->score >= 90 ? 'text-green-600' : ($grade->score >= 80 ? 'text-blue-600' : ($grade->score >= 70 ? 'text-yellow-600' : ($grade->score >= 60 ? 'text-orange-600' : 'text-red-600'))) }}">
                            {{ number_format($grade->score, 1) }}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Letter Grade</h3>
                        <p class="mt-1 text-2xl font-semibold">{{ $grade->letter_grade }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Weight</h3>
                        <p class="mt-1 text-2xl font-semibold">{{ number_format($grade->weight, 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Assessment Type</h3>
                        <p class="mt-1">{{ $grade->assessment_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Academic Term</h3>
                        <p class="mt-1">{{ $grade->academicTerm->name }}</p>
                    </div>
                    <div class="col-span-2">
                        <h3 class="text-sm font-medium text-gray-500">Comments</h3>
                        <p class="mt-1 text-gray-900">{{ $grade->comments ?: 'No comments provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="text-sm text-gray-500">
                    <p>Recorded by {{ $grade->grader->name }} on {{ $grade->created_at->format('M d, Y \a\t h:i A') }}</p>
                    @if($grade->updated_at->gt($grade->created_at))
                        <p class="mt-1">Last updated on {{ $grade->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    @endif
                </div>
            </div>

            <!-- Delete Grade -->
            <div class="border-t border-gray-200 px-6 py-4">
                <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="flex justify-end">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this grade? This action cannot be undone.')">
                        Delete Grade
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
