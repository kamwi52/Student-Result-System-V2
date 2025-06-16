@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit User: {{ $user->name }}</div>

                <div class="card-body">
                    {{-- Corrected the form's action route --}}
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @method('PUT')
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" value="{{ $user->name }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                                <option value="teacher" @if($user->role == 'teacher') selected @endif>Teacher</option>
                                <option value="student" @if($user->role == 'student') selected @endif>Student</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Role</button>
                        {{-- Corrected the "Cancel" button's route --}}
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection