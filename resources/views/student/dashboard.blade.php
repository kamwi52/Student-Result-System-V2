<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Academic Performance</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assessment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($results as $result)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $result->classSection->name ?? 'N/A' }} - {{ $result->classSection->subject->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $result->assessment->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $result->score }} / {{ $result->assessment->max_marks ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $result->remarks ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No results have been recorded for you yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination links --}}
                    <div class="mt-4">
                        {{ $results->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>