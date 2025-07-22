<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card for {{ $student->name }}</title>
    <style>
        @page { margin: 25px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 16px; font-weight: normal; color: #555; }
        .student-info { margin-bottom: 20px; line-height: 1.5; }
        .student-info table { width: 100%; }
        .student-info td { padding: 1px 0; }
        h3 { font-size: 16px; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 25px; }
        .content-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .content-table th { background-color: #f2f2f2; font-weight: bold; }
        .summary-row td { font-weight: bold; background-color: #f9f9f9; }
        .subject-header { background-color: #e9ecef; font-weight: bold; font-size: 14px; }
        .page-break { page-break-after: always; }
        .footer { position: fixed; bottom: 0px; left: 0px; right: 0px; height: 40px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ccc; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Linda Secondary School</h1>
        <h2>Academic Report Card - {{ $academicSession }}</h2>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Student Name:</strong></td>
                <td style="width: 35%;">{{ $student->name }}</td>
                <td style="width: 15%;"><strong>Student ID:</strong></td>
                <td style="width: 35%;">{{ $student->id }}</td>
            </tr>
            <tr>
                <td><strong>Class:</strong></td>
                <td>{{ $student->classSection->name ?? 'N/A' }}</td>
                <td><strong>Date Issued:</strong></td>
                <td>{{ now()->format('F j, Y') }}</td>
            </tr>
        </table>
    </div>

    <h3>Academic Performance Summary</h3>
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 50%;">Subject</th>
                <th style="width: 25%;">Final Grade / Average</th>
                <th style="width: 25%;">Remark</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportData as $subjectName => $data)
                <tr class="summary-row">
                    <td>{{ $subjectName }}</td>
                    <td>{{ number_format($data['final_grade'], 2) }}</td>
                    <td>
                        @php
                            $grade = $data['final_grade'];
                            if ($grade >= 90) { echo 'Outstanding'; }
                            elseif ($grade >= 80) { echo 'Excellent'; }
                            elseif ($grade >= 70) { echo 'Very Good'; }
                            elseif ($grade >= 60) { echo 'Good'; }
                            elseif ($grade >= 50) { echo 'Satisfactory'; }
                            else { echo 'Needs Improvement'; }
                        @endphp
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No summary data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ==================================================================== --}}
    {{-- === NEW: DETAILED BREAKDOWN TABLE === --}}
    {{-- ==================================================================== --}}

    <h3>Detailed Assessment Breakdown</h3>
    <table class="content-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Assessment Name</th>
                <th>Assessment Type / Title</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportData as $subjectName => $data)
                {{-- Create a header row for each subject --}}
                <tr>
                    <td colspan="4" class="subject-header">{{ $subjectName }}</td>
                </tr>
                {{-- Loop through each result within that subject's data --}}
                @forelse ($data['results'] as $result)
                    <tr>
                        <td>{{-- This cell is intentionally empty for grouping --}}</td>
                        <td>{{ $result->assessment->name ?? 'N/A' }}</td>
                        <td>
                            {{-- Use the 'assignment' relationship we loaded. --}}
                            {{-- Check for 'title' or 'name' on the assignment model. --}}
                            {{ $result->assessment->assignment->title ?? ($result->assessment->assignment->name ?? 'General') }}
                        </td>
                        <td>{{ $result->score ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>{{-- Empty cell --}}</td>
                        <td colspan="3">No detailed results recorded for this subject.</td>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No academic results have been recorded for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an official document generated by the R-System. | Principal's Signature: _________________________</p>
    </div>

</body>
</html>