@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">{{ $settings['school_name'] ?? config('app.name', 'Results Portal') }}</h1>
                    {{ __('Student Dashboard') }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Your Enrolled Classes & Results</h4>

                    @if(!isset($enrolled_classes) || $enrolled_classes->isEmpty())
                        <p>You are not currently enrolled in any classes.</p>
                    @else
                        @foreach($enrolled_classes as $class)
                            @php
                                // Get the results for this specific class from our data array
                                $class_results_data = $results_data[$class->id] ?? null;
                            @endphp
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ $class->subject->name }} - {{ $class->name }}</strong>
                                    <span class="text-muted">(Taught by: {{ $class->teacher->name }})</span>
                                </div>
                                <div class="card-body">
                                    @if($class_results_data)
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    @foreach($class_results_data['assessments'] as $assessment)
                                                        <th class="text-center">{{ $assessment->name }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @foreach($class_results_data['assessments'] as $assessment)
                                                        @php
                                                            $result = $class_results_data['results']->firstWhere('assessment_id', $assessment->id);
                                                        @endphp
                                                        <td class="text-center">
                                                            @if($result)
                                                                {{ $result->marks_obtained }} / {{ $assessment->max_marks }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <p>Results for this class are not yet available.</p>
                                    @endif
                                </div>
                                <div class="card-footer bg-light">
                                    <strong>
                                        Final Grade:
                                        <span class="text-primary fw-bold">{{ $class_results_data['final_percentage'] ?? 'N/A' }}%</span>
                                        <span class="badge bg-primary ms-2 fs-6 align-middle">{{ $class_results_data['final_letter_grade'] ?? '' }}</span>

                                        @if($class_results_data)
                                            <a href="{{ route('report-card.download', ['class' => $class->id, 'student' => Auth::user()->id]) }}" class="btn btn-secondary btn-sm float-end">Download Report Card (PDF)</a>
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection