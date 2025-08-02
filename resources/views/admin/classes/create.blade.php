<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Class') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.classes.store') }}">
                        @csrf

                        <!-- Class Name -->
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="e.g., Grade 10A">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Academic Session & Grading System -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="academic_session_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Session</label>
                                <select id="academic_session_id" name="academic_session_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    @foreach($academicSessions as $session)
                                        <option value="{{ $session->id }}" @selected(old('academic_session_id') == $session->id)>
                                            {{ $session->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('academic_session_id')" class="mt-2" />
                            </div>
                            <div>
                                <label for="grading_scale_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grading System</label>
                                <select id="grading_scale_id" name="grading_scale_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    @foreach($gradingScales as $scale)
                                        <option value="{{ $scale->id }}" @selected(old('grading_scale_id') == $scale->id)>
                                            {{ $scale->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('grading_scale_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Assign Subjects to this Class -->
                        <div class="mb-6">
                             <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assign Subjects to this Class</label>
                            <div class="mt-2 p-4 border border-gray-300 rounded-lg h-48 overflow-y-auto dark:border-gray-600">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($subjects as $subject)
                                    <label for="subject_{{ $subject->id }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" id="subject_{{ $subject->id }}" name="subjects[]" value="{{ $subject->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $subject->name }}</span>
                                    </label>
                                @endforeach
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('subjects')" class="mt-2" />
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('Create Class') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>