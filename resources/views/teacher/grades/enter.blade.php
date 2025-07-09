{{--
|--------------------------------------------------------------------------
| Teacher Grade Entry View
|--------------------------------------------------------------------------
|
| This view provides the form for teachers to select an assessment
| and enter scores for each student enrolled in a class.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Enter Grades for: {{ $classSection->name }} ({{ $classSection->subject->name ?? 'N/A' }})
            </h2>
            <a href="{{ route('teacher.dashboard') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Display Success Messages --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Select Assessment</h3>

                    {{-- Assessment Selection Form --}}
                    <form method="GET" action="{{ route('teacher.grades.enter', $classSection) }}" class="mb-6">
                        <div class="flex items-end space-x-4">
                            <div class="flex-grow">
                                <x-label for="assessment_id" value="{{ __('Assessment') }}" />
                                <select name="assessment_id" id="assessment_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Select an assessment --</option>
                                    @foreach($assessments as $assessment)
                                        <option value="{{ $assessment->id }}" @selected($selectedAssessment?->id == $assessment->id)>
                                            {{ $assessment->name }} (Max: {{ $assessment->max_marks }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-primary-button type="submit">Load Students</x-primary-button>
                            </div>
                        </div>
                    </form>

                    {{-- Grade Entry Form (only shows if an assessment is selected) --}}
                    @if($selectedAssessment)
                        <form method="POST" action="{{ route('teacher.grades.store', $classSection) }}">
                            @csrf
                            <input type="hidden" name="assessment_id" value="{{ $selectedAssessment->id }}">

                            <div class="overflow-x-auto border-t mt-6 pt-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                            <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score (out of {{ $selectedAssessment->max_marks }})</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($students as $index => $student)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{-- We use an array for scores to submit all at once --}}
                                                    <input type="hidden" name="scores[{{ $index }}][user_id]" value="{{ $student->id }}">
                                                    <x-text-input type="number"
                                                                  name="scores[{{ $index }}][score]"
                                                                  class="w-full"
                                                                  min="0"
                                                                  max="{{ $selectedAssessment->max_marks }}"
                                                                  {{-- Use the existing result if it exists, otherwise empty --}}
                                                                  :value="$existingResults[$student->id] ?? ''" />
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-4">No students are enrolled in this class.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Save All Grades') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8 text-gray-500 border-t mt-6 pt-6">
                            Please select an assessment from the dropdown above to begin entering grades.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>