
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assignments: ') }} {{ $subject->name }} ({{ $classSection->name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($assignments->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-500">No assignments found.</p>
                            <a href="#" class="text-indigo-600 hover:text-indigo-900 mt-2 inline-block">
                                Create New Assignment
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max Points</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->due_date->format('M j, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->max_points }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <a href="{{ route('teacher.gradebook.results.show', $assignment) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    View Grades
                                                </a>
                                                <a href="{{ route('teacher.gradebook.results.enter', $assignment) }}" 
                                                   class="text-green-600 hover:text-green-900">
                                                    Enter Grades
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>