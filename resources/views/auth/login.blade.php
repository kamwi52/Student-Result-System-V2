<x-guest-layout>
    <x-auth-card>
        
        <div class="mb-8 text-center">
            {{-- The "Sign In" text is a prominent blue color --}}
            <h2 class="text-3xl font-bold text-blue-600 dark:text-blue-500">Sign In</h2>
            
            {{-- === THIS IS THE FIX: Changed subtitle to blue === --}}
            <p class="text-blue-600 dark:text-blue-400 mt-1">to the Results & Analysis System</p>
        </div>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                {{-- Label "Card" --}}
                <div class="block w-full px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md shadow-sm">
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Email</span>
                </div>

                {{-- Input Field --}}
                <x-text-input id="email" class="block w-full" 
                              type="email" 
                              name="email" 
                              :value="old('email')" 
                              required 
                              autofocus 
                              autocomplete="username"
                              placeholder="e.g. yourname@example.com" />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 space-y-2">
                {{-- Label "Card" --}}
                <div class="block w-full px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded-md shadow-sm">
                    <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Password</span>
                </div>

                {{-- Input Field --}}
                <x-text-input id="password" class="block w-full"
                                type="password"
                                name="password"
                                required 
                                autocomplete="current-password"
                                placeholder="Enter your password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log In') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>