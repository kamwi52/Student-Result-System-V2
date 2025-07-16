<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bulk Grade Entry for: ') }} {{ $assignment->classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-1">Assessment: <span class="font-normal">{{ $assessment->name }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Subject: <span class="font-normal">{{ $assignment->subject->name }}</span></h3>

                    <x-validation-errors class="mb-4" />

                    <form action="{{ route('teacher.grades.bulk.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                        {{-- THE FIX: Add the assignment_id to the form --}}
                        <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                        <div class="overflow-x-auto">
                           {{-- ... (your existing table code for scores) ... --}}
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            {{-- THE FIX: Updated cancel link --}}
                            <a href="{{ route('teacher.gradebook.results', ['assignment' => $assignment->id, 'assessment' => $assessment->id]) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                Save All Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>