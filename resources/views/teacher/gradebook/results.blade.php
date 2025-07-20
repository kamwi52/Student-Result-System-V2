<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Results for ') }} {{ $assignment->assessment->name ?? 'N/A' }} ({{ $assignment->classSection->name }} - {{ $assignment->subject->name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Detailed Results
                        </h3>
                        <div>
                            <a href="{{ route('teacher.gradebook.assessments', [$assignment->classSection, $assignment->subject]) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Back to Assignments
                            </a>
                            {{-- === NEW BUTTON ADDED HERE === --}}
                            <a href="{{ route('teacher.class-sections.reports', $assignment->classSection) }}" target="_blank" class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Generate All Reports
                            </a>
                        </div>
                    </div>

                    @if($students->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400">No students found in this class, or no results recorded for this assignment.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score (Max: {{ $assignment->assessment->max_marks ?? 'N/A' }})</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comments</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($students as $student)
                                        @php
                                            $studentResult = $results->get($student->id);
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $student->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $studentResult->score ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                {{ $studentResult->comments ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('teacher.students.report', $student) }}" target="_blank" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-4">Report Card</a>

                                                @if($studentResult)
                                                    <a href="{{ route('teacher.results.edit', $studentResult) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                                @else
                                                    <a href="{{ route('admin.results.create', ['user_id' => $student->id, 'assessment_id' => $assignment->assessment->id]) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Add Score</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>