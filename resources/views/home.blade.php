{{--
|--------------------------------------------------------------------------
| Home / Default Dashboard View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file serves as the default dashboard for authenticated users who
| are not an Admin or a Teacher (e.g., Students).
| It has been converted to use the standard <x-app-layout> component.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Check for a success message from a previous action --}}
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>