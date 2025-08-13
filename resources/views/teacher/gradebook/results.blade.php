<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Enter Scores: {{ $assessment->name }}
            </h2>
            
            {{-- This button allows the teacher to print a clean summary of the entered marks --}}
            <a href="{{ route('teacher.gradebook.summary.print', $assessment) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Print Summary
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                {{-- The entire content is now a form that posts to the storeResults method --}}
                <form method="POST" action="{{ route('teacher.gradebook.results.store', $assessment) }}">
                    @csrf
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <x-success-message/>
                        <x-validation-errors class="mb-4" />

                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <p><span class="font-semibold">Class:</span> {{ $assessment->classSection->name }}</p>
                            <p><span class="font-semibold">Subject:</span> {{ $assessment->subject->name }}</p>
                            <p><span class="font-semibold">Max Marks:</span> {{ $assessment->max_marks }}</p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">
                                                {{ $student->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- This input is now part of the main form --}}
                                                <input type="number" 
                                                       name="scores[{{ $student->id }}]" 
                                                       value="{{ old('scores.' . $student->id, $results[$student->id]->score ?? '') }}"
                                                       class="w-full max-w-xs rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100"
                                                       step="0.01"
                                                       min="0"
                                                       max="{{ $assessment->max_marks }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                                No students are enrolled in this class.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('teacher.gradebook.assessments', ['classSection' => $assessment->classSection, 'subject' => $assessment->subject]) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                            Back to Assessments
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Save All Results
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>