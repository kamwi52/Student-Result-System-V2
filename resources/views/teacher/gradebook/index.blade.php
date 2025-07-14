<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gradebook - Step 1: Select a Class') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($classes as $class)
                            {{-- This link now goes to the new assessments page --}}
                            <a href="{{ route('teacher.gradebook.assessments', $class->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="font-bold text-lg">{{ $class->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Subjects: {{ $class->subjects->pluck('name')->join(', ') ?: 'No subjects assigned' }}
                                </div>
                            </a>
                        @empty
                            <p>You are not currently assigned to any classes.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>