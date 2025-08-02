<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome Banner --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Here's a summary of your school's activity.</p>
                </div>
            </div>

            {{-- Stat Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Total Students Card --}}
                <x-stat-card title="Total Students" :value="\App\Models\User::where('role', 'student')->count()" color="blue">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a3.002 3.002 0 013.39-2.34M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z"></path>
                </x-stat-card>

                {{-- Total Teachers Card --}}
                <x-stat-card title="Total Teachers" :value="\App\Models\User::where('role', 'teacher')->count()" color="orange">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21v-1a6 6 0 00-5.176-5.973M15 21H9"></path>
                </x-stat-card>

                {{-- Total Classes Card --}}
                <x-stat-card title="Active Classes" :value="\App\Models\ClassSection::whereHas('academicSession', fn($q) => $q->where('is_current', true))->count()" color="green">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </x-stat-card>

                {{-- Total Subjects Card --}}
                <x-stat-card title="Total Subjects" :value="\App\Models\Subject::count()" color="pink">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </x-stat-card>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Quick Actions</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.users.create') }}" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <p class="font-semibold text-blue-600 dark:text-blue-400">Add New User</p>
                        </a>
                        <a href="{{ route('admin.classes.create') }}" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                             <p class="font-semibold text-green-600 dark:text-green-400">Add New Class</p>
                        </a>
                        <a href="{{ route('admin.enrollments.bulk-manage.show') }}" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                             <p class="font-semibold text-orange-600 dark:text-orange-400">Enroll Students</p>
                        </a>
                        <a href="{{ route('admin.final-reports.index') }}" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                             <p class="font-semibold text-pink-600 dark:text-pink-400">Generate Reports</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>