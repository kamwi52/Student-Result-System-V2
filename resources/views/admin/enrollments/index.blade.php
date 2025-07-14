<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enroll Students in: <span class="text-blue-600">{{ $classSection->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.classes.enroll.store', $classSection) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Available Students</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 border-t border-b py-4">
                            @forelse ($allStudents as $student)
                                <label for="student-{{ $student->id }}" class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox"
                                           name="student_ids[]"
                                           value="{{ $student->id }}"
                                           id="student-{{ $student->id }}"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           @if(in_array($student->id, $enrolledStudentIds)) checked @endif
                                    >
                                    <span>{{ $student->name }} <span class="text-xs text-gray-500">({{ $student->email }})</span></span>
                                </label>
                            @empty
                                <p class="text-gray-500 col-span-full">No students found. Please import users with the 'student' role first.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('admin.classes.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Enrollment
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>