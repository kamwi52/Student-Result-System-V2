@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Subject Management</h2>
        <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">Create Subject</a>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th><th>Code</th><th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->code }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-secondary">Edit</a>
                                    <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">No subjects found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $subjects->links() }}</div>
        </div>
    </div>
</div>
@endsection