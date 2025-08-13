<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ranked Report Cards</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .page-container { width: 100%; margin: 0 auto; padding: 15px; }
        .page-break { page-break-after: always; }
        .report-header h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .report-header h2 { margin: 5px 0; font-size: 16px; font-weight: normal; color: #555; }
        .info-table, .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px; border: 1px solid #ddd; }
        .info-table td:first-child { font-weight: bold; width: 20%; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .results-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .comments-section { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background-color: #fdfdfd; border-radius: 3px; }
        .signature-section { margin-top: 40px; font-size: 10px; clear: both; }
    </style>
</head>
<body>

    @php
        $classSection->load('gradingScale.grades');
        $gradingScale = $classSection->gradingScale;

        if (!function_exists('getRemarkForScore')) {
            function getRemarkForScore($score, $gradingScale) {
                if (!$gradingScale) return '';
                $grade = $gradingScale->grades->first(function ($grade) use ($score) {
                    return $score >= $grade->min_score && $score <= $grade->max_score;
                });
                return $grade ? $grade->remark : '';
            }
        }
    @endphp

    @foreach ($reportData as $data)
        <div class="page-container">
            <div class="report-header" style="margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr>
                        <td style="width: 20%; text-align: left; vertical-align: middle;"><img src="{{ public_path('assets/coat_of_arms.png') }}" alt="Coat of Arms" style="max-height: 80px;"></td>
                        <td style="width: 60%; text-align: center; vertical-align: middle;">
                            <h1>Linda Secondary School</h1>
                            <h2>{{ optional($classSection->academicSession)->name ?? '' }} - Ranked Report Card</h2>
                        </td>
                        <td style="width: 20%; text-align: right; vertical-align: middle;"><img src="{{ public_path('assets/school_logo.png') }}" alt="School Logo" style="max-height: 80px;"></td>
                    </tr>
                </table>
            </div>

            <table class="info-table">
                <tr>
                    <td>Student Name:</td><td>{{ $data['student']->name ?? 'N/A' }}</td>
                    <td>Class:</td><td>{{ $classSection->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Total Score:</td><td>{{ $data['total'] }}</td>
                    <td>Class Rank:</td><td><strong>{{ $data['rank'] }} out of {{ count($reportData) }}</strong></td>
                </tr>
                 <tr>
                    <td>Average Score:</td><td colspan="3">{{ number_format($data['average'], 2) }}%</td>
                </tr>
            </table>

            <h4 style="font-size: 14px; margin-bottom: 5px;">Subject-wise Performance</h4>
            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Subject</th>
                        <th class="text-center" style="width: 15%;">Score</th>
                        <th class="text-center" style="width: 15%;">Subject Rank</th>
                        <th style="width: 25%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data['results']->sortBy(fn($result) => optional(optional($result->assessment)->subject)->name) as $result)
                        @php
                            // === FIX: Get the correct subject ID and student count for this subject ===
                            $subjectId = optional(optional($result->assessment)->subject)->id;
                            $subjectCount = $data['subject_student_counts'][$subjectId] ?? count($reportData);
                        @endphp
                        <tr>
                            <td>{{ optional(optional($result->assessment)->subject)->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $result->score ?? 'N/A' }}</td>
                            {{-- === FIX: Display the correct count for this specific subject === --}}
                            <td class="text-center">{{ $result->subject_rank }} out of {{ $subjectCount }}</td>
                            <td>{{ getRemarkForScore($result->score, $gradingScale) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No results found for this term.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="comments-section">
                <p style="margin: 0;"><strong>Overall Comment:</strong> {{ $data['system_comment'] ?? 'No comment available.' }}</p>
            </div>
            
            <div class="signature-section">
                <div style="width: 45%; float: left;">
                    <p><strong>Class Teacher's Comments:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
                </div>
                 <div style="width: 45%; float: right;">
                    <p><strong>Principal's Signature:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 40px;"></div>
                </div>
            </div>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>