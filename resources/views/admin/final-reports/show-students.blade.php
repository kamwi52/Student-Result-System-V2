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
                        <a href="{{ route('admin.final-reports.index') }}" class="text-sm text-blue-600 hover:underline">
                            ← Back to Selection
                        </a>
                    </div>
                    
                    @if($students->isEmpty())
                        <p class="text-center text-gray-500 py-8">There are no students enrolled in this class.</p>
                    @else
                        <form action="{{ route('admin.final-reports.generate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $classSection->id }}">
                            <input type="hidden" name="term_id" value="{{ $term->id }}">

                            <div class="border-t border-b py-2">
                                <div class="flex items-center px-4">
                                    <input type="checkbox" id="select-all">
                                    <label for="select-all" class="ml-3 block text-sm font-medium">Select All</label>
                                </div>
                            </div>
                            
                            <ul class="divide-y">
                                @foreach($students as $student)
                                    <li class="px-4 py-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox">
                                            <label class="ml-3 block text-sm">{{ $student->name }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border rounded-md font-semibold text-xs text-white uppercase">
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