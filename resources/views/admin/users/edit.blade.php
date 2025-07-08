{{--
|--------------------------------------------------------------------------
| Admin User Edit View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the form for editing an existing User, using the
| Tailwind CSS and Laravel Breeze component architecture.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Display Validation Errors --}}
                    {{-- Ensure you have the 'validation-errors.blade.php' component in resources/views/components --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Form to update the user. Uses PUT method. --}}
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf {{-- CSRF token for security --}}
                        @method('PUT') {{-- Use the PUT method for updates --}}

                        {{-- Name Field --}}
                        <div>
                            {{-- Ensure you have the 'label.blade.php' or 'input-label.blade.php' component --}}
                            <x-label for="name" value="{{ __('Name') }}" />
                            {{-- Using the standard 'text-input.blade.php' component --}}
                            {{-- Ensure you have the 'text-input.blade.php' component in resources/views/components with a standard <input> tag inside --}}
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        </div>

                        {{-- Email Field --}}
                         <div class="mt-4">
                            <x-label for="email" value="{{ __('Email') }}" />
                             <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        </div>

                         {{-- Role Field (Dropdown) --}}
                         <div class="mt-4">
                            <x-label for="role" value="{{ __('Role') }}" />
                            {{-- Standard HTML select tag styled with Tailwind classes --}}
                            {{-- Assuming $roles is passed from the controller (e.g., ['admin' => 'Admin', 'teacher' => 'Teacher', 'student' => 'Student']) --}}
                            <select name="role" id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Role</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" @if(old('role', $user->role) == $value) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Password Fields (Optional - for changing password) --}}
                        {{-- You might implement a separate "Change Password" form for better UX --}}
                        {{-- If you include them here, ensure they are handled carefully in the controller --}}
                        {{-- Example of how they might look (uncomment if needed): --}}
                        {{--
                        <div class="mt-4">
                            <x-label for="password" value="{{ __('New Password') }}" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <div class="text-sm text-gray-600 mt-1">{{ __('Leave blank to keep current password.') }}</div>
                        </div>

                        <div class="mt-4">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                        </div>
                        --}}


                        <div class="flex items-center justify-end mt-4">
                            {{-- Standard anchor tag for Cancel button --}}
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.users.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            {{-- Primary Button component for the submit button --}}
                            {{-- Ensure you have the 'primary-button.blade.php' component in resources/views/components --}}
                            <x-primary-button class="ms-4">
                                {{ __('Save User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>