<x-app-layout>
    <x-slot name="header">
        {{-- CORRECTED: Use $classSection instead of $class --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Class: ') }} {{ $classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- CORRECTED: Use $classSection instead of $class --}}
                <form method="POST" action="{{ route('admin.classes.update', $classSection->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Class Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Name</label>
                        {{-- CORRECTED: Use $classSection instead of $class --}}
                        <input type="text" name="name" id="name" value="{{ old('name', $classSection->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Teacher Dropdown -->
                    <div class="mb-4">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Teacher') }}</label>
                        <select name="teacher_id" id="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                            <option value="">-- Unassigned --</option>
                            @foreach($teachers as $teacher)
                                {{-- CORRECTED: Use $classSection instead of $class --}}
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $classSection->teacher_id) == $teacher->id)>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Academic Session Dropdown -->
                    <div class="mb-4">
                        <label for="academic_session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Academic Session') }}</label>
                        <select name="academic_session_id" id="academic_session_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                            @foreach($academicSessions as $session)
                                {{-- CORRECTED: Use $classSection instead of $class --}}
                                <option value="{{ $session->id }}" @selected(old('academic_session_id', $classSection->academic_session_id) == $session->id)>
                                    {{ $session->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Grading Scale Dropdown -->
                    <div class="mb-4">
                        <label for="grading_scale_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Grading System') }}</label>
                        <select name="grading_scale_id" id="grading_scale_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                             <option value="">-- Not Set --</option>
                             @foreach($gradingScales as $scale)
                                {{-- CORRECTED: Use $classSection instead of $class --}}
                                <option value="{{ $scale->id }}" @selected(old('grading_scale_id', $classSection->grading_scale_id) == $scale->id)>
                                    {{ $scale->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subjects Checkboxes -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Update Assigned Subjects</label>
                        <div class="mt-2 space-y-2 rounded-md border border-gray-200 dark:border-gray-700 p-4 max-h-60 overflow-y-auto">
                            @foreach($subjects as $subject)
                                <label class="flex items-center">
                                    {{-- CORRECTED: Use $classSection instead of $class --}}
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                           @if(in_array($subject->id, $classSection->subjects->pluck('id')->toArray())) checked @endif
                                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                         <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                            Update Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>