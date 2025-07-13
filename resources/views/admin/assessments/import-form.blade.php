<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Import Assessments') }}</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="mb-4">Upload a CSV file with assessment data. The file must have columns: `assessment_name`, `subject_name`, `academic_session_name`, `max_marks`, `weightage_percent`.</p>

                @if(session('import_errors'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        <p class="font-bold">Please fix these errors in your file:</p>
                        @if (is_array(session('import_errors')))
                            @foreach(session('import_errors') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        @else
                            <p>{{ session('import_errors') }}</p>
                        @endif
                    </div>
                @endif

                <form action="{{ route('admin.assessments.simpleImport.handle') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="assessments_file" required>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md ml-4">Import</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>