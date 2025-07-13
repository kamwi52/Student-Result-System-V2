<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Academic Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Welcome, {{ Auth::user()->name }}! Here are your results.
                    </h3>

                    @if($resultsBySession->isEmpty())
                        <p>Your results have not been published yet. Please check back later.</p>
                    @else
                        @foreach($resultsBySession as $sessionId => $results)
                            <div class="mt-6 mb-8">
                                {{-- Get session name from the first result in the group --}}
                                <h4 class="text-xl font-bold mb-4">
                                    {{ $results->first()->academicSession->name }}
                                </h4>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Subject
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Score (%)
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Grade
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Remarks
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($results as $result)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->subject->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->score }}</td>
                                                    
                                                    {{-- Placeholder for Grading Service --}}
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-gray-400 italic">Pending Grade</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-gray-400 italic">Pending Remarks</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-100 dark:bg-gray-900">
                                            <tr>
                                                <td class="px-6 py-4 font-bold">Session Average</td>
                                                <td class="px-6 py-4 font-bold">{{ number_format($results->avg('score'), 2) }}%</td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>