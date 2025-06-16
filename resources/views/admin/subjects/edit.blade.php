@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Edit Subject</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="name">Subject Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $subject->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="code">Subject Code</label>
                    <input type="text" class="form-control" name="code" id="code" value="{{ old('code', $subject->code) }}" required>
                </div>
                <div class="mb-3">
                    <label for="description">Description (Optional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $subject->description) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Subject</button>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection