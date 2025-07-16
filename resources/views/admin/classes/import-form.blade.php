<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- This component will display any validation errors --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Instructions and Template Info --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-900/50 border-l-4 border-indigo-500">
                        <h3 class="font-bold text-lg">Instructions</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Upload a CSV file to bulk import classes. The file must have the following columns in this exact order:
                        </p>
                        <code class="block bg-gray-200 dark:bg-gray-900 p-2 rounded-md text-sm mt-2">
                            name,teacher_email,academic_session_name,subjects
                        </code>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            The `subjects` column should contain one or more subject names, separated by a pipe character (`|`). For example: `Mathematics|Physics|English`.
                        </p>
                    </div>

                    {{-- The Upload Form --}}
                    <form action="{{ route('admin.classes.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="classes_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class CSV File</label>
                            <input type="file" name="classes_file" id="classes_file" required
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Import Classes') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>