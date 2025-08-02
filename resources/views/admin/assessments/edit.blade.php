<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Assessment: ') }} {{ $assessment->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.assessments.update', $assessment) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assessment Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $assessment->name) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <label for="subject_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subject</label>
                                <select id="subject_id" name="subject_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $assessment->subject_id) == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="academic_session_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Session</label>
                                <select id="academic_session_id" name="academic_session_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($academicSessions as $session)
                                        <option value="{{ $session->id }}" @selected(old('academic_session_id', $assessment->academic_session_id) == $session->id)>{{ $session->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('academic_session_id')" class="mt-2" />
                            </div>
                            <div>
                                <label for="class_section_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class</label>
                                <select id="class_section_id" name="class_section_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($classSections as $class)
                                        <option value="{{ $class->id }}" @selected(old('class_section_id', $assessment->class_section_id) == $class->id)>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('class_section_id')" class="mt-2" />
                            </div>
                        </div>

                        {{-- === FIX: ADDED TERM SELECTION DROPDOWN === --}}
                        <div class="mb-6">
                            <label for="term_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Term</label>
                            <select id="term_id" name="term_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                @foreach($terms as $term)
                                    <option value="{{ $term->id }}" @selected(old('term_id', $assessment->term_id) == $term->id)>{{ $term->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('term_id')" class="mt-2" />
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="max_marks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max Marks</label>
                                <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', $assessment->max_marks) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <x-input-error :messages="$errors->get('max_marks')" class="mt-2" />
                            </div>
                            <div>
                                <label for="weightage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Weightage (%)</label>
                                <input type="number" id="weightage" name="weightage" value="{{ old('weightage', $assessment->weightage) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <x-input-error :messages="$errors->get('weightage')" class="mt-2" />
                            </div>
                             <div>
                                <label for="assessment_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assessment Date</label>
                                <input type="date" id="assessment_date" name="assessment_date" value="{{ old('assessment_date', $assessment->assessment_date) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <x-input-error :messages="$errors->get('assessment_date')" class="mt-2" />
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.assessments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                                Update Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>