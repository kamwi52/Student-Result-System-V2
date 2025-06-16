@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Add New Class</div>
        <div class="card-body">
            {{-- Corrected the route name here --}}
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Class Name (e.g., Section A, Grade 9B)</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="subject_id" class="form-label">Subject</label>
                    <select name="subject_id" class="form-control" required>
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="user_id" class="form-label">Teacher</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="academic_session_id" class="form-label">Academic Session</label>
                    <select name="academic_session_id" class="form-control" required>
                        <option value="">-- Select Session --</option>
                        @foreach($academic_sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Class</button>
                {{-- Also corrected the cancel button route --}}
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection