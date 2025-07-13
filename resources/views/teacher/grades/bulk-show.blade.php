<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bulk Grade Entry for: ') }} {{ $classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-1">Assessment: <span class="font-normal">{{ $assessment->name }}</span></h3>
                    <h3 class="text-lg font-bold mb-4">Subject: <span class="font-normal">{{ $assessment->subject->name }}</span></h3>

                    <form action="{{ route('teacher.grades.bulk.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $classSection->id }}">
                        <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score (out of 100)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->student_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" 
                                                   name="scores[{{ $student->id }}]"
                                                   class="w-24 border-gray-300 rounded-md shadow-sm"
                                                   min="0"
                                                   max="100"
                                                   value="{{ $existingResults[$student->id] ?? '' }}"
                                                   placeholder="e.g. 85">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('teacher.grades.bulk.create') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
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