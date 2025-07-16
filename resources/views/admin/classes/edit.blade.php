<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Class: ') }} {{ $classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-success-message />
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.classes.update', $classSection->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Section 1: General Class Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Class Name</label>
                            <x-text-input type="text" name="name" id="name" class="mt-1 block w-full" value="{{ old('name', $classSection->name) }}" required />
                        </div>
                        <div>
                            <label for="academic_session_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Academic Session</label>
                            <select name="academic_session_id" id="academic_session_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}" @selected(old('academic_session_id', $classSection->academic_session_id) == $session->id)>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="grading_scale_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Grading System</label>
                        <select name="grading_scale_id" id="grading_scale_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Not Set --</option>
                            @foreach($gradingScales as $scale)
                                <option value="{{ $scale->id }}" @selected(old('grading_scale_id', $classSection->grading_scale_id) == $scale->id)>{{ $scale->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">
                    
                    {{-- Section 2: Teacher Assignments --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Teacher Assignments</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        For each subject taught in this class, select the teacher responsible.
                    </p>

                    <div class="mt-4 space-y-4">
                        @forelse($classSection->subjects as $subject)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $subject->name }}</span>
                                <div>
                                    <label for="teacher-for-{{ $subject->id }}" class="sr-only">Teacher for {{ $subject->name }}</label>
                                    <select name="assignments[{{ $subject->id }}]" id="teacher-for-{{ $subject->id }}" class="block w-full max-w-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm text-sm">
                                        <option value="">-- Unassigned --</option>
                                        @foreach($teachers as $teacher)
                                            @php
                                                // Find if an assignment already exists for this subject and get the teacher's ID
                                                $assignedTeacherId = $classSection->assignments->where('subject_id', $subject->id)->first()->user_id ?? null;
                                            @endphp
                                            <option value="{{ $teacher->id }}" @selected($assignedTeacherId == $teacher->id)>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4 border-dashed border-2 border-gray-300 dark:border-gray-600 rounded-lg">
                                <p class="text-gray-500 dark:text-gray-400">No subjects are assigned to this class.</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Assign subjects in the main "Class Management" area before assigning teachers.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-end mt-8">
                         <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                            Update Class & Assignments
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>