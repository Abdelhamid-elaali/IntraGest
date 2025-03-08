@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Grade Analytics</h1>
        <a href="{{ route('grades.index') }}" class="text-gray-600 hover:text-gray-900">
            Back to Grades
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Subject Performance -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Subject Performance</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Score</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pass Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($subjectPerformance as $subject)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('grades.subject', $subject) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $subject->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ number_format($subject->average_score, 1) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($subject->pass_rate / $subject->grades_count) * 100 }}%"></div>
                                        </div>
                                        <span class="ml-2">{{ number_format(($subject->pass_rate / $subject->grades_count) * 100, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Students -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Performing Students</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topStudents as $student)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('grades.student', $student) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $student->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ number_format($student->average_score, 1) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grade Distribution Chart -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Grade Distribution</h2>
        <div class="h-64" id="gradeDistribution"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const options = {
        chart: {
            type: 'bar',
            height: 250
        },
        series: [{
            name: 'Average Score',
            data: @json($subjectPerformance->pluck('average_score'))
        }],
        xaxis: {
            categories: @json($subjectPerformance->pluck('name')),
            labels: {
                rotate: -45,
                trim: true,
                maxHeight: 120
            }
        },
        yaxis: {
            title: {
                text: 'Average Score'
            },
            max: 100
        },
        colors: ['#2563EB'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
                columnWidth: '60%'
            }
        },
        dataLabels: {
            enabled: false
        },
        title: {
            text: 'Subject Performance Overview',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 'bold'
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#gradeDistribution"), options);
    chart.render();
});
</script>
@endpush
@endsection
