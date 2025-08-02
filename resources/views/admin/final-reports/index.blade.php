<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generate Ranked Report Cards (Step 1 of 2)') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <x-success-message />
            <x-error-message />

            {{-- Main Card --}}
            <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Select Class and Term
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Choose a class and a report term (e.g., First Term) to begin the report generation process.
                    </p>

                    <form action="{{ route('admin.final-reports.show-students') }}" method="GET">
                        {{-- Note: No @csrf is needed for a GET request --}}
                        <div class="space-y-6">
                            {{-- Class Selection --}}
                            <div>
                                <label for="class_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Class</label>
                                <select id="class_id" name="class_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>-- Please choose a class --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->academicSession->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Term Selection --}}
                            <div>
                                <label for="term_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Report Term</label>
                                <select id="term_id" name="term_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>-- Please choose a term --</option>
                                    @foreach ($terms as $term)
                                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('View Students') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>