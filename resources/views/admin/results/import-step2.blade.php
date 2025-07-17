<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Import Results - Step 2: Select Assessment</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">You are importing for class:</p>
                    <p class="font-bold text-lg">{{ $classSection->name }}</p>
                </div>
                <form method="POST" action="{{ route('admin.results.import.handle') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $classSection->id }}">

                    <div class="mt-4">
                        <label for="assessment_id" class="block font-medium">Select an Assessment</label>
                        <select name="assessment_id" id="assessment_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Choose an assessment --</option>
                            @foreach($assessments as $assessment)
                                <option value="{{ $assessment->id }}">{{ $assessment->subject->name }} - {{ $assessment->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="results_file" class="block font-medium">Upload Results CSV File</label>
                        <input type="file" name="results_file" id="results_file" required class="mt-1 block w-full">
                        <p class="text-xs text-gray-500 mt-1">File must have columns: `student_email`, `score`, `remark`</p>
                    </div>

                    <div class="flex justify-end items-center mt-6 gap-4">
                        <a href="{{ route('admin.results.import.step1') }}" class="text-sm hover:underline">Go Back</a>
                        <x-primary-button>Import Results</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>