<x-app-flowbite-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Classes') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    {{-- Instructions Section --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Instructions for CSV File</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Please prepare a CSV file with the following columns in this exact order:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                            <li><strong>name</strong>: The name of the class (e.g., Grade 9A).</li>
                            <li><strong>academic_session_name</strong>: The exact name of an existing Academic Session (e.g., 2025-2026).</li>
                            <li><strong>grading_scale_name</strong>: The exact name of an existing Grading System (e.g., Standard A-F).</li>
                            <li><strong>subjects</strong>: A list of existing subject names separated by a pipe character `|` (e.g., "Mathematics|Science|History").</li>
                        </ul>
                    </div>

                    {{-- Display Success/Error Messages --}}
                    <x-success-message />
                    <x-error-message />
                    @if(session('import_errors'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Please fix these errors in your file or system:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- File Upload Form --}}
                    <form action="{{ route('admin.classes.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="classes_file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload CSV File</label>
                            <input type="file" name="classes_file" id="classes_file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">CSV or TXT files only.</p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Import Classes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-flowbite-layout>