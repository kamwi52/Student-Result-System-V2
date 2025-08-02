<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Results: Step 2 of 2') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    {{-- === THE FIX IS HERE === --}}
                    {{-- Replaced the non-existent 'alert-messages' with the correct components --}}
                    <x-success-message />
                    <x-error-message />
                    
                    {{-- Instructions Section --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Import for Class: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $classSection->name }}</span>
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Please select the assessment you are importing results for and upload the CSV file.
                        </p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            The CSV file must have two columns in this exact order: <strong>student_email, score</strong>.
                        </p>
                    </div>

                    {{-- File Upload Form --}}
                    <form action="{{ route('admin.results.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Hidden field to pass the class_section_id to the final import handler --}}
                        <input type="hidden" name="class_section_id" value="{{ $classSection->id }}">

                        {{-- Assessment Selection --}}
                        <div class="mb-6">
                            <label for="assessment_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Assessment</label>
                            <select id="assessment_id" name="assessment_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="" disabled selected>-- Choose an assessment --</option>
                                @forelse($assessments as $assessment)
                                    <option value="{{ $assessment->id }}">
                                        {{ $assessment->name }} ({{ $assessment->subject->name }})
                                    </option>
                                @empty
                                    <option value="" disabled>No assessments found for this academic session.</option>
                                @endforelse
                            </select>
                        </div>

                        {{-- File Input --}}
                        <div class="mb-6">
                            <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Results File</label>
                            <input type="file" name="file" id="file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">CSV, TXT, XLSX or XLS files only.</p>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.results.import.show_step1') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('Back') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" @if($assessments->isEmpty()) disabled @endif>
                                Import Results
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>