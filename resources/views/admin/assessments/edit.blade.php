@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Edit Assessment</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('admin.assessments.update', $assessment->id) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Assessment Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $assessment->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="academic_session_id" class="form-label">Academic Session</label>
                    <select name="academic_session_id" id="academic_session_id" class="form-control" required>
                        @foreach($academic_sessions as $session)
                            <option value="{{ $session->id }}" {{ old('academic_session_id', $assessment->academic_session_id) == $session->id ? 'selected' : '' }}>
                                {{ $session->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="max_marks" class="form-label">Maximum Marks</label>
                    <input type="number" class="form-control" id="max_marks" name="max_marks" value="{{ old('max_marks', $assessment->max_marks) }}" required>
                </div>
                <div class="mb-3">
                    <label for="weightage" class="form-label">Weightage (e.g., 0.3 for 30%)</label>
                    <input type="text" class="form-control" id="weightage" name="weightage" value="{{ old('weightage', $assessment->weightage) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Assessment</button>
                <a href="{{ route('admin.assessments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection