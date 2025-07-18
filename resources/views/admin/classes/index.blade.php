<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Classes') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.classes.import.show') }}">
                    <x-secondary-button>
                        {{ __('Import Classes') }}
                    </x-secondary-button>
                </a>
                <a href="{{ route('admin.classes.create') }}">
                    <x-primary-button>
                        {{ __('Add New Class') }}
                    </x-primary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS/ERROR MESSAGES --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    
                                    {{-- === FIX: Removed whitespace-nowrap from Academic Session Header === --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Session</th>
                                    
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Students</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects & Assigned Teachers</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($classes as $class)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $class->name }}</td>
                                        
                                        {{-- === FIX: Removed whitespace-nowrap from Academic Session Data Cell === --}}
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $class->academicSession->name ?? 'N/A' }}</td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $class->students_count }}
                                        </td>
                                        
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($class->subjects as $subject)
                                                    <span class="inline-flex items-center bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                        {{ $subject->name }}
                                                        @if ($subject->pivot->teacher_id)
                                                            <span class="font-semibold ml-1">({{ \App\Models\User::find($subject->pivot->teacher_id)->name ?? 'N/A' }})</span>
                                                        @endif
                                                    </span>
                                                @empty
                                                    <span class="text-xs italic text-gray-400">No subjects assigned</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center space-x-3">
                                                <a href="{{ route('admin.classes.enroll.index', $class) }}" class="font-semibold text-blue-600 hover:text-blue-900">
                                                    Enroll
                                                </a>

                                                <a href="{{ route('admin.classes.edit', $class) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </a>
                                                
                                                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION LINKS --}}
                    <div class="mt-4">
                        {{ $classes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>