@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $subject->name }} Grades</h1>
            <p class="text-gray-600">Subject Performance Overview</p>
        </div>
        <a href="{{ route('grades.analytics') }}" class="text-gray-600 hover:text-gray-900">
            Back to Analytics
        </a>
    </div>

    <!-- Performance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Class Average</h3>
            <p class="text-3xl font-bold text-blue-600">
                {{ number_format($grades->avg('score'), 1) }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Pass Rate</h3>
            <p class="text-3xl font-bold text-green-600">
                {{ number_format(($grades->where('score', '>=', 60)->count() / max($grades->count(), 1)) * 100, 1) }}%
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Highest Score</h3>
            <p class="text-3xl font-bold text-indigo-600">
                {{ number_format($grades->max('score'), 1) }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Lowest Score</h3>
            <p class="text-3xl font-bold text-red-600">
                {{ number_format($grades->min('score'), 1) }}
            </p>
        </div>
    </div>

    <!-- Grade Distribution -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Grade Distribution</h2>
            <div class="h-64" id="gradeDistribution"></div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Performance Trend</h2>
            <div class="h-64" id="performanceTrend"></div>
        </div>
    </div>

    <!-- Grade History -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Grade Records</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
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
                                <a href="{{ route('grades.student', $grade->student) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $grade->student->name }}
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grade Distribution Chart
    const gradeRanges = {
        'A (90-100)': {{ $grades->where('score', '>=', 90)->count() }},
        'B (80-89)': {{ $grades->whereBetween('score', [80, 89.99])->count() }},
        'C (70-79)': {{ $grades->whereBetween('score', [70, 79.99])->count() }},
        'D (60-69)': {{ $grades->whereBetween('score', [60, 69.99])->count() }},
        'F (0-59)': {{ $grades->where('score', '<', 60)->count() }}
    };

    const distributionOptions = {
        chart: {
            type: 'pie',
            height: 250
        },
        series: Object.values(gradeRanges),
        labels: Object.keys(gradeRanges),
        colors: ['#059669', '#2563EB', '#EAB308', '#EA580C', '#DC2626'],
        legend: {
            position: 'bottom'
        },
        title: {
            text: 'Grade Distribution',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        }
    };

    const distributionChart = new ApexCharts(document.querySelector("#gradeDistribution"), distributionOptions);
    distributionChart.render();

    // Performance Trend Chart
    const grades = @json($grades->map(function($grade) {
        return [
            $grade->created_at->format('Y-m-d'),
            $grade->score
        ];
    }));

    const trendOptions = {
        chart: {
            type: 'line',
            height: 250,
            zoom: {
                enabled: true
            }
        },
        series: [{
            name: 'Average Score',
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
            text: 'Score Trend Over Time',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        }
    };

    const trendChart = new ApexCharts(document.querySelector("#performanceTrend"), trendOptions);
    trendChart.render();
});
</script>
@endpush
@endsection
