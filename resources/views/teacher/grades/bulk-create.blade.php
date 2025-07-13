<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Grade Entry - Select Class & Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">Please select the class and assessment, then choose your entry method.</p>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form id="selection-form" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="class_id">Select Class</label>
                            <select name="class_id" id="class_id" required class="mt-1 block w-full rounded-md">
                                <option value="">-- Choose a class --</option>
                                @foreach($teacherClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                           <label for="assessment_id">Select Assessment</label>
                           <select name="assessment_id" id="assessment_id" required class="mt-1 block w-full rounded-md">
                               <option value="">-- Choose an assessment --</option>
                               @foreach($assessments as $assessment)
                                   <option value="{{ $assessment->id }}">{{ $assessment->name }} ({{ $assessment->subject?->name ?? 'N/A' }})</option>
                               @endforeach
                           </select>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6 border-t pt-6 space-x-4">
                            <button type="button" id="manual-btn" class="px-5 py-2 bg-gray-700 text-white rounded-md">Enter Manually</button>
                            <button type="button" id="import-btn" class="px-5 py-2 bg-blue-600 text-white rounded-md">Import from File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('manual-btn').addEventListener('click', function() {
            const form = document.getElementById('selection-form');
            form.action = "{{ route('teacher.grades.bulk.show') }}";
            form.submit();
        });
        document.getElementById('import-btn').addEventListener('click', function() {
            const form = document.getElementById('selection-form');
            form.action = "{{ route('teacher.grades.import.show') }}";
            // We need to change the method to GET for the import form page
            form.method = "GET"; 
            form.submit();
        });
    </script>
</x-app-layout>