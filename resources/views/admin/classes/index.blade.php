@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Class Management</h2>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">Create Class</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Class Name</th><th>Subject</th><th>Teacher</th><th>Session</th><th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->subject->name }}</td>
                                <td>{{ $class->teacher->name ?? 'N/A' }}</td>
                                <td>{{ $class->academicSession->name ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.classes.enroll.index', $class) }}" class="btn btn-sm btn-success">Enroll</a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-secondary ms-1">Edit</a>
                                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">No classes found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $classes->links() }}</div>
        </div>
    </div>
</div>
@endsection