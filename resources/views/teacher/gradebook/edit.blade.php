<!-- resources/views/teacher/gradebook/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12"> <!-- Made it wider to accommodate the table -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Gradebook for: {{ $class->name }}</h5>
                            <small class="text-muted">
                                Subject: {{ $class->subject->name }} | Session: {{ $class->academicSession->name }}
                            </small>
                        </div>
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-secondary btn-sm">Back to Dashboard</a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.gradebook.store', $class->id) }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" style="min-width: 200px;">Student Name</th>
                                        <!-- Dynamically create a column for each assessment -->
                                        @foreach ($assessments as $assessment)
                                            <th scope="col" class="text-center" style="min-width: 120px;">
                                                {{ $assessment->name }}
                                                <br>
                                                <small>(out of {{ $assessment->total_marks }})</small>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($class->students as $student)
                                        <tr>
                                            <td class="font-weight-bold">{{ $student->name }}</td>
                                            <!-- Create a corresponding input for each assessment -->
                                            @foreach ($assessments as $assessment)
                                                <td class="text-center">
                                                    <input type="number" step="0.01" min="0" max="{{ $assessment->total_marks }}"
                                                           name="results[{{ $student->id }}][{{ $assessment->id }}]"
                                                           value="{{ $results[$student->id . '-' . $assessment->id]->marks_obtained ?? '' }}"
                                                           class="form-control form-control-sm mx-auto"
                                                           style="width: 80px;"
                                                           placeholder="--">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($assessments) + 1 }}" class="text-center text-muted py-4">
                                                No students are enrolled in this class.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Save All Grades</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection