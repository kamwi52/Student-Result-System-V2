<x-app-layout>
        <!-- START: Improved Ribbon-style Top Nav Bar with Dropdowns -->
    <div class="bg-gray-100 dark:bg-gray-800 shadow-sm sm:rounded-lg mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Your central hub for managing the entire system.</p>
                </div>
                <div class="flex items-center space-x-2">
                    
                    <!-- Management Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">
                            Management <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Manage Users</a>
                                <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Add New User</a>
                                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                                <a href="{{ route('admin.classes.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Manage Classes</a>
                                <a href="{{ route('admin.classes.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Create New Class</a>
                                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                                <a href="{{ route('admin.enrollments.bulk-manage.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Manage Enrollments</a>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold hover:bg-indigo-700 transition">
                            Settings <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('admin.academic-sessions.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Academic Sessions</a>
                                <a href="{{ route('admin.terms.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Manage Terms</a>
                                <a href="{{ route('admin.grading-scales.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Manage Grading Scales</a>
                            </div>
                        </div>
                    </div>

                    <!-- Reporting Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-md font-semibold hover:bg-purple-700 transition">
                            Reporting <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5" style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('admin.results.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Generate Report Cards</a>
                                <a href="{{ route('admin.results.ranked') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">View Ranked Results</a>
                            </div>
                        </div>
                    </div>

                    <!-- Downloads Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700 transition">
                            Downloads <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 right-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5" style="display: none;">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs text-gray-400">CSV Templates</div>
                                <a href="{{ route('admin.downloads.users-template') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Download Users CSV</a>
                                <a href="{{ route('admin.downloads.classes-template') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Download Classes CSV</a>
                                <a href="{{ route('admin.downloads.subjects-template') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Download Subjects CSV</a>
                                <a href="{{ route('admin.downloads.results-template') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Download Results CSV</a>
                                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>
                                <a href="{{ route('admin.downloads.user-guide') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Download User Guide (PDF)</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- END: Improved Ribbon-style Top Nav Bar -->

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

            <!-- START: Redesigned KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Total Students Card -->
                <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="block p-6 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition-transform transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white/20 mr-4">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Total Students</p>
                            <p class="text-3xl font-bold">{{ $studentCount }}</p>
                        </div>
                    </div>
                </a>

                <!-- Total Teachers Card -->
                <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}" class="block p-6 bg-indigo-500 text-white rounded-lg shadow-md hover:bg-indigo-600 transition-transform transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white/20 mr-4">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75V5a.75.75 0 0 1 .75-.75ZM12 18.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75v-.01a.75.75 0 0 1 .75-.75ZM7.5 12a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75Zm9 0a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75ZM7.5 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75Z" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Total Teachers</p>
                            <p class="text-3xl font-bold">{{ $teacherCount }}</p>
                        </div>
                    </div>
                </a>

                <!-- Total Classes Card -->
                <a href="{{ route('admin.classes.index') }}" class="block p-6 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition-transform transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white/20 mr-4">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6M9 15.75h6" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Total Classes</p>
                            <p class="text-3xl font-bold">{{ $classCount }}</p>
                        </div>
                    </div>
                </a>

                <!-- User Guide Card -->
                <a href="{{ route('admin.downloads.user-guide') }}" class="block p-6 bg-purple-500 text-white rounded-lg shadow-md hover:bg-purple-600 transition-transform transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white/20 mr-4">
                            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium">Need Help?</p>
                            <p class="text-xl font-bold">Read the Guide</p>
                        </div>
                    </div>
                </a>

            </div>
            <!-- END: Redesigned KPI Cards -->

            <!-- Split Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                



            </div>
        </div>
    </div>
    
    {{-- Chart-specific script has been removed as it's no longer needed --}}
</x-app-layout>