@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Manage Enrollment for: <strong>{{ $classSection->name }}</strong></h4>
                    <small class="text-muted">
                        {{ $classSection->subject->name ?? 'N/A' }} | 
                        {{ $classSection->academicSession->name ?? 'N/A' }} |
                        <span class="fw-bold">{{ $classSection->students_count }}</span> students enrolled
                    </small>
                </div>

                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.classes.enroll.index', $classSection) }}" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search students by name or email..." value="{{ $searchTerm }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.classes.enroll.store', $classSection) }}">
                        @csrf
                        <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                            @forelse ($allStudents as $student)
                                <label class="list-group-item">
                                    <input class="form-check-input me-1" 
                                           type="checkbox" 
                                           name="student_ids[]" 
                                           value="{{ $student->id }}"
                                           @if(in_array($student->id, $enrolledStudentIds)) checked @endif>
                                    {{ $student->name }} <span class="text-muted">- {{ $student->email }}</span>
                                </label>
                            @empty
                                <div class="alert alert-warning">No students found matching your search criteria.</div>
                            @endforelse
                        </div>
                        
                        {{-- Pagination Links for Students --}}
                        <div class="mt-4">
                            {{ $allStudents->links() }}
                        </div>
                    </div>
                    
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Enrollments</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection