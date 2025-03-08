@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $term->name }}</h1>
        <div class="flex space-x-4">
            <a href="{{ route('terms.edit', $term) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Term
            </a>
            <a href="{{ route('terms.index') }}" class="text-blue-600 hover:text-blue-800">Back to Terms</a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Term Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Term Details</h2>
            <div class="space-y-4">
                <div>
                    <span class="text-gray-600">Type:</span>
                    <span class="ml-2 font-medium">{{ ucfirst($term->type) }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Academic Year:</span>
                    <span class="ml-2 font-medium">{{ $term->academic_year }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Status:</span>
                    <span class="ml-2">
                        @php
                            $now = now();
                            $status = match(true) {
                                $now < $term->start_date => 'Upcoming',
                                $now <= $term->end_date => 'Active',
                                default => 'Completed'
                            };
                            $statusColor = match($status) {
                                'Upcoming' => 'blue',
                                'Active' => 'green',
                                'Completed' => 'gray'
                            };
                        @endphp
                        <span class="px-2 py-1 text-xs bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full">
                            {{ $status }}
                        </span>
                        @if($term->is_current)
                            <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Current Term</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Important Dates -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Important Dates</h2>
            <div class="space-y-4">
                <div>
                    <span class="text-gray-600">Start Date:</span>
                    <span class="ml-2 font-medium">{{ $term->start_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">End Date:</span>
                    <span class="ml-2 font-medium">{{ $term->end_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Registration Deadline:</span>
                    <span class="ml-2 font-medium">{{ $term->registration_deadline->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Drop Deadline:</span>
                    <span class="ml-2 font-medium">{{ $term->drop_deadline->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Grading Deadline:</span>
                    <span class="ml-2 font-medium">{{ $term->grading_deadline->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Term Statistics</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">Total Subjects</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_subjects'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">Total Students</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">Total Grades</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_grades'] }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">Average Grade</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_grade'], 1) }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg col-span-2">
                    <div class="text-sm text-gray-600">Pass Rate</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['pass_rate'], 1) }}%</div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Links</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('terms.subjects', $term) }}" 
                    class="flex items-center justify-center p-4 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">
                    <span>View Subjects</span>
                </a>
                <a href="{{ route('terms.enrollments', $term) }}" 
                    class="flex items-center justify-center p-4 bg-green-50 text-green-700 rounded-lg hover:bg-green-100">
                    <span>View Enrollments</span>
                </a>
                <a href="{{ route('terms.grades', $term) }}" 
                    class="flex items-center justify-center p-4 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100">
                    <span>View Grades</span>
                </a>
                <a href="{{ route('terms.analytics', $term) }}" 
                    class="flex items-center justify-center p-4 bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100">
                    <span>View Analytics</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
