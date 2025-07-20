<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bulk Grade: {{ $assignment->title }} ({{ $assignment->subject->name }} in {{ $assignment->classSection->name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('teacher.grades.bulk-update', $assignment->id) }}" method="POST" data-turbo="false">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score (Max: 100)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comments (Auto-Generated)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($assignment->results->sortBy('student.name') as $result)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $result->student->name ?? 'Student not found' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number"
                                                       name="grades[{{ $result->id }}][score]"
                                                       class="score-input mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100"
                                                       value="{{ old('grades.'.$result->id.'.score', $result->score) }}"
                                                       data-result-id="{{ $result->id }}"
                                                       min="0" max="100">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text"
                                                       id="comment-{{ $result->id }}"
                                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm bg-gray-100 dark:bg-gray-700 dark:text-gray-300"
                                                       value="{{ $result->comments }}"
                                                       placeholder="Comment appears here"
                                                       readonly>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                No students found for this assignment.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.assignments.results', $assignment->id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-100 underline">Cancel</a>
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Save Grades</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const gradeScale = @json($assignment->classSection->gradingScale->grades ?? []);

        document.addEventListener('DOMContentLoaded', function () {
            const scoreInputs = document.querySelectorAll('.score-input');

            // This function finds the right remark for a score
            function findRemarkForScore(score) {
                if (isNaN(score) || gradeScale.length === 0) {
                    return '';
                }
                for (const grade of gradeScale) {
                    if (score >= grade.min_score && score <= grade.max_score) {
                        return grade.remark;
                    }
                }
                return ''; // Return empty if no range matches
            }

            // This function updates the comment field for a specific score input
            function updateCommentField(inputElement) {
                const score = parseFloat(inputElement.value);
                const resultId = inputElement.dataset.resultId;
                const commentInput = document.getElementById('comment-' + resultId);
                
                if (commentInput) {
                    commentInput.value = findRemarkForScore(score);
                }
            }

            // Loop through all score inputs on the page
            scoreInputs.forEach(input => {
                // ENHANCEMENT: Run once immediately on page load for existing scores
                updateCommentField(input);

                // Add an event listener to update the comment whenever the score changes
                input.addEventListener('input', () => updateCommentField(input));
            });
        });
    </script>
    @endpush
</x-app-layout>