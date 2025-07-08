{{--
|--------------------------------------------------------------------------
| Authentication Card Component View
|--------------------------------------------------------------------------
|
| This file defines the structure for the authentication card component,
| commonly used to wrap forms on login, register, and other auth pages.
| It provides centering, background, shadow, and padding.
|
--}}

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    {{-- Optional slot for the logo at the top of the card --}}
    <div>
        {{ $logo ?? '' }}
    </div>

    {{-- The card structure itself --}}
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{-- The main content (the form) goes here --}}
        {{ $slot }}
    </div>
</div>