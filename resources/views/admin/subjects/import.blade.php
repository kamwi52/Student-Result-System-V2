<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Subjects from CSV') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- ADDED: Display general, critical import errors (e.g., invalid header) --}}
                    @if(session('import_error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Import Failed!</p>
                            <p>{{ session('import_error') }}</p>
                        </div>
                    @endif

                    {{-- Display row-by-row validation errors --}}
                    @if(session('import_errors') && !empty(session('import_errors')))
                        <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4" role="alert">
                            <p class="font-bold">Import completed with some issues. Please fix these errors in your file and re-upload:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ENHANCED: Instructions are now in a more visible block --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                        <h3 class="font-bold text-md text-gray-800 dark:text-gray-200">Instructions</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Upload a CSV file with subject data. The file must have a header row with the following columns in this exact order:
                        </p>
                        <p class="mt-1 font-mono text-sm text-gray-800 dark:text-gray-200">name,code,description</p>
                    </div>

                    <form method="POST" action="{{ route('admin.subjects.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="subjects_file" :value="__('Subject CSV File')" />

                            {{-- The name 'subjects_file' matches the controller --}}
                            <x-text-input type="file" name="subjects_file" id="subjects_file" class="block w-full mt-1" required accept=".csv" />
                            
                            {{-- ADDED: Display standard Laravel validation error for this field --}}
                            <x-input-error :messages="$errors->get('subjects_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.subjects.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Import Subjects') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>