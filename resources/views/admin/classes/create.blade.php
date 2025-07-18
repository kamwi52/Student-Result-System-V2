<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.classes.store') }}">
                        @csrf

                        <!-- Class Name -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Class Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Academic Session & Grading System -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <x-input-label for="academic_session_id" :value="__('Academic Session')" />
                                <select id="academic_session_id" name="academic_session_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($academicSessions as $session)
                                        <option value="{{ $session->id }}" @selected(old('academic_session_id') == $session->id)>
                                            {{ $session->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="grading_scale_id" :value="__('Grading System')" />
                                <select id="grading_scale_id" name="grading_scale_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    @foreach($gradingScales as $scale)
                                        <option value="{{ $scale->id }}" @selected(old('grading_scale_id') == $scale->id)>
                                            {{ $scale->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Assign Subjects to this Class -->
                        <div class="mb-6">
                            <x-input-label for="subjects" :value="__('Assign Subjects to this Class')" />
                            <div class="mt-2 p-4 border border-gray-200 rounded-md h-48 overflow-y-auto">
                                @foreach ($subjects as $subject)
                                    <label for="subject_{{ $subject->id }}" class="flex items-center">
                                        <input type="checkbox" id="subject_{{ $subject->id }}" name="subjects[]" value="{{ $subject->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-600">{{ $subject->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('subjects')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Class') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>