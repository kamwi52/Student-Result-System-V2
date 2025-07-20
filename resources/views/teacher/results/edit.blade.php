<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Grade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('teacher.assignments.results', $result->assessment->assignment->id) }}"
                    class="text-sm text-blue-600 hover:underline">← Back to Results</a>
            </div>
            
            {{-- This component will now display the success message after the redirect --}}
            <x-success-message />
            <x-validation-errors class="mb-4" />

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-1">Student: <span
                            class="font-normal">{{ $result->student->name ?? 'N/A' }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Assessment: <span
                            class="font-normal">{{ $result->assessment->name ?? 'N/A' }}</span></h3>

                    {{-- THE FIX IS ON THIS LINE --}}
                    <form action="{{ route('teacher.results.update', $result->id) }}" method="POST" data-turbo="false">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="score"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Score</label>
                            <input type="number" name="score" id="score"
                                value="{{ old('score', $result->score ?? '') }}" min="0"
                                max="{{ $result->assessment->max_marks ?? 100 }}" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100">
                            @error('score')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comments"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comments</label>
                            <input type="text" name="comments" id="comments"
                                value="{{ old('comments', $result->comments ?? '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100"
                                maxlength="1000"
                                placeholder="Enter comments here...">
                            @error('comments')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.assignments.results', $result->assessment->assignment->id) }}"
                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">Cancel</a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                Update Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>