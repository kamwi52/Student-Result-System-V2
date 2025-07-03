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

                <!-- resources/views/admin/assessments/index.blade.php -->

{{-- Add this section to your existing index view --}}
<div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="text-lg font-semibold mb-4">Import Assessments</h3>
        <p class="text-sm text-gray-600 mb-4">
            Upload a CSV or Excel file to bulk-create assessments for the <strong>current academic session</strong>.
            The file must contain columns named 'name' and 'total_marks'.
        </p>
        
        <!-- IMPORTANT: The form needs this enctype to handle file uploads -->
        <form action="{{ route('admin.assessments.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="import_file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Import File
                </button>
            </div>
            @error('import_file')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>
{{-- Your existing table of assessments would go here --}}


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