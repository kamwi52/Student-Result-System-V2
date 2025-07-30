<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Import Results - Step 2: Upload File') }}
            </h2>
             <a href="{{ route('admin.results.import.show_step1') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Go Back to Step 1
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                
                <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
                    <p class="text-sm text-gray-600 dark:text-gray-400">You are importing results for class:</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $classSection->name }}</p>
                </div>

                <x-error-message />
                
                {{-- === THE FIX: Form action points to the final 'process' route === --}}
                <form method="POST" action="{{ route('admin.results.import.process', ['assessment' => '_placeholder_']) }}" id="import-form" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="assessment_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">1. Select an Assessment</label>
                        <select name="assessment_id" id="assessment_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                            <option value="">-- Choose an assessment --</option>
                            @foreach($assessments as $assessment)
                                <option value="{{ $assessment->id }}">{{ $assessment->subject->name }} - {{ $assessment->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('assessment_id')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">2. Upload Results CSV File</label>
                        <input type="file" name="file" id="file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600">
                        <p class="text-xs text-gray-500 mt-1 dark:text-gray-400">File must have columns: `student_email`, `score`</p>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div class="flex justify-end items-center mt-6">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Import Results</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // This script dynamically updates the form's action URL when an assessment is selected.
        document.getElementById('assessment_id').addEventListener('change', function() {
            let form = document.getElementById('import-form');
            let action = form.getAttribute('action');
            // Replace the placeholder with the actual selected assessment ID
            form.setAttribute('action', action.replace('_placeholder_', this.value));
        });
    </script>
    @endpush
</x-app-layout>