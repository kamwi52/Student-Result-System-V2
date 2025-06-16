@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Manage Student Enrollment</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="class_id_selector" class="form-label">Select a Class to Manage Enrollment:</label>
                        <div class="input-group">
                            <select id="class_id_selector" class="form-control">
                                <option value="">-- Select a Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClass && $selectedClass->id == $class->id ? 'selected' : '' }}>
                                        {{ $class->subject->name }} - {{ $class->name }} (Taught by: {{ $class->teacher->name }})
                                    </option>
                                @endforeach
                            </select>
                            <button id="go-button" class="btn btn-primary">Go</button>
                        </div>
                    </div>

                    @if($selectedClass)
                        <hr>
                        <h5>Enrolling students for: <strong>{{ $selectedClass->subject->name }} - {{ $selectedClass->name }}</strong></h5>

                        <form action="{{ route('admin.enrollments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">Enroll</th>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td>
                                                {{-- This is the corrected checkbox line --}}
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" {{ in_array($student->id, $enrolledStudentIds) ? 'checked' : '' }}>
                                            </td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No students found. Please create student accounts first.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success">Update Enrollments</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('go-button').addEventListener('click', function() {
        var classId = document.getElementById('class_id_selector').value;
        if (classId) {
            window.location.href = '{{ route("admin.enrollments.index") }}?class_id=' + classId;
        } else {
            alert('Please select a class first.');
        }
    });
</script>
@endpush