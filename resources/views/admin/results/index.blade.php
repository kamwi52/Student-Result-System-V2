<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Results') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.results.import.show') }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    Import Results
                </a>
                <a href="{{ route('admin.results.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Add New Result
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- This block will display detailed import errors --}}
            @if(session('import_errors'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Import Failed!</strong>
                    <span class="block sm:inline">Please correct the following errors in your CSV file and try again:</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessment</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($results as $result)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $result->student->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $result->classSection->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $result->assessment->title ?? 'N/A' }} ({{ $result->assessment->subject->name ?? '' }})</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $result->score }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Future actions like edit/delete can go here --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                            No results found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>