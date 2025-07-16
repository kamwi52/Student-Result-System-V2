<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Select an Assessment') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('teacher.gradebook.index') }}" class="text-sm text-blue-600 hover:underline">← Back to My Assignments</a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Class: {{ $assignment->classSection->name }}</h3>
                    <h4 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">Subject: {{ $assignment->subject->name }}</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($assessments as $assessment)
                            <a href="{{ route('teacher.gradebook.results', ['assignment' => $assignment->id, 'assessment' => $assessment->id]) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="font-bold text-lg">{{ $assessment->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Date: {{ \Carbon\Carbon::parse($assessment->assessment_date)->format('M d, Y') }}
                                </div>
                            </a>
                        @empty
                            <p>There are no assessments for this subject yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>