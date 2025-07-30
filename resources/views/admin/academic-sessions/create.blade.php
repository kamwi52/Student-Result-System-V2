<x-app-flowbite-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Academic Session') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-2">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.academic-sessions.store') }}">
                        @csrf

                        <!-- Session Name -->
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Session Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g., 2024-2025">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Date Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Is Current Checkbox -->
                        <div class="flex items-center mb-6">
                            <input id="is_current" type="checkbox" name="is_current" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ old('is_current') ? 'checked' : '' }}>
                            <label for="is_current" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mark as Current Session</label>
                            <x-input-error :messages="$errors->get('is_current')" class="mt-2" />
                        </div>
                        
                        {{-- Explainer text --}}
                        <p class="text-xs text-gray-500 dark:text-gray-400 -mt-4 mb-6">If you mark this as the current session, the previously active session will be automatically unmarked.</p>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <a href="{{ route('admin.academic-sessions.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('Create Session') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-flowbite-layout>```

