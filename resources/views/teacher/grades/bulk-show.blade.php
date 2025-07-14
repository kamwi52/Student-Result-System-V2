<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bulk Grade Entry for: ') }} {{ $classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-1">Assessment: <span class="font-normal">{{ $assessment->name }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Subject: <span class="font-normal">{{ $assessment->subject->name }}</span></h3>

                    {{-- This will display validation errors if the form fails --}}
                    <x-validation-errors class="mb-4" />

                    <form action="{{ route('teacher.grades.bulk.store') }}" method="POST">
                        @csrf
                        {{-- We only need to submit the assessment_id --}}
                        <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score (out of 100)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($students as $student)
                                        @php
                                            // Get the full result object to access both score and remark
                                            $result = $existingResults->get($student->id);
                                        @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" 
                                                   name="scores[{{ $student->id }}]"
                                                   class="w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                   min="0"
                                                   max="100"
                                                   value="{{ old('scores.'.$student->id, $result->score ?? '') }}"
                                                   placeholder="e.g. 85">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             <input type="text" 
                                                   name="remarks[{{ $student->id }}]"
                                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                   value="{{ old('remarks.'.$student->id, $result->remark ?? '') }}"
                                                   placeholder="Optional comment...">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">Cancel</a>
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