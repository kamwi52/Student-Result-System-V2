<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Report Cards</title>
    <style>
        /* A base font that is commonly available in PDF engines */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* The main container for each student's report card */
        .page-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* The CSS rule that creates a new page after each report */
        .page-break {
            page-break-after: always;
        }

        /* School Header styling */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .report-header h2 {
            margin: 5px 0;
            font-size: 18px;
            font-weight: normal;
        }
        .report-header h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }

        /* Table styling */
        .info-table, .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 25%;
        }

        .results-table th, .results-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .results-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #000;
        }
        .results-table .text-center {
            text-align: center;
        }
        .results-table .text-right {
            text-align: right;
        }

        /* Summary section */
        .summary-section {
            margin-top: 30px;
        }
        .summary-section table {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }
        .summary-section td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .summary-section td:first-child {
            font-weight: bold;
        }

        /* Comments/Footer section */
        .footer-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }
        .footer-section p {
            margin: 5px 0;
        }

        /* A utility to clear floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    {{-- The main loop that iterates through each student passed from the Job --}}
    @foreach ($students as $student)
        
        <div class="page-container">

            {{-- 1. Report Header --}}
            <div class="report-header">
                <h1>Your International School Name</h1>
                <h2>Student Report Card</h2>
                <h3>{{ $academicSession->name ?? 'Academic Session' }}</h3>
            </div>

            {{-- 2. Student Information Table --}}
            <table class="info-table">
                <tr>
                    <td>Student Name:</td>
                    <td>{{ $student->user->name ?? 'N/A' }}</td>
                    <td>Student ID:</td>
                    <td>{{ $student->id_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Class:</td>
                    <td>{{ $student->classSection->name ?? 'N/A' }}</td>
                    <td>Date Generated:</td>
                    <td>{{ now()->format('F j, Y') }}</td>
                </tr>
            </table>

            {{-- 3. Academic Results Table --}}
            <h4 style="font-size: 16px; margin-bottom: 10px;">Academic Performance</h4>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Assessment</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Grade</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->results->sortBy('assessment.subject.name') as $result)
                        <tr>
                            <td>{{ $result->assessment->subject->name ?? 'N/A' }}</td>
                            <td>{{ $result->assessment->assessmentType->name ?? 'N/A' }} ({{ $result->assessment->name }})</td>
                            <td class="text-center">{{ $result->score ?? 'N/A' }}</td>
                            <td class="text-center">{{ $result->gradingScale->grade ?? 'N/A' }}</td>
                            <td>{{ $result->gradingScale->remark ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No results have been recorded for this student.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- 4. Summary & Comments Section --}}
            <div class="summary-section clearfix">
                {{-- NOTE: For complex calculations like averages and totals, it is BEST PRACTICE 
                     to create accessor methods on your Student or Result models instead of doing logic in the view.
                     This is a simplified example. --}}
                <table>
                    <tr>
                        <td>Total Score:</td>
                        <td>{{ $student->results->sum('score') }}</td>
                    </tr>
                    <tr>
                        <td>Average (%):</td>
                        <td>
                            @if($student->results->count() > 0)
                                {{ number_format($student->results->avg('score'), 2) }}%
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footer-section">
                <p><strong>Class Teacher's Comments:</strong></p>
                <div style="border-bottom: 1px solid #ccc; padding: 10px 0; min-height: 20px;"></div>
                <p style="margin-top: 30px;"><strong>Principal's Signature:</strong></p>
                <div style="border-bottom: 1px solid #ccc; padding: 10px 0; min-height: 20px;"></div>
            </div>

        </div>

        {{-- This Blade directive adds a page break after every student except the last one. --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

</body>
</html>