@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">My Assigned Classes</div>

                <div class="card-body">
                    @if($classes->isEmpty())
                        <p>You have not been assigned to any classes yet.</p>
                    @else
                        <ul class="list-group">
                            @foreach($classes as $class)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{-- Display the Subject name and the Class name --}}
                                    <div>
                                        <strong>{{ $class->subject->name }} - {{ $class->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $class->students->count() }} Students
                                        </small>
                                    </div>

                                    {{-- ============================================= --}}
                                    {{--  THIS IS THE CORRECTED LINK                --}}
                                    {{-- ============================================= --}}
                                    <a href="{{ route('teacher.gradebook.edit', $class->id) }}" class="btn btn-primary btn-sm">Manage Grades</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection