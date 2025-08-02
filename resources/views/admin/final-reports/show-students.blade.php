<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Generate Ranked Report Cards (Step 2 of 2)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">
                            {{ $term->name }} Reports for: {{ $classSection->name }}
                        </h3>
                        <a href="{{ route('admin.final-reports.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            ← Back to Selection
                        </a>
                    </div>
                    
                    @if($students->isEmpty())
                        <p class="text-center text-gray-500 py-8">There are no students enrolled in this class.</p>
                    @else
                        {{-- Bulk Generation Form --}}
                        <form action="{{ route('admin.final-reports.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $classSection->id }}">
                            <input type="hidden" name="term_id" value="{{ $term->id }}">

                            <div class="border-t border-b border-gray-200 dark:border-gray-700 py-2">
                                <div class="flex items-center px-4">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <label for="select-all" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">Select All</label>
                                </div>
                            </div>
                            
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($students as $student)
                                    {{-- === THIS IS THE UPDATED SECTION === --}}
                                    <li class="px-4 py-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                            <label class="ml-3 block text-sm text-gray-800 dark:text-gray-200">{{ $student->name }}</label>
                                        </div>
                                        {{-- New "Generate" link for a single student --}}
                                        <a href="{{ route('admin.final-reports.generate-single', ['student_id' => $student->id, 'class_id' => $classSection->id, 'term_id' => $term->id]) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            Generate
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Generate Selected Reports
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
    @endpush
</x-app-layout>