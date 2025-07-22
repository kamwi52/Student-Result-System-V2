<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report Generation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Generate Reports by Class
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Select a class from the dropdown below to generate a multi-page PDF containing a ranked report card for every student enrolled.
                    </p>

                    <form id="class-report-form" action="" method="GET" target="_blank">
                        <div class="flex items-end space-x-4">
                            <div class="flex-grow">
                                <label for="class_section" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select Class</label>
                                <select id="class_section" name="class_section" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="">-- Please choose a class --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ route('admin.class-sections.report', $class) }}">
                                            {{ $class->name }} ({{ $class->academicSession->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" id="generate-report-btn" disabled class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                    {{ __('Generate Report') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Use an event listener to make sure the whole page is loaded first
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Log a message to prove the script is running
            console.log('Report page JavaScript is now running!');

            // 2. Find the elements we need
            const classSelect = document.getElementById('class_section');
            const reportForm = document.getElementById('class-report-form');
            const generateBtn = document.getElementById('generate-report-btn');

            // 3. Check if we found them and log the results
            if (!classSelect) {
                console.error('ERROR: Could not find the dropdown with ID "class_section"');
            }
            if (!reportForm) {
                console.error('ERROR: Could not find the form with ID "class-report-form"');
            }
            if (!generateBtn) {
                console.error('ERROR: Could not find the button with ID "generate-report-btn"');
            }

            // 4. Only add the event listener if all elements were found
            if (classSelect && reportForm && generateBtn) {
                console.log('Successfully found all elements. Attaching event listener.');
                classSelect.addEventListener('change', function () {
                    console.log('Dropdown value changed to:', this.value);

                    if (this.value) {
                        // If a class is selected
                        reportForm.action = this.value;
                        generateBtn.disabled = false;
                        console.log('Button has been ENABLED.');
                    } else {
                        // If "-- Please choose a class --" is selected
                        reportForm.action = '';
                        generateBtn.disabled = true;
                        console.log('Button has been DISABLED.');
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>