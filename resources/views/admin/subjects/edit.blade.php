{{--
|--------------------------------------------------------------------------
| Admin Subject Edit View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the form for editing an existing Subject, using the
| Tailwind CSS and Laravel Breeze component architecture.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Subject') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Display Validation Errors --}}
                    {{-- Ensure you have the 'validation-errors.blade.php' component in resources/views/components --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Form to update the subject. Uses PUT method. --}}
                    <form method="POST" action="{{ route('admin.subjects.update', $subject->id) }}">
                        @csrf {{-- CSRF token for security --}}
                        @method('PUT') {{-- Use the PUT method for updates --}}

                        {{-- Name Field --}}
                        <div>
                            {{-- Using the standard 'label.blade.php' or 'input-label.blade.php' component --}}
                            <x-label for="name" value="{{ __('Subject Name') }}" />
                            {{-- Using the standard 'text-input.blade.php' component --}}
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $subject->name)" required autofocus />
                        </div>

                        {{-- Code Field --}}
                         <div class="mt-4">
                            <x-label for="code" value="{{ __('Subject Code') }}" />
                             <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code', $subject->code)" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- Standard anchor tag for Cancel button --}}
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.subjects.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            {{-- Primary Button component for the submit button --}}
                            {{-- Ensure you have the 'primary-button.blade.php' component in resources/views/components --}}
                            <x-primary-button class="ms-4">
                                {{ __('Save Subject') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>