<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Grades') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-1">Class: <span class="font-normal">{{ $classSection->name }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Assessment: <span class="font-normal">{{ $assessment->name }} ({{ $assessment->subject->name }})</span></h3>

                    @if(session('import_errors'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Import Failed. Please fix these errors in your file and try again:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-4 p-4 border rounded-md">
                        <h4 class="font-bold text-md mb-2">Instructions:</h4>
                        <ol class="list-decimal list-inside space-y-2">
                            <li><strong>Download the Template:</strong> A CSV template has been generated for you with all the students in this class.</li>
                            <li><strong>Fill in Scores:</strong> Open the file in Excel or Google Sheets. Enter the score for each student in the `score` column. You can optionally add text to the `remarks` column.</li>
                            <li><strong>Save and Upload:</strong> Save the file (as CSV or XLSX) and upload it below.</li>
                        </ol>
                    </div>

                    <form action="{{ route('teacher.grades.import.handle') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $classSection->id }}">
                        <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

                        <div class="mb-4">
                            <label for="results_file" class="block text-sm font-medium text-gray-700">Upload Your Completed File</label>
                            <input type="file" name="results_file" id="results_file" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                             <a href="{{ route('teacher.grades.bulk.create') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                Import Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>