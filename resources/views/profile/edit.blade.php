{{--
|--------------------------------------------------------------------------
| Profile Edit View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the user's profile page, allowing them to update
| their name, email, and password. It is now fully converted to the
| Tailwind/Breeze component architecture.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Profile Information Update Form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Include the partial view for updating profile info --}}
                    {{-- This partial file is a standard part of Breeze --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Password Update Form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Include the partial view for updating the password --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account Form --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- Include the partial view for deleting the account --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>