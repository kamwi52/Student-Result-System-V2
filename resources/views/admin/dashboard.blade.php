<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-semibold">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Here's a snapshot of your school's activity and some tools to get you started.
                    </p>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-500/20 mr-4"><svg class="w-8 h-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg></div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Students</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $studentCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-indigo-500/20 mr-4"><svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75V5a.75.75 0 0 1 .75-.75ZM12 18.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75v-.01a.75.75 0 0 1 .75-.75ZM7.5 12a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75Zm9 0a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75ZM7.5 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75Z" /></svg></div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Teachers</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $teacherCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-green-500/20 mr-4"><svg class="w-8 h-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6M9 15.75h6" /></svg></div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Classes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $classCount }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Split Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold text-lg mb-4">Students per Class</h3>
                        {!! $studentsPerClassChart->container() !!}
                    </div>
                </div>

                <!-- Right Column: Actions & Downloads -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.users.create') }}" class="flex items-center justify-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">Add New User</a>
                                <a href="{{ route('admin.classes.create') }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Create a New Class</a>
                                <a href="{{ route('admin.enrollments.bulk-manage.show') }}" class="flex items-center justify-center w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Manage Enrollments</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Download Templates Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-4">Download Templates</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Use these templates to prepare your data for bulk import.
                            </p>
                            <div class="space-y-3">
                                <a href="{{ route('admin.downloads.users-template') }}" class="flex items-center justify-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" /><path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" /></svg>
                                    Download Users CSV
                                </a>
                                <a href="{{ route('admin.downloads.classes-template') }}" class="flex items-center justify-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" /><path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" /></svg>
                                    Download Classes CSV
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        {!! $studentsPerClassChart->script() !!}
    @endpush
</x-app-layout>