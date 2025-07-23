<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report Generation (Step 2 of 2)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Select Students from: {{ $classSection->name }}
                        </h3>
                        <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:underline">
                            ← Back to Class Selection
                        </a>
                    </div>
                    
                    @if($students->isEmpty())
                        <p class="text-center text-gray-500 py-8">There are no students enrolled in this class.</p>
                    @else
                        <form action="{{ route('admin.reports.generate-bulk') }}" method="POST">
                            @csrf
                            <div class="border-t border-b border-gray-200 dark:border-gray-700 py-2">
                                <div class="flex items-center px-4">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900">
                                    <label for="select-all" class="ml-3 block text-sm font-medium text-gray-900 dark:text-gray-300">
                                        Select All / Deselect All
                                    </label>
                                </div>
                            </div>
                            
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($students as $student)
                                    <li class="px-4 py-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student-{{ $student->id }}" class="student-checkbox h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-900">
                                            <label for="student-{{ $student->id }}" class="ml-3 block text-sm text-gray-900 dark:text-gray-300">
                                                {{ $student->name }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Generate Selected Reports
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Add simple JS for the "Select All" checkbox --}}
    @push('scripts')
    <script>
        document.getElementById('select-all')?.addEventListener('change', function() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
    @endpush
</x-app-layout>