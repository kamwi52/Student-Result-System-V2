<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Class') }}: {{ $class->name ?? 'N/A' }} {{-- Use $class->name if available --}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Use PUT method for updates --}}
                    <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                        @csrf
                        @method('PUT')

                        <!-- Class Name (Adjust field name based on your Class model) -->
                        <div>
                            <x-input-label for="name" :value="__('Class Name')" />
                            {{-- Pre-fill with existing data. Adjust field name if necessary (e.g., $class->course_name) --}}
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $class->name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{--
                        // Example: If Class has a foreign key to Subject and Teacher
                        <div class="mt-4">
                            <x-input-label for="subject_id" :value="__('Subject')" />
                            <select id="subject_id" name="subject_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Subject</option>
                                // Loop through subjects provided by the controller
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $class->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                        </div>

                         <div class="mt-4">
                            <x-input-label for="teacher_id" :value="__('Teacher')" />
                            <select id="teacher_id" name="teacher_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Teacher</option>
                                // Loop through teachers provided by the controller (e.g., users with 'teacher' role)
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                        </div>
                        --}}


                        <div class="flex items-center justify-end mt-4">
                            {{-- Add a Cancel/Back button --}}
                             <x-secondary-button href="{{ route('admin.classes.index') }}" class="ms-0">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button class="ms-4">
                                {{ __('Update Class') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>