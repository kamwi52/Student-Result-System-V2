<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Subject') }}
            </h2>
            <a href="{{ route('admin.subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Go Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.subjects.store') }}">
                        @csrf

                        <!-- Subject Name -->
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600" placeholder="e.g., Mathematics">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Subject Code -->
                        <div class="mb-6">
                            <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject Code</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600" placeholder="e.g., MATH101">
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                        
                        <!-- === NEW: Assign Teachers Section === -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assign Qualified Teachers</label>
                            <div class="p-4 border border-gray-300 rounded-lg h-48 overflow-y-auto dark:border-gray-600">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach ($teachers as $teacher)
                                        <label for="teacher_{{ $teacher->id }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <input type="checkbox" id="teacher_{{ $teacher->id }}" name="teachers[]" value="{{ $teacher->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $teacher->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('teachers')" class="mt-2" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                                {{ __('Create Subject') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>