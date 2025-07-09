<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('admin.classes.update', $class->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Name Field --}}
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $class->name)" required autofocus />
                        </div>

                        {{-- Subject Dropdown --}}
                         <div class="mt-4">
                            <x-label for="subject_id" value="{{ __('Subject') }}" />
                            <select name="subject_id" id="subject_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @if(old('subject_id', $class->subject_id) == $subject->id) selected @endif>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Teacher Dropdown --}}
                         <div class="mt-4">
                            <x-label for="teacher_id" value="{{ __('Teacher') }}" />
                            <select name="teacher_id" id="teacher_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @if(old('teacher_id', $class->teacher_id) == $teacher->id) selected @endif>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>

                         {{-- Academic Session Dropdown --}}
                         <div class="mt-4">
                            <x-label for="academic_session_id" value="{{ __('Academic Session') }}" />
                            <select name="academic_session_id" id="academic_session_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Session</option>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}" @if(old('academic_session_id', $class->academic_session_id) == $session->id) selected @endif>{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            {{-- Standard anchor tag for Cancel button --}}
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.classes.index') }}">
                                {{ __('Cancel') }}
                            </a>

                            {{-- === CORRECTED: Ensure this is using x-primary-button === --}}
                            {{-- This will render the "Save Class" button --}}
                            <x-primary-button class="ms-4">
                                {{ __('Save Class') }}
                            </x-primary-button>
                            {{-- ======================================================= --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>