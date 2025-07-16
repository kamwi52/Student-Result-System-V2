<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Assignments - Select a Class & Subject') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($assignments as $assignment)
                            <a href="{{ route('teacher.gradebook.assessments', $assignment->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div class="font-bold text-lg">{{ $assignment->classSection->name }}</div>
                                <div class="text-base text-indigo-600 dark:text-indigo-400 font-semibold">
                                    Subject: {{ $assignment->subject->name }}
                                </div>
                            </a>
                        @empty
                            <p>You have not been assigned to teach any subjects yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>