<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Enroll Students in: <span class="text-indigo-600">{{ $classSection->name }}</span>
            </h2>
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Back to Classes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    {{-- === THIS IS THE NEW FILTER FORM === --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600">
                        <form method="GET" action="{{ route('admin.classes.enroll.index', $classSection) }}">
                            <label for="source_class_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filter Available Students by Previous Class</label>
                            <div class="flex items-center space-x-2">
                                <select name="source_class_id" id="source_class_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                                    <option value="">-- Show All Students --</option>
                                    @foreach($sessions as $session)
                                        <optgroup label="{{ $session->name }}">
                                            @foreach($session->classSections as $sourceClass)
                                                <option value="{{ $sourceClass->id }}" @selected($sourceClassId == $sourceClass->id)>
                                                    {{ $sourceClass->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Filter</button>
                            </div>
                        </form>
                    </div>
                    
                    {{-- This form saves the actual changes --}}
                    <form method="POST" action="{{ route('admin.classes.enroll.store', $classSection) }}">
                        @csrf
                        
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Available Students</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select the students you wish to enroll in this class.</p>

                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 h-96 overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @forelse($allStudents as $student)
                                    <label for="student_{{ $student->id }}" class="flex items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600">
                                        <input type="checkbox" id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}"
                                               @checked(in_array($student->id, $enrolledStudentIds))
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $student->name }}
                                            <span class="block text-xs text-gray-500">{{ $student->email }}</span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="col-span-full text-center text-gray-500">No students found for the selected filter.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                Update Enrollment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>