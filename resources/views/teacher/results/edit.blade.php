<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Grade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h3 class="text-lg">Student: <span class="font-bold">{{ $result->student->name }}</span></h3>
                    <p class="text-gray-600 dark:text-gray-400">Assessment: <span class="font-bold">{{ $result->assessment->name }}</span></p>
                </div>
                
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('teacher.results.update', $result->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- THE FIX: Add the assignment_id to the form --}}
                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                    <!-- Score Input -->
                    <div class="mb-4">
                        <label for="score" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Score</label>
                        <input id="score" class="block mt-1 w-full rounded-md" type="number" name="score" value="{{ old('score', $result->score) }}" required min="0" max="100" />
                    </div>

                    <!-- Auto-Generated Remark Display -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Auto-Generated Remark</label>
                        <div class="mt-1 p-3 min-h-[42px] bg-gray-100 dark:bg-gray-900 rounded-md border border-gray-300 dark:border-gray-700">
                           {{ $result->remark ?? 'Will be generated based on score.' }}
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('teacher.gradebook.results', ['assignment' => $assignment->id, 'assessment' => $result->assessment_id]) }}" class="mr-4">Cancel</a>
                        <x-primary-button>Update Grade</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>