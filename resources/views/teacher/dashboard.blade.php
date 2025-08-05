<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            From here you can view your assignments and manage your gradebook.
                        </p>
                    </div>
                    {{-- === THIS IS THE NEW, PRIMARY BUTTON === --}}
                    <div>
                        <a href="{{ route('teacher.gradebook.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Go to Full Gradebook
                        </a>
                    </div>
                </div>
            </div>

            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-4">My Teaching Assignments</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse ($assignedClasses as $class)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $class->name }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                {{ $class->students_count }} {{ Str::plural('Student', $class->students_count) }}
                            </p>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h5 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">My Subjects in this Class:</h5>
                                <ul class="space-y-3">
                                    {{-- === FIX: The individual links have been removed === --}}
                                    {{-- The list of subjects is now for informational purposes only. --}}
                                    @foreach ($class->subjects as $subject)
                                        <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md">
                                            <span class="text-gray-900 dark:text-gray-100">{{ $subject->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                         <div class="p-6 text-center text-gray-500">
                            You are not currently assigned to any subjects. Please contact an administrator.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>