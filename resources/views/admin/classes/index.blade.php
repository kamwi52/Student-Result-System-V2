<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Classes') }}
            </h2>
            <div>
                <a href="{{ route('admin.classes.import.show') }}" class="px-4 py-2 mr-2 bg-gray-600 text-white font-semibold rounded-md hover:bg-gray-700">
                    Import Classes
                </a>
                <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                    Add New Class
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-success-message />
            {{-- ... (your error display logic) ... --}}
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Class Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Teacher</th>
                                    {{-- === NEW: Table Header for the count === --}}
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase">Enrolled Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Subjects Taught</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($classes as $class)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $class->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $class->teacher->name ?? 'N/A' }}</td>
                                        {{-- === NEW: Table Data cell for the count === --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center font-bold">{{ $class->students_count }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse($class->subjects as $subject)
                                                    <span class="px-2 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">
                                                        {{ $subject->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-500">No subjects assigned</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.classes.enroll.index', $class->id) }}" class="text-green-600 hover:text-green-900 mr-4 font-bold">Enroll</a>
                                            <a href="{{ route('admin.classes.edit', $class->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                            <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $classes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>