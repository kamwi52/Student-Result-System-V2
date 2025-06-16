<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h2 { margin: 5px 0; font-size: 18px; }
        .student-info { margin-bottom: 20px; }
        .student-info table { width: 100%; border-collapse: collapse; }
        .student-info td { padding: 5px; }
        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .results-table th, .results-table td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        .results-table th { background-color: #f2f2f2; }
        .footer { text-align: center; margin-top: 40px; }
        .footer .grade { font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
    <h1>{{ $settings['school_name'] ?? config('app.name', 'Results Portal') }}</h1>
    <h2>Official Report Card</h2>
    ...
</div>
        <div class="student-info">
            <table>
                <tr>
                    <td><strong>Student Name:</strong></td>
                    <td>{{ $student->name }}</td>
                    <td><strong>Class:</strong></td>
                    <td>{{ $class->subject->name }} - {{ $class->name }}</td>
                </tr>
                <tr>
                    <td><strong>Teacher:</strong></td>
                    <td>{{ $class->teacher->name }}</td>
                    <td><strong>Date Generated:</strong></td>
                    <td>{{ date('Y-m-d') }}</td>
                </tr>
            </table>
        </div>

        <h4>Assessment Results</h4>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Assessment</th>
                    <th>Max Marks</th>
                    <th>Marks Obtained</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results_data['assessments'] as $assessment)
                    @php
                        $result = $results_data['results']->firstWhere('assessment_id', $assessment->id);
                        $marks = $result ? $result->marks_obtained : 'N/A';
                        $percentage = ($result && $assessment->max_marks > 0) ? round(($result->marks_obtained / $assessment->max_marks) * 100, 1) . '%' : 'N/A';
                    @endphp
                    <tr>
                        <td>{{ $assessment->name }}</td>
                        <td>{{ $assessment->max_marks }}</td>
                        <td>{{ $marks }}</td>
                        <td>{{ $percentage }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Final Result
            <div class="grade">
                Final Percentage: {{ $results_data['final_percentage'] }}% |
                Letter Grade: {{ $results_data['final_letter_grade'] }}
            </div>
        </div>
    </div>
</body>
</html>