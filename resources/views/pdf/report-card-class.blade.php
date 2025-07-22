<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Cards for {{ $classSection->name }}</title>
    <style>
        /* Your existing CSS styles remain the same */
        @page { margin: 25px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 11px; }
        .report-card { page-break-after: always; }
        .report-card:last-child { page-break-after: avoid; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header h2 { margin: 5px 0; font-size: 14px; font-weight: normal; color: #555; }
        .student-info { margin-bottom: 20px; line-height: 1.4; }
        .student-info table { width: 100%; }
        .student-info td { padding: 1px 0; }
        h3 { font-size: 14px; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-top: 20px; }
        .content-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .content-table th { background-color: #f2f2f2; font-weight: bold; }
        .summary-row td { font-weight: bold; background-color: #f9f9f9; }
        .subject-header { background-color: #e9ecef; font-weight: bold; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 40px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>

    {{-- === THE MAIN LOOP IS NOW UPDATED === --}}
    @foreach ($finalStudentData as $studentData)
        @php
            // Extract the student model and their rank from the data we prepared
            $student = $studentData['student'];
            $rank = $studentData['rank'];

            // Prepare the summary data for this specific student
            $resultsBySubject = $student->results->groupBy('assessment.subject.name');
            $reportData = collect();
            foreach ($resultsBySubject as $subjectName => $subjectResults) {
                if ($subjectName) {
                    $reportData->put($subjectName, [
                        'results' => $subjectResults,
                        'final_grade' => $subjectResults->avg('score')
                    ]);
                }
            }
        @endphp

        <div class="report-card">
            <div class="header">
                <h1>Your School Name</h1>
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
                        <td>{{ $classSection->name ?? 'N/A' }}</td>
                        <td><strong>Position in Class:</strong></td>
                        {{-- === DISPLAY THE RANK HERE === --}}
                        <td><strong>{{ $rank }} out of {{ count($finalStudentData) }}</strong></td>
                    </tr>
                </table>
            </div>

            {{-- The rest of the report card remains the same, using the $reportData --}}
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
                        <tr><td colspan="3" style="text-align: center;">No summary data available.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h3>Detailed Assessment Breakdown</h3>
            <table class="content-table">
                 <thead>
                    <tr>
                        <th style="width: 20%;">Subject</th>
                        <th style="width: 20%;">Teacher</th>
                        <th style="width: 35%;">Assessment</th>
                        <th style="width: 25%;">Score</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->results->sortBy('assessment.subject.name') as $result)
                        <tr>
                            <td>{{ $result->assessment->subject->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $subjectPivot = $classSection->subjects->firstWhere('id', $result->assessment->subject_id);
                                    $teacherId = $subjectPivot->pivot->teacher_id ?? null;
                                    echo $teachers->get($teacherId)->name ?? 'N/A';
                                @endphp
                            </td>
                            <td>{{ $result->assessment->name ?? 'N/A' }}</td>
                            <td>{{ $result->score ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center;">No academic results have been recorded for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer">
                <p>Head Master's Signature: _________________________</p>
            </div>
        </div>
    @endforeach

</body>
</html>