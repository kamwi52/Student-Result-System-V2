{{--
|--------------------------------------------------------------------------
| Admin User Import View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the form for importing users from a spreadsheet,
| using the Tailwind CSS and Laravel Breeze component architecture.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Display Validation Errors --}}
                    {{-- Ensure you have the 'validation-errors.blade.php' component in resources/views/components --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Form to handle file upload for importing users --}}
                    {{-- IMPORTANT: Use enctype="multipart/form-data" for file uploads --}}
                    <form method="POST" action="{{ route('admin.users.handleImport') }}" enctype="multipart/form-data">
                        @csrf {{-- CSRF token for security --}}

                        {{-- File Input Field --}}
                        <div>
                            {{-- Using the standard 'label.blade.php' or 'input-label.blade.php' component --}}
                            <x-label for="file" value="{{ __('Select File (.xlsx, .csv, .xls)') }}" />

                            {{-- Standard HTML input type="file" styled with Tailwind classes --}}
                            {{-- Note: x-text-input component is generally for text-based inputs, file inputs are typically raw HTML + classes --}}
                            <input id="file" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="file" required />

                            {{-- Input Error component for displaying validation errors for the 'file' field --}}
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- Standard anchor tag for Cancel button --}}
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.users.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            {{-- Primary Button component for the submit button --}}
                            {{-- Ensure you have the 'primary-button.blade.php' component in resources/views/components --}}
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