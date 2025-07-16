<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Assessments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-validation-errors class="mb-4" />
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

                    {{-- === THE FIX: Use the correct route name === --}}
                    <form method="POST" action="{{ route('admin.assessments.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="assessments_file" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                Assessment CSV File
                            </label>
                            <input type="file" name="assessments_file" id="assessments_file" class="block w-full mt-1 border-gray-300 dark:border-gray-700 rounded-md shadow-sm" required>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                CSV must have these columns: 

<form> `subject_name`, `academic_session_name`, `max_marks`, `assessment_date`.
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.assessments.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Import Assessments') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>