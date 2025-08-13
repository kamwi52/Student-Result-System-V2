<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Summary - {{ $assessment->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header-table { width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 25px; }
        .header-table h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header-table h2 { margin: 5px 0; font-size: 16px; font-weight: normal; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 2px 0; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table th, .summary-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .summary-table th { background-color: #f2f2f2; font-weight: bold; }
        .summary-table tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: middle;">
                {{-- This one is working correctly --}}
                <img src="{{ public_path('assets/coat_of_arms.png') }}" alt="Coat of Arms" style="max-height: 80px;">
            </td>
            <td style="width: 60%; text-align: center; vertical-align: middle;">
                <h1>Linda Secondary School</h1>
                <h2>Marks Summary</h2>
            </td>
            <td style="width: 20%; text-align: right; vertical-align: middle;">
                {{-- === FIX: Ensured the filename is exactly 'school_logo.png' === --}}
                <img src="{{ public_path('assets/school_logo.png') }}" alt="School Logo" style="max-height: 80px;">
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 15%;"><strong>Assessment:</strong></td>
            <td style="width: 85%;">{{ $assessment->name }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong></td>
            <td>{{ $assessment->classSection->name }}</td>
        </tr>
        <tr>
            <td><strong>Subject:</strong></td>
            <td>{{ $assessment->subject->name }}</td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $assessment->assessment_date->format('F j, Y') }}</td>
        </tr>
        <tr>
            <td><strong>Max Marks:</strong></td>
            <td>{{ $assessment->max_marks }}</td>
        </tr>
    </table>

    <table class="summary-table">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th style="width: 60%;">Student Name</th>
                <th style="width: 30%;" class="text-center">Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->name }}</td>
                    <td class="text-center">{{ $results[$student->id]->score ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No students are enrolled in this class.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>