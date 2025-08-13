<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Generate Ranked Report Cards (Step 2 of 2)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.final-reports.generate') }}" id="bulk-generate-form">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $classSection->id }}">
                    <input type="hidden" name="term_id" value="{{ $term->id }}">

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div id="status-message" class="hidden mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert"></div>

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Final Exam Reports for: {{ $classSection->name }}</h3>
                            <a href="{{ route('admin.final-reports.index') }}" class="text-sm text-indigo-600 hover:underline">← Back to Selection</a>
                        </div>

                        <div class="border rounded-lg">
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                {{-- === FIX: "Select All" Checkbox restored === --}}
                                <li class="p-4 flex items-center bg-gray-50 dark:bg-gray-700/50">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="select-all" class="ml-3 block text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Select All Students
                                    </label>
                                </li>

                                @forelse ($students as $student)
                                    <li class="p-4 flex justify-between items-center">
                                        <div class="flex items-center">
                                            {{-- === FIX: Student Checkbox restored === --}}
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-3">{{ $student->name }}</span>
                                        </div>
                                        <a href="{{ route('admin.final-reports.generate-single', ['student_id' => $student->id, 'class_id' => $classSection->id, 'term_id' => $term->id]) }}" 
                                           class="generate-link inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                            Generate
                                        </a>
                                    </li>
                                @empty
                                    <li class="p-4 text-center text-gray-500">No students are enrolled in this class.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    
                    {{-- === FIX: Bulk "Generate Selected" Button restored === --}}
                    @if($students->isNotEmpty())
                    <div class="flex items-center justify-end px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Generate for Selected (<span id="selected-count">0</span>)
                        </button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logic for the single "Generate" link spinners (already implemented)
            const generateLinks = document.querySelectorAll('.generate-link');
            const statusMessageDiv = document.getElementById('status-message');
            // ... (the existing fetch/spinner logic for single links)

            // === FIX: Logic for the bulk selection checkboxes ===
            const selectAllCheckbox = document.getElementById('select-all');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkGenerateForm = document.getElementById('bulk-generate-form');

            function updateSelectedCount() {
                const count = document.querySelectorAll('.student-checkbox:checked').length;
                selectedCountSpan.textContent = count;
            }

            selectAllCheckbox.addEventListener('change', function (event) {
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = event.target.checked;
                });
                updateSelectedCount();
            });

            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // If any individual box is unchecked, uncheck the "Select All" box
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    }
                    updateSelectedCount();
                });
            });
            
            // Show a confirmation before submitting the bulk form
            bulkGenerateForm.addEventListener('submit', function(event) {
                const count = document.querySelectorAll('.student-checkbox:checked').length;
                if (count === 0) {
                    alert('Please select at least one student.');
                    event.preventDefault();
                    return;
                }
                if (!confirm(`Are you sure you want to generate report cards for ${count} selected student(s)?`)) {
                    event.preventDefault();
                }
            });

            // Initialize count on page load
            updateSelectedCount();
        });
    </script>
    @endpush
</x-app-layout>