<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Absence Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .meta-info {
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        td {
            padding: 8px;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .summary h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        .summary-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .summary-item h3 {
            font-size: 14px;
            margin: 0 0 5px 0;
        }
        .summary-item p {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Absence Management Report</h1>
        <p>IntraGest Management System</p>
    </div>

    <div class="meta-info">
        <p><strong>Generated:</strong> {{ $generated_at }}</p>
        <p><strong>Generated by:</strong> {{ $generated_by }}</p>
        <p><strong>Total Records:</strong> {{ count($absences) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absences as $absence)
            <tr>
                <td>{{ $absence->student->name ?? 'Unknown' }}</td>
                <td>{{ ucfirst($absence->type) }}</td>
                <td>{{ $absence->start_date->format('M d, Y') }}</td>
                <td>{{ $absence->end_date->format('M d, Y') }}</td>
                <td>{{ $absence->getDurationInDays() }} day(s)</td>
                <td>{{ ucfirst($absence->status) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($absence->reason, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary Statistics</h2>
        
        <h3>Status Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCount = count($absences);
                    $statusCounts = $absences->groupBy('status')->map->count();
                @endphp
                
                @foreach($statusCounts as $status => $count)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ round(($count / $totalCount) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <h3>Type Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $typeCounts = $absences->groupBy('type')->map->count();
                @endphp
                
                @foreach($typeCounts as $type => $count)
                <tr>
                    <td>{{ ucfirst($type) }}</td>
                    <td>{{ $count }}</td>
                    <td>{{ round(($count / $totalCount) * 100, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <h3>Students with Most Absences</h3>
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Total Absences</th>
                    <th>Total Days</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $studentAbsences = $absences->groupBy('student_id');
                    $studentStats = [];
                    
                    foreach($studentAbsences as $studentId => $studentAbsences) {
                        $student = $studentAbsences->first()->student;
                        if ($student) {
                            $totalDays = 0;
                            foreach($studentAbsences as $absence) {
                                if ($absence->type !== 'late') {
                                    $totalDays += $absence->getDurationInDays();
                                }
                            }
                            
                            $studentStats[] = [
                                'name' => $student->name,
                                'count' => count($studentAbsences),
                                'days' => $totalDays
                            ];
                        }
                    }
                    
                    usort($studentStats, function($a, $b) {
                        return $b['count'] <=> $a['count'];
                    });
                    
                    $topStudents = array_slice($studentStats, 0, 5);
                @endphp
                
                @foreach($topStudents as $student)
                <tr>
                    <td>{{ $student['name'] }}</td>
                    <td>{{ $student['count'] }}</td>
                    <td>{{ $student['days'] }} day(s)</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>This is an automatically generated report. Generated on {{ now()->format('F d, Y H:i:s') }}</p>
        <p>&copy; {{ date('Y') }} IntraGest Management System. All rights reserved.</p>
    </div>
</body>
</html>
