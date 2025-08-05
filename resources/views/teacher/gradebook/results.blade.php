<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enter Results for: ') }} {{ $assessment->name }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $assessment->classSection->name }} - {{ $assessment->subject->name }}
                            </h3>
                            <p class="text-sm text-gray-500">Max Marks: {{ $assessment->max_marks }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            {{-- === THIS IS THE NEW IMPORT BUTTON === --}}
                            <button @click="openModal = true" type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Import Results
                            </button>
                            <a href="{{ route('teacher.gradebook.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
                                Back to Gradebook
                            </a>
                        </div>
                    </div>
                    
                    @if($students->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400">There are no students enrolled in this class.</p>
                    @else
                        <form method="POST" action="{{ route('teacher.gradebook.results.store', $assessment) }}">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    {{-- ... table header ... --}}
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($students as $student)
                                            <tr>
                                                <td class="px-6 py-4 ...">{{ $student->name }}</td>
                                                <td class="px-6 py-4 ...">
                                                    <input type="number" name="scores[{{ $student->id }}]"
                                                           value="{{ old('scores.' . $student->id, $results->get($student->id)->score ?? '') }}"
                                                           class="w-24 ... rounded-md shadow-sm">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Save Manually
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- === THIS IS THE NEW MODAL POP-UP === -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="openModal = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Import Results for {{ $assessment->name }}</h3>
                
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4 space-y-1">
                    <p>Your CSV file must have two columns in this exact order:</p>
                    <ul class="list-disc list-inside">
                        <li><strong>student_email</strong></li>
                        <li><strong>score</strong></li>
                    </ul>
                </div>
                
                <form method="POST" action="{{ route('teacher.gradebook.results.import', $assessment) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="results_file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600">
                    
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload and Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>