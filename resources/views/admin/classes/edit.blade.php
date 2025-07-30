<x-app-layout>
    {{-- Page Header with Back Button --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Class: ') }} {{ $classSection->name }}
            </h2>
            <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Back to Classes
            </a>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.classes.update', $classSection) }}">
                        @csrf
                        @method('PUT')

                        {{-- Class Name --}}
                        <div class="mb-6">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $classSection->name) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Academic Session & Grading System --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="academic_session_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Academic Session</label>
                                <select id="academic_session_id" name="academic_session_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($academicSessions as $session)
                                        <option value="{{ $session->id }}" @selected(old('academic_session_id', $classSection->academic_session_id) == $session->id)>{{ $session->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="grading_scale_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Grading System</label>
                                <select id="grading_scale_id" name="grading_scale_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($gradingScales as $scale)
                                        <option value="{{ $scale->id }}" @selected(old('grading_scale_id', $classSection->grading_scale_id) == $scale->id)>{{ $scale->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Subject & Teacher Assignment Matrix --}}
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Subject & Teacher Assignments</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Select the subjects taught in this class and assign a teacher to each.</p>

                            <div class="mt-4 border dark:border-gray-600 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-1/12">Assign</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-5/12">Subject</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-6/12">Assigned Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @php
                                            $assignedSubjectIds = $classSection->subjects->pluck('id')->toArray();
                                            $teacherAssignments = $classSection->subjects->keyBy('id');
                                        @endphp

                                        @foreach ($subjects as $subject)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-2">
                                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" @checked(in_array($subject->id, $assignedSubjectIds))>
                                                </td>
                                                <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">
                                                    {{ $subject->name }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <select name="assignments[{{ $subject->id }}]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                                        <option value="">-- No Teacher --</option>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" @selected(optional(optional($teacherAssignments->get($subject->id))->pivot)->teacher_id == $teacher->id)>
                                                                {{ $teacher->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('admin.classes.index') }}" class="font-medium text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Update Class & Assignments</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>