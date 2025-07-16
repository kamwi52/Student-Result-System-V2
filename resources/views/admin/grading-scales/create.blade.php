<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Grading Scale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <x-validation-errors class="mb-4" />
                <form method="POST" action="{{ route('admin.grading-scales.store') }}">
                    @csrf
                    <!-- Scale Name -->
                    <div class="mb-6">
                        <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Scale Name</label>
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="e.g., Standard A-F Scale" />
                    </div>

                    <!-- Grade Definitions -->
                    <h3 class="text-lg font-medium border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">Grade Definitions</h3>
                    <div id="grades-container" class="mt-4 space-y-4">
                        {{-- JS will insert rows here --}}
                    </div>
                    <button type="button" id="add-grade-btn" class="mt-4 px-3 py-1.5 text-sm bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                        + Add Grade
                    </button>
                    
                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.grading-scales.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 mr-4">Cancel</a>
                        <x-primary-button>Create Scale</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- === THE CHANGE: Use @section instead of @push === --}}
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('grades-container');
        const addBtn = document.getElementById('add-grade-btn');
        let gradeIndex = 0;

        function createGradeRow() {
            const index = gradeIndex++;
            const row = document.createElement('div');
            row.className = 'grid grid-cols-12 gap-x-4 items-center grade-row';
            row.innerHTML = `
                <div class="col-span-3">
                    <label class="text-xs text-gray-600 dark:text-gray-400">Grade</label>
                    <input type="text" name="grades[${index}][grade_name]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm" placeholder="e.g., A+" required>
                </div>
                <div class="col-span-3">
                    <label class="text-xs text-gray-600 dark:text-gray-400">Min Score %</label>
                    <input type="number" name="grades[${index}][min_score]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm" placeholder="90" required>
                </div>
                <div class="col-span-3">
                    <label class="text-xs text-gray-600 dark:text-gray-400">Max Score %</label>
                    <input type="number" name="grades[${index}][max_score]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm" placeholder="100" required>
                </div>
                <div class="col-span-2">
                    <label class="text-xs text-gray-600 dark:text-gray-400">Remark</label>
                    <input type="text" name="grades[${index}][remark]" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm" placeholder="Excellent">
                </div>
                <div class="col-span-1 flex items-end">
                    <button type="button" class="remove-grade-btn p-2 text-red-500 hover:text-red-700" title="Remove Grade">×</button>
                </div>
            `;
            container.appendChild(row);
        }

        addBtn.addEventListener('click', createGradeRow);

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-grade-btn')) {
                e.target.closest('.grade-row').remove();
            }
        });

        // Add one empty row to start with.
        createGradeRow(); 
    });
</script>
@endsection