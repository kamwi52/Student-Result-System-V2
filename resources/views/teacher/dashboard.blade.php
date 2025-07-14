<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Welcome, {{ Auth::user()->name }}!
                    </h3>
                    
                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        From here you can manage grades and view results for the classes you are assigned to.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="mt-4 flex flex-wrap gap-4">
                        {{-- Link to the Bulk Grade Entry Feature --}}
                        <a href="{{ route('teacher.grades.bulk.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Enter Grades (Grid View)
                        </a>
                        
                        {{-- === THE NEW LINK TO THE GRADEBOOK === --}}
                        <a href="{{ route('teacher.gradebook.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            View Gradebook (By Assessment)
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>