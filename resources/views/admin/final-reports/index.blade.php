<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generate Ranked Report Cards (Step 1 of 2)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Select Class and Term
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Choose a class and a report term (e.g., Mid Term) to begin.
                    </p>

                    <form action="{{ route('admin.final-reports.show-students') }}" method="GET">
                        <div class="space-y-4">
                            {{-- Class Selection --}}
                            <div>
                                <label for="class_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select Class</label>
                                <select id="class_id" name="class_id" required class="mt-1 block w-full rounded-md shadow-sm">
                                    <option value="">-- Please choose a class --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->academicSession->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Term Selection --}}
                            <div>
                                <label for="term_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select Report Term</label>
                                <select id="term_id" name="term_id" required class="mt-1 block w-full rounded-md shadow-sm">
                                    <option value="">-- Please choose a term --</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase">
                                {{ __('View Students') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>