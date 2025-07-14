<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('admin.classes.store') }}">
                    @csrf

                    <!-- Class Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Name</label>
                        <input id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Grade 8A, Year 10 Section B" />
                    </div>

                    <!-- Teacher Dropdown -->
                    <div class="mt-4">
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Teacher') }}</label>
                        <select name="teacher_id" id="teacher_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500 rounded-md">
                            <option value="">-- Unassigned --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @if(old('teacher_id') == $teacher->id) selected @endif>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Academic Session Dropdown -->
                    <div class="mt-4">
                        <label for="academic_session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Academic Session') }}</label>
                        <select name="academic_session_id" id="academic_session_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500 rounded-md">
                            <option value="">Select Session</option>
                            @foreach($academicSessions as $session)
                                <option value="{{ $session->id }}" @if(old('academic_session_id') == $session->id) selected @endif>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>

                     <!-- Grading Scale Dropdown -->
                     <div class="mt-4">
                        <label for="grading_scale_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Grading System') }}</label>
                        <select name="grading_scale_id" id="grading_scale_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500 rounded-md">
                            <option value="">-- Not Set --</option>
                            @foreach($gradingScales as $scale)
                                <option value="{{ $scale->id }}" @if(old('grading_scale_id') == $scale->id) selected @endif>{{ $scale->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subjects Checkboxes -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Subjects to this Class</label>
                        <div class="mt-2 space-y-2 rounded-md border border-gray-200 dark:border-gray-700 p-4 max-h-60 overflow-y-auto">
                            @forelse($subjects as $subject)
                                <label class="flex items-center">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                           class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                </label>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No subjects found. Please <a href="{{ route('admin.subjects.create') }}" class="text-blue-500 hover:underline">create a subject</a> first.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <x-primary-button>
                            Create Class
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>