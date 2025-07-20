<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import & Update Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- THIS IS THE NEW ERROR DISPLAY BLOCK --}}
                    {{-- Displays general file errors --}}
                    @if(session('import_error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Import Failed!</p>
                            <p>{{ session('import_error') }}</p>
                        </div>
                    @endif

                    {{-- Displays specific row-by-row validation errors --}}
                    @if(session('import_errors'))
                        <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4" role="alert">
                            <p class="font-bold">Import completed with some issues. Please fix these errors in your file and re-upload:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{-- Instruction Block --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                        <h3 class="font-bold text-md text-gray-800 dark:text-gray-200">File Requirements</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Your spreadsheet file must have a header row with the following column names:
                        </p>
                        <ul class="mt-2 list-disc list-inside text-sm font-mono text-gray-800 dark:text-gray-200">
                            <li>name</li>
                            <li>email (This is the unique key for updating)</li>
                            <li>password</li>
                            <li>role (Must be 'admin', 'teacher', or 'student')</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('admin.users.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="file" :value="__('Select File (.xlsx, .csv, .xls)')" />
                            <x-text-input id="file" class="block w-full mt-1" type="file" name="file" required accept=".xlsx,.csv,.xls" />
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('admin.users.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Import Users') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>