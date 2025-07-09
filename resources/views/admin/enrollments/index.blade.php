{{--
|--------------------------------------------------------------------------
| Admin Student Enrollment View (Tailwind CSS / Breeze Component Structure)
|--------------------------------------------------------------------------
|
| This file defines the interface for enrolling students into a specific class,
| using the Tailwind CSS and Laravel Breeze component architecture.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{-- Using the class name and subject name from the loaded relationships --}}
                Enroll Students in: {{ $classSection->name }} ({{ $classSection->subject->name ?? 'N/A' }})
            </h2>
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $classSection->students_count }} {{ Str::plural('Student', $classSection->students_count) }} Enrolled
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Search Form for Students --}}
                    <form method="GET" action="{{ route('admin.classes.enroll.index', $classSection) }}">
                        <div class="mb-4 flex items-center">
                            <x-text-input type="text" name="search" class="w-full" placeholder="Search students by name or email..." :value="$searchTerm" />
                            <x-primary-button class="ms-3">Search</x-primary-button>
                        </div>
                    </form>

                    {{-- Display Validation Errors --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Form to handle enrollment updates --}}
                    <form method="POST" action="{{ route('admin.classes.enroll.store', $classSection->id) }}">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="w-10 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enroll</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($allStudents as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{-- Checkbox for each student --}}
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{-- Check the box if the student's ID is in the enrolled list --}}
                                                       @if(in_array($student->id, $enrolledStudentIds)) checked @endif>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                @if($searchTerm)
                                                    No students found for your search term "{{ $searchTerm }}".
                                                @else
                                                    No students found.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination links --}}
                        <div class="mt-4">
                            {{ $allStudents->links() }}
                        </div>

                        <div class="flex items-center justify-end mt-4 border-t pt-4">
                            {{-- Standard anchor tag for Cancel button --}}
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.classes.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            {{-- Primary Button component for the submit button --}}
                            <x-primary-button class="ms-4">
                                {{ __('Update Enrollments') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>