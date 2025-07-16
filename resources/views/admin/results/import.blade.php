<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Student Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-validation-errors class="mb-4" />
                    {{-- ... (error display logic) ... --}}

                    <form method="POST" action="{{ route('admin.results.import.handle') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="class_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">1. Select Class</label>
                                <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Choose a class --</option>
                                    @foreach($classes as $class)
                                        {{-- Ensure data-subjects attribute is present --}}
                                        <option value="{{ $class->id }}" data-subjects="{{ $class->subjects->pluck('id')->toJson() }}">
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="assessment_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">2. Select Assessment</label>
                                <select name="assessment_id" id="assessment_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                                    <option value="">-- Select a class first --</option>
                                </select>
                            </div>
                        </div>

                        {{-- ... (File Upload and Buttons) ... --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function() {
                const classSelect = document.getElementById('class_id');
                const assessmentSelect = document.getElementById('assessment_id');

                if (!classSelect || !assessmentSelect) {
                    console.error('Error: Could not find class_id or assessment_id elements.');
                    return;
                }

                let assessmentsBySubject = {};
                try {
                    assessmentsBySubject = @json($assessments->groupBy('subject_id'));
                } catch (e) {
                    console.error('Error parsing assessments data.', e);
                    return;
                }

                classSelect.addEventListener('change', function() {
                    assessmentSelect.innerHTML = '<option value="">-- Loading... --</option>';
                    assessmentSelect.disabled = true;

                    const selectedOption = this.options[this.selectedIndex];
                    const subjectsData = selectedOption.getAttribute('data-subjects');
                    
                    let subjectIds = [];
                    
                    // === THE FIX: Robustly parse the data attribute ===
                    if (subjectsData && subjectsData !== '[]') {
                        try {
                            subjectIds = JSON.parse(subjectsData);
                        } catch (e) {
                            console.error('Failed to parse subject IDs:', subjectsData, e);
                            subjectIds = [];
                        }
                    }
                    // ==================================================

                    if (subjectIds.length === 0) {
                        assessmentSelect.innerHTML = '<option value="">-- This class has no subjects assigned --</option>';
                        return;
                    }

                    let availableAssessments = [];
                    subjectIds.forEach(subjectId => {
                        if (assessmentsBySubject[subjectId]) {
                            availableAssessments = availableAssessments.concat(assessmentsBySubject[subjectId]);
                        }
                    });
                    
                    assessmentSelect.innerHTML = '<option value="">-- Choose an assessment --</option>';
                    if (availableAssessments.length > 0) {
                        availableAssessments.forEach(assessment => {
                            const option = document.createElement('option');
                            option.value = assessment.id;
                            // Ensure subject exists before accessing its name
                            const subjectName = (assessment.subject && assessment.subject.name) ? assessment.subject.name : 'Unknown Subject';
                            option.textContent = `${subjectName} - ${assessment.name}`;
                            assessmentSelect.appendChild(option);
                        });
                        assessmentSelect.disabled = false;
                    } else {
                        assessmentSelect.innerHTML = '<option value="">-- No assessments found for this class --</option>';
                    }
                });
            })();
        });
    </script>
    @endpush
</x-app-layout>