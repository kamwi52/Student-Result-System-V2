<x-app-flowbite-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Classes') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Top Bar with Search and Add Button --}}
            <div class="flex flex-col sm:flex-row items-center justify-between mb-4">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('admin.classes.index') }}" class="w-full sm:w-1/2">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Search by Class Name..." value="{{ request('search') }}">
                    </div>
                </form>
                {{-- Action Buttons --}}
                <div class="flex items-center mt-3 sm:mt-0 sm:ml-4 space-x-2">
                    {{-- === MODIFIED SECTION: IMPORT BUTTON ADDED === --}}
                    <a href="{{ route('admin.classes.import.show') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:ring-blue-800">
                        Import Classes
                    </a>
                    <a href="{{ route('admin.classes.create') }}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Add New Class
                    </a>
                </div>
            </div>

            {{-- Display Messages --}}
            <x-success-message />
            <x-error-message />

            {{-- Classes Table --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Academic Session</th>
                            <th scope="col" class="px-6 py-3">Enrolled Students</th>
                            <th scope="col" class="px-6 py-3">Subjects & Teachers</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $class->name }}</th>
                                <td class="px-6 py-4">{{ $class->academicSession->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $class->students_count }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($class->subjects as $subject)
                                            <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                {{ $subject->name }}
                                                @if ($subject->pivot->teacher_id)
                                                    <span class="font-semibold ml-1">({{ \App\Models\User::find($subject->pivot->teacher_id)->name ?? 'N/A' }})</span>
                                                @endif
                                            </span>
                                        @empty
                                            <span class="text-xs italic text-gray-400 dark:text-gray-500">No subjects assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    {{-- NEW ACTION BUTTONS --}}
                                    <div class="flex justify-end items-center space-x-4">
                                        {{-- Link to the powerful Edit page --}}
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit & Assign</a>
                                        {{-- Link to the Enrollment page --}}
                                        <a href="{{ route('admin.classes.enroll.index', $class) }}" class="font-medium text-green-600 dark:text-green-500 hover:underline">Enroll Students</a>
                                        {{-- Standard Delete Form --}}
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b dark:bg-gray-800">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No classes found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{-- This makes the pagination links work with the search query --}}
                {{ $classes->withQueryString()->links() }}
            </div>

        </div>
    </div>
</x-app-flowbite-layout>