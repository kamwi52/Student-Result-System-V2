<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bulk Grade Entry for: ') }} {{ $assignment->classSection->name }}
            </h2>
            {{-- Optionally add a back button or other actions here --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-message /> {{-- To display success messages after saving --}}
            <x-validation-errors class="mb-4" /> {{-- To display validation errors --}}

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-1">Assessment: <span class="font-normal">{{ $assessment->name }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Subject: <span class="font-normal">{{ $assignment->subject->name }}</span></h3>

                    @if ($students->isEmpty())
                        <div class="p-4 text-sm text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg" role="alert">
                            No students enrolled in this class.
                        </div>
                    @else
                        <form action="{{ route('teacher.grades.bulk.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}"> {{-- Crucial for store method --}}

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Student Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Score (Max {{ $assessment->max_marks }})</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($students as $student)
                                            @php
                                                // Get existing result or default to empty
                                                $result = $existingResults->get($student->id);
                                                $currentScore = $result ? $result->score : '';
                                                $currentRemark = $result ? $result->remark : '';
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $student->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <input type="number" name="scores[{{ $student->id }}]" 
                                                           value="{{ old('scores.' . $student->id, $currentScore) }}"
                                                           min="0" max="{{ $assessment->max_marks }}" step="0.01"
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100">
                                                    @error('scores.' . $student->id)
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <input type="text" name="remarks[{{ $student->id }}]" 
                                                           value="{{ old('remarks.' . $student->id, $currentRemark) }}"
                                                           maxlength="255"
                                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-gray-100">
                                                    @error('remarks.' . $student->id)
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('teacher.gradebook.results', ['assignment' => $assignment->id, 'assessment' => $assessment->id]) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">Cancel</a>
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                    Save All Grades
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>