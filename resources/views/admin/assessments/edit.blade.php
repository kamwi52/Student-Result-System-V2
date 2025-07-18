<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Assessment: ') }} {{ $assessment->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.assessments.update', $assessment) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Assessment Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $assessment->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Subject -->
                        <div class="mb-4">
                            <x-input-label for="subject_id" :value="__('Subject')" />
                            <select id="subject_id" name="subject_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id', $assessment->subject_id) == $subject->id)>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>

                        <!-- Academic Session -->
                        <div class="mb-4">
                            <x-input-label for="academic_session_id" :value="__('Academic Session')" />
                            <select id="academic_session_id" name="academic_session_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Academic Session</option>
                                @foreach($academicSessions as $session)
                                    <option value="{{ $session->id }}" @selected(old('academic_session_id', $assessment->academic_session_id) == $session->id)>{{ $session->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('academic_session_id')" class="mt-2" />
                        </div>

                        <!-- Max Marks -->
                        <div class="mb-4">
                            <x-input-label for="max_marks" :value="__('Max Marks')" />
                            <x-text-input id="max_marks" class="block mt-1 w-full" type="number" name="max_marks" :value="old('max_marks', $assessment->max_marks)" required min="0" />
                            <x-input-error :messages="$errors->get('max_marks')" class="mt-2" />
                        </div>

                        <!-- Weightage (Optional) -->
                        <div class="mb-4">
                            <x-input-label for="weightage" :value="__('Weightage (%)')" />
                            <x-text-input id="weightage" class="block mt-1 w-full" type="number" name="weightage" :value="old('weightage', $assessment->weightage)" min="0" max="100" />
                            <x-input-error :messages="$errors->get('weightage')" class="mt-2" />
                        </div>

                        <!-- Assessment Date -->
                        <div class="mb-4">
                            <x-input-label for="assessment_date" :value="__('Assessment Date')" />
                            <x-text-input id="assessment_date" class="block mt-1 w-full" type="date" name="assessment_date" :value="old('assessment_date', $assessment->assessment_date)" required />
                            <x-input-error :messages="$errors->get('assessment_date')" class="mt-2" />
                        </div>

                        <!-- Assigned Teacher -->
                        <div class="mb-4">
                            <x-input-label for="teacher_id" :value="__('Assigned Teacher')" />
                            <select id="teacher_id" name="teacher_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('teacher_id', $assessment->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                        </div>

                        <!-- === Class Section Assignment (NEW FIELD) === -->
                        <div class="mb-4">
                            <x-input-label for="class_section_id" :value="__('Assigned Class')" />
                            <select id="class_section_id" name="class_section_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Class</option>
                                @foreach($classSections as $classSection)
                                    <option value="{{ $classSection->id }}" @selected(old('class_section_id', $assessment->class_section_id) == $classSection->id)>{{ $classSection->name }} - {{ $classSection->academicSession->name ?? '' }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('class_section_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.assessments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Assessment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>