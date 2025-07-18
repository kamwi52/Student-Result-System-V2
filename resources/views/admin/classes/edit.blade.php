<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Class: ') }} {{ $classSection->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form method="POST" action="{{ route('admin.classes.update', $classSection) }}">
                        @csrf
                        @method('PUT')

                        <!-- Class Name, Session, and Grading System (remain the same) -->
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Class Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $classSection->name)" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="academic_session_id" :value="__('Academic Session')" />
                                <select id="academic_session_id" name="academic_session_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($academicSessions as $session)
                                        <option value="{{ $session->id }}" @selected(old('academic_session_id', $classSection->academic_session_id) == $session->id)>{{ $session->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="grading_scale_id" :value="__('Grading System')" />
                                <select id="grading_scale_id" name="grading_scale_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($gradingScales as $scale)
                                        <option value="{{ $scale->id }}" @selected(old('grading_scale_id', $classSection->grading_scale_id) == $scale->id)>{{ $scale->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- === THE NEW, SMART ASSIGNMENT MATRIX === -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Subject & Teacher Assignments</h3>
                            <p class="text-sm text-gray-600 mt-1">Select the subjects taught in this class and assign a teacher to each.</p>

                            <div class="mt-4 border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Teacher</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php
                                            // Create lookup arrays for easier checking inside the loop
                                            $assignedSubjectIds = $classSection->subjects->pluck('id')->toArray();
                                            $teacherAssignments = $classSection->subjects->keyBy('id');
                                        @endphp

                                        @foreach ($subjects as $subject)
                                            <tr>
                                                <td class="px-4 py-2">
                                                    {{-- This checkbox assigns the subject to the class --}}
                                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm"
                                                           @checked(in_array($subject->id, $assignedSubjectIds))>
                                                </td>
                                                <td class="px-4 py-2 font-medium text-gray-900">
                                                    {{ $subject->name }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{-- This dropdown assigns a teacher to that subject --}}
                                                    <select name="assignments[{{ $subject->id }}]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                        <option value="">-- No Teacher --</option>
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}" 
                                                                @selected(optional(optional($teacherAssignments->get($subject->id))->pivot)->teacher_id == $teacher->id)>
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

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.classes.index') }}" class="text-sm text-gray-600 mr-4">Cancel</a>
                            <x-primary-button>Update Class & Assignments</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>