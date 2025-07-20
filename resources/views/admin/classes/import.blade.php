<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import & Update Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Display general or row-specific import errors --}}
                    @if(session('error') || session('import_errors'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Import Failed!</p>
                            @if(session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                            @if(session('import_errors') && !empty(session('import_errors')))
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach(session('import_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    {{-- Instruction Block --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                        <h3 class="font-bold text-md text-gray-800 dark:text-gray-200">File Requirements</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Your CSV file must have a header row with the following exact column names. The combination of `name` and `academic_session_id` is used to find existing classes to update.
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm font-mono text-gray-800 dark:text-gray-200">
                            <li>name</li>
                            <li>academic_session_id</li>
                            <li>grading_scale_id</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('admin.classes.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            {{-- EDITED: Using 'file' for consistency --}}
                            <x-input-label for="file" :value="__('Select CSV File')" />
                            <x-text-input id="file" class="block w-full mt-1" type="file" name="file" required accept=".csv,.txt" />
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('admin.classes.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Import Classes') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>