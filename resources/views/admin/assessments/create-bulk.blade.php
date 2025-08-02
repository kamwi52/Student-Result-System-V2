<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bulk Create Assessments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.assessments.bulk-create.handle') }}">
                        @csrf
                        <div class="mb-6">
                            <label for="class_section_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">1. Select a Class</label>
                            <select id="class_section_id" name="class_section_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <option value="" disabled selected>-- Choose a class --</option>
                                @foreach ($classSections as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">2. Select Subjects for Assessments</label>
                            <div class="p-4 border border-gray-300 rounded-lg h-48 overflow-y-auto dark:border-gray-600">
                                <div id="subjects-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <p class="text-gray-500 col-span-full">Please select a class to see its subjects.</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('subject_ids')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="base_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">3. Enter a Base Name</label>
                            <input type="text" id="base_name" name="base_name" value="{{ old('base_name', 'End of Term') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" placeholder="e.g., Final Exam">
                            <p class="mt-1 text-xs text-gray-500">The subject name will be added automatically, e.g., "End of Term (Mathematics)".</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="term_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">4. Select Term</label>
                                <select id="term_id" name="term_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="" disabled selected>-- Choose a term --</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="assessment_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">5. Assessment Date</label>
                                <input type="date" id="assessment_date" name="assessment_date" value="{{ old('assessment_date') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="max_marks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">6. Max Marks (for all)</label>
                                <input type="number" id="max_marks" name="max_marks" value="{{ old('max_marks', 100) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            </div>
                            <div>
                                <label for="weightage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">7. Weightage % (for all)</label>
                                <input type="number" id="weightage" name="weightage" value="{{ old('weightage', 100) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.assessments.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline mr-4">Cancel</a>
                            <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5">
                                Create Assessments
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classSelect = document.getElementById('class_section_id');
            const subjectsList = document.getElementById('subjects-list');
            const baseUrl = "{{ url('/admin/classes') }}";

            classSelect.addEventListener('change', function () {
                const selectedClassId = this.value;
                subjectsList.innerHTML = '<p class="text-gray-500 col-span-full">Loading subjects...</p>';

                if (!selectedClassId) {
                    subjectsList.innerHTML = '<p class="text-gray-500 col-span-full">Please select a class to see its subjects.</p>';
                    return;
                }

                // Make a fetch request to our new route
                fetch(`${baseUrl}/${selectedClassId}/subjects`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(subjects => {
                        subjectsList.innerHTML = ''; // Clear loading message

                        if (subjects.length > 0) {
                            subjects.forEach(subject => {
                                const label = document.createElement('label');
                                label.className = 'flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700';
                                
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'subject_ids[]';
                                checkbox.value = subject.id;
                                checkbox.checked = true;
                                checkbox.className = 'w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500';
                                
                                const span = document.createElement('span');
                                span.className = 'ms-2 text-sm font-medium text-gray-900 dark:text-gray-300';
                                span.textContent = subject.name;

                                label.appendChild(checkbox);
                                label.appendChild(span);
                                subjectsList.appendChild(label);
                            });
                        } else {
                            subjectsList.innerHTML = '<p class="text-gray-500 col-span-full">This class has no subjects assigned.</p>';
                        }
                    })
                    .catch(error => {
                        subjectsList.innerHTML = '<p class="text-red-500 col-span-full">Could not load subjects. Please try again.</p>';
                        console.error('Error fetching subjects:', error);
                    });
            });
        });
    </script>
    @endpush
</x-app-layout>