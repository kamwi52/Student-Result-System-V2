<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Assessments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Action Buttons --}}
            <div class="flex items-center justify-end mb-4 space-x-2">
                {{-- === THIS IS THE NEW BUTTON === --}}
                <a href="{{ route('admin.assessments.bulk-create.show') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Bulk Create Assessments
                </a>
                
                <a href="{{ route('admin.assessments.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    Create Single Assessment
                </a>
            </div>

            <x-success-message />
            <x-error-message />

            {{-- Assessments Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Class</th>
                                <th scope="col" class="px-6 py-3">Subject</th>
                                <th scope="col" class="px-6 py-3">Term</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assessments as $assessment)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $assessment->name }}
                                    </th>
                                    <td class="px-6 py-4">{{ optional($assessment->classSection)->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ optional($assessment->subject)->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ optional($assessment->term)->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $assessment->assessment_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center space-x-4">
                                            <a href="{{ route('admin.assessments.edit', $assessment->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <form method="POST" action="{{ route('admin.assessments.destroy', $assessment->id) }}" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b dark:bg-gray-800">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No assessments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $assessments->links() }}
            </div>

        </div>
    </div>
</x-app-layout>