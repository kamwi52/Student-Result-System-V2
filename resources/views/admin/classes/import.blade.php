<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-md">
                    <h3 class="font-bold">Import Instructions</h3>
                    <p class="text-sm mt-1">Upload a CSV file with the exact header row: <strong>name,academic_session_id,grading_scale_id</strong></p>
                    <ul class="list-disc list-inside text-sm mt-2">
                        <li><strong>name:</strong> The name of the class (e.g., Grade 10A).</li>
                        <li><strong>academic_session_id:</strong> The numeric ID of the academic session.</li>
                        <li><strong>grading_scale_id:</strong> The numeric ID of the grading scale (this field can be empty).</li>
                    </ul>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('admin.classes.import.handle') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="import_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Class Import File (CSV)</label>
                        <input id="import_file" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="import_file" required />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <x-primary-button>
                            Upload and Import
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>