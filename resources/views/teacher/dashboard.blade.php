@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- SUCCESS MESSAGE ALERT --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Welcome Header -->
            <div class="card mb-4">
                <div class="card-header">{{ __('Teacher Dashboard') }}</div>
                <div class="card-body">
                    <h3 class="h4">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">
                        You are assigned to <strong>{{ $stats['total_classes'] }}</strong> classes with a total of <strong>{{ $stats['total_students'] }}</strong> students this session.
                    </p>
                </div>
            </div>

            <!-- Class List -->
            <div class="card">
                <div class="card-header">{{ __('Your Assigned Classes') }}</div>
                <div class="card-body">
                    @forelse ($classes as $class)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                            <div>
                                <h5 class="mb-1 fw-bold text-primary">{{ $class->name }}</h5>
                                <small class="text-muted">{{ $class->subject->name }} · {{ $class->academicSession->name }}</small>
                                <div class="mt-2">
                                    <span class="badge bg-secondary">Students: {{ $class->students_count }}</span>
                                </div>
                            </div>
                            <div>
                                {{-- We use the corrected route name from our plan --}}
                                <a href="{{ route('teacher.grades.create', $class) }}" class="btn btn-primary">
                                    Enter Grades
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            You do not have any classes assigned for the current academic session.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection