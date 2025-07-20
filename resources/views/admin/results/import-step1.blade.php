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

                    {{-- Display Import Errors --}}
                    @if(session('import_error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Import Failed!</p>
                            <p>{{ session('import_error') }}</p>
                        </div>
                    @endif
                    @if(session('import_errors'))
                        <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4" role="alert">
                            <p class="font-bold">Please fix these errors in your file:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach(session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.results.import.process') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Select Assessment -->
                        <div class="mb-6">
                            <x-input-label for="assessment_id" :value="__('Step 1: Choose an Assessment')" class="text-lg" />
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Select the assessment for which you are importing scores.</p>
                            <select name="assessment_id" id="assessment_id" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Please select an assessment --</option>
                                @foreach($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" {{ old('assessment_id') == $assessment->id ? 'selected' : '' }}>
                                        {{ $assessment->classSection->name }} - {{ $assessment->subject->name }} - {{ $assessment->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assessment_id')" class="mt-2" />
                        </div>
                        
                        <!-- Step 2: Upload File -->
                        <div>
                             <x-input-label for="file" :value="__('Step 2: Upload Results File')" class="text-lg" />
                             <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Your file must have a header row with columns: `student_email` and `score`.</p>
                            <x-text-input id="file" class="block w-full mt-1" type="file" name="file" required accept=".xlsx,.csv,.xls" />
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                             <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('admin.results.index') }}">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Import Results') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>