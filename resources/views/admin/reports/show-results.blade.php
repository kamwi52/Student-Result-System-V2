<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Results for {{ $assessment->name }} ({{ $assessment->subject->name }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Detailed Results</h3>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.reports.show-assessments', $assessment->subject->classSections->first()) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs uppercase shadow-sm">
                                Back to Assessments
                            </a>

                            {{-- Form to generate reports for ALL students on this page --}}
                            <form action="{{ route('admin.reports.generate-bulk') }}" method="POST">
                                @csrf
                                @foreach ($results as $result)
                                    {{-- Ensure the value is the user ID --}}
                                    <input type="hidden" name="student_ids[]" value="{{ $result->student->user_id }}">
                                @endforeach
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-green-700">
                                    GENERATE ALL REPORTS
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Table of results --}}
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        {{-- Table headers and body just like your teacher view --}}
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $result)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->student->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->score ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->gradingScale->remark ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-8 text-gray-500">No results found for this assessment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>