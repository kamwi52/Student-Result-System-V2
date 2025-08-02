<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Import Results - Step 1: Select Class') }}
            </h2>
            <a href="{{ route('admin.results.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                ← Back to Results
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Please select the class for which you want to import assessment results.
                    </p>

                    {{-- === THIS IS THE FIX: Changed method="GET" to method="POST" === --}}
                    <form method="POST" action="{{ route('admin.results.import.prepare_step2') }}">
                        @csrf
                        
                        <div>
                            <label for="class_section_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class Section</label>
                            <select id="class_section_id" name="class_section_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                <option value="" disabled selected>-- Select a class --</option>
                                @foreach($classSections as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->academicSession->name ?? 'No Session' }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('class_section_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">
                                Next: Select Assessment & Upload File →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>