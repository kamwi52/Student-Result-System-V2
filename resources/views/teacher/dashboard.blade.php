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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Welcome, {{ Auth::user()->name }}!
                    </h3>
                    
                    <p class="mb-6">From here you can manage grades for the classes you are assigned to.</p>

                    {{-- Link to the New Bulk Grade Entry Feature --}}
                    <div class="mt-4">
                        <a href="{{ route('teacher.grades.bulk.create') }}"
                           class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold text-lg rounded-md hover:bg-blue-700 transition ease-in-out duration-150">
                            Enter Grades for a Class
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>