<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Import Assessments') }}
            </h2>
            <a href="{{ route('admin.assessments.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Back to Assessments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Using the reusable error component --}}
                    <x-error-message />
                    
                    {{-- Instruction Block --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                        <h3 class="font-bold text-md text-gray-800 dark:text-gray-200">File Requirements</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Your CSV file must have a header row with the following exact column names:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm font-mono text-gray-800 dark:text-gray-200">
                            <li>assessment_name</li>
                            <li>subject_name</li>
                            <li>class_name</li>
                            <li>academic_session_name</li>
                            <li>max_marks</li>
                            <li>weightage</li>
                            <li>assessment_date (format: YYYY-MM-DD)</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('admin.assessments.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select CSV File</label>
                            <input id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="file" required accept=".csv,.txt" >
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('Upload & Process') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>