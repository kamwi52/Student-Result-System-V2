<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Instructions for CSV File</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Please prepare a CSV file with the following columns. The names in the file must exactly match the names in the system.
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                            <li><strong>name</strong>: The name of the class (e.g., 12A).</li>
                            <li><strong>academic_session</strong>: The exact name of an existing Academic Session (e.g., 2025 Academic Year).</li>
                            <li><strong>grading_system</strong>: The exact name of an existing Grading System (e.g., Senior Secondary (Exam)).</li>
                            <li><strong>subjects</strong>: A list of existing subject names separated by a pipe character `|` (e.g., "Mathematics|Science|History").</li>
                        </ul>
                         <div class="mt-4">
                            <a href="{{ route('admin.downloads.classes-template') }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template
                            </a>
                        </div>
                    </div>

                    {{-- Display any success or error messages from the controller --}}
                    <x-success-message />
                    <x-error-message />

                    {{-- ========================================================================= --}}
                    {{-- === THIS IS THE DEFINITIVE FIX ========================================== --}}
                    {{-- The action and enctype are guaranteed to be correct.                   --}}
                    {{-- ========================================================================= --}}
                    <form action="{{ route('admin.classes.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload CSV File</label>
                            <input type="file" name="file" id="file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">CSV or TXT files only.</p>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">{{ __('Cancel') }}</a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Import Classes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>