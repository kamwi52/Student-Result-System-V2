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

                    {{-- Display any validation errors from the controller --}}
                    @if(session('import_errors'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Please fix these errors in your file:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Upload a CSV file with subject data. The file must have columns in this exact order: <strong>name,code,description</strong>.
                    </p>

                    <form method="POST" action="{{ route('admin.subjects.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="subjects_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Subject CSV File
                            </label>

                            <input type="file" name="subjects_file" id="subjects_file" class="block w-full mt-1 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.subjects.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">
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