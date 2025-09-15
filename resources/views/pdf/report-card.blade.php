<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ranked Report Cards for {{ $classSection->name }}</title>
    <style>
        /* A simpler, more robust stylesheet optimized for dompdf */
        body {
            font-family: DejaVu Sans, sans-serif; /* Use a font that supports more characters */
            font-size: 11px;
            color: #333;
        }
        @page {
            margin: 40px;
        }
        .page-break {
            page-break-after: always;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-text {
            text-align: center;
        }
        .header-text h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-text h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .student-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .student-info-table th, .student-info-table td {
            border: 1px solid #999;
            padding: 5px;
            text-align: left;
        }
        .student-info-table th {
            font-weight: bold;
            width: 15%;
            background-color: #e9e9e9;
        }
        .performance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .performance-table thead th {
            background-color: #333;
            color: white;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #333;
        }
        .performance-table tbody td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }
        .performance-table .subject-name {
            text-align: left;
        }
        .comment-box {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #999;
            background-color: #f9f9f9;
        }
        .signature-table {
            width: 100%;
            margin-top: 60px;
        }
        .signature-table td {
            width: 50%;
            padding-top: 30px;
            border-top: 1px solid #000;
            text-align: center;
        }
    </style>
</head>
<body>
    @foreach ($reportData as $data)
        {{-- ========================================================================= --}}
        {{-- === THE DEFINITIVE FIX FOR IMAGES ======================================= --}}
        {{-- We now use a more reliable method to embed the images directly.      --}}
        {{-- ========================================================================= --}}
        @php
            $crestPath = public_path('images/school_crest.png');
            $crestData = base64_encode(file_get_contents($crestPath));
            $crestSrc = 'data:image/png;base64,' . $crestData;

            $logoPath = public_path('images/school_logo.png');
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            // --- THE DEFINITIVE FIX FOR THE TERM ---
            // We get the Term name from the first result available for the student.
            $termName = $data['results']->isNotEmpty() ? $data['results']->first()->assessment->term->name : 'N/A';
        @endphp

        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: left;"><img src="{{ $crestSrc }}" alt="Crest" style="width: 70px;"></td>
                <td style="width: 70%;" class="header-text">
                    <h1>Linda Secondary School</h1>
                    <h2>{{ $classSection->academicSession->name }} - Ranked Report Card</h2>
                </td>
                <td style="width: 15%; text-align: right;"><img src="{{ $logoSrc }}" alt="Logo" style="width: 70px;"></td>
            </tr>
        </table>
        
        <table class="student-info-table">
            <tr>
                <th>Student Name:</th>
                <td>{{ $data['student']->name }}</td>
                <th>Class:</th>
                <td>{{ $classSection->name }}</td>
            </tr>
            <tr>
                <th>Total Score:</th>
                <td>{{ $data['total'] }}</td>
                <th>Class Position:</th>
                <td style="font-weight: bold;">{{ $data['rank'] }} out of {{ count($reportData) }}</td>
            </tr>
            <tr>
                <th>Average Score:</th>
                <td>{{ number_format($data['average'], 2) }}%</td>
                <th>Term:</th>
                <td>{{ $termName }}</td>
            </tr>
        </table>

        <table class="performance-table">
            <thead>
                <tr>
                    <th class="subject-name" style="width: 35%;">Subject</th>
                    <th>Score</th>
                    <th>Subject Position</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['results']->sortBy('assessment.subject.name') as $result)
                    <tr>
                        <td class="subject-name">{{ $result->assessment->subject->name }}</td>
                        <td>{{ $result->score }}</td>
                        <td>{{ $result->subject_rank }} out of {{ $data['subject_student_counts'][$result->assessment->subject_id] ?? 'N/A' }}</td>
                        <td>
                            @php
                                $remark = 'Not Graded';
                                if ($classSection->gradingScale && $classSection->gradingScale->grades) {
                                    foreach ($classSection->gradingScale->grades->sortByDesc('min_score') as $grade) {
                                        if ($result->score >= $grade->min_score) {
                                            $remark = $grade->remark;
                                            break;
                                        }
                                    }
                                }
                                echo $remark;
                            @endphp
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No results have been recorded for this term.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="comment-box">
            <strong>Overall Comment:</strong> {{ $data['system_comment'] }}
        </div>

        <table class="signature-table">
            <tr>
                <td>Class Teacher's Signature</td>
                <td>Principal's Signature</td>
            </tr>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>