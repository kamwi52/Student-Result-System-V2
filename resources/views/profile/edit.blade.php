<x-app-flowbite-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Profile Information Update Form --}}
            <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Password Update Form --}}
            <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account Form --}}
            <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            
        </div>
    </div>
</x-app-flowbite-layout>