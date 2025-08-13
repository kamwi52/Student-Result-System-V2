<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Results - Step 1: Select Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Please select the class for which you want to manage results.
                    </p>
                    <form method="GET" action="{{ route('teacher.results.manage.step2') }}">
                        <div>
                            <label for="class_section_id" class="block mb-2 text-sm font-medium">Class Section</label>
                            <select id="class_section_id" name="class_section_id" required class="bg-gray-50 border ...">
                                <option value="">-- Select a class --</option>
                                @foreach($classSections as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->academicSession->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 ...">
                                Next: Select Assessment →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>