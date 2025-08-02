<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Bulk Student Enrollments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <x-success-message />
                <x-error-message />

                {{-- Form #1: Select a Class --}}
                <form method="POST" action="{{ route('admin.enrollments.bulk-manage.show') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="class_section_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">1. Select a Class to Manage</label>
                        <div class="flex items-center space-x-2">
                            <select id="class_section_id" name="class_section_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <option value="">-- Please choose a class --</option>
                                @foreach ($classSections as $class)
                                    <option value="{{ $class->id }}" @selected(optional($selectedClass)->id == $class->id)>
                                        {{ $class->name }} ({{ $class->academicSession->name }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="text-white bg-gray-700 hover:bg-gray-800 font-medium rounded-lg text-sm px-5 py-2.5">Load Students</button>
                        </div>
                    </div>
                </form>

                @if($selectedClass)
                    <hr class="my-6 border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Editing Enrollments for: {{ $selectedClass->name }}</h3>
                    
                    {{-- Form #2: Manage and Save Enrollments --}}
                    <form method="POST" action="{{ route('admin.enrollments.bulk-manage.handle') }}">
                        @csrf
                        <input type="hidden" name="class_section_id" value="{{ $selectedClass->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Enrolled Students List --}}
                            <div>
                                <h4 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enrolled Students (Uncheck to remove)</h4>
                                <div class="border border-gray-300 rounded-lg h-80 overflow-y-auto p-4 space-y-2 dark:border-gray-600">
                                    @forelse($enrolledStudents as $student)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-800 dark:text-gray-200">{{ $student->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No students are currently enrolled.</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            {{-- Unenrolled Students List --}}
                            <div>
                                <h4 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Students (Check to add)</h4>
                                <div class="border border-gray-300 rounded-lg h-80 overflow-y-auto p-4 space-y-2 dark:border-gray-600">
                                     @forelse($unenrolledStudents as $student)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-800 dark:text-gray-200">{{ $student->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500">No other students available.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5">
                                Save All Enrollment Changes
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>