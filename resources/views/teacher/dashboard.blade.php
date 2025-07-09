{{--
|--------------------------------------------------------------------------
| Teacher Dashboard View
|--------------------------------------------------------------------------
|
| This view serves as the main landing page for users with the 'teacher' role.
| It displays a list of classes assigned to the currently logged-in teacher.
|
--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Your Assigned Classes</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Session</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled Students</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($classes as $class)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->subject->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->academicSession->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->students_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- === EDITED: This link now points to the correct grade entry route === --}}
                                            <a href="{{ route('teacher.grades.enter', $class) }}" class="text-indigo-600 hover:text-indigo-900">Enter Grades</a>
                                            {{-- =================================================================== --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">You are not currently assigned to any classes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination links --}}
                    <div class="mt-4">
                        {{ $classes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>