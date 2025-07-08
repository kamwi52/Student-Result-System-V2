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
                    <form method="POST" action="{{ route('admin.classes.update', $schoolClass) }}">
                        @csrf
                        @method('PUT')
                         <div>
                            <x-label for="name" value="Class Name (e.g., Physics - Section 9A)" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $schoolClass->name)" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="subject_id" value="Subject" />
                            <select name="subject_id" id="subject_id" class="block mt-1 w-full border-gray-300 rounded-md">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected($schoolClass->subject_id == $subject->id)>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mt-4">
                            <x-label for="teacher_id" value="Teacher" />
                            <select name="teacher_id" id="teacher_id" class="block mt-1 w-full border-gray-300 rounded-md">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected($schoolClass->teacher_id == $teacher->id)>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-label for="academic_session_id" value="Academic Session" />
                            <select name="academic_session_id" id="academic_session_id" class="block mt-1 w-full border-gray-300 rounded-md">
                                @foreach ($academicSessions as $session)
                                    <option value="{{ $session->id }}" @selected($schoolClass->academic_session_id == $session->id)>
                                        {{ $session->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-label for="description" value="Description" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md">{{ old('description', $schoolClass->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>Update Class</x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>