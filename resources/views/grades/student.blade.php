@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $student->name }}'s Grades</h1>
            <p class="text-gray-600">Student Performance Overview</p>
        </div>
        <a href="{{ route('grades.analytics') }}" class="text-gray-600 hover:text-gray-900">
            Back to Analytics
        </a>
    </div>

    <!-- Performance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Overall Average</h3>
            <p class="text-3xl font-bold text-blue-600">
                {{ number_format($grades->avg('score'), 1) }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Subjects Passed</h3>
            <p class="text-3xl font-bold text-green-600">
                {{ $grades->where('score', '>=', 60)->count() }}/{{ $grades->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Latest Grade</h3>
            <p class="text-3xl font-bold text-indigo-600">
                {{ $grades->first() ? number_format($grades->first()->score, 1) : 'N/A' }}
            </p>
        </div>
    </div>

    <!-- Grade History -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Grade History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Letter Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($grades as $grade)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('grades.subject', $grade->subject) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $grade->subject->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->academicTerm->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $grade->assessment_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $scoreColor = $grade->score >= 90 ? 'text-green-600' :
                                                ($grade->score >= 80 ? 'text-blue-600' :
                                                ($grade->score >= 70 ? 'text-yellow-600' :
                                                ($grade->score >= 60 ? 'text-orange-600' : 'text-red-600')));
                                @endphp
                                <span class="font-medium {{ $scoreColor }}">
                                    {{ number_format($grade->score, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $grade->letter_grade }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $grade->comments ?: 'No comments' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $grade->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $grades->links() }}
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Performance Trend</h2>
        <div class="h-64" id="performanceChart"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grades = @json($grades->map(function($grade) {
        return [
            $grade->created_at->format('Y-m-d'),
            $grade->score
        ];
    }));

    const options = {
        chart: {
            type: 'line',
            height: 250,
            zoom: {
                enabled: true
            }
        },
        series: [{
            name: 'Score',
            data: grades
        }],
        xaxis: {
            type: 'datetime',
            labels: {
                format: 'MMM dd, yyyy'
            }
        },
        yaxis: {
            title: {
                text: 'Score'
            },
            min: 0,
            max: 100
        },
        colors: ['#2563EB'],
        stroke: {
            curve: 'smooth',
            width: 2
        },
        markers: {
            size: 4
        },
        title: {
            text: 'Grade Performance Over Time',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#performanceChart"), options);
    chart.render();
});
</script>
@endpush
@endsection
