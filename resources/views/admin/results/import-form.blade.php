<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Assessment Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
                    <ol class="list-decimal list-inside text-sm text-gray-600 space-y-1 mt-2">
                        <li><strong>Step 1:</strong> Select the Class you are importing results for.</li>
                        <li><strong>Step 2:</strong> Select the specific Assessment from the list that appears.</li>
                        <li><strong>Step 3:</strong> Choose your CSV file. It must have two columns in this exact order: <strong>student_email,score</strong>. An optional third column for `remarks` can be included.</li>
                    </ol>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Import Status</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('import_errors'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Please fix these errors:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @if(is_array(session('import_errors')))
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            @else
                                <li>{{ session('import_errors') }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.results.import.handle') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Step 1: Class Dropdown -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Step 1: Class</label>
                        <select name="class_id" id="class_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Select a Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Step 2: Assessment Dropdown (Populated by JS) -->
                    <div>
                        <label for="assessment_id" class="block text-sm font-medium text-gray-700">Step 2: Assessment</label>
                        <select name="assessment_id" id="assessment_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" disabled>
                            <option value="">-- Select a Class First --</option>
                        </select>
                    </div>

                    <!-- Step 3: File Input -->
                    <div>
                        <label for="results_file" class="block text-sm font-medium text-gray-700">Step 3: Results CSV File</label>
                        <input type="file" name="results_file" id="results_file" required class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    </div>
                    
                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Import Results
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classAssessmentsMap = @json($classAssessmentsMap);
            const classSelect = document.getElementById('class_id');
            const assessmentSelect = document.getElementById('assessment_id');
            const oldClassId = "{{ old('class_id') }}";
            const oldAssessmentId = "{{ old('assessment_id') }}";

            function populateAssessments(classId, selectedAssessmentId = null) {
                const assessments = classAssessmentsMap[classId] || [];
                assessmentSelect.innerHTML = '<option value="">-- Select an Assessment --</option>'; // Reset

                if (assessments.length > 0) {
                    assessments.forEach(assessment => {
                        const option = document.createElement('option');
                        option.value = assessment.id;
                        option.textContent = assessment.display_name;
                        if (assessment.id == selectedAssessmentId) {
                            option.selected = true;
                        }
                        assessmentSelect.appendChild(option);
                    });
                    assessmentSelect.disabled = false;
                } else {
                    assessmentSelect.innerHTML = '<option value="">-- No assessments found for this class --</option>';
                    assessmentSelect.disabled = true;
                }
            }
            
            classSelect.addEventListener('change', function () {
                const selectedClassId = this.value;
                populateAssessments(selectedClassId);
            });

            // On page load, if there's an old class ID (e.g., due to validation error),
            // trigger the change to populate the assessments and re-select the old assessment.
            if (oldClassId) {
                populateAssessments(oldClassId, oldAssessmentId);
            }
        });
    </script>
</x-app-layout>