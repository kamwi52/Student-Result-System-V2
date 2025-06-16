@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Assessments</h5>
            <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary btn-sm">Add New Assessment</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Academic Session</th>
                        <th>Max Marks</th>
                        <th>Weightage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assessments as $assessment)
                        <tr>
                            <td>{{ $assessment->name }}</td>
                            <td>{{ $assessment->academicSession->name }}</td>
                            <td>{{ $assessment->max_marks }}</td>
                            <td>{{ $assessment->weightage * 100 }}%</td>
                            <td>
                                <a href="{{ route('admin.assessments.edit', $assessment->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.assessments.destroy', $assessment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No assessments found. Please add one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection