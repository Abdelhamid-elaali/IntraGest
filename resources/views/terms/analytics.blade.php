@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $term->name }} Analytics</h1>
        <a href="{{ route('terms.show', $term) }}" class="text-blue-600 hover:text-blue-800">Back to Term Details</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Overall Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Overall Statistics</h2>
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
                    <div class="text-sm text-gray-600">Average Grade</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_grade'], 1) }}</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">Pass Rate</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['pass_rate'], 1) }}%</div>
                </div>
            </div>
        </div>

        <!-- Grade Distribution -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Grade Distribution</h2>
            <div id="gradeDistributionChart" class="h-64"></div>
        </div>

        <!-- Top Performing Students -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Performing Students</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Score</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topStudents as $index => $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->student->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($student->average_score, 1) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subject Performance -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Subject Performance</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pass Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subjectPerformance as $subject)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $subject->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($subject->average_score, 1) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $passRate = $subject->grades_count > 0 
                                            ? ($subject->pass_rate / $subject->grades_count) * 100 
                                            : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-900">{{ number_format($passRate, 1) }}%</span>
                                        <div class="ml-2 w-24 h-2 bg-gray-200 rounded-full">
                                            <div class="h-2 bg-green-500 rounded-full" style="width: {{ $passRate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gradeDistribution = @json($stats['grade_distribution']);
        
        const options = {
            series: [{
                name: 'Students',
                data: [
                    gradeDistribution.A,
                    gradeDistribution.B,
                    gradeDistribution.C,
                    gradeDistribution.D,
                    gradeDistribution.F
                ]
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '55%',
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['A', 'B', 'C', 'D', 'F'],
                position: 'bottom',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Students'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " students"
                    }
                }
            },
            colors: ['#4F46E5']
        };

        const chart = new ApexCharts(document.querySelector("#gradeDistributionChart"), options);
        chart.render();
    });
</script>
@endpush
@endsection
