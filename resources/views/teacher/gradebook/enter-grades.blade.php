<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enter Grades: ') }} {{ $assignment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('teacher.gradebook.results.store', $assignment) }}">
                        @csrf
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score (Max: {{ $assignment->max_points }})</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comments</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        @php
                                            $result = $results[$student->id] ?? null;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $student->full_name }}
                                                <input type="hidden" name="grades[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" 
                                                       name="grades[{{ $loop->index }}][score]" 
                                                       value="{{ old('grades.'.$loop->index.'.score', $result?->score) }}"
                                                       class="w-20 border rounded px-2 py-1"
                                                       min="0" 
                                                       max="{{ $assignment->max_points }}" 
                                                       step="0.01"
                                                       required>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" 
                                                       name="grades[{{ $loop->index }}][comments]"
                                                       value="{{ old('grades.'.$loop->index.'.comments', $result?->comments) }}"
                                                       class="w-full border rounded px-2 py-1">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Save All Grades
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>