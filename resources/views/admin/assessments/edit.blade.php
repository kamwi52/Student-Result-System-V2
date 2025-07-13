<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.assessments.update', $assessment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Assessment Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Assessment Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $assessment->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Subject Dropdown -->
                        <div class="mb-4">
                            <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select name="subject_id" id="subject_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $assessment->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ============================================= -->
                        <!-- ====== THE NEW CLASS DROPDOWN IS HERE ======= -->
                        <!-- ============================================= -->
                        <div class="mb-4">
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="class_id" id="class_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" disabled>-- Select a Class --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $assessment->class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- ============================================= -->

                        <!-- Max Marks -->
                        <div class="mb-4">
                            <label for="max_marks" class="block text-sm font-medium text-gray-700">Max Marks</label>
                            <input type="number" name="max_marks" id="max_marks" value="{{ old('max_marks', $assessment->max_marks) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Weightage -->
                        <div class="mb-4">
                            <label for="weightage" class="block text-sm font-medium text-gray-700">Weightage (%)</label>
                            <input type="number" name="weightage" id="weightage" value="{{ old('weightage', $assessment->weightage) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Academic Session Dropdown -->
                        <div class="mb-4">
                            <label for="academic_session_id" class="block text-sm font-medium text-gray-700">Academic Session</label>
                            <select name="academic_session_id" id="academic_session_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}" {{ old('academic_session_id', $assessment->academic_session_id) == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                             <a href="{{ route('admin.assessments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md">
                                Save Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>