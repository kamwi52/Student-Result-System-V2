{{--
|--------------------------------------------------------------------------
| Login Page View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the user login interface using the standard
| Laravel Breeze component system and Tailwind CSS classes.
| It replaces the old Bootstrap-based layout and form structure.
|
--}}

{{-- Extends the guest layout component provided by Breeze --}}
<x-guest-layout>

    {{-- Authentication Card component often used to wrap auth forms in Breeze --}}
    {{-- This component provides background, shadow, padding, max width, and centers the card --}}
    {{-- The logo is typically handled by the x-guest-layout directly, not within the auth-card slot here --}}
    <x-auth-card>

        {{--
            The x-slot name="logo" block has been removed from here.
            The logo is now expected to be rendered by the x-guest-layout component itself.
        --}}

        {{-- Session Status component for displaying flash messages (like login errors) --}}
        {{-- Ensure you have the 'auth-session-status.blade.php' component --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- The Login Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email Address Input --}}
            <div>
                {{-- Input Label component --}}
                {{-- Ensure you have the 'input-label.blade.php' component --}}
                <x-input-label for="email" :value="__('Email')" />

                {{-- Text Input component (styled input field) --}}
                {{-- Ensure you have the 'text-input.blade.php' component --}}
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />

                {{-- Input Error component for displaying validation errors --}}
                {{-- Ensure you have the 'input-error.blade.php' component --}}
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password Input --}}
            <div class="mt-4"> {{-- Added margin top for spacing --}}
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Remember Me Checkbox --}}
            <div class="block mt-4"> {{-- Added margin top for spacing --}}
                <label for="remember_me" class="inline-flex items-center">
                    {{-- Standard HTML checkbox with Tailwind classes --}}
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span> {{-- ms-2 is margin-left: 0.5rem --}}
                </label>
            </div>

            {{-- Action Buttons (Forgot Password and Login) --}}
            <div class="flex items-center justify-end mt-4"> {{-- Added margin top and used flexbox for alignment --}}
                {{-- Forgot Password Link (if route exists) --}}
                @if (Route::has('password.request'))
                    {{-- Standard anchor tag with Tailwind styling --}}
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                {{-- Primary Button component for the submit button --}}
                {{-- Ensure you have the 'primary-button.blade.php' component --}}
                <x-primary-button class="ms-3"> {{-- ms-3 is margin-left: 0.75rem --}}
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>