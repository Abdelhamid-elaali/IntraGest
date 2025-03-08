@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $term->name }} - Grades</h1>
        <div class="flex space-x-4">
            <a href="{{ route('grades.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Grade
            </a>
            <a href="{{ route('terms.show', $term) }}" class="text-blue-600 hover:text-blue-800">Back to Term Details</a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Letter Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($grades as $grade)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $grade->student->name }}</div>
                            <div class="text-sm text-gray-500">{{ $grade->student->student_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $grade->subject->name }}</div>
                            <div class="text-sm text-gray-500">{{ $grade->subject->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($grade->score, 1) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $letterColor = match($grade->letter_grade) {
                                    'A' => 'green',
                                    'B' => 'blue',
                                    'C' => 'yellow',
                                    'D' => 'orange',
                                    'F' => 'red',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs bg-{{ $letterColor }}-100 text-{{ $letterColor }}-800 rounded-full">
                                {{ $grade->letter_grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $grade->weight }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($grade->is_finalized)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Finalized</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('grades.show', $grade) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                @if(!$grade->is_finalized)
                                    <a href="{{ route('grades.edit', $grade) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                    <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this grade?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $grades->links() }}
    </div>
</div>
@endsection
