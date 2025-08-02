<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ranked Report Cards</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .page-container { width: 100%; margin: 0 auto; padding: 15px; }
        .page-break { page-break-after: always; }
        .report-header { text-align: center; margin-bottom: 20px; }
        .report-header h1 { margin: 0; font-size: 22px; }
        .report-header h2 { margin: 5px 0; font-size: 16px; font-weight: normal; }
        .info-table, .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px; border: 1px solid #ddd; }
        .info-table td:first-child { font-weight: bold; width: 20%; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .results-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    @php
        // Eager load the grades for the class's grading scale once to avoid N+1 query problems
        $classSection->load('gradingScale.grades');
        $gradingScale = $classSection->gradingScale;

        // === FIX: The function is now defined OUTSIDE the loop ===
        // This ensures it is only declared once.
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
            <div class="report-header">
                <h1>Your School Name</h1>
                <h2>{{ optional($classSection->academicSession)->name ?? '' }} - Ranked Report Card</h2>
            </div>

            <table class="info-table">
                <tr>
                    <td>Student Name:</td>
                    <td>{{ $data['student']->name ?? 'N/A' }}</td>
                    <td>Class:</td>
                    <td>{{ $classSection->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Total Score:</td>
                    <td>{{ $data['total'] }}</td>
                    <td>Class Rank:</td>
                    <td><strong>{{ $data['rank'] }} out of {{ count($reportData) }}</strong></td>
                </tr>
                 <tr>
                    <td>Average Score:</td>
                    <td colspan="3">{{ number_format($data['average'], 2) }}%</td>
                </tr>
            </table>

            <h4 style="font-size: 14px; margin-bottom: 5px;">Subject-wise Performance</h4>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th class="text-center">Score</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data['results']->sortBy(fn($result) => optional(optional($result->assessment)->subject)->name) as $result)
                        <tr>
                            <td>{{ optional(optional($result->assessment)->subject)->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $result->score ?? 'N/A' }}</td>
                            {{-- This now safely calls the function defined above --}}
                            <td>{{ getRemarkForScore($result->score, $gradingScale) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No results found for this term.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <div style="margin-top: 30px; font-size: 10px;">
                <div style="width: 45%; float: left;">
                    <p><strong>Class Teacher's Signature:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 30px;"></div>
                </div>
                 <div style="width: 45%; float: right;">
                    <p><strong>Principal's Signature:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 30px;"></div>
                </div>
            </div>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ranked Report Cards</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        .page-container { width: 100%; margin: 0 auto; padding: 15px; }
        .page-break { page-break-after: always; }
        .report-header { text-align: center; margin-bottom: 20px; }
        .report-header h1 { margin: 0; font-size: 22px; }
        .report-header h2 { margin: 5px 0; font-size: 16px; font-weight: normal; }
        .info-table, .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px; border: 1px solid #ddd; }
        .info-table td:first-child { font-weight: bold; width: 20%; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .results-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    @php
        // Eager load the grades for the class's grading scale once to avoid N+1 query problems
        $classSection->load('gradingScale.grades');
        $gradingScale = $classSection->gradingScale;

        // === FIX: The function is now defined OUTSIDE the loop ===
        // This ensures it is only declared once.
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
            <div class="report-header">
                <h1>Your School Name</h1>
                <h2>{{ optional($classSection->academicSession)->name ?? '' }} - Ranked Report Card</h2>
            </div>

            <table class="info-table">
                <tr>
                    <td>Student Name:</td>
                    <td>{{ $data['student']->name ?? 'N/A' }}</td>
                    <td>Class:</td>
                    <td>{{ $classSection->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Total Score:</td>
                    <td>{{ $data['total'] }}</td>
                    <td>Class Rank:</td>
                    <td><strong>{{ $data['rank'] }} out of {{ count($reportData) }}</strong></td>
                </tr>
                 <tr>
                    <td>Average Score:</td>
                    <td colspan="3">{{ number_format($data['average'], 2) }}%</td>
                </tr>
            </table>

            <h4 style="font-size: 14px; margin-bottom: 5px;">Subject-wise Performance</h4>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th class="text-center">Score</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data['results']->sortBy(fn($result) => optional(optional($result->assessment)->subject)->name) as $result)
                        <tr>
                            <td>{{ optional(optional($result->assessment)->subject)->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $result->score ?? 'N/A' }}</td>
                            {{-- This now safely calls the function defined above --}}
                            <td>{{ getRemarkForScore($result->score, $gradingScale) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No results found for this term.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <div style="margin-top: 30px; font-size: 10px;">
                <div style="width: 45%; float: left;">
                    <p><strong>Class Teacher's Signature:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 30px;"></div>
                </div>
                 <div style="width: 45%; float: right;">
                    <p><strong>Principal's Signature:</strong></p>
                    <div style="border-bottom: 1px solid #333; margin-top: 30px;"></div>
                </div>
            </div>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>