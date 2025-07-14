<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gradebook - Results
            </h2>
            {{-- NEW BUTTON: This links to the familiar grid-entry page --}}
            <a href="{{ route('teacher.grades.bulk.show', ['classSection' => $classSection->id, 'assessment' => $assessment->id]) }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 text-sm">
                Edit All Grades (Grid View)
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('teacher.gradebook.assessments', $classSection->id) }}" class="text-sm text-blue-600 hover:underline">← Back to Assessments</a>
            </div>
            <x-success-message />
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Class: {{ $classSection->name }}</h3>
                    <h4 class="text-xl mb-4">Assessment: {{ $assessment->title }}</h4>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Remark</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Action</th>
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
                                                {{-- NEW LINK: Points to the single result edit route --}}
                                                <a href="{{ route('teacher.results.edit', $result->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center">No students are enrolled in this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>