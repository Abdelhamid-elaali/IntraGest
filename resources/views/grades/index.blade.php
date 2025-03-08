@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Grades Management</h1>
            <div class="mt-2 flex space-x-4">
                <a href="{{ route('grades.analytics') }}" class="text-blue-500 hover:text-blue-600">
                    View Analytics
                </a>
            </div>
        </div>
        @if(auth()->user()->hasRole('teacher'))
            <a href="{{ route('grades.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Record New Grades
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($view_type === 'student')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($grades as $grade)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $grade->subject->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $grade->academicTerm->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $grade->assessment_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($grade->weight, 1) }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium {{ $grade->score >= 90 ? 'text-green-600' : ($grade->score >= 80 ? 'text-blue-600' : ($grade->score >= 70 ? 'text-yellow-600' : ($grade->score >= 60 ? 'text-orange-600' : 'text-red-600'))) }}">
                                        {{ number_format($grade->score, 1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $grade->letter_grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $grade->comments }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($grades as $grade)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('grades.student', $grade->student) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $grade->student->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('grades.subject', $grade->subject) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $grade->subject->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $grade->academicTerm->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $grade->assessment_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($grade->weight, 1) }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium {{ $grade->score >= 90 ? 'text-green-600' : ($grade->score >= 80 ? 'text-blue-600' : ($grade->score >= 70 ? 'text-yellow-600' : ($grade->score >= 60 ? 'text-orange-600' : 'text-red-600'))) }}">
                                        {{ number_format($grade->score, 1) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $grade->letter_grade }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="{{ route('grades.show', $grade) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    @can('update', $grade)
                                        <a href="{{ route('grades.edit', $grade) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    @endcan
                                    @can('delete', $grade)
                                        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this grade? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $grades->links() }}
        </div>
    </div>
</div>
@endsection
