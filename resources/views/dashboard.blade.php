<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Students Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full mr-4">
                        {{-- Heroicon: academic-cap --}}
                        <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('role', 'student')->count() }}</p>
                    </div>
                </div>
                <!-- Teachers Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full mr-4">
                        {{-- Heroicon: user-group --}}
                         <svg class="h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m-7.5-2.962a3.75 3.75 0 1 0-5.214-5.214A3.75 3.75 0 0 0 10.5 7.5Zm-4.5 4.5a3.75 3.75 0 0 0 5.214 5.214A3.75 3.75 0 0 0 10.5 16.5Zm4.5-4.5a3.75 3.75 0 0 0-5.214-5.214A3.75 3.75 0 0 0 10.5 7.5Zm0 9a3.75 3.75 0 0 0 5.214-5.214A3.75 3.75 0 0 0 15 12Zm-9-9a3.75 3.75 0 0 0-5.214 5.214A3.75 3.75 0 0 0 6 12Zm0 9a3.75 3.75 0 0 0 5.214 5.214A3.75 3.75 0 0 0 6 12Z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Teachers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('role', 'teacher')->count() }}</p>
                    </div>
                </div>
                <!-- Subjects Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center">
                    <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full mr-4">
                        {{-- Heroicon: book-open --}}
                        <svg class="h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Subjects</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Subject::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Welcome to the School Result Management System dashboard! Use the "Admin Menu" to manage the application's data.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>