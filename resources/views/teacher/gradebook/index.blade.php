@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5>Gradebook for: {{ $class->subject->name }} - {{ $class->name }}</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            @if($students->isEmpty())
                <p>No students are enrolled in this class. Please use the Admin Panel to enroll students.</p>
            @elseif($assessments->isEmpty())
                <p>No assessments have been created for this academic session. Please use the Admin Panel to create assessments.</p>
            @else
                {{-- The form tag wraps the entire gradebook table --}}
                <form action="{{ route('teacher.gradebook.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_section_id" value="{{ $class->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    @foreach($assessments as $assessment)
                                        <th class="text-center">{{ $assessment->name }}<br><small>(Max: {{ $assessment->max_marks }})</small></th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        @foreach($assessments as $assessment)
                                            <td>
                                                <input type="number" class="form-control"
                                                       name="results[{{ $student->id }}][{{ $assessment->id }}]"
                                                       value="{{ $results->get($student->id . '-' . $assessment->id)->marks_obtained ?? '' }}"
                                                       max="{{ $assessment->max_marks }}" min="0">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- The save button is outside the table but inside the form --}}
                    <button type="submit" class="btn btn-success mt-3">Save Grades</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection