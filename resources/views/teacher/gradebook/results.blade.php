<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gradebook - Results
            </h2>
            {{-- FIX 1: Updated route name from 'grades.bulk.show' to 'teacher.grades.bulk.show' --}}
            <a href="{{ route('teacher.grades.bulk.show', ['assignment' => $assignment->id, 'assessment' => $assessment->id]) }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 text-sm">
                Edit All Grades (Grid View)
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                {{-- This one was already correct --}}
                <a href="{{ route('teacher.gradebook.assessments', ['classSection' => $assignment->class_section_id, 'subject' => $assignment->subject_id]) }}" class="text-sm text-blue-600 hover:underline">← Back to Assessments</a>
            </div>
            <x-success-message />
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Class: {{ $assignment->classSection->name }}</h3>
                    <h4 class="text-xl mb-4">Assessment: {{ $assessment->name }}</h4>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead> {{-- Added a basic table header for clarity, you might have one --}}
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($students as $student)
                                @php $result = $results->get($student->id); @endphp
                                <tr>
                                    <td class="px-6 py-4">{{ $student->name }}</td>
                                    <td class="px-6 py-4">{{ $result->score ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $result->remark ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        @if($result)
                                            {{-- FIX 2: Updated route name from 'results.edit' to 'teacher.results.edit' --}}
                                            <a href="{{ route('teacher.results.edit', ['assignment' => $assignment->id, 'result' => $result->id]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 text-center">No students enrolled.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>