@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Import Users') }}</div>

                <div class="card-body">
                    <p class="mb-2">Upload a CSV or Excel file with the following columns in this exact order:</p>
                    <p><code>name, email, password, role</code></p>
                    <p class="text-muted mb-4"><small>The 'role' must be one of: 'admin', 'teacher', or 'student'.</small></p>

                    {{-- This checks for any validation errors and displays them --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label"><strong>Spreadsheet File</strong></label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                             <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Import Users</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection