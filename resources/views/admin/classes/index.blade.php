@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            Manage Classes
            <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm">Add New Class</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Academic Session</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class_sections as $class_section)
                    <tr>
                        <td>{{ $class_section->name }}</td>
                        <td>{{ $class_section->subject->name }}</td>
                        <td>{{ $class_section->teacher->name }}</td>
                        <td>{{ $class_section->academicSession->name }}</td>
                        <td>
                            <a href="{{ route('admin.classes.edit', $class_section->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            {{-- We can add a delete form here later --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection