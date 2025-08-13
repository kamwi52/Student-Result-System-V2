<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Results - Step 2: Select Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                    <p class="text-sm text-gray-600 dark:text-gray-400">You are managing results for class:</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $classSection->name }}</p>
                </div>
                <a href="{{ route('teacher.results.manage.step1') }}" class="text-sm text-blue-600 hover:underline mb-6 inline-block">← Change Class</a>

                @if($assessments->isEmpty())
                    <p class="text-center text-gray-500 py-8">No assessments found for the subjects you teach in this class.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($assessments as $assessment)
                            <li class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $assessment->name }} ({{ $assessment->subject->name }})</p>
                                    <p class="text-xs text-gray-500">Date: {{ $assessment->assessment_date->format('Y-m-d') }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('teacher.results.import.show', $assessment) }}" class="px-3 py-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                                        Import
                                    </a>
                                    <a href="{{ route('teacher.results.enter', $assessment) }}" class="px-3 py-2 text-xs font-medium text-white bg-green-700 rounded-lg hover:bg-green-800">
                                        Enter Manually
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>