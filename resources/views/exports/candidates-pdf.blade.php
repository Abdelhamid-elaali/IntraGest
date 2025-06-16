<!DOCTYPE html>
<html>
<head>
    <title>Candidates List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            padding: 10px 8px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f9fe;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
        }
        .info-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .male {
            background-color: #d4e6f7;
            color: #2980b9;
        }
        .female {
            background-color: #f7d4e6;
            color: #c2185b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Candidates List</h1>
        <div class="subtitle">Generated on {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Details</th>
                <th>Education</th>
                <th>Family</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidates as $candidate)
            <tr>
                <td>
                    <strong>{{ $candidate->first_name }} {{ $candidate->last_name }}</strong><br>
                    <span class="info-badge {{ $candidate->gender === 'male' ? 'male' : 'female' }}">
                        {{ ucfirst($candidate->gender) }}
                    </span><br>
                    DOB: {{ $candidate->birth_date ? $candidate->birth_date->format('Y-m-d') : 'N/A' }}
                </td>
                <td>
                    {{ $candidate->email }}<br>
                    {{ $candidate->phone }}<br>
                    {{ $candidate->address }}, {{ $candidate->city }}
                </td>
                <td>
                    Nationality: {{ $candidate->nationality }}<br>
                    Distance: {{ $candidate->distance }} km<br>
                    Income: {{ ucfirst(str_replace('_', ' ', $candidate->income_level)) }}
                </td>
                <td>
                    Level: {{ $candidate->educational_level }}<br>
                    Year: {{ $candidate->academic_year }}<br>
                    Specialization: {{ $candidate->specialization }}
                </td>
                <td>
                    Status: {{ $candidate->family_status }}<br>
                    Siblings: {{ $candidate->siblings_count }}<br>
                    Guardian: {{ $candidate->guardian_first_name }} {{ $candidate->guardian_last_name }}
                </td>
                <td>
                    Applied: {{ $candidate->application_date ? $candidate->application_date->format('Y-m-d') : 'N/A' }}<br>
                    Status: <strong>{{ ucfirst($candidate->status) }}</strong><br>
                    Score: {{ $candidate->score ?? 'N/A' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total Candidates: {{ count($candidates) }} | Confidential Document
    </div>
</body>
</html>